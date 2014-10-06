<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/////////////////////////
// Table-related
/////////////////////////

if (!function_exists('create_leader_table'))
{
	/** Creates the table with leader data */
	function create_leader_table($id = NULL)
	{
		$CI =& get_instance();
		base_table($id);
		$heading = array(lang('leader'), lang('actions'));
		if (empty($id)) array_unshift($heading, lang('experiment'));
		$CI->table->set_heading($heading);
	}
}

if (!function_exists('leader_actions'))
{
	/** Possible actions for a leader: delete */
	function leader_actions($leader_id)
	{
		return anchor('leader/delete/' . $leader_id, img_delete(), warning(lang('sure_delete_leader')));
	}
}

/////////////////////////
// Options
/////////////////////////

if (!function_exists('leader_options'))
{
	/** Returns an option list of leaders */
	function leader_options($leaders)
	{
		$l_options = array();
		foreach ($leaders as $l)
		{
			$l_options[$l->id] = $l->username;
		}
		asort($l_options);
		return $l_options;
	}
}
