var counter = 0;

$("#timeselector").selectorTable({
	resolution: 5,
	tableClass: 'pure-table',
	selected: [
		preselectedTimes,
	],
	hourFormat24: hourFormat,
	hourText: hourSuffix,
	hourStart: 1,
	hourEnd: 24,
}); 


$( "#datum" ).datepicker({
	changeMonth : true,
	changeYear : true,
	showOn : 'both',
	buttonImage : 'images/calendar.png',
	buttonImageOnly : true,
	buttonText : 'Pick a date',
	altFormat : "yy-mm-dd"
});

$('#daterange').click(function(){
	if ($(this).prop('checked'))
	{
		$('#datum-eind').datepicker({
			changeMonth : true,
			changeYear : true,
			showOn : 'both',
			buttonImage : 'images/calendar.png',
			buttonImageOnly : true,
			buttonText : 'Pick a date',
			altFormat : "yy-mm-dd"
		})
	} else {
		$('#datum-eind').datepicker('destroy');
	}
});

$("#resetTimeRange").click(function(){
	$("#timeselector").selectorTable('reset');
});


$("#daterange").attr('checked', false);

$("#clickme").click(function(){

	var datum = moment($("#datum").datepicker("getDate"));

	var dateEnd;

	if ($('#daterange').prop('checked'))
	{
		dateEnd = moment($("#datum-eind").datepicker("getDate"));
		if (dateEnd < datum)
		{
			var dd = dateEnd.clone();
			dateEnd = datum.clone();
			datum = dd.clone();
		} 
	} else {
		dateEnd = datum.clone();
	}

	var times = $("#timeselector").selectorTable('getTimes');


	while(datum <= dateEnd)
	{
		$.each(times, function(key, value)
		{

			$("#schedule tbody tr").each(function(k, d){
				var children = $(d).children().children();
				if ($(children[0]).val() == datum.format("YYYY-MM-DD"))
				{
					$(d).remove();
				}
			});
		});	

		$.each(times, function(key, value)
		{	

			var row = "<tr><td><input readonly type='text' value='" + datum.format("YYYY-MM-DD") + "' name='value[" + counter + "][date]' /></td>";
			row += "<td><input readonly type='text' value='" + value[0].format(dateTimeFormat) + "' name='value[" + counter + "][time_from]'</td>";
			row += "<td><input readonly type='text' value='" + value[1].format(dateTimeFormat) + "' name='value[" + counter + "][time_to]'</td>";
			row += "<td><input type='text' name='value[" + counter + "][comment]' /></td>";
			row += '<td onClick="deleteRow(this)">' + img + '</td></tr>';
			
			$("#schedule tbody").append(row);
			counter++;
		});	

		datum.add(1, 'day');
	}

});

function deleteRow(row){
	$("#schedule tr:eq(" + row.parentNode.rowIndex + ")").remove();
};