<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('email_testinvite'))
{
	function email_testinvite($participant, $testinvite, $auto = FALSE, $concept = FALSE)
	{
		$CI =& get_instance();
		$test = $CI->testInviteModel->get_test_by_testinvite($testinvite);
		$template = $CI->testTemplateModel->get_testtemplate_by_test($test->id, L::Dutch); // TODO: set to current language?
		$email = $concept ? TO_EMAIL_OVERRIDE : $participant->email;

		$message = email_replace($template->template, $participant, NULL, NULL, $testinvite, NULL, $auto);

		$CI->email->clear();
		$CI->email->from(FROM_EMAIL, FROM_EMAIL_NAME);
		$CI->email->to(in_development() ? TO_EMAIL_OVERRIDE : $email);
		$CI->email->subject('Babylab Utrecht: Uitnoding voor vragenlijst');
		$CI->email->message($message);
		$CI->email->send();
		
		return sprintf(lang('testinvite_added'), name($participant), $test->name, $email);
	}
}

if (!function_exists('email_replace'))
{
	/**
	 * This method creates an e-mail by referring to a view and replacing the variables. 
	 * TODO: refactor to use less parameters (all in one array)?
	 */
	function email_replace($view, $participant = NULL, $participation = NULL, $experiment = NULL, 
		$testinvite = NULL, $comb_experiment = NULL, $auto = FALSE, $message = "", $language = L::Dutch)
	{
		$CI =& get_instance();
		$user = $CI->userModel->get_user_by_id(current_user_id());

		reset_language($language);
		
		$message_data = array();
		$message_data['auto'] 				= $auto;
		$message_data['message'] 			= $message;
		$message_data['combination']		= FALSE;
		$message_data['longitudinal']		= FALSE;
		$message_data['survey_link']		= FALSE;

		if ($user)
		{
			$message_data['user_username'] 	= $user->username;
			$message_data['user_email'] 	= $user->email;
		}
		
		if ($participant) 
		{
			$message_data['name']			= name($participant);
			$message_data['name_first']		= $participant->firstname;
			$message_data['name_parent']	= parent_name($participant);
			$message_data['gender']			= gender_child($participant->gender);
			$message_data['gender_pos']		= gender_pos($participant->gender);
			$message_data['gender_plural']	= gender_sex($participant->gender) . 's';
			$message_data['phone']			= $participant->phone;
		}

		if ($participation) 
		{
			$leader = $CI->userModel->get_user_by_id($participation->user_id_leader); 
			
			$message_data['appointment']	= format_datetime($participation->appointment);
			$message_data['leader_name']	= $leader->firstname;
		}
		
		if ($experiment) 
		{
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
			$relation = $CI->relationModel->get_relation_by_experiments($experiment->id, $comb_experiment->id);

			$message_data['combination']		= $relation->relation === RelationType::Combination;
			$message_data['longitudinal']		= $relation->relation === RelationType::Prerequisite;
			$message_data['comb_exp_name']		= $comb_experiment->name;
			$message_data['comb_type'] 			= $comb_experiment->type;
			$message_data['comb_duration'] 		= $comb_experiment->duration;
			$message_data['comb_duration_total']= $comb_experiment->duration + INSTRUCTION_DURATION;
			$message_data['comb_description'] 	= $comb_experiment->description;
			$message_data['comb_location'] 		= sprintf('%s (%s)', $location->name, $location->roomnumber);
			$message_data['comb_appointment']	= format_datetime($comb_participation->appointment);
		}
		
		if ($participant && $experiment) 
		{
			$data = get_min_max_days($participant, $experiment);
			
			$message_data['min_date'] 		= format_date($data['min_date_js']);
			$message_data['max_date'] 		= format_date($data['max_date_js']);
		}
		
		if ($testinvite) 
		{
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