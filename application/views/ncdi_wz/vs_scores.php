<script type="text/javascript">
	// Load the Visualization API and the corechart package.
	google.load('visualization', '1', {'packages':['controls']});
  
	// Set a callback to run when the Google Visualization API is loaded.
	google.setOnLoadCallback(drawVisualization);
	  
	function drawVisualization() {
        // CategoryPicker for test
        var testPicker = new google.visualization.ControlWrapper({
			controlType: 'CategoryFilter',
			containerId: 'filter_1',
			options: {
				filterColumnLabel: '<?=lang('testcat'); ?>',
				ui: {
					labelStacking: 'horizontal',
					labelSeparator: ':',
					allowTyping: false,
					allowMultiple: false,
					allowNone: false
				}
			}
		});
		
        // CategoryPicker for gender
        var genderPicker = new google.visualization.ControlWrapper({
			controlType: 'CategoryFilter',
			containerId: 'filter_2',
			state: {selectedValues: ['<?=$gender; ?>']},
			options: {
				filterColumnLabel: '<?=lang('gender'); ?>',
				ui: {
					labelStacking: 'horizontal',
					labelSeparator: ':',
					allowTyping: false,
					allowMultiple: false,
					allowNone: false
				}
			}
		});
		
		// Create our data table out of JSON data loaded from server.
		var data = new google.visualization.DataTable(
			$.ajax({
				url: "<?=base_url() . 'charts/chart/vs_scores/' . $test_code; ?>",
				dataType: "json",
				async: false
			}).responseText
		);
		
		var chart = new google.visualization.ChartWrapper({
			chartType: 'ScatterChart',
			containerId: 'chart_div',
	        view: {columns: [2, 3, 4]},
			options: {
				hAxis: {title: 'Leeftijd in maanden'},
				vAxis: {title: 'Ruwe score'},
				width: '800', 
				height: '500',
				tooltip: {
					trigger: 'none'
				},
				series: {
					0: { pointSize: 7, color: 'blue' }, 
					1: { pointSize: 7, lineWidth: 3, color: 'yellow' }
				}
			}
		});

		// Create the dashboard
		new google.visualization.Dashboard(document.getElementById('dashboard_div')).
			// Configure the testpicker to affect the genderPicker
			bind(testPicker, genderPicker).
			// Configure the controls to affect the chart
			bind([genderPicker], chart).
			// Draw the dashboard
			draw(data);
	}
</script>

<style>
.google-visualization-controls-label {
	width: 100px;
}

.gc-filter {
	margin-bottom: 5px;
}
</style>

<?=heading($page_title, 2); ?>
<p>De grafiek hieronder toont scores van alle proefpersonen van het Babylab Utrecht ten opzichte van het 50e percentiel.</p>
<ul>
	<li>De gele lijn is het 50ste percentiel.</li>
	<li>De blauwe punten geven de scores van een kind in onze database aan.</li>
	<li>Met de filters kunt u tussen de verschillende testcategorie&#235;n navigeren.</li>
</ul>
<br>
<!--Div that will hold the dashboard-->
<div id="dashboard_div">
	<!--Divs that will hold each control and chart-->
	<div id="filter_1" class="gc-filter"></div>
	<div id="filter_2" class="gc-filter"></div>
	<div id="chart_div"></div>
</div>
