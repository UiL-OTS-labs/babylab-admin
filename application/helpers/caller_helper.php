<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/////////////////////////
// Table-related
/////////////////////////

if (!function_exists('create_caller_table'))
{
	/** Creates the table with caller data */
	function create_caller_table($id = NULL)
	{
		$CI =& get_instance();
		base_table($id);
		$heading = array(lang('caller'), lang('actions'));
		if (empty($id)) array_unshift($heading, lang('experiment'));
		$CI->table->set_heading($heading);
	}
}

if (!function_exists('caller_actions'))
{
	/** Possible actions for a caller: delete */
	function caller_actions($caller_id)
	{
		return anchor('caller/delete/' . $caller_id, img_delete(), warning(lang('sure_delete_caller')));
	}
}

/////////////////////////
// Options
/////////////////////////

if (!function_exists('caller_options'))
{
	/** Returns an option list of callers */
	function caller_options($callers)
	{
		$c_options = array();
		foreach ($callers as $c)
		{
			$c_options[$c->id] = $c->username;
		}
		asort($c_options);
		return $c_options;
	}
}
