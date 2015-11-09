<?php defined('SYSPATH') or die('No direct script access.');?>
    <legend><?php echo __('Edit comment')?></legend>
    <?php echoForm::open(null, array('class'=>''))?>
    <?if($errors) echo View::factory('admin/validation_errors', array('errors'=>$errors))->render()?>

    <div class="pull-right">
        <?php echo Form::submit('submit',__('Save'),array('class'=>'btn btn-primary'))?>
        <?php echo Form::submit('cancel',__('Cancel'),array('class'=>'btn'))?>
    </div>
    <div class="clearfix"></div>
    <fieldset class="well the-fieldset">
        <?if($comment->author):?>
            <strong><?php echo __('Username')?>:</strong> <?php echo HTML::anchor('/admin/users/edit/'.$comment->author->id, $comment->author->profile->name, array('target'=>'_blank'))?><br><br>
        <?else:?>
            <div class="form-group">
                <?php echo  Form::label('username', __('Username'), array('class'=>'control-label')) ?>
                <?php echo  Form::input('username', $comment->username, array('class'=>'form-control')) ?>
            </div>
        <?endif?>
        <div class="form-group">
            <?php echo  Form::label('moderated', __('Moderated'), array('class'=>'control-label')) ?>
            <?php echo  Form::checkbox('moderated', 1, $comment->moderated==1) ?>
        </div>
        <div class="form-group">
            <?php echo  Form::label('content', __('Content'), array('class'=>'control-label')) ?>
            <?php echo  Form::textarea('content', $comment->content, array('class'=>'form-control')) ?>
        </div>
    </fieldset>
    <div class="pull-right">
        <?php echo  Form::submit('submit',__('Save'),array('class'=>'btn btn-primary'))?>
        <?php echo  Form::submit('cancel',__('Cancel'),array('class'=>'btn'))?>
    </div>
    <?php echo Form::close()?>