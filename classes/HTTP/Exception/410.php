<?php defined('SYSPATH') or die('No direct script access.');
class HTTP_Exception_410 extends Kohana_HTTP_Exception_410 {

    /**
     * Generate a Response for the 410 Exception.
     *
     * The user should be shown a nice 410 page.
     * 
     * @return Response
     */
    public function get_response()
    {
        return Request::factory('error/410/'. urlencode(Request::initial()->uri()) .'/' . $this->getMessage())->execute();
    }
}