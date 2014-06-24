<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('base_table'))
{
	/** Returns a basic table */
	function base_table($id = NULL)
	{
		$CI =& get_instance();
		
		$tmpl = array (
				'table_open'	=> '<table border="0" cellpadding="0" cellspacing="0" ' . (!empty($id) ? 'id="' . $id . '">' : 'class="dataTable">'),
		);

		$CI->table->set_template($tmpl);
		$CI->table->set_empty("&nbsp;");
	}
}