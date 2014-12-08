// From: http://jqueryui.com/datepicker/#date-range
$(function() {
    $( "#from" ).datetimepicker({
        changeMonth : true,
        changeYear : true,
        showOn : 'both',
        buttonImage : 'images/calendar.png',
        buttonImageOnly : true,
        buttonText : 'Pick a date',
        onClose: function( selectedDate ) {
            $( "#to" ).datetimepicker( "option", "minDate", selectedDate );
        }
    });
    $( "#to" ).datetimepicker({
        changeMonth : true,
        changeYear : true,
        showOn : 'both',
        buttonImage : 'images/calendar.png',
        buttonImageOnly : true,
        buttonText : 'Pick a date',
        onClose: function( selectedDate ) {
            $( "#from" ).datetimepicker( "option", "maxDate", selectedDate );
        }
    });
});