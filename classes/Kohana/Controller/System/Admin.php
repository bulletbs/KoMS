<?php
/**
 * Класс страницы области администрирования,
 * используется для отображения Страниц и Виждетов Админ-части
 */
class Kohana_Controller_System_Admin extends Controller_System_Template
{

    public $template = 'global/admin';

    public $uri = NULL;

    public $title = 'Administration';
    public $keywords = '';
    public $description = '';

    public $auto_render = TRUE;
    public $skip_auto_content_apply = array();

    /**
     * Need to be authenticated admin role, to access the controller
     * @var array
     */
    public $auth_required = array('admin');

    /**
     * Route to login form
     * @var string
     */
    public $login_action = 'login';
    public $login_route = 'auth_admin';

    public function before()
    {
        $this->allow_mobile = FALSE;
        parent::before();

        if ($this->auto_render === TRUE) {
            $this->styles[] = 'media/libs/bootstrap/css/bootstrap.min.css';
            $this->styles[] = 'media/libs/bootstrap/css/bootstrap-responsive.min.css';
            $this->styles[] = 'media/libs/bootstrap-notify-master/css/bootstrap-notify.css';
            $this->styles[] = 'assets/koms/css/admin.css';

            $this->scripts[] = 'media/libs/jquery-1.11.1.min.js';
            $this->scripts[] = 'media/libs/bootstrap/js/bootstrap.min.js';
            $this->scripts[] = 'media/libs/bootstrap-notify-master/js/bootstrap-notify.js';
            $this->scripts[] = 'assets/koms/js/modal_handler.js';

            /* Widgets */
            $this->template->menu = Widget::factory('adminmainmenu')->render();
        }
    }

    public function after()
    {
        if ($this->auto_render === TRUE) {

            /* META */
//            $this->title = Kohana::$config->load('admin')->title ;
//            $this->description = Kohana::$config->load('admin')->description;
            $this->template->title = $this->title;
            $this->template->description = $this->description;
        }

        parent::after();
    }
}