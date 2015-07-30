<?php defined('SYSPATH') or die('No direct script access.');?>

<?=Form::open(Request::$initial->url() . URL::query(), array('class'=>'', 'enctype'=>'multipart/form-data'))?>
<?if($errors) echo View::factory('admin/validation_errors', array('errors'=>$errors))->render()?>
<div class="pull-right">
    <?= Form::input('submit',__('Save'),array('type'=>'submit', 'class'=>'btn btn-primary'))?>
    <?= Form::button('submit',__('Save'),array('type'=>'submit', 'class'=>'btn btn-primary'))?>
    <?= Form::button('cancel',__('Cancel'),array('class'=>'btn'))?>
</div>
<h3><?=(isset($model->id) ? __("Edit") : __("Add"))?> <?= $item_name?></h3>
<fieldset class="well the-fieldset">
    <?foreach($form_fields as $field => $field_data):?>
    <? if($field_data['type'] == 'legend'): ?>
</fieldset>
<h3><?= __($field_data['name'])?></h3>
<fieldset class="well the-fieldset">
    <? elseif($field_data['type'] == 'checkbox'): ?>
        <div class="checkbox">
            <label>
            <?= Form::hidden($field, 0) ?>
            <?= Form::checkbox($field, 1, $model->{$field}>0 ? TRUE : FALSE) ?>
            <?= $labels[$field] ?>
            </label>
        </div>
    <?else:?>
        <div <?= isset($field_data['hidden']) ? " hide" : NULL?>" class="form-group" id="control_group_<?=$field?>">
            <?if(isset($labels[$field])):?>
                <?= Form::label($field, $labels[$field], array('class'=>'control-label', 'id'=>'control_label_'.$field)) ?>
            <?endif?>
            <? if($field_data['type'] == 'text'): ?>
            <?= Form::input($field, $model->{$field}, array('class'=>'form-control')) ?>

            <? elseif($field_data['type'] == 'password'): ?>
            <?= Form::password($field, NULL, array('class'=>'form-control')) ?>

            <? elseif($field_data['type'] == 'textarea'): ?>
            <?= Form::textarea($field, $model->{$field}, array('class'=>'form-control')) ?>

            <? elseif($field_data['type'] == 'editor'): ?>
            <?= Wysiwyg::Ckeditor($field, $model->{$field}, isset($field_data['config']) ? $field_data['config'] : 'admin') ?>

            <? elseif($field_data['type'] == 'tinymce'): ?>
            <?= Wysiwyg::TinyMCE($field, $model->{$field}, isset($field_data['config']) ? $field_data['config'] : 'admin') ?>

            <? elseif($field_data['type'] == 'file'): ?>
            <? if(isset($field_data['data'])) echo $field_data['data'] . "<br>"?>
            <?= Form::file($field, array('class'=>'span8')) ?>

            <? elseif($field_data['type'] == 'datetime'): ?>
            <div class='input-group date col-md-8' id='datetimepicker-<?=$field?>'>
                <?= Form::input($field, date("d.m.Y H:i", $model->{$field}), array('class'=>'form-control'))//?>
                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
            </div>
            <script type="text/javascript">
                $(function () {
                    $('#datetimepicker-<?=$field?>').datetimepicker({
                        'sideBySide' : true,
                        'format' : 'DD.MM.YYYY HH:mm'
                    });
                });
            </script>

            <? elseif($field_data['type'] == 'select'): ?>
            <?= Form::select($field, $field_data['data']['options'], $field_data['data']['selected'], array('id'=>'form_select_'.$field,'class'=>'form-control'))?>

            <? elseif($field_data['type'] == 'method' && method_exists($model, 'form'.ucfirst($field))): ?>
            <?= $model->{'form'.ucfirst($field)}() . "<br>" ?>

            <? elseif($field_data['type'] == 'call_view'): ?>
            <?= View::factory($field_data['data'], array('model'=>$model, 'advanced_data'=>isset($field_data['advanced_data']) ? $field_data['advanced_data'] : ''))->render()?>
            <? endif; ?>
        </div>
    <? endif; ?>
    <?endforeach;?>
</fieldset>
<div class="pull-right">
    <?= Form::button('submit',__('Save'),array('type'=>'submit', 'class'=>'btn btn-primary'))?>
    <?= Form::button('cancel',__('Cancel'),array('class'=>'btn'))?>
    <br><br>
</div>
<?=Form::close()?>