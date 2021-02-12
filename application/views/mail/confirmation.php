<html>
<body>
    <p>
        Beste <?=$name_parent; ?>,
    </p>
    <p>
        U heeft zojuist een afspraak gemaakt met één van de medewerkers van het Babylab.
    </p>
    <p>
        <?php if ($longitudinal) { ?>
        U en <?=$name; ?> worden verwacht op <strong><?=$appointment; ?></strong> en op
        <strong><?=$comb_appointment; ?>.</strong>
        <?php } else { ?>
        U en <?=$name; ?> worden verwacht op <strong><?=$appointment; ?></strong> op Janskerkhof 13a.
        <?php } ?>
        Eventuele reis- en parkeerkosten worden vergoed zoals aangegeven in de bijgevoegde informatiebrief.
    </p>
    <p>
        Wij volgen de corona-richtlijnen van het RIVM
        (<?=anchor('https://www.rivm.nl/coronavirus-covid-19','www.rivm.nl/coronavirus-covid-19'); ?>, augustus 2020).
        Daarom vragen wij uw aandacht voor de volgende punten.
    </p>
    <p>
        <strong>Gezondheidsklachten?</strong>
    </p>
    <p>
        Wij vragen u de afspraak af te zeggen wanneer u of uw kind op de dag van het experiment of in de dagen
        daarvoor last
        heeft (gehad) van:
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
        Ook als iemand in uw gezin of huishouden koorts en/of benauwdheid heeft, vragen wij u de afspraak af te
        zeggen.
    </p>

    <p>
        <strong>Aankomst in het Babylab</strong>
    </p>
    <p>
        Wij verzoeken u bij binnenkomst uw handen te desinfecteren. De onderzoeksmedewerker wacht op u vlak achter
        de hal bij de lift
        en wijst u de weg. De éénrichtingsroute naar het lab is ook aangegeven met stickers.
        Wij raden u dringend aan geen kinderwagen mee te nemen. Als u (toch) een kinderwagen meeneemt, dan moet deze
        op de begane grond achterblijven. Na afloop van uw bezoek kunt u uw kinderwagen dan weer ophalen op de
        route naar de uitgang.
        Laat geen kostbaarheden achter in de kinderwagen.
    </p>
    <p>
        <strong>Voor en na het experiment</strong>
    </p>
    <p>
        Wanneer het bij de voorbereiding of na afloop van een experiment niet mogelijk is om 1,5 meter afstand te
        houden, zullen wij in overleg met u gebruik maken van mondkapjes en/of gezichtsschermen.
        De onderzoeksmedewerker kan u in dat geval ook verzoeken een mondkapje te dragen. <em>Tijdens</em> een
        experiment is het dragen van mondkapjes of gezichtsschermen niet nodig, omdat u en de onderzoeksmedewerker
        zich dan in verschillende ruimtes
        bevinden. Het eventueel dragen van bescherming is altijd van korte duur. Mondkapjes worden door ons
        beschikbaar gesteld.
    </p>
    <p>
        Tot slot: voorafgaand aan elk experiment worden zaken als pennen, deurklinken en stoelleuningen afgenomen
        met desinfectiedoekjes.
    </p>
    <p>
        <strong>Het experiment</strong>
    </p>
    <?php if ($combination) { ?>
    <p>
        Het eerste experiment is een <?=$type; ?> en duurt maximaal <?=$duration; ?> minuten.
        Het tweede experiment is een <?=$comb_type; ?> en duurt maximaal <?=$comb_duration; ?> minuten.
        Omdat we ook de procedure uitleggen en er achteraf tijd is voor vragen, zult u ongeveer
        <?=$duration_total + $comb_duration; ?> minuten moeten reserveren voor uw bezoek aan het Babylab.</p>
    <?php } else if ($longitudinal && $duration == $comb_duration) { ?>
    <p>Het experiment is een <?=$type; ?>. Beide afspraken duren maximaal <?=$duration; ?> minuten.
        Omdat we ook de procedure uitleggen en er achteraf tijd is voor vragen, zult u ongeveer
        <?=$duration_total; ?> minuten moeten reserveren voor uw bezoek aan het Babylab.</p>
    <?php } else if ($longitudinal && $duration != $comb_duration) { ?>
    <p>Het experiment is een <?=$type; ?>. De eerste afspraak duurt maximaal <?=$duration; ?> minuten.
        De tweede afspraak duurt maximaal <?=$comb_duration; ?> minuten.
        Omdat we ook de procedure uitleggen en er achteraf tijd is voor vragen, zult u ongeveer
        <?=$duration_total; ?> minuten voor de eerste afspraak,
        en ongeveer <?=$comb_duration_total; ?> moeten reserveren voor uw tweede bezoek aan het Babylab.</p>
    <?php } else { ?>
    <p>Het experiment is een <?=$type; ?> en duurt maximaal <?=$duration; ?> minuten.
        Omdat we ook de procedure uitleggen en er achteraf tijd is voor vragen, zult u ongeveer
        <?=$duration_total; ?> minuten moeten reserveren voor uw bezoek aan het Babylab.
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