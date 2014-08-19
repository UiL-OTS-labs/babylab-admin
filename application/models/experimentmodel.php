<?php
class ExperimentModel extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/////////////////////////
	// CRUD-actions
	/////////////////////////

	/**
	 * Returns all experiments as an array.
	 * If $include_archived is FALSE, return only unarchived experiments.
	 */
	public function get_all_experiments($include_archived = TRUE)
	{
		if (!$include_archived) $this->db->where('archived', 0);
		return $this->db->get('experiment')->result();
	}

	/** Adds an experiment to the DB */
	public function add_experiment($experiment)
	{
		$this->db->insert('experiment', $experiment);
		return $this->db->insert_id();
	}

	/** Updates the experiment specified by the id with the details of the experiment */
	public function update_experiment($experiment_id, $experiment)
	{
		$this->db->where('id', $experiment_id);
		$this->db->update('experiment', $experiment);
	}

	/** Deletes the specified experiment */
	public function delete_experiment($experiment_id)
	{
		// Delete references to callers
		$this->db->delete('caller', array('experiment_id' => $experiment_id));

		// Delete references to leaders
		$this->db->delete('leader', array('experiment_id' => $experiment_id));

		// Delete references to participations
		$this->db->delete('participation', array('experiment_id' => $experiment_id));

		// Delete references to relations
		$this->db->delete('relation', array('experiment_id' => $experiment_id));
		$this->db->delete('relation', array('rel_exp_id' => $experiment_id));

		$this->db->delete('experiment', array('id' => $experiment_id));
	}

	/** Returns the experiment for an id */
	public function get_experiment_by_id($experiment_id)
	{
		return $this->db->get_where('experiment', array('id' => $experiment_id))->row();
	}

	/////////////////////////
	// Other actions
	/////////////////////////

	/** Archives/unarchives the specified experiment */
	public function archive($experiment_id, $archived)
	{
		$this->db->where('id', $experiment_id);
		$this->db->update('experiment', array('archived' => $archived));
	}

	/////////////////////////
	// Participations
	/////////////////////////

	/** Returns the number of participations for the given experiment */
	public function count_participations($experiment_id)
	{
		$this->db->where('experiment_id', $experiment_id);
		$this->db->from('participation');
		return $this->db->count_all_results();
	}

	/////////////////////////
	// Participants
	/////////////////////////

	/** Returns all experiments for a participant */
	public function get_experiments_by_participant($participant_id)
	{
		$this->db->where('participant_id', $participant_id);
		$participations = $this->db->get('participation')->result();

		$experiments = array();
		foreach ($participations as $p)
		{
			array_push($experiments, $this->get_experiment_by_id($p->experiment_id));
		}
		return $experiments;
	}
	
	/** Returns all participants for an experiment */
	public function get_participants_by_experiment($experiment_id)
	{
		$this->db->select('participant.*');
		$this->db->join('participation', 'participation.participant_id = participant.id');
		$this->db->where('participation.experiment_id', $experiment_id);
		$this->db->where('participation.completed', '1');
		return $this->db->get('participant')->result();
	}
}