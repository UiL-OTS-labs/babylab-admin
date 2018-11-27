<script type="text/javascript" src="js/dob.js"></script>
<script type="text/javascript" src="js/travelexpenses.js"></script>
<script type="text/javascript" src="js/languages_toggle.js"></script>
<?php if (!$is_registration) { ?>
<script type="text/javascript" src="js/languages_add.js"></script>
<?php } ?>

<?=heading($page_title, 2); ?>

<?=form_open($action, array('class' => 'pure-form pure-form-aligned')); ?>

<?=form_fieldset(lang('data_child')); ?>
<?=form_input_and_label('firstname', $firstname, 'required'); ?>
<?=form_input_and_label('lastname', $lastname, 'required'); ?>
<div class="pure-control-group">
<?=form_label(lang('gender'), 'gender'); ?>
<?=form_radio_and_label('gender', Gender::FEMALE, $gender, lang('girl')); ?>
<?=form_radio_and_label('gender', Gender::MALE, $gender, lang('boy')); ?>
<?=form_error('gender'); ?>
</div>
<?=form_input_and_label('dob', $dob, 'id="birth_datepicker" required'); ?>

<!-- Birth weight and pregnancy duration: required during registration, not when editing -->  
<?=form_input_and_label('birthweight', $birthweight, 'class="positive-integer"' . ($is_registration ? 'required' : '')); ?>

<div class="pure-control-group">
<?=form_label(lang('pregnancy')); ?>
<?=form_input('pregnancyweeks', set_value('pregnancyweeks', $pregnancyweeks), 
	'class="positive-integer" placeholder="' . ucfirst(lang('weeks')) . '"' . ($is_registration ? 'required' : '')); ?>
<?=form_error('pregnancyweeks'); ?>
<?=form_input('pregnancydays', set_value('pregnancydays', $pregnancydays), 
	'class="positive-integer" placeholder="' . ucfirst(lang('days')) . '"' . ($is_registration ? 'required' : '')); ?>
<?=form_error('pregnancydays'); ?>
</div>

<?=form_fieldset(lang('data_parent')); ?>
<?=form_input_and_label('parentfirstname', $parentfirstname, 'required'); ?>
<?=form_input_and_label('parentlastname', $parentlastname, 'required'); ?>
<?=form_input_and_label('city', $city); ?>
<div style="margin-bottom: 10px;padding-left: 26em;display:none;" id="travel_expenses_warning">
    <?=lang('outside_utrecht')?>
</div>
<?=form_input_and_label('phone', $phone, 'required'); ?>
<?=form_input_and_label('phonealt', $phonealt); ?>
<?=form_input_and_label('email', $email, 'required email="true"'); ?>
<div class="pure-control-group">
    <?=form_label(lang('newsletter').'*', 'newsletter'); ?>
    <?=form_radio_and_label('newsletter', '1', $newsletter, lang('yes')); ?>
    <?=form_radio_and_label('newsletter', '0', $newsletter, lang('no'), TRUE); ?>
</div>
<p>* <?=lang('newsletter_q')?></p>

<?=form_fieldset(lang('data_language')); ?>
<div class="pure-control-group">
<?=form_label(lang('dyslexic_q'), 'dyslexicparent'); ?>
<?=form_radio_and_label('dyslexicparent', Gender::FEMALE, $dyslexicparent, lang('yes') . ', ' . strtolower(lang('mother'))); ?>
<?=form_radio_and_label('dyslexicparent', Gender::MALE, $dyslexicparent, lang('yes') . ', ' . strtolower(lang('father'))); ?>
<?=form_radio_and_label('dyslexicparent', Gender::BOTH, $dyslexicparent, lang('yes') . ', ' . strtolower(lang('both'))); ?>
<?=form_radio_and_label('dyslexicparent', Gender::NONE, $dyslexicparent, lang('no'), TRUE); ?>
</div>
<div class="pure-control-group">
<?=form_label(lang('problems_q'), 'problemsparent'); ?>
<?=form_radio_and_label('problemsparent', Gender::FEMALE, $problemsparent, lang('yes') . ', ' . strtolower(lang('mother'))); ?>
<?=form_radio_and_label('problemsparent', Gender::MALE, $problemsparent, lang('yes') . ', ' . strtolower(lang('father'))); ?>
<?=form_radio_and_label('problemsparent', Gender::BOTH, $problemsparent, lang('yes') . ', ' . strtolower(lang('both'))); ?>
<?=form_radio_and_label('problemsparent', Gender::NONE, $problemsparent, lang('no'), TRUE); ?>
</div>
<div class="pure-control-group">
<?=form_label(lang('multilingual_q'), 'multilingual'); ?>
<?=form_radio_and_label('multilingual', '1', $multilingual, lang('yes')); ?>
<?=form_radio_and_label('multilingual', '0', $multilingual, lang('no'), TRUE); ?>
</div>

<div id="languages">
<?php if (!$is_registration) { ?>
	<p>
		<em>Vul hieronder in met welke talen het kind in aanraking komt en de (geschatte) percentages van blootstelling aan deze talen. 
			Als je deze velden invult (je kunt de velden ook leeg laten), moeten de percentages optellen tot 100.</em>
	</p>
	<?=form_error('percentage'); ?>
	<?php
	$i = 1;
	foreach ($languages AS $language)
	{
		echo '<div class="pure-control-group">';
		echo ' <label for="language">Taal ' . $i . '</label>';
		echo ' <input type="text" name="language[]" value="' . $language->language . '" placeholder="Taal">';
		echo ' <input type="text" name="percentage[]" value="' . $language->percentage . '" placeholder="Percentage" class="positive-integer">';
		if ($i == 1) echo ' <a class="add_l">+ voeg taal toe</a>';
		else echo ' <a class="del_l">x verwijder taal</a>';
		echo '</div>';
		$i++;
	}
	?>
	<?php } ?>
</div>

	<?=form_fieldset(lang('data_end')); ?>
	<?=form_dropdown_and_label('origin', array(
	'letter' 	=> lang('origin_letter'),
	'zwazat' 	=> lang('origin_zwazat'), 
	'mouth' 	=> lang('origin_mouth'),
	'info' 		=> lang('origin_info'),
	'other' 	=> lang('origin_other')), $origin); ?>
	<?=form_textarea_and_label('comment', $comment, lang('comment_space')); ?>

	<?=form_controls(); ?>
	<?=form_fieldset_close(); ?>
	<?=form_close(); ?>
