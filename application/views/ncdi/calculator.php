<?=doctype(); ?>
<html lang="en">
<head>
<base href="<?=base_url(); ?>">
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?=lang('babylab'); ?></title>
<!-- Common JQuery -->
<script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
<!-- Google JS API -->
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<!-- (default) CSS -->
<?=link_tag('css/pure-min.css'); ?>
<?=link_tag('css/style.css'); ?>
</head>
<body>

<div class="pure-g">
	<div class="pure-u-1-8"></div>
	<div id="wrapper" class="pure-u-3-4">

		<img id="header-img" src="images/uu-header.png">
		<?=heading(lang('babylab'), 1); ?>
		<?=heading('NCDI-Calculator', 2); ?>
		
		Met deze applicatie kun je de NCDI-percentielen en taalleeftijden laten berekenen. 
		<ul>
		<li>De applicatie verwacht als input een <em>.csv</em>-bestand, gescheiden door ofwel komma's (,) of puntkomma's (;).</li>
		<li>Zo'n bestand kun je laten uitvoeren door een spreadsheetprogramma als Microsoft Excel of LibreOffice (kies <em>Opslaan als...</em>).</li>
		<li>De applicatie verwacht de volgende kolommen als invoer:
		<?=ol(array('proefpersoon', 'geboortedatum', 'datum invullen', 'geslacht (M/F)',
			'totaalscore begrip', 'totaalscore productie', 'totaalscore woordvormen', 'totaalscore zinnen'))?>
		</li>
		<li>De uitvoer van deze applicatie is een <em>.csv</em>-bestand, dat je weer kunt inlezen met een spreadsheetprogramma.</li>
		<li>De applicatie geeft de volgende kolommen als uitvoer:		
		<?=ol(array('proefpersoon', 'leeftijd in maanden', 'leeftijd in maanden en dagen (m;d)',
			'percentielscore begrip', 'taalleeftijd begrip',
			'percentielscore productie', 'taalleeftijd productie',
			'percentielscore woordvormen', 'taalleeftijd woordvormen',
			'percentielscore zinnen', 'taalleeftijd zinnen'))?>
		</li>
		</ul>
		
		<?=$this->session->flashdata('message'); ?>
		<?=form_open_multipart($action, array('class' => 'pure-form pure-form-aligned')); ?>
		<?=form_fieldset($page_title); ?>
		<div class="pure-control-group">
		<?=form_label('Scheidingsteken', 'separator'); ?>
		<?=form_radio_and_label('separator', ';', NULL, ';', TRUE); ?>
		<?=form_radio_and_label('separator', ',', NULL, ','); ?>
		</div>
		<div class="pure-control-group">
		<?=form_label('Bestand', 'userfile'); ?>
		<input type="file" name="userfile" size="20" class="pure-input-rounded" />
		</div>

		<?=form_controls(); ?>
		<?=form_fieldset_close(); ?>
		<?=form_close(); ?>
		<?=validation_errors(); ?>