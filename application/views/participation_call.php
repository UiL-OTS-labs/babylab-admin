<script>
	$(function() {
		// Accordion display
		$( "#accordion" ).accordion({
			collapsible: true,
			heightStyle: "content",
			active: <?=$this->session->flashdata('comment_message') ? 3 : 
					   ($this->session->flashdata('impediment_message') ? 2 : 0); ?>
		});

		// Show/hide actions
		$( ".show" ).hide();
		$( ".click" ).click(function() {
			$( ".show", this ).show( "slow" );
		});

		// Appointment scheduling
		$( "#appointment" ).datetimepicker({
			changeMonth : true,
			changeYear : true,
			minDate : '<?=$min_date_js; ?>',
			maxDate : '<?=$max_date_js; ?>',
			showOn : 'both',
			buttonImage : 'images/calendar.png',
			buttonImageOnly : true,
			buttonText : 'Pick a date and time'
		});
		
		$( "#appointment" ).attr({
			readOnly : true
		});
	});
</script>

<?=heading($page_title, 2); ?>

<div id="accordion">
	<h3><?=lang('contact_details'); ?></h3>
	<div>
		<?=sprintf(lang('now_calling'), name($participant), gender($participant->gender), dob($participant->dateofbirth), age_in_months($participant)); ?>
		<?php if ($participation->nrcalls > 0 && !empty($previous_call)) {
			echo '<p><em>';
			echo sprintf(lang('already_called'), name($participant), $participation->nrcalls, output_date($participation->lastcalled), lcfirst(lang($previous_call->status)));
			echo '</em></p>';
		} ?>
		
		<!-- Contact details -->
		<?=sprintf(lang('call_contact'), $participant->firstname, parent_name($participant)); ?><br><br>
		<table class="pure-table">
			<tr><th><?=lang('phone'); ?></th><td><?=$participant->phone; ?></td></tr>
			<?php if (!empty($participant->phonealt)) { ?>
				<tr><th><?=lang('phonealt'); ?></th><td><?=$participant->phonealt; ?></td></tr>
			<?php } ?>
			<tr><th><?=lang('email'); ?></th><td><?=mailto($participant->email); ?></td></tr>
			<tr><th><?=lang('last_experiment'); ?></th><td><?=$last_experiment; ?></td></tr>
			<tr><th><?=lang('last_called'); ?></th><td><?=$last_called; ?></td></tr>
		</table>
		
		<!-- Languages confirmation -->
		<?php if ($verify_languages) { ?>
			<p class="warning"><?=sprintf(lang('verify_languages'), name($participant), participant_edit_link($participant->id)); ?></p>
		<?php } ?>
		
		<!-- When to make an appointment -->
		<p><?=sprintf(lang('call_dates'), name($participant), $min_date, $max_date); ?></p>
	
		<!-- Participation actions -->
		<p><?=lang('call_action'); ?></p>
		<?=$this->session->flashdata('message'); ?>
		<ul>
			<li class="click"><u class="call_link"><?=lang('confirmed'); ?></u>
				<div class="show">
					<?=form_open('call/confirm/' . $call_id, array('class' => 'pure-form')); ?>
					<p><?=form_input('appointment', '', 'placeholder= "' . lang('appointment') . '" id="appointment"'); ?>
					<?=form_submit_only(); ?></p>
					<?=form_close(); ?>
				</div>
			</li>
			<li class="click"><u class="call_link"><?=lang('cancelled'); ?></u>
				<div class="show">
					<?=form_open('call/cancel/' . $call_id, array('class' => 'pure-form')); ?>
					<p><?=form_input('comment', '', 'placeholder= "' . lang('comment') . '"'); ?>
					<?=form_submit_only(); ?></p>
					<?=form_close(); ?>
				</div>
			</li>
			<li class="click"><u class="call_link"><?=lang('no_reply'); ?></u>
				<div class="show">
					<?=form_open('call/no_reply/' . $call_id); ?>
					<p><?=lang('message_left'); ?>
					<?=form_radio_and_label('message', 'none', 'none', lang('no')); ?>
					<?=form_radio_and_label('message', 'voicemail'); ?>
					<?=form_radio_and_label('message', 'email'); ?>
					<?=form_submit_only(); ?></p>
					<?=form_close(); ?>
				</div>
			</li>
			<li><?=anchor('call/undo/' . $call_id, lang('cancel')); ?></li>
		</ul>
	</div>
	
	<h3><?=lang('experiment'); ?></h3>
	<div>
		<!-- Experiment information TODO: clean up -->
		<div>
			<table class="pure-table">
			<tr><th><?=lang('name'); ?></th><td><?=$experiment->name; ?></td></tr>
			<tr><th><?=lang('description'); ?></th><td><?=$experiment->description; ?></td></tr>
			<tr><th><?=lang('duration'); ?></th><td><?=$experiment->duration; ?></td></tr>
			<tr><th><?=lang('location'); ?></th><td><?=location_name($experiment->location_id); ?></td></tr>
			<tr><th><?=lang('age_range'); ?></th><td><?=age_range($experiment); ?></td></tr>
			<tr><th><?=lang('act_nr_part'); ?></th><td><?=$nr_participations; ?></td></tr>
			</table>
		</div>
	</div>
	
	<!-- Impediments --> 
	<h3><?=lang('impediments') . ' (' . $impediment_size . ')'; ?></h3>
	<div>
		<?php
			create_impediment_table('impediments');
			$impediments['id'] = 'impediments';
			$impediments['ajax_source'] = 'impediment/table/1/' . $participant->id;
			echo $this->load->view('templates/list_view', $impediments);
			echo $this->load->view('impediment_add_view', $impediments); 
		?>
	</div>
	
	<!-- Comments -->
	<h3><?=lang('comments') . ' (' . $comment_size . ')'; ?></h3>
	<div>
		<?php
			create_comment_table('comments');
			$comments['id'] = 'comments';
			$comments['ajax_source'] = 'comment/table/0/' . $participant->id;
			echo $this->load->view('templates/list_view', $comments);
			echo $this->load->view('comment_add_view'); ?>
	</div>
	
	<!-- Participations -->
	<h3><?=lang('participations') . ' (' . $comment_size . ')'; ?></h3>
	<div>
		<?php
			create_participation_table('participations');
			$participations['id'] = 'participations';
			$participations['ajax_source'] = 'participation/table/' . $participant->id;
			echo $this->load->view('templates/list_view', $participations); ?>
	</div>
</div>
