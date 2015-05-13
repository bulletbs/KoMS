<?php defined('SYSPATH') or die('No direct script access.');?>
<h2><?php echo __('Comments')?></h2>

<?= $pagination->render()?>

<?=Form::open(null, array('method'=>'get', 'class'=>'well form-inline', 'enctype'=>'multipart/form-data', 'id'=>'sortForm'))?>
    <?= Form::label('comment_sort', __('Show comments'), array('class'=>'control-label')) ?>
    <?= Form::select('comment_sort', $sorts, $comment_sort)?>
    <?= Form::submit('submit',__('Filter'),array('class'=>'btn'))?>
<?=Form::close()?>

<?php echo Form::open(URL::site( 'admin/comments/multi'))?>
<?if(count($comments) || !$comment_sort):?>
<div class="pull-right">
    <?if(!$comment_sort):?><?php echo HTML::anchor('admin/comments/checkall', __('Check all'), array('class'=>'btn btn-primary'))?><?endif?>
    <?if(count($comments)):?><?php echo Form::button('delete_all', __('Delete selected'), array('class'=>'btn btn-danger', 'data-bb'=>'confirm'))?><?endif?>
    <?if(!$comment_sort && count($comments)):?><?php echo Form::button('check_all', __('Check selected'), array('class'=>'btn btn-success'))?><?endif?>
</div>
<div class="clearfix"></div>
<div class="row">&nbsp;</div>
<?endif;?>

<div class="well">
<table class="table table-striped">
<thead>
    <tr>
        <th>ID</th>
        <th><?php echo __('Comment content')?></th>
        <th><?php echo __('Operations')?></th>
        <th><input type="checkbox" value="1" id="toggle_checkbox"></th>
    </tr>
</thead>
<?if(!count($comments)):?>
    <tr><td colspan="4"><?php echo __('Nothing found')?></td></tr>
<?endif;?>
<? foreach($comments as $comment): ?>
    <tr>
        <td><?=$comment->id?></td>
        <td><?= $comment->getUri() ? HTML::anchor($comment->getUri(), $comment->content, array('target'=>'_blank')) : $comment->content ?></td>
        <td style="width: 150px;">
            <div class="btn-group">
            <a href="<?=URL::site( 'admin/comments/edit/'.$comment->id . URL::query())?>" class='btn btn-inverse' title='<?=__('Edit')?>'><i class="glyphicon glyphicon-edit"></i></a>
            <a data-bb="confirm" href="<?=URL::site( 'admin/comments/delete/'.$comment->id . URL::query())?>" class='btn btn-inverse' title='<?=__('Delete')?>'><i class="glyphicon glyphicon-trash"></i></a>
            <?if(!$comment->moderated):?><a href="<?=URL::site( 'admin/comments/check/'.$comment->id . URL::query())?>" class='btn btn-inverse' title='<?=__('Moderate')?>'><i class="glyphicon glyphicon-check"></i></a><?endif?>
            </div>
        </td>
        <td><input type="checkbox" name="operate[]" value="<?php echo $comment->id?>"></td>
    </tr>
<? endforeach; ?>
</table>
<?php echo Form::close()?>
</div>