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

    /**
     * Check if string is serialized data
     * @param $data
     * @return bool
     */
    public static function isSerialized($data){
        return (is_string($data) && preg_match("#^((N;)|((a|O|s):[0-9]+:.*[;}])|((b|i|d):[0-9.E-]+;))$#um", $data));
    }

    /**
     * Getting configuration instance
     * @param string $config
     * @return KoMSConfig
     */
    public static function config($config = 'global'){
        return KoMSConfig::instance($config);
    }
}