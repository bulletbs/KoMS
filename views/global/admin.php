<?php defined('SYSPATH') or die('No direct script access.');?>
<!DOCTYPE HTML>
<html>
<head>
    <title><?php echo $title; ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <?php foreach($styles as $style): ?>
    <?= HTML::style($style); ?>
    <?php endforeach; ?>
    <?php foreach($scripts as $script): ?>
    <?= HTML::script($script); ?>
    <?php endforeach; ?>
</head>
<body>
<div id="wrapper">
    <div class="loading" id="loading"><?=__('Loading')?>...</div>
    <div class="container">
        <?= $menu ?>

        <?if(isset($submenu)):?><?= $submenu ?><?endif?>

        <?= Flash::render() ?>
        <?= $content?>

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