<?php
/**
 * Класс страницы приложения, используется для отображения Страниц и Виждетов
 */
class Controller_System_Page extends Controller_System_Template
{
    public $navigation;
    public $breadcrumbs;
    public $category_menu = array();

    public $title = '';
    public $keywords = '';
    public $description = '';

    public $login_action = 'enter';

    public function before()
    {
        parent::before();

        if($this->auto_render === TRUE){

            /*
             * only for Initial request OR error controller
             */
            if(Request::$current->is_initial() || $this->request->controller()=='Error'){
                $this->breadcrumbs = Breadcrumbs::factory()->add('Главная', '/', 0);
                $this->styles[] = "media/css/style.css";
                $this->styles[] = "media/libs/pure-release-0.5.0/buttons-min.css";
                $this->styles[] = "media/libs/pure-release-0.5.0/pure-extras.css";
                $this->styles[] = "/media/libs/font-awesome-4.1.0/css/font-awesome.min.css";
                $this->scripts[] = 'media/libs/jquery-1.11.1.min.js';
            }
        }
    }

    public function after()
    {
        if ($this->auto_render === TRUE) {
            /* Right column */
            if(!isset($this->template->right_column)){
                $right = View::factory('global/right_column')
//                    ->set('most_articles', Request::factory('news/most')->execute())
                ;
                $this->template->set('right_column', $right);
            }

            if($this->title)
                $this->template->title = $this->title;
            if($this->keywords)
                $this->template->keywords = $this->keywords;
            if($this->description)
                $this->template->description = $this->description;

            $this->template->breadcrumbs = $this->breadcrumbs;

            unset($styles, $scripts);
        }

        parent::after();
    }
}