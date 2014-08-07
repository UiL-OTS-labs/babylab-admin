<?php
class TestSurveyModel extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/////////////////////////
	// CRUD-actions
	/////////////////////////

	/** Returns all testsurveys as an array */
	public function get_all_testsurveys()
	{
		return $this->db->get('testsurvey')->result();
	}

	/** Adds a testsurvey to the DB */
	public function add_testsurvey($testsurvey)
	{
		$this->db->insert('testsurvey', $testsurvey);
		return $this->db->insert_id();
	}

	/** Updates the testsurvey specified by the id with the details of the testsurvey */
	public function update_testsurvey($testsurvey_id, $testsurvey)
	{
		$this->db->where('id', $testsurvey_id);
		$this->db->update('testsurvey', $testsurvey);
	}

	/** Deletes a testsurvey from the DB */
	public function delete_testsurvey($testsurvey_id)
	{
		// Delete references to test invitations
		$this->db->delete('testinvite', array('testsurvey_id' => $testsurvey_id));

		$this->db->delete('testsurvey', array('id' => $testsurvey_id));
	}

	/** Returns the testsurvey for an id */
	public function get_testsurvey_by_id($testsurvey_id)
	{
		return $this->db->get_where('testsurvey', array('id' => $testsurvey_id))->row();
	}

	/////////////////////////
	// Tests
	/////////////////////////

	/** Returns the surveys for a test */
	public function get_testsurveys_by_test($test_id)
	{
		$this->db->where('test_id', $test_id);
		return $this->db->get('testsurvey')->result();
	}

	/** Returns the test for a test category */
	public function get_test_by_testsurvey($testsurvey)
	{
		return $this->db->get_where('test', array('id' => $testsurvey->test_id))->row();
	}

	/////////////////////////
	// Extra
	/////////////////////////

	public function get_testsurveys_by_when($whensent, $whennr = NULL)
	{
		$this->db->where('whensent', $whensent);
		if (!empty($whennr)) $this->db->where('whennr', $whennr);
		return $this->db->get('testsurvey')->result();
	}
}