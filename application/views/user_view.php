<?=heading($page_title, 2); ?>

<?=$this->session->flashdata('message'); ?>

<!-- General info -->
<?=heading(lang('general_info'), 3); ?>
<table class="pure-table">
	<tr>
		<th><?=lang('user'); ?></th>
		<td><?=$user->username; ?> (<em><?=lang($user->role); ?></em>)</td>
	</tr>
	<tr>
		<th><?=lang('phone'); ?></th>
		<td><?=$user->phone; ?></td>
	</tr>
	<tr>
		<th><?=lang('mobile'); ?></th>
		<td><?=$user->mobile; ?></td>
	</tr>
	<tr>
		<th><?=lang('email'); ?></th>
		<td><?=mailto($user->email); ?></td>
	</tr>
	<tr>
		<th><?=lang('preferredlanguage'); ?></th>
		<td><?=lang(user_language($user)); ?></td>
	</tr>
	<?php if ($user->needssignature) { ?>
	<tr>
		<th><?=lang('signed'); ?></th>
		<td><?=$user->signed ? output_datetime($user->signed) : lang('no'); ?></td>
	</tr>
	<?php } ?>
</table>

<!-- Experiments -->
<?php
if (is_admin())
{
	echo heading(lang('experiments'), 3);
	create_experiment_table('experiments');
	$experiments['id'] = 'experiments';
	$experiments['ajax_source'] = 'experiment/table_by_user/' . $user->id;
	echo $this->load->view('templates/list_view', $experiments, true);
}
?>

<!-- Comments -->
<?php
	echo heading(lang('comments'), 3);
	create_comment_table('comments');
	$comments['id'] = 'comments';
	$comments['ajax_source'] = 'comment/table_by_user/' . $user->id;
	echo $this->load->view('templates/list_view', $comments, true);
?>

<!-- Calls -->
<?php
	if (is_caller())
	{
		echo heading(lang('calls'), 3);
		create_call_table('calls', FALSE);
		$calls['id'] = 'calls';
		$calls['sort_column'] = 4;
		$calls['sort_order'] = 'desc';
		$calls['ajax_source'] = 'call/table_by_user/' . $user->id;
		echo $this->load->view('templates/list_view', $calls, true);
	}
?>