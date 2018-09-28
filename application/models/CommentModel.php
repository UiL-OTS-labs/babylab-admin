<?php
class CommentModel extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/////////////////////////
	// CRUD-actions
	/////////////////////////

	/** Returns all comments as an array */
	public function get_all_comments($priority_only)
	{
		if ($priority_only) {
			$this->db->where('priority', 1);
		}
		return $this->db->get('comment')->result();
	}

	/** Adds a comment to the DB */
	public function add_comment($comment)
	{
		$this->db->insert('comment', $comment);
		return $this->db->insert_id();
	}

	/** Updates the comment specified by the id with the details of the comment */
	public function update_comment($comment_id, $comment)
	{
		$this->db->where('id', $comment_id);
		$this->db->update('comment', array('body' => $comment));
	}

	/** Deletes a comment from the DB */
	public function delete_comment($comment_id)
	{
		$this->db->delete('comment', array('id' => $comment_id));
	}

	/** Returns the comment for an id */
	public function get_comment_by_id($comment_id)
	{
		return $this->db->get_where('comment', array('id' => $comment_id))->row();
	}

	/////////////////////////
	// Other actions
	/////////////////////////

	/** Prioritizes (or downgrades the priority) a comment */
	public function prioritize($comment_id, $up = TRUE)
	{
		$this->db->where('id', $comment_id);
		$this->db->update('comment', array('priority' => $up));
	}

	/** Mark a comment as handled (or not) */
	public function mark_handled($comment_id, $handled)
	{
		$this->db->where('id', $comment_id);
		$this->db->update('comment', array('handled' => $handled));
	}

	/////////////////////////
	// Participants
	/////////////////////////

	/** Returns the comments for a participant */
	public function get_comments_by_participant($participant_id)
	{
		$this->db->where('participant_id', $participant_id);
		return $this->db->get('comment')->result();
	}

	/** Returns the participant for a comment */
	public function get_participant_by_comment($comment)
	{
		return $this->db->get_where('participant', array('id' => $comment->participant_id))->row();
	}

	/////////////////////////
	// Users
	/////////////////////////

	/** Returns the comments for a user */
	public function get_comments_by_user($user_id)
	{
		$this->db->where('user_id', $user_id);
		return $this->db->get('comment')->result();
	}

	/** Returns the user for a comment */
	public function get_user_by_comment($comment)
	{
		return $this->db->get_where('user', array('id' => $comment->user_id))->row();
	}
}