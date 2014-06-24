<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/////////////////////////
// Table
/////////////////////////

if (!function_exists('create_score_table'))
{
	/** Creates the table with score data */
	function create_score_table($id = NULL, $by_item = '')
	{
		$CI =& get_instance();
		base_table($id);
		$heading = array(lang('score'), lang('date'), lang('age'), lang('actions'));
		switch ($by_item)
		{
			case 'testsurvey' 	: array_unshift($heading, lang('testcat'), lang('participant')); break;
			case 'testcat' 		: array_unshift($heading, lang('testsurvey'), lang('participant')); break;
			case 'participant'	: array_unshift($heading, lang('testsurvey'), lang('testcat')); break;
			case 'testinvite'	: array_unshift($heading, lang('testcat')); break;
			default				: array_unshift($heading, lang('testsurvey'), lang('testcat'), lang('participant')); break;
		}
		$CI->table->set_heading($heading);
	}
}

if (!function_exists('create_total_score_table'))
{
	/** Creates the table with score data, but only totals (for root test categories) */
	function create_total_score_table($testcats, $testinvites)
	{
		$CI =& get_instance();

		base_table();
		$CI->table->set_heading(lang('participant'), lang('score'), lang('date'), lang('age'), lang('percentile'), lang('language_age'), lang('actions'));

		foreach ($testcats as $tc)
		{
			foreach ($testinvites as $ti)
			{
				$score = $CI->testCatModel->total_score($tc->id, $ti->id);
				$t = $CI->testCatModel->get_test_by_testcat($tc);

				if ($score->score > 0)
				{
					$p = $CI->testInviteModel->get_participant_by_testinvite($ti);
					
					$score_age = age_in_months($p, $score->date);
					$p_link = participant_get_link($p);
					$percentile = $CI->percentileModel->find_percentile($tc->id, $p->gender, $score_age, $score->score);
					$language_age = $CI->percentileModel->find_50percentile_age($tc->id, $p->gender, $score->score);
					$edit_link = anchor('score/edit_all/' . $t->id . '/' . $p->id, img_edit());
					$actions = implode(' ', array($edit_link));

					$CI->table->add_row($p_link, $score->score, output_date($score->date), $score_age, $percentile, $language_age, $actions);
				}
			}
		}

		return $CI->table->generate();
	}
}

/////////////////////////
// NCDI
/////////////////////////

if (!function_exists('create_ncdi_score_array'))
{
	/** Creates the table with NCDI score date */
	function create_ncdi_score_array($test, $testinvite)
	{
		$CI =& get_instance();
		
		$testcats = $CI->testCatModel->get_testcats_by_test($test->id, TRUE);
		$result = array();
		foreach ($testcats as $tc)
		{
			$score = $CI->testCatModel->total_score($tc->id, $testinvite->id);
			$t = $CI->testCatModel->get_test_by_testcat($tc);

			if ($score->score > 0)
			{
				$participant = $CI->testInviteModel->get_participant_by_testinvite($testinvite);
				
				$score_age = age_in_months($participant, $score->date);
				$percentile = $CI->percentileModel->find_percentile($tc->id, $participant->gender, $score_age, $score->score);
				$language_age = $CI->percentileModel->find_50percentile_age($tc->id, $participant->gender, $score->score);

				array_push($result, array(
					'code' 			=> $tc->code, 
					'name' 			=> $tc->name, 
					'score' 		=> $score->score, 
					'percentile' 	=> $percentile, 
					'age' 			=> $language_age));
			}
		}

		return $result;
	}
}

if (!function_exists('create_ncdi_table'))
{
	/** Creates the table with NCDI score date */
	function create_ncdi_table($scores)
	{
		$CI =& get_instance();

		$tmpl = array (
				'table_open'	=> '<table class="pure-table">'
		);

		$CI->table->set_template($tmpl);
		$CI->table->set_empty("&nbsp;");
		$CI->table->set_heading(lang('testcat'), lang('raw_score'), lang('percentile'), lang('language_age'));

		foreach ($scores as $score)
		{
			$CI->table->add_row($score['name'], $score['score'], $score['percentile'], $score['age'] . ' ' . lang('months'));
		}

		return $CI->table->generate();
	}
}

/////////////////////////
// Links
/////////////////////////

if (!function_exists('score_actions'))
{
	/** Possible actions for a score: edit, delete */
	function score_actions($score_id)
	{
		$edit_link = anchor('score/edit/' . $score_id, img_edit());
		$delete_link = anchor('score/delete/' . $score_id, img_delete(), warning(lang('sure_delete_score')));

		return implode(' ', array($edit_link, $delete_link));
	}
}
