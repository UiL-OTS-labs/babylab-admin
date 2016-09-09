<script type="text/javascript" src="js/date_range.js"></script>

<?=heading(lang('timeline'), 2); ?>

<?=form_open($action, array('class' => 'pure-form')); ?>
<?=form_input('date_start', $date_start, 'id="from" readonly placeholder="'. lang('date_start') . '"'); ?>
-
<?=form_input('date_end', $date_end, 'id="to" readonly placeholder="'. lang('date_end') . '"'); ?>
<?=form_submit_only(); ?>
<?=form_close(); ?>

<table class="pure-table pure-table-bordered pure-table-striped" style="margin-top: 10px;">
	<thead>
	<?php
		echo '<tr>';
		echo '<th style="min-width: 200px; text-align: center;">' . lang('experiment') . '</th>';
		foreach (array_keys(reset($tested)) as $month) {
			echo '<th style="min-width: 45px; text-align: center;">';
			echo $month;
			echo '</th>';
		}
		echo '</tr>';
	?>
	</thead>
	<tbody>
	<?php
		$totals = '';
		foreach ($tested as $experiment => $month_counts) {
			echo '<tr>';
			echo '<td>' . $experiment . '</td>';
			foreach ($month_counts as $month => $count) {
				echo '<td style="text-align: right;">';
				echo $count > 0 ? $count : '';
				echo '</td>';

				if (!isset($totals[$month]))
				{
					$totals[$month] = $count;
				}
				else
				{
					$totals[$month] += $count;
				}
			}
			echo '</tr>';
		}

		echo '<tr style="font-weight: bold;">';
		echo '<td>' . lang('total') . '</td>';
		foreach ($totals as $month => $count) {
			echo '<td style="text-align: right;">';
			echo $count;
			echo '</td>';
		}
		echo '</tr>';
	?>
	</tbody>
</table>
