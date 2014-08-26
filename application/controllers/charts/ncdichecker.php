<?php

/////////////////////////
// WORK IN PROGRESS:
// This is a rudimentary implementation of a calculator of NCDI scores.
/////////////////////////

class NCDIChecker extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		reset_language(L::Dutch);
		$this->load->model('NCDICheckModel');

		$this->form_validation->set_error_delimiters('<p class="error">', '</p>');
	}

	/////////////////////////
	// Pages: index, result, submit
	/////////////////////////

	/** Specifies the contents of the default page. */
	public function index()
	{
		$data['page_title'] = 'Bereken NCDI percentielscores';
		$data['action'] = 'charts/ncdichecker/submit/';

		$this->load->view('ncdi_wz/checker', $data);
		$this->load->view('templates/footer');
	}

	/** Specifies the contents of the default page. */
	public function result()
	{
		create_result_table();
		$data['ajax_source'] = 'charts/ncdichecker/table/';
		$data['page_title'] = lang('results');

		$this->load->view('templates/header', $data);
		$this->load->view('templates/list_view', $data);
		$this->load->view('templates/footer');
	}

	public function submit()
	{
		$config['upload_path'] = './uploads/';
		$config['max_size']	= '100';
		$config['allowed_types'] = '*';
		$this->load->library('upload', $config);

		if (!$this->upload->do_upload())
		{
			flashdata($this->upload->display_errors(), FALSE);
			$this->index();
		}
		else
		{
			$data = $this->upload->data();
			$this->import_csv($data['full_path']);
			//redirect('charts/ncdi_checker/result/', 'refresh');
		}
	}

	private function import_csv($filename)
	{
		$row = 0;
		$out = fopen('php://output', 'w');
		ob_start();

		fputcsv($out, array('PPnr', 'Leeftijd in maanden', 'Leeftijd in maanden en dagen', 'Percentiel begrip', 'Percentiel productie', 'Taalleeftijd begrip', 'Taalleeftijd productie'));

		if (($handle = fopen($filename, "r")) !== FALSE)
		{
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE)
			{
				$row++;
				if ($row != 1) // skip header and empties
				{
					if (count($data) == 5)
					{
						$ncdi_check = array(
							'ip_address' 	=> $this->input->ip_address(),
							'p_number' 		=> $data[0],
							'ageinmonths' 	=> intval($data[1]),
							'gender'		=> $data[2] === 'M' ? Gender::Male : Gender::Female,
							'b_score'		=> intval($data[3]),
							'p_score'		=> intval($data[4]),				
						);
					}
					else
					{
						$diff = date_diff(new DateTime($data[1]), new DateTime($data[2]));
						$ageinmonths = intval($diff->format('%r') . ($diff->format('%m') + 12 * $diff->format('%y')));
						$ageinmonthsdays = $diff->format('%r') . ($diff->format('%m') + 12 * $diff->format('%y')) . ';' . $diff->format('%d');

						$ncdi_check = array(
							'ip_address' 	=> $this->input->ip_address(),
							'p_number' 		=> $data[0],
							'ageinmonths' 	=> $ageinmonths,
							'ageinmonthsdays' 	=> $ageinmonthsdays,
							'gender'		=> $data[3] === 'M' ? Gender::Male : Gender::Female,
							'b_score'		=> intval($data[4]),
							'p_score'		=> intval($data[5]),				
						);
					}

					$test = $this->testModel->get_test_by_code('ncdi_wz');
					$testcat_b = $this->testCatModel->get_testcat_by_code($test, 'b');
					$testcat_p = $this->testCatModel->get_testcat_by_code($test, 'p');

					$perc_b = $this->percentileModel->find_percentile($testcat_b->id,
					$ncdi_check['gender'], $ncdi_check['ageinmonths'], $ncdi_check['b_score']);
					$perc_p = $this->percentileModel->find_percentile($testcat_p->id,
					$ncdi_check['gender'], $ncdi_check['ageinmonths'], $ncdi_check['p_score']);
					$age_b = $this->percentileModel->find_50percentile_age($testcat_b->id,
					$ncdi_check['gender'], $ncdi_check['b_score']);
					$age_p = $this->percentileModel->find_50percentile_age($testcat_p->id,
					$ncdi_check['gender'], $ncdi_check['p_score']);

					fputcsv($out, array($ncdi_check['p_number'], $ncdi_check['ageinmonths'], $ncdi_check['ageinmonthsdays'], $perc_b, $perc_p, $age_b, $age_p));

					//$this->NCDICheckModel->add_ncdi_check($ncdi_check);
				}
			}
			fclose($handle);
		}

		$csv = ob_get_contents();
		ob_end_clean();
		$filename = 'output.csv';

		$this->load->helper('download');
		force_download($filename, $csv);
	}
}
