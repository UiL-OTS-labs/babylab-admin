<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.12/themes/dot-luv/jquery-ui.css" type="text/css" rel="stylesheet"></link>

<link type="text/css" rel="stylesheet" href="css/timerangeselector.css"/>
<script type="text/javascript" src="js/jquery.ui.timerangeselector.js"></script>


<style>
div.timeslot, {
	margin-top: 30px;
}

#schedule-submit {
	font-size: 1em !important;
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
					<input class="pure-button pure-button-primary" type="button" 
						style="font-size: 1em; margin-top: 50px" value="<?=lang('availability_add');?>" id="clickme" />
					<input class="pure-button pure-button-primary" type="button" 
						style="font-size: 1em; margin-top: 50px" value="<?=lang('availability_reset');?>" id="resetTimeRange"/>
				</div>
				
				<div id="timeselector" class="pure-u-2-5"></div>

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
	// TODO's: 24/12 hours based on language =)
</script>
<script type="text/javascript" src="js/availability_helper.js"></script>