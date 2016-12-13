<?php defined('SYSPATH') or die('No direct script access.');?>

<?php echo  HTML::anchor(URL::site($crud_uri.'/add' . URL::query()), __('Add').' ' . $item_name, array('role'=>'button', 'class'=>'btn btn-success pull-right', 'title'=>__('Add'). ' ' . $item_name)) ?>
<h3><?php echo  $crud_name ?></h3>
<?php echo  $pagination->render()?>
<div class="clearfix"></div>
<?if($filter_fields) echo View::factory('admin/crud/filters', array('filter_fields'=>$filter_fields))->render();?>
<?if($order_field && !count($multi_operations)):?><?php echo  Form::open($crud_uri . '/setorder'. URL::query())?><?endif;?>
<?if(count($multi_operations)):?>
    <?php echo Form::open(URL::site($crud_uri .'/multi') . URL::query())?>
    <div class="pull-right">
    <?foreach($multi_operations as $_operation=>$_operation_name):?>
        <?php echo Form::button($_operation, __($_operation_name), array('class'=>'btn btn-danger', 'data-bb'=>'confirm'))?>
    <?endforeach;?>
    </div>
    <div class="clearfix"></div><br>
<?endif;?>
<div class="well">
<table class="table table-striped table-condensed">
<thead>
    <tr>
    <?foreach($list_fields as $field):?>
        <th><?if(isset($sort_fields[$field])):?>
        <a href="<?php echo URL::base() . $crud_uri . URL::query(array('orderby'=>$field, 'orderdir'=>'DESC'))?>"><span class="glyphicon glyphicon-arrow-up"></span></a><?php echo $labels[$field]?><a href="<?php echo URL::base() . $crud_uri . URL::query(array('orderby'=>$field, 'orderdir'=>'ASC'))?>"><span class="glyphicon glyphicon-arrow-down"></span></a>
        <?else:?><?php echo  $labels[$field]?><?endif?></th>
    <?endforeach;?>
        <th><?php echo __('Operations')?></th>
    <?if($order_field):?><th width="1%"><?php echo  Form::submit('save',__('Order'), array('class'=>'btn'))?></th><?endif;?>
    <?if(count($multi_operations)):?><th width="1%"><input type="checkbox" value="1" id="toggle_checkbox"></th><?endif;?>
    </tr>
</thead>
<?if(!count($items)):?>
<tr><td colspan="<?php echo  count($list_fields)+1 ?>"><?php echo __('Nothing found')?></td></tr>
<?endif;?>
<? foreach($items as $item): ?>
    <tr>
        <?foreach($list_fields as $field):?>
            <td><?php echo  $item->{$field} ?></td>
        <?endforeach;?>
        <td class="oper">
            <div class="btn-group">
            <a href="<?php echo URL::site($crud_uri . '/edit/'.$item->id . URL::query())?>" class='btn btn-inverse' title='<?php echo __('Edit')?>'><i class="glyphicon glyphicon-edit icon-white"></i></a>
            <?foreach($advanced_actions as $action):?>
                <a href="<?php echo URL::site($crud_uri . '/'. $action['action'] .'/'.$item->id . URL::query())?>" class='btn btn-inverse' title='<?php echo __($action['label'])?>' target="<?php echo Arr::get($action, 'target')?>"><i class="glyphicon glyphicon-<?php echo is_array($action['icon']) ? $action['icon']['values'][ $item->{$action['icon']['field']} ] : $action['icon']?> icon-white"></i></a>
            <?endforeach;?>
            <a data-bb="confirm" href="<?php echo URL::site($crud_uri . '/delete/'.$item->id . URL::query())?>" class='btn btn-inverse' title='<?php echo __('Delete')?>'><i class="icon-white glyphicon glyphicon-trash"></i></a>
            </div>
        </td>
        <?if($order_field):?><td width="1%"><?php echo  Form::input('orders['.$item->id.']', $item->{$order_field}, array('class'=>'col-md-9'))?></td><?endif;?>
        <?if(count($multi_operations)):?><td><input type="checkbox" name="operate[]" value="<?php echo $item->id?>"></td><?endif;?>
    </tr>
<? endforeach; ?>
</table>
</div>
<?if($order_field):?><?php echo  Form::close()?><?endif;?>
<?php echo  $pagination->render()?>
