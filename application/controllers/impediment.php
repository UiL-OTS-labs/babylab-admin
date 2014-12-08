<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Impediment extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->authenticate->redirect_except();
		reset_language(current_language());

		$this->form_validation->set_error_delimiters('<p class="error">', '</p>');
	}

	/////////////////////////
	// CRUD-actions
	/////////////////////////

	/** Specifies the contents of the default page. */
	public function index($include_past = TRUE)
	{
		$add_url = array('url' => 'impediment/add', 'title'	=> lang('add_impediment'));
		$past_url = impediment_past_url($include_past);

		create_impediment_table();
		$data['ajax_source'] = 'impediment/table/' . $include_past;
		$data['page_title'] = lang('impediments');
		$data['action_urls'] = array($add_url, $past_url);

		$this->load->view('templates/header', $data);
		$this->authenticate->authenticate_redirect('templates/list_view', $data, UserRole::Admin);
		$this->load->view('templates/footer');
	}

	/** Page to add an impediment. */
	public function add()
	{
		$data['page_title'] = lang('add_impediment');
		$data['participants'] = participant_options($this->participantModel->get_all_participants(TRUE));

		$this->load->view('templates/header', $data);
		$this->load->view('impediment_add_view', $data);
		$this->load->view('templates/footer');
	}

	/** Adds an impedimentfor a participant */
	public function add_submit($p_id = NULL)
	{
		$participant_id = empty($p_id) ? $this->input->post('participant') : $p_id;

		// Run validation
		if (!$this->validate_impediment($participant_id, empty($p_id)))
		{
			// Show form again with error messages
			flashdata(validation_errors(), FALSE, 'impediment_message');
			redirect(empty($p_id) ? '/impediment/add' : $this->agent->referrer(), 'refresh');
		}
		else
		{
			// If succeeded, insert data into database
			$impediment = $this->post_impediment($participant_id);
			$this->impedimentModel->add_impediment($impediment);

			flashdata(lang('impediment_added'), TRUE, 'impediment_message');
			redirect(empty($p_id) ? '/impediment/' : $this->agent->referrer(), 'refresh');
		}
	}

	/** Deletes the specified impediment. */
	public function delete($impediment_id)
	{
		$this->impedimentModel->delete_impediment($impediment_id);
		flashdata(lang('impediment_deleted'), TRUE, 'impediment_message');
		redirect($this->agent->referrer(), 'refresh');
	}

	/////////////////////////
	// Other views
	/////////////////////////

	/** Specifies the content of a page with the contents for a specific participant */
	public function participant($participant_id)
	{
		$participant = $this->participantModel->get_participant_by_id($participant_id);

		// Check validity of participant
		if (empty($participant))
		{
			$data['error'] = sprintf(lang('not_authorized'));
			$this->load->view('templates/error', $data);
			return;
		}

		create_impediment_table();
		$data['ajax_source'] = 'impediment/table/0/' . $participant_id;
		$data['page_title'] = sprintf(lang('impediments_for'), name($participant));

		$this->load->view('templates/header', $data);
		$this->load->view('templates/list_view', $data);
		$this->load->view('templates/footer');
	}

	/////////////////////////
	// Form handling
	/////////////////////////

	/** Validates an impediment */
	private function validate_impediment($participant_id, $check_participant)
	{
		$this->form_validation->set_rules('from', lang('from_date'), 'trim|required|callback_check_within_bounds[' . $participant_id . ']');
		$this->form_validation->set_rules('to', lang('to_date'), 'trim|required|callback_check_within_bounds[' . $participant_id . ']');
		if ($check_participant) $this->form_validation->set_rules('participant', lang('participant'), 'callback_not_zero');

		return $this->form_validation->run();
	}

	/** Posts the data for an impediment */
	private function post_impediment($participant_id)
	{
		return array(
					'from' 				=> input_date($this->input->post('from')),
					'to' 				=> input_date($this->input->post('to')),
					'comment'			=> $this->input->post('comment'),
					'participant_id' 	=> $participant_id
		);
	}

	/////////////////////////
	// Callbacks
	/////////////////////////

	/** Checks whether the given date is within bounds of an existing impediment for this participant */
	public function check_within_bounds($date, $participant_id)
	{
		if ($this->impedimentModel->within_bounds(input_date($date), $participant_id))
		{
			$this->form_validation->set_message('check_within_bounds', lang('outside_bounds'));
			return FALSE;
		}
		return TRUE;
	}

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

	/////////////////////////
	// Table
	/////////////////////////

	public function table($include_past = TRUE, $participant_id = NULL)
	{
		$this->datatables->select('firstname AS p, from, comment, impediment.id AS id, participant_id');
		$this->datatables->from('impediment');
		$this->datatables->join('participant', 'participant.id = impediment.participant_id');

		if (!$include_past) $this->db->where('to >=', input_date());
		if (!empty($participant_id)) $this->datatables->where('participant_id', $participant_id);

		$this->datatables->edit_column('p', '$1', 'participant_get_link_by_id(participant_id)');
		$this->datatables->edit_column('from', '$1', 'impediment_dates_by_id(id)');
		$this->datatables->edit_column('comment', '$1', 'comment_body(comment, 30)');
		$this->datatables->edit_column('id', '$1', 'impediment_actions(id)');

		$this->datatables->unset_column('participant_id');

		echo $this->datatables->generate();
	}
}
