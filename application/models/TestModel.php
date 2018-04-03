<?php
class TestModel extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/////////////////////////
	// CRUD-actions
	/////////////////////////

	/** Returns all tests as an array */
	public function get_all_tests()
	{
		return $this->db->get('test')->result();
	}

	/** Adds a test to the DB */
	public function add_test($test)
	{
		$this->db->insert('test', $test);
		return $this->db->insert_id();
	}

	/** Updates the test specified by the id with the details of the test */
	public function update_test($test_id, $test)
	{
		$this->db->where('id', $test_id);
		$this->db->update('test', $test);
	}

	/** Deletes a test from the DB */
	public function delete_test($test_id)
	{
		// Delete references to test categories
		$this->db->delete('testcat', array('test_id' => $test_id));

		$this->db->delete('test', array('id' => $test_id));
	}

	/** Returns the test for an id */
	public function get_test_by_id($test_id)
	{
		return $this->db->get_where('test', array('id' => $test_id))->row();
	}

	/** Returns the test for a code (unique key) */
	public function get_test_by_code($code)
	{
		return $this->db->get_where('test', array('code' => $code))->row();
	}

	/** Returns all tests for a specific set of codes */
	public function get_tests_by_codes($codes)
	{
		$this->db->where_in('code', $codes);
		$this->db->order_by('code');
		return $this->db->get('test')->result();
	}
}