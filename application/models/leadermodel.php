<?php
class LeaderModel extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/////////////////////////
	// CRUD-actions
	/////////////////////////

	/** Returns all leaders as an array */
	public function get_all_leaders()
	{
		return $this->db->get('leader')->result();
	}

	/** Deletes a leader from the DB */
	public function delete_leader($leader_id)
	{
		$this->db->delete('leader', array('id' => $leader_id));
	}

	/////////////////////////
	// Users
	/////////////////////////

	/** Returns the leaders for a user */
	public function get_leaders_by_user($user_id)
	{
		$this->db->where('user_id_leader', $user_id);
		return $this->db->get('leader')->result();
	}

	/** Returns the user for a leader */
	public function get_user_by_leader($leader)
	{
		return $this->db->get_where('user', array('id' => $leader->user_id_leader))->row();
	}

	/** Deletes all leaders from the DB for a user */
	public function delete_leaders_by_user($user_id)
	{
		$this->db->where('user_id_leader', $user_id);
		$this->db->delete('leader');
	}

	/////////////////////////
	// Experiments
	/////////////////////////

	public function is_leader_for_experiment($user_id, $experiment_id)
	{
		$this->db->where('user_id_leader', $user_id);
		$this->db->where('experiment_id', $experiment_id);
		return $this->db->get('leader')->num_rows() > 0;
	}

	/** Adds a leader to an experiment */
	public function add_leader_to_experiment($experiment_id, $leader_id)
	{
		$data = array(
				'experiment_id'  => $experiment_id,
				'user_id_leader' => $leader_id
		);
		$this->db->insert('leader', $data);
	}

	/** Deletes a leader from an experiment */
	public function delete_leader_from_experiment($experiment_id, $leader_id)
	{
		$where = array(
				'experiment_id'  => $experiment_id,
				'user_id_leader' => $leader_id
		);
		$this->db->delete('leader', $where);
	}

	/** Returns the number of leaders for the given experiment */
	public function count_leaders($experiment_id)
	{
		$this->db->where('experiment_id', $experiment_id);
		$this->db->from('leader');
		return $this->db->count_all_results();
	}

	/** Returns all leaders for the given experiment */
	public function get_leaders_by_experiment($experiment_id)
	{
		$this->db->where('experiment_id', $experiment_id);
		return $this->db->get('leader')->result();
	}

	/** Returns all leader id's for the given experiment */
	public function get_leader_ids_by_experiment($experiment_id)
	{
		$leaders = $this->get_leaders_by_experiment($experiment_id);
		return get_object_ids($leaders, 'user_id_leader');
	}

	/** Returns all leader users for the given experiment */
	public function get_leader_users_by_experiment($experiment_id)
	{
		$this->db->select('user.*');
		$this->db->from('leader');
		$this->db->join('user', 'user.id = leader.user_id_leader');
		$this->db->where('experiment_id', $experiment_id);
		return $this->db->get()->result(); 
	}

	/** Returns all leader users for the given experiments */
	public function get_leader_users_by_experiments($experiment_ids)
	{
		$this->db->select('user.*');
		$this->db->from('leader');
		$this->db->join('user', 'user.id = leader.user_id_leader');
		$this->db->where_in('experiment_id', $experiment_ids);
		return $this->db->get()->result(); 
	}

	/** Returns all leader mails for the given experiment */
	public function get_leader_emails_by_experiment($experiment_id)
	{
		return get_object_ids($this->get_leaders_by_experiment($experiment_id), 'email');
	}

	/** Returns all experiments for a user */
	public function get_experiments_by_leader($user_id, $include_archived = FALSE)
	{
		$this->db->where('user_id_leader', $user_id);
		$leaders = $this->db->get('leader')->result();

		$experiments = array();
		foreach ($leaders as $l)
		{
			$experiment = $this->experimentModel->get_experiment_by_id($l->experiment_id);
			if ($include_archived || !$experiment->archived) 
			{
				array_push($experiments, $experiment);
			}
		}
		return $experiments;
	}

	/** Returns all experiment id's for a leader */
	public function get_experiment_ids_by_leader($user_id)
	{
		$leaders = $this->get_leaders_by_user($user_id);
		return get_object_ids($leaders, 'experiment_id');
	}

	/** Returns all experiments without a leader */
	public function get_experiments_without_leaders()
	{
		$experiments = $this->experimentModel->get_all_experiments();

		$result = array();
		foreach ($experiments as $experiment)
		{
			if ($this->count_leaders($experiment->id) == 0)
			{
				array_push($result, $experiment);
			}
		}
		return $result;
	}

	/** Update leaders, delete if current leaders is not in line with selected ones */
	public function update_leaders($experiment_id, $leaders)
	{
		$current_leader_ids = $this->get_leader_ids_by_experiment($experiment_id);

		$leaders = empty($leaders) ? array() : $leaders;
		foreach ($leaders as $leader_id)
		{
			if (!in_array($leader_id, $current_leader_ids))
			{
				$this->add_leader_to_experiment($experiment_id, $leader_id);
			}
			$current_leader_ids = array_diff($current_leader_ids, array($leader_id));
		}

		foreach ($current_leader_ids as $leader_id)
		{
			$this->delete_leader_from_experiment($experiment_id, $leader_id);
		}
	}
}