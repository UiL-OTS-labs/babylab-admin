<?=heading($page_title, 2); ?>

<?php if ($valid_token) { ?>
<p>Hartelijk dank voor het invullen van de vragenlijst <em><?=$test_name; ?></em>.</p>
<p>Op deze pagina's kunt u de resultaten bekijken. Vooraf is het van belang te weten hoe de resultaten te beoordelen, en daarom volgt nu eerst een korte uitleg. Daarna kunt u doorklikken naar de scores van uw kind en deze vergelijken met de percentielscores en de scores van andere kinderen uit onze database. Uiteraard wordt deze data geanominiseerd getoond.</p>

<?=heading('Uitleg', 3); ?>
<?=heading('Percentielscores', 4); ?>
<ul>
	<li>U krijgt voor ieder onderdeel van de vragenlijst een <em>percentielscore</em> te zien.</li>
	<li>De scores lopen van 0 tot 100.</li>
	<li>Een percentielscore van 65 betekent dat uw kind op dit onderdeel beter presteert dan 65% van de populatie.</li> 
	<li>Een score tussen het 20ste en 80ste percentiel is normaal. Boven de 80 indiceert een voorsprong, onder de 20 een achterstand.</li>
	<li>Het kan goed zijn dat uw kind op het huidige meetmoment een achterstand heeft, terwijl deze bij het volgende meetmoment een voorsprong wordt. Omgekeerd kan ook. Ieder kind ontwikkelt zich anders.</li>
</ul>
<?=heading('Taalleeftijd', 4); ?>
 <ul>
	<li>U krijgt voor ieder onderdeel van de vragenlijst een <em>taalleeftijd</em> (in maanden) te zien.</li>
	<li>De taalleeftijden lopen van 16 tot 30.</li>
	<li>Een taalleeftijd van 20 betekent dat uw kind presteert op het niveau van een kind van 20 maanden.</li> 
	<li>Een score binnen vier maanden van de werkelijke leeftijd van uw kind is normaal. Een taalleeftijd van meer dan 4 maanden ouder indiceert een voorsprong, meer dan 4 maanden jonger een achterstand.</li>
	<li>Het kan goed zijn dat uw kind op het huidige meetmoment een achterstand heeft, terwijl deze bij het volgende meetmoment een voorsprong wordt. Omgekeerd kan ook. Ieder kind ontwikkelt zich anders.</li>
</ul>
	
<?=heading('Rapportage', 3); ?>
<ul>
	<li>U kunt via de pagina <?=anchor('c/' . $test_code . '/' . $token . '/sc', lang('scores')); ?> de scores van uw kind inzien.</li>
	<li>Via de pagina <?=anchor('c/' . $test_code . '/' . $token . '/cp', lang('percentiles')); ?> de scores van uw zoon/dochter vergelijken met de percentielscores.</li>
	<li>Via de pagina <?=anchor('c/' . $test_code . '/' . $token . '/vs', 'Alle scores'); ?> kunt u een overzicht zien van alle scores.</li>
</ul>

<?php } else { ?>
<p>Welkom! Op deze pagina vindt u gegevens over de vragenlijst <em><?=$test_name; ?></em>.</p>
<?=heading('Rapportage', 3); ?>
<ul>
	<li>Via de pagina <?=anchor('c/' . $test_code . '/cp', lang('percentiles')); ?> de percentielscores inzien.</li>
	<li>Via de pagina <?=anchor('c/' . $test_code . '/vs', 'Alle scores'); ?> kunt u een overzicht zien van alle scores.</li>
</ul>
<?php } ?>