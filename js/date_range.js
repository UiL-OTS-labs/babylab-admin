// From: http://jqueryui.com/datepicker/#date-range
$(function() {
	$( "#from" ).datepicker({
		changeMonth : true,
		changeYear : true,
		showOn : 'both',
		buttonImage : 'images/calendar.png',
		buttonImageOnly : true,
		buttonText : 'Pick a date',
		onClose: function( selectedDate ) {
			$( "#to" ).datepicker( "option", "minDate", selectedDate );
		}
	});
	$( "#to" ).datepicker({
		changeMonth : true,
		changeYear : true,
		showOn : 'both',
		buttonImage : 'images/calendar.png',
		buttonImageOnly : true,
		buttonText : 'Pick a date',
		onClose: function( selectedDate ) {
			$( "#from" ).datepicker( "option", "maxDate", selectedDate );
		}
	});
});