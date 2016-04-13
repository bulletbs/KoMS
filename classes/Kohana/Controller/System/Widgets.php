<?php defined('SYSPATH') or die('No direct script access.');
/*
 * Базовый класс виджетов
 */
class Kohana_Controller_System_Widgets extends Controller_System_Security {

    public $template = 'global/widget';
    public $skip_auto_content_apply = array();

    public function  before() {
        parent::before();
        if(!in_array($this->request->action(), $this->skip_auto_content_apply))
            $this->template = View::factory($this->template);
    }

    public function after(){
        if(!in_array($this->request->action(), $this->skip_auto_content_apply))
            $this->response->body($this->template);
    }
}
