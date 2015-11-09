<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Created by JetBrains PhpStorm.
 * User: butch
 * Date: 23.05.12
 * Time: 19:19
 * To change this template use File | Settings | File Templates.
 */

class Model_Page extends ORM{

    public function rules(){
        return array(
            'name' => array(
                array('not_empty'),
                array('min_length', array('value:',3)),
            ),
            'text' => array(
                array('max_length', array('value:',65525)),
            ),
            'status' => array(
                array('in_array',array(':value', array('1',null))),
            ),
        );
    }

    public function labels(){
        return array(
            'id' => __('Id'),
            'name' => __('Name'),
            'alias' => __('Alias'),
            'text' => __('Text'),
            'status' => __('Status'),
            'title' => 'Meta Title',
            'description' => 'Meta Description',
            'keywords' => 'Meta Keywords',
        );
    }

    public function filters(){
        return array(
            'alias' => array(
                array(array($this,'generateAlias'))
            ),
        );
    }

    /**
     * Generate transliterated alias
     */
    public function generateAlias($alias){
        $alias = trim($alias);
        if(empty($alias))
            $alias = Text::transliterate($this->title, true);
        return $alias;
    }

    /**
     * Generating page URL
     */
    public function getUri(){
        $uri = Route::get('static')->uri(array(
            'page' => $this->alias,
        ));
        return $uri;
    }

    /* Meta tags checkers an getters */
    public function haveTitle(){
        return !empty($this->title);
    }

    public function haveDescription(){
        return !empty($this->description);
    }

    public function haveKeywords(){
        return !empty($this->keywords);
    }

    public function getTitle(){
        return $this->title;
    }

    public function getDescription(){
        return $this->description;
    }

    public function getKeywords(){
        return $this->keywords;
    }
}