<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/////////////////////////
// Table-related
/////////////////////////

if (!function_exists('create_availability_table'))
{
	/** Creates the table with availability data */
	function create_availability_table($id = NULL)
	{
		$CI =& get_instance();
		base_table($id);
		$CI->table->set_heading(lang('availability'), lang('comment'), lang('actions'));
	}
}

if (!function_exists('availability_dates'))
{
	/** Gets the availability dates in readable format */
	function availability_dates($availability)
	{
		return output_datetime($availability->from) . ' - ' . output_datetime($availability->to);
	}
}

if (!function_exists('availability_dates_by_id'))
{
	/** Gets the availability dates in readable format */
	function availability_dates_by_id($availability_id)
	{
		$CI =& get_instance();
		$availability = $CI->availabilityModel->get_availability_by_id($availability_id);

		return availability_dates($availability);
	}
}

if (!function_exists('availability_actions'))
{
	/** Possible actions for an availability: delete */
	function availability_actions($availability_id)
	{
		$d_link = anchor('availability/delete/' . $availability_id, img_delete(), warning(lang('sure_delete_imp')));
		return $d_link;
	}
}

if (!function_exists('availability_past_url'))
{
	/** The "past" url for an availability */
	function availability_past_url($include_past)
	{
		$include_past_url = array('url' => 'availability/index/1', 'title' => lang('availability_include_past'));
		$dont_include_past_url = array(	'url' => 'availability/index/0', 'title' => lang('availability_not_include_past'));

		return $include_past ? $dont_include_past_url : $include_past_url;
	}
}