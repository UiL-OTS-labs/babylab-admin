<script type="text/javascript" src="js/testcats_filter.js"></script>

<?=heading(lang('testcats'), 2); ?>

<?=$this->session->flashdata('message'); ?>

<?=form_open($action, array('class' => 'pure-form pure-form-aligned')); ?>
<?=form_fieldset($page_title); ?>

<?php
if ($new_testcat) {
	echo form_dropdown_and_label('test', test_options($tests), $test_id);
	if (!empty($testcat_id))
	{
		echo form_dropdown_and_label('parent_testcat', testcat_options($testcats), $testcat_id);
	}
	else
	{
		echo form_dropdown_and_label('parent_testcat', array(), array(), 'disabled');
	}
}
else {
	echo form_input_and_label('test', $test->name, 'readonly');
	if (!empty($parent_testcat))
	{
		echo form_input_and_label('parent_testcat', $parent_testcat->name, 'readonly');
	}
}
?>
<?=form_input_and_label('code', $code, 'required'); ?>
<?=form_input_and_label('name', $name, 'required'); ?>

<?=form_controls(); ?>
<?=form_fieldset_close(); ?>
<?=form_close(); ?>
<?=validation_errors(); ?>
