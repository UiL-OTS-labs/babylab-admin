<?=heading($page_title, 2); ?>

<div>
	<!-- General info -->
	<?=heading(lang('testcat'), 3); ?>
	<table class="pure-table">
		<tr>
			<th><?=lang('name'); ?></th>
			<td><?=$testcat->name; ?></td>
		</tr>
		<tr>
			<th><?=lang('code'); ?></th>
			<td><?=$testcat->code; ?></td>
		</tr>
		<tr>
			<th><?=lang('test'); ?></th>
			<td><?=test_get_link($test) ?></td>
		</tr>
		<?php if (!$is_parent) { ?>
			<tr>
				<th><?=lang('boxplot'); ?></th>
				<td><?=testcat_score_boxplot($testcat->id); ?></td>
			</tr>
			<tr>
				<th><?=lang('parent_testcat'); ?></th>
				<td><?=testcat_get_link($parent_testcat); ?></td>
			</tr>
		<?php } ?>
	</table>

	<!-- Scores -->
	<?=heading(lang('scores'), 3); ?>
	<div>
		<?php
			if ($is_parent)
			{
				$scores['id'] = 'scores';
				$scores['table'] = create_total_score_table(array($testcat), $testinvites); // FIXME!
				$this->load->view('templates/table_view', $scores);
			}
			else
			{
				create_score_table('scores', 'testcat');
				$scores['id'] = 'scores';
				$scores['ajax_source'] = 'score/table_by_testcat/' . $testcat->id;
				$this->load->view('templates/list_view', $scores);
			}
		?>
	</div>

	<!-- Child categories -->
	<?php if ($is_parent) { ?>
		<?=heading(lang('testcats'), 3); ?>
		<div>
			<?php
				create_testcat_table('testcats');
				$testcats['id'] = 'testcats';
				$testcats['ajax_source'] = 'testcat/table_children/' . $testcat->id;
				$this->load->view('templates/list_view', $testcats);
			?>
		</div>

		<!-- Percentiles -->
		<?=heading(lang('percentiles'), 3); ?>
		<div>
			<?php
				create_percentile_table('percentiles');
				$percentiles['id'] = 'percentiles';
				$percentiles['ajax_source'] = 'percentile/table_by_testcat/' . $testcat->id;
				$this->load->view('templates/list_view', $percentiles);
			?>
		</div>
	<?php } ?>
</div>
