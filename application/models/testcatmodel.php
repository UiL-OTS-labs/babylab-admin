<?php
class TestCatModel extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/////////////////////////
	// CRUD-actions
	/////////////////////////

	/** Returns all testcats as an array */
	public function get_all_testcats()
	{
		return $this->db->get('testcat')->result();
	}

	/** Adds a testcat to the DB */
	public function add_testcat($testcat)
	{
		$this->db->insert('testcat', $testcat);
		return $this->db->insert_id();
	}

	/** Updates the testcat specified by the id with the details of the testcat */
	public function update_testcat($testcat_id, $testcat)
	{
		$this->db->where('id', $testcat_id);
		$this->db->update('testcat', $testcat);
	}

	/** Deletes a testcat from the DB */
	public function delete_testcat($testcat_id)
	{
		// Find children
		$children = $this->get_children($testcat_id);

		// Delete recursively
		foreach ($children as $child) {
			delete_testcat($child->id);
		}

		// Delete references to children
		$this->db->delete('score', array('testcat_id' => $testcat_id));
		$this->db->delete('percentile', array('testcat_id' => $testcat_id));

		$this->db->delete('testcat', array('id' => $testcat_id));
	}

	/** Returns the testcat for an id */
	public function get_testcat_by_id($testcat_id)
	{
		return $this->db->get_where('testcat', array('id' => $testcat_id))->row();
	}

	/** Returns the testcat for a test and a code (unique key) */
	public function get_testcat_by_code($test, $code)
	{
		return $this->db->get_where('testcat', array('test_id' => $test->id, 'code' => $code))->row();
	}

	/** Returns the testcat for a test and a limesurvey question id (unique key) */
	public function get_testcat_by_question_id($test, $question_id)
	{
		return $this->db->get_where('testcat', array('test_id' => $test->id, 'limesurvey_question_id' => $question_id))->row();
	}

	/////////////////////////
	// Parents/children
	/////////////////////////

	/** Returns all testcat roots as an array */
	public function get_all_testcat_roots()
	{
		$this->db->where('parent_id', NULL);
		return $this->db->get('testcat')->result();
	}

	/** Returns all testcat children as an array */
	public function get_all_testcat_children()
	{
		$this->db->where('parent_id IS NOT NULL', NULL);
		return $this->db->get('testcat')->result();
	}

	/** Returns the parent for a test category, NULL if none exists */
	public function get_parent($testcat)
	{
		return $this->db->get_where('testcat', array('id' => $testcat->parent_id))->row();
	}

	/** Returns TRUE if the test category has a parent, FALSE otherwise */
	public function has_parent($testcat)
	{
		$parent = $this->get_parent($testcat);
		return !empty($parent);
	}

	/** Returns the children for a test category, NULL if none exists */
	public function get_children($testcat_id)
	{
		$this->db->where('parent_id', $testcat_id);
		return $this->db->get('testcat')->result();
	}

	/** Returns TRUE if the test category has children, FALSE otherwise */
	public function has_children($testcat_id)
	{
		return $this->get_children($testcat_id) != NULL;
	}

	/////////////////////////
	// Tests
	/////////////////////////

	/** Returns the test categories for a test, ordered by code */
	public function get_testcats_by_test($test_id, $select_roots = FALSE, $select_children = FALSE)
	{
		$this->db->where('test_id', $test_id);
		if ($select_roots) $this->db->where('parent_id', NULL);
		else if ($select_children) $this->db->where('parent_id IS NOT NULL', NULL);
		$this->db->order_by('code');
		return $this->db->get('testcat')->result();
	}

	/** Returns the test for a test category */
	public function get_test_by_testcat($testcat)
	{
		return $this->db->get_where('test', array('id' => $testcat->test_id))->row();
	}

	/////////////////////////
	// Scores
	/////////////////////////

	/** Returns the total score (an array) for a testcat per testinvite */
	public function total_score($testcat_id, $testinvite_id)
	{
		$children = $this->get_children($testcat_id);
		$children_ids = get_object_ids($children);

		$this->db->select_sum('score');
		$this->db->select_max('date');
		$this->db->where_in('testcat_id', $children_ids);
		$this->db->where('testinvite_id', $testinvite_id);
		return $this->db->get('score')->row();
	}

	/** Returns the total score (an array) for a testcat per participant */
	public function total_score_per_testinvite($testcat_id)
	{
		$children = $this->get_children($testcat_id);
		$children_ids = get_object_ids($children);

		$this->db->select('testinvite_id');
		$this->db->select_sum('score');
		$this->db->select_max('date');
		$this->db->where_in('testcat_id', $children_ids);
		$this->db->group_by('testinvite_id');
		return $this->db->get('score')->result();
	}

	/** Returns the average score for a testcat */
	public function avg_score($testcat_id)
	{
		$this->db->select_avg('score');
		$this->db->where('testcat_id', $testcat_id);
		return round($this->db->get('score')->row()->score, 2, PHP_ROUND_HALF_UP);
	}

	/** Returns the minimum score for a testcat */
	public function min_score($testcat_id)
	{
		$this->db->select_min('score');
		$this->db->where('testcat_id', $testcat_id);
		return round($this->db->get('score')->row()->score, 2, PHP_ROUND_HALF_UP);
	}

	/** Returns the maximum score for a testcat */
	public function max_score($testcat_id)
	{
		$this->db->select_max('score');
		$this->db->where('testcat_id', $testcat_id);
		return round($this->db->get('score')->row()->score, 2, PHP_ROUND_HALF_UP);
	}
}