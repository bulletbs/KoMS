

<nav class="navbar navbar-default navbar-inverse" role="navigation">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="/" target="_blank"><?php echo  $menutitle ?></a>
    </div>
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav">
            <li><a href="<?php echo  URL::site('admin')?>"><i class="glyphicon glyphicon-home icon-white"></i> <?php echo __('Home')?></a></li>
            <?php foreach($menu as $_menu): ?>
                <?php if(isset($_menu['role']) && Auth::instance()->logged_in($_menu['role'])) continue; ?>
                <?if(isset($_menu['submenu']) && count($_menu['submenu'])):?>
                    <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#"><?if(isset($_menu['icon'])):?><i class="glyphicon glyphicon-<?php echo $_menu['icon']?> icon-white"></i> <?endif?><?php echo  $_menu['label']?> <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <?foreach($_menu['submenu'] as $_submenu):?>
                                <li><?php echo HTML::anchor('admin/'. $_submenu['controller'], (isset($_menu['icon']) ? '<i class="icon-'.$_menu['icon'].' icon-black"></i> ' : '') . $_submenu['label'])?></li>
                            <?endforeach?></ul>
                    </li>
                <?else:?>
                    <li><a href="<?php echo  URL::site('admin/'. $_menu['controller'])?>"><?php echo isset($_menu['icon']) ? '<i class="glyphicon glyphicon-'.$_menu['icon'].' icon-white"></i> ' : ''?><?php echo  $_menu['label']?></a></li>
                <?endif?>
            <? endforeach; ?>
        </ul>
        <ul class="nav navbar-nav navbar-right">
            <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo __('Welcome')?>, <?php echo $current_user_username?> <b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li><a href="<?php echo  URL::site('admin/main/clear')?>"><i class="icon-cog"></i> <?php echo __('Clear cache')?></a></li>
                    <li><a href="<?php echo  URL::site('admin/main/clearcache')?>"><i class="icon-cog"></i> <?php echo __('Clear cache only')?></a></li>
                    <li><a href="<?php echo  URL::site('admin/main/clearstyle')?>"><i class="icon-cog"></i> <?php echo __('Clear styles only')?></a></li>
                    <?if(class_exists('BoardCache')):?><li><a href="<?php echo  URL::site('admin/main/clearboard')?>"><i class="icon-cog"></i> <?php echo __('Clear board cache')?></a></li><?endif?>
<!--                    <li><a href="--><?php //echo  URL::site('admin/main/settings')?><!--"><i class="icon-cog"></i> --><?php //echo __('Settings')?><!--</a></li>-->
                    <li class="divider"></li>
                    <li><a href="<?php echo  URL::site('admin/logout')?>"><i class="icon-off"></i> <?php echo __('Logout')?></a></li>
                </ul>
            </li>
        </ul>
    </div>
</nav>
