<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/////////////////////////
// Table-related
/////////////////////////

if (!function_exists('create_language_table'))
{
	/** Creates the table with language data */ 
	function create_language_table($id = NULL) 
	{
		$CI =& get_instance();
		base_table($id);
		$CI->table->set_heading(lang('participant'), lang('language'), lang('percentage'), lang('actions'));
	}
}

if (!function_exists('language_actions'))
{
	/** Possible actions for a language: prioritize and delete */ 
	function language_actions($language_id) 
	{
		$CI =& get_instance();
		$c = $CI->languageModel->get_language_by_id($language_id);
		
		$d_link = anchor('language/delete/' . $c->id, img_delete(), warning(lang('sure_delete_language')));
		
		return $d_link;
	}
}
