<script type="text/javascript">
$(function() {	
	//Appointment scheduling
	$( "#appointment" ).datetimepicker({
		changeMonth : true,
		changeYear : true,
		showOn : 'both',
		buttonImage : 'images/calendar.png',
		buttonImageOnly : true,
		buttonText : 'Pick a date and time',
	});
	
	$( "#appointment" ).attr({
		readOnly : true
	});
});
</script>

<?=heading(lang('participation'), 2); ?>

<p class="warning"><?=lang('participation_no_restrictions')?></p> 
<?=$this->session->flashdata('message'); ?>

<?=form_open($action, array('class' => 'pure-form pure-form-aligned')); ?>

<?=form_fieldset($page_title); ?>
<?=form_dropdown_and_label('experiment', $experiments, array(), 'class="chosen-select"'); ?>
<?=form_dropdown_and_label('participant', $participants, array(), 'class="chosen-select"'); ?>
<?=form_input_and_label('appointment', '', 'placeholder= "' . lang('appointment') . '" id="appointment"'); ?>
<?=form_controls(); ?>
<?=form_fieldset_close(); ?>
<?=form_close(); ?>
