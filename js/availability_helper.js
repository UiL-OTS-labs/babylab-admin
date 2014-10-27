var counter = 0;

$("#timeselector").selectorTable({
	resolution: 5,
	tableClass: 'pure-table',
	selected: [
		preselectedTimes,
	],
	hourFormat24: false,
	hourText: 'uur',
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

$("#resetTimeRange").click(function(){
	$("#timeselector").selectorTable('reset');
});

$("#clickme").click(function(){
	var times = $("#timeselector").selectorTable('getTimes');

	$.each(times, function(key, value)
	{
		var datum = $.datepicker.formatDate("yy-mm-dd", $("#datum").datepicker("getDate"));

		var add = true;

		$("#schedule tbody tr").each(function(k, d){
			var children = $(d).children().children();
			if ($(children[0]).val() == datum)
			{
				
				
				if ( 
						(parseInt(compareTime(value[0], $(children[1]).val() )) < 1) &&
						(parseInt(compareTime(value[1], $(children[1]).val() )) >= 0) 
					)
				{
					// Do something
					$(children[1]).val(value[0]);
					add = false;
				}

				if (
						(parseInt(compareTime(value[0], $(children[2]).val() )) < 1) &&
						(parseInt(compareTime(value[1], $(children[2]).val() )) >= 0)
					)
				{
					// Do something
					$(children[2]).val(value[1]);
					add = false;
				}

			}
		});

		if (add){
			var row = "<tr><td><input readonly type='text' value='" + datum + "' name='value[" + counter + "][date]' /></td>";
			row += "<td><input readonly type='text' value='" + value[0] + "' name='value[" + counter + "][time_from]'</td>";
			row += "<td><input readonly type='text' value='" + value[1] + "' name='value[" + counter + "][time_to]'</td>";
			row += "<td><input type='text' name='value[" + counter + "][comment]' /></td>";
			row += '<td onClick="deleteRow(this)">' + img + '</td></tr>';
			
			$("#schedule tbody").append(row);
			counter++;
		}
		
	});	
});

function deleteRow(row){
	//alert("Row index is " + row.parentNode.rowIndex);
	$("#schedule tr:eq(" + row.parentNode.rowIndex + ")").remove();
};


function convertTime(time)
{
    var time = time.split(":");
    var minute = time[1].split(" ");
    
    if (minute.length > 1)
    {
        if (minute[1] == "pm")
        {
            time[0] = parseInt(time[0]) + 12;
        }
    }
    
    return time[0] + ":" + minute[0];
}

function compareTime(time1, time2)
{
	time1 = convertTime(time1).split(":");
	var hours1 = parseInt(time1[0]);
	var minutes1 = parseInt(time1[1]);

	time2 = convertTime(time2).split(":");
	var hours2 = parseInt(time2[0]);
	var minutes2 = parseInt(time2[1]);

	var result;

	if (hours1 > hours2)
	{
		result = 1;
	} else if (hours2 > hours1)
	{
		result = -1;
	} else {
		if (minutes1 > minutes2)
		{
			result = 1;
		}
		else if (minutes2 > minutes1)
		{
			result = -1;
		}
		else{
			result = 0;
		}
	}
	return parseInt(result);
}