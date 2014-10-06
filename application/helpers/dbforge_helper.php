<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/**
 * Add Key
 *
 * @access	public
 * @param	string	key
 * @param	string	type
 * @return	void
 */
if (!function_exists('add_key'))
{
	function add_key($key = '', $primary = FALSE)
	{
		if (is_array($key))
		{
			foreach ($key as $one)
			{
				$this->add_key($one, $primary);
			}
			return;
		}

		if ($key == '')
		{
			show_error('Key information is required for that operation.');
		}

		if ($primary === TRUE)
		{
			$this->primary_keys[] = $key;
		}
		else
		{
			$this->keys[] = $key;
		}
	}
}

/**
 * Add Foreign Key
 * Custom function for DBForge
 *
 * @access	public
 * @param	string	key
 * @param	string	type
 * @return	void
 */
if (!function_exists('add_foreign_key'))
{	
	function add_foreign_key($key)
	{

		if ($key == '')
		{
			show_error('Key information is required for that operation.');
		}

		$this->foreign_keys[] = $key;	

	}
}