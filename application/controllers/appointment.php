<?php
class Appointment extends CI_Controller
{

	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();
		reset_language(current_language());
	}

	/** Specifies the contents of the default page. */
	public function index()
	{
		// Prepare the data
		$data['page_title'] = lang('appointments');
		$data['legend'] = $this->generate_legend();
		$data['lang'] = (current_language() == 'dutch') ? 'nl' : 'en';
		$data['experiments'] = $this->experimentModel->get_all_experiments();
		$data['participants'] = $this->participantModel->get_all_participants();

		// Load the view
		$this->load->view('templates/header', $data);
		$this->authenticate->authenticate_redirect('appointment_view.php');
		$this->load->view('templates/footer');
	}

	/** Generates an array of events for the calender */
	public function events()
	{
		// Fetch POST data
		$experiment_ids = $this->input->post('experiment_ids');
		$participant_ids = $this->input->post('participant_ids');
		$exclude_canceled = ($this->input->post('exclude_canceled') == "true") ? true : false;

		
		if (empty($experiment_ids) && empty($participant_ids))
		{
			$appointments = $this->participationModel->get_all_appointments($exclude_canceled);
		} else {
			// There is a filter somehwere
			if (empty($experiment_ids))
			{
				// The filter isn't on experiment, so it is on participant.
				// Filter participants
				$appointments = $this->participationModel->get_participations_by_participants($participant_ids, $exclude_canceled);
			} else {
				// There is a filter on participants only
				if(empty($participant_ids))
				{
					// There is a filter on experiments but not on participants
					// Filter on experiments only
					$appointments = $this->participationModel->get_participations_by_experiments($experiment_ids, $exclude_canceled);	
				} else {
					// There is a filter on both experiments and participants
					// Filter both
					$appointments = $this->participationModel->get_participations_by_filter($experiment_ids, $participant_ids, $exclude_canceled);	
				}
			}
		}

		// Array of array 'events'
		$events = array();

		// For each appointment, create an event
		foreach ($appointments as $appointment){
				
			// Participant and Experiment
			$participant = $this->participationModel->get_participant_by_participation($appointment->id);
			$experiment = $this->participationModel->get_experiment_by_participation($appointment->id);
				
			// Begin and end datetime
			$dateTime = new DateTime($appointment->appointment);
			$startTime = $dateTime->format(DateTime::ISO8601);
			date_add($dateTime, date_interval_create_from_date_string($experiment->duration . ' minutes'));
			$end = $dateTime->format(DateTime::ISO8601);
				
			// Colors
			$bgcolor = $experiment->experiment_color;
			$textcolor = isset($bgcolor) ? get_foreground_color($bgcolor) : "";

			// Generate array for event
			$event = array(
				"title" => "\n" . name($participant),
				"start" => $startTime,
				"end"	=> $end,
				"color" => $bgcolor,
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
			array_push($events,$event);
		}

		// Returns a json array
		echo json_encode($events);
	}

	/**
	 * Generates HTML Output for the calender tooltip
	 * @param int $id 	appointment ID
	 * @param Participant $participant
	 * @param Experiment $experiment
	 */
	private function generate_tooltip($id,$participant, $experiment)
	{
		$expLink = lang('experiment');
		$expLink .= ": <a href='experiment/get/" . $experiment->id . "' title='" . $experiment->name . "'";
		$expLink .= ">" . $experiment->name . "</a><br/>";

		$partLink = lang('participant');
		$partLink .= ": <a href='participant/get/" . $participant->id . "'title='" . name($participant) . "'";
		$partLink .= ">" . name($participant) . "</a><br/>";

		$participation_actions = "<center>" . participation_actions($id) . "</center>";

		return addslashes($expLink . $partLink . $participation_actions);
	}

	/**
	 * Generates the content of the legend tooltip
	 */
	private function generate_legend()
	{
		$exps = $this->experimentModel->get_all_experiments();

		$title = "<h3>" . lang('experiment_color') . "</h3>";
		$colors = "";

		foreach ($exps as $e)
		{
			$colors .= get_colored_label($e);
		}

		return $title . $colors ;
	}

	/**
	 * Generates the messages for an event, based on appointment information.
	 * Appointment $appointment
	 */
	private function get_messages($appointment)
	{
		return ($appointment->cancelled) ? lang('rescheduled') : "";
	}

}