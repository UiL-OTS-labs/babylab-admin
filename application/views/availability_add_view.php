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
				<div class="timeslot pure-u-2-5">
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
var first, last, add, mouseDown;
	
	// Disable selection
	$("#select-time-table").attr('unselectable','on')
     .css({'-moz-user-select':'-moz-none',
           '-moz-user-select':'none',
           '-o-user-select':'none',
           '-khtml-user-select':'none', /* you could also put this in a class */
           '-webkit-user-select':'none',/* and add the CSS class here instead */
           '-ms-user-select':'none',
           'user-select':'none'
     }).bind('selectstart', function(){ return false; });

	// Make sure no checkboxes are checked on refresing
	$(':checkbox:checked').prop('checked',false);

	$("#select-time-table td").mousedown(function(){
        mouseDown = 1;
		first = $(this);
        last = $(this);
        if ($(this).hasClass("ui-custom-selected")){
            add = "ui-custom-unselecting";
        } else {
            add = "ui-custom-selecting";
        }
        
        changeSelection();
    
    }).mouseover(function(){
        if (mouseDown == 1)
        {
            if (!$(this).hasClass(add))
            {
                last = $(this);
                var alreadySelected = 0;
                changeSelection();
            }
            else
            {
                removeFromSelection($(this));
            }
        }
    }).mouseup(function(){
        mouseDown = 0;
        
		last = $(this);
        changeSelection();
        finishSelection();
	});

$(document).mouseup(function(){
    mouseDown = 0;
    finishSelection();
});


function changeSelection(){
    if (first.attr("id") < last.attr("id"))
    {
        select(first, last);
    } else {
        select(last, first);
    }
}

function removeFromSelection(item)
{
    if (last.attr("id") < item.attr("id"))
    {
        unselect(last, item);
    } else {
        unselect(item, last);
    }
}

function select(f, l)
{
    // Split to iX[row][col]
    var iF = f.attr("id").split("-");
    var iL = l.attr("id").split("-");
    
    // If selection spans multiple rows...
    if (iF[0] < iL[0])
    {
        // Fill in the first row from the selected element till the end
        for(var i = iF[1]; i <= 6; i++)
        {
            $("#" + iF[0] + "-" + i).addClass(add);
        }        
        // Fill in all the rows in between the first and last selected element
        for(var k = parseInt(iF[0], 10) + 1; k < iL[0]; k++)
        {
            for (var j = 0; j <= 7; j++)
            {
                $("#" + mkDouble(k) + "-" + j).addClass(add);
            }
        }
        
        // Fill in the last row from the beginning till the last selected element
        for(var n = 0; n <= iL[1]; n++)
        {
           $("#" + iL[0] + "-" + n).addClass(add);           
        }
    }
    else
    {
        for (var m = iF[1]; m <= iL[1]; m++)
        {
           $("#" + iF[0] + "-" + m).addClass(add);
        }
    }
}

function unselect(f, l)
{
   // Split to iX[row][col]
   var iF = f.attr("id").split("-");
   var iL = l.attr("id").split("-");
    
    var unAdd = (add == "ui-custom-selecting") ? "ui-custom-unselecting" : "ui-custom-selecting";
    
    // If selection spans multiple rows...
    if (iF[0] < iL[0])
    {
        // Fill in the first row from the selected element till the end
        for(var i = iF[1]; i <= 6; i++)
        {
            $("#" + iF[0] + "-" + i).removeClass(add);
            //$("#" + iF[0] + "-" + i).addClass(unAdd);
        }        
        // Fill in all the rows in between the first and last selected element
        for(var k = parseInt(iF[0], 10) + 1; k < iL[0]; k++)
        {
            for (var j = 0; j <= 7; j++)
            {
                $("#" + mkDouble(k) + "-" + j).removeClass(add);
                //$("#" + mkDouble(k) + "-" + j).addClass(unAdd);
            }
        }
        
        // Fill in the last row from the beginning till the last selected element
        for(var n = 0; n <= iL[1]; n++)
        {
           $("#" + iL[0] + "-" + n).removeClass(add);  
           //$("#" + iL[0] + "-" + n).addClass(unAdd);           
        }
    }
    else
    {
        for (var m = iF[1]; m <= iL[1]; m++)
        {
           $("#" + iF[0] + "-" + m).removeClass(add);
           //$("#" + iF[0] + "-" + m).addClass(unAdd);
        }
    }
}

function finishSelection()
{
    $(".ui-custom-selecting").each(function(){
        $(this).removeClass("ui-custom-selecting");
        $(this).addClass("ui-custom-selected");
        $(this).children(["input"]).prop("checked", true);
    });
    
    $(".ui-custom-unselecting").each(function(){
        $(this).removeClass("ui-custom-unselecting");
        $(this).removeClass("ui-custom-selected");
        $(this).children(["input"]).prop("checked", false);
    });
}

function mkDouble(i)
{
    if ( i < 10)
    {
        return "0" + i;
    }
    else
    {
        return i;
    }
}
	
</script>