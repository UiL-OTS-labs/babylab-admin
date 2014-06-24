$(function() {
	$( "#appointment" ).datetimepicker({
		changeMonth : true,
		changeYear : true,
		minDate : 'today',
		showOn : 'both',
		buttonImage : 'images/calendar.png',
		buttonImageOnly : true,
		buttonText : 'Pick a date and time'
	});
	
	$( "#appointment" ).attr({
		readOnly : true
	});
});
