<?php
/**
 * Created by JetBrains PhpStorm.
 * User: bullet
 * Date: 06.05.12
 * Time: 8:58
 * To change this template use File | Settings | File Templates.
 */
 
class Kohana_Controller_System_Error extends Controller_System_Page {

    public function before()
    {
        parent::before();

        $uri = URL::site( rawurldecode( Request::$initial->uri() ) );
        $message = 'Критическая ошибка';
        $this->template->content->page = $uri;

        if ( Request::$initial !== Request::$current )
        {
            if ( $message = rawurldecode( $this->request->param( 'message' ) ) )
            {
                $this->template->content->message = $message;
            }
        }

        $this->template->content->action = $this->request->action();
    }

    public function action_index()
    {
        $this->page->title = 'Страница не найдена (404-я ошибка)';
        $this->response->status( 404 );
    }
}