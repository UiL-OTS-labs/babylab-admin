<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Dyslexia extends CI_Controller
{
	// TODO: on save, propagate correct values to participant.

	public function __construct()
	{
		parent::__construct();
		$this->authenticate->redirect_except();
		reset_language(current_language());

		$this->form_validation->set_error_delimiters('<label class="error">', '</label>');
	}

	/////////////////////////
	// CRUD-actions
	/////////////////////////

	/** Specifies the contents of the default page. */
	public function index()
	{
		$add_url = array('url' => 'dyslexia/add', 'title' => lang('add_dyslexia'));

		create_dyslexia_table();
		$data['ajax_source'] = 'dyslexia/table/';
		$data['page_title'] = lang('dyslexia');
		$data['action_urls'] = array($add_url);

		$this->load->view('templates/header', $data);
		$this->load->view('templates/list_view', $data);
		$this->load->view('templates/footer');
	}

	/** Specifies the contents of the add (single) score page */
	public function add($participant_id = 0)
	{
		$data['page_title'] = lang('add_dyslexia');
		$data['new_dyslexia'] = TRUE;
		$data['action'] = 'dyslexia/add_submit';
		$data = add_fields($data, 'dyslexia');

		$data['participant_id'] = $participant_id;
		$data['participants'] = participant_options($this->participantModel->get_all_participants(TRUE));

		$this->load->view('templates/header', $data);
		$this->load->view('dyslexia_edit_view', $data);
		$this->load->view('templates/footer');
	}

	/** Submits the addition of a dyslexia-item */
	public function add_submit()
	{
		// Run validation
		if (!$this->validate_dyslexia(TRUE))
		{
			// If not succeeded, show form again with error messages
			$this->add($this->input->post('participant'));
		}
		else
		{
			// If succeeded, insert data into database
			$dyslexia = $this->post_dyslexia(TRUE);
			$dyslexia_id = $this->dyslexiaModel->add_dyslexia($dyslexia);

			$d = $this->dyslexiaModel->get_dyslexia_by_id($dyslexia_id);
			flashdata(lang('dyslexia_added'));
			redirect('/dyslexia/', 'refresh');
		}
	}

	/** Specifies the contents of the edit dyslexia page */
	public function edit($dyslexia_id)
	{
		$dyslexia = $this->dyslexiaModel->get_dyslexia_by_id($dyslexia_id);
		$data['participant'] = $this->dyslexiaModel->get_participant_by_dyslexia($dyslexia);

		$data['page_title'] = lang('edit_dyslexia');
		$data['new_dyslexia'] = FALSE;
		$data['action'] = 'dyslexia/edit_submit/' . $dyslexia_id;
			
		$data = add_fields($data, 'dyslexia', $dyslexia);

		$this->load->view('templates/header', $data);
		$this->load->view('dyslexia_edit_view', $data);
		$this->load->view('templates/footer');
	}

	/** Submits the edit of a dyslexia */
	public function edit_submit($dyslexia_id)
	{
		// Run validation
		if (!$this->validate_dyslexia(FALSE))
		{
			// If not succeeded, show form again with error messages
			$this->edit($dyslexia_id);
		}
		else
		{
			// If succeeded, update data into database
			$dyslexia = $this->post_dyslexia(FALSE);
			$this->dyslexiaModel->update_dyslexia($dyslexia_id, $dyslexia);

			$d = $this->dyslexiaModel->get_dyslexia_by_id($dyslexia_id);
			flashdata(lang('dyslexia_edited'));
			redirect('/dyslexia/', 'refresh');
		}
	}

	/** Deletes the specified dyslexia, and returns to previous page */
	public function delete($dyslexia_id)
	{
		$this->dyslexiaModel->delete_dyslexia($dyslexia_id);
		flashdata(lang('dyslexia_deleted'), TRUE, 'dyslexia_message');
		redirect($this->agent->referrer(), 'refresh');
	}

	/////////////////////////
	// Form handling
	/////////////////////////

	/** Validates a dyslexia */
	private function validate_dyslexia($new_dyslexia)
	{
		if ($new_dyslexia)
		{
			$this->form_validation->set_rules('participant', lang('participant'), 'callback_not_zero|callback_unique_dyslexia');
		}
		$this->form_validation->set_rules('gender', lang('gender'), 'trim|required');
		$this->form_validation->set_rules('emt_score', lang('emt_score'), 'trim|is_natural');
		$this->form_validation->set_rules('klepel_score', lang('klepel_score'), 'trim|is_natural');
		$this->form_validation->set_rules('vc_score', lang('vc_score'), 'trim|is_natural');
		$this->form_validation->set_rules('comment', lang('comment'), 'trim|max_length[200]');

		return $this->form_validation->run();
	}

	/** Posts the data for a dyslexia */
	private function post_dyslexia($new_dyslexia)
	{
		$emt_score = $this->input->post('emt_score');
		$klepel_score = $this->input->post('klepel_score');
		$vc_score = $this->input->post('vc_score');

		$dyslexia = array(
				'gender' 		=> $this->input->post('gender'),
				'statement' 	=> $this->input->post('statement') === '1',
				'emt_score' 	=> empty($emt_score) ? NULL : $emt_score,
				'klepel_score' 	=> empty($klepel_score) ? NULL : $klepel_score,
				'vc_score' 		=> empty($vc_score) ? NULL : $vc_score,
				'comment' 		=> $this->input->post('comment'),
		);

		if ($new_dyslexia) $dyslexia['participant_id'] = $this->input->post('participant');

		return $dyslexia;
	}

	/////////////////////////
	// Callbacks
	/////////////////////////

	/** Checks whether the given parameter is higher than 0 */
	public function not_zero($value)
	{
		if (intval($value) <= 0)
		{
			$this->form_validation->set_message('not_zero', lang('isset'));
			return FALSE;
		}
		return TRUE;
	}

	/** Checks whether the given participant and gender-combination is unique */
	public function unique_dyslexia($participant_id)
	{
		$gender = $this->input->post('gender');
		$dyslexia = $this->dyslexiaModel->get_dyslexia_by_participant_gender($participant_id, $gender);
		if ($dyslexia)
		{
			$participant = $this->participantModel->get_participant_by_id($participant_id);
			$message = sprintf(lang('unique_dyslexia'), strtolower(gender_parent($gender)), name($participant));
			$this->form_validation->set_message('unique_dyslexia', $message);
			return FALSE;
		}
		return TRUE;
	}

	/////////////////////////
	// Table
	/////////////////////////

	public function table()
	{
		$this->datatables->select('CONCAT(firstname, " ", lastname) AS p,
			dyslexia.gender AS gender, statement, emt_score, klepel_score, vc_score, comment, 
			dyslexia.id AS id, participant_id', FALSE);
		$this->datatables->from('dyslexia');
		$this->datatables->join('participant', 'participant.id = dyslexia.participant_id');

		$this->datatables->edit_column('p', '$1', 'participant_get_link_by_id(participant_id)');
		$this->datatables->edit_column('gender', '$1', 'gender_parent(gender)');
		$this->datatables->edit_column('statement', '$1', 'img_tick(statement)');
		$this->datatables->edit_column('comment', '$1', 'comment_body(comment, 30)');
		$this->datatables->edit_column('id', '$1', 'dyslexia_actions(id)');

		$this->datatables->unset_column('participant_id');

		echo $this->datatables->generate();
	}

	public function table_by_participant($participant_id)
	{
		$this->datatables->where('participant_id', $participant_id);
		$this->table();
	}
}
