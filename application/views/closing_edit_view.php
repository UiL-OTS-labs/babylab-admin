<!-- Form for adding closings -->
<script type="text/javascript" src="js/datetime_range.js"></script>

<script type="text/javascript">
$(function() {
    $( "#date" ).datepicker({
        changeMonth : true,
        changeYear : true,
        showOn : 'both',
        buttonImage : 'images/calendar.png',
        buttonImageOnly : true,
        buttonText : 'Pick a date',
        minDate : new Date(),
        onClose: function( selectedDate ) {
            $( "#date" ).datetimepicker( );
        }
    });

	$( "input[name='all_day']" ).change(function() {
		$( "#times").toggle($( "input[name='all_day']:checked" ).val() != "1");
		$( "#all_day_field").toggle($( "input[name='all_day']:checked" ).val() == "1");
	});
	$( "input[name='all_day']" ).change();

	$( "input[name='lockdown']" ).change(function() {
		$( "#location_field").toggle($( "input[name='lockdown']:checked" ).val() != "1");
	});
	$( "input[name='lockdown']" ).change();
});
</script>

<?=heading($page_title, 2); ?>

<?=$this->session->flashdata('message'); ?>
<?=form_open($action, array('class' => 'pure-form pure-form-aligned')); ?>
<?=form_fieldset(lang('add_closing')); ?>
<?=form_single_checkbox_and_label('lockdown', '1', $lockdown); ?>

<div id="location_field">
	<?=form_multiselect_and_label('location', $locations, $current_location_id, 'class="chosen-select"'); ?>
</div>

<?=form_single_checkbox_and_label('all_day', '1', $all_day); ?>
<div id="times">
	<?=form_input_and_label('from_date', $from, 'id="from" readonly'); ?>
	<?=form_input_and_label('to_date', $to, 'id="to" readonly'); ?>
</div>
<div id="all_day_field">
	<?=form_input_and_label('date', $date, 'id="date" readonly');?>
</div>

<?=form_textarea_and_label('comment', $comment); ?>

<?=form_submit_only(); ?>
<?=form_fieldset_close(); ?>
<?=form_close(); ?>
