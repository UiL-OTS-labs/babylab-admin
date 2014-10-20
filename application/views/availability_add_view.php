<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.12/themes/dot-luv/jquery-ui.css" type="text/css" rel="stylesheet"></link>


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

#select-time-table input{
	visibility: hidden;
}

.timeslot td{
	border: 1px solid gray !important;
}

td.ui-custom-selected, td.ui-custom-selecting{
	background-color: green;
	border: 1px solid white !important;
}

td.ui-custom-unselecting{
	background-color: white;
	border: 1px solid gray !important;
}





</style>

<?=heading($page_title, 2); ?>

<?=form_open('availability/add_submit', array('class' => 'pure-form')); ?>
<form class="pure-form pure-form-aligned">
	<fieldset>
		<legend>TODO: datum & tijd</legend>
			<div class="pure-g time-selector">
				<div id="datum" class="pure-u-6-24"></div>
				<div id="timeselector" class="timeslot pure-u-2-5"></div>
			</div>
				<!--<div class="timeslot pure-u-2-5">
					<table class="pure-table" id="select-time-table">
					<thead>
						<tr>
							<th></th>
							<th>00</th>
							<th>10</th>
							<th>20</th>
							<th>30</th>
							<th>40</th>
							<th>50</th>
							<th>60</th>
						</tr>
					</thead>
					<?php
						for ($i=0; $i < 24; $i++) {
							echo "<tr id='";
							if ($i < 10)
								echo 0;
							echo $i . "' class='hour'>";
							echo "<th>" . $i . " uur</th>\n"; 
							for ($j=0; $j < 7; $j++) { 
								echo "<td id='";
								if ($i < 10)
									echo "0";
								echo $i . "-" . $j . "'><input type='checkbox' unchecked name='time[" ;
								if ($i < 10)
									echo "0";
								echo $i . "-" . $j ."]'/>&nbsp;</td>\n";
							}
							echo "</tr>\n";
						}
					?>
					</table>
					<div id="datetime_show"><span id="date_show"></span>&nbsp;<span id="time_show"></span></div>
	
					<input type="button" id="schedule-submit" value="TODO: toevoegen" />
				</div>
			</div> 

			<div id="selector-table">Hello</div> 

			<div id="clickmenot" onClick="$('#selector-table').destroy()">Click me not</div> -->

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

		<?=form_submit_only(); ?>
		<?br().br().br().br().br();?>
		<legend>Testing area</legend>
		<div id="json_arrays"></div>
	</fieldset>
<?=form_close(); ?>

<script type="text/javascript" src="js/jquery.timerangeselector.js"></script>
<script> 
$("#timeselector").selectorTable({
	resolution: 10,
	tableClass: 'pure-table'
}
); 

</script>