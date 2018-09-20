<?php defined('SYSPATH') or die('No direct script access.');?>
<!DOCTYPE HTML>
<html>
<head>
    <title><?php echo $title; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <?php foreach($styles as $style): ?>
    <?php echo  HTML::style($style); ?>
    <?php endforeach; ?>
    <?php foreach($scripts as $script): ?>
    <?php echo  HTML::script($script); ?>
    <?php endforeach; ?>
</head>
<body>
<div id="wrapper">
    <div id="modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="bannerformmodal" aria-hidden="true"></div>
    <div id="notify" class="notifications top-right"></div>
    <div class="loading" id="loading"><?php echo __('Loading')?>...</div>
    <div class="container">
        <?php echo  $menu ?>

        <?if(isset($submenu)):?><?php echo  $submenu ?><?endif?>

        <?php echo  Flash::render() ?>
        <?php echo  $content?>

    </div>
    <div id="push"></div>
</div>
<div id="footer">
    <div class="container">
        <small class="muted">2012-<?php echo date('Y')?> &copy; Created at Bullet Factory</small><br>
        <small class="muted">Powered by <?php echo HTML::anchor('http://kohanaframework.org/', 'Kohana Framework')?></small>
    </div>
</div>
</body>
</html>