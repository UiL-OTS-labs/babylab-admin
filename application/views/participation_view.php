<?=heading($page_title, 2); ?>

<?=$this->session->flashdata('message'); ?>

<?php if ($participation->interrupted) { ?>
<p class="warning">
<?=lang('part_interrupted'); ?>
</p>
<?php } ?>

<!-- Participation info -->
<div>
	<table class="pure-table">
		<tr>
			<th><?=lang('participant'); ?></th>
			<td><?=participant_get_link($participant); ?></td>
		</tr>
		<tr>
			<th><?=lang('experiment'); ?></th>
			<td><?=experiment_get_link($experiment); ?></td>
		</tr>
		<tr>
			<th><?=lang('risk_group'); ?></th>
			<td><?=$participation->risk ? lang('yes') : lang('no'); ?></td>
		</tr>
		<tr>
			<th><?=lang('last_called'); ?></th>
			<td><?=output_datetime($participation->lastcalled); ?></td>
		</tr>
		<tr>
			<th><?=lang('status'); ?></th>
			<td><?=lang($participation->status); ?></td>
		</tr>
		<tr>
			<th><?=lang('comment'); ?></th>
			<td><?=$participation->comment; ?></td>
		</tr>
		<?php if (!empty($participation->appointment)) { ?>
		<tr>
			<th><?=lang('appointment'); ?></th>
			<td><?=output_datetime($participation->appointment); ?></td>
		</tr>
		<tr>
			<th><?=lang('age'); ?></th>
			<td><?=age_in_months_and_days($participant, $participation->appointment); ?>
			</td>
		</tr>
		<?php } ?>
		<?php if (!empty($participation->completed)) { ?>
		<tr>
			<th><?=lang('part_number'); ?></th>
			<td><?=$participation->part_number; ?></td>
		</tr>
		<?php } ?>
	</table>
</div>

<!-- Calls -->
<h3>
<?=lang('calls'); ?>
</h3>
<div>
<?php
create_call_table('calls');
$calls['id'] = 'calls';
$calls['sort_column'] = 5;
$calls['ajax_source'] = 'call/table_by_participation/' . $participation->id;
echo $this->load->view('templates/list_view', $calls);
?>
</div>

<!-- Results -->
<h3>
<?=lang('results'); ?>
</h3>
<div>
<?php
create_result_table('results');
$results['id'] = 'results';
$results['ajax_source'] = 'result/table_by_participation/' . $participation->id;
		echo $this->load->view('templates/list_view', $results);
	?>
</div>
