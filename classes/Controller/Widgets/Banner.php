<?php defined('SYSPATH') or die('No direct script access.');
/*
 * Виджет "970x90"
 */
class Controller_Widgets_Banner extends Controller_System_Widgets {

    public $skip_auto_content_apply = array(
        'index',
    );

    public function action_index()
    {
        $this->template = NULL;
        if(!is_null(Request::current()->post('tpl')) && Kohana::find_file('views/widgets', Request::current()->post('tpl')))
            $this->template = View::factory('widgets/'. Request::current()->post('tpl'));
        $this->response->body($this->template);
    }

}