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

    public $headers = array();

    public $login_action = 'enter';

    public function before()
    {
        parent::before();

        if($this->auto_render === TRUE){

            /*
             * only for Initial request OR error controller
             */
            if(Request::$current->is_initial() || $this->request->controller()=='Error'){
                $this->breadcrumbs = Breadcrumbs::factory()->add($this->config['breadcrumb_root'], '/', 0);
                $this->styles = Arr::merge($this->styles, $this->config->styles);
                $this->scripts = Arr::merge($this->config->scripts, $this->scripts);
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

            foreach($this->headers as $_header)
                header($_header);
        }

        parent::after();
    }

    /**
     * Add header to headers query
     * @param $content
     */
    public function add_page_header($content){
        $this->headers[] = $content;
    }
}