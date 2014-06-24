<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/////////////////////////
// Table-related
/////////////////////////

if (!function_exists('create_participation_table'))
{
	/** Creates the table with participation data */
	function create_participation_table($id = NULL)
	{
		$CI =& get_instance();
		base_table($id);
		$CI->table->set_heading(lang('participant'), lang('experiment'), lang('appointment'), lang('cancelled_short'), lang('no_show'), lang('completed'), lang('actions'));
	}
}

if (!function_exists('create_no_show_table'))
{
	/** Creates the table with participation data */
	function create_no_show_table($participations)
	{
		if (empty($participations)) return lang('no_results_found');

		$CI =& get_instance();

		base_table();
		$CI->table->set_heading(lang('participant'), lang('no_shows'), lang('actions'));

		foreach ($participations as $pp)
		{
			$participant = $CI->participantModel->get_participant_by_id($pp->participant_id);

			$p_link = participant_get_link($participant);
			$act_link = participant_activate_link($participant);

			$CI->table->add_row($p_link, $pp->noshows, $act_link);
		}

		return $CI->table->generate();
	}
}

if (!function_exists('participation_actions'))
{
	/** Possible actions for a participation: prioritize and delete */
	function participation_actions($participation_id)
	{
		$CI =& get_instance();
		$pp = $CI->participationModel->get_participation_by_id($participation_id);

		$is_cancelled = $pp->cancelled == 0;
		$is_planned = $pp->confirmed == 1 && $pp->cancelled == 0 && $pp->noshow == 0 && $pp->completed == 0;
		$is_confirmed = $pp->confirmed == 1 && $pp->cancelled == 0;
		$is_noshow = $pp->noshow == 1;
		$is_completed = $pp->completed == 1;

		$get_link = participation_get_link($pp);
		$cancel_link = $is_cancelled ? anchor('participation/cancel/' . $pp->id . '/0', img_cancel(lang('cancelled'))) : img_cancel(lang('cancelled'), TRUE);
		$reschedule_link = $is_planned ? anchor('participation/reschedule/' . $pp->id, img_calendar()) : img_calendar(TRUE);
		$noshow_link = $is_confirmed && !$is_noshow ? anchor('participation/no_show/' . $pp->id, img_noshow()) : img_noshow(TRUE);
		$completed_link = $is_confirmed && !$is_completed ? anchor('participation/completed/' . $pp->id, img_accept(lang('completed'))) : img_accept(lang('completed'), TRUE);
		$delete_link = anchor('participation/delete/' . $pp->id, img_delete(), warning(lang('sure_delete_part')));

		switch (current_role())
		{
			case UserRole::Admin:
				$actions = array($get_link, $cancel_link, $reschedule_link, $noshow_link, $completed_link, $delete_link);
				break;
			case UserRole::Leader:
				$actions = array($get_link, $cancel_link, $noshow_link, $completed_link);
				break;
			default:
				$actions = array($get_link, $cancel_link, $reschedule_link);
				break;
		}
		return implode(' ', $actions);
	}
}

/////////////////////////
// Helpers
/////////////////////////

if (!function_exists('get_min_max_days'))
{
	function get_min_max_days($participant, $experiment)
	{
		$min = $participant->dateofbirth . '+' . $experiment->agefrommonths . ' months +' . $experiment->agefromdays . ' days';
		$max = $participant->dateofbirth . '+' . $experiment->agetomonths . ' months +' . $experiment->agetodays . ' days';
		$data['min_date'] = output_date($min);
		$data['max_date'] = output_date($max);
		$data['min_date_js'] = output_date($min, TRUE);
		$data['max_date_js'] = output_date($max, TRUE);

		return $data;
	}
}

/////////////////////////
// Links
/////////////////////////

if (!function_exists('participation_get_link'))
{
	/** Returns the get link for a participation */
	function participation_get_link($participation)
	{
		return anchor('participation/get/' . $participation->id, img_zoom('participation'));
	}
}

if (!function_exists('participation_get_link_by_id'))
{
	/** Returns the get link for a participant */
	function participation_get_link_by_id($participant_id)
	{
		$CI =& get_instance();
		$participation = $this->participationModel->get_participation_by_id($participation_id);

		return participation_get_link($participation);
	}
}