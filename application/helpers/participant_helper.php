<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/////////////////////////
// Table
/////////////////////////

if (!function_exists('create_participant_table'))
{
	/** Creates the table with participant data */
	function create_participant_table($id = NULL, $find = FALSE)
	{
		$CI =& get_instance();
		base_table($id);
		$heading = array(lang('name'), lang('dob'), lang('dyslexicparent'), lang('multilingual'), lang('phone'), lang('actions'));
		if ($find)
		{
			array_splice($heading, -1, 1, array(lang('status'), lang('actions')));
		}
		$CI->table->set_heading($heading);
	}
}

/////////////////////////
// Participant-related
/////////////////////////

if (!function_exists('name'))
{
	/** Returns the full name of the participant */
	function name($participant)
	{
		return $participant->firstname . ' ' . $participant->lastname;
	}
}

if (!function_exists('parent_name'))
{
	/** Returns the full name of the parent of the participant */
	function parent_name($participant)
	{
		return $participant->parentfirstname . ' ' . $participant->parentlastname;
	}
}

if (!function_exists('gender'))
{
	/** Returns the gender of the participant in html */
	function gender($gender)
	{
		if (strcasecmp($gender, Gender::Male) == 0)
		{
			return '<font color="blue">&#9794</font>';
		}
		else if (strcasecmp($gender, Gender::Female) == 0)
		{
			return '<font color="pink">&#9792</font>';
		}
		else
		{
			return lang('none');
		}
	}
}

if (!function_exists('gender_sex'))
{
	/** Returns the gender, sex form */
	function gender_sex($gender)
	{
		switch ($gender)
		{
			case Gender::Male: 		$result = lang('boy');		break;
			case Gender::Female: 	$result = lang('girl');		break;
			default: 				$result = lang('none');		break;
		}
		return strtolower($result);
	}
}

if (!function_exists('gender_child'))
{
	/** Returns the gender, child form */
	function gender_child($gender)
	{
		switch ($gender)
		{
			case Gender::Male: 		$result = lang('son');		break;
			case Gender::Female: 	$result = lang('daughter');	break;
			default: 				$result = lang('none');		break;
		}
		return strtolower($result);
	}
}

if (!function_exists('gender_pos'))
{
	/** Returns the gender, possessive form */
	function gender_pos($gender)
	{
		switch ($gender)
		{
			case Gender::Male: 		$result = lang('his');		break;
			case Gender::Female: 	$result = lang('her');		break;
			default: 				$result = lang('none');		break;
		}
		return strtolower($result);
	}
}

if (!function_exists('gender_parent'))
{
	/** Returns the gender, parental form */
	function gender_parent($gender)
	{
		switch ($gender)
		{
			case Gender::Male: 		$result = lang('father');	break;
			case Gender::Female: 	$result = lang('mother');	break;
			case Gender::Both: 		$result = lang('both'); 	break;
			default: 				$result = lang('none');		break;
		}
		return $result;
	}
}

if (!function_exists('dob'))
{
	/** Returns the date of birth of the participant */
	function dob($dateofbirth)
	{
		return output_date($dateofbirth);
	}
}

if (!function_exists('birthweight'))
{
	/** Returns the birthweight of the participant */
	function birthweight($participant)
	{
		return $participant->birthweight . ' g';
	}
}

if (!function_exists('pregnancy'))
{
	/** Returns the pregnancy dureation of the participant */
	function pregnancy($participant)
	{
		return $participant->pregnancyweeks . ' ' . lang('months') . '; ' . $participant->pregnancydays . ' ' . lang('days');
	}
}

if (!function_exists('age_in_months'))
{
	/** Returns the age in months of the participant given an optional date
	 *  Code copied from http://stackoverflow.com/questions/3324513/php-how-to-calculate-person-age-in-months
	 *  Note that %r prints the signum */
	function age_in_months($participant, $date = '')
	{
		$diff = date_diff(new DateTime($participant->dateofbirth), new DateTime($date));

		return intval($diff->format('%r') . ($diff->format('%m') + 12 * $diff->format('%y')));
	}
}

if (!function_exists('age_in_md_by_id'))
{
	/** Returns the age in months of the participant */
	function age_in_md_by_id($participant_id, $date)
	{
		if (!$date) return '';

		$CI =& get_instance();
		$participant = $CI->participantModel->get_participant_by_id($participant_id);

		return age_in_months_and_days($participant, $date);
	}
}

if (!function_exists('age_in_months_and_days'))
{
	/** Returns the age in months and days (m;d) of the participant given an optional date
	 *  Code copied from http://stackoverflow.com/questions/3324513/php-how-to-calculate-person-age-in-months
	 *  Note that %r prints the signum */
	function age_in_months_and_days($participant, $date = '')
	{
		$diff = date_diff(new DateTime($participant->dateofbirth), new DateTime($date));

		return $diff->format('%r') . ($diff->format('%m') + 12 * $diff->format('%y')) . ';' . $diff->format('%d');
	}
}

if (!function_exists('age_in_ymd'))
{
	/** Returns the age in months and days (y;m;d) of the participant given an optional date
	 *  Code copied from http://stackoverflow.com/questions/3324513/php-how-to-calculate-person-age-in-months
	 *  Note that %r prints the signum */
	function age_in_ymd($participant, $date = '')
	{
		$diff = date_diff(new DateTime($participant->dateofbirth), new DateTime($date));
		$y = '%y ' . strtolower(lang('year')) . '; ';
		$m = '%m ' . ($diff->format('%m') == 1 ? strtolower(lang('month')) : lang('months')) . '; ';
		$d = '%d ' . ($diff->format('%d') == 1 ? strtolower(lang('day')) : lang('days'));

		return $diff->format('%r') . $diff->format($y . $m . $d);
	}
}

if (!function_exists('last_called'))
{
	/** Returns the time last called for a participant */
	function last_called($participant_id, $experiment_id)
	{
		$result = '';

		$CI =& get_instance();
		$participation = $CI->participationModel->get_participation($experiment_id, $participant_id);
		if (!empty($participation))
		{
			$call = $CI->callModel->last_call($participation->id);
			if (!empty($call) && !empty($call->timeend))
			{
				$result = '<abbr title="' . format_datetime($call->timeend) . '">';
				$result .= lang($call->status);
				$result .= '</abbr>';
			}
		}

		return $result;
	}
}

/////////////////////////
// Links
/////////////////////////

if (!function_exists('participant_get_link'))
{
	/** Returns the get link for a participant */
	function participant_get_link($participant)
	{
		return anchor('participant/get/' . $participant->id, name($participant));
	}
}

if (!function_exists('participant_get_link_by_id'))
{
	/** Returns the get link for a participant */
	function participant_get_link_by_id($participant_id)
	{
		$CI =& get_instance();
		$participant = $CI->participantModel->get_participant_by_id($participant_id);

		return participant_get_link($participant);
	}
}

if (!function_exists('participant_edit_link'))
{
	/** Returns the get link for a participant */
	function participant_edit_link($participant_id)
	{
		return anchor('participant/edit/' . $participant_id, lang('here'), array('target' => '_blank'));
	}
}

if (!function_exists('participant_activate_link'))
{
	/** Returns the activation link for a participant */
	function participant_activate_link($participant, $text = '')
	{
		if (!$participant->activated) 
		{
			if ($participant->deactivated_reason == DeactivateReason::NewParticipant)
			{
				return anchor('participant/activate/' . $participant->id, $text ? $text : img_active(FALSE));
			}
			else 
			{
				return $text ? $text : img_active(FALSE);
			}
		}
		else 
		{
			return anchor('participant/deactivate/' . $participant->id, $text ? $text : img_active(TRUE));
		}
	}
}

if (!function_exists('participant_impediment_link'))
{
	/** Returns the activation link for a participant */
	function participant_impediment_link($participant_id)
	{
		$CI =& get_instance();
		$imp = $CI->impedimentModel->next_impediment_by_participant($participant_id);
		return isset($imp) ? anchor('impediment/participant/' . $participant_id, impediment_dates($imp)) : '-';
	}
}

if (!function_exists('participant_actions'))
{
	/** Possible actions for a participant: edit, activate, comments, scores */
	function participant_actions($participant_id)
	{
		$CI =& get_instance();
		$pp = $CI->participantModel->get_participant_by_id($participant_id);

		$edit_link = anchor('participant/edit/' . $participant_id, img_edit());
		$act_link = participant_activate_link($pp);

		$nr_comments = count($CI->commentModel->get_comments_by_participant($participant_id));
		$com_link = $nr_comments > 0 ? anchor('comment/participant/' . $participant_id, img_comments($nr_comments)) : img_comments();

		$nr_scores = count($CI->scoreModel->get_scores_by_participant($participant_id));
		$score_link = $nr_scores > 0 ? anchor('score/participant/' . $participant_id, img_scores()) : img_scores(TRUE);

		return is_admin()
		? implode(' ', array($edit_link, $act_link, $com_link, $score_link))
		: implode(' ', array($edit_link, $act_link, $com_link));
	}
}

if (!function_exists('participant_call_actions'))
{
	/** Possible actions for calling a participant: call, comments */
	function participant_call_actions($participant_id, $experiment_id, $weeks_ahead = WEEKS_AHEAD)
	{
		$CI =& get_instance();
		$pp = $CI->participantModel->get_participant_by_id($participant_id);

		$call_link = img_call_p($pp, $experiment_id, $weeks_ahead);

		$nr_comments = count($CI->commentModel->get_comments_by_participant($participant_id));
		$com_link = $nr_comments > 0 ? anchor('comment/participant/' . $participant_id, img_comments($nr_comments)) : img_comments();

		return implode(' ', array($call_link, $com_link));
	}
}

/////////////////////////
// Options
/////////////////////////

if (!function_exists('participant_options'))
{
	/** Returns an option list of participants */
	function participant_options($participants)
	{
		$p_options = array();
		foreach ($participants as $p)
		{
			$p_options[$p->id] = name($p);
		}
		asort($p_options);
		return $p_options;
	}
}
