<!-- THIS IS NOT USED -->

<script type="text/javascript">
	// Load the Visualization API and the corechart package.
	google.load('visualization', '1', {'packages':['corechart']});
  
	// Set a callback to run when the Google Visualization API is loaded.
	google.setOnLoadCallback(drawChart);
	  
	function drawChart() {
		var jsonData = $.ajax({
			url: "<?=base_url() . 'chart/percentiles'; ?>",
			dataType: "json",
			async: false
		}).responseText;
	  
		// Create our data table out of JSON data loaded from server.
		var data = new google.visualization.DataTable(jsonData);
		var options = {
			title: 'Percentiles',
			hAxis: {title: 'Age in months'},
			vAxis: {title: 'Score'},
			width: '800', 
			height: '500',
			lineWidth: 1,
			pointSize: 3,
			series: {
				0: { color: 'yellow' },
				1: { lineWidth: 2, color: 'orange' },
				2: { lineWidth: 3, color: 'red' },
				3: { lineWidth: 2, color: 'orange' },
				4: { color: 'yellow' }
			}
		};

		var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
		chart.draw(data, options);
	}
</script>

<h2>
<?=lang('percentiles'); ?>
</h2>
<!--Div that will hold the dashboard-->
<div id="dashboard_div">
	<!--Divs that will hold each control and chart-->
	<div id="filter_div"></div>
	<div id="chart_div"></div>
</div>
