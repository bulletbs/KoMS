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
            'title' => array(
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
            'title' => __('Title'),
            'alias' => __('Alias'),
            'text' => __('Text'),
            'status' => __('Status'),
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
}