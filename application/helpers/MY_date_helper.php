<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('output_date'))
{
	function output_date($date = 'now', $input = FALSE)
	{
		if ($date == NULL) return '';
		
		$abbr = $input ? '' : '<abbr title="' . format_date($date) . '">';
		$date_output = date('d-m-Y', strtotime($date));
		$abbr_end = $input ? '' : '</abbr>';
		
		return $abbr . $date_output . $abbr_end;
	}
}

if (!function_exists('format_date'))
{
	function format_date($date = 'now')
	{
		if ($date == NULL) return '';
		return strftime("%A %e %B %Y", strtotime($date));
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
		
		$abbr = $input ? '' : '<abbr title="' . format_datetime($date) . '">';
		$date_output = date('d-m-Y H:i', strtotime($date));
		$abbr_end = $input ? '' : '</abbr>';
		
		return $abbr . $date_output . $abbr_end;
	}
}

if (!function_exists('format_datetime'))
{
	function format_datetime($date = 'now')
	{
		if ($date == NULL) return '';
		return strftime("%A %e %B %Y, om %R uur", strtotime($date));
	}
}

if (!function_exists('input_datetime'))
{
	function input_datetime($date = 'now')
	{
		return date('Y-m-d H:i:s', strtotime($date));
	}
}


if (!function_exists('months_between'))
{
	// Retrieves the months between two dates
	// Copied from http://stackoverflow.com/a/18743012
	function months_between($min_date, $max_date)
	{
		$months = array();

		$start    = (new DateTime($min_date))->modify('first day of this month');
		$end      = (new DateTime($max_date))->modify('first day of next month');
		$interval = DateInterval::createFromDateString('1 month');
		$period   = new DatePeriod($start, $interval, $end);

		foreach ($period as $dt)
		{
			array_push($months, $dt->format('Y-m'));
		}

		return $months;
	}
}
