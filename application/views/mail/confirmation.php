<html>
<body>
<p>Beste <?=$name_parent; ?>,</p>
<p>U heeft zojuist een afspraak gepland met één van de medewerkers van het Babylab.</p>
<?php if ($longitudinal) { ?>
<p>U en <?=$name; ?> worden verwacht op <strong><?=$appointment; ?></strong> en op <strong><?=$comb_appointment; ?></strong>.</p>
<?php } else { ?>
<p>U en <?=$name; ?> worden verwacht op <strong><?=$appointment; ?></strong>.</p>
<?php } ?>
<p>De locatie is Janskerkhof 13. Eventuele reis- en parkeerkosten worden vergoed.</p>
<p>Wanneer u binnenkomt, kunt u doorlopen naar de <strong>wachtkamer van het Babylab</strong>.
Deze bevindt zich in de kelder van het pand, in <strong>kamer K.01</strong>.
Via de borden wordt u de weg gewezen naar de wachtkamer. Wanneer u met een kinderwagen komt, kunt u de route naar de lift volgen.
Anders kunt u aan het einde van de welkomsthal de trap naar beneden nemen en de weg verder vervolgen met behulp van de borden.
Let op: het pand heeft erg veel deuren!<br>
Eén van onze onderzoeksmedewerkers zal u vervolgens op het afgesproken tijdstip op komen halen in de wachtkamer.
U kunt een kopje koffie of thee pakken wanneer u eerder bent gearriveerd dan het afgesproken tijdstip.
</p>
<?php if ($combination) { ?>
<p>Het eerste experiment is een <?=$type; ?> en duurt maximaal <?=$duration; ?> minuten. 
Het tweede experiment is een <?=$comb_type; ?> en duurt maximaal <?=$comb_duration; ?> minuten. 
Omdat we ook de procedure uitleggen en er achteraf tijd is voor vragen, zult u ongeveer 
<?=$duration_total + $comb_duration; ?> minuten moeten reserveren voor uw bezoek aan het lab. 
<?php } else if ($longitudinal && $duration == $comb_duration) { ?>
<p>Het experiment is een <?=$type; ?>. Beide afspraken duren maximaal <?=$duration; ?> minuten. 
Omdat we ook de procedure uitleggen en er achteraf tijd is voor vragen, zult u ongeveer <?=$duration_total; ?> minuten moeten reserveren voor uw bezoek aan het lab.
<?php } else if ($longitudinal && $duration != $comb_duration) { ?>
<p>Het experiment is een <?=$type; ?>. De eerste afspraak duurt maximaal <?=$duration; ?> minuten. 
De tweede afspraak duurt maximaal <?=$comb_duration; ?> minuten. 
Omdat we ook de procedure uitleggen en er achteraf tijd is voor vragen, zult u ongeveer <?=$duration_total; ?> minuten voor de eerste afspraak, 
en ongeveer <?=$comb_duration_total; ?> moeten reserveren voor uw tweede bezoek aan het lab.
<?php } else { ?>
<p>Het experiment is een <?=$type; ?> en duurt maximaal <?=$duration; ?> minuten. 
Omdat we ook de procedure uitleggen en er achteraf tijd is voor vragen, zult u ongeveer <?=$duration_total; ?> minuten moeten reserveren voor uw bezoek aan het lab.
<?php } ?>
In de bijlage van deze mail vindt u meer informatie over het experiment en onze werkwijze.</p>
<?php if ($survey_link) { ?>
<p>Omdat u voor de eerste keer met <?=$name_first; ?> een bezoek brengt aan het Babylab, zouden wij u willen vragen de onderstaande link te openen.
Hier vindt u vragen die o.a. ingaan op de gezinssamenstelling en medische achtergrond van <?=$name_first; ?>. 
Het invullen van de vragenlijst duurt 5 tot 10 minuten.</p> 
<?php } ?>
<p>Het is belangrijk voor ons onderzoek dat er geen broertje of zusje meekomt tijdens het bezoek aan het lab. 
Indien u hierover een afspraak heeft gemaakt met de medewerker van het Babylab, dan geldt uiteraard de afspraak die jullie gemaakt hebben.</p>
<p>Wanneer u verhinderd bent te komen of de afspraak graag wil verzetten, dan kunt u mailen naar: <?=mailto(BABYLAB_MANAGER_EMAIL); ?>.
Mocht u verder nog vragen hebben dan kunt u contact opnemen met:</p>
<?=ul($caller_contacts); ?> 
<?php if ($survey_link) { ?>
<p>De link naar de vragenlijst (<em>Anamnese 1ste bezoek</em>) vindt u hier: <?=$survey_link; ?>.</p>
<?php } ?>
<p>Meer informatie over het Babylab en bijvoorbeeld hoe er te komen is beschikbaar op <?=anchor('http://babylab.wp.hum.uu.nl'); ?>.</p>
<p>Wij danken u alvast hartelijk voor uw medewerking, zonder uw deelname kunnen wij geen onderzoek doen!</p>
<p>Hartelijke groet,</p>
<p><?=BABYLAB_TEAM; ?></p>
<p><em>Deze e-mail is automatisch gegenereerd.</em></p>
</body>
</html>
