<?php
class Availability extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->authenticate->redirect_except();
		reset_language(current_language());
	}

	public function index()
	{
		$add_url = array('url' => 'availability/add', 'title'	=> lang('add_impediment'));
		
		create_impediment_table();
		$data['ajax_source'] = 'impediment/table/' . $include_past;
		$data["page_title"] = "TODO: AVAIABILITIES";


		$this->load->view('templates/header', $data);
		$this->authenticate->authenticate_redirect('templates/list_view', $data, UserRole::Admin);
		$this->load->view('templates/footer');
	}

	/** Specifies the contents of the default page. */
	public function add()
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

		$id = $this->session->userdata('user_id');

		// Prepare array
		$times = Array();
		$existing = Array();

		// Convert date-times
		foreach ($values as $value)
		{
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

			// Put in array
			$curtime["user_id"] = $id;
			$curtime["from"] = $start->format("Y-m-d H:i:s");
			$curtime["to"] = $end->format("Y-m-d H:i:s");
			$curtime["comment"] = $value["comment"];
			
			if ($this->check_within_bounds($start->format("Y-m-d H:i:s"), $id) && $this->check_within_bounds($end->format("Y-m-d H:i:s"), $id))
			{
				array_push($times, $curtime);
			} else {
				array_push($existing, $start->format("Y-m-d H:i:s") . " - " . $end->format("Y-m-d H:i:s") . br());
			}
		}

		foreach ($times as $time)
		{
			$this->availabilityModel->add_availability($time);
		}

		if (sizeof($existing) > 0)
		{
			$flashdata = lang('daterange_already_exists', 'unicorns');
		}
		redirect('/availability/', 'refresh');
	}

	public function check_within_bounds($date, $user_id)
	{
		return $this->availabilityModel->within_bounds(input_date($date), $user_id);
	}

	/////////////////////////
	// Table
	/////////////////////////

	public function table($include_past = TRUE, $user_id)
	{
		$this->datatables->select('firstname AS p, from, comment, impediment.id AS id, participant_id');
		$this->datatables->from('availability');
		$this->datatables->join('participant', 'participant.id = impediment.participant_id');

		if (!$include_past) $this->db->where('to >=', input_date());
		if (!empty($user_id)) $this->datatables->where('user_id', $user_id);

		$this->datatables->edit_column('p', '$1', 'participant_get_link_by_id(participant_id)');
		$this->datatables->edit_column('from', '$1', 'impediment_dates_by_id(id)');
		$this->datatables->edit_column('id', '$1', 'impediment_actions(id)');

		$this->datatables->unset_column('participant_id');

		echo $this->datatables->generate();
	}
}