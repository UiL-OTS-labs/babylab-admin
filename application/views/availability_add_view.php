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

.ui-selectee input{
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
				<div class="timeslot pure-u-2-5">
					<table class="pure-table" id="select-time-table">
					<thead>
						<tr>
							<td></td>
							<td>00</td>
							<td>10</td>
							<td>20</td>
							<td>30</td>
							<td>40</td>
							<td>50</td>
							<td>60</td>
						</tr>
					</thead>
					<?php
						for ($i=0; $i < 24; $i++) {
							echo "<tr id='" . $i . "' class='hour'>";
							echo "<th>" . $i . " uur</th>\n"; 
							for ($j=0; $j < 7; $j++) { 
								echo "<td id='" . 
									$i . "-" . $j . "'><input type='checkbox' unchecked name='time[" 
									. $i . "-" . $j ."]'/>&nbsp;</td>\n";
							}
							echo "</tr>\n";
						}
					?>
					</table>
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

		<?=form_submit_only(); ?>
		<?br().br().br().br().br();?>
		<legend>Testing area</legend>
		<div id="json_arrays"></div>
	</fieldset>
<?=form_close(); ?>

    


<script>
	// Keeps track of the first and last selected elements
	var first, last;
	
	$(function(){
		// Make sure no checkboxes are checked on refresing
		$(':checkbox:checked').prop('checked',false);

		// e.metaKey enables selection of multiple area's without holding ctrl-key
		$("#select-time-table").bind("mousedown", function(e){ e.metaKey = true; }).selectable({
			filter: "td",
			selecting: function(event, ui){
				
			    var select = ui.selecting;

			    if (first == null){
			    	first = select;
			    } else {
			    	$("json_arrays").append("<br/>wtf<br/>");
			    }

			    last = select;

			    if ($(select).hasClass("ui-custom-selected"))
			    {
			    	$(select).addClass("ui-custom-unselecting");
			    } else {
			    	$(select).addClass("ui-custom-selecting");
			    }

			    check();

			    
			    last = null;
			},
			unselecting: function(event, ui)
			{
				var e = ui.unselecting;

				$(e).removeClass("ui-custom-selecting");
				$(e).removeClass("ui-custom-unselecting");
			},
			selected: function(event, ui)
			{
				var e = ui.selected;
				$(e).removeClass("ui-custom-selecting");
				$(e).removeClass("ui-custom-unselecting");
				if ($(e).hasClass("ui-custom-selected"))
				{
					$(e).removeClass("ui-custom-selected");
					$(e).children(["input"]).prop("checked", false)
				} else {
					$(e).addClass("ui-custom-selected");
					$(e).children(["input"]).prop("checked", true)
				}
			},
			stop: function(event, ui)
			{
				first = null;
				last = null;
			}
		});
	});

	function check()
	{
		var firstB4last = false;
		if (first.parentNode.rowIndex > last.parentNode.rowIndex)
		{
			firstB4last = false;
		}
		else if (first.parentNode.rowIndex < last.parentNode.rowIndex)
		{
			firstB4last = true;
		}
		else{
			if (first.cellIndex < last.cellIndex)
			{
				firstB4last = true;
			}
			else{
				firstB4last = false;
			}
		}

		if (firstB4last)
		{
			if (first.parentNode.rowIndex < last.parentNode.rowIndex){
				for (var i = first.cellIndex; i < 8; i++)
				{
					var cell = document.getElementById("select-time-table").rows[first.parentNode.rowIndex].cells[i];
					$(cell).addClass("ui-custom-selecting");
				}

				for (var i = first.parentNode.rowIndex + 1; i < last.parentNode.rowIndex; i++)
				{
					for (var j = 0; j < 8; j++)
					{
						var cell = document.getElementById("select-time-table").rows[i].cells[j];
						$(cell).addClass("ui-custom-selecting");
					}
				}

				for (var i = 0; i < last.cellIndex; i++)
				{
					var cell = document.getElementById("select-time-table").rows[last.parentNode.rowIndex].cells[i];
					$(cell).addClass("ui-custom-selecting");
				}
			}
		}
		

		//var cell = $("#select-time-table").eq(first.parentNode.rowIndex).find('td').eq(0);

		


		/*$("#json_arrays").append("First: (" + first.parentNode.rowIndex + "," + first.cellIndex + 
			")&nbsp;&nbsp;&nbsp;&nbsp;last: (" + 
			last.parentNode.rowIndex + "," + last.cellIndex + 
			")&nbsp;&nbsp;&nbsp;&nbsp; First before last: " + 
			firstB4last.toString() + "<br/>");*/

	}



	// Makes datepicker and sets format
	$("#datum").datepicker({ 
		dateFormat: "dd-mm-yy",
		onSelect: function(){
			$("#date_show").html($(this).val());
		}
	});
	
	

	

	
	
</script>