<?php
class ImpedimentModel extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/////////////////////////
	// CRUD-actions
	/////////////////////////

	/** Returns all impediments as an array.
	 * If $future is set to false, past impediments are also included. */
	public function get_all_impediments($future = TRUE)
	{
		if ($future) $this->db->where('to >=', input_date());
		return $this->db->get('impediment')->result();
	}

	/** Adds an impediment to the DB */
	public function add_impediment($impediment)
	{
		$this->db->insert('impediment', $impediment);
		return $this->db->insert_id();
	}

	/** Deletes an impediment from the DB */
	public function delete_impediment($impediment_id)
	{
		$this->db->delete('impediment', array('id' => $impediment_id));
	}

	/** Returns the impediment for an id */
	public function get_impediment_by_id($impediment_id)
	{
		return $this->db->get_where('impediment', array('id' => $impediment_id))->row();
	}

	/////////////////////////
	// Participants
	/////////////////////////

	/** Returns the next impediment for a participant */
	public function next_impediment_by_participant($participant_id)
	{
		$this->db->where('participant_id', $participant_id);
		$this->db->where('to >=', input_date());
		$this->db->order_by('from', 'asc');
		$imp = $this->db->get('impediment')->result();
		return $imp ? $imp[0] : NULL;
	}

	/** Returns all future impediments for a participant */
	public function get_future_impediments_by_participant($participant_id)
	{
		$this->db->where('participant_id', $participant_id);
		$this->db->where('to >=', input_date());
		return $this->db->get('impediment')->result();
	}

	/** Returns all impediments for a participant */
	public function get_impediments_by_participant($participant_id)
	{
		$this->db->where('participant_id', $participant_id);
		return $this->db->get('impediment')->result();
	}

	/** Returns the participant for a impediment */
	public function get_participant_by_impediment($impediment)
	{
		return $this->db->get_where('participant', array('id' => $impediment->participant_id))->row();
	}

	/** Returns whether there is already an impediment for the given date and participant */
	public function within_bounds($date, $participant_id)
	{
		$this->db->where('participant_id', $participant_id);
		$this->db->where('from <=', $date);
		$this->db->where('to >', $date);
		$this->db->from('impediment');
		return $this->db->count_all_results() > 0;
	}
}
