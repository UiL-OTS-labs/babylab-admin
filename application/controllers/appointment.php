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
		$data['events'] = $this->events();
		$data['legend'] = $this->generate_legend();
		$data['lang'] = (current_language() == 'dutch') ? 'nl' : 'en';
		
		// Load the view
		$this->load->view('templates/header', $data);
		$this->authenticate->authenticate_redirect('appointment_view.php');
		$this->load->view('templates/footer');
	}
	
	/** Generates an array of events for the calender */
	public function events()
	{
		$appointments = $this->participationModel->get_all_appointments();
		
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
				"title" => name($participant),
				"start" => $startTime,
				"end"	=> $end,
				"color" => $bgcolor,
				"textColor" => $textcolor,
				"experiment" => $experiment->name,
				"type"		=> $experiment->type,
				"tooltip"	=> $this->generate_tooltip($appointment->id, $participant, $experiment),
				"message"	=> $this->get_messages($appointment),
			);

			// Add array to events
			array_push($events,$event);
		}
		
		// Returns a json array
		return json_encode($events);
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