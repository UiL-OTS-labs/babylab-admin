<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/////////////////////////
// Table-related
/////////////////////////

if (!function_exists('create_experiment_table'))
{
	/** Creates the table with experiment data */
	function create_experiment_table($id = NULL)
	{
		$CI =& get_instance();
		base_table($id);
		$CI->table->set_heading(lang('experiment'), lang('age_range'), lang('dyslexic'), lang('multilingual'), lang('callers'), lang('leaders'), lang('actions'));
	}
}

/////////////////////////
// Experiment-related
/////////////////////////

if (!function_exists('is_risk'))
{
	/** Returns whether the experiment contains a risk/control group. */
	function is_risk($experiment)
	{
		return $experiment->dyslexic || $experiment->multilingual;
	}
}

if (!function_exists('is_archived'))
{
	/** Returns whether the experiment is archived. */
	function is_archived($experiment)
	{
		return $experiment->archived;
	}
}

if (!function_exists('age_range'))
{
	/** Returns the age range for an experiment. */
	function age_range($experiment)
	{
		return $experiment->agefrommonths . ';' . $experiment->agefromdays . ' - ' .
		$experiment->agetomonths . ';' . $experiment->agetodays;
	}
}

if (!function_exists('age_range_by_id'))
{
	/** Returns the age range for an experiment. */
	function age_range_by_id($experiment_id)
	{
		$CI =& get_instance();
		$experiment = $CI->experimentModel->get_experiment_by_id($experiment_id);

		return age_range($experiment);
	}
}

if (!function_exists('get_foreground_color'))
{
	/**
	 * Function to calculate the text color off of the
	 * brightness of the background color
	 * 
	 * Based on: 
	 * http://themergency.com/calculate-text-color-based-on-background-color-brightness/
	 * 
	 * @param String $bgColor
	 */
	function get_foreground_color($color)
	{
		$R = hexdec(substr($color, 1,2));
		$G = hexdec(substr($color,3,4));
		$B = hexdec(substr($color,5,6));
		
		// Calculate a brightness bases on a weighted sum
		$brightness = sqrt($R * $R * .241 + $G * $G * .691 + $B * $B * 0.068);
		
		// Return either light or dark color
		return $brightness < 130 ? "#ffffff" : "#000000";
	}
}

if (!function_exists('get_colored_label'))
{
	/**
	 * Generates a label that shows the experiment
	 * label color with the name inside
	 * @param Experiment $experiment
	 */
	function get_colored_label($experiment)
	{
		$bg = $experiment->experiment_color;
		$color = get_foreground_color($bg);
		
		$label = "<div class=\'legend experiment-color\' style=\'background-color: ";
		$label .= $bg;
		$label .= "; color: ";
		$label .= $color;
		$label .= ";\'>";
		$label .= $experiment->name;
		$label .= "</div>";
		
		return $label;
	}
}

/////////////////////////
// Links
/////////////////////////

if (!function_exists('experiment_get_link'))
{
	/** Returns the get link for an experiment */
	function experiment_get_link($experiment)
	{
		return current_role() === UserRole::Caller ? $experiment->name : anchor('experiment/get/' . $experiment->id, $experiment->name);
	}
}

if (!function_exists('experiment_get_link_by_id'))
{
	/** Returns the get link for an experiment */
	function experiment_get_link_by_id($experiment_id)
	{
		$CI =& get_instance();
		$experiment = $CI->experimentModel->get_experiment_by_id($experiment_id);

		return experiment_get_link($experiment);
	}
}

if (!function_exists('experiment_caller_link'))
{
	/** Returns the leader link for an experiment */
	function experiment_caller_link($experiment_id)
	{
		$CI =& get_instance();
		$nr_callers = $CI->callerModel->count_callers($experiment_id);
		$callers_link = (is_admin() ? anchor('caller/experiment/' . $experiment_id, $nr_callers) : $nr_callers);

		return $nr_callers > 0 ? $callers_link : '-';
	}
}

if (!function_exists('experiment_leader_link'))
{
	/** Returns the leader link for an experiment */
	function experiment_leader_link($experiment_id)
	{
		$CI =& get_instance();
		$nr_leaders = $CI->leaderModel->count_leaders($experiment_id);
		$leaders_link = (is_admin() ? anchor('leader/experiment/' . $experiment_id, $nr_leaders) : $nr_leaders);

		return $nr_leaders > 0 ? $leaders_link : '-';
	}
}

if (!function_exists('experiment_actions'))
{
	/** Possible actions for an experiment: edit, view participants, call, archive, delete */
	function experiment_actions($experiment_id)
	{
		$CI =& get_instance();
		$e = $CI->experimentModel->get_experiment_by_id($experiment_id);
		$is_leader = is_leader() && $CI->leaderModel->is_leader_for_experiment(current_user_id(), $experiment_id);

		$nr_participants = count($CI->participationModel->get_participations_by_experiment($e->id));
		$part_link = $nr_participants > 0 ? anchor('participation/experiment/' . $e->id, img_participations($nr_participants)) : img_participations($nr_participants);
		$call_link = anchor('participant/find/' . $e->id, img_call());
		$edit_link = !is_archived($e) && ($is_leader || is_admin()) ? anchor('experiment/edit/' . $e->id, img_edit()) : img_edit(TRUE);
		$archive_link = !is_archived($e) ? anchor('experiment/archive/' . $e->id, img(array('src' => 'images/folder.png', 'title' => lang('archive')))) : anchor('experiment/unarchive/' . $e->id, img(array('src' => 'images/folder_go.png', 'title' => lang('activate'))));

		switch (current_role())
		{
			case UserRole::Admin:
				$actions = array($edit_link, $part_link, $call_link, $archive_link);
				break;
			case UserRole::Leader:
				$actions = array($edit_link, $part_link);
				break;
			default:
				$actions = array($call_link, $part_link);
				break;
		}
		return implode(' ', $actions);
	}
}

if (!function_exists('experiment_archive_link'))
{
	/** Possible actions for an experiment: edit, view participants, call, archive, delete */
	function experiment_archive_link($include_archived)
	{
		$show_archive_url = array(
				'url' 	=> 'experiment/show_archive',
				'title'	=> lang('show_archived_exps')
		);
		$not_show_archive_url = array(
				'url' 	=> 'experiment',
				'title'	=> lang('not_show_archived_exps')
		);
		return $include_archived ? $not_show_archive_url : $show_archive_url;
	}
}

/////////////////////////
// Options
/////////////////////////

if (!function_exists('experiment_options'))
{
	/** Returns an option list of tests */
	function experiment_options($experiments)
	{
		$e_options = array();
		foreach ($experiments as $e)
		{
			$e_options[$e->id] = $e->name;
		}
		asort($e_options);
		return $e_options;
	}
}