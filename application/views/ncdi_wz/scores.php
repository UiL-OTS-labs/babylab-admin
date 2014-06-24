<?php if ($valid_token) { ?>
	<?=heading($page_title, 2); ?>
	<p>U heeft op <?=$test_date; ?> deze vragenlijst ingevuld. 
	Uw <?=$gender_child; ?> was toen <strong><?=$age_in_months; ?> maanden</strong> oud.</p>
	<p>De resultaten van uw <?=$gender_child; ?> op de vragenlijst <em><?=$test_name; ?></em> waren als volgt:</p>
	<?=$ncdi_table; ?>
	<?=heading('Commentaar', 3); ?>
	<?=$ncdi_text; ?>
	<?php if ($has_prev_results) { ?>
		<?=heading('Eerdere resultaten', 2); ?>
		<p>Uw <?=$gender_child; ?> had al eerder meegedaan aan deze vragenlijst. De resultaten waren toen als volgt:</p>
		<?php
			for ($i = 0; $i < count($ncdi_prev_tables); $i++)
			{
				echo heading($ncdi_prev_descs[$i], 3);
				echo $ncdi_prev_tables[$i];
			}
		?>
	<?php } ?>
<?php } else { show_404(); } ?>