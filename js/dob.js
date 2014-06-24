$(function() {
	$("#birth_datepicker").datepicker({
		dateFormat : 'dd-mm-yy',
		changeMonth : true,
		changeYear : true,
		yearRange : '-5y:c+nn',
		maxDate : 'today',
		showOn : 'both',
		buttonImage : 'images/calendar.png',
		buttonImageOnly : true,
		buttonText : 'Pick a date'
	});
});