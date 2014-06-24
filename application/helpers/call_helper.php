<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/////////////////////////
// Table
/////////////////////////

if (!function_exists('create_call_table'))
{
	/** Creates the table with call data */
	function create_call_table($id = NULL, $by_user = FALSE)
	{
		$CI =& get_instance();
		base_table($id);
		$heading = array(lang('participant'), lang('experiment'), lang('action'), lang('order'), lang('start_call'), lang('end_call'), lang('actions'));
		if (empty($id) && !$by_user) array_unshift($heading, lang('caller'));
		$CI->table->set_heading($heading);
	}
}

/////////////////////////
// Links
/////////////////////////

if (!function_exists('call_get_link'))
{
	/** Returns the get link for a call */
	function call_get_link($call)
	{
		return anchor('call/get/' . $call->id, $call->name);
	}
}

if (!function_exists('call_get_link_by_id'))
{
	/** Returns the get link for a call */
	function call_get_link_by_id($call_id)
	{
		$CI =& get_instance();
		$call = $CI->callModel->get_call_by_id($call_id);

		return call_get_link($call);
	}
}

if (!function_exists('call_actions'))
{
	/** Possible actions for a call: edit, view scores, delete */
	function call_actions($call_id)
	{
		$delete_link = anchor('call/delete/' . $call_id, img_delete(), warning(lang('sure_delete_call')));
			
		return implode(' ', array($delete_link));
	}
}
