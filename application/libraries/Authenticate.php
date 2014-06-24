<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Authenticate 
{
	var $obj;
	
	function Authenticate()
	{
		$this->obj =& get_instance();
	}
	
	/**
	 * library: authenticate
	 * purpose: authenticate session
	 * use: $this->authenticate->authenticate_redirect($param1, $param2, $param3);
	 * location: ./libraries/authenticate.php
	 * @param string $param1 view to be loaded
	 * @param string $param2 data to be sent along with the view
	 * @param string $param3 role needed to be able to display page
	 * @param string $param4 message to be displayed when not authenticated
	 * @return boolean
	 */
	function authenticate_redirect($url, $data = '', $role = UserRole::Caller, $message_login_failed = '') 
	{			
		// if authentication succes, load view, otherwise show message not authorized
		if ($this->obj->authenticate->authenticate_session($role) == TRUE) { 
			$this->obj->load->view($url, $data);
		}
		else {
			$data['error'] = $message_login_failed === '' ? lang('not_authorized') : $message_login_failed;
			$this->obj->load->view('templates/error', $data); 
		}	
	}
	
	function authenticate_session($role) 
	{	
		$session_bool = $this->obj->session->userdata('loggedin');
		$session_role =  $this->obj->session->userdata('role');	

		$correct_role = $role === UserRole::Admin ? $session_role === UserRole::Admin : TRUE;
		$correct_role &= $role === UserRole::Leader ? in_array($session_role, array(UserRole::Admin, UserRole::Leader)) : TRUE;
		
		if (isset($session_bool) && $session_bool == TRUE && $correct_role) {
			return TRUE;
		} 
		else { 
			return FALSE; 
		}		
	}	
}

/* End of file Authenticate.php */
