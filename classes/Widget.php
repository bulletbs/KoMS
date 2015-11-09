<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Class Widget
 * Базовый класс виджета
 * Фабрика виджетов
 *
 */
class Widget  {

    protected $_controllers_folder  = 'widgets';    // Название папки с контроллерами виджетов
    protected $_config_filename     = 'widgets';    // Название файла конфигураций виджетов

    protected $_route_name          = 'widgets';    // Название файла конфигураций виджетов по умолчанию
    protected $_params              = array();      // Массив передаваемых параметров
    protected $_widget_name;                        // Название виждета (контроллер)

    protected $_cachetime = 0;                       // Время кеширования (0 - без кеширования)


    public function __construct($widget_name, array $params = array(), $route_name = NULL)
    {
        $this->_widget_name = $widget_name;

        if ($params != NULL)
        {
            $this->_params = $params;
        }

        if ($route_name != NULL)
        {
            $this->_route_name = $route_name;
        }
    }

    /*
     * Вызов виджета Widget::factory('widget_name')->render();
     * @param   string  Название виджета
     * @param   array   Массив передаваемых параметров
     * @param   string  Название роута данного виджета
    */
    public static function factory($widget_name, array $params = array())
    {
        return new Widget($widget_name, $params);
    }

    /*
     * Вызов виджета Widget::load('widget_name', array('param' => 'val'), 'route_name');
     * @param   string  Название виджета
     * @param   array   Массив передаваемых параметров
     * @param   string  Название роута данного виджета
     */
    public static function load($widget_name, array $params = NULL)
    {
        $widget = new Widget($widget_name, $params);
        return $widget->render();
    }

    /**
     * Кештровать вывод виджета
     * @param int $time - время кеширования (0 - без кеширования)
     * @return $this
     */
    public function cached($time = 0){
        $this->_cachetime = $time;
        return $this;
    }

    /**
     * Имя кеша для виджета
     * @return string
     */
    protected function _widgetCacheName(){
        return "widgetCache". $this->_widget_name;
    }

    /**
     * Рендер виджета
     * @return null|Response
     */
    public function render()
    {
        if($this->_cachetime && ($content = Cache::instance()->get( $this->_widgetCacheName() ))){
            return $content;
        }

        // Получаем текущий контроллер и экшен
        $controller = Request::current()->controller();
        $action = Request::current()->action();
//        $directory = Request::current()->directory();

        // Загружаем файл конфигураций
        $widget_config = Kohana::$config->load("$this->_config_filename.$this->_widget_name.$controller");

        // Запрещаем вывод виджета в экшенах, указанных в конфигах
        if ($widget_config != NULL)
        {
            if (in_array($action, $widget_config) ||in_array('all_actions', $widget_config) )
            {
                return NULL;
            }
        }

        $this->_params['controller'] = $this->_widget_name;
        $url = Route::get($this->_route_name)->uri($this->_params);

        $content = Request::factory($url)->post($this->_params)->execute();
        if($this->_cachetime){
            Cache::instance()->set( $this->_widgetCacheName(), $content, $this->_cachetime );
        }
        return $content;
    }

    /**
     * Widget parameter getter
     * @param $name
     * @return null
     */
    public function __get($name){
        if(isset($this->_params[$name]))
            return $this->_params[$name];
        return NULL;
    }

    /**
     * @return string
     */
    public function __toString(){
        return (string) $this->render();
    }
}
