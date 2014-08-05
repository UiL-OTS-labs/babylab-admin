<html>
<body>
<p>Beste <?=$name_parent; ?>,</p>
<p>U heeft zojuist een afspraak gepland met één van de medewerkers van het BabyLab.</p> 
<p>U en <?=$name; ?> worden verwacht op <strong><?=$appointment; ?></strong>.</p>
<p>De locatie is Janskerkhof 13. Eventuele reis- en parkeerkosten worden vergoed.</p>
<p>Het Janskerkhof 13 heeft helaas geen informatiebalie waar u zich kunt melden. 
Daarom zal een van de onderzoeksmedewerkers u op het afgesproken tijdstip komen halen. 
Wanneer u het gebouw binnenkomt, staat u in de hal. 
Daar staan twee banken, waar u kunt wachten als u wat eerder bent gearriveerd dan het afgesproken tijdstip.</p>
<p>Het experiment is een <?=$type; ?> en duurt maximaal <?=$duration; ?> minuten. 
Omdat we ook de procedure uitleggen en er achteraf tijd is voor vragen, zult u ongeveer <?=$duration_total; ?> minuten moeten reserveren voor uw bezoek aan het lab.
In de bijlage van deze mail vindt u meer informatie over het experiment en onze werkwijze.</p>
<?php if ($first_visit) { ?>
<p>Omdat u voor de eerste keer met <?=$name_first; ?> een bezoek brengt aan het BabyLab, zouden wij u willen vragen de onderstaande link te openen.
Hier vindt u vragen die o.a. ingaan op de gezinssamenstelling en medische achtergrond van <?=$name_first; ?>. 
Het invullen van de vragenlijst duurt 5 tot 10 minuten.</p> 
<?php } ?>
<p>Het is belangrijk voor ons onderzoek dat er geen broertje of zusje meekomt tijdens het bezoek aan het lab. 
Indien u hierover een afspraak heeft gemaakt met de medewerker van het BabyLab, dan geldt uiteraard de afspraak die jullie gemaakt hebben.</p>
<p>Wanneer u verhinderd bent te komen of de afspraak graag wil verzetten, dan kunt u mailen naar: <a href="mailto:babylabutrecht@uu.nl">babylabutrecht@uu.nl</a>.
Mocht u verder nog vragen hebben dan kunt u contact opnemen met Maartje de Klerk: 06-39 01 54 20.</p>
<?php if ($first_visit) { ?>
<p>De link naar de vragenlijst (<em>Anamnese 1ste bezoek</em>) vindt u hier: <?=$survey_link; ?>.</p>
<?php } ?>
<p>Meer informatie over het BabyLab en bijvoorbeeld hoe er te komen is beschikbaar op <a href="http://babylab.wp.hum.uu.nl">http://babylab.wp.hum.uu.nl</a>.</p>
<p>Wij danken u alvast hartelijk voor uw medewerking, zonder uw deelname kunnen wij geen onderzoek doen!</p>
<p>Maartje de Klerk (lab manager)</p>
<p><em>Deze e-mail is automatisch gegenereerd.</em></p>
</body>
</html>