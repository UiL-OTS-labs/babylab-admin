<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Authenticate
{
	var $CI;

	public function __construct()
	{
		$this->CI =& get_instance();
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
	public function authenticate_redirect($url, $data = '', $role = UserRole::Caller, $message_login_failed = '')
	{
		// if authentication succes, load view, otherwise show message not authorized
		if ($this->CI->authenticate->authenticate_session($role) == TRUE) 
		{
			$this->CI->load->view($url, $data);
		}
		else 
		{

			$data['error'] = $message_login_failed === '' ? lang('not_authorized') : $message_login_failed;
			$this->CI->load->view('templates/error', $data);
		}
	}

	public function authenticate_session($role)
	{
		$session_role = $this->CI->session->userdata('role');

		$correct_role = $role === UserRole::Admin ? $session_role === UserRole::Admin : TRUE;
		$correct_role &= $role === UserRole::Leader ? in_array($session_role, array(UserRole::Admin, UserRole::Leader)) : TRUE;

		return $this->logged_in() && $correct_role;
	}

	/**
	 * Checks the session data as to whether or not a user is logged in.
	 */
	public function logged_in()
	{
		return $this->CI->session->userdata('logged_in');
	}

	/**
	 * 
	 * Redirect a non-logged in user to the default controller, unless specified otherwise.
	 * @param array $exceptions
	 */
	public function redirect_except($exceptions = array())
	{
		$method = $this->CI->router->fetch_method();
		
		if (!$this->logged_in())
		{
			if (!in_array($method, $exceptions))
			{
				// Redirect to login page and remember which page to go back to
				// after succesful login
				$this->CI->session->set_userdata('redirect_back', $this->CI->uri->uri_string);  
				redirect();
			}
		}
	}
}

/* End of file: Authenticate.php */
/* Location: application/libraries/Authenticate.php */
