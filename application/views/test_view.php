<?=heading($page_title, 2); ?>

<?=$this->session->flashdata('message'); ?>

<div>
	<!-- General info -->
	<h3><?=lang('test'); ?></h3>
	<table class="pure-table">
		<tr><th><?=lang('code'); ?></th><td><?=$test->code; ?></td></tr>
		<tr><th><?=lang('name'); ?></th><td><?=$test->name; ?></td></tr>
	</table> 
	
	<!-- Testcats -->
	<h3><?=lang('testcats'); ?></h3>
	<div>
		<?php
			create_testcat_table('testcats');
			$testcats['id'] = 'testcats';
			$testcats['ajax_source'] = 'testcat/table_roots/' . $test->id;
			$this->load->view('templates/list_view', $testcats); 
		?>
	</div>
	
	<!-- Testsurveys -->
	<h3><?=lang('testsurveys'); ?></h3>
	<div>
		<?php
			create_testsurvey_table('testsurveys');
			$testsurveys['id'] = 'testsurveys';
			$testsurveys['ajax_source'] = 'testsurvey/table_by_test/' . $test->id;
			$this->load->view('templates/list_view', $testsurveys);
		?>
	</div>
	
	<!-- Testinvites -->
	<h3><?=lang('testinvites'); ?></h3>
	<div>
		<?php
			create_testinvite_table('testinvites');
			$testinvites['id'] = 'testinvites';
			$testinvites['ajax_source'] = 'testinvite/table_by_test/' . $test->id;
			$this->load->view('templates/list_view', $testinvites);
		?>
	</div>
</div>