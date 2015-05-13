<?php defined('SYSPATH') or die('No direct script access.');

if(!Route::cache()){
    Route::set('auth_admin', 'admin/<action>', array('action' => '(login|logout|register|profile)'))
        ->defaults(array(
            'directory' => 'admin',
            'controller' => 'main',
        ));
    Route::set('admin', 'admin(/<controller>(/<action>(/<id>)(/p<page>)))', array('aaction' => 'index|edit|delete|add|status', 'page' => '[0-9]+', 'id' => '[0-9]+'))
        ->defaults(array(
            'directory' => 'admin',
            'controller' => 'main',
            'action' => 'index',
        ));
}