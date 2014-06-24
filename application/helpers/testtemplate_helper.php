<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/////////////////////////
// Table
/////////////////////////

if (!function_exists('create_testtemplate_table'))
{
	/** Creates the table with testtemplate data */
	function create_testtemplate_table($id = NULL)
	{
		$CI =& get_instance();
		base_table($id);
		$CI->table->set_heading(lang('test'), lang('language'), lang('template'), lang('actions'));
	}
}

/////////////////////////
// Links
/////////////////////////

if (!function_exists('testtemplate_get_link'))
{
	/** Returns the get link for a testtemplate */
	function testtemplate_get_link($testtemplate)
	{
		return anchor('testtemplate/get/' . $testtemplate->id, $testtemplate->name);
	}
}

if (!function_exists('testtemplate_get_link_by_id'))
{
	/** Returns the get link for a testtemplate */
	function testtemplate_get_link_by_id($testtemplate_id)
	{
		$CI =& get_instance();
		$testtemplate = $CI->testSurveyModel->get_testtemplate_by_id($testtemplate_id);

		return testtemplate_get_link($testtemplate);
	}
}

if (!function_exists('testtemplate_actions'))
{
	/** Possible actions for a testtemplate: edit, view scores, delete */
	function testtemplate_actions($testtemplate_id)
	{
		$edit_link = anchor('testtemplate/edit/' . $testtemplate_id, img_edit());
		$delete_link = anchor('testtemplate/delete/' . $testtemplate_id, img_delete(), warning(lang('sure_delete_testtemplate')));
			
		return implode(' ', array($edit_link, $delete_link));
	}
}

if (!function_exists('synopsis'))
{
	/** Synopsis for a testtemplate */
	function synopsis($text)
	{
		return substr(html_escape($text), 0, 100) . '...';
	}
}
