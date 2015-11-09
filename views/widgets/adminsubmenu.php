<ul class="nav nav-tabs">
    <? foreach($menu as $name=>$menu): ?>
        <li<?php echo in_array($select, $menu) ? ' class="active"' : NULL ?>><?php echo HTML::anchor('admin/'. $menu[0], $name); ?></li>
    <? endforeach; ?>
</ul>