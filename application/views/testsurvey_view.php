<?=heading($page_title, 2); ?>

<?=$this->session->flashdata('message'); ?>

<div>
	<!-- General info -->
	<?=heading(lang('general_info'), 3); ?>
	<table class="pure-table">
		<tr>
			<th><?=lang('test'); ?></th>
			<td><?=test_get_link($test); ?></td>
		</tr>
		<tr>
			<th><?=lang('limesurvey_id'); ?></th>
			<td><?=survey_by_id($testsurvey->limesurvey_id); ?></td>
		</tr>
		<tr>
			<th><?=lang('whensent'); ?></th>
			<td><?=testsurvey_when($testsurvey->whensent, $testsurvey->whennr); ?>
			</td>
		</tr>
	</table>

	<!-- Scores -->
	<?=heading(lang('scores'), 3); ?>
	<?php if ($test->code == 'ncdi_wz') {
		echo anchor('testsurvey/download_scores/' . $testsurvey->id, 'Download alle scores');
	}
	?>
	<div>
		<?php
			create_score_table('scores', 'testsurvey');
			$scores['id'] = 'scores';
			$scores['ajax_source'] = 'score/table_by_testsurvey/' . $testsurvey->id;
			$this->load->view('templates/list_view', $scores);
		?>
	</div>

	<!-- Testinvites -->
	<?=heading(lang('testinvites'), 3); ?>
	<div>
		<?php
			create_testinvite_table('testinvites');
			$testinvites['id'] = 'testinvites';
			$testinvites['ajax_source'] = 'testinvite/table_by_testsurvey/' . $testsurvey->id;
			$this->load->view('templates/list_view', $testinvites);
		?>
	</div>
</div>
