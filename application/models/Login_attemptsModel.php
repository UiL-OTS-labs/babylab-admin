<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Login_attempts extends CI_Model
{
	/** Get number of attempts to login occured from given IP-address or login. */
	function get_attempts_num($ip_address, $login)
	{
		$this->db->select('1', FALSE);
		$this->db->where('ip_address', $ip_address);
		if (isset($login)) $this->db->or_where('login', $login);

		return $this->db->get('login_attempts')->num_rows();
	}

	/** Increase number of attempts for given IP-address and login. */
	function increase_attempt($ip_address, $login)
	{
		$this->db->insert('login_attempts', array('ip_address' => $ip_address, 'login' => $login));
	}

	/** Clear all attempt records for given IP-address and login. Also purge obsolete login attempts (to keep DB clear). */
	function clear_attempts($ip_address, $login, $expire_period = 86400)
	{
		$this->db->where(array('ip_address' => $ip_address, 'login' => $login));

		// Purge obsolete login attempts
		$this->db->or_where('UNIX_TIMESTAMP(time) <', time() - $expire_period);

		$this->db->delete('login_attempts');
	}
}

// $this->input->ip_address() => fetch IP address