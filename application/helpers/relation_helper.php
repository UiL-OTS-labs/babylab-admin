<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/////////////////////////
// Table-related
/////////////////////////

if (!function_exists('create_relation_table'))
{
	/** Creates the table with relation data */
	function create_relation_table($id = NULL)
	{
		$CI =& get_instance();
		base_table($id);
		$CI->table->set_heading(lang('experiment'), lang('relation'), lang('experiment'), lang('actions'));
	}
}

if (!function_exists('relation_actions'))
{
	/** Possible actions for a relation: delete */
	function relation_actions($relation_id)
	{
		return anchor('relation/delete/' . $relation_id, img_delete(), warning(lang('sure_delete_relation')));
	}
}
