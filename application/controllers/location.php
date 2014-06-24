<?php
class Location extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		reset_language(current_language());
		
		$this->form_validation->set_error_delimiters('<p class="error">', '</p>');
	}
	
	/////////////////////////
	// CRUD-actions
	/////////////////////////
	
 	/** Specifies the contents of the default page. */
	public function index()
	{
		$add_url = array('url' => 'location/add', 'title' => lang('add_location'));

		create_location_table();
		$data['ajax_source'] = 'location/table/';
		$data['page_title'] = lang('locations');
		$data['action_urls'] = array($add_url);

		$this->load->view('templates/header', $data);
		$this->authenticate->authenticate_redirect('templates/list_view', $data, UserRole::Admin);
		$this->load->view('templates/footer');
	}
	
	/** Shows the page for a single location */
	public function get($location_id)
	{
		$location = $this->locationModel->get_location_by_id($location_id);
		
		$data['location'] = $location;
		$data['page_title'] = sprintf(lang('data_for_location'), $location->name);
	
		$this->load->view('templates/header', $data);
		$this->authenticate->authenticate_redirect('location_view', $data);
		$this->load->view('templates/footer');
	}
	
	/** Specifies the contents of the add location page */
	public function add()
	{
		$data['page_title'] = lang('add_location');
		$data['new_location'] = TRUE;
		$data['action'] = 'location/add_submit/';
		$data = add_fields($data, 'location');
	
		$this->load->view('templates/header', $data);
		$this->authenticate->authenticate_redirect('location_edit_view', $data);
		$this->load->view('templates/footer');
	}
	
	/** Submits the addition of a location */
	public function add_submit()
	{
		// Run validation
		if (!$this->validate_location()) {
			// If not succeeded, show form again with error messages
			$this->add();
		}
		else {
			// If succeeded, insert data into database
			$location = $this->post_location();
			$location_id = $this->locationModel->add_location($location);
	
			$t = $this->locationModel->get_location_by_id($location_id);
			flashdata(sprintf(lang('location_added'), $t->name));
			redirect('/location/', 'refresh');
		}
	}
	
	/** Specifies the contents of the edit location page */
	public function edit($location_id)
	{
		// Set default values
		$location = $this->locationModel->get_location_by_id($location_id);
	
		$data['page_title'] = sprintf(lang('edit_location'), $location->name);
		$data['new_location'] = FALSE;
		$data['action'] = 'location/edit_submit/' . $location_id;
		$data = add_fields($data, 'location', $location);
	
		$this->load->view('templates/header', $data);
		$this->authenticate->authenticate_redirect('location_edit_view', $data);
		$this->load->view('templates/footer');
	}
	
	/** Submits the edit of a location */
	public function edit_submit($location_id)
	{
		// Run validation
		if (!$this->validate_location()) {
			// If not succeeded, show form again with error messages
			$this->edit($location_id);
		}
		else {
			// If succeeded, update data into database
			$location = $this->post_location();
			$this->locationModel->update_location($location_id, $location);
	
			$t = $this->locationModel->get_location_by_id($location_id);
			flashdata(sprintf(lang('location_edited'), $t->name));
			redirect('/location/', 'refresh');
		}
	}
	
	/** Deletes the specified location, and returns to previous page */
	public function delete($location_id)
	{
		$this->locationModel->delete_location($location_id);
		flashdata(lang('location_deleted'));
		redirect('location', 'refresh');
	}
	
	/////////////////////////
	// Form handling
	/////////////////////////
	
	/** Validates a location */
	private function validate_location()
	{
		$this->form_validation->set_rules('name', lang('name'), 'trim|required');
		$this->form_validation->set_rules('roomnumber', lang('roomnumber'), 'trim|required');
		
		return $this->form_validation->run();
	}
	
	/** Posts the data for a location */
	private function post_location()
	{
		return array(
				'name' 			=> $this->input->post('name'),
				'roomnumber' 	=> $this->input->post('roomnumber')
		);
	}
	
	/////////////////////////
	// Table
	/////////////////////////

	public function table()
	{
		$this->datatables->select('name, roomnumber, id');
		$this->datatables->from('location');
		
		$this->datatables->edit_column('name', '$1', 'location_get_link_by_id(id)');
		$this->datatables->edit_column('id', '$1', 'location_actions(id)');
		
		echo $this->datatables->generate();
	}
}
