<?php defined('SYSPATH') or die('No direct script access.');
/*
 * Виджет "Меню админа"
 */
class Controller_Widgets_Adminmainmenu extends Controller_System_Widgets {

    public $template = 'widgets/adminmenu';    // Шаблон виждета

    public function action_index()
    {
        $menu = Kohana::$config->load('koms')->admin_menu;

        $current_user = NULL;
        if($this->current_user)
            $current_user = !empty($this->current_user->profile->name) ? $this->current_user->profile->name : $this->current_user->username;
        $this->template->bind('current_user_username', $current_user);
        $this->template->set('menutitle', $this->config['project']['name']);
        $this->template->set('menu', $menu);
        $this->template->set('select', Request::initial()->controller());
    }

}