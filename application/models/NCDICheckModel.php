<?php
class NCDICheckModel extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/////////////////////////
	// CRUD-actions
	/////////////////////////

	/** Returns all NCDI checks as an array */
	public function get_all_ncdi_checks()
	{
		return $this->db->get('ncdi_check')->result();
	}

	/** Adds a NCDI check to the DB */
	public function add_ncdi_check($ncdi_check)
	{
		$this->db->insert('ncdi_check', $ncdi_check);
		return $this->db->insert_id();
	}
}