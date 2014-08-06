<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('email_testinvite'))
{
	function email_testinvite($participant, $testinvite)
	{
		$CI =& get_instance();
		$test = $CI->testInviteModel->get_test_by_testinvite($testinvite);
		$template = $CI->testTemplateModel->get_testtemplate_by_test($test->id, L::Dutch); // TODO: set to current language?

		$template = file_get_contents($template->template);
		$message = email_replace($template, $participant, NULL, NULL, $testinvite);

		$CI->email->clear();
		$CI->email->from(FROM_EMAIL, FROM_EMAIL_NAME);
		$CI->email->to(EMAIL_DEV_MODE ? TO_EMAIL_OVERRIDE : $participant->email);
		$CI->email->subject('Babylab Utrecht: Uitnoding voor vragenlijst');
		$CI->email->message($message);
		$CI->email->send();
		
		return sprintf(lang('testinvite_added'), name($participant), $test->name);
	}
}

if (!function_exists('email_replace'))
{
	function email_replace($message, $participant = NULL, $participation = NULL, $experiment = NULL, $testinvite = NULL)
	{
		$CI =& get_instance();
		
		$replacements = array();
		
		if (!empty($participant)) 
		{
			$replacements['name']			= name($participant);
			$replacements['name_first']		= $participant->firstname;
			$replacements['name_parent']	= parent_name($participant);
			$replacements['gender']			= gender_child($participant->gender);
			$replacements['gender_pos']		= gender_pos($participant->gender);
			$replacements['phone']			= $participant->phone;
		}

		if (!empty($participation)) 
		{
			$replacements['appointment']	= output_datetime_email($participation->appointment);
		}
		
		if (!empty($experiment)) 
		{
			$replacements['type'] 			= $experiment->type;
			$replacements['duration'] 		= $experiment->duration;
			$replacements['duration_total'] = $experiment->duration + INSTRUCTION_DURATION;
			$replacements['description'] 	= $experiment->description;
		}
		
		if (!empty($participant) && !empty($experiment)) 
		{
			$data = get_min_max_days($participant, $experiment);
			$replacements['min_date'] 		= $data['min_date'];
			$replacements['max_date'] 		= $data['max_date'];
		}
		
		if (!empty($testinvite)) 
		{
			$testsurvey = $CI->testInviteModel->get_testsurvey_by_testinvite($testinvite);
			$replacements['survey_link'] 	= survey_link($testsurvey->limesurvey_id, $testinvite->token);
			$replacements['whennr'] 		= $testsurvey->whennr;
		}

		// Start the replacement (recursively)!
		foreach ($replacements as $k => $v) 
		{
			$message = str_replace('{{' . $k . '}}', $v, $message);
		}
		
		return $message;
	}
}