<?php
class CallerModel extends CI_Model 
{
	public function __construct()
	{
		parent::__construct();
	}

	/////////////////////////
	// CRUD-actions
	/////////////////////////
	
	/** Returns all callers as an array */
	public function get_all_callers() 
	{ 
		return $this->db->get('caller')->result();
	}
	
	/** Deletes a caller from the DB */
	public function delete_caller($caller_id)
	{
		$this->db->delete('caller', array('id' => $caller_id));
	}
	
	/////////////////////////
	// Users
	/////////////////////////
	
	/** Returns the callers for a user */
	public function get_callers_by_user($user_id)
	{
		$this->db->where('user_id_caller', $user_id);
		return $this->db->get('caller')->result();
	}
	
	/** Returns the user for a caller */
	public function get_user_by_caller($caller)
	{
		return $this->db->get_where('user', array('id' => $caller->user_id_caller))->row();
	}
	
	/** Deletes all callers from the DB for a user */
	public function delete_callers_by_user($user_id)
	{
		$this->db->where('user_id_caller', $user_id);
		$this->db->delete('caller');
	}
	
	/////////////////////////
	// Experiments
	/////////////////////////
	
	public function is_caller_for_experiment($user_id, $experiment_id) 
	{
		$this->db->where('user_id_caller', $user_id);
		$this->db->where('experiment_id', $experiment_id);
		return $this->db->get('caller')->num_rows() > 0;
	}
	
	/** Adds a caller to an experiment */
	public function add_caller_to_experiment($experiment_id, $caller_id)
	{
		$data = array(
				'experiment_id'	 => $experiment_id,
				'user_id_caller' => $caller_id
		);
		$this->db->insert('caller', $data);
	}

	/** Deletes a caller from an experiment */
	public function delete_caller_from_experiment($experiment_id, $caller_id)
	{
		$where = array(
				'experiment_id'  => $experiment_id,
				'user_id_caller' => $caller_id
		);
		$this->db->delete('caller', $where);
	}

	/** Returns the number of callers for the given experiment */
	public function count_callers($experiment_id)
	{
		$this->db->where('experiment_id', $experiment_id);
		$this->db->from('caller');
		return $this->db->count_all_results();
	}

	/** Returns all callers for the given experiment */
	public function get_callers_by_experiment($experiment_id)
	{
		$this->db->where('experiment_id', $experiment_id);
		return $this->db->get('caller')->result();
	}

	/** Returns all caller id's for the given experiment */
	public function get_caller_ids_by_experiment($experiment_id)
	{
		$callers = $this->get_callers_by_experiment($experiment_id);
		return get_object_ids($callers, 'user_id_caller');
	}

	/** Returns all experiments for a caller */
	public function get_experiments_by_caller($user_id)
	{
		$callers = $this->get_callers_by_user($user_id);

		$experiments = array();
		foreach ($callers as $c) 
		{
			array_push($experiments, $this->experimentModel->get_experiment_by_id($c->experiment_id));
		}
		return $experiments;
	}
	
	/** Returns all experiment id's for a caller */
	public function get_experiment_ids_by_caller($user_id)
	{
		$callers = $this->get_callers_by_user($user_id);
		return get_object_ids($callers, 'experiment_id');
	}

	/** Returns all experiments without a caller */
	public function get_experiments_without_callers()
	{
		$experiments = $this->experimentModel->get_all_experiments();

		$result = array();
		foreach ($experiments as $experiment) 
		{
			if ($this->count_callers($experiment->id) == 0) 
			{
				array_push($result, $experiment);
			}
		}
		return $result;
	}

	/** Update callers, delete if current callers is not in line with selected ones */
	public function update_callers($experiment_id, $callers)
	{
		$current_caller_ids = $this->get_caller_ids_by_experiment($experiment_id);

		$callers = empty($callers) ? array() : $callers;
		foreach ($callers as $caller_id) 
		{
			if (!in_array($caller_id, $current_caller_ids)) 
			{
				$this->add_caller_to_experiment($experiment_id, $caller_id);
			}
			$current_caller_ids = array_diff($current_caller_ids, array($caller_id));
		}

		foreach ($current_caller_ids as $caller_id) 
		{
			$this->delete_caller_from_experiment($experiment_id, $caller_id);
		}
	}
}