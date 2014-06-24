<?=heading(lang('results'), 2); ?>

<?=$this->session->flashdata('message'); ?>

<!-- 
<pre>
<?=var_dump($file); ?>
</pre>
 -->

<?=form_open_multipart($action, array('class' => 'pure-form pure-form-aligned')); ?>
<?=form_fieldset($page_title); ?>
<input type="file" name="userfile" size="20" />

<?=form_controls(); ?>
<?=form_fieldset_close(); ?>
<?=form_close(); ?>
<?=validation_errors(); ?>
