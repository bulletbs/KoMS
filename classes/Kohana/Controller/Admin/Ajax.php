<?php
/**
 * Created by JetBrains PhpStorm.
 * User: butch
 * Date: 20.07.12
 * Time: 11:02
 * To change this template use File | Settings | File Templates.
 */

class Kohana_Controller_Admin_Ajax extends Controller_System_Admin{

    public $auto_render = FALSE;

    public function action_index(){

    }

    public function action_ads_filters(){
        $this->json['status'] = TRUE;
    }
}