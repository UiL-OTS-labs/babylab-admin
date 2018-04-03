<script>
	$(document).ready(function() {
		$( "#accordion" ).accordion({
			collapsible: true,
			heightStyle: "content",
			active: <?=$this->session->flashdata('comment_message') ? 4 : 
						($this->session->flashdata('impediment_message') ? 3 : 0); ?>
		});
	});
</script>

<?=heading($page_title, 2); ?>

<?=$this->session->flashdata('message'); ?>

<div id="accordion">
	<!-- General info -->
	<h3>
	<?=lang('general_info'); ?>
	</h3>
	<div class="pure-g">
		<div class="pure-u-3-5">
			<!-- Languages/dyslexia confirmation -->
			<?php 
				if ($verify_languages || $verify_dyslexia) 
				{ 
					echo '<p class="warning">'. implode('<br />', array_merge($verify_languages, $verify_dyslexia)) . '</p>';
				} 
			?>
			<table class="pure-table">
				<tr>
					<th><?=lang('name'); ?></th>
					<td><?=name($participant); ?> (<?=gender($participant->gender); ?>)</td>
				</tr>
				<tr>
					<th><?=lang('dob'); ?></th>
					<td><?=dob($participant->dateofbirth); ?></td>
				</tr>
				<tr>
					<th><?=lang('birthweight'); ?></th>
					<td><?=birthweight($participant); ?></td>
				</tr>
				<tr>
					<th><?=lang('pregnancy'); ?></th>
					<td><?=pregnancy($participant); ?></td>
				</tr>
				<tr>
					<th><?=lang('age'); ?></th>
					<td><?=age_in_ymd($participant); ?></td>
				</tr>
				<tr>
					<th><?=lang('dyslexicparent'); ?></th>
					<td><?=img_tick($participant->dyslexicparent); ?></td>
				</tr>
				<tr>
					<th><?=lang('multilingual'); ?></th>
					<td><?=img_tick($participant->multilingual); ?></td>
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
		</div>
		<div class="pure-u-2-5">
			<?php
				if (!$participant->activated)
				{
					$reason = $participant->deactivated_reason;
					$new = $reason == DeactivateReason::NewParticipant;
					$class = $new ? 'warning' : 'info';
					echo '<div class="' . $class . '"';
					echo "<p>" . lang('p_not_yet_active') . "</p>";
					echo "<p>" . lang('reason') . '<br />';
					echo lang('reason_' . $reason) . ' (' . output_datetime($participant->deactivated) . ")</p>";
					if ($new) echo participant_activate_link($participant, lang('activate'));
					echo "</div>";
				}
			?>
		</div>
	</div>

	<!-- Contact details -->
	<?=heading(lang('contact_details'), 3); ?>
	<div>
		<table class="pure-table">
			<tr>
				<th><?=lang('parent_name'); ?></th>
				<td><?=parent_name($participant) ?></td>
			</tr>
			<tr>
				<th><?=lang('phone'); ?></th>
				<td><?=$participant->phone; ?></td>
			</tr>
			<tr>
				<th><?=lang('phonealt'); ?></th>
				<td><?=$participant->phonealt; ?></td>
			</tr>
			<tr>
				<th><?=lang('email'); ?></th>
				<td><?=mailto($participant->email); ?></td>
			</tr>
		</table>
	</div>

	<!-- Languages and dyslexia -->
	<?php
		if ($participant->multilingual || $participant->dyslexicparent)
		{
			echo heading(lang('specific_info'), 3);
			echo '<div>';

			if ($participant->multilingual) 
			{
				echo heading(lang('languages'), 4);
				create_language_table('languages');
				$languages['id'] = 'languages';
				$languages['ajax_source'] = 'language/table_by_participant/' . $participant->id;
				$this->load->view('templates/list_view', $languages);	
			}

			if ($participant->dyslexicparent)
			{
				echo heading(lang('dyslexia'), 4);
				create_dyslexia_table('dyslexia');
				$dyslexia['id'] = 'dyslexia';
				$dyslexia['ajax_source'] = 'dyslexia/table_by_participant/' . $participant->id;
				$this->load->view('templates/list_view', $dyslexia);
			}
			echo '</div>';
		}
	?>

	<!-- Impediments -->
	<?=heading(lang('impediments') . ' (' . $impediment_size . ')', 3); ?>
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
	<?=heading(lang('comments') . ' (' . $comment_size . ')', 3); ?>
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
	<?=heading(lang('participations') . ' (' . $participation_size . ')', 3); ?>
	<div>
	<?php
	create_participation_table('participations');
	$participations['id'] = 'participations';
	$participations['ajax_source'] = 'participation/table/' . $participant->id;
	$this->load->view('templates/list_view', $participations);
	?>
	</div>

	<!-- TestInvites -->
	<?=heading(lang('testinvites'), 3); ?>
	<div>
	<?php
		create_testinvite_participant_table('testinvites');
		$testinvites['id'] = 'testinvites';	
		$testinvites['ajax_source'] = 'testinvite/table_by_participant/' . $participant->id;
		$this->load->view('templates/list_view', $testinvites); 
	?>
	</div>
</div>
