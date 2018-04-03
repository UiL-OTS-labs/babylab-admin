<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Appointment extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->authenticate->redirect_except();
		reset_language(current_language());
	}

	/** Specifies the contents of the default page. */
	public function index($header = TRUE)
	{
		// Prepare the data
		$data['page_title'] = lang('calendar');
		$data['legend'] = $this->generate_legend();
		$data['lang'] = (current_language() == L::Dutch) ? 'nl' : 'en';
		$data['experiments'] = $this->experimentModel->get_all_experiments();
		$data['participants'] = $this->participantModel->get_all_participants();
		$data['locations'] = $this->locationModel->get_all_locations();
		$data['leaders'] = array_merge($this->userModel->get_all_leaders(), $this->userModel->get_all_admins());

		// Load the view
		if ($header) $this->load->view('templates/header', $data);
		else $this->load->view('templates/simple_header', $data);
		
		$this->load->view('appointment_view');
		$this->load->view('templates/footer');
	}

	/////////////////////////
	// JSON 
	/////////////////////////

	/** Generates an array of events (JSON encoded) for the calender */
	public function appointments()
	{
		$events = array();

		// For each appointment, create an event
		$appointments = $this->filter_appointments($this->input->post('start'), $this->input->post('end'));
		foreach ($appointments as $appointment)
		{
			// Participant, experiment and leader
			$participant = $this->participationModel->get_participant_by_participation($appointment->id);
			$experiment = $this->participationModel->get_experiment_by_participation($appointment->id);
			$leader = $this->participationModel->get_user_by_participation($appointment->id);

			// Begin and end datetime
			$dateTime = new DateTime($appointment->appointment);
			$startTime = $dateTime->format(DateTime::ISO8601);
			$minutes = $experiment->duration + $experiment->duration_additional;
			$dateTime->add(new DateInterval('PT' . $minutes . 'M'));
			$end = $dateTime->format(DateTime::ISO8601);

			// Colors
			$bgcolor = $experiment->experiment_color;
			$textcolor = isset($bgcolor) ? get_foreground_color($bgcolor) : '';

			// Title
			$title = "\n" . name($participant);
			$title .= "\n" . location_name($experiment->location_id);
			if ($leader) $title .= ' / ' . $leader->firstname;

			// Generate array for event
			$event = array(
				'title' 	=> $title,
				'start' 	=> $startTime,
				'end'		=> $end,
				'color' 	=> $bgcolor,
				'textColor' => $textcolor,
				'experiment' => $experiment->name,
				'type'		=> $experiment->type,
				'tooltip'	=> $this->generate_tooltip($appointment, $participant, $experiment),
				'message'	=> $this->get_messages($appointment),
				'className' => ($appointment->cancelled != 0) ? 'event-cancelled' : '',
			);

			// Add array to events
			array_push($events, $event);
		}

		// Returns a json array
		echo json_encode($events);
	}

	/**
	 * Generates events off of the availabilities from leaders and administrators
	 */
	public function availabilities()
	{
		$result = array();

		// For each user
		$availabilities = $this->filter_availabilities($this->input->post('start'), $this->input->post('end'));
		foreach ($availabilities as $u_id => $u)
		{
			// For each day
			foreach ($u as $day => $d)
			{
				// Begin and end datetime
				$user = $this->userModel->get_user_by_id($u_id);

				$event = array(
					'title' 	=> lang('availability') . ' ' . $user->firstname,
					'start' 	=> $day,
					'allDay'	=> true,
					'tooltip'	=> $this->generate_label($user, $d)
				);
			
				array_push($result, $event);
			}
		}

		// echo $this->input->get('start');

		echo json_encode($result);
	}

	/** Generates an array of closings (JSON encoded) for the calender */
	public function closings()
	{
		$events = array();

		$closings = $this->closingModel->get_all_closings($this->input->post('start'), $this->input->post('end')); 
		foreach ($closings as $closing) 
		{
			
			$lockdown = !(isset($closing->location_id));
			if($lockdown)
			{
				$title = lang('lockdown');
			} else {
				$location = location_name($closing->location_id);
				$title = lang('closing') . ' ' . $location;
			}

			$from = new DateTime($closing->from);
			$to = new DateTime($closing->to);

			$event = array(
				'title' 	=> $title,
				'allDay'	=> $from->diff($to)->format('%a') > 1,
				'start' 	=> $from->format(DateTime::ISO8601),
				'end'		=> $to->format(DateTime::ISO8601),
				'tooltip'	=> $closing->comment,
				'color'		=> ($lockdown) ? "#ff0000" : "",
			);

			// Add array to events
			array_push($events, $event);
		}

		// Returns a json array
		echo json_encode($events);
	}

	/////////////////////////
	// Filters 
	/////////////////////////

	/**
	 * Returns the appointments based on the filters provided. 
	 */
	private function filter_appointments($range_begin, $range_end)
	{
		$experiment_ids = $this->input->post('experiment_ids');
		$participant_ids = $this->input->post('participant_ids');
		$location_ids = $this->input->post('location_ids');
		$leader_ids = $this->input->post('leader_ids');
		$exclude_canceled = $this->input->post('exclude_canceled') == 'true';

		// If there are locations selected... 
		if ($location_ids)
		{
			// ... get the accompanying experiments
			$exp_locations = $this->experimentModel->get_experiments_by_locations($location_ids);
			// If no locations found, don't return experiments
			if (!$exp_locations) 
			{
				return array();
			}
			// Else, if no experiments selected, return only the experiments for the selected locations
			elseif (!$experiment_ids) 
			{
				$experiment_ids = $exp_locations;
			}
			// Else, return the intersection of both
			else 
			{
				$experiment_ids = array_intersect($experiment_ids, $exp_locations);
			}
		}

		return $this->participationModel->filter_participations($experiment_ids, $participant_ids, $leader_ids, $exclude_canceled, $range_begin, $range_end);
	}

	/**
	 * Runs the filter on availabilities
	 */
	private function filter_availabilities($range_begin, $range_end)
	{
		// Post Data
		$experiment_ids = $this->input->post('experiment_ids');
		$include_availability = $this->input->post('include_availability') == 'true';

		if ($include_availability)
		{
			if ($experiment_ids != '')
				$users = $this->leaderModel->get_leader_users_by_experiments($experiment_ids);
			else
				$users = $this->userModel->get_all_users();
			
			// For every selected user, get all availabilities
			foreach($users as $u)
			{
				$c_u = array();

				// Get availabilities for current user
				$av = $this->availabilityModel->get_availabilities_by_user($u->id, $range_begin, $range_end);

				// Iterate through availabilities
				foreach($av as $a)
				{
					// Current availability
					$c_a = array('from' => $a->from, 'to' => $a->to, 'comment' => $a->comment);
					
					// Get the date of the current availability
					$date = new DateTime($a->from);
					$k = $date->format('Y-m-d');

					// If an entry exists for this day, append, otherwise, create
					if(isset($c_u[$k]))
					{
						array_push($c_u[$k], $c_a);
					} else {
						$c_u[$k] = array($c_a);
					}
				}

				// Add the availabilities for the current user to the total
				$availabilities[$u->id] = $c_u;
			}

			// Return the availabilities
			return $availabilities;
		} 
		else 
		{
			// Do not show. Easiest solution
			return array();
		}
	}

	/////////////////////////
	// Helpers 
	/////////////////////////

	/**
	 * Generates HTML Output for the calender tooltip
	 * @param Participation $participation ID
	 * @param Participant $participant
	 * @param Experiment $experiment
	 */
	private function generate_tooltip($participation, $participant, $experiment)
	{
		$exp_link = lang('experiment') . ': ';
		$exp_link .= experiment_get_link($experiment);
		$exp_link .= '<br />';

		$part_link = lang('participant') . ': ';
		$part_link .= participant_get_link($participant);
		$part_link .= '<br />';

		$loc_link = lang('location') . ': ';
		$loc_link .= location_get_link_by_id($experiment->location_id);
		$loc_link .= '<br />';

		$user_link = '';
		if ($participation->user_id_leader)
		{
			$user_link .= lang('leader') . ': ';
			$user_link .= user_get_link_by_id($participation->user_id_leader);
			$user_link .= '<br />';
		}

		$comment = '';
		if ($participation->calendar_comment)
		{
			$comment .= lang('comment') . ': '; 
			$comment .= $participation->calendar_comment;
			$comment .= '<br />';
		}

		// Show actions only if user the leader of this participation (or if user is admin/caller)
		$current_user_is_leader = is_leader() && $participation->user_id_leader != current_user_id();
		$participation_actions = $current_user_is_leader ? '' : '<center>' . participation_actions($participation->id) . '</center>';

		return addslashes($exp_link . $part_link . $loc_link . $user_link . $comment . $participation_actions);
	}

	/**
	 * Generates the content of the legend tooltip
	 */
	private function generate_legend($exps = NULL, $title = NULL)
	{
		if(!$exps) $exps = $this->experimentModel->get_all_experiments();
		if(!$title) $title = heading(lang('experiment_color'), 3);

		$colors = '';
		foreach ($exps as $e)
		{
			$colors .= get_colored_label($e);
		}

		return $title . $colors;
	}

	/**
	 * Generates the messages for an event, based on appointment information.
	 * @param Participation $appointment
	 */
	private function get_messages($appointment)
	{
		return $appointment->cancelled ? lang('rescheduled') : '';
	}

	/**
	 * Generates the tooltip label of availability-events
	 */
	private function generate_label($user, $d)
	{
		$html = heading(sprintf(lang('availability_for_user'), $user->firstname), 3);

		$experiments = $this->leaderModel->get_experiments_by_leader($user->id);

		$html .= '<ul>';
		foreach ($d as $times)
		{
			$s = new Datetime($times['from']);
			$e = new Datetime($times['to']);
			$html .= '<li>';
			$html .= $s->format('H:i') . ' - ' . $e->format('H:i');
			if (isset($times->comment))
				$html .= '(' . $times->comment . ')';
		}
		$html .= '</ul>';

		if(sizeof($experiments) > 0)
		{
			$title = heading(sprintf(lang('exp_for_leader'), $user->firstname),3);
			$html .= $this->generate_legend($experiments, $title);
		} 
		else 
		{
			$html .= sprintf(lang('has_no_experiments'), $user->firstname);
		}
		
		return $html;
	}
}
