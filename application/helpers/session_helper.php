<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/////////////////////////
// Session-related
/////////////////////////

if (!function_exists('current_user_id'))
{
	/** Returns the user_id of the current user */
	function current_user_id()
	{
		$CI =& get_instance();
		return $CI->session->userdata('user_id');
	}
}

if (!function_exists('system_user_id'))
{
	/** Returns the user_id of the system user */
	function system_user_id()
	{
		$CI =& get_instance();
		$user = $CI->userModel->get_user_by_username('system');
		return $user->id;
	}
}

if (!function_exists('correct_user'))
{
	/**
	 * 	Checks whether the current user is also the user for which the page is called.
	 *  If not, this function will load a 'not authorized' page.
	 */
	function correct_user($user_id)
	{
		if ($user_id != current_user_id()) 
		{
			$CI =& get_instance();

			$data['error'] = lang('not_authorized');
			$CI->load->view('templates/error', $data);

			return FALSE;
		}
		return TRUE;
	}
}

if (!function_exists('current_username'))
{
	/** Returns the username of the current user */
	function current_username()
	{
		$CI =& get_instance();
		return $CI->session->userdata('username');
	}
}

if (!function_exists('current_language'))
{
	/** Returns the preferred language of the current user */
	function current_language()
	{
		$CI =& get_instance();
		return $CI->session->userdata('language');
	}
}

if (!function_exists('current_email'))
{
	/** Returns the preferred language of the current user */
	function current_email()
	{
		$CI =& get_instance();
		return $CI->session->userdata('email');
	}
}

if (!function_exists('current_role'))
{
	/** Returns the role of the current user */
	function current_role()
	{
		$CI =& get_instance();
		return $CI->session->userdata('role');
	}
}

if (!function_exists('is_admin'))
{
	/** Returns whether or not the current user is an admin */
	function is_admin()
	{
		return current_role() === UserRole::Admin;
	}
}

if (!function_exists('is_leader'))
{
	/** Returns whether or not the current user is a leader */
	function is_leader()
	{
		return current_role() === UserRole::Leader;
	}
}

/////////////////////////
// Server-related
/////////////////////////

if (!function_exists('in_development'))
{
	/** Returns whether or we are in development mode */
	function in_development()
	{
		return ENVIRONMENT === 'development';
	}
}

/////////////////////////
// DB-related
/////////////////////////

if (!function_exists('add_fields'))
{
	/** A small trick to add all fields of a table (possibly as NULLs) */
	function add_fields($data, $table, $object = NULL)
	{
		$CI =& get_instance();
		$fields = $CI->db->list_fields($table);
		foreach ($fields as $field)
		{
			$data[$field] = isset($object) ? $object->$field : NULL;
		}
		return $data;
	}
}

if (!function_exists('get_object_ids'))
{
	function get_object_ids($objects, $field = 'id')
	{
		if (empty($objects)) return array();

		$result = array();
		foreach ($objects as $object)
		{
			array_push($result, $object->$field);
		}
		return $result;
	}
}