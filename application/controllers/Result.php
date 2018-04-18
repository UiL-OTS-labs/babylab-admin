<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Result extends CI_Controller
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
	public function index()
	{
		create_result_table();
		$data['ajax_source'] = 'result/table/';
		$data['page_title'] = lang('results');

		$this->load->view('templates/header', $data);
		$this->authenticate->authenticate_redirect('templates/list_view', $data, UserRole::ADMIN);
		$this->load->view('templates/footer');
	}

	/** Specifies the contents of the add result page */
	public function add($participation_id = NULL)
	{
		if (empty($participation_id)) show_404();

		$data['page_title'] = lang('add_result');
		$data['action'] = 'result/add_submit/' . $participation_id;

		$this->load->view('templates/header', $data);
		$this->load->view('result_add_view', $data);
		$this->load->view('templates/footer');
	}

	/** Submits the addition of a result */
	public function add_submit($participation_id)
	{
		$config['upload_path'] = './uploads/';
		$config['max_size']	= '100';
		$config['allowed_types'] = '*';
		$this->load->library('upload', $config);

		if (!$this->upload->do_upload())
		{
			flashdata($this->upload->display_errors(), FALSE);
			$this->add($participation_id);
		}
		else
		{
			$data = $this->upload->data();
			$results = $this->hvf_results($data['full_path']);
			foreach ($results as $result)
			{
				$result['participation_id'] = $participation_id;
				$this->resultModel->add_result($result);
			}
			redirect('/participation/get/' . $participation_id, 'refresh');
		}
	}

	private function hvf_results($filename)
	{
		$file = file_get_contents($filename);
		$lines = explode(PHP_EOL, $file);

		$results = array();
		$filtered = '';
		foreach ($lines as $line)
		{
			if ($line && strpos($line, '6232') === 0)
			{
				$filtered .= $line . PHP_EOL;
			}
		}

		$csv = str_getcsv($filtered, PHP_EOL);
		$nr = 1;
		foreach ($csv as $row)
		{
			$row = str_getcsv($row, "\t");
			$prev_phase = empty($phase) ? NULL : $phase;
			$phase = $row[4];
				
			if ($prev_phase && $prev_phase != $phase)
			{
				$nr++;
			}
				
			$prepost = $phase === 'PRE' || $phase === 'POST';
			$test = $phase === 'TEST';
				
			$result = array(
				'phasenr' 		=> $nr,
				'phase' 		=> $phase,
				'trial' 		=> $prepost ? NULL : intval($row[5]),
				'condition' 	=> $prepost ? NULL : ($test ? $row[6] . '|' . $row[7] : $row[6]),
				'lookingtime'	=> intval($prepost ? $row[5] : ($test ? $row[9] : $row[8]))
			);
			array_push($results, $result);
		}

		return $results;
	}

	private function starts_with($haystack, $needle)
	{
		return $needle === '' || strpos($haystack, $needle) === 0;
	}

	/** Specifies the contents of the edit result page */
	public function edit($result_id)
	{
		$result = $this->resultModel->get_result_by_id($result_id);

		$data['page_title'] = lang('edit_result');
		$data['action'] = 'result/edit_submit/' . $result_id;
		$data = add_fields($data, 'result', $result);

		$this->load->view('templates/header', $data);
		$this->load->view('result_edit_view', $data);
		$this->load->view('templates/footer');
	}

	/** Submits the edit of a result */
	public function edit_submit($result_id)
	{
		// Run validation
		if (!$this->validate_result($result_id))
		{
			// If not succeeded, show form again with error messages
			$this->edit($result_id);
		}
		else
		{
			// If succeeded, update data into database
			$result = $this->post_result();
			$this->resultModel->update_result($result_id, $result);

			flashdata(lang('result_edited'));
			redirect('/result/', 'refresh');
		}
	}

	/** Deletes the specified result, and returns to previous page */
	public function delete($result_id)
	{
		$this->resultModel->delete_result($result_id);
		flashdata(lang('result_deleted'));
		redirect($this->agent->referrer(), 'refresh');
	}

	/////////////////////////
	// Form handling
	/////////////////////////

	/** Validates a result */
	private function validate_result($result_id = 0)
	{
		$this->form_validation->set_rules('phasenr', lang('phasenr'), 'trim|required');
		$this->form_validation->set_rules('phase', lang('phase'), 'trim|required');
		$this->form_validation->set_rules('trial', lang('trial'), 'trim|required');
		$this->form_validation->set_rules('lookingtime', lang('lookingtime'), 'trim|required');
		$this->form_validation->set_rules('nrlooks', lang('nrlooks'), 'trim|required');

		return $this->form_validation->run();
	}

	/** Posts the data for a result */
	private function post_result()
	{
		return array(
			'phasenr'		=> $this->input->post('phasenr'),
			'phase' 		=> $this->input->post('phase'),
			'trial'			=> $this->input->post('trial'),
			'lookingtime' 	=> $this->input->post('lookingtime'),
			'nrlooks'		=> $this->input->post('nrlooks')
		);
	}

	/////////////////////////
	// Table
	/////////////////////////

	public function table()
	{
		$this->datatables->select('CONCAT(firstname, " ", lastname) AS p, name AS e, CONCAT(phasenr, ": " , phase) AS phase, trial, lookingtime, nrlooks,
									result.id AS id, participant_id, experiment_id', FALSE);
		$this->datatables->from('result');
		$this->datatables->join('participation', 'participation.id = result.participation_id');
		$this->datatables->join('participant', 'participant.id = participation.participant_id');
		$this->datatables->join('experiment', 'experiment.id = participation.experiment_id');

		$this->datatables->edit_column('p', '$1', 'participant_get_link_by_id(participant_id)');
		$this->datatables->edit_column('e', '$1', 'experiment_get_link_by_id(experiment_id)');
		$this->datatables->edit_column('id', '$1', 'result_actions(id)');

		$this->datatables->unset_column('participant_id');
		$this->datatables->unset_column('experiment_id');

		echo $this->datatables->generate();
	}

	public function table_by_participation($participation_id)
	{
		$this->datatables->where('participation_id', $participation_id);
		$this->datatables->unset_column('p');
		$this->datatables->unset_column('e');
		$this->table();
	}
}
