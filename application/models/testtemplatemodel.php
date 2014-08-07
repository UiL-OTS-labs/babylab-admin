<?php
class TestTemplateModel extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/////////////////////////
	// CRUD-actions
	/////////////////////////

	/** Returns all testtemplates as an array */
	public function get_all_testtemplates()
	{
		return $this->db->get('testtemplate')->result();
	}

	/** Adds a testtemplate to the DB */
	public function add_testtemplate($testtemplate)
	{
		$this->db->insert('testtemplate', $testtemplate);
		return $this->db->insert_id();
	}

	/** Updates the testtemplate specified by the id with the details of the testtemplate */
	public function update_testtemplate($testtemplate_id, $testtemplate)
	{
		$this->db->where('id', $testtemplate_id);
		$this->db->update('testtemplate', $testtemplate);
	}

	/** Deletes a testtemplate from the DB */
	public function delete_testtemplate($testtemplate_id)
	{
		$this->db->delete('testtemplate', array('id' => $testtemplate_id));
	}

	/** Returns the testtemplate for an id */
	public function get_testtemplate_by_id($testtemplate_id)
	{
		return $this->db->get_where('testtemplate', array('id' => $testtemplate_id))->row();
	}

	/////////////////////////
	// Tests
	/////////////////////////

	/** Returns the templates for a test */
	public function get_testtemplates_by_test($test_id)
	{
		$this->db->where('test_id', $test_id);
		return $this->db->get('testtemplate')->result();
	}

	/** Returns the test for a template */
	public function get_test_by_testtemplate($testtemplate)
	{
		return $this->db->get_where('test', array('id' => $testtemplate->test_id))->row();
	}

	/** Returns the single for a test and language (unique key) */
	public function get_testtemplate_by_test($test_id, $language)
	{
		$this->db->where('test_id', $test_id);
		$this->db->where('language', $language);
		return $this->db->get('testtemplate')->row();
	}
}