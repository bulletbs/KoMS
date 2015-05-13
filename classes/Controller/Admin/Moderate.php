<?php defined('SYSPATH') or die('No direct script access.');

/**
 * CRUD Controller
 * Have an Actions to operate ORM models
 */
class Controller_Admin_Moderate extends Controller_System_Admin{

    const NOT_MODERATED = 0;
    const IS_MODERATED = 1;

    public $list_fields = array(
        'name',
    );

    /**
     * Если указано название виджета - вызывает подменю
     * @var
     */
    protected $submenu;


    public $model_name = '';
    public $moderate_field = 'moderate';

    protected $_item_name;
    protected $_moderate_name;

    protected $_moderate_uri;
    protected $_crud_uri;

    public $skip_auto_render = array(
        'delete',
        'check',
        'checkall',
        'multi',
    );

    public $moderate_render_action = array(
        'index',
    );

    protected $_views_path = 'admin/moderate';

    public function before(){
        $this->skip_auto_content_apply = Arr::merge($this->moderate_render_action, $this->skip_auto_content_apply);

        parent::before();
        if(empty($this->model_name))
            throw new Kohana_Exception('There is no model to moderate');

        $route_params = array(
            'controller'=>lcfirst($this->request->controller()),
            'id'=>NULL,
        );
        $this->_moderate_uri = Route::get('admin')->uri($route_params);

        /* Rendering submenu if widget name exists */
        if($this->auto_render===TRUE && isset($this->submenu))
            $this->template->set('submenu', Request::factory('widgets/' . $this->submenu)->execute());

        if(in_array($this->request->action(), $this->moderate_render_action))
            $this->template->content = View::factory($this->_views_path .'/'. $this->request->action());
    }

    /**
     * List items
     */
    public function action_index(){
        $this->template->scripts[] = "media/libs/bootstrap/js/bootbox.min.js";
        $this->template->scripts[] = "media/libs/bootstrap/js/bbox_".I18n::$lang.".js";
        $this->template->scripts[] = "media/js/admin/check_all.js";

        $orm = ORM::factory($this->model_name);
        $orm->where($this->moderate_field,'=',self::NOT_MODERATED);
        $count = $orm->count_all();
        $pagination = Pagination::factory(
            array(
                'total_items' => $count,
                'group' => 'admin',
            )
        )->route_params(
                array(
                    'controller' => Request::current()->controller(),
                )
            );
        /**
         * @var $comment ORM
         */
        $orm = ORM::factory($this->model_name)
            ->where($this->moderate_field,'=',self::NOT_MODERATED)
            ->limit($pagination->items_per_page)
            ->offset($pagination->offset);
        $items = $orm->find_all();
        $this->template->content
            ->set('pagination', $pagination)
            ->set('items', $items)

            ->set('list_fields',$this->list_fields)
            ->set('crud_uri',$this->_crud_uri)
            ->set('moderate_uri',$this->_moderate_uri)
            ->set('moderate_name',$this->_moderate_name)
            ->set('moderate_field',$this->moderate_field)
            ->set('item_name',$this->_item_name)
            ->set('labels',$this->_getModelLabels())
        ;
    }

    /**
     * Delete item
     */
    public function action_delete(){
        $comment = ORM::factory($this->model_name, $this->request->param('id'));
        if($comment->loaded())
            $comment->delete();
        $this->redirect($this->_moderate_uri . URL::query());
    }

    /**
     * Check comment as moderated
     */
    public function action_check(){
        $model = ORM::factory($this->model_name, $this->request->param('id'));
        if($model->loaded() && !$model->{$this->moderate_field}){
            $model->{$this->moderate_field} = self::IS_MODERATED;
            $model->update();
            Flash::success(__('Item #:id was successfully moderated', array(':id' => $model->id)));
        }
        $this->redirect($this->_moderate_uri . URL::query());
    }

    /**
     * Check comment as moderated
     */
    public function action_checkall(){
        $count = $this->_setAllModerated();
        Flash::success(__('All items (:count) was successfully moderated', array(':count'=>$count)));
        $this->redirect($this->_moderate_uri . URL::query());
    }

    /**
     * Multi action related to button
     */
    public function action_multi(){
        $ids = Arr::get($_POST, 'operate');
        if(isset($_POST['check_all']) && count($ids)){
            $this->_setModerated($ids);
            Flash::success(__('All items (:count) was successfully moderated', array(':count'=>count($ids) )));
        }
        if(isset($_POST['delete_all']) && count($ids)){
            $this->_delSelected($ids);
            Flash::success(__('All items (:count) was successfully deleted', array(':count'=>count($ids))));
        }
        $this->redirect($this->_moderate_uri . URL::query());

    }

    /**
     * Get model field labels
     * @return array
     */
    protected function _getModelLabels(){
        return ORM::factory($this->model_name)->labels();
    }

    /**
     * Check all not moderated comments as moderated
     * @return int
     */
    protected function _setAllModerated(){
        return DB::update(ORM::factory($this->model_name)->table_name())->set(array($this->moderate_field=>1))->where($this->moderate_field, '=', self::NOT_MODERATED)->execute();
    }

    /**
     * Check all selected
     * @param array $ids
     * @return object
     */
    protected function _setModerated(Array $ids){
        return DB::update(ORM::factory($this->model_name)->table_name())->set(array($this->moderate_field=>1))->where($this->moderate_field, '=', self::NOT_MODERATED)->and_where('id','IN',$ids)->execute();
    }

    /**
     * Delete all selected comment
     * @param array $ids
     * @return object
     */
    protected function _delSelected(Array $ids){
        $count = ORM::factory($this->model_name)->where('id','IN',$ids)->count_all();
        $items = ORM::factory($this->model_name)->where('id','IN',$ids)->find_all();
        foreach($items as $item)
            $item->delete();
        return $count;
    }
}