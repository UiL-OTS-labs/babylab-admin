<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/////////////////////////
// Table
/////////////////////////

if (!function_exists('create_testinvite_table'))
{

	/** Creates the table with testinvite data */
	function create_testinvite_table($id = NULL)
	{
		$CI = & get_instance();
		base_table($id);
		$heading = array(lang('participant'), lang('token'), lang('datesent'), lang('datecompleted'), lang('datereminder'), lang('datemanualreminder'), lang('actions'));
		if (empty($id))
			array_unshift($heading, lang('testsurvey'));
		$CI->table->set_heading($heading);
	}

}

if (!function_exists('create_testinvite_participant_table'))
{

	/** Creates the table with testinvite data for a participant */
	function create_testinvite_participant_table($id = NULL)
	{
		$CI = & get_instance();
		base_table($id);
		$heading = array(lang('testsurvey'), lang('token'), lang('datesent'), lang('datecompleted'), lang('datereminder'), lang('datemanualreminder'), lang('actions'));
		$CI->table->set_heading($heading);
	}

}

if (!function_exists('create_testinvite_experiment_table'))
{

	/** Creates the table with testinvite data for an experiment */
	function create_testinvite_experiment_table($id = NULL)
	{
		$CI = & get_instance();
		base_table($id);
		$heading = array(lang('testsurvey'), lang('participant'), lang('token'), lang('datesent'), lang('datecompleted'), lang('datereminder'), lang('datemanualreminder'), lang('actions'));
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
		$CI = & get_instance();
		$testinvite = $CI->testSurveyModel->get_testinvite_by_id($testinvite_id);

		return testinvite_get_link($testinvite);
	}

}

if (!function_exists('testinvite_actions'))
{

	/** Possible actions for a testinvite: edit, view scores, delete */
	function testinvite_actions($testinvite_id)
	{
		$CI = & get_instance();
		$testinvite = $CI->testInviteModel->get_testinvite_by_id($testinvite_id);
		$scores = $CI->scoreModel->get_scores_by_testinvite($testinvite_id);

		$reminder_available = !$testinvite->datecompleted && $testinvite->datereminder;
		$r_link = anchor('testinvite/manual_reminder/' . $testinvite_id, img_email(lang('manual_reminder'), FALSE));
		$reminder_link = $reminder_available ? $r_link : img_email('', TRUE);

		// By default, the score link is an opaque image
		$score_link = img_scores(TRUE);
		if ($testinvite->datecompleted)
		{
			// If we copied and processed the scores to our database, show them there
			if ($scores)
			{
				$score_link = anchor('score/testinvite/' . $testinvite_id, img_scores());
			}
			// Otherwise, return a link to the raw scores in LimeSurvey
			else if (!SURVEY_DEV_MODE)
			{
				$score_link = anchor(survey_results_link($testinvite_id), img_scores());
			}
		}

		$delete_link = anchor('testinvite/delete/' . $testinvite_id, img_delete(), warning(lang('sure_delete_testinvite')));

		if (is_caller())
			$actions = array($reminder_link, $delete_link);
		if (is_leader())
			$actions = array($score_link, $reminder_link);
		if (is_admin())
			$actions = array($score_link, $reminder_link, $delete_link);
		return implode(' ', $actions);
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

if (!function_exists('survey_results_link'))
{

	/** Returns the link to the results in LimeSurvey */
	function survey_results_link($testinvite_id)
	{
		if (!SURVEY_DEV_MODE)
		{
			$CI = & get_instance();
			$testinvite = $CI->testInviteModel->get_testinvite_by_id($testinvite_id);
			$testsurvey = $CI->testInviteModel->get_testsurvey_by_testinvite($testinvite);

			$CI->load->model('surveyModel');
			$result = $CI->surveyModel->get_result_by_token($testsurvey->limesurvey_id, $testinvite->token);

			$url = LS_BASEURL . '/admin.php?action=browse&sid=';
			$url .= $testsurvey->limesurvey_id . '&subaction=id&id=' . $result->id;
			return $url;
		}
	}

}
