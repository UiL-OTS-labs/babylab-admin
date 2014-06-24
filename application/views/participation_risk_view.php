<?=heading($page_title, 2); ?>

<?=$this->session->flashdata('message'); ?>

<h3><?=lang('risk_group'); ?></h3>
<?php
	create_participation_table('risks');
	$risks['id'] = 'risks';
	$risks['ajax_source'] = 'participation/risks_table/1/' . $experiment_id;
	$this->load->view('templates/list_view', $risks);
?>
<h3><?=lang('control_group'); ?></h3>
<?php
	create_participation_table('controls');
	$controls['id'] = 'controls';
	$controls['ajax_source'] = 'participation/risks_table/0/' . $experiment_id;
	$this->load->view('templates/list_view', $controls);
?>