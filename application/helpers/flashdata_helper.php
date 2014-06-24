<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('flashdata'))
{
	/** Adds a flashdata message to the current session */
	function flashdata($message, $success = TRUE, $message_id = 'message')
	{
		if ($success) 
		{
			$message = '<div class="success">' . $message . '</div>';
		}
		else 
		{
			$message = '<div class="failed">' . $message . '</div>';
		}

		$CI =& get_instance();
		$CI->session->set_flashdata($message_id, $message);
	}
}

if (!function_exists('warning'))
{
	/** Adds a warning message, for example to a URL */
	function warning($message)
	{
		return array('onclick' => "return confirm('" . $message . "')");
	}
}