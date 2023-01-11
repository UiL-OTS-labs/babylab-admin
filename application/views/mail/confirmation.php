<html>
<body>
    <p>
        Beste <?=$name_parent; ?>,
    </p>
    <p>
        U heeft zojuist een afspraak gemaakt met één van de medewerkers van het Babylab van de Universiteit Utrecht.
    </p>
    <p>
        <?php if ($longitudinal) { ?>
        U en <?=$name; ?> worden verwacht op <strong><?=$appointment; ?></strong> en op
        <strong><?=$comb_appointment; ?></strong>,&nbsp;
        <?php } else { ?>
        U en <?=$name; ?> worden verwacht op <strong><?=$appointment; ?></strong>,&nbsp;
        <?php } ?>
        bij Janskerkhof <strong>13a</strong> (let op: dit is de groene voordeur met de helling ervoor). Zie
        <?=anchor('https://babylab.wp.hum.uu.nl/route-in-het-gebouw-13a/', 'https://babylab.wp.hum.uu.nl/route-in-het-gebouw-13a/'); ?>. Als u aanbelt en via de intercom zegt dat u voor het Babylab komt, dan wordt de deur voor u geopend.
        Eventuele reis- en parkeerkosten worden vergoed zoals aangegeven in de bijgevoegde informatiebrief.
    </p>
    <p>
        <strong>Gezondheidsklachten?</strong>
    </p>
    <p>
        Wij vragen u de afspraak af te zeggen wanneer u of uw kind op de dag van het experiment of in de dagen
        daarvoor last heeft (gehad) van:
    </p>
    <p>
        <ul>
            <li>verkoudheidsklachten zoals neusverkoudheid, loopneus, niezen, keelpijn</li>
            <li>hoesten</li>
            <li>benauwdheid</li>
            <li>verhoging of koorts</li>
            <li>plotseling verlies van reuk en/of smaak (zonder neusverstopping)</li>
        </ul>
    </p>
    <p>
        <strong>Aankomst in het Babylab</strong>
    </p>
    <p>
        Wanneer u binnenkomt, kunt u gelijk na de hal met de lift (of met de trap) naar beneden. Aan uw rechterhand
        vindt u de wachtkamer, waar u plaats kunt nemen. De onderzoeksassistent zal u daar komen ophalen.
    </p>
    <p>
        <strong>Het experiment</strong>
    </p>
    <?php if ($combination) { ?>
    <p>
        Het eerste experiment is een <?=$type; ?> en duurt maximaal <?=$duration; ?> minuten.
        Het tweede experiment is een <?=$comb_type; ?> en duurt maximaal <?=$comb_duration; ?> minuten.
        Omdat we ook de procedure uitleggen en er achteraf tijd is voor vragen, zult u ongeveer
        <?=$duration_total + $comb_duration; ?> minuten moeten kwijt zijn aan uw bezoek aan het Babylab.</p>
    <?php } else if ($longitudinal && $duration == $comb_duration) { ?>
    <p>Het experiment is een <?=$type; ?>. Beide afspraken duren maximaal <?=$duration; ?> minuten.
        Omdat we ook de procedure uitleggen en er achteraf tijd is voor vragen, zult u ongeveer
        <?=$duration_total; ?> minuten kwijt zijn aan uw bezoek aan het Babylab.</p>
    <?php } else if ($longitudinal && $duration != $comb_duration) { ?>
    <p>Het experiment is een <?=$type; ?>. De eerste afspraak duurt maximaal <?=$duration; ?> minuten.
        De tweede afspraak duurt maximaal <?=$comb_duration; ?> minuten.
        Omdat we ook de procedure uitleggen en er achteraf tijd is voor vragen, zult u ongeveer
        <?=$duration_total; ?> minuten voor de eerste afspraak,
        en ongeveer <?=$comb_duration_total; ?> kwijt zijn aan uw tweede bezoek aan het Babylab.</p>
    <?php } else { ?>
    <p>Het experiment is een <?=$type; ?> en duurt maximaal <?=$duration; ?> minuten.
        Omdat we ook de procedure uitleggen en er achteraf tijd is voor vragen, zult u ongeveer
        <?=$duration_total; ?> minuten kwijt zijn aan uw bezoek aan het Babylab.
        <?php } ?>
        In de bijlage van deze mail vindt u meer informatie over het experiment en onze werkwijze.
    </p>
    <p>
        Het is belangrijk voor ons onderzoek dat er geen broertje of zusje meekomt tijdens het bezoek
        aan het lab. Als u hierover van tevoren een andere afspraak heeft gemaakt met de medewerker van
        het Babylab, dan geldt uiteraard die afspraak.
    </p>
    <?php if ($survey_link) { ?>
    <p>
        Omdat u voor de eerste keer met <?=$name_first; ?> een bezoek brengt aan het Babylab, vragen wij
        u om de onderstaande link te openen. U wordt dan doorgeleid
        naar een pagina met vragen over o.a. de gezinssamenstelling en medische achtergrond van
        <?=$name_first; ?>.
        Het invullen van de vragenlijst duurt 5 tot 10 minuten.</p>
    <p>
        De link naar de vragenlijst (<em>Vragenlijst 1ste bezoek</em>) vindt u <?=$survey_link; ?>.
    </p>
    <?php } ?>
    <p>
        <strong>Afspraak verzetten/afzeggen</strong>
    </p>
    <p>Wanneer u verhinderd bent te komen of uw afspraak graag wilt verzetten, dan kunt u mailen
        naar: <?=mailto(BABYLAB_MANAGER_EMAIL); ?>.
        Mocht u verder nog vragen hebben dan kunt u contact opnemen met:</p>

    <?=ul($caller_contacts); ?>

    <p>
        Meer informatie over het Babylab, bijvoorbeeld hoe er te komen, is beschikbaar op de
        <?=anchor( 'https://babylab.wp.hum.uu.nl' , 'website van het Babylab' ); ?>.
        Wij danken u alvast hartelijk voor uw medewerking. Zonder uw deelname kunnen wij geen
        onderzoek doen!
    </p>
    <p>Hartelijke groet,</p>
    <p><?=BABYLAB_TEAM; ?></p>
    <p><em>Deze e-mail is automatisch gegenereerd.</em></p>

</body>

</html>
