<h2>
    Contract: "Omgang met vertrouwelijke data"
</h2>
<p>
    In het Babylab wordt door studenten – meestal in het kader van een (onderzoeks)stage of in het kader van een bachelor-/masterthesis – veel gewerkt met persoonlijke gegevens van kinderen en hun ouders. Omdat het hierbij veelal gaat om zeer vertrouwelijke data, zijn er bepaalde regels en wetten van toepassing op het werk van de student in de omgang met deze data. Ter bescherming van de student, de medewerkers en de participanten, worden er middels dit contract enkele afspraken gemaakt om de privacy van de kinderen en hun ouders te waarborgen en de materialen/gegevens van de onderzoeksgroep te beschermen.
</p>
<p>
    <strong>Ik, <?=$user_full_name; ?>, verklaar hierbij:</strong>
</p>
<div>
    <p>
        De privacy van kinderen en hun ouders te allen tijde te respecteren en te waarborgen door:
    </p>
    <ul>
        <li>
            Geen persoonlijke gegevens mee te nemen naar locaties buiten de UU (dossiers, gegevens op memory stick, kopieën van documenten en andere persoonlijke gegevens).
        </li>
        <li>
            Geen persoonlijke gegevens of databestanden over de mail te versturen.
        </li>
        <li>
            De beroepscode van het <a href="https://issuu.com/communicatienip/docs/150024_beroepscode_bw_def_p" target="_blank">NIP</a> of de <a href="https://www.nvo.nl/bestanden/Beroepscode/840-1/Beroepscode_web.pdf" target="_blank">NVO</a> in acht te nemen door bijvoorbeeld niet te spreken over kinderen met derden of in gelegenheden buiten de UU.
        </li>
        <li>
            Al het andere te doen dat in de macht van de student ligt om de privacy van de kinderen te bewaren.
        </li>
    </ul>
    <button class="pure-button pure-button-primary">
        Ik ga akkoord
    </button>
</div>
<div style="display: none;">
    <p>
        De gegevens en materialen van medewerkers en afdeling te beschermen door:
    </p>
    <ul>
        <li>
            Geen gegevens of materialen anders dan nodig voor de (onderzoeks)stage/thesis te gebruiken.
        </li>
        <li>
            Materialen van het onderzoek niet voor privédoeleinden te gebruiken. 
        </li>
        <li>
            Kamers en kasten altijd zorgvuldig af te sluiten en het onderzoeksmateriaal in een afgesloten kast op de UU te bewaren. 
        </li>
    </ul>
    <button class="pure-button pure-button-primary">
        Ik ga akkoord
    </button>
</div>
<div style="display: none;">
    <p>
        De onderzoeksgegevens zorgvuldig te verwerken opdat het een betrouwbaar en publiceerbaar onderzoek wordt door:
    </p>
    <ul>
        <li>
            De gegevens zorgvuldig in te voeren en het altijd te melden als ik merk dat ik fouten heb gemaakt bij het invoeren of analyseren van de data. De student draagt zorg voor de kwaliteit van de onderzoeksgegevens. NB: De onderzoeker draagt er zorg voor dat de student het project met een voldoende kan voltooien ook als de student merkt en meldt dat hij/zij fouten heeft gemaakt (tenzij er sprake is van opzet of grove nalatigheid). De student hoeft dus niet te vrezen voor een onvoldoende of voor tijdverlies.
        </li>
        <li>
            De gegevens die ik gebruik voor het schrijven van een onderzoeksverslag of scriptie, bij voorkeur in de vorm van een artikel, nergens anders ter publicatie zal aanbieden dan aan mijn begeleiders van de UU. Ik publiceer of rapporteer niet over het onderzoek zonder de uitdrukkelijke toestemming van mijn begeleiders van de UU.
        </li>
    </ul>
    <button class="pure-button pure-button-primary">
        Ik ga akkoord
    </button>
</div>
<div style="display: none;">
    <h3>
        Ondertekening
    </h3>
    <table class="pure-table" style="margin-bottom: 10px;">
        <tbody>
            <tr>
                <th>Naam:</th>
                <td><?=$user_full_name; ?></td>
            </tr>
            <tr>
                <th>Datum:</th>
                <td><?=output_date(); ?></td>
            </tr>
            <tr>
                <th>Plaats:</th>
                <td>Utrecht</td>
            </tr>
        </tbody>
    </table>
    <a href="<?=$action; ?>" class="pure-button pure-button-primary">
        Ik ga akkoord
    </a>
</div>

<script>
$(function() {
    // On click of a button, show the next div
    $("button").click(function() {
        $(this).parent('div').next().show();
    });
});
</script>
