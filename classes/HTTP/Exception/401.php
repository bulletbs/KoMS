<?php defined('SYSPATH') or die('No direct script access.');
class HTTP_Exception_401 extends Kohana_HTTP_Exception_401 {

    /**
     * Generate a Response for the 401 Exception.
     *
     * The user should be redirect to a login page.
     *
     * @return Response
     */
    public function get_response()
    {
        return Request::factory('error/401/'. urlencode(Request::initial()->uri()) .'/' . $this->getMessage())->execute();
    }
}