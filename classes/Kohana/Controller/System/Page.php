<?php
/**
 * Класс страницы приложения, используется для отображения Страниц и Виждетов
 */
class Kohana_Controller_System_Page extends Controller_System_Template
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
            }
        }
    }

    public function after()
    {
        if ($this->auto_render === TRUE) {
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

    /**
     * Set META data to main template
     */
    protected function _setTemplateMeta(){
        $this->template->project_name = $this->config['project']['name'] ;
        $this->template->title = !empty($this->title) ? $this->title : $this->config['view']['title'] ;
        $this->template->keywords = !empty($this->keywords) ? $this->keywords : $this->config['view']['keywords'];
        $this->template->description = !empty($this->description) ? $this->description : $this->config['view']['description'];
    }
}