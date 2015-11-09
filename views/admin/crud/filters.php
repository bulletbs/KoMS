<?php defined('SYSPATH') or die('No direct script access.');?>
<?php echo Form::open(null, array('method'=>'get', 'class'=>'well form-inline', 'enctype'=>'multipart/form-data', 'id'=>'sortForm'))?>
<?foreach($filter_fields as $field => $field_data):?>
    <? if($field_data['type'] == 'nl'): ?>
        <hr class="sort_hr">
        <?continue?>
    <?endif?>
    <div class="form-group">
    <?php echo  Form::label($field, $field_data['label'], array('class'=>'control-label')) ?>

    <? if($field_data['type'] == 'digit'): ?>
        <?php echo  Form::input($field, $field_data['data'], array('class'=>'form-control input_short')) ?>
    <? elseif($field_data['type'] == 'text'): ?>
        <?php echo  Form::input($field, $field_data['data'], array('class'=>'form-control input_medium')) ?>
    <? elseif($field_data['type'] == 'checkbox'): ?>
        <?php echo  Form::checkbox($field, 1, $model->{$field}>0 ? TRUE : FALSE, array('class'=>'form-control')) ?>
    <? elseif($field_data['type'] == 'select'): ?>
        <?php echo  Form::select($field, $field_data['data']['options'], $field_data['data']['selected'], array('class'=>'form-control input_long'))?>
    <? endif; ?>
    &nbsp;
    </div>
<?endforeach;?>
<?php echo  Form::button('submit',__('Filter'),array('class'=>'btn pull-right','onclick'=>"$('#sortForm').submit();"))?>
<?php echo Form::close()?>
