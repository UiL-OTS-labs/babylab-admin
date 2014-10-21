<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.12/themes/dot-luv/jquery-ui.css" type="text/css" rel="stylesheet"></link>


<style>
div.timeslot, {
	margin-top: 30px;
}

#schedule-submit {
	font-size: 1em !important;
}

.timeslot-picker th{
	font-weight: normal;
	background-color: #474747;
	color: #ffffff;
	border: 0px solid #000;
}

.timeslot-picker .timepicker-header{
	width: 20px;
	border-bottom: 5px solid white;
	padding: 5px 0px 5px 0px;
}

.timeslot-picker .time-picker-row-header{
	height: 20px;
	border-right: 5px solid white;
	padding: 0px 5px 0px 5px;
}


.timeslot-picker td{
	border: 1px solid white !important;
	padding: 0px;
	margin: 0px;
	background-color: #e0e0e0;
}

td.ui-custom-selected {
	background-color: #0b3e6f;
}

td.ui-custom-selecting{
	background-color: #1b48e4;
}

td.ui-custom-unselecting{
	background-color: #efefef;
}




</style>

<?=heading($page_title, 2); ?>

<?=form_open('availability/add_submit', array('class' => 'pure-form')); ?>
<form class="pure-form pure-form-aligned">
	<fieldset>
		<legend>TODO: datum & tijd</legend>
			<div class="pure-g time-selector">
				<div id="datum" class="pure-u-6-24"></div>
				<div id="timeselector" class="pure-u-2-5"></div>

			</div>
			<div id="clickme">Basically. Run</div>
				

		<legend>TODO: schedule</legend>
		<table class="pure-table" id="schedule">
			<thead>
				<th><?=lang('date'); ?> </th>
				<th><?=lang('from_date'); ?></th>
				<th><?=lang('to_date'); ?></th>
				<th><?=lang('comment'); ?></th>
				<th><?=lang('action'); ?></th>
			</thead>
			<tbody>
			</tbody>
		</table>

		<?=form_submit_only(); ?>
		<?br().br().br().br().br();?>
		<legend>Testing area</legend>
		<div id="json_arrays"></div>
	</fieldset>
<?=form_close(); ?>

<script type="text/javascript" src="js/jquery.timerangeselector.js"></script>
<script> 

var counter = 0;

$("#timeselector").selectorTable({
	resolution: 5,
	tableClass: 'pure-table',
	selected: [
		["08:30", "18:00"],
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

$("#clickme").click(function(){
	var times = $("#timeselector").selectorTable('getTimes');

	$.each(times, function(key, value)
	{
		var datum = $.datepicker.formatDate("yy-mm-dd", $("#datum").datepicker("getDate"));

		var counter2 = 0;

		$("#schedule tbody tr").each(function(k, d){
			var children = $(d).children().children();
			if ($(children[0]).val() == datum)
			{
				// WERKT NOG NIET MET AM's!!!!
				if (convertTime($(children[1]).val()) <= convertTime(value[0].toString()) && 
					convertTime(value[0].toString()) <= convertTime($(children[2]).val()))
				{
					alert("Begint in range");
				}
				/*alert("Child 1: " + $(children[1]).val() + "\n" +
					"Value: " + value[0] + "\n" + 
					"Child 2: " + $(children[2]).val());*/
			}
		});


		var row = "<tr><td><input readonly type='text' value='" + datum + "' name='value[" + counter + "][date]' /></td>";
		row += "<td><input readonly type='text' value='" + value[0].toString() + "' name='value[" + counter + "][time_from]'</td>";
		row += "<td><input readonly type='text' value='" + value[1] + "' name='value[" + counter + "][time_to]'</td>";
		row += "<td><input type='text' name='value[" + counter + "][comment]' /></td>";
		row += '<td onClick="deleteRow(this)"><?=img_delete(); ?></td></tr>';

		$("#schedule tbody").append(row);
		counter++;
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

</script>