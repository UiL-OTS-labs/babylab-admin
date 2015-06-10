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
					'age' 			=> $language_age, 
					'score_age' 	=> $score_age));
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


if (!function_exists('scores_to_csv'))
{
	/** Creates a .csv-file from a table of scores (testinvite_id -> score) */
	function scores_to_csv($test_code, $score_table, $experiment_id = NULL)
	{
		$CI =& get_instance();

		// Retrieve the headers
		$headers = array(lang('reference_number'), lang('gender'), lang('age'), 
			lang('age_md'), lang('dyslexicparent'), lang('multilingual'));

		if ($experiment_id)
		{
			array_unshift($headers, lang('part_number'));
		}
		
		// Add test categories
		$test = $CI->testModel->get_test_by_code($test_code);
		$testcats = $CI->testCatModel->get_testcats_by_test($test->id, FALSE, TRUE);
		foreach ($testcats as $testcat)
		{
			$headers[] = $testcat->code . ' - ' . $testcat->name;
		}
		
		// For N-CDI: add parent test categories
		if ($test_code == 'ncdi_wz') 
		{
			$parent_testcats = $CI->testCatModel->get_testcats_by_test($test->id, TRUE);
			foreach ($parent_testcats as $parent)
			{
				$headers[] = $parent->name . ' - ' . lang('raw_score');
				$headers[] = $parent->name . ' - ' . lang('percentile');
				$headers[] = $parent->name . ' - ' . lang('language_age');
			}
		}
		
		// Add headers to the csv array (later used in fputscsv)
		$csv_array = array();
		$csv_array[] = $headers;
		
		// Generate array for each row and put in total array
		foreach ($score_table as $testinvite_id => $scores)
		{			
			$testinvite = $CI->testInviteModel->get_testinvite_by_id($testinvite_id);
			$participant = $CI->testInviteModel->get_participant_by_testinvite($testinvite);
			
			// Participant data
			$refnr = reference_number($participant);
			$g = $participant->gender;
			$age = age_in_months($participant, $testinvite->datecompleted);
			$agemd = age_in_months_and_days($participant->dateofbirth, $testinvite->datecompleted);
			$d = $participant->dyslexicparent ? $participant->dyslexicparent : lang('no');
			$m = $participant->multilingual ? lang('yes') : lang('no');

			$csv_row = array($refnr, $g, $age, $agemd, $d, $m);
			if ($experiment_id) 
			{
				$participation = $CI->participationModel->get_participation($experiment_id, $participant->id);
				array_unshift($csv_row, $participation->part_number);
			}
			
			// Score data
			foreach ($testcats as $testcat)
			{
				if(isset($scores[$testcat->id]))
				{
					array_push($csv_row, $scores[$testcat->id]);
				}
			}
			
			// For N-CDI: total score data
			if ($test_code == 'ncdi_wz') 
			{
				$totals = create_ncdi_score_array($test, $testinvite);
				foreach ($totals as $total)
				{
					array_push($csv_row, $total['score'], $total['percentile'], $total['age']);
				}
			}
			
			// Add row to csv array
			$csv_array[] = $csv_row;
		}
		
		// Create a new output stream and capture the result in a new object
		$fp = fopen('php://output', 'w');
		ob_start();
		
		// Create a new row in the CSV file for every in the array
		foreach ($csv_array as $row)
		{
			fputcsv($fp, $row, ';');
		}
		
		// Capture the output as a string
		$csv = ob_get_contents();
		
		// Close the object and the stream
		ob_end_clean();
		fclose($fp);

		return $csv;
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
