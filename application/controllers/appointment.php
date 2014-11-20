<?php
class Appointment extends CI_Controller
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
		// Prepare the data
		$data['page_title'] = lang('calendar');
		$data['legend'] = $this->generate_legend();
		$data['lang'] = (current_language() == L::Dutch) ? 'nl' : 'en';
		$data['experiments'] = $this->experimentModel->get_all_experiments();
		$data['participants'] = $this->participantModel->get_all_participants();
		$data['locations'] = $this->locationModel->get_all_locations();

		// Load the view
		if ($header) $this->load->view('templates/header', $data);
		else $this->load->view('templates/simple_header', $data);
		
		$this->authenticate->authenticate_redirect('appointment_view.php');
		$this->load->view('templates/footer');
	}

	/** Generates an array of events (JSON encoded) for the calender */
	public function events()
	{
		$appointments = $this->filter_appointments();
		$events = array();

		// For each appointment, create an event
		foreach ($appointments as $appointment)
		{
			// Participant, Experiment and Location
			$participant = $this->participationModel->get_participant_by_participation($appointment->id);
			$experiment = $this->participationModel->get_experiment_by_participation($appointment->id);
			$location_name = location_name($experiment->location_id);
			

			// Begin and end datetime
			$dateTime = new DateTime($appointment->appointment);
			$startTime = $dateTime->format(DateTime::ISO8601);
			$totalTime = $experiment->duration + INSTRUCTION_DURATION;
			date_add($dateTime, date_interval_create_from_date_string($totalTime . ' minutes'));
			$end = $dateTime->format(DateTime::ISO8601);

			// Colors
			$bgcolor = $experiment->experiment_color;
			$textcolor = isset($bgcolor) ? get_foreground_color($bgcolor) : "";

			// Generate array for event
			$event = array(
				"title" 	=> "\n" . name($participant) . "\n" . $location_name,
				"start" 	=> $startTime,
				"end"		=> $end,
				"color" 	=> $bgcolor,
				"textColor" => $textcolor,
				"experiment" => $experiment->name,
				"type"		=> $experiment->type,
				"tooltip"	=> $this->generate_tooltip($appointment->id, $participant, $experiment),
				"message"	=> $this->get_messages($appointment),
				"className" => ($appointment->cancelled != 0) ? "event-cancelled" : "",
			);

			/*if ($appointment->cancelled != 0)
			 {
				$event .= array("className" => "event-cancelled");
				}*/

			// Add array to events
			array_push($events, $event);
		}

		// Returns a json array
		echo json_encode($events);
	}

	/**
	 * Returns the appointments based on the filters provided. 
	 * TODO: this can probably be done cleaner, call to one function and dealing with conditions in the model.
	 */
	private function filter_appointments()
	{
		// Fetch POST data
		$experiment_ids = $this->input->post('experiment_ids');
		$participant_ids = $this->input->post('participant_ids');
		$location_ids = $this->input->post('location_ids');
		$exclude_canceled = $this->input->post('exclude_canceled') == "true";
		
		// This just makes things look a little bit more fancy
		$experiment = !empty($experiment_ids);
		$participant = !empty($participant_ids);
		$location = !empty($location_ids);
			
		switch (true)
		{
			case $experiment && !$participant && !$location;
				// Filter on experiments only
				$appointments = $this->participationModel->get_participations_by_experiments($experiment_ids, $exclude_canceled);
				break;
			
			case !$experiment && $participant && !$location;
				// Filter on participants only
				$appointments = $this->participationModel->get_participations_by_participants($participant_ids, $exclude_canceled);
				break;
			
			case !$experiment && !$participant && $location;
				// Filter on location only
				$experiments = $this->experimentModel->get_experiments_by_locations($location_ids);
				$appointments = $this->participationModel->get_participations_by_experiments($experiments, $exclude_canceled);
				break;
			
			case $experiment && $participant && !$location;
				// Filter by experiment AND participant but not by location
				$appointments = $this->participationModel->get_participations_by_filter($experiment_ids, $participant_ids, $exclude_canceled);
				break;
			
			case $experiment && !$participant && $location;
				// There is a filter on experiment AND location but not on participants
				$experiments_ids = array_intersect($experiment_ids, $this->experimentModel->get_experiments_by_locations($location_ids));
				$appointments = $this->participationModel->get_participations_by_experiments($experiments_ids, $exclude_canceled);
				break;
			
			case !$experiment && $participant && $location;
				// There is a filter on participant AND location but not on experiment
				// TODO: Fix this shit!
				$experiments_ids = $this->experimentModel->get_experiments_by_locations($location_ids);
				$appointments = $this->participationModel->get_participations_by_filter($experiments_ids, $participant_ids, $exclude_canceled);
				break;
			
			case $experiment && $participant && $location;
				// Filters on participant AND location AND experiment
				$experiments_ids = array_intersect($experiment_ids, $this->experimentModel->get_experiments_by_locations($location_ids));
				$appointments = $this->participationModel->get_participations_by_filter($experiments_ids, $participant_ids, $exclude_canceled);
				break;
			
			default:
				// No filters exist. Proceed normally
				$appointments = $this->participationModel->get_all_appointments($exclude_canceled);
				break; 
		}

		return $appointments;
	}

	/**
	 * Generates HTML Output for the calender tooltip
	 * @param int $id participation ID
	 * @param Participant $participant
	 * @param Experiment $experiment
	 */
	private function generate_tooltip($id, $participant, $experiment)
	{
		$exp_link = lang('experiment') . ': ';
		$exp_link .= experiment_get_link($experiment);
		$exp_link .= br();

		$part_link = lang('participant') . ': ';
		$part_link .= participant_get_link($participant);
		$part_link .= br();

		$loc_link = lang('location') . ': ';
		$loc_link .= location_get_link_by_id($experiment->location_id);
		$loc_link .= br();

		$participation_actions = is_leader() ? '' : '<center>' . participation_actions($id) . '</center>';

		return addslashes($exp_link . $part_link . $loc_link . $participation_actions);
	}

	/**
	 * Generates the content of the legend tooltip
	 */
	private function generate_legend($exps = null, $title = null)
	{
		
		if(!isset($exps))
			$exps = $this->experimentModel->get_all_experiments();

		if(!isset($title))
			$title = heading(lang('experiment_color'), 3);

		$colors = "";

		foreach ($exps as $e)
		{
			$colors .= get_colored_label($e);
		}

		return $title . $colors;
	}

	/**
	 * Generates the messages for an event, based on appointment information.
	 * Appointment $appointment
	 */
	private function get_messages($appointment)
	{
		return ($appointment->cancelled) ? lang('rescheduled') : "";
	}

	/**
	 * Generates events off of the availabilities from leaders and administrators
	 */
	public function availabilities()
	{
		$availabilities = $this->filter_availabilities();
		$result = array();

		foreach ($availabilities as $a)
		{
			// Begin and end datetime
			$s = new DateTime($a->from);
			$start = $s->format(DateTime::ISO8601);
			
			$e = new DateTime($a->to);
			$end = $e->format(DateTime::ISO8601);

			$user = $this->userModel->get_user_by_id($a->user_id);

			// Generate array for event
			$event = array(
				"title" 	=> lang('availability') . " " . $user->username,
				"start" 	=> $start,
				"end"		=> $end,
				"allDay"	=> true,
				"tooltip"	=> $this->generate_label($user, $s, $e)
			);

			array_push($result, $event);
		}

		echo json_encode($result);
	}

	/**
	 * Runs the filter on availabilities
	 */
	private function filter_availabilities()
	{
		$experiment_ids = $this->input->post('experiment_ids');
		$include_availability = $this->input->post('include_availability') == "true";

		if($include_availability)
		{
			if($experiment_ids != '')
				return $this->availabilityModel->get_availabilities_by_experiments($experiment_ids);
			else
				return $this->availabilityModel->get_all_availabilities();
		} else {
			return array();
		}
	}

	/**
	 * Generates the tooltip label of availability-events
	 */
	private function generate_label($user, $start, $end)
	{
		//$html = "<b>" . lang('availability') . " " . $start->format('H:i') . "-" . $end->format('H:i') . "</b>" . br() ;
		$html = heading(sprintf(lang('availability_from_to'), $user->username, $start->format('Y-m-d'), 
			$start->format('H:i') , $end->format('H:i')), 3) . br();
		$experiments = $this->leaderModel->get_experiments_by_leader($user->id);
		if(!empty($experiments))
			$html .= $this->generate_legend($experiments, sprintf(lang('exp_for_leader'), $user->username));

		return $html;
	}

}