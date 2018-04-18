<script>
$(function() {
	$("input[name='whensent']").change(function() {
		var manual = $("input[name='whensent']:checked").val() === "manual";
		$("#whennr").prop('disabled', manual);
	});

	$("input[name='whensent']").change();
});
</script>

<?=heading(lang('testsurveys'), 2); ?>

<?=$this->session->flashdata('message'); ?>

<?=form_open($action, array('class' => 'pure-form pure-form-aligned')); ?>
<?=form_fieldset($page_title); ?>

<?php
if ($new_testsurvey)
{
	echo form_dropdown_and_label('test', test_options($tests), $test_id);
}
else
{
	echo form_input_and_label('test', $test->name, 'readonly');
}
?>
<?=form_input_and_label('limesurvey_id', $limesurvey_id, 'required class="positive-integer"'); ?>
<div class="pure-control-group">
<?=form_label(lang('whensent'), 'whensent'); ?>
<?=form_radio_and_label('whensent', TestWhenSent::PARTICIPATION, $whensent, lcfirst(lang(TestWhenSent::PARTICIPATION))); ?>
<?=form_radio_and_label('whensent', TestWhenSent::MONTHS, $whensent, lang(TestWhenSent::MONTHS)); ?>
<?=form_radio_and_label('whensent', TestWhenSent::MANUAL, $whensent, lang(TestWhenSent::MANUAL)); ?>
</div>
<?=form_input_and_label('whennr', $whennr, 'required class="positive-integer"'); ?>
<?=form_textarea_and_label('description', $description, lang('survey_description')); ?>

<?=form_controls(); ?>
<?=form_fieldset_close(); ?>
<?=form_close(); ?>
