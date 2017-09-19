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
		$(".show").hide();
		$(".call_link").click(function() {
			$(".call_link").next().hide();
			$(this).next().show("slow");
		});

		// Appointment(s) scheduling
		$('input[name="appointment"]').datetimepicker({
			changeMonth : true,
			changeYear : true,
			minDate : '<?=$min_date_js; ?>',
			maxDate : '<?=$max_date_js; ?>',
			showOn : 'both',
			buttonImage : 'images/calendar.png',
			buttonImageOnly : true,
			buttonText : 'Pick a date and time'
		});
		$('input[name="comb_appointment"]').datetimepicker({
			changeMonth : true,
			changeYear : true,
			minDate : '<?=isset($comb_min_date_js) ? $comb_min_date_js : ''; ?>',
			maxDate : '<?=isset($comb_max_date_js) ? $comb_max_date_js : ''; ?>',
			showOn : 'both',
			buttonImage : 'images/calendar.png',
			buttonImageOnly : true,
			buttonText : 'Pick a date and time'
		});
		
        // Set the appointment field(s) to read-only
		$( ".appointment" ).attr({
			readOnly : true
		});

		// If one wants to send an invitation for a combination experiment, toggle that appointment field. 
		$('input[name="send_combination"]').click(function() {
        	$('#comb_appointment').toggle();
    	});

		// Call back scheduling
		$('input[name="call_back_date"').datepicker({
        	dateFormat : 'dd-mm-yy',
			changeMonth : true,
			changeYear : true,
			minDate : 0, // from now onwards
			maxDate : '<?=$max_date_js; ?>',
			showOn : 'both',
			buttonImage : 'images/calendar.png',
			buttonImageOnly : true,
			buttonText : 'Pick a date'
		});
		
        // Set the appointment field(s) to read-only
		$('input[name="call_back_date"').attr({
			readOnly : true
		});

		// Navigation handling: only allow navigation from forms or the cancel link.
		var correct = false; 

		$( "form" ).submit(function() {
			correct = true;
		});
		$( "#cancel_link" ).click(function() {
			correct = true;
		});

		// Final handling with onbeforeload
		window.onbeforeunload = confirmExit;
		function confirmExit() {
			if (!correct) {
				return "Please only use the navigation buttons provided on this page.";
    		}
			else {
				return null;
			}
		}

		function checkChosenTime()
		{
			var time = $('[name="appointment"]')[0].value;
			var date = time.split(" ")[0].split("-");
			var date = date[2] + "-" + date[1] + "-" + date[0] + "T" + time.split(" ")[1] + ":00";

			var leader_id = $("#leader").val();
			var url = "participation/check_moment/" + date + "/" + <?=$experiment->id;?> + "/" + leader_id;
			var alertbox = $('#alertboxChosenTime');

			$.ajax({
				dataType: "json",
				url: url,
				success: function(data){
					// console.log("hmm");
					alertbox.html("<ul>")
					alertbox.css('display', 'none');

					if (data["locks"]["status"])
					{
						alertbox.css('display', 'block');
						alertbox.append("<p><b>" + data["locks"]["string"] + "<b>");
						alertbox.append("<?=lang('lockdown_at_times');?><ul>");
						for(var i = 0; i < data["locks"]["times"].length; i++)
						{
							alertbox.append("<li style='margin-left: 30px;'>" + data["locks"]["times"][i] + "</li>");
						}
						alertbox.append("</ul></p>");
					}

					if(data["closings"]["status"])
					{
						alertbox.css('display', 'block');
						alertbox.append("<p><b>" + data["closings"]["string"] + "<b>");
						alertbox.append("<?=lang('lab_closed_at_times');?><ul>");
						for(var i = 0; i < data["locks"]["times"].length; i++)
						{
							alertbox.append("<li style='margin-left: 30px;'>" + data["closings"]["times"][i] + "</li>");
						}
						alertbox.append("</ul></p>");
					}

					if(data["availability"]["status"])
					{
						alertbox.css('display', 'block');
						alertbox.append("<p><b>" + data["availability"]["string"] + "<b>");
						alertbox.append("<?=lang('is_available_at_times');?><ul>");
						for(var i = 0; i < data["availability"]["times"].length; i++)
						{
							alertbox.append("<li style='margin-left: 30px;'>" + data["availability"]["times"][i] + "</li>");
						}
						alertbox.append("</ul></p>");
					}
				},
				error : function(error){console.log(error);}
			});
		}

		$('input[name="appointment"]').change(function(){
			checkChosenTime();
		});

		$('#leader').change(function(){
			checkChosenTime();
		});

	});
</script>

<?=heading($page_title, 2); ?>
<div id="accordion">
	<?=heading(lang('contact_details'), 3); ?>
	<div class="pure-g">
		<div class="pure-u-3-5">	
			<?=sprintf(lang('now_calling'), name($participant), gender($participant->gender), dob($participant->dateofbirth), age_in_months($participant)); ?>
			<?php if ($participation->nrcalls > 0 && !empty($previous_call)) {
				echo '<p><em>';
				echo sprintf(lang('already_called'), name($participant), $participation->nrcalls, output_date($participation->lastcalled), lcfirst(lang($previous_call->status)));
				echo '</em></p>';
			} ?>
		
				<!-- Contact details -->
			<?=sprintf(lang('call_contact'), $participant->firstname, parent_name($participant)); ?>
		
			<br> <br>
			<table class="pure-table">
				<tr>
					<th><?=lang('phone'); ?></th>
					<td><?=$participant->phone; ?></td>
				</tr>
				<?php if (!empty($participant->phonealt)) { ?>
				<tr>
					<th><?=lang('phonealt'); ?></th>
					<td><?=$participant->phonealt; ?></td>
				</tr>
				<?php } ?>
				<tr>
					<th><?=lang('email'); ?></th>
					<td><?=mailto($participant->email); ?></td>
				</tr>
				<tr>
					<th><?=lang('registered'); ?></th>
					<td>
						<?php 
							echo output_date($participant->created);
							if ($participant->created < $participant->dateofbirth)
							{
								echo ' ';
								echo '<span class="warning">';
								echo lang('registered_before_birth');
								echo '</span>';
							}
						?>
					</td>
				</tr>
				<tr>
					<th><?=lang('last_experiment'); ?></th>
					<td><?=$last_experiment; ?></td>
				</tr>
				<tr>
					<th><?=lang('last_called'); ?></th>
					<td><?=$last_called; ?></td>
				</tr>
			</table>
	
			<!-- Languages/dyslexia confirmation -->
			<?php 
				if ($verify_languages || $verify_dyslexia) 
				{ 
					echo '<p class="warning">'. implode(br(), array_merge($verify_languages, $verify_dyslexia)) . '</p>';
				} 
			?>
	
			<!-- When to make an appointment -->
			<p>
			<?=sprintf(lang('call_dates'), name($participant), $min_date, $max_date); ?>
			</p>
	
			<!-- Participation actions -->
			<p>
			<?=lang('call_action'); ?>
			</p>
			<?=$this->session->flashdata('message'); ?>
			<ul>
				<li><u class="call_link"><?=lang('confirmed'); ?> </u>
					<div class="show">
					<?=form_open('call/confirm/' . $call_id, array('class' => 'pure-form')); ?>
						<p>
						<?=form_checkbox('concept', 1, FALSE); ?>
						<?=form_label(sprintf(lang('concept_mail_only'), TO_EMAIL_OVERRIDE)); ?>
						</p>
						<!-- Temporary addition (hopefully) to select whether or not to send an anamnese -->
						<?php if ($first_visit) { ?>
							<p>
							<?=form_checkbox('send_anamnese', 1, TRUE); ?>
							<?=form_label(lang('send_anamnese')); ?>
							</p>
						<?php } ?>
						<?php if ($combination_exp) { ?>
							<p>
							<?=form_checkbox('send_combination', 1, FALSE); ?>
							<?=form_label(sprintf(lang('send_combination'), $combination_exp->name)); ?>
							<span id="comb_appointment" style="display:none;">
							<?=form_input('comb_appointment', '', 'placeholder= "' . lang('appointment') . '" class="appointment required"'); ?>
							<?=form_label(lang('leader')); ?>
                            <?=form_dropdown_and_label('comb_leader', $combination_leaders, array(), '', FALSE, NULL); ?>
							<?=form_hidden('comb_exp', $combination_exp->id); ?>
							</span>
							</p>
						<?php } ?>
						<p>
						<?=form_label(lang('appointment')); ?>
						<?=form_input('appointment', '', 'placeholder= "' . lang('appointment') . '" class="appointment required"'); ?>
                        <?=form_error('appointment'); ?>
						<?=form_dropdown_and_label('leader', $leaders, array(), '', FALSE, NULL); ?>
                        </p>
                        <div id="alertboxChosenTime" class="warning" style="text-align: left; display: none; padding-left: 40px;">
                        	
                        </div>
                        <p>
						<?=form_submit_only(); ?>
						</p>
						<?=form_close(); ?>
					</div>
				</li>
				<li><u class="call_link"><?=lang('cancelled'); ?> </u>
					<div class="show">
						<?=form_open('call/cancel/' . $call_id, array('class' => 'pure-form')); ?>
						<p>
							<?=form_checkbox(array('name' => 'never_again', 'id' => 'never_again', 'value' => true)); ?>
							<label for="never_again"><?=lang('cancelled_complete'); ?></label></p><p>
							<?=form_input('comment', '', 'placeholder= "' . lang('comment') . '"'); ?>
							<?=form_submit_only(); ?>
						</p>
						<?=form_close(); ?>
					</div>
				</li>
				<li><u class="call_link"><?=lang('call_back'); ?> </u>
					<div class="show">
						<?=form_open('call/call_back/' . $call_id); ?>
						<p>
							<?=lang('date'); ?>
							<?=form_input('call_back_date', '', 'placeholder= "' . lang('date') . '" class="required"'); ?>
							<?=form_input('call_back_comment', '', 'placeholder= "' . lang('comment') . '"'); ?>
							<?=form_submit_only(); ?>
						</p>
						<?=form_close(); ?>
					</div>
				</li>
				<li><u class="call_link"><?=lang('via_email'); ?> </u>
					<div class="show">
					<?=form_open('call/via_email/' . $call_id); ?>
						<p>
							<?=lang('via_email_info'); ?>
							<?=form_submit_only(); ?>
						</p>
						<?=form_close(); ?>
					</div>
				</li>
				<li><u class="call_link"><?=lang('no_reply'); ?> </u>
					<div class="show">
					<?=form_open('call/no_reply/' . $call_id); ?>
						<p>
							<?=lang('message_left'); ?>
							<?=form_radio_and_label('message', 'none', 'none', lang('no')); ?>
							<?=form_radio_and_label('message', 'voicemail'); ?>
							<?=form_radio_and_label('message', 'email', '', lang('email_sent')); ?>
							<?=form_submit_only(); ?>
						</p>
						<?=form_close(); ?>
					</div>
				</li>
				<li><?=anchor('call/undo/' . $call_id, lang('cancel'), array('id' => 'cancel_link')); ?>
				</li>
			</ul>
		</div>
		<div class="pure-u-1-5"></div>
		<div class="pure-u-1-5">
			<?php
				$calendar_attrs = array(
					'screenx' => '100',
					'screeny' => '50',
					'width' => '1200',
					'height' => '800',
					'toolbar' => 'no',
					'directories' =>  'no',
					'status' => 'no',
					'menubar' => 'no',
					'resizable' => 'yes'
				);
				$calendar_content = '<center><img class="pure-u-3-4" src="images/calendar_large.png" title="';
				$calendar_content .= lang('show_calendar');
				$calendar_content .= '" alt="';
				$calendar_content .= lang('show_calendar');
				$calendar_content .= '"/><h3>';
				$calendar_content .= lang('show_calendar');
				$calendar_content .= '</h3></center>';
			
				echo anchor_popup(site_url('appointment/index/0'), $calendar_content, $calendar_attrs);

				$edit_attrs = array(
					'screenx' => '100',
					'screeny' => '50',
					'width' => '1000',
					'height' => '800',
					'toolbar' => 'no',
					'directories' =>  'no',
					'status' => 'no',
					'menubar' => 'no',
					'resizable' => 'yes'
				);
				$edit_content = "<center>" . img_edit() . sprintf(lang('edit_participant'),name($participant)) . "</center>";
				$edit_url = site_url('participant/edit/' . $participant->id . "/0/0");
				echo anchor_popup($edit_url, $edit_content, $edit_attrs);

			?>

			
			
		</div>
	</div>

	<?=heading(lang('experiment'), 3); ?>
	<div>
		<!-- Experiment information TODO: clean up -->
		<div>
			<table class="pure-table">
				<tr>
					<th><?=lang('name'); ?></th>
					<td><?=$experiment->name; ?></td>
				</tr>
				<tr>
					<th><?=lang('description'); ?></th>
					<td><?=$experiment->description; ?></td>
				</tr>
				<tr>
					<th><?=lang('duration'); ?></th>
					<td><?=sprintf(lang('duration_total'), $experiment->duration, $experiment->duration_additional); ?></td>
				</tr>
				<tr>
					<th><?=lang('location'); ?></th>
					<td><?=location_name($experiment->location_id); ?></td>
				</tr>
				<tr>
					<th><?=lang('age_range'); ?></th>
					<td><?=age_range($experiment); ?></td>
				</tr>
				<tr>
					<th><?=lang('act_nr_part'); ?></th>
					<td><?=$nr_participations; ?></td>
				</tr>
				<tr>
					<th><?=lang('attachment'); ?></th>
					<td><?=$experiment->attachment . ' ' . 
					anchor(array('experiment/download_attachment', $experiment->id, 'attachment'), lang('download')); ?></td>
				</tr>
				<tr>
					<th><?=lang('informedconsent'); ?></th>
					<td><?=$experiment->informedconsent . ' ' . 
					anchor(array('experiment/download_attachment', $experiment->id, 'informedconsent'), lang('download')); ?></td>
				</tr>
			</table>
		</div>
	</div>

	<!-- Impediments -->
	<?=heading(lang('impediments'), 3); ?>
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
	<?=heading(lang('comments'), 3); ?>
	<div>
		<?php
			create_comment_table('comments');
			$comments['id'] = 'comments';
			$comments['ajax_source'] = 'comment/table/0/0/' . $participant->id;
			echo $this->load->view('templates/list_view', $comments);
			echo $this->load->view('comment_add_view'); 
		?>
	</div>

	<!-- Participations -->
	<?=heading(lang('participations'), 3); ?>
	<div>
		<?php
			create_participation_table('participations');
			$participations['id'] = 'participations';
			$participations['ajax_source'] = 'participation/table/' . $participant->id;
			echo $this->load->view('templates/list_view', $participations); 
		?>
	</div>
</div>
