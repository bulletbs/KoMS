<?php defined('SYSPATH') or die('No direct script access.');
/*
 * Виджет "Меню админа"
 */
class Controller_Widgets_Adminmainmenu extends Controller_System_Widgets {

    public $template = 'widgets/adminmenu';    // Шаблон виждета

    public function action_index()
    {

        $menu = array(
            array(
                'controller'=>'pages',
                'icon'=>'file',
                'label'=>__('Pages'),
            ),
//            array(
//                'controller'=>'news',
//                'icon'=>'calendar',
//                'label'=>__('News'),
//            ),
//            array(
//                'controller'=>'catalog',
//                'icon'=>'briefcase',
//                'label'=>__('Site Catalog'),
//            ),ray(
//                'controller'=>'news',
//                'icon'=>'calendar',
//                'label'=>__('News'),
//            ),
//            array(
//                'controller'=>'catalog',
//                'icon'=>'briefcase',
//                'label'=>__('Site Catalog'),
//            ),
            array(
                'controller'=>'board',
                'icon'=>'briefcase',
                'label'=>__('Site Ads'),
            ),
            array(
                'controller'=>'comments',
                'icon'=>'comment',
                'label'=>__('Comments'),
            ),
            array(
                'controller'=>'users',
                'icon'=>'user',
                'label'=>__('Users'),
            ),
//            array(
//                'icon'=>'file',
//                'label'=> __('Contents'),
//                'submenu'=>array(
//                ),
//            ),
//            array(
//                'icon'=>'folder-open',
//                'label'=>'Data',
//                'submenu'=>array(
//                ),
//            ),
        );

        $current_user = NULL;
        if($this->current_user)
            $current_user = !empty($this->current_user->profile->name) ? $this->current_user->profile->name : $this->current_user->username;
        $this->template->bind('current_user_username', $current_user);
        $this->template->set('menutitle', $this->config['project']['name']);
        $this->template->set('menu', $menu);
        $this->template->set('select', Request::initial()->controller());
    }

}