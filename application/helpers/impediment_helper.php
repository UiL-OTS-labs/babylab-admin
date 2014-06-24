<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/////////////////////////
// Table-related
/////////////////////////

if (!function_exists('create_impediment_table'))
{
	/** Creates the table with impediment data */
	function create_impediment_table($id = NULL)
	{
		$CI =& get_instance();
		base_table($id);
		$CI->table->set_heading(lang('participant'), lang('impediment'), lang('comment'), lang('actions'));
	}
}

if (!function_exists('impediment_dates'))
{
	/** Gets the impediment dates in readable format */
	function impediment_dates($impediment)
	{
		return output_date($impediment->from) . ' - ' . output_date($impediment->to);
	}
}

if (!function_exists('impediment_dates_by_id'))
{
	/** Gets the impediment dates in readable format */
	function impediment_dates_by_id($impediment_id)
	{
		$CI =& get_instance();
		$impediment = $CI->impedimentModel->get_impediment_by_id($impediment_id);
		
		return impediment_dates($impediment);
	}
}

if (!function_exists('impediment_actions'))
{
	/** Possible actions for an impediment: delete */
	function impediment_actions($impediment_id)
	{
		$d_link = anchor('impediment/delete/' . $impediment_id, img_delete(), warning(lang('sure_delete_imp')));
		return $d_link;
	}
}

if (!function_exists('impediment_past_url'))
{
	/** The "past" url for an impediment */
	function impediment_past_url($include_past)
	{
		$include_past_url = array('url' => 'impediment/index/1', 'title' => lang('include_past'));
		$dont_include_past_url = array(	'url' => 'impediment/index/0', 'title' => lang('not_include_past'));
		
		return $include_past ? $dont_include_past_url : $include_past_url;
	}
}