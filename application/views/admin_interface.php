<?=heading($page_title, 2); ?>

<?=$this->session->flashdata('message'); ?>

<?=lang('welcome_admin'); ?>

<h3>
<?=lang('tasks'); ?>
</h3>
<ul>
	<li><?=anchor('participant', lang('pp_action')); ?></li>
	<li><?=anchor('comment/priority', sprintf(lang('comment_action'), $prio_comment_nr)); ?>
	</li>
	<li><?=anchor('caller', sprintf(lang('caller_action'), $call_min_exp)); ?>
	</li>
	<li><?=anchor('leader', sprintf(lang('leader_action'), $leader_min_exp)); ?>
	</li>
</ul>
