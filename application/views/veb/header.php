<?=doctype(); ?>
<html lang="en">
<head>
    <base href="<?=base_url(); ?>">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=lang('babylab'); ?></title>
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
          <hr>
          