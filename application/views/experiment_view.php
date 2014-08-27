<script>
	$(function() {
		$( "#accordion" ).accordion({
			collapsible: true,
			heightStyle: "content"
		});
	});
</script>

<?=heading($page_title, 2); ?>

<?=$this->session->flashdata('message'); ?>

<div
	id="accordion">
	<!-- General info -->
	<h3>
	<?=lang('general_info'); ?>
	</h3>
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
				<td><?=$experiment->duration; ?></td>
			</tr>
			<tr>
				<th><?=lang('location'); ?></th>
				<td><?=location_get_link($location); ?></td>
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
				<th><?=lang('wbs_number'); ?></th>
				<td><?=$experiment->wbs_number; ?></td>
			</tr>
			<tr>
				<th><?=lang('experiment_color'); ?></th>
				<td><?=stripslashes(get_colored_label($experiment));?></td>
			</tr>
		</table>
	</div>

	<!-- Callers -->
	<h3>
	<?=lang('callers'); ?>
	</h3>
	<div>
	<?php
	create_caller_table('callers');
	$callers['id'] = 'callers';
	$callers['ajax_source'] = 'caller/table_by_experiment/' . $experiment->id;
	$this->load->view('templates/list_view', $callers);
	?>
	</div>

	<!-- Leaders -->
	<h3>
	<?=lang('leaders'); ?>
	</h3>
	<div>
	<?php
	create_leader_table('leaders');
	$leaders['id'] = 'leaders';
	$leaders['ajax_source'] = 'leader/table_by_experiment/' . $experiment->id;
	$this->load->view('templates/list_view', $leaders);
	?>
	</div>

	<!-- Participations -->
	<h3>
	<?=lang('participations') . ' (' . $nr_participations . ')'; ?>
	</h3>
	<div>
	<?php
	create_participation_table('participations');
	$participations['id'] = 'participations';
	$participations['ajax_source'] = 'participation/table/0/' . $experiment->id;
	$this->load->view('templates/list_view', $participations);
	?>
	</div>

	<!-- Relations -->
	<h3>
	<?=lang('relations'); ?>
	</h3>
	<div>
	<?php
	create_relation_table('relations');
	$relations['id'] = 'relations';
	$relations['ajax_source'] = 'relation/table_by_experiment/' . $experiment->id;
	$this->load->view('templates/list_view', $relations);
		?>
	</div>
	
	<!-- Scores -->
	<h3>
	<?=lang('scores'); ?>
	</h3>
	<div>
	<?=anchor('experiment/download_scores/' . $experiment->id . '/ncdi_wz', 'Download NCDI scores'); ?>
	</div>
</div>
