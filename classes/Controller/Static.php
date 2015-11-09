<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Static extends Controller_System_Page
{
    public function action_index()
    {
        $id = $this->request->param('page');
        $page = ORM::factory('Page')->where('alias','=',$id)->find();
        if($page->loaded()){
            if($page->haveTitle())
                $this->title = $page->getTitle();
            if($page->haveDescription())
                $this->description = $page->getDescription();
            if($page->haveKeywords())
                $this->keywords = $page->getKeywords();
            $this->template->content
                ->set('page_header', $page->title)
                ->set('page_content', $page->text);
        }
        else{
            throw new HTTP_Exception_404('Requested page not found');
        }
    }
}