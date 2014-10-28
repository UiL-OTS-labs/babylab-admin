<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.12/themes/dot-luv/jquery-ui.css" type="text/css" rel="stylesheet"></link>
<script type="text/javascript" src="js/moment-locales.js"/></script>
<link type="text/css" rel="stylesheet" href="css/timerangeselector.css"/>
<script type="text/javascript" src="js/jquery.ui.timerangeselector.js"></script>


<style>

#schedule-submit {
	font-size: 1em !important;
}

#datum-eind{
	margin-top: 20px;
}

</style>

<?=heading($page_title, 2); ?>
<?=form_open('availability/add_submit', array('class' => 'pure-form')); ?>
<form class="pure-form pure-form-aligned">
	<fieldset>
		<legend><?=lang('availability_select'); ?></legend>
			<div class="pure-g time-selector">
				<div class="pure-u-6-24">
					
					<div id="datum"></div>
					<div id="datum-eind"></div>
				</div>
				
				<div class="pure-u-6-24">
					<div id="timeselector"></div>
					
				</div>
				<div class="pure-u-1-2">
					<input class="pure-button pure-button-primary" type="button" 
						style="font-size: 1em;" value="<?=lang('availability_add');?>" id="clickme" />
					<input class="pure-button pure-button-primary" type="button" 
						style="font-size: 1em;" value="<?=lang('availability_reset');?>" id="resetTimeRange"/>
					<p><input id="daterange" type="checkbox" checked='false'/> <label for="daterange" id="date-range-label"><?=lang('date_range');?></label></p>
				</div>

			</div>
			
				

		<legend><?=lang('availability_schedule');?></legend>
		<table class="pure-table" id="schedule">
			<thead>
				<th><?=lang('date'); ?> </th>
				<th><?=lang('time_from'); ?></th>
				<th><?=lang('time_to'); ?></th>
				<th><?=lang('comment'); ?></th>
				<th><?=lang('action'); ?></th>
			</thead>
			<tbody>
			</tbody>
		</table>

		<?=form_submit_only(); ?>

	</fieldset>
<?=form_close(); ?>

<script type="text/javascript">
	var img = '<?=img_delete(); ?>';
	var preselectedTimes = <?=$preselect;?>;
	var hourFormat, hourSuffix, dateTimeFormat;
	<?php if ($lang == 'en')
	{
		echo "hourFormat = true;";
		echo "hourSuffix = 'uur';";
		echo "dateTimeFormat = 'HH:mm';";
	} else {
		echo "hourFormat = false;";
		echo "hourSuffix = '';";
		echo "dateTimeFormat = 'hh:mm a';";
	}
	?>
</script>
<script type="text/javascript" src="js/availability_helper.js"></script>