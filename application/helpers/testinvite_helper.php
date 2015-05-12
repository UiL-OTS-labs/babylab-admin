<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/////////////////////////
// Table
/////////////////////////

if (!function_exists('create_testinvite_table'))
{
	/** Creates the table with testinvite data */
	function create_testinvite_table($id = NULL)
	{
		$CI =& get_instance();
		base_table($id);
		$heading = array(lang('participant'), lang('token'), lang('datesent'), lang('datecompleted'), lang('datereminder'), lang('actions'));
		if (empty($id)) array_unshift($heading, lang('testsurvey'));
		$CI->table->set_heading($heading);
	}
}

if (!function_exists('create_testinvite_participant_table'))
{
	/** Creates the table with testinvite data for a participant */
	function create_testinvite_participant_table($id = NULL)
	{
		$CI =& get_instance();
		base_table($id);
		$heading = array(lang('testsurvey'), lang('token'), lang('datesent'), lang('datecompleted'), lang('datereminder'), lang('actions'));
		$CI->table->set_heading($heading);
	}
}

if (!function_exists('create_testinvite_experiment_table'))
{
	/** Creates the table with testinvite data for an experiment */
	function create_testinvite_experiment_table($id = NULL)
	{
		$CI =& get_instance();
		base_table($id);
		$heading = array(lang('testsurvey'), lang('participant'), lang('token'), lang('datesent'), lang('datecompleted'), lang('datereminder'), lang('actions'));
		$CI->table->set_heading($heading);
	}
}

/////////////////////////
// Links
/////////////////////////

if (!function_exists('testinvite_get_link'))
{
	/** Returns the get link for a testinvite */
	function testinvite_get_link($testinvite)
	{
		return anchor('testinvite/get/' . $testinvite->id, $testinvite->name);
	}
}

if (!function_exists('testinvite_get_link_by_id'))
{
	/** Returns the get link for a testinvite */
	function testinvite_get_link_by_id($testinvite_id)
	{
		$CI =& get_instance();
		$testinvite = $CI->testSurveyModel->get_testinvite_by_id($testinvite_id);

		return testinvite_get_link($testinvite);
	}
}

if (!function_exists('testinvite_actions'))
{
	/** Possible actions for a testinvite: edit, view scores, delete */
	function testinvite_actions($testinvite_id)
	{
		if (!is_admin()) return img_scores(TRUE); 

		$CI =& get_instance();
		$testinvite = $CI->testInviteModel->get_testinvite_by_id($testinvite_id);
		$scores = $CI->scoreModel->get_scores_by_testinvite($testinvite_id);

		$reminder_available = !$testinvite->datecompleted && $testinvite->datereminder;
		$reminder_link = anchor('testinvite/manual_reminder/' . $testinvite_id, img_email(lang('manual_reminder'), FALSE));

		$score_link = anchor('score/testinvite/' . $testinvite_id, img_scores(empty($scores)));
		$reminder_link = $reminder_available ? $reminder_link : img_email('', TRUE);
		$delete_link = anchor('testinvite/delete/' . $testinvite_id, img_delete(), warning(lang('sure_delete_testinvite')));
			
		return implode(' ', array($score_link, $reminder_link, $delete_link));
	}
}

if (!function_exists('testinvite_results_link'))
{
	/** Possible actions for a testinvite: edit, view scores, delete */
	function testinvite_results_link($testinvite_id, $token)
	{
		return anchor('test/results/' . $testinvite_id, $token);
	}
}
