<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/////////////////////////
// Table-related
/////////////////////////

if (!function_exists('create_user_table'))
{
	/** Creates the table with user data */
	function create_user_table($id = NULL)
	{
		$CI =& get_instance();
		base_table($id);
		$CI->table->set_heading(lang('username'), lang('role'), lang('email'), lang('phone'), lang('mobile'), lang('actions'));
	}
}

/////////////////////////
// User-related
/////////////////////////

if (!function_exists('is_activated'))
{
	/** Returns whether or not the user is activated */
	function is_activated($user)
	{
		return $user->activated != NULL && $user->activated <= input_datetime();
	}
}

if (!function_exists('user_language'))
{
	/** Returns the preferred language of the user */
	function user_language($user)
	{
		return $user->preferredlanguage === 'nl' ? L::Dutch : L::English;
	}
}

/////////////////////////
// Links
/////////////////////////

if (!function_exists('user_get_link'))
{
	/** Returns the get link for a user */
	function user_get_link($user)
	{
		return anchor('user/get/' . $user->id, $user->username);
	}
}

if (!function_exists('user_get_link_by_id'))
{
	/** Returns the get link for a user */
	function user_get_link_by_id($user_id)
	{
		$CI =& get_instance();
		$user = $CI->userModel->get_user_by_id($user_id);

		return user_get_link($user);
	}
}

if (!function_exists('user_is_admin'))
{
	/** Returns whether or not a user is an admin */
	function user_is_admin($user)
	{
		return $user->role === UserRole::Admin;
	}
}

if (!function_exists('user_actions'))
{
	/** Possible actions for a user: edit, view participants, call, archive, delete */
	function user_actions($user_id)
	{
		$CI =& get_instance();
		$u = $CI->userModel->get_user_by_id($user_id);

		$edit_link = anchor('user/edit/' . $u->id, img_edit());
		$act_link = is_activated($u) ? anchor('user/deactivate/' . $u->id, img_active(TRUE)) : anchor('user/activate/' . $u->id, img_active(FALSE));
		$delete_link = user_is_admin($u) ? img_delete(TRUE) : anchor('user/delete/' . $u->id, img_delete(), warning(lang('sure_delete_user')));

		return implode(' ', array($edit_link, $act_link, $delete_link));
	}
}
