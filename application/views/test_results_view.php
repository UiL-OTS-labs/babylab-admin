<?=heading($page_title, 2); ?>

<?=$this->session->flashdata('message'); ?>

<table class="pure-table">
	<tr>
		<th><?=lang('test'); ?></th>
		<td><?=$test->name; ?></td>
	</tr>
	<tr>
		<th><?=lang('date'); ?></th>
		<td><?=output_date($result->submitdate); ?></td>
	</tr>
	<tr>
		<th><?=lang('token'); ?></th>
		<td><?=$result->token; ?></td>
	</tr>

	<?php
	foreach ($result_array as $k => $v)
	{
		$k = strip_tags($k);
		echo '<tr><th>' . substr($k, 0, 80) . '</th><td>' . $v . '</td></tr>';
	} 
?>
</table>
