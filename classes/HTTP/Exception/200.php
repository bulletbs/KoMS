<?php defined('SYSPATH') or die('No direct script access.');
class HTTP_Exception_200 extends HTTP_Exception {

	protected $_code = 200;

    /**
     * Generate a Response for the 200 Exception.
     *
     * @return Response
     */
    public function get_response()
    {
        return Request::factory('error/200/'. urlencode(Request::initial()->uri()) .'/' . $this->getMessage())->execute();
    }
}