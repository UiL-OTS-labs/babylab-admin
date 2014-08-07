<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/////////////////////////
// Table
/////////////////////////

if (!function_exists('create_percentile_table'))
{
	/** Creates the table with percentile data */
	function create_percentile_table($id = NULL)
	{
		$CI =& get_instance();
		base_table($id);
		$heading = array(lang('gender'), lang('age'), lang('score'), lang('percentile'), lang('actions'));
		if (empty($id)) array_unshift($heading, lang('testcat'));
		$CI->table->set_heading($heading);
	}
}

/////////////////////////
// Links
/////////////////////////

if (!function_exists('percentile_actions'))
{
	/** Possible actions for a caller: delete */
	function percentile_actions($percentile_id)
	{
		$CI =& get_instance();

		$edit_link = anchor('percentile/edit/' . $percentile_id, img_edit());
		$delete_link = anchor('percentile/delete/' . $percentile_id, img_delete(), warning(lang('sure_delete_percentile')));

		return implode(' ', array($edit_link, $delete_link));
	}
}
