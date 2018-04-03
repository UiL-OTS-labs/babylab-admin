<?php
class ScoreModel extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/////////////////////////
	// CRUD-actions
	/////////////////////////

	/** Returns all scores as an array */
	public function get_all_scores()
	{
		return $this->db->get('score')->result();
	}

	/** Adds a score to the DB */
	public function add_score($score)
	{
		$this->db->insert('score', $score);
		return $this->db->insert_id();
	}

	/** Updates the score specified by the id with the details of the score */
	public function update_score($score_id, $score)
	{
		$this->db->where('id', $score_id);
		$this->db->update('score', $score);
	}

	/** Adds a score to the DB, updates when already exists */
	public function add_or_update_score($score)
	{
		// TODO: has to be changed
		$s = $this->get_score($score['testcat_id'], $score['participant_id']);

		if ($s)
		{
			$this->update_score($s->id, $score);
			return $s->id;
		}
		else return $this->add_score($score);
	}

	/** Deletes a score from the DB */
	public function delete_score($score_id)
	{
		$this->db->delete('score', array('id' => $score_id));
	}

	/** Returns the score for an id */
	public function get_score_by_id($score_id)
	{
		return $this->db->get_where('score', array('id' => $score_id))->row();
	}

	/** Retrieves a score for a test category and a testinvite (unique key) */
	public function get_score_by_testcat_testinvite($testcat_id, $testinvite_id)
	{
		$this->db->where('testcat_id', $testcat_id);
		$this->db->where('testinvite_id', $testinvite_id);
		return $this->db->get('score')->row();
	}

	/////////////////////////
	// Testinvites
	/////////////////////////

	/** Returns the scores for a testinvite */
	public function get_scores_by_testinvite($testinvite_id)
	{
		$this->db->where('testinvite_id', $testinvite_id);
		return $this->db->get('score')->result();
	}

	/** Returns the testinvite for a score */
	public function get_testinvite_by_score($score)
	{
		return $this->db->get_where('testinvite', array('id' => $score->testinvite_id))->row();
	}

	/////////////////////////
	// Testsurvey
	/////////////////////////

	/** Returns the scores for a testsurvey */
	public function get_scores_by_testsurvey($testsurvey_id)
	{
		$this->db->join('testinvite', 'score.testinvite_id = testinvite.id');
		$this->db->join('testsurvey', 'testinvite.testsurvey_id = testsurvey.id');
		$this->db->where('testsurvey.id', $testsurvey_id);
		return $this->db->get('score')->result();
	}

	/** Returns the testsurvey for a score */
	public function get_testsurvey_by_score($score)
	{
		$this->db->select('testsurvey.*');
		$this->db->from('testsurvey');
		$this->db->join('testinvite', 'testinvite.testsurvey_id = testsurvey.id');
		$this->db->join('score', 'score.testinvite_id = testinvite.id');
		$this->db->where('testinvite.id', $score->testinvite_id);
		return $this->db->get()->row();
	}

	/////////////////////////
	// Participants
	/////////////////////////

	/** Returns the scores for a participant */
	public function get_scores_by_participant($participant_id)
	{
		$this->db->select('score.*');
		$this->db->join('testinvite', 'score.testinvite_id = testinvite.id');
		$this->db->join('participant', 'testinvite.participant_id = participant.id');
		$this->db->where('participant.id', $participant_id);
		return $this->db->get('score')->result();
	}

	/** Returns the participant for a score */
	public function get_participant_by_score($score)
	{
		$this->db->select('participant.*');
		$this->db->join('testinvite', 'testinvite.participant_id = participant.id');
		$this->db->where('testinvite.id', $score->testinvite_id);
		return $this->db->get('participant')->row();
	}

	/////////////////////////
	// Test categories
	/////////////////////////

	/** Returns all scores for a test category */
	public function get_scores_by_testcat($testcat_id)
	{
		$this->db->where('testcat_id', $testcat_id);
		return $this->db->get('score')->result();
	}

	/** Returns all scores for a test category, including that of its children */
	public function get_all_scores_by_testcat($testcat_id)
	{
		$this->db->select('score.*');
		$this->db->from('score');
		$this->db->join('testcat', 'score.testcat_id = testcat.id');
		$this->db->where('testcat.id', $testcat_id);
		$this->db->or_where('testcat.parent_id', $testcat_id);
		return $this->db->get()->result();
	}

	/** Returns the test category for a score */
	public function get_testcat_by_score($score)
	{
		return $this->db->get_where('testcat', array('id' => $score->testcat_id))->row();
	}

	/** Returns the score for a test category and an array fo testinvite ids */
	public function get_score($testcat_id, $testinvite_ids)
	{
		# assume no testinvite ids that appear in an emtpy array can be found
		if(empty($testinvite_ids)) return NULL;

		$this->db->select('score');
		$this->db->where('testcat_id', $testcat_id);
		$this->db->where_in('testinvite_id', $testinvite_ids);
		return $this->db->get()->row();
	}

	/////////////////////////
	// Tests
	/////////////////////////

	/** Returns all scores for a test */
	public function get_scores_by_test($test_id)
	{
		$this->db->join('testcat', 'score.testcat_id = testcat.id');
		$this->db->join('test', 'testcat.test_id = test.id');
		$this->db->where('test.id', $test_id);
		return $this->db->get('score')->result();
	}

	/** Returns the test for a score */
	public function get_test_by_score($score)
	{
		$this->db->select('test.*');
		$this->db->from('test');
		$this->db->join('testcat', 'testcat.test_id = test.id');
		$this->db->join('score', 'score.testcat_id = testcat.id');
		$this->db->where('testcat.id', $score->testcat_id);
		return $this->db->get()->row();
	}
}