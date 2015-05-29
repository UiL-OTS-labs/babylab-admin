<?php
class UserModel extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/////////////////////////
	// CRUD-actions
	/////////////////////////

	/** Returns all users as an array */
	public function get_all_users($include_inactive = FALSE)
	{
		if (!$include_inactive) $this->db->where('activated IS NOT NULL');
		return $this->db->get('user')->result();
	}

	/** Adds a user to the DB */
	public function add_user($user)
	{
		$this->db->insert('user', $user);
		return $this->db->insert_id();
	}

	/** Updates the user specified by the id with the details of the user */
	public function update_user($user_id, $user)
	{
		$u = $this->get_user_by_id($user_id);
		if (isset($user['role']) && $user['role'] != $u->role)
		{
			if ($u->role === UserRole::Caller) $this->callerModel->delete_callers_by_user($user_id);
			if ($u->role === UserRole::Leader) $this->leaderModel->delete_leaders_by_user($user_id);
		}

		$this->db->where('id', $user_id);
		$this->db->update('user', $user);
	}

	/** Returns the user specified by the id */
	public function get_user_by_id($user_id)
	{
		return $this->db->get_where('user', array('id' => $user_id))->row();
	}

	/** Returns the user specified by the username */
	public function get_user_by_username($user_name)
	{
		return $this->db->get_where('user', array('username' => $user_name))->row();
	}

	/** Returns the user specified by the email address */
	public function get_user_by_email($email)
	{
		return $this->db->get_where('user', array('email' => $email))->row();
	}

	/** Returns the user specified by the resetstring */
	public function get_user_by_resetstring($resetstring)
	{
		return $this->db->get_where('user', array('resetrequeststring' => $resetstring))->row();
	}

	/** Deletes a user from the DB */
	public function delete_user($user_id)
	{
		// TODO: don't delete references, but add warnings.
		// Delete references to callers
		//$this->db->delete('caller', array('user_id_caller' => $user_id));
		// Delete references to leaders
		//$this->db->delete('leader', array('user_id_leader' => $user_id));
		// Delete references to comments
		//$this->db->delete('comment', array('user_id' => $user_id));

		$this->db->delete('user', array('id' => $user_id));
	}

	/////////////////////////
	// Other actions
	/////////////////////////

	/** Returns all the active users of type caller */
	public function get_all_callers()
	{
		$this->db->where('activated IS NOT NULL');
		return $this->db->get_where('user', array('role' => UserRole::Caller))->result();
	}

	/** Returns all the active users of type leader */
	public function get_all_leaders()
	{
		$this->db->where('activated IS NOT NULL');
		return $this->db->get_where('user', array('role' => UserRole::Leader))->result();
	}

	/** Returns all the active users of type admin */
	public function get_all_admins()
	{
		$this->db->where('activated IS NOT NULL');
		return $this->db->get_where('user', array('role' => UserRole::Admin))->result();
	}

	/** Returns whether or not a user exists based on the given user id */
	public function check_user_exists($user_id)
	{
		$this->db->where('id', $user_id);
		$this->db->from('user');
		return $this->db->count_all_results() > 0;
	}

	/** Activates or deactivates the specified user */
	public function set_activate($user_id, $activated)
	{
		$this->db->where('id', $user_id);
		if ($activated) 
		{
			$this->db->update('user', array('activated' => input_date()));
		}
		else 
		{
			$this->db->update('user', array('activated' => NULL));
		}
	}
}