<script>
	$(function() {
		$( "#accordion" ).accordion({
			collapsible: true,
			heightStyle: "content"
		});
	});
</script>

<script src="https://www.gstatic.com/charts/loader.js"></script>
<script>
	google.charts.load('current', {'packages':['corechart']});
	google.charts.setOnLoadCallback(drawChart);

	function drawChart() {
		var data = google.visualization.arrayToDataTable([
          ['Type', 'Amount'],
          ["<?=lang('tested'); ?>", <?=$nr_included; ?>],
          ["<?=lang('to_test'); ?>", <?=max($experiment->target_nr_participants - $nr_included, 0); ?>],
		]);

		var options = {
			title: "<?=lang('act_nr_part'); ?>",
		};

		var chart = new google.visualization.PieChart(document.getElementById('chart_div'));

		chart.draw(data, options);
	}
</script>

<?=heading($page_title, 2); ?>

<?=$this->session->flashdata('message'); ?>

<div id="accordion">
	<!-- General info -->
	<?=heading(lang('general_info'), 3); ?>
	<div>
		<div style="width: 50%; float: left; margin-bottom: 10px;">
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
					<td><?=sprintf(lang('nr_participations'), $nr_participations, $nr_included, $experiment->target_nr_participants); ?></td>
				</tr>
				<tr>
					<th><?=lang('wbs_number'); ?></th>
					<td><?=$experiment->wbs_number; ?></td>
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
				<tr>
					<th><?=lang('experiment_color'); ?></th>
					<td><?=stripslashes(get_colored_label($experiment));?></td>
				</tr>
			</table>
		</div>

		<?php if ($nr_participations > 0) { ?>
		<div id="chart_div" style="width: 50%; height: 300px; float: right; margin-bottom: 10px;"></div>

		<?=heading(lang('month_overview'), 2); ?>
		<table class="pure-table">
			<thead>
			<?php
				echo '<tr>';
				echo '<th style="min-width: 100px; text-align: center;">' . lang('month') . '</th>';
				foreach (array_keys(reset($tested)) as $leader) {
					echo '<th style="min-width: 50px; text-align: center;">';
					echo $leader;
					echo '</th>';
				}
				echo '</tr>';
			?>
			</thead>
			<tbody>
			<?php
				foreach ($tested as $month => $leader_counts) {
					echo '<tr>';
					echo '<td>' . $month . '</td>';
					foreach ($leader_counts as $leader => $count) {
						echo '<td style="text-align: right;">';
						echo $count;
						echo '</td>';
					}
					echo '</tr>';
				}
			?>
			</tbody>
		</table>
		<?php } ?>
	</div>

	<!-- Callers -->
	<?=heading(lang('callers'), 3); ?>
	<div>
		<?php
			create_caller_table('callers');
			$callers['id'] = 'callers';
			$callers['ajax_source'] = 'caller/table_by_experiment/' . $experiment->id;
			$this->load->view('templates/list_view', $callers);
		?>
	</div>

	<!-- Leaders -->
	<?=heading(lang('leaders'), 3); ?>
	<div>
		<?php
			create_leader_table('leaders');
			$leaders['id'] = 'leaders';
			$leaders['ajax_source'] = 'leader/table_by_experiment/' . $experiment->id;
			$this->load->view('templates/list_view', $leaders);
		?>
	</div>

	<!-- Participations -->
	<?=heading(lang('participations') . ' (' . $nr_participations . ')', 3); ?>
	<div>
		<?php
			is_leader() ? create_participation_leader_table('participations') : create_participation_table('participations');
			$participations['id'] = 'participations';
			$participations['ajax_source'] = is_leader() ? 'participation/table_by_leader/' . $experiment->id : 'participation/table/0/' . $experiment->id;
			$this->load->view('templates/list_view', $participations);
		?>
		<?=anchor('participation/download/' . $experiment->id, lang('download_participations')); ?>
	</div>

	<!-- Relations -->
	<?=heading(lang('relations'), 3); ?>
	<div>
		<?php
			create_relation_table('relations');
			$relations['id'] = 'relations';
			$relations['ajax_source'] = 'relation/table_by_experiment/' . $experiment->id;
			$this->load->view('templates/list_view', $relations);
		?>
	</div>
	
	<!-- Scores -->
	<?=heading(lang('scores'), 3); ?>
	<div>
		<?php
			create_testinvite_experiment_table('testinvites');
			$testinvites['id'] = 'testinvites';
			$testinvites['ajax_source'] = 'testinvite/table_by_experiment/' . $experiment->id;
			$this->load->view('templates/list_view', $testinvites);
		?>
		<ul>
			<li><?=anchor('experiment/download_scores/' . $experiment->id . '/anamnese', sprintf(lang('download_results'), 'Anamnese')); ?></li>
			<li><?=anchor('experiment/download_scores/' . $experiment->id . '/ncdi_wz', sprintf(lang('download_results'), 'N-CDI')); ?></li>
			<?php if ($experiment->dyslexic) { ?>
				<li><?=anchor('experiment/download_dyslexia/' . $experiment->id, lang('download_dyslexia')); ?></li>
			<?php } ?>
		</ul>
	</div>
</div>
