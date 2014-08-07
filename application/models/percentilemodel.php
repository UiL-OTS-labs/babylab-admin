<?php
class PercentileModel extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/////////////////////////
	// CRUD-actions
	/////////////////////////

	/** Returns all percentiles as an array */
	public function get_all_percentiles()
	{
		return $this->db->get('percentile')->result();
	}

	/** Adds a percentile to the DB */
	public function add_percentile($percentile)
	{
		$this->db->insert('percentile', $percentile);
		return $this->db->insert_id();
	}

	/** Updates the percentile specified by the id with the details of the percentile */
	public function update_percentile($percentile_id, $percentile)
	{
		$this->db->where('id', $percentile_id);
		$this->db->update('percentile', $percentile);
	}

	/** Deletes a percentile from the DB */
	public function delete_percentile($percentile_id)
	{
		$this->db->delete('percentile', array('id' => $percentile_id));
	}

	/** Returns the percentile for an id */
	public function get_percentile_by_id($percentile_id)
	{
		return $this->db->get_where('percentile', array('id' => $percentile_id))->row();
	}

	/////////////////////////
	// Test categories
	/////////////////////////

	/** Returns the percentiles for a test category */
	public function get_percentiles_by_testcat($testcat_id)
	{
		$this->db->where('testcat_id', $testcat_id);
		return $this->db->get('percentile')->result();
	}

	/** Returns the percentiles for test categories, for specific percentiles */
	public function get_percentiles_by_testcats($testcat_ids, $percentiles = array())
	{
		$this->db->where_in('testcat_id', $testcat_ids);
		$this->db->where_in('percentile', $percentiles);
		$this->db->order_by('testcat_id', 'asc');
		$this->db->order_by('gender', 'asc');
		$this->db->order_by('age', 'asc');
		$this->db->order_by('percentile', 'desc');
		return $this->db->get('percentile')->result();
	}

	/** Returns the test category for a percentile */
	public function get_testcat_by_percentile($percentile)
	{
		return $this->db->get_where('testcat', array('id' => $percentile->testcat_id))->row();
	}

	/////////////////////////
	// Tests
	/////////////////////////

	/** Returns the percentiles for a test */
	public function get_percentiles_by_test($test_id)
	{
		$this->db->join('testcat', 'percentile.testcat_id = testcat.id');
		$this->db->join('test', 'testcat.test_id = test.id');
		$this->db->where('test.id', $test_id);
		return $this->db->get('percentile')->result();
	}

	/** Returns the test for a percentile */
	public function get_test_by_percentile($percentile)
	{
		$this->db->select('test.*');
		$this->db->from('test');
		$this->db->join('testcat', 'testcat.test_id = test.id');
		$this->db->join('percentile', 'percentile.testcat_id = testcat.id');
		$this->db->where('testcat.id', $percentile->testcat_id);
		return $this->db->get()->row();
	}

	/////////////////////////
	// Find right percentile
	/////////////////////////

	/** Find the percentile given the test, gender and score */
	public function find_percentile($testcat_id, $gender, $age, $score)
	{
		if ($this->gender_where($testcat_id)) $this->db->where('gender', $gender);
		$this->db->where('age', $age);
		$this->db->where('testcat_id', $testcat_id);
		$this->db->where('score >=', $score);
		$this->db->order_by('score', 'asc');
		$this->db->order_by('percentile', 'desc');
		$this->db->limit(1);
		$percentile = $this->db->get('percentile')->row();

		// If no result found, return max percentile
		if (empty($percentile)) return $this->max_percentile($testcat_id, $gender, $age);

		return $percentile->percentile;
	}

	/** Find the maximum percentile given the test, gender and age */
	public function max_percentile($testcat_id, $gender, $age)
	{
		if ($this->gender_where($testcat_id)) $this->db->where('gender', $gender);
		$this->db->where('age', $age);
		$this->db->where('testcat_id', $testcat_id);
		$this->db->order_by('percentile', 'desc');
		$this->db->limit(1);
		$percentile = $this->db->get('percentile')->row();

		// If no result found, return 0
		if (empty($percentile)) return 0;

		return $percentile->percentile;
	}

	/** Find the 50-percentile age given the test, gender and score */
	public function find_50percentile_age($testcat_id, $gender, $score)
	{
		if ($this->gender_where($testcat_id)) $this->db->where('gender', $gender);
		$this->db->where('percentile', 50);		// 50-th percentile
		$this->db->where('testcat_id', $testcat_id);
		$this->db->where('score >=', $score);
		$this->db->order_by('score', 'asc');
		$this->db->order_by('age', 'desc');
		$this->db->limit(1);
		$percentile = $this->db->get('percentile')->row();

		// If no result found, return max age
		if (empty($percentile)) return $this->max_50percentile_age($testcat_id, $gender);

		return $percentile->age;
	}

	/** Find the maximum 50-percentile age given the test and gender */
	public function max_50percentile_age($testcat_id, $gender)
	{
		if ($this->gender_where($testcat_id)) $this->db->where('gender', $gender);
		$this->db->where('percentile', 50);		// 50-th percentile
		$this->db->where('testcat_id', $testcat_id);
		$this->db->order_by('age', 'desc');
		$this->db->limit(1);
		$percentile = $this->db->get('percentile')->row();
		return $percentile->age;
	}

	private function gender_where($testcat_id)
	{
		$this->db->where('testcat_id', $testcat_id);
		$this->db->limit(1);
		$percentile = $this->db->get('percentile')->row();
		return !empty($percentile->gender);
	}
}