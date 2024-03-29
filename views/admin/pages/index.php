    <?php defined('SYSPATH') or die('No direct script access.');?>
<?php echo  HTML::anchor(URL::site('admin/pages/add'), __('Add page'), array('class'=>'btn btn-success pull-right', 'title'=>'Add page')) ?>
<h2><?php echo __('Static pages')?></h2>

<?php echo  $pagination->render()?>

<div class="well">
<table class="table table-striped">
<thead>
    <tr>
        <th>ID</th>
        <th><?php echo __('Name')?></th>
        <th><?php echo __('Alias')?></th>
        <th class="pull-right"><?php echo __('Operations')?></th>
    </tr>
</thead>
<?if(!count($pages)):?>
    <tr><td colspan="4"><?php echo __('Nothing found')?></td></tr>
<?endif;?>
<? foreach($pages as $page): ?>
    <tr>
        <td><?php echo $page->id?></td>
        <td><?php echo  HTML::anchor($page->getUri(), $page->name, array('target' => '_blank'))?></td>
        <td><?php echo $page->alias?></td>
        <td>
            <div class="btn-group pull-right">
            <a href="<?php echo URL::site( 'admin/pages/edit/'.$page->id . URL::query())?>" class='btn btn-inverse' title='<?php echo __('Edit')?>'><i class="glyphicon glyphicon-edit"></i></a>
            <a data-bb="confirm" href="<?php echo URL::site( 'admin/pages/delete/'.$page->id . URL::query())?>" class='btn btn-inverse' title='<?php echo __('Delete')?>'><i class="glyphicon glyphicon-trash"></i></a>
            <a href="<?php echo URL::site( 'admin/pages/status/'.$page->id . URL::query())?>" class='btn btn-inverse' title='<?php echo __('View')?>'><i class="glyphicon glyphicon-eye-<?php echo ($page->status == 0 ? 'close' : 'open')?>"></i></a>
            </div>
        </td>
    </tr>
<? endforeach; ?>
</table>
</div>