<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Class that check
 *
 * @throws HTTP_Exception_403 Если действие не разрешено
 */
class Controller_System_Security extends Controller_System_Controller
{
    /**
     * Actoins that need to be logged in
     * @var bool
     */
    public $secure_actions = FALSE;

    /**
     * Flag if controller need to be logged in
     * When auth needed, set this variable to role
     * @var bool|string
     */
    public $auth_required = FALSE;

    /**
     * Are user logged
     * @var bool
     */
    public $logged_in = FALSE;

    /**
     * Current logged user holder
     * @var null
     */
    public $current_user = NULL;

    /**
     * Default login form route
     * @var string
     */
    public $login_route = 'auth';


    /**
     * Default login form action
     * @var string
     */
    public $login_action = 'login';

    /**
     * Проверяем права на доступ к текущей странице
     * @throws HTTP_Exception_403 Если действие не разрешено
     * @return void
     */
    public function before()
    {
        parent::before();

        $this->logged_in = Auth::instance()->logged_in();
        if($this->logged_in)
            $this->current_user = Auth::instance()->get_user();

//        if($_SERVER['REMOTE_ADDR'] == '109.108.68.2'){
//            echo Debug::vars($this->request->action());
//            echo Debug::vars(Auth::instance()->logged_in($this->secure_actions[$this->request->action()]));
//            die();
//        }

        /*
         * Checks role access to controller
         */
        if($this->auth_required && !Auth::instance()->logged_in($this->auth_required)){
            $uri = Route::url($this->login_route, array(
                'action' => $this->login_action,
            ));
            header("Location: ". $uri);
            die();
        }

        /*
         * role access to action
         */
        elseif (
            is_array($this->secure_actions)
            AND array_key_exists($this->request->action(), $this->secure_actions)
            AND !Auth::instance()->logged_in($this->secure_actions[$this->request->action()])
        ) {
            // Если нет прав и AJAX запрос, то выдаем эксепшен
            if ($this->logged_in OR $this->request->is_ajax()) {
                throw new HTTP_Exception_403('You don\'t have permissions to acces this page');
            } // Если нет прав и обычный запрос, в моем случае происходит редирект
            else {
                $uri = Route::url($this->login_route, array(
                    'action' => $this->login_action,
                ));
                header("Location: ". $uri);
                die();
            }
        }
    }
}