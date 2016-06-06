<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * Board config-loader / parameter-getter
 * Class BoardConfig
 */
class KoMSConfig {


    protected static $_instances;

    protected $_config;

    /**`
     * Creates config instance
     * @param string $config_file
     * @return KoMSConfig
     */
    public static function instance($config_file = 'global'){
        if(is_null(self::$_instances[$config_file])){
            self::$_instances[$config_file] = new self($config_file);
        }
        return self::$_instances[$config_file];
    }

    /**
     * Constructs a new config board instance
     */
    public function __construct($config_file)
    {
        $this->_config = Kohana::$config->load( $config_file )->as_array();
    }

    /**
     * Closed method
     */
    private function __clone(){}


    /**
     * Config getter
     * @param $id
     * @return null
     */
    public function __get($id){
        if(isset($this->_config[ $id ]))
            return $this->_config[ $id ];
        return NULL;
    }
}