<?php defined('SYSPATH') or die('No direct script access.');

/**
 * CRUD Controller
 * Have an Actions to operate ORM models
 */
class Controller_Admin_Crud extends Controller_System_Admin{

    /**
     * Actions that never rendered
     * @var array
     */
    public $skip_auto_render = array(
        'delete',
        'setorder',
        'multi',
    );

    /**
     * Actions with manual rendering
     * @var array
     */
    public $skip_auto_content_apply = array(
        'add',
        'edit',
    );

    /**
     * Actions rendered by CRUD Controller
     * @var array
     */
    public $crud_render_actions = array(
        'index',
    );

    /**
     * Path to CRUD controllers
     * @var string
     */
    protected $_views_path = 'admin/crud';

    /**
     * Если указано название виджета - вызывает подменю
     * @var
     */
    protected $submenu;

    /**
     * Advanced item actions in items list
     * Example:
     * protected $_advanced_list_actions = array(
            array(
                'action'=>'status', // controller action name
                'label'=>'On/Off', // title label
                'icon'=>'edit', // simple icon
            ),
            OR
            array(
                'action'=>'status', // controller action name
                'label'=>'On/Off', // title label
                'icon'=>array(, // smart icon
                    'field' => '', // indicator field
                    'values' => array( //icon state for values
                        '0' => 'on',
                        '1' => 'off',
                    ),
            ),
     * );
     *
     * @var array
     */
    protected $_advanced_list_actions = array();

    /**
     * Список полей для вывода в списке элементов (ACTION = index)
     * @var array
     */
    protected $list_fields = array();

    /**
     * Список полей для рендера формы и получения значений из формы по ключам (ACTION = add|edit)
     * $_form_fields - список полей из формы
     * $form_fields_save_extra - дополнительный список полей для сохранения, не указаных в $_form_fields (например для доп.шаблонов)
     * Example:
     *
        protected $_form_fields = array(
            'name' => array('type'=>'text'),
            'category_id' => array(
                'type'=>'select',
                'data'=>array('options'=>array())
            ),
            'main' => array('type'=>'checkbox'),
            'source' => array('type'=>'text'),
            'date' => array('type'=>'datetime'),
            'brief' => array('type'=>'editor', 'config'=>'admin-120'),
            'content' => array('type'=>'editor'),
            'photo' => array(
                'type'=>'call_view',
                'data'=>'admin/ads/photos',
                'advanced_data'=>array(
                    'photos'=>array(),
                )
            ),
        );
     * @var array
     */
    protected $_form_fields = array();
    protected $form_fields_save_extra = array();


    /**
     * Список полей для сортировки списка
     * Example of an array items:
     *   'category_id' => array(
     *       'type'=>'select',
     *       'label'=>'Type',
     *       'oper'=>' IN ',
     *   ),
     *   'name' => array(
     *       'type'=>'text',
     *       'label'=>'Contains',
     *       'oper'=>'like',
     *   ),
     * @var array
     */
    protected $_sort_fields = array();
    protected $_sort_values;

    protected $_index_field = 'id';
    protected $_ordeby_field = 'id';
    protected $_ordeby_direction = 'DESC';

    protected $_setorder_field;
    protected $_model_name;

    protected $_item_name;
    protected $_crud_name;
    protected $_crud_uri;

    /**
     * Multi operation array
     *  array(
           'del_selected' => 'Delete selected',
     *  )
     * And then define method for process selected records
     * like this:
     *  protected function _multi_del_selected(){ ... }
     *
     * @var array
     */
    protected $_multi_operations = array();

    public function before(){
        $this->skip_auto_content_apply = Arr::merge($this->crud_render_actions, $this->skip_auto_content_apply);

        parent::before();
        /* getting sorting values */
        if(count($this->_sort_fields))
            $this->_sort_values = Arr::extract($this->request->query(), array_keys($this->_sort_fields));

        /* Getting controller main route */
        $this->_crud_uri = $this->_calculateRoute();

        /* Calculate template */
        if(in_array($this->request->action(), $this->crud_render_actions))
            $this->template->content = View::factory($this->_views_path .'/'. $this->request->action());

        /* Creating controller title & name if empty */
        if(is_null($this->_item_name))
            $this->_item_name = $this->request->controller();
        if(is_null($this->_crud_name))
            $this->_crud_name = $this->request->controller();

        /* Rendering submenu if widget name exists */
        if($this->auto_render===TRUE && isset($this->submenu))
            $this->template->set('submenu', Request::factory('widgets/' . $this->submenu)->execute());

        /* Load CRUD translates */
        if(!empty($this->_crud_name))
            $this->_crud_name = __($this->_crud_name);
        if(!empty($this->_item_name))
            $this->_item_name = __($this->_item_name);
    }

    /**
     * List items
     */
    public function action_index(){
        $this->template->scripts[] = "media/libs/bootstrap/js/bootbox.min.js";
        $this->template->scripts[] = "media/libs/bootstrap/js/bbox_".I18n::$lang.".js";
        if(count($this->_multi_operations))
            $this->template->scripts[] = "media/js/admin/check_all.js";

        $count = ORM::factory($this->_model_name);
        $this->_applyQueryFilters($count);
        $count = $count->count_all();

        /* Init Pagination module */
        $pagination = Pagination::factory(
            array(
                'total_items' => $count,
                'group' => 'admin_float',
            )
        )->route_params(
            array(
                'controller' => Request::current()->controller(),
            )
        );
        /* Init Items query */
        $items = ORM::factory($this->_model_name)
            ->limit($pagination->items_per_page)
            ->offset($pagination->offset);

        $this->_applyQueryFilters($items);
        $items = $items->order_by($this->_ordeby_field, $this->_ordeby_direction)->find_all();

        $this->template->content
            ->set('pagination',$pagination)
            ->set('items',$items)
            ->set('list_fields',$this->list_fields)
            ->set('crud_uri',$this->_crud_uri)
            ->set('crud_name',$this->_crud_name)
            ->set('item_name',$this->_item_name)
            ->set('labels',$this->_getModelLabels())
            ->set('sort_fields',$this->_sort_fields)
            ->set('order_field',$this->_setorder_field)
            ->set('advanced_actions',$this->_advanced_list_actions)
            ->set('multi_operations',$this->_multi_operations)
        ;
    }

    /**
     * Set items order action
     * User $this->_order_field name to set order
     */
    public function action_setorder(){
        $orders = Arr::get($_POST, 'orders', array());
        foreach($orders as $item_id=>$order)
            $item = ORM::factory($this->_model_name, $item_id)->set($this->_setorder_field,  $order)->save();
        $this->go($this->_crud_uri. URL::query());
    }

    /**
     * Multi data processing
     * @throws Kohana_Exception
     */
    public function action_multi(){
        $ids = Arr::get($_POST, 'operate', array());
        if(count($ids)){
            foreach($this->_multi_operations as $_operation=>$_operation_name)
                if(NULL !== Arr::get($_POST, $_operation)){
                    if(!method_exists($this, '_multi_'.$_operation))
                        throw new Kohana_Exception('Method _multi_'.$_operation.' does not defined');
                    $this->{'_multi_'.$_operation}($ids);
                }
        }
        $this->redirect($this->_crud_uri . URL::query());
    }

    /**
     * Add item
     */
    public function action_add(){
        $model = $this->_loadModel();
        $this->_processForm($model);
    }

    /**
     * Edit item
     */
    public function action_edit(){
        $this->template->styles[] = "media/libs/bootstrap/css/bootstrap-datetimepicker.min.css";
        $this->template->scripts[] = "media/libs/moment/moment.min.js";
        $this->template->scripts[] = "media/libs/bootstrap/js/bootstrap-datetimepicker.min.js";

        $id = $this->request->param($this->_index_field);
        $model = $this->_loadModel($id);
        $this->_processForm($model);
    }

    /**
     * Delete item
     */
    public function action_delete(){
        $model = $this->_loadModel($this->request->param($this->_index_field));
        if($model->loaded()){
            $model->delete();
            Flash::success('Record was successfully deleted');
        }
        $this->redirect($this->_crud_uri . URL::query());
    }

    /**
     * Form render/process method (if POST isset than save Model and goto items list)
     * @param $model
     * @return bool
     */
    protected  function _processForm($model){
        if(!isset($_POST))
            return FALSE;

        /* Process POST */
        if(isset($_POST['cancel'])){
            $this->go($this->_crud_uri . URL::query());
        }
        if(isset($_POST['submit'])){
            try{
                $this->_saveModel($model);
                Flash::success('Record was successfully saved');
                $this->go($this->_crud_uri . URL::query());
            }
            catch(ORM_Validation_Exception $e){
                    $errors = $e->errors('validation');
            }
        }

        /* Fetch Form */
//        $this->template->content->set('item_name', $this->_item_name);
//        $this->template->content->form =  View::factory($this->_views_path . '/form')
        $this->template->content =  View::factory($this->_views_path . '/form')
            ->set('form_fields',$this->_form_fields)
            ->set('item_name', $this->_item_name)
            ->set('advanced_data')
            ->set('labels',$this->_getModelLabels())
            ->bind('model', $model)
            ->bind('errors', $errors);

        /* Setting advanced data */
//        $this->template->content->form->set('advanced_data');
    }


    /**
     * Saving Model Method
     * @param $model
     */
    protected function _saveModel($model){
        $ks = array_merge(array_keys($this->_form_fields), $this->form_fields_save_extra);
        $model->values(Arr::extract($_POST, $ks));
        $model->save();
    }

    /**
     * Load Model Method
     * @param null $id
     * @return ORM
     */
    protected function _loadModel($id = NULL){
        return ORM::factory($this->_model_name, $id);
    }

    /**
     * Get model field labels
     * @return array
     */
    protected function _getModelLabels(){
        $labels = array();
        foreach(ORM::factory($this->_model_name)->labels() as $label_id=>$label)
            $labels[$label_id] = __($label);
        return $labels;
    }

    /**
     * Calculate current controller URI
     * @param array $params
     * @return string
     */
    protected function _calculateRoute($params = array()){
        $route_params = array(
            'controller'=>lcfirst($this->request->controller()),
            'id'=>NULL,
        );
        $route_params = Arr::overwrite($route_params, $params);
        $uri = Route::get('admin')->uri($route_params);
        return $uri;
    }

    /**
     * Applying filters values (from _sort_values) to model query (Index Action)
     * @param ORM $model
     */
    protected function _applyQueryFilters(ORM &$model){
        if(count($this->_sort_fields) && count($this->_sort_values))
            foreach($this->_sort_values as $k=>$v)
                if($v)
                    $model->where(
                        $k ,
                        isset($this->_sort_fields[$k]['oper']) ? $this->_sort_fields[$k]['oper'] : '=',
                        isset($this->_sort_fields[$k]['oper']) && strtolower($this->_sort_fields[$k]['oper']) == 'like' ? "%{$v}%" : $v
                    );
    }
}