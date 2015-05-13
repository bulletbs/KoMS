<?php
class Controller_System_Controller extends Kohana_Controller
{

    public $config;

    public function before()
    {
        parent::before();

        // Загружаем конфиг для сайта
        $GLOBALS['__config'] = $this->config = Kohana::$config->load('global');
    }

    public function go($url = NULL, $code = 302)
    {
        $route = array(
            'controller' => $this->request->controller()
        );

        if (is_array($url)) {
            $route = array_merge($route, $url);
        }

        if ($url === NULL OR is_array($url)) {
            $url = Route::url('default', $route);
        }

        $this->redirect($url, $code);
    }
}