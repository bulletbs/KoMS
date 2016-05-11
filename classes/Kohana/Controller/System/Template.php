<?php
/**
 * Класс основного шаблона приложения, используется для отображения Шаблонов
 */

abstract class Kohana_Controller_System_Template extends Controller_System_Security
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

    public $mobile_scripts = array();
    public $mobile_styles = array();

    public $metatags = array();

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

        /* Mobile template handling */
        if($this->is_mobile)
            $this->template = 'mobile/layout';

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

            /* выбор шаблона для рендера */
            if(!in_array($this->request->action(), $this->skip_auto_content_apply))
                $this->template->content = $this->getContentTemplate($this->content_template);
        }
    }

    public function after()
    {
        parent::after();
        if ($this->auto_render === TRUE && $this->request->is_ajax() !== TRUE ) {
            $this->_setTemplateAssets();
            $this->_setTemplateMeta();
            $this->template->metatags = $this->metatags;

            $this->template->logged_in = $this->logged_in;
            $this->template->current_user = $this->current_user;
            $this->template->project_host = $this->config['project']['host'];
            $this->template->project_name = $this->config['project']['name'];

            $this->response->body($this->template->render());
        }
        elseif ($this->request->is_ajax() === TRUE) // Если AJAX
        {
            // И параметр json содержит данные
            if ($this->json !== NULL) {
                if (is_array($this->json) AND !isset($this->json['status'])) {
                    $this->json['status'] = FALSE;
                }

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
     * @return View
     * @throws HTTP_Exception_404
     */
    public function getContentTemplate($path = NULL){
        $content = View::factory();
        if ($this->auto_render === TRUE && ($this->request->is_ajax() !== TRUE || !is_null($path))) {
            if(is_null($path))
                $path = $this->get_action_view();

            /* Looking for mobile version */
            if($this->is_mobile && Kohana::find_file('views', 'mobile/'.$path)){
                $content = View::factory('mobile/'.$path);
            }
            /* Looking for pc version */
            elseif ( Kohana::find_file('views', $path) )
            {
                $content = View::factory($path);
            }
            else
            {
                throw new HTTP_Exception_404(__('The requested page not found'));
            }
        }
        return $content;
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
        if($this->is_mobile){
            $this->template->styles = $this->config['mobile_styles'];
            $this->template->scripts = $this->config['mobile_scripts'];
            $this->template->styles = array_merge($this->template->styles, $this->mobile_styles);
            $this->template->scripts = array_merge($this->template->scripts, $this->mobile_scripts);
        }
        else{
            $this->template->styles = $this->config['styles'];
            $this->template->scripts = $this->config['scripts'];
            $this->template->styles = array_merge($this->template->styles, $this->styles);
            $this->template->scripts = array_merge($this->template->scripts, $this->scripts);
        }
    }

    /**
     * Set META data to main template
     */
    protected function _setTemplateMeta(){
        $this->template->project_name = $this->config['project']['name'] ;
        $this->template->title = $this->config['view']['title'] ;
        $this->template->keywords = $this->config['view']['keywords'];
        $this->template->description = $this->config['view']['description'];
    }

    
    public function add_meta_content(Array $parameters = array()){
        $this->metatags[] = $parameters;
    }

    public function getCacheName($name){
        if($this->is_mobile)
            $name .= '_mobile';
        return $name;
    }
}