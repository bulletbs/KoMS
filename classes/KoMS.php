<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Kohana managed site
 * Main helper class
 */
class KoMS{

    /**
     * Translates an array values
     * @param array $array
     * @return array
     */
    public static function translateArray(Array $array){
        $labels = array();
        foreach($array as $_id=>$_val)
            $labels[$_id] = __($_val);
        return $labels;
    }
}