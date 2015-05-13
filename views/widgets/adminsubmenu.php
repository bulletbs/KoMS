<ul class="nav nav-tabs">
    <? foreach($menu as $name=>$menu): ?>
        <li<?=in_array($select, $menu) ? ' class="active"' : NULL ?>><?=HTML::anchor('admin/'. $menu[0], $name); ?></li>
    <? endforeach; ?>
</ul>