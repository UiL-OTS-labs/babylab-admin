<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.12/themes/dot-luv/jquery-ui.css" type="text/css" rel="stylesheet"></link>
<script src="js/jquery.ui.timeslider.js"> </script>

<style>
div.timeslot, {
	margin-top: 30px;
}

.timeslot #slider2, div.time-selector, #slider2, #time2{
	margin-bottom: 30px;
}

</style>

<?=heading($page_title, 2); ?>

<form class="pure-form pure-form-aligned" method="post" action="#">
	<fieldset>
		<legend>TODO: datum & tijd</legend>
			<div class="pure-g time-selector">
				<div id="datum" class="pure-u-6-24"></div>
				<div class="timeslot pure-u-2-5">
					<div id="slider2" ></div>
					<div id="time2"></div>
					<input type="submit" id="schedule-submit2" class="ui-state-default" value="TODO: toevoegen" /> <br/>
				</div>
			</div>

		<legend>TODO: schedule</legend>
		<table class="pure-table" id="schedule2">
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
	</fieldset>
</form>

    


<script>
	$("#slider2").timeslider({
		sliderOptions: {
			range: true,
			min: 360,
			max: 1380,
			values: [480, 1020], 
			step:15,
		},
		
		clockFormat: <?= (current_language() == 'dutch') ? '24' : '12'; ?>,
		timeDisplay: '#time2',
		submitButton: '#schedule-submit2',
		clickSubmit: function (e){
			var that = $(this).siblings('#slider2');

			$('#schedule2 tbody').append('<tr>' +
			'<td><input type="text" readonly="" value="' + $('#datum').val() + '" /></td>' +
			'<td><input type="text" readonly="" value="' + that.timeslider('getTime', that.slider("values", 0)) + '" /></td>' + 
			'<td><input type="text" readonly="" value="' + that.timeslider('getTime', that.slider("values", 1)) + '" /></td>' +
			'<td><input type="text" /></td>' + 
			'<td> TODO: remove button </td>' + 
			'</tr>');
			e.preventDefault(); 
		}
	});

	$("#datum").datepicker();
	
</script>