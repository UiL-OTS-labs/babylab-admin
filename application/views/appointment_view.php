<!-- Calendar -->

<!-- Stylesheets for this page only -->
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.0.2/fullcalendar.css" />
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/qtip2/2.2.0/jquery.qtip.min.css" />
<link rel="stylesheet" href="css/calendar.css" />

<!-- Scripts for this page only -->
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/qtip2/2.2.0/jquery.qtip.min.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.2/moment.min.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.0.2/fullcalendar.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.0.2/lang/nl.js"></script>

<!-- Build the calendar -->
<script type="text/javascript" src="js/fullCalendar_helper.js"></script>
<script>
$(function(){
	initializeCalendar("<?=$lang;?>", "<?=lang('not_loggedin_error');?>");
	addDateSelectTool("<?=lang('date_text'); ?>");
	addLegendButton("<?=$legend;?>", "<?=lang('legend');?>");
	addFilters();
	
	// Refresh every minute
	setInterval(function() { $('#calendar').fullCalendar('refetchEvents'); }, 60000);
});
</script>

<?=heading($page_title, 2); ?>

<?=$this->session->flashdata('message'); ?>

<!-- Filter helper buttons. Disabled in this version -->  

<!-- <div class="filter-control">
	<span class="fc-button fc-state-default" id="all_exps" style="-moz-user-select: none;">Select all experiments</span>
	<span class="fc-button fc-state-default" id="clear_exps" style="-moz-user-select: none;">Clear experiment filter</span>
	<span class="fc-button fc-state-default" id="all_parts" style="-moz-user-select: none;">Select all participants</span>
	<span class="fc-button fc-state-default" id="clear_parts" style="-moz-user-select: none;">Clear participant filter</span>
</div>-->

<div class="filter" style="margin-bottom: 10px;">
	<select class="chosen-select select-experiment" multiple data-placeholder="<?=lang('filter_experiment');?>">
		<?php 
		foreach (experiment_options($experiments) as $id => $option)
		{
			echo '<option value="' . $id . '">' . $option . '</option>\n';
		}
		?>
	</select>
	
	<select class="chosen-select select-participant" multiple data-placeholder="<?=lang('filter_participant');?>">
		<?php 
		foreach (participant_options($participants) as $id => $option)
		{
			echo '<option value="' . $id . '">' . $option . '</option>\n';
		}
		?>
	</select>

	<br>

	<select class="chosen-select select-location" multiple data-placeholder="<?=lang('filter_location');?>">
		<?php 
		foreach (location_options($locations) as $id => $option)
		{
			echo '<option value="' . $id . '">' . $option . '</option>\n';
		}
		?>
	</select>

	<select class="chosen-select select-leader" multiple data-placeholder="<?=lang('filter_leader');?>">
		<?php 
		foreach (leader_options($leaders) as $id => $option)
		{
			echo '<option value="' . $id . '">' . $option . '</option>\n';
		}
		?>
	</select>

	<span class="fc-button fc-state-default"
		id="clearall" style="vertical-align: middle; -moz-user-select: none;">
			<?=lang('clear_filters');?>
	</span>
	
	<div style="margin-top: 10px;"><label id="exclude-canceled-label" style="vertical-align: middle;">
		<input type="checkbox" name="exclude-canceled" style="vertical-align: middle;" checked="checked" id="exclude-canceled" />
		&nbsp;<?=lang('exclude_empty');?>
	</label></div>

	<div style="margin-top: 10px"><label id="include-availability-label" style="vertical-align: middle;">
		<input type="checkbox" name="include-availability" style="vertical-align: middle;" checked="checked" id="include-availability"/>
		&nbsp;<?=lang('show_availability');?>
	</label></div>
</div>
<input type="hidden" name="date_picker" id="date_picker" />
<div id='calendar'></div>
