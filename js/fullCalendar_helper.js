/**
 * Function to generate the calendar.
 * All kinds of settings, which is nice.
 */
function initializeCalendar(lang) {
	$('#calendar').fullCalendar({
		events: {
			url: 'appointment/events/',
			type: 'POST',
			data: function() { // a function that returns an object
	            return {
	            	participant_ids: $('.select-participant').val(),
	                experiment_ids: $('.select-experiment').val(),
	                location_ids: $('.select-location').val(),
	                exclude_canceled: $('#exclude-canceled').is(':checked'),
	            };
			},
		},
		lang: lang,
		header:
	    {
		    left: 'prev,today,next',
			center: 'title',
		    right: 'month,agendaWeek,agendaDay',
	    },
	    timeFormat: 'HH:mm',
		weekNumbers: {month: true, agendaWeek: true, 'default': false},
		displayEventEnd: true,
	    slotEventOverlap: false,
	    
	    // Go to the current day when day is clicked
	    dayClick: function(date, jsEvent, view) {
	    	
	    	$('#calendar').fullCalendar('changeView', 'agendaDay');
	    	$('#calendar').fullCalendar('gotoDate', date);
	    	
	    },

	    // Add qTip tooltips to events 
	    eventRender: function(event, element) {
			element.css('cursor', 'pointer');
	        element.qtip({
	            content: stripslashes(event.tooltip),
	            position: {
	                my: 'bottom left',
	                at: 'top left'
	            },
		        show: {
		            event: 'click'
		        },
		        hide: {
			        event: 'click',
			        inactive: 3000,
		        }
	        });
	        element.add(event.message);
	    },
	});
}

/**
 * Function to add the legend button to the header of the calendar
 */
function addLegendButton(legend, legendLabel) {
	// Create a new button, matching the buttons
	// in the calendar header
	var legendButton = '<span unselectable="on" id="legend"';
	legendButton += 'class="fc-button fc-state-default"';
	legendButton += 'style="-moz-user-select: none;">' + legendLabel + '</span>';
	
	// Add button to header
	$('#calendar td.fc-header-left .fc-corner-right').before(legendButton);
	
	// Add tooltip with HTML content to button
	$('#legend').qtip({
		content: legend,
		position: {
			my: 'top center',
			at: 'bottom center'
		},
		show: {
			event: 'click'
		},
		hide: {
			event: 'click'
		}
	});
}

/**
 * Function to add a button to the header of the calendar
 * that allows the user to jump to a selected date.
 * 
 * This function requires a (hidden) inputbox with id='date_picker'
 * to be placed anywhere in the document
 */
function addDateSelectTool(dateText){
	// Set up the datepicker tool
	$("#date_picker").datepicker({
		dateFormat : 'yy-mm-dd',
		changeMonth : true,
		changeYear : true,
		//yearRange : '-5y:c+nn',
		//maxDate : '-1d',
		showOn : 'both',
		buttonImageOnly : true,
		buttonText : '',
	});
	
	// Create a button that shows the datepicker tool
	var goToDateBtn = '<span unselectable="on" id="go_to_date"';
		goToDateBtn += 'class="fc-button fc-state-default"';
		goToDateBtn += 'name="date" style="-moz-user-select: none;">' + dateText + '</span>';
	
	// Add the button to the header of the calendar	
	$('#calendar td.fc-header-left .fc-corner-left').after(goToDateBtn);
	
	// Make the button do something
	$('#go_to_date').click(function(){
		$("#date_picker").datepicker("show");
	});
	
	// Reposition the datepicker tool so that it looks
	// like it is coming from the button
	$('.ui-datepicker-trigger').position({
		my: 'left top',
		at: 'right bottom',
		of: $('#go_to_date'),
	});

	// When a date is selected, go to that date.
	$('#date_picker').change(function(){
		var d = $.fullCalendar.moment($('#date_picker').val());
		$('#calendar').fullCalendar('gotoDate', d);
	});
	
}

/** 
 * Function to make multiselectboxes into filters.
 * Requires one or more multiselectboxes with
 * class 'chosen-select'.
 */
function addFilters() {
	// Make the calendar responsive to changes in the filter
	$(".chosen-select").chosen().change(function(){			
		$('#calendar').fullCalendar( 'refetchEvents' );
	});
	
	$('#exclude-canceled').change( function() {
		$('#calendar').fullCalendar( 'refetchEvents' );
	});
	
	
	  /////////////////////////////////////
	 ////////  Helper Buttons ////////////
	/////////////////////////////////////
	
	
	// Select all experiments button
	$('#all_exps').click( function(){
		$('.select-experiment option').prop('selected', true);
		$('.select-experiment').trigger("chosen:updated");
		$('#calendar').fullCalendar( 'refetchEvents' );
	});

	// Clear all experiments button
	$('#clear_exps').click( function(){
		$('.select-experiment option').prop('selected', false);
		$('.select-experiment').trigger("chosen:updated");
		$('#calendar').fullCalendar( 'refetchEvents' );
	});

	// Select all participants button
	$('#all_parts').click( function(){
		$('.select-participant option').prop('selected', true);
		$('.select-participant').trigger("chosen:updated");
		$('#calendar').fullCalendar( 'refetchEvents' );
	});

	// Clear all participants button
	$('#clear_parts').click( function(){
		$('.select-participant option').prop('selected', false);
		$('.select-participant').trigger("chosen:updated");
		$('#calendar').fullCalendar( 'refetchEvents' );
	});
	
	// Clear all filters button
	$('#clearall').click( function(){
		$('.chosen-select option').prop('selected', false);
		$('.chosen-select').trigger("chosen:updated");
		$('#calendar').fullCalendar( 'refetchEvents' );
	});
}

function stripslashes(str) {
	str=str.replace(/\\'/g,'\'');
	str=str.replace(/\\"/g,'"');
	str=str.replace(/\\0/g,'\0');
	str=str.replace(/\\\\/g,'\\');
	return str;
}