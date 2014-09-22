<link rel="stylesheet" href="css/chosen.css" />
<script type="text/javascript" src="js/chosen.jquery.min.js"></script>
<script type="text/javascript" src="js/jquery.ui.timepicker-addon.js"></script>
<script type="text/javascript">
$(function() {
	$(".chosen-select").chosen();
	
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
<?=form_dropdown_and_label('experiment', $experiments, array(), 'class="chosen-select" style="width: 350px;"'); ?>
<?=form_dropdown_and_label('participant', $participants, array(), 'class="chosen-select" style="width: 350px;"'); ?>
<?=form_input_and_label('appointment', '', 'placeholder= "' . lang('appointment') . '" id="appointment"'); ?>
<?=form_controls(); ?>
<?=form_fieldset_close(); ?>
<?=form_close(); ?>
