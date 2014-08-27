<?php

/////////////////////////
// WORK IN PROGRESS:
// This is a rudimentary implementation of a calculator of NCDI scores.
/////////////////////////

class Calculator extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		reset_language(L::Dutch);
		$this->load->model('NCDICheckModel');

		$this->form_validation->set_error_delimiters('<p class="error">', '</p>');
		
		$config['upload_path'] = './uploads/';
		$config['max_size']	= '100';
		$config['allowed_types'] = '*';
		$this->load->library('upload', $config);

		$this->load->helper('download');
	}

	/////////////////////////
	// Pages: index, result, submit
	/////////////////////////

	/** Specifies the contents of the default page. */
	public function index()
	{
		$data['page_title'] = 'Upload je bestand hier';
		$data['action'] = 'charts/calculator/submit/';

		$this->load->view('ncdi_wz/calculator', $data);
		$this->load->view('templates/footer');
	}

	/** Specifies the contents of the default page. TODO: not implemented */
	public function result()
	{
		create_result_table();
		$data['ajax_source'] = 'charts/calculator/table/';
		$data['page_title'] = lang('results');

		$this->load->view('templates/header', $data);
		$this->load->view('templates/list_view', $data);
		$this->load->view('templates/footer');
	}

	/**
	 * Handles the submit of a file: moves to the upload directory and then do the import
	 */
	public function submit()
	{
		if (!$this->upload->do_upload())
		{
			flashdata($this->upload->display_errors(), FALSE);
			redirect('ncdi_calculator');
		}
		else
		{
			$data = $this->upload->data();
			$this->import_csv($data['full_path']);
		}
	}

	/**
	 * Imports the .csv-file and outputs a download.
	 * @param file $filename
	 */
	private function import_csv($filename)
	{
		$row = 0;
		$out = fopen('php://output', 'w');
		ob_start();

		fputcsv($out, array('proefpersoon', 'leeftijd in maanden', 'leeftijd in maanden en dagen (m;d)', 
			'percentielscore begrip', 'percentielscore productie', 'taalleeftijd begrip', 'taalleeftijd productie'));
		
		$separator = $this->input->post('separator');

		if (($handle = fopen($filename, 'r')) !== FALSE)
		{
			while (($data = fgetcsv($handle, 1000, $separator)) !== FALSE)
			{
				$row++;
				if ($row != 1 && $data !== array(NULL)) // skip header and empties
				{
					if (count($data) == 6)
					{
						// TODO: remove duplicate code below (refer to functions age_in_months)
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
					else 
					{
						flashdata(sprintf('Verkeerd aantal kolommen op rij %d, check het bestand. (heb je het goede scheidingsteken aangevinkt?)', $row), FALSE);
						redirect('ncdi_calculator');
					}

					// TODO: deal with constants here
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
		force_download('ncdi_calculator_output.csv', $csv);
	}
}
