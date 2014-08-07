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
		
		// Slider for age
		var ageSlider = new google.visualization.ControlWrapper({
			controlType: 'NumberRangeFilter',
			containerId: 'filter_3',
			options: {
				filterColumnLabel: '<?=lang('age'); ?>',
				ui: {
					labelStacking: 'horizontal',
					labelSeparator: ':'
				}
			}
		});
		
		// Create our data table out of JSON data loaded from server.
		var data = new google.visualization.DataTable(
			$.ajax({
				url: "<?=base_url() . 'charts/chart/percentiles/' . $test_code . (isset($testinvite_id) ? '/' . $testinvite_id : ''); ?>",
				dataType: "json",
				async: false
			}).responseText
		);
		
		var chart = new google.visualization.ChartWrapper({
			chartType: 'LineChart',
			containerId: 'chart_div',
	        view: {columns: [<?=$participant_id == 0 ? '2, 3, 4, 5, 6, 7' : '2, 3, 4, 5, 6, 7, 8' ?>]},
			options: {
				hAxis: {title: 'Leeftijd in maanden'},
				vAxis: {title: 'Ruwe score'},
				width: '800', 
				height: '500',
				intervals: { style: 'area' }, 
				lineWidth: 1,
				pointSize: 3,
				series: {
					0: { pointSize: 7, lineWidth: 3, color: 'yellow' },
					1: { pointSize: 7, color: 'blue' }

					// old setup
					//0: { color: 'yellow' },
					//1: { lineWidth: 2, color: 'orange' },
					//2: { pointSize: 4, lineWidth: 3, color: 'red' },
					//3: { lineWidth: 2, color: 'orange' },
					//4: { color: 'yellow' },
					//5: { pointSize: 5, color: 'blue' }
				}
			}
		});

		// Create the dashboard
		new google.visualization.Dashboard(document.getElementById('dashboard_div')).
			// Configure the testpicker to affect the genderPicker
			bind(testPicker, genderPicker).
			// Configure the controls to affect the chart
			bind([genderPicker, ageSlider], chart).
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
<!--Div that will hold the dashboard-->
<div id="dashboard_div">
	<!--Divs that will hold each control and chart-->
	<div id="filter_1" class="gc-filter"></div>
	<div id="filter_2" class="gc-filter"></div>
	<div id="filter_3" class="gc-filter"></div>
	<div id="chart_div"></div>
</div>
