<?=heading($page_title, 2); ?>

<?=$this->session->flashdata('message'); ?>

<?=form_open('testinvite/manual_reminder_submit/' . $testinvite->id, array('class' => 'pure-form')); ?>

<p>
	U gaat een herinnering versturen aan <strong><?=name($participant); ?></strong> voor de vragenlijst <strong><?=testsurvey_name($testsurvey); ?></strong>.
</p>
<p>
	Alvorens een handmatige herinnering te sturen is het te prefereren de ouder van de proefpersoon te bellen.
	<?=sprintf(lang('call_contact'), $participant->firstname, parent_name($participant)); ?>
</p>
<table class="pure-table">
	<tr>
		<th><?=lang('phone'); ?></th>
		<td><?=$participant->phone; ?></td>
	</tr>
	<?php if (!empty($participant->phonealt)) { ?>
	<tr>
		<th><?=lang('phonealt'); ?></th>
		<td><?=$participant->phonealt; ?></td>
	</tr>
	<?php } ?>
	<tr>
		<th><?=lang('email'); ?></th>
		<td><?=mailto($participant->email); ?></td>
	</tr>
</table>

<?=form_submit_only('', 'Herinnering versturen'); ?>
<?=form_cancel('testinvite', lang('cancel')); ?>
<?=form_close(); ?>
