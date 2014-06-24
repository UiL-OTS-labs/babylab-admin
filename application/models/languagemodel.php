<?php
class LanguageModel extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}
	
	/////////////////////////
	// CRUD-actions
	/////////////////////////

	/** Returns all languages as an array */
	public function get_all_languages($priority_only)
	{
		if ($priority_only) {
			$this->db->where('priority', 1); 
		}
		return $this->db->get('language')->result();
	}
	
	/** Adds a language to the DB */
	public function add_language($language)
	{
		$this->db->insert('language', $language);
		return $this->db->insert_id();
	}
	
	/** Deletes a language from the DB */
	public function delete_language($language_id)
	{
		$this->db->delete('language', array('id' => $language_id));
	}
	
	/** Deletes a language from the DB */
	public function delete_languages_by_participant($participant_id)
	{
		$this->db->delete('language', array('participant_id' => $participant_id));
	}
	
	/** Returns the language for an id */ 
	public function get_language_by_id($language_id) 
	{
		return $this->db->get_where('language', array('id' => $language_id))->row();
	}
	
	/////////////////////////
	// Participants
	/////////////////////////
	
	/** Returns the languages for a participant */
	public function get_languages_by_participant($participant_id)
	{
		$this->db->where('participant_id', $participant_id);
		return $this->db->get('language')->result();
	}

	/** Returns the participant for a language */
	public function get_participant_by_language($language)
	{
		return $this->db->get_where('participant', array('id' => $language->participant_id))->row();
	}
}