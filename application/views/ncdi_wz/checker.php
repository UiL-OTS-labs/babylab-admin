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

		<?=heading('NCDI-Checker', 2); ?>

		<?=$this->session->flashdata('message'); ?>

<?=form_open_multipart($action, array('class' => 'pure-form pure-form-aligned')); ?>
<?=form_fieldset($page_title); ?>
		<input type="file" name="userfile" size="20" />

		<?=form_controls(); ?>
		<?=form_fieldset_close(); ?>
		<?=form_close(); ?>
		<?=validation_errors(); ?>