<!-- THIS IS NOT USED -->

<script type="text/javascript">
	// Load the Visualization API and the corechart package.
	google.load('visualization', '1', {'packages':['corechart']});
  
	// Set a callback to run when the Google Visualization API is loaded.
	google.setOnLoadCallback(drawChart);
	  
	function drawChart() {
		var jsonData = $.ajax({
			url: "<?=base_url() . 'chart/percentiles_by_gender'; ?>",
			dataType:"json",
			async: false
		}).responseText;
	  
		// Create our data table out of JSON data loaded from server.
		var data = new google.visualization.DataTable(jsonData);
		var options = {
			hAxis: {title: '<?=lang('age'); ?>'},
			vAxis: {title: '<?=lang('score'); ?>'},
			width: '800', 
			height: '500'
		};

		var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
		chart.draw(data, options);
	}
</script>

<h2>
<?=lang('percentiles'); ?>
</h2>
<div id="chart_div"></div>
