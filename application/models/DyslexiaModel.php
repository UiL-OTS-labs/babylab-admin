<?php
class DyslexiaModel extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/////////////////////////
	// CRUD-actions
	/////////////////////////

	/** Returns all dyslexias as an array */
	public function get_all_dyslexias()
	{
		return $this->db->get('dyslexia')->result();
	}

	/** Adds a dyslexia to the DB */
	public function add_dyslexia($dyslexia)
	{
		$this->db->insert('dyslexia', $dyslexia);
		return $this->db->insert_id();
	}

	/** Updates the dyslexia specified by the id with the details of the dyslexia */
	public function update_dyslexia($dyslexia_id, $dyslexia)
	{
		$this->db->where('id', $dyslexia_id);
		$this->db->update('dyslexia', $dyslexia);
	}

	/** Deletes a dyslexia from the DB */
	public function delete_dyslexia($dyslexia_id)
	{
		$this->db->delete('dyslexia', array('id' => $dyslexia_id));
	}

	/** Returns the dyslexia for an id */
	public function get_dyslexia_by_id($dyslexia_id)
	{
		return $this->db->get_where('dyslexia', array('id' => $dyslexia_id))->row();
	}

	/** Returns the dyslexia for a participant and gender (unique key) */
	public function get_dyslexia_by_participant_gender($participant_id, $gender)
	{
		$this->db->where('participant_id', $participant_id);
		$this->db->where('gender', $gender);
		return $this->db->get('dyslexia')->row();
	}

	/////////////////////////
	// Participants
	/////////////////////////

	/** Returns the dyslexias for a participant */
	public function get_dyslexias_by_participant($participant_id)
	{
		$this->db->where('participant_id', $participant_id);
		return $this->db->get('dyslexia')->result();
	}

	/** Returns the participant for a dyslexia */
	public function get_participant_by_dyslexia($dyslexia)
	{
		return $this->db->get_where('participant', array('id' => $dyslexia->participant_id))->row();
	}
}