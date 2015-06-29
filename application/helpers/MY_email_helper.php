<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('email_testinvite'))
{
	function email_testinvite($participant, $testinvite, $auto = FALSE, $concept = FALSE)
	{
		$CI =& get_instance();
		$test = $CI->testInviteModel->get_test_by_testinvite($testinvite);
		$template = $CI->testTemplateModel->get_testtemplate_by_test($test->id, L::Dutch); // TODO: set to current language?
		$email = $concept ? TO_EMAIL_OVERRIDE : $participant->email;

		$message_args = array(
				"participant" => $participant,
				"testinvite" => $testinvite,
				"auto" => $auto
			);
		$message = email_replace($template->template, $message_args);

		$CI->mail->prepare();
		$CI->mail->to($participant->email);
		$CI->mail->subject('Uitnoding voor vragenlijst');
		$CI->mail->message($message);
		$CI->mail->send();
		
		return sprintf(lang('testinvite_added'), name($participant), $test->name, $email);
	}
}

if (!function_exists('email_replace'))
{
	/**
	 * This method creates an e-mail by referring to a view and replacing the variables. 
	 * @param String view 		The view to use
	 * @param Language language
	 * @param array 	all optional fields.
	 * participant, participation, experiment, 
	 * testinvite, comb_experiment, 
	 * auto, message, language
	 */
	function email_replace($view, $a)
	{
		// $view, 
		$CI =& get_instance();
		$user = $CI->userModel->get_user_by_id(current_user_id());

		$language = array_key_exists('language', $a) ? $a['language'] : L::Dutch;
		reset_language($language);

		$comb_experiment = array_key_exists('comb_experiment', $a) ? $a['comb_experiment'] : False;
		
		$message_data = array();
		$message_data['auto'] 				= array_key_exists('auto', $a) ? $a['auto'] : False;
		$message_data['message'] 			= array_key_exists('message', $a) ? $a['message'] : "";
		$message_data['combination']		= FALSE;
		$message_data['survey_link']		= FALSE;

		if($user)
		{
			$message_data['user_username'] 	= $user->username;
			$message_data['user_email'] 	= $user->email;
		}
		
		if (array_key_exists('participant', $a)) 
		{
			$participant = $a['participant'];
			$message_data['name']			= name($participant);
			$message_data['name_first']		= $participant->firstname;
			$message_data['name_parent']	= parent_name($participant);
			$message_data['gender']			= gender_child($participant->gender);
			$message_data['gender_pos']		= gender_pos($participant->gender);
			$message_data['gender_plural']	= gender_sex($participant->gender) . 's';
			$message_data['phone']			= $participant->phone;
		}

		if (array_key_exists('participation', $a)) 
		{
			$participation = $a['participation'];
			$message_data['appointment']	= format_datetime($participation->appointment);
		}
		
		if (array_key_exists('experiment', $a)) 
		{
			$experiment = $a['experiment'];
			$location = $CI->locationModel->get_location_by_experiment($experiment);
			
			$message_data['exp_name']		= $experiment->name;
			$message_data['type'] 			= $experiment->type;
			$message_data['duration'] 		= $experiment->duration;
			$message_data['duration_total'] = $experiment->duration + INSTRUCTION_DURATION;
			$message_data['description'] 	= $experiment->description;
			$message_data['location'] 		= sprintf('%s (%s)', $location->name, $location->roomnumber);
			$message_data['caller_contacts'] = extract_callers($experiment, $comb_experiment);
		}
		
		if ($comb_experiment) 
		{
			$location = $CI->locationModel->get_location_by_experiment($comb_experiment);
			$comb_participation = $CI->participationModel->get_participation($comb_experiment->id, $participant->id);
			
			$message_data['combination']		= TRUE;
			$message_data['comb_exp_name']		= $comb_experiment->name;
			$message_data['comb_type'] 			= $comb_experiment->type;
			$message_data['comb_duration'] 		= $comb_experiment->duration;
			$message_data['comb_duration_total']= $comb_experiment->duration + INSTRUCTION_DURATION;
			$message_data['comb_description'] 	= $comb_experiment->description;
			$message_data['comb_location'] 		= sprintf('%s (%s)', $location->name, $location->roomnumber);
			$message_data['comb_appointment']	= format_datetime($comb_participation->appointment);
		}
		
		if (array_key_exists('participant', $a) && array_key_exists('experiment', $a)) 
		{
			$data = get_min_max_days($participant, $experiment);
			
			$message_data['min_date'] 		= format_date($data['min_date_js']);
			$message_data['max_date'] 		= format_date($data['max_date_js']);
		}
		
		if (array_key_exists('testinvite', $a) && $a['testinvite']) 
		{
			$testinvite = $a['testinvite'];
			$testsurvey = $CI->testInviteModel->get_testsurvey_by_testinvite($testinvite);
			
			$message_data['survey_name']	= testsurvey_name($testsurvey);
			$message_data['survey_link'] 	= survey_link($testsurvey->limesurvey_id, $testinvite->token);
			$message_data['whennr'] 		= $testsurvey->whennr;
		}
		
		return $CI->load->view($view, $message_data, TRUE);
	}
}

if (!function_exists('extract_callers'))
{
    /**
     * Returns the unique set of callers for an experiment and it's combination experiment.
     */
    function extract_callers($experiment, $comb_experiment)
    {
		$CI =& get_instance();
        $experiment_ids = array($experiment->id);
        if ($comb_experiment)
        {
            array_push($experiment_ids, $comb_experiment->id);
        }
        
        $users = $CI->callerModel->get_caller_users_by_experiments($experiment_ids);
        $contacts = array();
        foreach ($users as $user)
        {
            array_push($contacts, sprintf('%s %s, %s', 
                $user->firstname, $user->lastname, $user->phone ? $user->phone : $user->mobile)); 
        }
        
        return $contacts;
    }
}