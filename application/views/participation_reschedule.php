<script>
	$(function() {
		$( "#appointment" ).datetimepicker({
			changeMonth : true,
			changeYear : true,
			minDate : '<?=$min_date_js; ?>',
			maxDate : '<?=$max_date_js; ?>',
			showOn : 'both',
			buttonImage : 'images/calendar.png',
			buttonImageOnly : true,
			buttonText : 'Pick a date and time'
		});
		
		$( "#appointment" ).attr({
			readOnly : true
		});
	});
</script>

<?=heading(lang('participations'), 2); ?>

<?=$this->session->flashdata('message'); ?>

<?=form_open('participation/reschedule_submit/' . $participation->id, array('class' => 'pure-form')); ?>

<?=form_fieldset(lang('reschedule')); ?>
<p>
<?=sprintf(lang('reschedule_info'), name($participant), $experiment->name, output_datetime($participation->appointment)); ?>
</p>
<p>
<?=sprintf(lang('call_dates'), name($participant), $min_date, $max_date); ?>
</p>
<?=form_input('appointment', $appointment, 'id="appointment"'); ?>

<?=form_submit_only(); ?>
<?=form_cancel('participation/experiment/' . $experiment->id); ?>
<?=form_fieldset_close(); ?>
<?=form_close(); ?>
<?=validation_errors(); ?>