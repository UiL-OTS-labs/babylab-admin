<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/////////////////////////
// Table
/////////////////////////

if (!function_exists('create_result_table'))
{
	/** Creates the table with result data */
	function create_result_table($id = NULL)
	{
		$CI =& get_instance();
		base_table($id);
		$heading = array(lang('phase'), lang('trial'), lang('lookingtime'), lang('nrlooks'), lang('actions'));
		if (empty($id)) array_unshift($heading, lang('participant'), lang('participant'));
		$CI->table->set_heading($heading);
	}
}

/////////////////////////
// Links
/////////////////////////

if (!function_exists('result_get_link'))
{
	/** Returns the get link for a result */
	function result_get_link($result)
	{
		return anchor('result/get/' . $result->id, $result->name);
	}
}

if (!function_exists('result_get_link_by_id'))
{
	/** Returns the get link for a result */
	function result_get_link_by_id($result_id)
	{
		$CI =& get_instance();
		$result = $CI->resultModel->get_result_by_id($result_id);

		return result_get_link($result);
	}
}

if (!function_exists('result_actions'))
{
	/** Possible actions for a result: edit, view scores, delete */
	function result_actions($result_id)
	{
		$edit_link = anchor('result/edit/' . $result_id, img_edit());
		$delete_link = anchor('result/delete/' . $result_id, img_delete(), warning(lang('sure_delete_result')));
			
		return implode(' ', array($edit_link, $delete_link));
	}
}
