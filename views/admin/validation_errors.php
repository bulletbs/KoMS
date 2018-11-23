<?php defined('SYSPATH') or die('No direct script access.');?>
<?if(count($errors)):?>
<div class="alert alert-danger">
    <b><?php echo __('Some errors appear'); ?>:</b>
    <ul>
    <?foreach($errors as $k=>$err):?>
        <?if(is_array($err)):?>
            <?foreach(isset($err['_external']) ? $err['_external'] : $err as $er):?>
                <li><?php echo  $er?></li>
            <?endforeach;?>
        <?else:?>
            <li><?echo $err;?></li>
        <?endif?>
    <?endforeach;?>
    </ul>
</div>
<?endif;?>