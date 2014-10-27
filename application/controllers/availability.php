<?php
class Availability extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->authenticate->redirect_except();
		reset_language(current_language());
	}

	/** Specifies the contents of the default page. */
	public function index($header=1)
	{
		$data['page_title'] = lang('availability');
		$data['lang'] = (current_language() == L::Dutch) ? 'nl' : 'en';
		$data['preselect'] = AVAILABILITY_DEFAULT_TIMES;

		$this->load->view('templates/header');
		$this->load->view('availability_add_view', $data);
		$this->load->view('templates/footer');
	}

	public function add_submit()
	{
		// Loads the submitted values

		// $values[x][y][z]
		//	where
		//	  x is the row id
		//    y in [date | time_from | time_to | comment]
		// 	  z = 1;
		$values = $this->input->post('value');

		foreach ($values as $value)
		{
			echo "Date: " . $value["date"] . br();
			echo "time_from: " . $value["time_from"] . br();
			echo "time_to: " . $value["time_to"] . br();
			echo "comment: " . $value["comment"] . br();
			echo br() . br();
		}
	}

}