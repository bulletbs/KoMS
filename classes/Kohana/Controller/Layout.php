<?php defined('SYSPATH') or die('No direct script access.');

class Kohana_Controller_Layout extends Controller_System_Page
{
    public $skip_auto_content_apply = array(
        'showHeader',
        'showFooter'
    );

    public function action_showHeader(){
        $this->template = View::factory('global/_header')->set(array(
            'project_name' => $this->config['project']['name'],
            'category_menu' => $this->category_menu,

            'title' => $this->title,
            'keywords' => $this->keywords,
            'description' => $this->description,

            'styles' => $this->styles,
            'scripts' => $this->scripts,
        ));
    }

    public function action_showFooter(){
        $this->template = View::factory('global/_footer')->set(array(
            'project_name' => $this->config['project']['name'],
            'category_menu' => $this->category_menu,

            'title' => $this->title,
            'keywords' => $this->keywords,
            'description' => $this->description,

            'styles' => $this->styles,
            'scripts' => $this->scripts,
        ));
    }
}