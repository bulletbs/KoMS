<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Created by JetBrains PhpStorm.
 * User: butch
 * Date: 23.05.12
 * Time: 18:35
 * To change this template use File | Settings | File Templates.
 */
class Kohana_Controller_Admin_Pages extends Controller_System_Admin
{
    public $skip_auto_render = array(
        'delete',
        'status',
    );

    /**
     * List items
     */
    public function action_index(){
        $this->scripts[] = "media/libs/bootstrap/js/bootbox.min.js";
        $this->scripts[] = "media/libs/bootstrap/js/bbox_".I18n::$lang.".js";

        $count = ORM::factory('Page')->count_all();
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
         * @var $pages ORM_MPTT
         */
        $pages = ORM::factory('Page')
            ->limit($pagination->items_per_page)
            ->offset($pagination->offset)
            ->find_all();
        $this->template->content->pagination = $pagination;
        $this->template->content->pages = $pages;
    }

    /**
     * Add item
     */
    public function action_add(){
        $parent_id = $this->request->param('id');
        $page = ORM::factory('Page');
        $this->processForm($page);
    }

    /**
     * Edit item
     */
    public function action_edit(){
        $id = $this->request->param('id');
        $page = ORM::factory('Page', $id);
        $this->processForm($page);
    }

    /**
     * Delete item
     */
    public function action_delete(){
        $page = ORM::factory('Page', $this->request->param('id'));
        if($page->loaded())
            $page->delete();
        $this->redirect('admin/pages');
    }

    /**
     * On/Off item
     */
    public function action_status(){
        $page = ORM::factory('Page', $this->request->param('id'));
        if($page->loaded()){
            $page->status = $page->status==0 ? 1 : 0;
            $page->update();
        }
        $this->redirect('admin/pages');
    }

    /**
     * Form render/process method
     * @param $model - model Object
     * @return array|bool
     */
    public function processForm($model){
        if(!isset($_POST))
            return FALSE;

        /* Process POST */
        if(isset($_POST['cancel'])){
            $this->go("admin/pages");
        }
        if(isset($_POST['submit'])){
            $post = Arr::extract($_POST, array('name', 'alias', 'text', 'status', 'title', 'description', 'keywords'));
            $model->values($post);
            try{
                $model->save();
                Flash::success('Page was successfully saved');
                $this->go("admin/pages");
            }
            catch(ORM_Validation_Exception $e){
                $errors = $e->errors('validation');
            }
        }

        /* Pages List */


        /* Fetch Form */
//        $this->template->content->form =  View::factory('admin/pages/form')
        $this->template->content =  View::factory('admin/pages/form')
            ->bind('page', $model)
            ->bind('errors', $errors);
    }
}
