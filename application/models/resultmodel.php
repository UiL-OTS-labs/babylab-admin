<?php
class ResultModel extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}
	
	/////////////////////////
	// CRUD-actions
	/////////////////////////

	/** Returns all results as an array */
	public function get_all_results()
	{
		return $this->db->get('result')->result();
	}
	
	/** Adds a result to the DB */
	public function add_result($result)
	{
		$this->db->insert('result', $result);
		return $this->db->insert_id();
	}
	
	/** Updates the result specified by the id with the details of the result */
	public function update_result($result_id, $result)
	{
		$this->db->where('id', $result_id);
		$this->db->update('result', $result);
	}

	/** Deletes a result from the DB */
	public function delete_result($result_id)
	{
		$this->db->delete('result', array('id' => $result_id));
	}

	/** Returns the result for an id */
	public function get_result_by_id($result_id)
	{
		return $this->db->get_where('result', array('id' => $result_id))->row();
	}
}