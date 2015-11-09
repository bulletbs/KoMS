<?php defined('SYSPATH') or die('No direct script access.');?>
    <?php echo Form::open(null, array('class'=>''))?>
    <?if($errors) echo View::factory('admin/validation_errors', array('errors'=>$errors))->render()?>

    <div class="pull-right">
        <?php echo  Form::submit('submit',__('Save'),array('class'=>'btn btn-primary'))?>
        <?php echo  Form::submit('cancel',__('Cancel'),array('class'=>'btn'))?>
    </div>
    <h3><?php echo (isset($page->id) ? __("Edit") : __("Add"))?> страницу</h3>
    <fieldset class="well the-fieldset">
        <div class="form-group">
            <?php echo  Form::label('name', __('Name'), array('class'=>'control-label')) ?>
            <?php echo  Form::input('name', $page->name, array('class'=>'form-control')) ?>
        </div>
        <div class="form-group">
            <?php echo  Form::label('alias', __('Alias'), array('class'=>'control-label')) ?>
            <?php echo  Form::input('alias', $page->alias, array('class'=>'form-control')) ?>
        </div>
        <div class="form-group">
            <?php echo  Form::label('status', __('Status'), array('class'=>'control-label')) ?>
            <?php echo  Form::checkbox('status', 1, $page->status==1) ?>
        </div>

        <div class="form-group">
            <?php echo  Form::label('text', __('Text on page'), array('class'=>'control-label')) ?>
            <?php echo  Wysiwyg::Ckeditor('text', $page->text, 'admin') ?>
        </div>
    </fieldset>
    <fieldset class="well the-fieldset">
        <div class="form-group">
        <legend>Meta tags</legend>
        <?php echo  Form::label('title', __('Title'), array('class'=>'control-label')) ?>
        <?php echo  Form::input('title', $page->title, array('class'=>'form-control')) ?>
        </div>
        <div class="form-group">
        <?php echo  Form::label('description', __('Description'), array('class'=>'control-label')) ?>
        <?php echo  Form::input('description', $page->description, array('class'=>'form-control')) ?>
        </div>
        <div class="form-group">
        <?php echo  Form::label('keywords', __('Keywords'), array('class'=>'control-label')) ?>
        <?php echo  Form::input('keywords', $page->keywords, array('class'=>'form-control')) ?>
        </div>
    </fieldset>
    <div class="pull-right">
        <?php echo  Form::submit('submit',__('Save'),array('class'=>'btn btn-primary'))?>
        <?php echo  Form::submit('cancel',__('Cancel'),array('class'=>'btn'))?>
    </div>
    <?php echo Form::close()?>