<!-- Form for adding closings -->
<script type="text/javascript" src="js/datetime_range.js"></script>

<?=heading($page_title, 2); ?>

<?=$this->session->flashdata('message'); ?>
<?=form_open('closing/add_submit/', array('class' => 'pure-form pure-form-aligned')); ?>
<?=form_fieldset(lang('add_closing')); ?>
<?=form_multiselect_and_label('location', $locations, array(), 'class="chosen-select"'); ?>
<?=form_single_checkbox_and_label('lockdown', '1'); ?>
<?=form_input_and_label('from_date', '', 'id="from" readonly'); ?>
<?=form_input_and_label('to_date', '', 'id="to" readonly'); ?>
<?=form_textarea_and_label('comment'); ?>

<?=form_submit_only(); ?>
<?=form_fieldset_close(); ?>
<?=form_close(); ?>
