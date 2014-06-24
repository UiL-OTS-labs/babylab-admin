<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/////////////////////////
// Table
/////////////////////////

if (!function_exists('create_location_table'))
{
	/** Creates the table with location data */
	function create_location_table($id = NULL)
	{
		$CI =& get_instance();
		base_table($id);
		$CI->table->set_heading(lang('name'), lang('roomnumber'), lang('actions'));
	}
}

/////////////////////////
// Links
/////////////////////////

if (!function_exists('location_get_link'))
{
	/** Returns the get link for a location */
	function location_get_link($location)
	{
		return anchor('location/get/' . $location->id, $location->name);
	}
}

if (!function_exists('location_get_link_by_id'))
{
	/** Returns the get link for a location */
	function location_get_link_by_id($location_id)
	{
		$CI =& get_instance();
		$location = $CI->locationModel->get_location_by_id($location_id);
		
		return location_get_link($location);
	}
}

if (!function_exists('location_name'))
{
	/** Returns the get link for a location */
	function location_name($location_id)
	{
		$CI =& get_instance();
		$location = $CI->locationModel->get_location_by_id($location_id);
		
		return $location->name . ' (' . $location->roomnumber . ')';
	}
}

if (!function_exists('location_actions'))
{
	/** Possible actions for an experiment: edit, view participants, call, archive, delete */
	function location_actions($location_id)
	{
		$edit_link = anchor('location/edit/' . $location_id, img_edit());
		$delete_link = anchor('location/delete/' . $location_id, img_delete(), warning(lang('sure_delete_location')));
			
		return implode(' ', array($edit_link, $delete_link));
	}
}

/////////////////////////
// Options
/////////////////////////

if (!function_exists('location_options'))
{
	/** Returns an option list of locations */
	function location_options($locations)
	{
		$t_options = array();
		foreach ($locations as $t)
		{
			$t_options[$t->id] = $t->name . ' (' . $t->roomnumber . ')';
		}
		asort($t_options);
		return $t_options;
	}
}
