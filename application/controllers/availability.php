<?php
class Availability extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->authenticate->redirect_except();
		reset_language(current_language());
	}

	public function index($include_past = FALSE)
	{
		$add_url = array('url' => 'availability/add', 'title'	=> lang('create_availability'));
		$past_url = availability_past_url($include_past);

		create_availability_table();
		$data['ajax_source'] = 'availability/table/' . $include_past;
		$data["page_title"] = lang('your_availability');
		$data['action_urls'] = array($add_url, $past_url);

		$this->load->view('templates/header', $data);
		$this->authenticate->authenticate_redirect('templates/list_view', $data, UserRole::Leader);
		$this->load->view('templates/footer');
	}

	/** Specifies the contents of the default page. */
	public function add()
	{
		$data['page_title'] = lang('availability');
		$data['lang'] = (current_language() == L::Dutch) ? 'nl' : 'en';
		$data['preselect'] = AVAILABILITY_DEFAULT_TIMES;

		$this->load->view('templates/header');
		$this->authenticate->authenticate_redirect('availability_add_view', $data, UserRole::Leader);
		$this->load->view('templates/footer');
	}

	public function add_submit()
	{
		$values = $this->input->post('value');

		if ($values)
		{		
			list($times, $existing) = $this->convert_array($values);

			foreach ($times as $time)
			{
				$this->availabilityModel->add_availability($time);
			}

			if ($existing != "<ul></ul>")
			{
				flashdata(sprintf(lang('daterange_already_exists'), $existing));
			}
		}

		redirect('/availability/', 'refresh');
	}

	public function convert_array($values)
	{
		// Prepare array
		$times = Array();
		$existing = "<ul>";

		// Request current user ID
		$id = $this->session->userdata('user_id');

		// Convert date-times
		foreach ($values as $value)
		{
			// Prepare subarray
			$curtime = Array();

			// Convert date and time to objects
			$timefrom = date("H:i", strtotime($value["time_from"]));
			$timeto = date("H:i", strtotime($value["time_to"]));
			$date = DateTime::createFromFormat("Y-m-d", $value["date"]);
			
			// Merge start date and time
			$start = new DateTime($timefrom);
			$start->setDate($date->format("Y"), $date->format("m"), $date->format("d"));

			// Merge end date and time
			$end = new DateTime($timeto);
			$end->setDate($date->format("Y"), $date->format("m"), $date->format("d"));

			// Prepare array for ORM
			$curtime["user_id"] = $id;
			$curtime["from"] = $start->format("Y-m-d H:i:s");
			$curtime["to"] = $end->format("Y-m-d H:i:s");
			$curtime["comment"] = $value["comment"];
			
			// Validate
			if ($this->check_within_bounds($curtime["from"]) && $this->check_within_bounds($curtime["to"]))
			{
				$existing .= "<li>" . $curtime["from"] . " - " . $curtime["to"] . "</li>";
			} else {
				array_push($times, $curtime);
			}
		}

		$existing .= "</ul>";

		// Return result
		return array($times, $existing);
	}

	public function check_within_bounds($date)
	{
		$id = $this->session->userdata('user_id');
		return $this->availabilityModel->within_bounds($date, $id);
	}

	/** Deletes the specified impediment. */
	public function delete($availability_id)
	{
		$av = $this->availabilityModel->get_availability_by_id($availability_id);
		if ($av->user_id == $this->session->userdata('user_id'))
		{
			$this->availabilityModel->delete_availability($availability_id);
			flashdata(lang('availability_deleted'), TRUE, 'availability_message');
		}
		else
		{
			flashdata(lang('no_permission'));
		}
		redirect($this->agent->referrer(), 'refresh');
	}

	/////////////////////////
	// Table
	/////////////////////////

	public function table($include_past = FALSE)
	{
		$id = $this->session->userdata('user_id');

		$this->datatables->select('from, comment, id');
		$this->datatables->from('availability');
		$this->datatables->where("`user_id` = '" . $this->session->userdata('user_id') . "'");

		if (!$include_past) $this->db->where('to >=', input_date());

		$this->datatables->edit_column('from', '$1', 'availability_dates_by_id(id)');
		$this->datatables->edit_column('id', '$1', 'availability_actions(id)');

		$this->datatables->unset_column('user_id');

		echo $this->datatables->generate();
	}
}