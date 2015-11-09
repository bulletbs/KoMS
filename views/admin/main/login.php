<?php defined('SYSPATH') or die('No direct script access.');?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $title; ?> </title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

    <?php foreach($scripts as $script): ?>
    <?php echo  HTML::script($script)?><?php endforeach; ?>

    <?php foreach($styles as $style): ?>
    <?php echo  HTML::style($style)?><?php endforeach; ?>
    <style>
        body {
            background-color: #f5f5f5;
        }
        .form-signin{
            margin-top: 40px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row login_form">

            <form method="POST" action="" accept-charset="UTF-8" class="form-signin">
                <legend><?php echo  $title ?> site backend</legend>

                <?if(count($errors)):?>
                    <div class="alert alert-error">
                        <a class="close" data-dismiss="alert" href="#">×</a>
                        <?foreach($errors as $err):?>
                            <?echo $err;?><br>
                        <?endforeach;?>
                    </div>
                <?endif;?>
                <input type="hidden" id="goto" name="goto" value="<?php echo  $goto ?>">
                <input type="text" id="username" class="input-block-level" name="username" placeholder="Username">
                <input type="password" id="password" class="input-block-level" name="password" placeholder="Password">
                <label class="checkbox">
                    <input type="checkbox" name="remember" value="1"> Remember Me
                </label>
                <input type="submit" name="submit" value="Sign in" class="btn btn-large btn-primary">
            </form>
    </div>
</div>
</body>
</html>