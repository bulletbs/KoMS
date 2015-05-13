<?php defined('SYSPATH') or die('No direct script access.');?>
<?if(count($errors)):?>
    <div class="pure-alert pure-alert-warning">
        <b><?php echo __('An error occurred')?>:</b>
        <ul>
            <?foreach($messages as $k=>$err):?>
                <?if($k == '_external' && is_array($err)):?>
                    <?foreach($err as $er):?>
                        <li><?= $er ?></li>
                    <?endforeach;?>
                <?else:?>
                    <li><?echo $err;?></li>
                <?endif?>
            <?endforeach;?>
        </ul>
    </div>
<?endif;?>