<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Class that check
 *
 * @throws HTTP_Exception_403 Если действие не разрешено
 */
class Kohana_Controller_System_Security extends Controller_System_Controller
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
    public $login_action = 'enter';

    /**
     * Проверяем права на доступ к текущей странице
     * @throws HTTP_Exception_403 Если действие не разрешено
     * @return void
     */
    public function before()
    {
        parent::before();

        $this->logged_in = Auth::instance()->logged_in();
        if($this->logged_in){
            if(!is_null($subuser = Session::instance()->get(Model_User::SESSION_SUBUSER_NAME)))
                $this->current_user = ORM::factory('User', $subuser);
            else
                $this->current_user = Auth::instance()->get_user();
        }

        /*
         * Checks role access to controller
         */
        if(!$this->passAuth()){
            $uri = Route::url($this->login_route, array(
                'action' => $this->login_action,
            ));
            header("Location: ". $uri);
            die();
        }

        /*
         * role access to action
         */
        elseif (!$this->passActionAuth()) {
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

    /**
     * Check auth_required role and secured actions before it
     * @return bool
     */
    protected function passAuth(){
        if (Auth::instance()->logged_in('admin'))
            return true;// if action set as secured pass and check at passActionCheck
        if(is_array($this->secure_actions) && array_key_exists($this->request->action(), $this->secure_actions))
            return true;
        // Otherwise check only role
        elseif($this->auth_required && !Auth::instance()->logged_in($this->auth_required))
            return false;
        return true;
    }

    /**
     * Check action auth by "secured_actions"
     * Pass if User have action role
     * or action role set to NULL (useful for secured controllers)
     * @return bool
     */
    protected function passActionAuth(){
        if(!is_array($this->secure_actions) || Auth::instance()->logged_in('admin'))
            return true;
        if(
            array_key_exists($this->request->action(), $this->secure_actions)
            && !is_null($this->secure_actions[$this->request->action()])
            && !Auth::instance()->logged_in($this->secure_actions[$this->request->action()])
        )
            return false;
        return true;

    }
}