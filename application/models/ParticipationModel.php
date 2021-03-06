<?php

class ParticipationModel extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
	}

	/////////////////////////
	// CRUD-actions
	/////////////////////////

	/** Returns all participations */
	public function get_all_participations()
	{
		return $this->db->get('participation')->result();
	}

	/** Returns all participations that have been confirmed, but no other action has been taken */
	public function get_confirmed_participations($experiments = array())
	{
		if ($experiments)
		{
			$this->db->where_in('experiment_id', get_object_ids($experiments));
		}

		$this->db->where(array('confirmed' => 1, 'cancelled' => 0, 'noshow' => 0, 'completed' => 0));
		return $this->db->get('participation')->result();
	}

	/** Returns all participations that have been confirmed and completed */
	public function get_completed_participations()
	{
		$this->db->where(array('confirmed' => 1, 'cancelled' => 0, 'noshow' => 0, 'completed' => 1));
		return $this->db->get('participation')->result();
	}

	/** Creates a participation, returns the id of the created participation */
	public function create_participation($experiment, $participant)
	{
		$risk = NULL;
		if ($experiment->dyslexic)
			$risk = !empty($participant->dyslexicparent);
		if ($experiment->multilingual)
			$risk = $participant->multilingual;
		if ($experiment->dyslexic && $experiment->multilingual)
			$risk = !empty($participant->dyslexicparent) && $participant->multilingual;

		$participation = array(
			'experiment_id' => $experiment->id,
			'participant_id' => $participant->id,
			'risk' => $risk
		);
		$this->db->insert('participation', $participation);
		return $this->db->insert_id();
	}

	/** Updates the participation specified by the id with the details of the participation */
	public function update_participation($participation_id, $participation)
	{
		$this->db->where('id', $participation_id);
		$this->db->update('participation', $participation);
	}

	/** Deletes a participation from the DB */
	public function delete_participation($participation_id)
	{
		// Delete references to calls
		$this->db->delete('call', array('participation_id' => $participation_id));

		$this->db->delete('participation', array('id' => $participation_id));
	}

	/** Retrieves a participation for a given ID */
	public function get_participation_by_id($participation_id)
	{
		$this->db->where('id', $participation_id);
		return $this->db->get('participation')->row();
	}

	/** Retrieves a participation for an experiment and a participant */
	public function get_participation($experiment_id, $participant_id)
	{
		$this->db->where('experiment_id', $experiment_id);
		$this->db->where('participant_id', $participant_id);
		return $this->db->get('participation')->row();
	}

	/** Retrieves or creates a participation ID for an experiment and a participant */
	public function get_or_create_participation($experiment, $participant)
	{
		$p = $this->get_participation($experiment->id, $participant->id);
		if ($p)
		{
			return $p->id;
		}
		else
		{
			return $this->create_participation($experiment, $participant);
		}
	}

	/////////////////////////
	// Filter for experiments and participants
	/////////////////////////

	/** Retrieves all participations for multiple participants in an experiment */
	public function filter_participations($experiment_ids, $participant_ids, $leader_ids, $exclude_canceled = TRUE, $date_from = NULL, $date_to = NULL)
	{
		if ($experiment_ids)
			$this->db->where_in('experiment_id', $experiment_ids);
		if ($participant_ids)
			$this->db->where_in('participant_id', $participant_ids);
		if ($leader_ids)
			$this->db->where_in('user_id_leader', $leader_ids);
		if ($exclude_canceled)
			$this->db->where('(appointment IS NOT NULL)');
		else
			$this->db->where('(appointment IS NOT NULL OR cancelled = 1)');
		if ($date_from)
			$this->db->where('appointment >=', $date_from);
		if ($date_to)
			$this->db->where('appointment <=', $date_to);
		return $this->db->get('participation')->result();
	}

	/////////////////////////
	// Experiments
	/////////////////////////

	/** Retrieves all participations for an experiment, with optional parameter to select risk/control group */
	public function get_participations_by_experiment($experiment_id, $risk = NULL)
	{
		$this->db->where('experiment_id', $experiment_id);
		if (isset($risk))
			$this->db->where('risk', $risk);
		// exclude empty participations (and also do not include cancelled participations)
		$this->db->where('appointment IS NOT NULL');
		return $this->db->get('participation')->result();
	}

	/** Retrieves an experiment by the participation id */
	public function get_experiment_by_participation($participation_id)
	{
		$participation = $this->get_participation_by_id($participation_id);
		$e_id = $participation->experiment_id;
		return $this->db->get_where('experiment', array('id' => $e_id))->row();
	}

	/////////////////////////
	// Participants
	/////////////////////////

	/** Retrieves all participations for an participant */
	public function get_participations_by_participant($participant_id, $exclude_empty = FALSE)
	{
		$this->db->where('participant_id', $participant_id);
		if ($exclude_empty)
			$this->db->where('(appointment IS NOT NULL OR cancelled = 1)');
		return $this->db->get('participation')->result();
	}

	/** Retrieves a participant by the participation id */
	public function get_participant_by_participation($participation_id)
	{
		$participation = $this->get_participation_by_id($participation_id);
		$p_id = $participation->participant_id;
		return $this->db->get_where('participant', array('id' => $p_id))->row();
	}

	/////////////////////////
	// Leaders
	/////////////////////////

	/** Retrieves all participations for a leader */
	public function get_participations_by_user($user_id)
	{
		$this->db->where('user_id_leader', $user_id);
		return $this->db->get('participation')->result();
	}

	/** Retrieves a user by the participation id */
	public function get_user_by_participation($participation_id)
	{
		$participation = $this->get_participation_by_id($participation_id);
		return $this->db->get_where('user', array('id' => $participation->user_id_leader))->row();
	}

	/////////////////////////
	// No-shows
	/////////////////////////

	/** Retrieves a sum of participations for an experiment, given a count column */
	public function count_participations($count_column, $experiment_id = NULL)
	{
		$this->db->select('participant_id');
		$this->db->select_sum('confirmed', 'count');
		$this->db->select_sum($count_column, 'count_column');
		if (isset($experiment_id))
			$this->db->where('experiment_id', $experiment_id);
		$this->db->where('cancelled', FALSE);
		$this->db->group_by('participant_id');
		$this->db->having('count_column > 0');

		return $this->db->get('participation')->result();
	}

	/////////////////////////
	// Confirming/Cancelling the participation
	/////////////////////////

	/** Makes an appointment for the specified participation */
	public function confirm($participation_id, $appointment, $leader_id)
	{
		$this->db->where('id', $participation_id);
		$this->db->update('participation', array(
			'confirmed' => 1,
			'appointment' => $appointment,
			'user_id_leader' => $leader_id,
			'status' => ParticipationStatus::CONFIRMED));

		$this->update_nr_calls($participation_id);
	}

	/** Reschedules an appointment for the specified participation */
	public function reschedule($participation_id, $appointment)
	{
		$this->db->where('id', $participation_id);
		$this->db->update('participation', array(
			'appointment' => $appointment,
			'status' => ParticipationStatus::RESCHEDULED));
	}

	/** Cancels the specified participation */
	public function cancel($participation_id, $called = TRUE)
	{
		$this->db->where('id', $participation_id);
		$this->db->update('participation', array(
			'cancelled' => 1,
			'noshow' => 0,
			'completed' => 0,
			'appointment' => NULL,
			'status' => ParticipationStatus::CANCELLED));

		if ($called)
			$this->update_nr_calls($participation_id);
	}

	/** Handles a no reply for the specified participation */
	public function no_reply($participation_id)
	{
		$this->update_nr_calls($participation_id);

		$this->db->where('id', $participation_id);
		$this->db->update('participation', array('status' => ParticipationStatus::UNCONFIRMED));
	}

	/** Updates the number of calls for the participation, returns the current number of calls. */
	private function update_nr_calls($participation_id)
	{
		$participation = $this->get_participation_by_id($participation_id);
		$nrcalls = $participation->nrcalls + 1;

		$this->db->where('id', $participation_id);
		$this->db->update('participation', array(
			'nrcalls' => $nrcalls,
			'lastcalled' => input_datetime()));

		return $nrcalls;
	}

	/////////////////////////
	// Completing the participation
	/////////////////////////

	/** No-shows the specified participation */
	public function no_show($participation_id)
	{
		$this->db->where('id', $participation_id);
		$this->db->update('participation', array(
			'cancelled' => 0,
			'noshow' => 1,
			'completed' => 0,
			'status' => ParticipationStatus::NO_SHOW));
	}

	/** Completes the specified participation */
	public function completed($participation_id, $participation)
	{
		$this->db->where('id', $participation_id);
		$this->db->update('participation', array_merge($participation, array(
			'cancelled' => 0,
			'noshow' => 0,
			'completed' => 1,
			'status' => ParticipationStatus::COMPLETED)));
	}

	/** Adds a technical message to the specified participation */
	public function add_tech_message($participation_id, $tech_comment)
	{
		$this->db->where('id', $participation_id);
		$this->db->update('participation', array(
			'tech_problems' => 1,
			'tech_comment' => $tech_comment));
	}

	/////////////////////////
	// Locking
	/////////////////////////

	/** Locks the participation for 30 minutes */
	public function lock($participation_id)
	{
		$this->db->where('id', $participation_id);
		$this->db->update('participation', array('locktime' => input_datetime('+30 minutes')));
	}

	/** Checks whether the participation is locked */
	public function is_locked($participation)
	{
		if (empty($participation))
			return FALSE;
		$time = $participation->locktime;
		if ($time == NULL)
			return FALSE;
		if ($time <= input_datetime())
		{
			$this->release_lock($participation->id);
			return FALSE;
		}
		return TRUE;
	}

	/** Checks whether there is a lock for the current participant */
	public function is_locked_participant($participant_id, $experiment_id)
	{
		$participations = $this->get_participations_by_participant($participant_id);
		foreach ($participations as $participation)
		{
			if ($participation->experiment_id == $experiment_id)
				continue;
			if ($this->is_locked($participation))
				return TRUE;
		}
		return FALSE;
	}

	/** Releases the lock of the participation */
	public function release_lock($participation_id)
	{
		$this->db->where('id', $participation_id);
		$this->db->update('participation', array('locktime' => NULL));
	}

	/////////////////////////
	// Call-backs
	/////////////////////////

	/** Releases the lock of the participation */
	public function count_to_be_called_back($call_back_date = NULL)
	{
		$experiment_ids = $this->callerModel->get_experiment_ids_by_caller(current_user_id());

		$this->db->join('participant', 'participant.id = participation.participant_id');
		$this->db->join('experiment', 'experiment.id = participation.experiment_id');
		$this->db->join('call', 'call.participation_id = participation.id AND TIMESTAMPDIFF(MINUTE, call.timeend, participation.lastcalled) <= 1');
		$this->db->where('call.status', CallStatus::CALL_BACK);
		if ($experiment_ids)
		{
			$this->db->where('experiment_id IN (' . implode(',', $experiment_ids) . ')');
		}
		else
		{
			$this->db->where('experiment_id IS NULL');
		}
		if ($call_back_date)
			$this->db->where('call_back_date', $call_back_date);

		return $this->db->count_all_results('participation');
	}

}
