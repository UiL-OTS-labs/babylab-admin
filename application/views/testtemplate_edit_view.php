<?=heading(lang('testtemplates'), 2); ?>

<?=$this->session->flashdata('message'); ?>

<?=form_open($action, array('class' => 'pure-form pure-form-aligned')); ?>
<?=form_fieldset($page_title); ?>

<?php
if ($new_testtemplate)
{
	echo form_dropdown_and_label('test', test_options($tests), $test_id);
}
else
{
	echo form_input_and_label('test', $test->name, 'readonly');
}
?>
<?=form_input_and_label('template', $template, 'required'); ?>

<?=form_controls(); ?>
<?=form_fieldset_close(); ?>
<?=form_close(); ?>
