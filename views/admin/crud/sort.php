<?php defined('SYSPATH') or die('No direct script access.');?>
<?=Form::open(null, array('method'=>'get', 'class'=>'well form-inline', 'enctype'=>'multipart/form-data', 'id'=>'sortForm'))?>
<?foreach($sort_fields as $field => $field_data):?>
    <?= Form::label($field, $field_data['label'], array('class'=>'control-label')) ?>

    <? if($field_data['type'] == 'text'): ?>
        <?= Form::input($field, $field_data['data'], array('id'=>'sortValue')) ?>

    <? elseif($field_data['type'] == 'checkbox'): ?>
        <?= Form::checkbox($field, 1, $model->{$field}>0 ? TRUE : FALSE, array('id'=>'sortValue')) ?>

    <? elseif($field_data['type'] == 'select'): ?>
        <?= Form::select($field, $field_data['data']['options'], $field_data['data']['selected'], array('id'=>'sortValue'))?>

    <? endif; ?>
    &nbsp;
<?endforeach;?>
<?= Form::button('submit',__('Filter'),array('class'=>'btn','onclick'=>"$('#sortForm').submit();"))?>
<?=Form::close()?>
