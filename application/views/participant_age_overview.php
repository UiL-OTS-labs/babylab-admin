<script>
$(function() {
    $('#datepicker').datepicker({
        dateFormat : 'dd-mm-yy',
        changeMonth : true,
        changeYear : true,
        showOn : 'both',
        buttonImage : 'images/calendar.png',
        buttonImageOnly : true,
        buttonText : 'Pick a date'
    });

    $('#datepicker').change(function() {
        $('form').submit();
    });
});
</script>
<?=form_open('participant/age_overview/', array('class' => 'pure-form')); ?>
<?=form_label('Bekijk het overzicht op datum: '); ?>
<?=form_input('date', $date, 'required id="datepicker"'); ?>
<?=form_close(); ?>
