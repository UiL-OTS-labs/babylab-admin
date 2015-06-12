<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Experiment extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->authenticate->redirect_except();
		reset_language(current_language());

		$this->form_validation->set_error_delimiters('<label class="error">', '</label>');	
        
        // Uploading experiment attachments
		$config['upload_path'] = './uploads/';
		$config['allowed_types'] = 'pdf';
		$this->load->library('upload', $config);
	}

	/////////////////////////
	// CRUD-actions
	/////////////////////////

	/** Specifies the contents of the default page. */
	public function index($include_archived = FALSE)
	{
		$add_url = array('url' => 'experiment/add', 'title'	=> lang('add_experiment'));

		create_experiment_table();
		$data['ajax_source'] = 'experiment/table/' . $include_archived;
		$data['page_title'] = lang('experiments');
		$data['action_urls'] = array($add_url, experiment_archive_link($include_archived));

		$this->load->view('templates/header', $data);
		$this->authenticate->authenticate_redirect('templates/list_view', $data, UserRole::Leader);
		$this->load->view('templates/footer');
	}

	/** Shows the page for a single experiment */
	public function get($experiment_id)
	{
		$experiment = $this->experimentModel->get_experiment_by_id($experiment_id);
		$location = $this->locationModel->get_location_by_experiment($experiment);
		$participations = $this->participationModel->get_participations_by_experiment($experiment_id);

		$data['page_title'] = sprintf(lang('data_for_experiment'), $experiment->name);
		$data['experiment'] = $experiment;
		$data['location'] = $location;
		$data['nr_participations'] = count($participations);

		$this->load->view('templates/header', $data);
		$this->load->view('experiment_view', $data);
		$this->load->view('templates/footer');
	}

	/** Specifies the contents of the add experiment page */
	public function add()
	{
		$leaders = array_merge($this->userModel->get_all_leaders(), $this->userModel->get_all_admins());
		$callers = $this->userModel->get_all_users();
		$experiments = $this->experimentModel->get_all_experiments();

		$data['page_title'] = lang('add_experiment');
		$data['action'] = 'experiment/add_submit';
		$data = add_fields($data, 'experiment');

		$data['locations'] = $this->locationModel->get_all_locations();
		$data['callers'] = caller_options($callers);
		$data['leaders'] = leader_options($leaders);
		$data['experiments'] = experiment_options($experiments);

		$this->load->view('templates/header', $data);
		$this->load->view('experiment_edit_view', $data);
		$this->load->view('templates/footer');
	}

	/** Adds an experiment to the database */
	public function add_submit()
	{
		// Validate experiment
		if (!$this->validate_experiment())
		{
			// Show form again with error messages
			$this->add();
		}
		else 
		{
			// Add experiment to database
			$experiment = $this->post_experiment();
			$experiment_id = $this->experimentModel->add_experiment($experiment);
			$this->update_references($experiment_id);

			// Print success!
			flashdata(lang('exp_added'));
			redirect('/experiment/', 'refresh');
		}
	}

	/** Specifies the contents of the edit experiment page */
	public function edit($experiment_id)
	{
		if (is_leader() && !$this->leaderModel->is_leader_for_experiment(current_user_id(), $experiment_id))
		{
			show_error("You are not a leader for this experiment.");
		}

		$experiment = $this->experimentModel->get_experiment_by_id($experiment_id);
		$leaders = array_merge($this->userModel->get_all_leaders(), $this->userModel->get_all_admins());
		$callers = $this->userModel->get_all_users();
		$experiments = $this->experimentModel->get_all_experiments(TRUE, $experiment_id);

		$data['page_title'] = lang('edit_experiment');
		$data['action'] = 'experiment/edit_submit/' . $experiment_id;
		$data = add_fields($data, 'experiment', $experiment);
		
		$data['locations'] = $this->locationModel->get_all_locations();
		$data['callers'] = caller_options($callers);
		$data['leaders'] = leader_options($leaders);
		$data['experiments'] = experiment_options($experiments);

		$data['location_id'] = $this->locationModel->get_location_by_experiment($experiment)->id;
		$data['current_caller_ids'] = $this->callerModel->get_caller_ids_by_experiment($experiment_id);
		$data['current_leader_ids'] = $this->leaderModel->get_leader_ids_by_experiment($experiment_id);
		$data['current_prerequisite_ids'] = $this->relationModel->get_relation_ids_by_experiment($experiment_id, RelationType::Prerequisite);
		$data['current_exclude_ids'] = $this->relationModel->get_relation_ids_by_experiment($experiment_id, RelationType::Excludes);
		$data['current_combination_ids'] = $this->relationModel->get_relation_ids_by_experiment($experiment_id, RelationType::Combination);

		$this->load->view('templates/header', $data);
		$this->load->view('experiment_edit_view', $data);
		$this->load->view('templates/footer');
	}

	/** Adds an experiment to the database */
	public function edit_submit($experiment_id)
	{        
        $e = $this->experimentModel->get_experiment_by_id($experiment_id);
        
		// Validate experiment
		if (!$this->validate_experiment($e->attachment))
		{
			// Show form again with error messages
			$this->edit($experiment_id);
		}
		else 
		{            
			// Update experiment in database
			$experiment = $this->post_experiment();
			$this->experimentModel->update_experiment($experiment_id, $experiment);
			$this->update_references($experiment_id);

			// Print success!
			flashdata(lang('exp_edited'));
			redirect('/experiment/', 'refresh');
		}
	}

	/////////////////////////
	// Other actions
	/////////////////////////

	/** Archives the given experiment (instead of deleting) */
	public function archive($experiment_id)
	{
		$this->experimentModel->archive($experiment_id, 1);
		flashdata(lang('archived_exp'));
		redirect($this->agent->referrer(), 'refresh');
	}

	/** Unarchives the given experiment */
	public function unarchive($experiment_id)
	{
		$this->experimentModel->archive($experiment_id, 0);
		flashdata(lang('unarchived_exp'));
		redirect($this->agent->referrer(), 'refresh');
	}

	/////////////////////////
	// Other views
	/////////////////////////

	/** Redirects to the default page, but now including the archived experiments */
	public function show_archive()
	{
		$this->index(1);
	}

	/** Shows all experiments for a caller TODO: unused? */
	public function caller($user_id)
	{
		if (!correct_user($user_id)) return;

		$experiments = $this->callerModel->get_experiments_by_caller($user_id);

		$data['page_title'] = lang('experiments');
		$data['table'] = create_experiment_table($experiments);

		$this->load->view('templates/header', $data);
		$this->load->view('templates/list_view', $data);
		$this->load->view('templates/footer');
	}

	/** Shows all experiments for a leader TODO: unused? */
	public function leader($user_id)
	{
		if (!correct_user($user_id)) return;

		$experiments = $this->leaderModel->get_experiments_by_leader($user_id);

		$data['page_title'] = lang('experiments');
		$data['table'] = create_experiment_table($experiments);

		$this->load->view('templates/header', $data);
		$this->load->view('templates/list_view', $data);
		$this->load->view('templates/footer');
	}

	/**
	 * Shows non-archived experiments without a caller.
	 */
	public function without_caller()
	{
		create_experiment_table();
		$data['ajax_source'] = 'experiment/table_without_caller/';
		$data['page_title'] = lang('experiments');

		$this->load->view('templates/header', $data);
		$this->authenticate->authenticate_redirect('templates/list_view', $data, UserRole::Admin);
		$this->load->view('templates/footer');
	}

	/**
	 * Shows non-archived experiments without a leader.
	 */
	public function without_leader()
	{
		create_experiment_table();
		$data['ajax_source'] = 'experiment/table_without_leader/';
		$data['page_title'] = lang('experiments');

		$this->load->view('templates/header', $data);
		$this->authenticate->authenticate_redirect('templates/list_view', $data, UserRole::Admin);
		$this->load->view('templates/footer');
	}

	/**
     * Removes the attachment for an experiment and returns to the edit view.
     * @param integer $experiment_id
     */
    public function remove_attachment($experiment_id) 
    {
        $this->experimentModel->update_experiment($experiment_id, array('attachment' => NULL));
        redirect('experiment/edit/' . $experiment_id);
    }
    
    /**
     * Downloads the attachment for an experiment. 
     * @param integer $experiment_id
     */
    public function download_attachment($experiment_id) 
    {
        $experiment = $this->experimentModel->get_experiment_by_id($experiment_id);
        
        $data = file_get_contents('uploads/' . $experiment->attachment); 
        $name = $experiment->attachment;

        force_download($name, $data);
    }
	
	/**
	 * Downloads all scores of participants of an experiment as a .csv-file.
	 * @param integer $experiment_id
	 * @param string $test_code
	 */
	public function download_scores($experiment_id, $test_code)
	{
		// Retrieve the scores and convert to .csv
		$table = $this->get_results_table($experiment_id, $test_code);
		$csv = scores_to_csv($test_code, $table, $experiment_id);
		
		// Generate filename
		$experiment_name = $this->experimentModel->get_experiment_by_id($experiment_id)->name;
		$escaped = preg_replace('/[^A-Za-z0-9_\-]/', '_', $experiment_name);
		$filename = $escaped . '_' . mdate("%Y%m%d_%H%i", time()) . '.csv';
		
		// Download the file
		force_download($filename, $csv);
	}
	
	/**
	 * Returns all scores for participants of an experiment.
	 * @param integer $experiment_id
	 * @param string $test_code
	 */
	private function get_results_table($experiment_id, $test_code) 
	{
		$participants = $this->experimentModel->get_participants_by_experiment($experiment_id, TRUE);
		
		$result = array();
		foreach ($participants as $participant)
		{
			$testinvites = $this->testInviteModel->get_testinvites_by_participant($participant->id);
			foreach ($testinvites as $testinvite)
			{
				$test = $this->testInviteModel->get_test_by_testinvite($testinvite);
				if ($test->code === $test_code)
				{
					$scores = $this->scoreModel->get_scores_by_testinvite($testinvite->id);
					
					foreach ($scores as $score)
					{
						$result[$score->testinvite_id][$score->testcat_id] = $score->score;
					}
				}
			}
		}
		
		return $result;
	}
	
	/////////////////////////
	// Form handling
	/////////////////////////

	private function validate_experiment($has_attachment = FALSE)
	{
		$this->form_validation->set_rules('location', lang('location'), 'required');
		$this->form_validation->set_rules('name', lang('name'), 'trim|required');
		$this->form_validation->set_rules('type', lang('type'), 'trim|required');
		$this->form_validation->set_rules('description', lang('description'), 'trim|required');
		$this->form_validation->set_rules('duration', lang('duration'), 'trim|required');
		$this->form_validation->set_rules('wbs_number', lang('wbs_number'), 'trim|required|callback_wbs_check');
		$this->form_validation->set_rules('dyslexic', lang('dyslexic'), '');
		$this->form_validation->set_rules('multilingual', lang('multilingual'), '');
		$this->form_validation->set_rules('agefrommonths', lang('agefrommonths'), 'trim|required|is_natural|callback_age_check');
		$this->form_validation->set_rules('agefromdays', lang('agefromdays'), 'trim|required|is_natural|less_than[32]');
		$this->form_validation->set_rules('agetomonths', lang('agetomonths'), 'trim|required|is_natural');
		$this->form_validation->set_rules('agetodays', lang('agetodays'), 'trim|required|is_natural|less_than[32]');
        
        if (!$has_attachment)
        {
            $this->form_validation->set_rules('userfile', lang('attachment'), 'callback_upload_attachment');
        }

		return $this->form_validation->run();
	}

	/** Posts the data for an experiment */
	private function post_experiment()
	{
		$exp = array(
				'location_id' 		=> $this->input->post('location'),
				'name' 				=> $this->input->post('name'),
				'type' 				=> $this->input->post('type'),
				'description' 		=> $this->input->post('description'),
				'duration' 			=> $this->input->post('duration'),
				'wbs_number'		=> $this->input->post('wbs_number'),
				'experiment_color'	=> $this->input->post('experiment_color'),
				'dyslexic' 			=> is_array($this->input->post('dyslexic')),
				'multilingual' 		=> is_array($this->input->post('multilingual')),
				'agefrommonths' 	=> $this->input->post('agefrommonths'),
				'agefromdays' 		=> $this->input->post('agefromdays'),
				'agetomonths' 		=> $this->input->post('agetomonths'),
				'agetodays' 		=> $this->input->post('agetodays')
		);
        
        // If there is uploaded data, set this as the attachment on an experiment
        $data = $this->upload->data();
        if ($data['file_name']) 
        {
            $exp['attachment'] = $data['file_name'];
        }
        
        return $exp;
	}

	private function update_references($experiment_id)
	{
		// Update references to callers
		$callers = $this->input->post('callers');
		$this->callerModel->update_callers($experiment_id, $callers);
		// Update references to leaders
		$leaders = $this->input->post('leaders');
		$this->leaderModel->update_leaders($experiment_id, $leaders);
		// Update references to prerequisites
		$prerequisites = $this->input->post('prerequisite');
		$this->relationModel->update_relations($experiment_id, $prerequisites, RelationType::Prerequisite);
		// Update references to excludes
		$excludes = $this->input->post('excludes');
		$this->relationModel->update_relations($experiment_id, $excludes, RelationType::Excludes);
		// Update references to combination
		$combination = $this->input->post('combination') === '-1' ? array(): array($this->input->post('combination'));
		$this->relationModel->update_relations($experiment_id, $combination, RelationType::Combination);
	}

	/////////////////////////
	// Callbacks
	/////////////////////////

	/** Checks whether the from age range is before that of the to age range. */
	public function age_check()
	{
		$from_days = $this->input->post('agefrommonths') * 30 + $this->input->post('agefromdays');
		$to_days = $this->input->post('agetomonths') * 30 + $this->input->post('agetodays');

		if ($from_days > $to_days) {
			$this->form_validation->set_message('age_check', lang('age_from_before_to'));
			return FALSE;
		}
		return TRUE;
	}
	
	/**
	 * Callback function to check if the WBS number is correctly
	 * formatted.
	 * @param String $str	wbs number
	 */
	public function wbs_check($str)
	{
		$pattern = "/^[a-zA-Z]{2}\.?\d{6}\.?\d$/";
		if (!preg_match($pattern, $str))
		{
			$this->form_validation->set_message('wbs_check', lang('wbs_check'));
			return FALSE;
		} 
		return TRUE;
	}
    
    public function upload_attachment()
    {
        if (!$this->upload->do_upload()) 
        {
            $this->form_validation->set_message('upload_attachment', $this->upload->display_errors());
            return FALSE; 
        }
        return TRUE;
    }

	/////////////////////////
	// Table
	/////////////////////////

	public function table($archived = FALSE, $caller_id = '', $leader_id = '')
	{
		$experiment_ids = array(); // where id IN een niet lege array, dat is wel handig
		if ($caller_id) $experiment_ids += $this->callerModel->get_experiment_ids_by_caller($caller_id);
		if ($leader_id) $experiment_ids += $this->leaderModel->get_experiment_ids_by_leader($leader_id);

		if (empty($experiment_ids)) array_push($experiment_ids, 0);

		$this->datatables->select('name, agefrommonths, dyslexic, multilingual, id AS callers, id AS leaders, id');
		$this->datatables->from('experiment');

		if (!$archived) $this->datatables->where('archived', $archived);
		if ($caller_id || $leader_id) $this->datatables->where('id IN (' . implode(',', $experiment_ids) . ')');

		$this->datatables->edit_column('name', '$1', 'experiment_get_link_by_id(id)');
		$this->datatables->edit_column('agefrommonths', '$1', 'age_range_by_id(id)');
		$this->datatables->edit_column('dyslexic', '$1', 'img_tick(dyslexic)');
		$this->datatables->edit_column('multilingual', '$1', 'img_tick(multilingual)');
		$this->datatables->edit_column('callers', '$1', 'experiment_caller_link(id)');
		$this->datatables->edit_column('leaders', '$1', 'experiment_leader_link(id)');
		$this->datatables->edit_column('id', '$1', 'experiment_actions(id)');

		echo $this->datatables->generate();
	}

	public function table_by_user($user_id)
	{
		$this->table(FALSE, $user_id, $user_id);
	}

	public function table_without_caller()
	{
		$experiment_ids = get_object_ids($this->callerModel->get_experiments_without_callers());
		$this->datatables->where('id IN (' . implode(',', $experiment_ids) . ')');
		$this->table();
	}

	public function table_without_leader()
	{
		$experiment_ids = get_object_ids($this->leaderModel->get_experiments_without_leaders());
		$this->datatables->where('id IN (' . implode(',', $experiment_ids) . ')');
		$this->table();
	}
}
