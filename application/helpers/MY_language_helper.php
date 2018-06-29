<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('reset_language'))
{
	/** Reset the language to the specified language */
	function reset_language($language)
	{
		$CI =& get_instance();
		$CI->lang->is_loaded = array();

		// Generic
		$CI->lang->load('babylab', $language);

		// Per controller
		$CI->lang->load('percentile', $language);
		$CI->lang->load('result', $language);
		$CI->lang->load('score', $language);
		$CI->lang->load('test', $language);
		$CI->lang->load('testinvite', $language);
		$CI->lang->load('testsurvey', $language);
		$CI->lang->load('testtemplate', $language);
		$CI->lang->load('language', $language);
		$CI->lang->load('dyslexia', $language);

		$CI->config->set_item('language', $language);

		// Date/Time
		setlocale(LC_TIME, $language === L::DUTCH ? 'nl_NL.utf8' : 'C');
	}
}
