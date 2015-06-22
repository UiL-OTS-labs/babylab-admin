<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Comment extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->authenticate->redirect_except();
		reset_language(current_language());
	}

	/////////////////////////
	// CRUD-actions
	/////////////////////////

	/** Specifies the contents of the default page. */
	public function index()
	{
		$priority_only = is_array($this->input->post('view_high_priority'));
		$handled_only = is_array($this->input->post('view_handled'));

		create_comment_table();
		$data['ajax_source'] = site_url(array('comment', 'table', intval($priority_only), intval($handled_only)));
		$data['page_title'] = lang('comments');
		$data['filter_options'] = array(
			array('name' => 'view_high_priority', 'value' => 1, 'checked' => $priority_only), 
			array('name' => 'view_handled', 'value' => 1, 'checked' => $handled_only)
			);

		$this->load->view('templates/header', $data);
		$this->authenticate->authenticate_redirect('templates/list_view', $data, UserRole::Admin);
		$this->load->view('templates/footer');
	}

	/** Adds a comment for the specified participant */
	public function add_submit($participant_id)
	{
		// Run validation
		if (!$this->validate_comment())
		{
			// Show form again with error messages
			flashdata(validation_errors(), FALSE, 'comment_message');
			redirect($this->agent->referrer(), 'refresh');
		}
		else
		{
			// If succeeded, insert data into database
			$comment = $this->post_comment($participant_id);
			$this->commentModel->add_comment($comment);

			flashdata(lang('comment_added'), TRUE, 'comment_message');
			redirect($this->agent->referrer(), 'refresh');
		}
	}

	/** Specifies the contents of the edit page. */
	public function edit($comment_id)
	{
		$comment = $this->commentModel->get_comment_by_id($comment_id);
		$participant = $this->commentModel->get_participant_by_comment($comment);
		
		$data['page_title'] = sprintf(lang('edit_comment'), name($participant));
		$data['action'] = 'comment/edit_submit/' . $comment_id;
		
		$data['comment'] = $comment->body;
		$data['referrer'] = $this->agent->referrer();

		$this->load->view('templates/header', $data);
		$this->load->view('comment_edit_view', $data);
		$this->load->view('templates/footer');
	}
	
	/** Submits the edit of a comment */
	public function edit_submit($comment_id)
	{
		// Run validation
		if (!$this->validate_comment())
		{
			// Show form again with error messages
			$this->edit($comment_id);
		}
		else
		{
			// If succeeded, insert data into database
			$comment = $this->input->post('comment');
			$this->commentModel->update_comment($comment_id, $comment);

			flashdata(lang('comment_edited'), TRUE, 'comment_message');
			redirect($this->input->post('referrer'), 'refresh');
		}
	}

	/** Deletes the specified comment, and returns to previous page */
	public function delete($comment_id)
	{
		$this->commentModel->delete_comment($comment_id);
		flashdata(lang('comment_deleted'), TRUE, 'comment_message');
		redirect($this->agent->referrer(), 'refresh');
	}

	/////////////////////////
	// Other actions
	/////////////////////////

	/** Prioritizes (or downgrades the priority) a comment */
	public function prioritize($comment_id, $up = TRUE)
	{
		$this->commentModel->prioritize($comment_id, $up);
		$message = $up ? lang('comment_prio_up') : lang('comment_prio_down');
		flashdata($message, TRUE, 'comment_message');
		redirect($this->agent->referrer(), 'refresh');
	}

	public function mark_handled($comment_id, $handled = TRUE)
	{
		$this->commentModel->mark_handled($comment_id, $handled ? input_datetime() : NULL);
		$message = $handled ? lang('comment_marked_handled') : lang('comment_marked_unsettled');
		flashdata($message, TRUE, 'comment_message');
		redirect($this->agent->referrer(), 'refresh');
	}

	/////////////////////////
	// Other views
	/////////////////////////

	/** Specifies the contents of tthe page with only priority items. */
	public function priority()
	{
		$this->index(TRUE);
	}

	/** Specifies the content of a page with the contents for a specific participant */
	public function participant($participant_id)
	{
		$participant = $this->participantModel->get_participant_by_id($participant_id);

		create_comment_table();
		$data['ajax_source'] = 'comment/table/0/0/' . $participant->id;
		$data['page_title'] = sprintf(lang('comments_for'), name($participant));

		$this->load->view('templates/header', $data);
		$this->load->view('templates/list_view', $data);
		$this->load->view('templates/footer');
	}

	/////////////////////////
	// Form handling
	/////////////////////////

	/** Validates a comment */
	private function validate_comment()
	{
		$this->form_validation->set_rules('comment', lang('comment'), 'trim|required');

		return $this->form_validation->run();
	}

	/** Posts the data for a comment */
	private function post_comment($participant_id)
	{
		return array(
				'body'				=> $this->input->post('comment'),
				'participant_id' 	=> $participant_id,
				'user_id'		 	=> current_user_id()
		);
	}

	/////////////////////////
	// Table
	/////////////////////////

	public function table($priority_only = FALSE, $handled_only = FALSE, $participant_id = NULL)
	{
		$this->datatables->select('CONCAT(participant.firstname, " ", participant.lastname) AS p, 
			body, timecreated, username,
			comment.id AS id, participant_id, user_id', FALSE);
		$this->datatables->from('comment');
		$this->datatables->join('participant', 'participant.id = comment.participant_id');
		$this->datatables->join('user', 'user.id = comment.user_id');

		if ($priority_only) $this->datatables->where('priority', TRUE);
		if ($handled_only) $this->datatables->where('handled IS NOT NULL');
		if ($participant_id) $this->datatables->where('participant_id', $participant_id);

		$this->datatables->edit_column('p', '$1', 'participant_get_link_by_id(participant_id)');
		$this->datatables->edit_column('timecreated', '$1', 'output_date(timecreated)');
		$this->datatables->edit_column('username', '$1', 'user_get_link_by_id(user_id)');
		$this->datatables->edit_column('body', '$1', 'comment_body(body)');
		$this->datatables->edit_column('id', '$1', 'comment_actions(id)');

		$this->datatables->unset_column('participant_id');
		$this->datatables->unset_column('user_id');

		echo $this->datatables->generate();
	}

	public function table_by_user($user_id)
	{
		$this->datatables->where('user_id', $user_id);
		$this->table(FALSE, TRUE);
	}
}
