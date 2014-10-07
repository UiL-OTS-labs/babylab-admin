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
		$data['page_title'] = "TODO: Test";
		$data['lang'] = (current_language() == L::Dutch) ? 'nl' : 'en';

		$this->load->view('templates/header');
		$this->load->view('availability_add_view', $data);
		$this->load->view('templates/footer');
	}

}