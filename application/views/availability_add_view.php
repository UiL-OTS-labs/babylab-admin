<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.12/themes/dot-luv/jquery-ui.css" type="text/css" rel="stylesheet"></link>
<script src="js/jquery.ui.timeslider.js"> </script>

<style>
div.timeslot, {
	margin-top: 30px;
}

.timeslot #slider, div.time-selector, #slider, #datetime_show, #schedule{
	margin-bottom: 30px;
}

#schedule-submit {
	font-size: 1em !important;
}

</style>

<?=heading($page_title, 2); ?>

<?=form_open('availability/add_submit', array('class' => 'pure-form')); ?>
<form class="pure-form pure-form-aligned">
	<fieldset>
		<legend>TODO: datum & tijd</legend>
			<div class="pure-g time-selector">
				<div id="datum" class="pure-u-6-24"></div>
				<div class="timeslot pure-u-2-5">
					<div id="slider" ></div>
					<div id="datetime_show"><span id="date_show"></span>&nbsp;<span id="time_show"></span></div>
	
					<input type="button" id="schedule-submit" class="ui-state-default" value="TODO: toevoegen" />
				</div>
			</div>

		<legend>TODO: schedule</legend>
		<table class="pure-table" id="schedule">
			<thead>
				<th><?=lang('date'); ?> </th>
				<th><?=lang('from_date'); ?></th>
				<th><?=lang('to_date'); ?></th>
				<th><?=lang('comment'); ?></th>
				<th><?=lang('action'); ?></th>
			<thead>
			<tbody>
			</tbody>
		</table>

		<?=form_controls(); ?>
	</fieldset>
<?=form_close(); ?>

    


<script>
	// Makes datepicker and sets format
	$("#datum").datepicker({ 
		dateFormat: "dd-mm-yy",
		onSelect: function(){
			$("#date_show").html($(this).val());
		}
	});
	
	$("#date_show").html($("#datum").val());

	var counter = 0;

	// Creates the timeslider with all kinds of fancy stuff
	$("#slider").timeslider({
		sliderOptions: {
			range: true,
			min: 360,
			max: 1380,
			values: [480, 1020], 
			step:15,
		},
		
		clockFormat: <?= (current_language() == 'dutch') ? '24' : '12'; ?>,
		timeDisplay: '#time_show',
		submitButton: '#schedule-submit',
		clickSubmit: function (e){
			var that = $(this).siblings('#slider');

			$('#schedule tbody').append('<tr>' +
			'<td><input name="value[' + counter + '][date]" type="text" readonly="" value="' + $('#datum').val() + '" /></td>' +
			'<td><input name="value[' + counter + '][time_from]" type="text" readonly="" value="' + that.timeslider('getTime', that.slider("values", 0)) + '" /></td>' + 
			'<td><input name="value[' + counter + '][time_to]" type="text" readonly="" value="' + that.timeslider('getTime', that.slider("values", 1)) + '" /></td>' +
			'<td><input name="value[' + counter + '][comment]" type="text" /></td>' + 
			'<td onClick="deleteRow(this)"><?=img_delete(); ?>  </td>' + 
			'</tr>');
			counter++;
			e.preventDefault(); 
		}
	});

	function deleteRow(row){
		//alert("Row index is " + row.parentNode.rowIndex);
		$("#schedule tr:eq(" + row.parentNode.rowIndex + ")").remove();
	};

	
	
</script>