<?php

$lang['table_language'] 	= "js/datatables/language/english.txt";

$lang['not_authorized'] 	= "You are not authorized to view this page";
$lang['home'] 				= "Home";
$lang['babylab'] 			= "Babylab Utrecht";
$lang['action']				= "Action";
$lang['actions']			= "Actions";
$lang['delete']				= "Delete";
$lang['cancel']				= "Cancel";
$lang['back']				= "Back";
$lang['welcome']			= "Welcome <i>%s</i>!";
$lang['welcome_admin']		= "Welcome to the administrative interface of the Babylab call center.";
$lang['welcome_caller']		= "Welcome to the caller interface of the Babylab call center.";
$lang['info_caller']		= "You are enrolled as caller for %s experiment(s) (shown below).
								Curerntly you can call %s participant(s). 
							   	Choose one of the experiments below and click the phone icon to start calling.";
$lang['info_leader']		= "You are enrolled as leader for %s experiment(s) (shown below).";


$lang['error']				= "Whoops, something clearly went wrong here...";
$lang['activate']			= "Activate";
$lang['archive']			= "Archive";
$lang['deactivate']			= "Deactivate";
$lang['edit']				= "Edit";
$lang['tasks']				= "Your main tasks are:";
$lang['set_date']			= "Modify the date";
$lang['date']				= "Date";
$lang['select']				= "Select...";
$lang['boxplot']			= "Boxplot";
$lang['submit']				= "Submit";
$lang['inspect']	 		= "Inspect";
$lang['status']	 			= "Status";
$lang['here']	 			= "Here";
$lang['overview']	 		= "Overview";

$lang['yes']				= "Yes";
$lang['no']					= "No";
$lang['daily']				= "Daily";
$lang['weekly']				= "Weekly";
$lang['seldom']				= "Seldom";

$lang['day']                = "Day";
$lang['days'] 				= "days";
$lang['weeks'] 				= "weeks";
$lang['month']              = "Month";
$lang['months']             = "months";
$lang['year']               = "Year";
$lang['years'] 				= "years";

$lang['no_results_found']	= "No results found.";

/* Register */
$lang['register']			= "Register";
$lang['register_finish']	= "Thanks for your registration!";
$lang['register_info']		= "Your registration has been processed.
								Click " . anchor(base_url(), 'here') . " to return to the Babylab Utrecht website.";
$lang['deregister']			= "Deregister";
$lang['deregister_finish']	= "Thank you for your deregistration!";
$lang['deregister_info']	= "Your request for deregistering will be processed. In case we have some questions for you regarding your deregistration, we may contact you.";

$lang['register_return']    = "Klik " . anchor('http://babylab.wp.hum.uu.nl', 'hier') . " om terug te keren naar de website van het Babylab Utrecht.</p>";

/* Login page */
$lang['login'] 				= "Log in";
$lang['logout'] 			= "Log out";
$lang['reset']				= "Reset";
$lang['invalid_login']		= "Invalid credentials or user deactivated. Please try again.";
$lang['forgot_password'] 	= "Forgot password?";
$lang['forgot_pw_instr']	= "Please enter the email address for your account. You will be emailed a link to reset your password.";
$lang['forgot_pw_sent']		= "Password reset request successfully sent to %s.";
$lang['unknown_email']		= "Unknown e-mail address. Please try again.";
$lang['reg_user'] 			= "User registration";
$lang['reg_pp'] 			= "Participant registration";
$lang['dereg_pp'] 			= "Unregister as participant.";
$lang['reason']				= "Reason for unregistration";

/* Mails */
$lang['mail_heading']		= "Dear %s,";
$lang['mail_ending']		= "Thanks, Babylab Utrecht";
$lang['mail_disclaimer']	= "<em>This e-mail was generated automatically.</em>";
/* Reminders */
$lang['rem_subject'] 		= "Babylab call center reminder";
$lang['rem_body'] 			= "This is the weekly reminder for the Babylab call center:";
$lang['rem_exp_call']		= "In experiment %s, you can currently call %s participants.";
/* Registration of participants */
$lang['reg_pp_subject'] 	= "Babylab: participant registration";
$lang['reg_pp_body'] 		= "<p>A new participant has been registered: %s (tel.: %s). You're able to activate or delete this participant in the administration interface, <a href=\"%s\">or you can click  click here</a> to view and activate this participant.</p><p>If this link does not work, copy the following link to your browser: <p>%s</p>";
/* Deregistration of participants */
$lang['dereg_pp_subject'] 	= "Babylab Utrecht: Participant deregistration.";
$lang['dereg_pp_body'] 		= "Participant %s (date of birth: %s, e-mail: %s, reason: %s) has deregistered. You can deactivate this participant in the administration interface.";
/* Registration of users */
$lang['reg_user_subject'] 	= "Babylab: user registration";
$lang['reg_user_body'] 		= "A new user has been registered: %s (e-mail.: %s). You're able to activate or delete this participant in the administration interface.";
/* Reset password */
$lang['resetpw_subject'] 	= "Reset password request";
$lang['resetpw_body'] 		= "You send a reset password request. You can change your password at %s.";
$lang['reset_request_sent'] = "Already sent a reset request for this e-mail. Please check or your inbox. If you didn't receive a mail, contact an administator.";
/* Confirmation */
$lang['confirmation_sent']	= "A confirmation e-mail was sent to <em>%s</em>.";
$lang['reschedule_sent']	= "A confirmation e-mail of the rescheduling was sent to <em>%s</em>.";
$lang['request_participation_sent']	= "A participation request was sent to <em>%s</em>.";

/* Experiments */
$lang['experiment'] 		= "Experiment";
$lang['experiments'] 		= "Experiments";
$lang['add_experiment'] 	= "Add experiment";
$lang['exp_added']			= "New experiment successfully added.";
$lang['edit_experiment'] 	= "Edit experiment";
$lang['exp_edited']			= "Experiment successfully edited.";
$lang['type'] 				= "Task type";
$lang['wbs_number']			= "WBS Number";
$lang['experiment_color']	= "Experiment label color";
$lang['description'] 		= "Task description";
$lang['duration'] 			= "Duration";
$lang['multilingual']		= "Multilingual";
$lang['dyslexic']			= "Dyslexic";
$lang['age'] 				= "Age";
$lang['age_range'] 			= "Age range";
$lang['age_range_from'] 	= "Age range - from";
$lang['age_range_to'] 		= "Age range - to";
$lang['agefrommonths'] 		= "Age range from (months)";
$lang['agefromdays'] 		= "Age range from (days)";
$lang['agetomonths'] 		= "Age range to (months)";
$lang['agetodays']			= "Age range to (days)";
$lang['callable'] 			= "Callable";
$lang['callable_for']		= "Participants callable for experiment <em>%s</em>";
$lang['callable_longitudinal']	= "You can currently call <em>%s</em> participants for the longitudinal experiment <em>%s</em>.";
$lang['data_for_experiment']= "Details of experiment <em>%s</em>";
$lang['show_archived_exps'] = "Show archived experiments";
$lang['not_show_archived_exps'] = "Don't show archived experiments";
$lang['archived_exp']		= "Archived experiment successfully.";
$lang['unarchived_exp']		= "Activated experiment successfully.";
$lang['age_from_before_to'] = "The 'to' age range is less than the 'from' age range.";
$lang['act_nr_part']		= "Current number of participants";
$lang['attachment']         = "Attachment";
$lang['download']           = "Download";
$lang['remove']             = "Remove";
$lang['sure_remove_attachment'] = "Are you sure you want to remove the attachment? Other edits to the experiment will not be saved.";


/* Relations */
$lang['relation']	 		= "Relation";
$lang['relations']	 		= "Relations";
$lang['relation_deleted']	= "Relation removed";
$lang['sure_delete_relation']	= "Are you sure you want to remove this relation?";
$lang['prerequisite']		= "Is a prerequisite for participation to";
$lang['excludes']			= "Excludes participation to";
$lang['combination']		= "Is (possibly) combined with";

/* Locations */
$lang['location']			= "Location";
$lang['locations']			= "Locations";
$lang['data_for_location']	= "Details of location <em>%s</em>";
$lang['add_location'] 		= "Add location";
$lang['location_added']		= "New location <em>%s</em> was succesfully added.";
$lang['edit_location']		= "Edit location <em>%s</em>";
$lang['location_edited'] 	= "Location <em>%s</em> was succesfully edited.";
$lang['sure_delete_location']= "Are you sure you want to remove this location?";
$lang['location_deleted'] 	= "Location was succesfully removed.";
$lang['roomnumber']			= "Room number";
$lang['location_closed']	= "Location <em>%s</em> is closed then.";

/* Callers */
$lang['caller'] 			= "Caller";
$lang['callers']	 		= "Callers";
$lang['callers_for_exp']	= "Callers for experiment %s";
$lang['call_info'] 			= "You are now calling for experiment %s.
								Participants are shown that can participate in two weeks from now.  
								Choose one of the participants below and click on the phone icon to proceed.";
$lang['call_participants']	= "Call participants";
$lang['exp_without_call']	= "Currently there are no callers for %s experiment(s). You can add them in the <a href='experiment'>experiment overview</a>.";
$lang['add_callers_exp']	= "By <a href='%s'>modifying this experiment</a>, you're able to add (or delete) callers.";
$lang['sure_delete_caller']	= "Are you sure you want to delete this caller for this experiment?";
$lang['deleted_caller']		= "Deleted caller successfully.";
$lang['caller_action']		= "Adding callers to experiments (now: %s experiment(s) without callers)";
$lang['not_caller']			= "You are not a caller for experiment %s.";
$lang['not_callable_for']	= "%s is not callable (anymore) for experiment %s.";

/* Leaders */
$lang['leader'] 			= "Leader";
$lang['leaders']	 		= "Leaders";
$lang['leaders_for_exp']	= "Leaders voor experiment %s";
$lang['exp_without_leader']	= "Currently there are no leaders for %s experiment(s). You can add them in the " . anchor('experiment', 'experiment overview') . ".";
$lang['add_leaders_exp']	= "By <a href='%s'>modifying this experiment</a>, you're able to add (or delete) leaders.";
$lang['sure_delete_leader']	= "Are you sure you want to delete this leader for this experiment?";
$lang['deleted_leader']		= "Deleted leader successfully.";
$lang['leader_action']		= "Adding leaders to experiments (now: %s experiment(s) without leaders)";
$lang['not_leader']			= "You are not a leader for experiment %s.";
$lang['has_no_experiments']	= "%s is not currently leading any experiments.";

/* Participants */
$lang['participant']	 	= "Participant";
$lang['participants']	 	= "Participants";
$lang['new_participants']   = "Show newly registered (still deactivated) participants";
$lang['age_overview']       = "Show age overview";
$lang['data_for_pp']		= "Details of participant <em>%s</em>";
$lang['add_participant'] 	= "Add participant";
$lang['new_pp_added']		= "New participant <em>%s</em> successfully added.";
$lang['edit_participant']	= "Edit participant <em>%s</em>";
$lang['participant_edited'] = "Participant <em>%s</em> successfully edited.";
$lang['general_info']	 	= "General information";
$lang['contact_details']	= "Contact details";
$lang['parent_name']	 	= "Parent's name";
$lang['p_activated'] 		= "Participant <em>%s</em> activated.";
$lang['p_deactivated'] 		= "Participant <em>%s</em> deactivated.";
$lang['p_not_yet_active']	= "This participant is not (yet) activated.";
$lang['p_already_activated'] = "This participant is already activated.";
$lang['all_participants']	= "All participants";
$lang['pp_action']			= "Editing or (de)activating of participants";
$lang['city']				= "City";
$lang['birthweight']		= "Birth weight";
$lang['pregnancy']			= "Pregnancy age";
$lang['pregnancyweeks']		= "Pregnancy duration (weeks)";
$lang['pregnancydays']		= "Pregnancy duration (days)";
$lang['dyslexicparent']		= "Dyslexic parent";
$lang['problemsparent']		= "Language or motorical problems parent";
$lang['data_child']			= "Data child";
$lang['data_parent']		= "Data parent";
$lang['data_language']		= "Language specific questions";
$lang['data_end']			= "Lastly";
$lang['dyslexic_q']			= "Are there indications one of the parents may be <b>dyslectic</b>?";
$lang['problems_q']			= "Has one of the parents ever had a <b>language and/or motorical problem</b>?";
$lang['multilingual_q']		= "Is your child regularly exposed to <b>other languages</b> than Dutch?";
$lang['parent']				= "Parent";
$lang['father']				= "Father";
$lang['mother']				= "Mother";
$lang['son']				= "Son";
$lang['daughter']			= "Daughter";
$lang['boy']				= "Boy";
$lang['girl']				= "Girl";
$lang['his']				= "His";
$lang['her']				= "Her";
$lang['both']				= "Both";
$lang['origin']				= "How do you know about Babylab Utrecht?";
$lang['control']            = "Control";
$lang['participant_graph']  = "Show graph per year/month";

/* Participations */
$lang['participation']	 	= "Participation";
$lang['participations']	 	= "Participations";
$lang['participations_for']	= "Participations in experiment %s";
$lang['risk']               = "D/M";
$lang['last_called']	 	= "Last called";
$lang['last_call']	 		= "%s for %s";
$lang['last_experiment']	= "Last participation";
$lang['last_exp']	 		= "%s at %s";
$lang['never_called']		= "Never called for an experiment";
$lang['never_participated']	= "Never participated in an experiment";
$lang['call_participant']	= "Call participant %s";
$lang['in_conversation']	= "Participant %s is being called right now";
$lang['take_over_warning']	= "%s is being called right now by %s. Are you sure you want to take over this call?";
$lang['include_callable']	= "Include callable participants";
$lang['exclude_callable']	= "Exclude callable participants";
$lang['nr_calls']			= "Number of calls";
$lang['appointment']		= "Appointment";
$lang['confirmed']			= "Appointment confirmed";
$lang['reschedule']			= "Reschedule appointment";
$lang['rescheduled']		= "Appointment rescheduled";
$lang['reschedule_short']   = "reschedule";
$lang['reschedule_info']	= "Now rescheduling <strong>%s</strong> in experiment <em>%s</em>. The current appointment is at %s.";
$lang['cancelled']			= "Can't/won't participate";
$lang['cancelled_complete']	= "Never wants to participate again (unsubscribe from any next call)";
$lang['cancelled_short']	= "Cancelled";
$lang['cancel_info']        = "You're about to cancel the participation of <strong>%s</strong> in experiment <em>%s</em>. 
    The leader(s) of this experiment will receive an e-mail of this cancellation.
    You can also delete this appointment, so you'll be able to call the participant again.
    If you made a new appointment, it's better to %s this appointment right away.";
$lang['call_started']		= "Call started";
$lang['no_reply']			= "No reply";
$lang['no_show']			= "No show";
$lang['no_shows']			= "No-shows";
$lang['no_shows_for']		= "No-shows for %s";
$lang['no_shows_info']      = "This overview shows the no-shows per participant.
                                When there are a lot of these, one might want to remove the participant. 
                                You can use the deactivate-button here for that.";
$lang['completed']			= "Completed";
$lang['complete_part']		= "Complete participation";
$lang['complete_part_info']	= "In this window you can complete the participation of <em>%s</em> to experiment <em>%s</em>.
							   Please complete all required fields before confirming.";
$lang['message_left']		= "Left a message?";
$lang['none']				= "None";
$lang['voicemail']			= "Voicemail";
$lang['sure_delete_part']	= "Are you sure you want to delete this participation?";
$lang['part_cancel_call']	= "Calling <em>%s</em> for experiment <em>%s</em> cancelled.";
$lang['part_confirmed']		= "Participation of <em>%s</em> in experiment <em>%s</em> confirmed.";
$lang['part_cancelled'] 	= "Participation of <em>%s</em> in experiment <em>%s</em> cancelled.";
$lang['part_cancelled_complete'] 	= "Participation of <em>%s</em> in experiment <em>%s</em> cancelled.<br/><em>%s</em> deactivated.";
$lang['part_no_reply']		= "Added a no reply notice for <em>%s</em> in experiment <em>%s</em>.";
$lang['part_no_show']		= "Participation of <em>%s</em> in experiment <em>%s</em> marked as no-show.";
$lang['part_completed']		= "Participation of <em>%s</em> in experiment <em>%s</em> marked as completed.";
$lang['part_deleted']		= "Participation of <em>%s</em> in experiment <em>%s</em> deleted.";
$lang['part_rescheduled']	= "Participation of <em>%s</em> in experiment <em>%s</em> rescheduled.";
$lang['part_action']		= "Marking the participation of participants as completed (confirmed but not completed: %s participations)";
$lang['now_calling']		= "You're about to call <b>%s</b> (%s), born %s (%s months old).";
$lang['already_called']		= "%s has been called %s time(s) already for this experiment (last call: %s, action: %s).";
$lang['call_contact']		= "You can contact %s's parent <b>%s</b> via:";
$lang['call_action']		= "<strong><i>After you've made the call</i></strong>, please select one of the following options. <br>
							   On this page, you will be able to add a comment or an impediment stemming from your call.";
$lang['call_dates']			= "%s can participate in this experiment from %s to %s.";
$lang['risk_group']	 		= "Risk group";
$lang['control_group']	 	= "Control group";
$lang['part_number']	 	= "Participant number";
$lang['interrupted']	 	= "Interrupted";
$lang['interrupted_long']   = "Interrupted during experiment";
$lang['part_interrupted']	= "This participation was interrupted.";
$lang['interruptions']		= "Interrupted participations";
$lang['interruptions_for']	= "Interrupted participations for %s";
$lang['interruptions_info'] = "This overview shows the interrupted participations per participant.
                                When there are a lot of these, one might want to remove the participant. 
                                You can use the deactivate-button here for that.";
$lang['excluded']           = "Excluded";
$lang['excluded_reason']    = "Reason exclusion";
$lang['excluded_long']      = "Exclude this participation from analysis";
$lang['part_interrupted']   = "This participation is excluded from analysis.";
$lang['part_comment']		= "Comments for participation";
$lang['part_comment_info']	= "The (required) comment added below will only be saved for this participation. <em>(example: didn't sit still)</em>";
$lang['pp_comment']			= "Comments for participant";
$lang['pp_comment_info']	= "Any comment added below will be saved with the participant for future reference. <em>(example: possibly dyslexic)</em>";
$lang['tech_comment']		= "Comments for lab staff";
$lang['tech_comment_info']	= "Any comment added below will be e-mailed to the lab staff directly. <em>(example: sound seems off)</em>";
$lang['concept_mail_only']	= "Send concept confirmation mail to %s rather than directly to participant";
$lang['concept_send']		= "A concept confirmation mail has been send to %s. Do not forget to send the actual mail to %s!";

$lang['ad_hoc_participation'] = "Add ad hoc participation";
$lang['participation_no_restrictions'] = "<b>Warning!</b><br/>This page is intended as a <i>last resort</i> and does not contain any safeguards whatsoever.<br/>
Please make sure the participant can and wants to participate in the experiment and is available at the specified time!";
$lang['participation_exists'] = "A participation for <em>%s</em> to <em>%s</em> already exists. Please delete this participation, before you try to make a new one.";

/* Calls */
$lang['call']	 			= "Call";
$lang['calls']	 			= "Calls";
$lang['call_deleted']		= "Call deleted.";
$lang['sure_delete_call']	= "Are you sure you want to delete this call?";
$lang['order']				= "Order";
$lang['start_call']			= "Start call";
$lang['end_call']			= "End call";

/* Comments */
$lang['comment']	 		= "Comment";
$lang['comments']	 		= "Comments";
$lang['comments_for']	 	= "Comments for %s";
$lang['add_comment']	 	= "Add comment";
$lang['edit_comment']	 	= "Edit comment at <em>%s</em>";
$lang['posted_at']	 		= "Posted at";
$lang['posted_by']	 		= "Posted by";
$lang['comment_deleted']	= "Comment successfully deleted.";
$lang['comment_added']		= "New comment successfully added.";
$lang['comment_edited']		= "Comment successfully edited.";
$lang['sure_delete_comment']= "Are you sure you want to delete this comment?";
$lang['priority']			= "Priority";
$lang['comment_prio_up']	= "Upgraded the priority.";
$lang['comment_prio_down']	= "Downgraded the priority.";
$lang['comment_action']		= "Checking any comments with priority (now: %s comments)";
$lang['view_high_priority']	= "Show only high priority comments";
$lang['view_low_priority']	= "Also show normal priority comments";

/* Impediments */
$lang['impediment']	 		= "Impediment";
$lang['impediments']	 	= "Impediments";
$lang['add_impediment']	 	= "Add impediment";
$lang['next_impediment'] 	= "Next impediment";
$lang['impediment_deleted'] = "Impediment successfully deleted.";
$lang['impediment_added']	= "New impediment successfully added.";
$lang['include_past']		= "Include past impediments";
$lang['not_include_past']	= "Exclude past impediments";
$lang['impediments_for']	= "Impediments for %s";
$lang['sure_delete_imp']	= "Are you sure you want to delete this impediment?";
$lang['from_date']			= "Date from";
$lang['to_date']			= "Date until";
$lang['outside_bounds']		= "The '%s' is within the bounds of an already created impediment for the selected participant.";

/* Users */
$lang['user']				= "User";
$lang['users']				= "Users";
$lang['data_for_user']		= "Details of user <em>%s</em>";
$lang['add_user']			= "Add user";
$lang['edit_user']			= "Edit user <em>%s</em>";
$lang['edit_user_profile']  = "Change profile";
$lang['user_edited']		= "User successfully edited.";
$lang['user_added']			= "New user successfully added.";
$lang['change_password']	= "Change password";
$lang['username']			= "Username";
$lang['name'] 				= "Name";
$lang['firstname'] 			= "First name";
$lang['lastname'] 			= "Last name";
$lang['parentfirstname']	= "First name parent";
$lang['parentlastname'] 	= "Last name parent";
$lang['gender'] 			= "Gender";
$lang['password']			= "Password";
$lang['password_conf']		= "Verify password";
$lang['password_prev']		= "Previous password";
$lang['password_new']		= "New password";
$lang['password_updated']	= "Password successfully changed";
$lang['prev_pass_incorrect']= "The entered previous password is incorrect. Please try again.";
$lang['role']				= "Role";
$lang['activated']			= "Activated";
$lang['dob']				= "Date of birth";
$lang['email']				= "E-mail";
$lang['phone']				= "Phone";
$lang['phonealt']			= "Alternative phone number";
$lang['mobile']				= "Mobile";
$lang['preferredlanguage']	= "Preferred language";
$lang['admin']				= "Admin";
$lang['english']			= "English";
$lang['dutch']				= "Dutch";
$lang['u_activated'] 		= "User %s activated.";
$lang['u_deactivated'] 		= "User %s deactivated.";
$lang['u_deactivated_self'] = "You can not deactivate yourself.";
$lang['sure_delete_user']	= "Are you sure you want to delete this user? This will also delete any comments made by this user.";
$lang['deleted_user']		= "User %s successfully deleted.";

/* Test Categories */
$lang['testcat']			= "Test category";
$lang['testcats']			= "Test categories";
$lang['data_for_testcat']	= "Details of test category <em>%s</em>";
$lang['add_testcat'] 		= "Add new test category";
$lang['testcat_added']		= "New test category <em>%s</em> was succesfully added.";
$lang['edit_tc']			= "Edit test category";
$lang['edit_testcat']		= "Edit test category <i>%s</i>";
$lang['testcat_edited'] 	= "Test category %s was succesfully edited.";
$lang['sure_delete_testcat']= "Are you sure you want to remove this test category";
$lang['testcat_deleted'] 	= "Test category succesfully removed.";
$lang['parent_testcat'] 	= "Parent category";
$lang['code']				= "Code";

/* Appointments calender overview page */
$lang['appointments'] 		= "Appointments";
$lang['calendar'] 			= "Calendar";

/* Appointment Calendar */
$lang['legend']				= "Legend";
$lang['filter_experiment']	= "Filter by experiment";
$lang['filter_participant'] = "Filter by participant";
$lang['filter_location']	= "Filter by location";
$lang['clear_filters']		= "Clear filters";
$lang['exclude_empty']		= "Exclude cancelled appointments";
$lang['date_text']			= "Jump to date";
$lang['show_calendar']		= "Show the calendar";
$lang['show_availability']	= "Show availability of research leaders";

/* Anamnese */
$lang['send_anamnese'] 		= "Send the anamnese?";

/* Availability view */
$lang['availability']		= "Availability";
$lang['create_availability'] = "Add new availability information";
$lang['availability_include_past'] = "Show old availability information";
$lang['availability_not_include_past'] = "Do not show old availability information";
$lang['availability_add']	= "Add to table";
$lang['availability_reset']	= "Reset";
$lang['availability_schedule']	= "Table of availability";
$lang['time_from']			= "Time from";
$lang['time_to']			= "Time to";
$lang['availability_select'] = "Select date and times";
$lang['date_range']			= "Select multiple dates in one go";
$lang['daterange_already_exists'] = "De following items already exist or are in the same range as items in the database:<br/>%sPlease delete these items and try again";
$lang['no_permission']			= "You have no permission for this action";
$lang['exp_for_leader']		= "%s is leader for the following experiments:";
$lang['availability_for_user'] = "%s is available at the following times:";

?>