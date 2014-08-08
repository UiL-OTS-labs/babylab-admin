<?php
class RelationModel extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/////////////////////////
	// CRUD-actions
	/////////////////////////

	/** Returns all relations as an array. */
	public function get_all_relations()
	{
		return $this->db->get('relation')->result();
	}

	/** Returns all relations as an array. */
	public function get_relations_by_type($relation_type)
	{
		$this->db->where('relation', $relation_type);
		return $this->db->get('relation')->result();
	}

	/** Adds an relation to the DB */
	public function add_relation($experiment1, $experiment2, $relation_type)
	{
		$relation = array(
			'experiment_id' 	=> $experiment1, 
			'relation'		 	=> $relation_type, 
			'rel_exp_id' 		=> $experiment2
		);
		$this->db->insert('relation', $relation);
		return $this->db->insert_id();
	}

	/** Updates the relation specified by the id with the details of the relation */
	public function update_relation($relation_id, $relation)
	{
		$this->db->where('id', $relation_id);
		$this->db->update('relation', $relation);
	}

	/** Deletes the specified relation */
	public function delete_relation($relation_id)
	{
		$this->db->delete('relation', array('id' => $relation_id));
	}

	/** Adds an relation to the DB */
	public function delete_relation_from_experiment($experiment1, $experiment2, $relation_type)
	{
		$where = array(
			'experiment_id' 	=> $experiment1, 
			'relation'		 	=> $relation_type, 
			'rel_exp_id' 		=> $experiment2
		);
		$this->db->delete('relation', $where);
	}

	/** Returns the relation for an id */
	public function get_relation_by_id($relation_id)
	{
		return $this->db->get_where('relation', array('id' => $relation_id))->row();
	}

	/////////////////////////
	// Experiments
	/////////////////////////

	/** Update relations, delete if current relations is not in line with selected ones */
	public function update_relations($experiment_id, $relations, $relation_type)
	{
		$current_relation_ids = $this->get_relation_ids_by_experiment($experiment_id, $relation_type);

		$relations = empty($relations) ? array() : $relations;
		foreach ($relations as $relation_id)
		{
			if (!in_array($relation_id, $current_relation_ids))
			{
				$this->add_relation($experiment_id, $relation_id, $relation_type);
			}
			$current_relation_ids = array_diff($current_relation_ids, array($relation_id));
		}

		foreach ($current_relation_ids as $relation_id)
		{
			$this->delete_relation_from_experiment($experiment_id, $relation_id, $relation_type);
		}
	}

	/** Returns relation id's per experiment as an array. */
	public function get_relation_ids_by_experiment($experiment_id, $relation_type, $reversed = FALSE)
	{
		$this->db->where('relation', $relation_type);
		$this->db->where($reversed ? 'rel_exp_id' : 'experiment_id', $experiment_id);
		$relations = $this->db->get('relation')->result();
		return get_object_ids($relations, $reversed ? 'experiment_id' : 'rel_exp_id');
	}
}