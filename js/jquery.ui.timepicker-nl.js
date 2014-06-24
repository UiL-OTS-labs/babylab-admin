/* Dutch (UTF-8) initialisation for the jQuery UI time picker plugin. */
/* Written by Martijn van der Klis */
jQuery(function($) {
	$.timepicker.regional.nl = {
		timeOnlyTitle : 'Kies tijd',
		timeText : 'Tijd',
		hourText : 'Uur',
		minuteText : 'Minuut',
		secondText : 'Seconde',
		millisecText : 'Milliseconde',
		timezoneText : 'Tijdzone',
		currentText : 'Nu',
		closeText : 'Klaar',
		timeFormat : 'HH:mm',
		amNames : [ 'AM', 'A' ],
		pmNames : [ 'PM', 'P' ],
		isRTL : false
	};
	$.timepicker.setDefaults($.timepicker.regional.nl);
});