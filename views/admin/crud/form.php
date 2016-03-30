<?php defined('SYSPATH') or die('No direct script access.');?>

<?php echo Form::open(Request::$initial->url() . URL::query(), array('class'=>'', 'enctype'=>'multipart/form-data'))?>
<?if($errors) echo View::factory('admin/validation_errors', array('errors'=>$errors))->render()?>
<?php echo Form::hidden('referer', Request::current()->referrer()) ?>
<div class="pull-right">
    <?php echo  Form::input('submit',__('Save'),array('type'=>'submit', 'class'=>'btn btn-primary'))?>
    <?php echo  Form::button('cancel',__('Cancel'),array('class'=>'btn'))?>
</div>
<h3><?php echo (isset($model->id) ? __("Edit") : __("Add"))?> <?php echo  $item_name?></h3>
<fieldset class="well the-fieldset">
    <?foreach($form_fields as $field => $field_data):?>
    <? if($field_data['type'] == 'legend'): ?>
</fieldset>
<h3><?php echo  __($field_data['name'])?></h3>
<fieldset class="well the-fieldset">
    <? elseif($field_data['type'] == 'checkbox'): ?>
        <div class="checkbox">
            <label>
            <?php echo  Form::hidden($field, 0) ?>
            <?php echo  Form::checkbox($field, 1, $model->{$field}>0 ? TRUE : FALSE) ?>
            <?php echo  $labels[$field] ?>
            </label>
        </div>
    <?else:?>
    <div <?php echo  isset($field_data['hidden']) ? " hide" : NULL?>" class="form-group" id="control_group_<?php echo $field?>">
        <?if(isset($labels[$field])):?>
            <?php echo  Form::label($field, $labels[$field], array('class'=>'control-label', 'id'=>'control_label_'.$field)) ?>
        <?endif?>

        <? if($field_data['type'] == 'text'): ?>
        <?php echo  Form::input($field, $model->{$field}, array('class'=>'form-control')) ?>

        <? elseif($field_data['type'] == 'digit'): ?>
        <?php echo  Form::input($field, $model->{$field}, array('class'=>'form-control input_short')) ?>

        <? elseif($field_data['type'] == 'password'): ?>
        <?php echo  Form::password($field, NULL, array('class'=>'form-control')) ?>

        <? elseif($field_data['type'] == 'textarea'): ?>
        <?php echo  Form::textarea($field, $model->{$field}, array('class'=>'form-control')) ?>

        <? elseif($field_data['type'] == 'editor'): ?>
        <?php echo  Wysiwyg::Ckeditor($field, $model->{$field}, isset($field_data['config']) ? $field_data['config'] : 'admin') ?>

        <? elseif($field_data['type'] == 'tinymce'): ?>
        <?php echo  Wysiwyg::TinyMCE($field, $model->{$field}, isset($field_data['config']) ? $field_data['config'] : 'admin') ?>

        <? elseif($field_data['type'] == 'file'): ?>
        <? if(isset($field_data['data'])) echo $field_data['data'] . "<br>"?>
        <?php echo  Form::file($field, array('class'=>'span8')) ?>

        <? elseif($field_data['type'] == 'datetime'): ?>
        <div class='input-group date col-md-8' id='datetimepicker-<?php echo $field?>'>
            <?php echo  Form::input($field, date("d.m.Y H:i", $model->{$field}), array('class'=>'form-control'))//?>
            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
        </div>
        <script type="text/javascript">
            $(function () {
                $('#datetimepicker-<?php echo $field?>').datetimepicker({
                    'sideBySide' : true,
                    'format' : 'DD.MM.YYYY HH:mm'
                });
            });
        </script>

        <? elseif($field_data['type'] == 'select'): ?>
        <?php echo  Form::select($field, $field_data['data']['options'], $field_data['data']['selected'], array('id'=>'form_select_'.$field,'class'=>'form-control'))?>

        <? elseif($field_data['type'] == 'method' && method_exists($model, 'form'.ucfirst($field))): ?>
        <?php echo  $model->{'form'.ucfirst($field)}() . "<br>" ?>

        <? elseif($field_data['type'] == 'call_view'): ?>
        <?php echo  View::factory($field_data['data'], array('model'=>$model, 'advanced_data'=>isset($field_data['advanced_data']) ? $field_data['advanced_data'] : ''))->render()?>
        <? endif; ?>
    </div>
    <? endif; ?>
    <?endforeach;?>
</fieldset>
<div class="pull-right">
    <?php echo  Form::button('submit',__('Save'),array('type'=>'submit', 'class'=>'btn btn-primary'))?>
    <?php echo  Form::button('cancel',__('Cancel'),array('class'=>'btn'))?>
    <br><br>
</div>
<?php echo Form::close()?>