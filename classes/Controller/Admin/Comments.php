<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Created by JetBrains PhpStorm.
 * User: butch
 * Date: 23.05.12
 * Time: 18:35
 * To change this template use File | Settings | File Templates.
 */
class Controller_Admin_Comments extends Controller_System_Admin
{
    public $skip_auto_render = array(
        'delete',
        'check',
        'checkall',
        'multi',
    );

    public $skip_auto_content_apply = array(
        'edit',
    );

    /**
     * List items
     */
    public function action_index(){
        $this->template->scripts[] = "media/libs/bootstrap/js/bootbox.min.js";
        $this->template->scripts[] = "media/libs/bootstrap/js/bbox_".I18n::$lang.".js";
        $this->template->scripts[] = "media/js/admin/check_all.js";

        $comment_sort = Arr::get($_GET, 'comment_sort', 0);

        $orm = ORM::factory('Comment');
        if(in_array($comment_sort, array(0,1)))
            $orm->where('moderated' ,'=', $comment_sort);
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
        $orm = ORM::factory('Comment')
            ->limit($pagination->items_per_page)
            ->offset($pagination->offset);
        if(in_array($comment_sort, array(0,1)))
            $orm->where('moderated' ,'=', $comment_sort);
        $comments = $orm->find_all();
        $this->template->content
            ->set('pagination', $pagination)
            ->set('comments', $comments)
            ->set('comment_sort', $comment_sort)
            ->set('sorts', array(
                __('Not checked'),
                __('Checked'),
                __('All comments'),
            ))
        ;
    }

    /**
     * Add item
     */
    public function action_add(){
        $parent_id = $this->request->param('id');
        $comment = ORM::factory('Comment');
        $this->processForm($comment);
    }

    /**
     * Edit item
     */
    public function action_edit(){
        $id = $this->request->param('id');
        $comment = ORM::factory('Comment', $id);
        $this->processForm($comment);
    }

    /**
     * Delete item
     */
    public function action_delete(){
        $comment = ORM::factory('Comment', $this->request->param('id'));
        if($comment->loaded())
            $comment->delete();
        $this->redirect('admin/comments' . URL::query());
    }

    /**
     * Check comment as moderated
     */
    public function action_check(){
        $comment = ORM::factory('Comment', $this->request->param('id'));
        if($comment->loaded() && !$comment->moderated){
            $comment->moderated = 1;
            $comment->update();
            Flash::success(__('Item #:id was successfully moderated', array(':id' => $comment->id)));
        }
        $this->redirect('admin/comments' . URL::query());
    }

    /**
     * Check comment as moderated
     */
    public function action_checkall(){
        $count = Comments::setAllModerated();
        Flash::success(__('All items (:count) was successfully moderated', array(':count'=>$count)));
        $this->redirect('admin/comments' . URL::query());
    }

    /**
     * Multi action related to button
     */
    public function action_multi(){
        $ids = Arr::get($_POST, 'operate');
        if(isset($_POST['check_all']) && count($ids)){
            Comments::setModerated($ids);
            Flash::success(__('All items (:count) was successfully moderated', array(':count'=>count($ids) )));
        }
        if(isset($_POST['delete_all']) && count($ids)){
            Comments::delSelected($ids);
            Flash::success(__('All items (:count) was successfully deleted', array(':count'=>count($ids))));
        }
        $this->redirect('admin/comments' . URL::query());

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
            $this->go("admin/comments");
        }
        if(isset($_POST['submit'])){
            $post = Arr::extract($_POST, array('content', 'author', 'username'));
            $post = array_merge($post, Arr::extract($_POST, array('moderated'), 0));
            $model->values($post);
            try{
                $model->save();
                Flash::success(__('Comment was successfully saved'));
                $this->go("admin/comments" . URL::query());
            }
            catch(ORM_Validation_Exception $e){
                $errors = $e->errors('validation');
            }
        }

        /* Fetch Form */
        $this->template->content =  View::factory('admin/comments/form')
            ->set('comment', $model)
            ->bind('errors', $errors);
    }
}
