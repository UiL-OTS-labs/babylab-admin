<?php
class CallModel extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}
	
	/////////////////////////
	// CRUD-actions
	/////////////////////////

	/** Returns all calls as an array */
	public function get_all_calls()
	{
		return $this->db->get('call')->result();
	}
	
	/** Creates a call, returns the id of the created call */
	public function create_call($participation_id)
	{
		$previous_call = $this->last_call($participation_id);
		$nr = empty($previous_call) ? 1 : $previous_call->nr + 1; 
		
		$call = array(
				'participation_id'	=> $participation_id,
				'user_id'			=> current_user_id(),
				'nr' 				=> $nr,
				'status' 			=> CallStatus::CallStarted
		);
		$this->db->insert('call', $call);
		return $this->db->insert_id();
	}

	/** Deletes a call from the DB */
	public function delete_call($call_id)
	{
		$this->db->delete('call', array('id' => $call_id));
	}

	/** Returns the call for an id */
	public function get_call_by_id($call_id)
	{
		return $this->db->get_where('call', array('id' => $call_id))->row();
	}
	
	/////////////////////////
	// Participations
	/////////////////////////

	/** Retrieves all calls for a participation */
	public function get_calls_by_participation($participation_id)
	{
		$this->db->where('participation_id', $participation_id);
		return $this->db->get('call')->result();
	}
	
	/** Retrieves a participation by the call id */
	public function get_participation_by_call($call_id)
	{
		$call = $this->get_call_by_id($call_id);
		return $this->db->get_where('participation', array('id' => $call->participation_id))->row();
	}
	
	/////////////////////////
	// Users
	/////////////////////////

	/** Retrieves all calls for a users */
	public function get_calls_by_user($user_id)
	{
		$this->db->where('user_id', $user_id);
		return $this->db->get('call')->result();
	}
	
	/** Retrieves a user by the call id */
	public function get_user_by_call($call_id)
	{
		$call = $this->get_call_by_id($call_id);
		return $this->db->get_where('user', array('id' => $call->user_id))->row();
	}
	
	/////////////////////////
	// Ending a call
	/////////////////////////

	/** Ends a call */
	public function end_call($call_id, $call_status)
	{
		$this->db->where('id', $call_id);
		$this->db->update('call', array(
			'status' 		=> $call_status,
			'timeend' 		=> input_datetime()
		));
	}
	
	/** Updates the call specified by the id with the details of the call */
	public function update_call($call_id, $call_status)
	{
		$this->db->where('id', $call_id);
		$this->db->update('call', array(
			'status' => $call_status
		));
	}
	
	/////////////////////////
	// Helpers
	/////////////////////////
	
	/** Returns the last call for the given participation */
	public function last_call($participation_id)
	{
		$this->db->where('participation_id', $participation_id);
		$this->db->order_by('timestart', 'DESC');
		$this->db->limit(1);
		$call = $this->db->get('call')->row();
		
		return empty($call) ? NULL : $call;
	}
}