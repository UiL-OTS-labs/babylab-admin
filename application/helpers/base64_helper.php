<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('base64_url'))
{
	/** Returns the specified URL in base64 format */
	function base64_url($url)
	{
		return rtrim(base64_encode($url), '=');
	}
}
