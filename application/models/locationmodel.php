<?php
class LocationModel extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/////////////////////////
	// CRUD-actions
	/////////////////////////

	/** Returns all locations as an array */
	public function get_all_locations()
	{
		return $this->db->get('location')->result();
	}

	/** Adds a location to the DB */
	public function add_location($location)
	{
		$this->db->insert('location', $location);
		return $this->db->insert_id();
	}

	/** Updates the location specified by the id with the details of the location */
	public function update_location($location_id, $location)
	{
		$this->db->where('id', $location_id);
		$this->db->update('location', $location);
	}

	/** Deletes a location from the DB */
	public function delete_location($location_id)
	{
		$this->db->delete('location', array('id' => $location_id));
	}

	/** Returns the location for an id */
	public function get_location_by_id($location_id)
	{
		return $this->db->get_where('location', array('id' => $location_id))->row();
	}

	/////////////////////////
	// Experiments
	/////////////////////////

	/** Retrieves all experiments for a location */
	public function get_experiments_by_location($location_id)
	{
		$this->db->where('location_id', $location_id);
		return $this->db->get('experiment')->result();
	}

	/** Retrieves a location by the experiment id */
	public function get_location_by_experiment($experiment)
	{
		return $this->db->get_where('location', array('id' => $experiment->location_id))->row();
	}
}