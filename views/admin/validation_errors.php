<?php defined('SYSPATH') or die('No direct script access.');?>
<?if(count($errors)):?>
<div class="alert alert-error">
    <b>Some errors appear:</b>
    <ul>
    <?foreach($errors as $k=>$err):?>
        <?if($k == '_external'):?>
            <?foreach($err as $er):?>
                <li><?php echo  $er?></li>
            <?endforeach;?>
        <?else:?>
            <li><?echo $err;?></li>
        <?endif?>
    <?endforeach;?>
    </ul>
</div>
<?endif;?>