<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * Class MetaGenerator
 * Generation constructor for tags like title, meta-description, meta-keywords etc
 */
class MetaGenerator {


    protected static $_instance;
	/** @param $parameters - replaces <param> labels in config templates
	 *  Known labels:
	 *   ad_title - title of AD
	 *   category - category name
	 *   region   - region name
	 *   project  - name of site
	 * */
    protected $_params;
    protected $_template = '';

    /**`
     * Creates config instance
     * @return MetaGenerator
     */
    public static function instance($template = ''){
        if(is_null(self::$_instance)){
            self::$_instance = new self($template);
        }
        return self::$_instance;
    }

    /**
     * Constructs a new config board instance
     */
    public function __construct($template = '')
    {
    	$this->_template = $template;
    }

	/**
	 * Set constructor template
	 * @param $template
	 *
	 * @return $this
	 */
	public function setTemplate($template){
		$this->_template = $template;
		return $this;
	}

	public function setValues(Array $params){
		$this->_params = $params;
		$this->_pageString();
		return $this;
	}

	/**
	 * Generates description string from config templates
	 * @return null
	 */
	public function generate(){
		$template = $this->_template;
		foreach($this->_params as $_param=>$_val)
			$template = $this->_replaceMetaParam($_param, $_val, $template);
		return $template;
	}

	/**
	 * Replace parameter to value in template
	 * @param $key
	 * @param $value
	 * @param $template
	 * @return mixed
	 */
	protected function _replaceMetaParam($key, $value, $template){

		if(mb_strstr($template, '<'.$key.':')){
			preg_match_all('~<'.$key.':(\d+)>~', $template, $matches);
			foreach($matches as $_match_id=>$_match){
				if($_match_id == 0)
					continue;
				$template = str_replace('<'.$key.':'.$_match[0].'>', mb_substr($value, 0, $_match[0]), $template);
			}
		}
		$template = str_replace('<'.$key.'>', $value, $template);
		return $template;
	}

	protected function _pageString($page = 0){
		$this->_params['page'] = isset($this->_params['page']) && $this->_params['page']>1 ? ' - страница '.$this->_params['page'] : NULL;
		return true;
	}

    /**
     * Params getter
     * @param $id
     * @return null
     */
    public function __get($id){
        if(isset($this->_params[ $id ]))
            return $this->_params[ $id ];
        return NULL;
    }

	/**
	 * Params setter
	 * @param $id
	 * @return null
	 */
	public function __set($id, $val){
		$this->_params[ $id ] = $val;
	}

	/**
	 * Closed method
	 */
	private function __clone(){}
}