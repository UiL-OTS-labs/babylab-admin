<?=heading($page_title, 2); ?>

<?=$this->session->flashdata('message'); ?>

<!-- General info -->
<h3>
<?=lang('general_info'); ?>
</h3>
<table class="pure-table">
	<tr>
		<th><?=lang('user'); ?></th>
		<td><?=$user->username; ?> (<em><?=lang($user->role); ?> </em>)</td>
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
</table>

<!-- Experiments -->
<?php
if ($user->role != UserRole::Admin)
{
	echo heading(lang('experiments'), 3);
	create_experiment_table('experiments');
	$experiments['id'] = 'experiments';
	$experiments['ajax_source'] = 'experiment/table_by_user/' . $user->id;
	echo $this->load->view('templates/list_view', $experiments);
}
?>

<!-- Comments -->
<?php
echo heading(lang('comments'), 3);
create_comment_table('comments');
$comments['id'] = 'comments';
$comments['ajax_source'] = 'comment/table_by_user/' . $user->id;
echo $this->load->view('templates/list_view', $comments);
?>

<!-- Calls -->
<?php
if ($user->role === UserRole::Caller)
{
	echo heading(lang('calls'), 3);
	create_call_table('calls');
	$calls['id'] = 'calls';
	$calls['ajax_source'] = 'call/table_by_user/' . $user->id;
	echo $this->load->view('templates/list_view', $calls);
	}
?>