<?php
class AvailabilityModel extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/////////////////////////
	// CRUD-actions
	/////////////////////////

	/** Returns all availabilities as an array.
	 * If $future is set to false, past availabilities are also included. */
	public function get_all_availabilities($future = TRUE)
	{
		if ($future) $this->db->where('to >=', input_date());
		return $this->db->get('availability')->result();
	}

	/** Adds an availability to the DB */
	public function add_availability($availability)
	{
		$this->db->insert('availability', $availability);
		return $this->db->insert_id();
	}

	/** Deletes an availability from the DB */
	public function delete_availability($availability_id)
	{
		$this->db->delete('availability', array('id' => $availability_id));
	}

	/** Returns the availability for an id */
	public function get_availability_by_id($availability_id)
	{
		return $this->db->get_where('availability', array('id' => $availability_id))->row();
	}

	/////////////////////////
	// User actions
	/////////////////////////

	/** Returns all future availabilities for a user */
	public function get_future_availabilities_by_user($user_id)
	{
		$this->db->where('user_id', $user_id);
		$this->db->where('to >=', input_date());
		return $this->db->get('availability')->result();
	}

	public function get_future_availabilities_by_users($ids)
	{
		$this->db->where_in('user_id', $ids);
		$this->db->where('to >=', input_date());
		return $this->db->get('availability')->result();
	}

	public function get_availabilities_by_users($ids)
	{
		$this->db->where_in('user_id', $ids);
		$this->db->orderby('from');
		return $this->db->get('availability')->result();
	}

	/** Returns all availabilities for a user */
	public function get_availabilities_by_user($user_id)
	{
		$this->db->where('user_id', $user_id);
		return $this->db->get('availability')->result();
	}

	/** Returns all availabilities for leaders of the experiments */
	public function get_availabilities_by_experiments($ids)
	{
		$this->db->select('availability.*');
		$this->db->join('leader', 'leader.user_id_leader = availability.user_id');
		$this->db->where_in('leader.experiment_id', $ids);
		return $this->db->get('availability')->result();
	}

	/** Returns the user for an availability */
	public function get_user_by_availability($availability)
	{
		return $this->db->get_where('user', array('id' => $availability->user_id))->row();
	}

	/////////////////////////
	// Helpers
	/////////////////////////

	/** Returns whether there is already an availability for the given date and user */
	public function within_bounds($date, $user_id)
	{
		$this->db->where('user_id', $user_id);
		$this->db->where('from <=', $date);
		$this->db->where('to >=', $date);
		$this->db->from('availability');
		return $this->db->count_all_results() > 0;
	}
}