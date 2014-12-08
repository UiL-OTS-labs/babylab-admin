<?=heading($page_title, 2); ?>

<?=$this->session->flashdata('message'); ?>

<div>
	<!-- General info -->
	<?=heading(lang('location'), 3); ?>
	<table class="pure-table">
		<tr>
			<th><?=lang('name'); ?></th>
			<td><?=$location->name; ?></td>
		</tr>
		<tr>
			<th><?=lang('roomnumber'); ?></th>
			<td><?=$location->roomnumber; ?></td>
		</tr>
	</table>
</div>
