<?php defined('SYSPATH') or die('No direct script access.');?>
    <?=Form::open(null, array('class'=>''))?>
    <?if($errors) echo View::factory('admin/validation_errors', array('errors'=>$errors))->render()?>

    <div class="pull-right">
        <?= Form::submit('submit',__('Save'),array('class'=>'btn btn-primary'))?>
        <?= Form::submit('cancel',__('Cancel'),array('class'=>'btn'))?>
    </div>
    <h3><?=(isset($page->id) ? __("Edit") : __("Add"))?> страницу</h3>
    <fieldset class="well the-fieldset">
        <div class="form-group">
            <?= Form::label('title', __('Title'), array('class'=>'control-label')) ?>
            <?= Form::input('title', $page->title, array('class'=>'form-control')) ?>
        </div>
        <div class="form-group">
            <?= Form::label('alias', __('Alias'), array('class'=>'control-label')) ?>
            <?= Form::input('alias', $page->alias, array('class'=>'form-control')) ?>
        </div>
        <div class="form-group">
            <?= Form::label('status', __('Status'), array('class'=>'control-label')) ?>
            <?= Form::checkbox('status', 1, $page->status==1) ?>
        </div>

        <div class="form-group">
            <?= Form::label('text', __('Text on page'), array('class'=>'control-label')) ?>
            <?= Wysiwyg::Ckeditor('text', $page->text, 'admin') ?>
        </div>
    </fieldset>
    <div class="pull-right">
        <?= Form::submit('submit',__('Save'),array('class'=>'btn btn-primary'))?>
        <?= Form::submit('cancel',__('Cancel'),array('class'=>'btn'))?>
    </div>
    <?=Form::close()?>