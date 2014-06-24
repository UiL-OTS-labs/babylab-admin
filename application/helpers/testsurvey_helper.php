<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/////////////////////////
// Table
/////////////////////////

if (!function_exists('create_testsurvey_table'))
{
	/** Creates the table with testsurvey data */
	function create_testsurvey_table($id = NULL)
	{
		$CI =& get_instance();
		base_table($id);
		$heading = array(lang('limesurvey_id'), lang('whensent'), lang('actions'));
		if (empty($id)) array_unshift($heading, lang('test'));
		$CI->table->set_heading($heading);
	}
}

/////////////////////////
// Links
/////////////////////////

if (!function_exists('testsurvey_get_link'))
{
	/** Returns the get link for a testsurvey */
	function testsurvey_get_link($testsurvey)
	{
		return anchor('testsurvey/get/' . $testsurvey->id, testsurvey_name($testsurvey));
	}
}

if (!function_exists('testsurvey_get_link_by_id'))
{
	/** Returns the get link for a testsurvey */
	function testsurvey_get_link_by_id($testsurvey_id)
	{
		$CI =& get_instance();
		$testsurvey = $CI->testSurveyModel->get_testsurvey_by_id($testsurvey_id);

		return testsurvey_get_link($testsurvey);
	}
}

if (!function_exists('testsurvey_actions'))
{
	/** Possible actions for a testsurvey: edit, view scores, delete */
	function testsurvey_actions($testsurvey_id)
	{
		$inspect_link = anchor('testsurvey/get/' . $testsurvey_id, img_zoom('testsurvey'));
		$find_link = anchor('testsurvey/find/' . $testsurvey_id, img_email());
		$edit_link = anchor('testsurvey/edit/' . $testsurvey_id, img_edit());
		$delete_link = anchor('testsurvey/delete/' . $testsurvey_id, img_delete(), warning(lang('sure_delete_testsurvey')));
			
		return implode(' ', array($inspect_link, $find_link, $edit_link, $delete_link));
	}
}

if (!function_exists('testsurvey_participant_actions'))
{
	/** Possible actions for a testsurvey/participant: invite */
	function testsurvey_participant_actions($testsurvey_id, $participant_id)
	{
		$find_link = anchor('testinvite/invite/' . $testsurvey_id . '/' . $participant_id, img_email());
		
		return implode(' ', array($find_link));
	}
}

/////////////////////////
// Names
/////////////////////////

if (!function_exists('testsurvey_when'))
{
	/** Returns an option list of testsurveys */
	function testsurvey_when($whensent, $whennr)
	{
		return implode(' ', array(lang('after'), sprintf('%2d', $whennr), lcfirst(lang($whensent))));
	}
}

if (!function_exists('testsurvey_name'))
{
	/** Return the name for a testsurvey */
	function testsurvey_name($testsurvey)
	{
		$CI =& get_instance();
		$test = $CI->testModel->get_test_by_id($testsurvey->test_id);
		return implode(' ', array($test->name, testsurvey_when($testsurvey->whensent, $testsurvey->whennr)));
	}
}

/////////////////////////
// Survey links
/////////////////////////

if (!function_exists('survey_by_id'))
{
	/** Returns the URL to the survey */
	function survey_by_id($survey_id)
	{
		return anchor('http://survey.hum.uu.nl/admin/admin.php?sid=' . $survey_id, $survey_id, 'target="_blank"');
	}
}

if (!function_exists('survey_link'))
{
	/** Returns the URL to the survey */
	function survey_link($survey_id, $token)
	{
		$link = 'http://survey.hum.uu.nl/index.php?sid=' . $survey_id . '&token=' . $token;
		return anchor($link, $link, 'target="_blank"');
	}
}

/////////////////////////
// Options
/////////////////////////

if (!function_exists('testsurvey_options'))
{
	/** Returns an option list of testsurveys */
	function testsurvey_options($testsurveys)
	{
		$t_options = array();
		foreach ($testsurveys as $t)
		{
			$t_options[$t->id] = testsurvey_name($t);
		}
		asort($t_options);
		return $t_options;
	}
}

if (!function_exists('testsurvey_whensent_options'))
{
	/** Returns an option list of testsurveys */
	function testsurvey_whensent_options()
	{
		return array(TestWhenSent::Participation => lcfirst(lang(TestWhenSent::Participation)),
			TestWhenSent::Months => lang(TestWhenSent::Months));
	}
}
