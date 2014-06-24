<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/////////////////////////
// Table
/////////////////////////

if (!function_exists('create_test_table'))
{
	/** Creates the table with test data */
	function create_test_table($id = NULL)
	{
		$CI =& get_instance();
		base_table($id);
		$CI->table->set_heading(lang('code'), lang('name'), lang('actions'));
	}
}

/////////////////////////
// Links
/////////////////////////

if (!function_exists('test_get_link'))
{
	/** Returns the get link for a test */
	function test_get_link($test)
	{
		return anchor('test/get/' . $test->id, $test->name);
	}
}

if (!function_exists('test_get_link_by_id'))
{
	/** Returns the get link for a test */
	function test_get_link_by_id($test_id)
	{
		$CI =& get_instance();
		$test = $CI->testModel->get_test_by_id($test_id);

		return test_get_link($test);
	}
}

if (!function_exists('test_actions'))
{
	/** Possible actions for a test: edit, view scores, delete */
	function test_actions($test_id)
	{
		$edit_link = anchor('test/edit/' . $test_id, img_edit());
		$delete_link = anchor('test/delete/' . $test_id, img_delete(), warning(lang('sure_delete_test')));
			
		return implode(' ', array($edit_link, $delete_link));
	}
}

if (!function_exists('test_when'))
{
	/** Returns an option list of tests */
	function test_when($whensent, $whennr)
	{
		$nr = $whensent === TestWhenSent::Participation ? $whennr . 'e' : $whennr; 
		return $nr . ' ' . lcfirst(lang($whensent));
	}
}

/////////////////////////
// Options
/////////////////////////

if (!function_exists('test_options'))
{
	/** Returns an option list of tests */
	function test_options($tests)
	{
		$t_options = array();
		foreach ($tests as $t)
		{
			$t_options[$t->id] = $t->name;
		}
		asort($t_options);
		return $t_options;
	}
}
