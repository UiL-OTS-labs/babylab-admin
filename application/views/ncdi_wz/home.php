<?=heading($page_title, 2); ?>

<?php if ($valid_token) { ?>
<h3>Wij willen u hartelijk danken voor het invullen van de vragenlijst!</h3>
<p>Op deze pagina's kunt u de resultaten bekijken. Vooraf is het van belang te weten hoe de resultaten te beoordelen, en daarom volgt nu eerst een korte uitleg. Daarna kunt u doorklikken naar de scores van uw kind en deze vergelijken met de percentielscores en de scores van andere kinderen uit onze database. Uiteraard wordt deze data geanominiseerd getoond.</p>

<?=heading('Uitleg', 3); ?>
<?=heading('Percentielscores', 4); ?>
<ul>
	<li>U krijgt voor ieder onderdeel van de vragenlijst een <em>percentielscore</em> te zien.</li>
	<li>De scores lopen van 0 tot 100.</li>
	<li>Een percentielscore van 65 betekent dat 35% van de kinderen van de gehele populatie een hogere score haalt.</li> 
	<li>Een score tussen 16de en 84ste percentiel valt binnen de normale curve. Bij een percentielscore van 85 of hoger, scoort uw kind bovengemiddeld en bij een score lager dan 15 scoort uw kind benedengemiddeld.</li>
	<li>Het kan goed zijn dat uw kind op één (of meer) van de onderdelen een achterstand heeft, dit is niet zorgelijk: het is een momentopname en het kan heel goed zo zijn dat deze lage score(s) bij het volgende meetmoment verdwenen is (/zijn).</li>
</ul>
<?=heading('Taalleeftijd', 4); ?>
 <ul>
	<li>U krijgt voor ieder onderdeel van de vragenlijst een <em>taalleeftijd</em> (in maanden) te zien.</li>
	<li>De taalleeftijden lopen van 16 maanden tot 30 maanden.</li>
	<li>Een taalleeftijd van 20 betekent dat uw kind presteert op het niveau van een kind van 20 maanden.</li> 
	<li>Een score binnen vier maanden van de werkelijke leeftijd van uw kind is normaal. Een taalleeftijd van meer dan 4 maanden ouder indiceert een voorsprong, een taalleeftijd van meer dan 4 maanden jonger een achterstand.</li>
	<li>Het kan goed zijn dat uw kind op het huidige meetmoment op één (of meer) van de onderdelen een achterstand heeft, dit is niet zorgelijk: het is een momentopname en het kan heel goed zo zijn dat deze lage score(s) bij het volgende meetmoment verdwenen is.</li>
</ul>

<?=heading('Interpretatie resultaten', 4); ?>
<ul>
	<li>Uw kind scoort gemiddeld wanneer de percentielscores tussen de 16 en 84 liggen.</li>
	<li>Uw kind scoort benedengemiddeld, maar er is geen reden tot zorgen wanneer uw kind op ALLE onderdelen wat betreft de percentielscores benedengemiddeld scoort (percentielscore < 16) OF op ALLE onderdelen op de taalleeftijden benedengemiddeld (> 4 maanden) scoort.</li>
	<li>Uw kind scoort benedengemiddeld, maar er is wel reden tot zorgen wanneer uw kind op ALLE onderdelen wat betreft de percentielscores benedengemiddeld scoort (percentielscore < 16) EN op ALLE onderdelen op de taalleeftijden benedengemiddeld (> 4 maanden) scoort.</li> 
	<li>Uw kind scoort bovengemiddeld wanneer de percentielscores boven de 84 liggen</li>
</ul>
Wanneer uw kind beneden gemiddeld scoort is er lang niet altijd reden tot zorg: kinderen ontwikkelen zich niet in een rechte lijn en het kan daarom heel goed zo zijn dat er van de lage scores niets meer te zien is bij een volgende meting. Wanneer dat wel zo is en de scores zijn erg laag, bij meerdere metingen, dan is het verstandig om contact op te nemen met Maartje de Klerk (<?=mailto('M.K.A.deKlerk@uu.nl'); ?>)
	
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