<?php defined('SYSPATH') or die('No direct script access.');
class ORM extends Kohana_ORM
{
    /**
     * List columns caching
     * @return array|mixed
     */
    public function list_columns()
    {
        $cache_lifetime=360000; // 100 часов
        $cache_key = $this->_table_name ."structure";
        if ($result = Cache::instance()->get($cache_key, NULL, $cache_lifetime)) {
            $_columns_data = $result;
        }

        if( !isset($_columns_data)) {
            $_columns_data = $this->_db->list_columns($this->_table_name);
            Cache::instance()->set($cache_key, $_columns_data, $cache_lifetime);
        }

        return $_columns_data;
    }

	/**
	 * Add USE INDEX construction to ORM Query Builder
	 * @param $index
	 * @return $this
	 */
	public function use_index($index)
	{
		$this->_db_pending[] = array(
			'name' => 'use_index',
			'args' => array($index),
		);
		return $this;
	}
}