<script type="text/javascript">
    // Load the Visualization API and the corechart package.
    google.load('visualization', '1', {'packages':['controls']});

    // Set a callback to run when the Google Visualization API is loaded.
    google.setOnLoadCallback(drawVisualization);

    function drawVisualization() 
    {
        // CategoryPicker for date of birth vs. registration date
        var catPicker = new google.visualization.ControlWrapper({
            controlType: 'CategoryFilter',
            containerId: 'cat_filter',
            state: {selectedValues: ["<?=lang('dob'); ?>"]},
            options: {
                filterColumnLabel: "<?=lang('graph_show_by'); ?>",
                ui: {
                    labelStacking: 'horizontal',
                    labelSeparator: ':',
                    allowTyping: true,
                    allowMultiple: false,
                    allowNone: false
                }
            }
        });

        // CategoryPicker for year
        var yearPicker = new google.visualization.ControlWrapper({
            controlType: 'CategoryFilter',
            containerId: 'year_filter',
            state: {selectedValues: [new Date().getFullYear()]},
            options: {
                filterColumnLabel: "<?=lang('year'); ?>",
                ui: {
                    labelStacking: 'horizontal',
                    labelSeparator: ':',
                    allowTyping: true,
                    allowMultiple: true,
                    allowNone: true
                }
            }
        });

        // Create our data table out of JSON data loaded from server.
        var data = new google.visualization.DataTable(
            $.ajax({
                url: "<?=base_url() . 'participant/graph_json/'; ?>",
                dataType: "json",
                async: false
            }).responseText
        );

        var chart = new google.visualization.ChartWrapper({
            chartType: 'ColumnChart',
            containerId: 'chart_div',
            view: {columns: [2, 3, 4, 5, 6]},
            options: {
                height: 450,
                isStacked: true,
            }
        });

        // Create the dashboard
        new google.visualization.Dashboard(document.getElementById('dashboard_div')).
            // Configure the catPicker and yearPicker to affect the chart
            bind([catPicker, yearPicker], chart).
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

<p>
    Dit overzicht toont het aantal actieve proefpersonen per jaar/maand in de database. Met de filters kunt u aangeven welk kenmerk u wilt zien (geboortedatum of aanmelddatum) en welke jaren getoond moeten worden.
</p>

<!--Div that will hold the dashboard-->
<div id="dashboard_div">
    <!--Divs that will hold each control and chart-->
    <div id="cat_filter" class="gc-filter"></div>
    <div id="year_filter" class="gc-filter"></div>
    <div id="chart_div"></div>
</div>
