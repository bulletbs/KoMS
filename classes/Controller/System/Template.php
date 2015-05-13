<?php
/**
 * Класс основного шаблона приложения, используется для отображения Шаблонов
 */

class Controller_System_Template extends Controller_System_Security
{

    /**
     * Путь до файла глобального шаблона
     * @var string
     */
    public $template = 'global/layout';

    /**
     * Путь до файла шаблона контента
     * (если не указан будет найден автоматически)
     * @var string
     */
    public $content_template;

    /**
     * JSON data holder
     * @var null
     */
    public $json = NULL;

    /**
     * Auto-render flag ( )
     * @var bool
     */
    public $auto_render = TRUE;

    public $scripts = array();
    public $styles = array();

    /**
     * @var Breadcrumbs
     */
    public $breadcrumbs;

    /**
     * @var array
     * Actions that not need to be rendered
     */
    public $skip_auto_render = array();

    /**
     * Actions where content redndering manualy (only content)
     * @var array
     */
    public $skip_auto_content_apply = array();

    public function before()
    {
        /* If actions exists in $this->skip_auto_render
           set off auto_render */
        if($this->auto_render && in_array($this->request->action(), $this->skip_auto_render)) {
            $this->auto_render = FALSE;
        }

        parent::before();

        if ($this->auto_render === TRUE) {
            // Если AJAX запрос, то происходит подмена шаблона, чтобы не выводить лишние данные
            // Выводится только блок с контентом
            // шаблон 'ajax/layout' содержит всего одну строчку "<?php echo $content;"
            if ($this->request->is_ajax() === TRUE) {
                $this->template = View::factory('global/ajax');
            }
            else
            {
                $this->template = View::factory($this->template);
            }

            // В этой переменной будет инициализирован шаблон блока с контентом
            $this->template->content = '';
            $this->_setTemplateAssets();
            $this->_setTemplateMeta();

            /* выбор шаблона для рендера */
            if(!in_array($this->request->action(), $this->skip_auto_content_apply))
                $this->getContentTemplate($this->content_template);
        }
    }

    public function after()
    {
        parent::after();
        if ($this->auto_render === TRUE && $this->request->is_ajax() !== TRUE ) {
            $this->template->styles = array_merge($this->template->styles, $this->styles);
            $this->template->scripts = array_merge($this->template->scripts, $this->scripts);

            $this->template->logged_in = $this->logged_in;
            $this->template->current_user = $this->current_user;
            unset($styles, $scripts);

            // Заносим в переменную messages данные из сессии
//            $this->template->messages = View::factory('global/messages', array(
//                'messages' => Session::instance()->get_once('flash_messages')
//            ));

            $this->response->body($this->template->render());
        }
        elseif ($this->request->is_ajax() === TRUE) // Если AJAX
        {
            // И параметр json содержит данные
            if ($this->json !== NULL) {
                if (is_array($this->json) AND !isset($this->json['status'])) {
                    $this->json['status'] = FALSE;
                }
//                echo Debug::vars($this->json);
//                die();

                // То в темплейте мы выводим не шаблон, а кодированные в json format данные
                $this->template->content = json_encode($this->json);
            }

            $this->response->body($this->template);
        }
        else{

        }
    }

    /**
     * Looking for content template (auto_render = TRUE)
     * @param null $path
     * @throws HTTP_Exception_404
     */
    public function getContentTemplate($path = null){
        if(is_null($path)) {
            if ($this->auto_render === TRUE && $this->request->is_ajax() !== TRUE) {
                if ( Kohana::find_file('views', $this->get_action_view()) )
                {
                    $this->template->content = View::factory($this->get_action_view());
                }
                else
                {
                    throw new HTTP_Exception_404(__('The requested page not found'));
                }
            }
        }
        else{
            $this->template->content = View::factory($path);
        }
    }

    /**
     * Generating action template path
     * @return string
     */
    public function get_action_view()
    {
        if (empty($this->uri)) {
            $uri = lcfirst($this->request->controller()) . '/' . $this->request->action();
            $dir = lcfirst($this->request->directory());

            if (!empty($dir))
                $uri = $dir . '/' . $uri;

            $this->uri = $uri;
            unset($uri, $dir);
        }
        return $this->uri;
    }

    /**
     * Set assets to main template
     */
    protected function _setTemplateAssets(){
        $this->template->styles = array();
        $this->template->scripts = array();
        $this->template->logged_in = false;
    }

    /**
     * Set META data to main template
     */
    protected function _setTemplateMeta(){
        $this->template->project_name = $this->config['project']['name'] ;
        $this->template->title = $this->config->view['title'] ;
        $this->template->keywords = $this->config->view['keywords'];
        $this->template->description = $this->config->view['description'];
    }
}