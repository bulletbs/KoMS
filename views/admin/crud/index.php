<?php defined('SYSPATH') or die('No direct script access.');?>

<?= HTML::anchor(URL::site($crud_uri.'/add' . URL::query()), __('Add').' ' . $item_name, array('role'=>'button', 'class'=>'btn btn-success pull-right', 'title'=>__('Add'). ' ' . $item_name)) ?>
<h3><?= $crud_name ?></h3>
<?= $pagination->render()?>
<div class="clearfix"></div>
<?if($sort_fields) echo View::factory('admin/crud/sort', array('sort_fields'=>$sort_fields))->render();?>
<?if($order_field):?><?= Form::open($crud_uri . '/setorder'. URL::query())?><?endif;?>
<div class="well">
<table class="table table-striped table-condensed">
<thead>
    <tr>
    <?foreach($list_fields as $field):?>
        <th><?= $labels[$field]?> </th>
    <?endforeach;?>
        <th><?=__('Operations')?></th>
    <?if($order_field):?><th width="1%"><?= Form::submit('save',__('Order'), array('class'=>'btn'))?></th><?endif;?>
    </tr>
</thead>
<?if(!count($items)):?>
<tr><td colspan="<?= count($list_fields)+1 ?>"><?=__('Nothing found')?></td></tr>
<?endif;?>
<? foreach($items as $item): ?>
    <tr>
        <?foreach($list_fields as $field):?>
            <td><?= $item->{$field} ?></td>
        <?endforeach;?>
        <td class="oper">
            <div class="btn-group">
            <a href="<?=URL::site($crud_uri . '/edit/'.$item->id . URL::query())?>" class='btn btn-inverse' title='<?=__('Edit')?>'><i class="glyphicon glyphicon-edit icon-white"></i></a>
            <?foreach($advanced_actions as $action):?>
                <a href="<?=URL::site($crud_uri . '/'. $action['action'] .'/'.$item->id . URL::query())?>" class='btn btn-inverse' title='<?=__($action['label'])?>'><i class="glyphicon glyphicon-<?php echo is_array($action['icon']) ? $action['icon']['values'][ $item->{$action['icon']['field']} ] : $action['icon']?> icon-white"></i></a>
            <?endforeach;?>
            <a data-bb="confirm" href="<?=URL::site($crud_uri . '/delete/'.$item->id . URL::query())?>" class='btn btn-inverse' title='<?=__('Delete')?>'><i class="icon-white glyphicon glyphicon-trash"></i></a>
            </div>
        </td>
        <?if($order_field):?><td width="1%"><?= Form::input('orders['.$item->id.']', $item->{$order_field}, array('class'=>'col-md-9'))?></td><?endif;?>
    </tr>
<? endforeach; ?>
</table>
</div>
<?if($order_field):?><?= Form::close()?><?endif;?>