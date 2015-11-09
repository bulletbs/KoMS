<?php defined('SYSPATH') or die('No direct script access.');?>
<h2>Новые записи на <?php echo  $name?></h2>

<table class="table .table-striped">
    <tr><td><?php echo  HTML::anchor(URL::site('admin/comments'), 'Новые комментарии')?></td><td><?php echo  HTML::anchor(URL::site('main/moderate'), $moderate_comments)?></td></tr>
    <?if(isset($modules['catalog'])):?>
        <tr><td><?php echo  HTML::anchor(URL::site('admin/catalogModerate'), 'Новые компании')?></td><td><?php echo  HTML::anchor(URL::site('admin/catalogModerate'), $moderate_catalog)?></td></tr>
    <?endif?>
    <?if(isset($modules['board'])):?>
        <tr><td><?php echo  HTML::anchor(URL::site('admin/boardModerate'), 'Новые объявления')?></td><td><?php echo  HTML::anchor(URL::site('admin/boardModerate'), $moderate_board)?></td></tr>
    <?endif?>
</table>

<h2>Количество записей в разделах</h2>
<table class="table .table-striped">
    <tr><td><?php echo  HTML::anchor(URL::site('admin/pages'), 'Страниц')?></td><td><?php echo  HTML::anchor(URL::site('admin/catalog'), $counters['pages'])?></td></tr>
    <tr><td><?php echo  HTML::anchor(URL::site('admin/users'), 'Пользователей')?></td><td><?php echo  HTML::anchor(URL::site('admin/catalog'), $counters['users'])?></td></tr>
    <?if(isset($modules['news'])):?>
        <tr><td><?php echo  HTML::anchor(URL::site('admin/news'), 'Новости')?></td><td><?php echo  HTML::anchor(URL::site('admin/catalog'), $counters['news'])?></td></tr>
    <?endif?>
    <?if(isset($modules['catalog'])):?>
        <tr><td><?php echo  HTML::anchor(URL::site('admin/catalog'), 'Компании')?></td><td><?php echo  HTML::anchor(URL::site('admin/catalog'), $counters['catalog'])?></td></tr>
    <?endif?>
    <?if(isset($modules['board'])):?>
        <tr><td><?php echo  HTML::anchor(URL::site('admin/board'), 'Объявления')?></td><td><?php echo  HTML::anchor(URL::site('admin/board'), $counters['board'])?></td></tr>
    <?endif?>
</table>
