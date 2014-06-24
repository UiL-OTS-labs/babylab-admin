<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('output_date'))
{
	function output_date($date = 'now', $input = FALSE)
	{
		if ($date == NULL) return '';
		
		$abbr = $input ? '' : '<abbr title="' . strftime("%A %e %B %Y", strtotime($date)) . '">';
		$date_output = date('d-m-Y', strtotime($date));
		$abbr_end = $input ? '' : '</abbr>';
		
		return $abbr . $date_output . $abbr_end;
	}
}

if (!function_exists('input_date'))
{
	function input_date($date = 'now')
	{
		return date('Y-m-d', strtotime($date));
	}
}

if (!function_exists('output_datetime'))
{
	function output_datetime($date = 'now', $input = FALSE)
	{
		if ($date == NULL) return '';
		
		$abbr = $input ? '' : '<abbr title="' . strftime("%A %e %B %Y %R", strtotime($date)) . '">';
		$date_output = date('d-m-Y H:i', strtotime($date));
		$abbr_end = $input ? '' : '</abbr>';
		
		return $abbr . $date_output . $abbr_end;
	}
}

if (!function_exists('input_datetime'))
{
	function input_datetime($date = 'now')
	{
		return date('Y-m-d H:i:s', strtotime($date));
	}
}

if (!function_exists('output_datetime_email'))
{
	function output_datetime_email($date = 'now')
	{
		if ($date == NULL) return '';
		
		return strftime("%A %e %B %Y, om %R uur", strtotime($date));
	}
}