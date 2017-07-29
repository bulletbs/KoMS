<?php defined('SYSPATH') or die('No direct script access');

return array(
    'project'=>array(
        'name'=>'Doreno',
        'host' => Kohana::$environment == Kohana::DEVELOPMENT ? 'sellmania.local' : 'doreno.ru',
        'protocol' => 'http',
        'mobile_subdomain' => FALSE,
    ),
    'view' => array(
        'title'=>'Доска бесплатных объявлений Doreno',
        'keywords'=>'подать объявление, доска объявлений, объявления, бесплатные объявления, рекламные объявления',
        'description'=>'Doreno - Доска бесплатных объявлений России. Здесь вы можете подать объявление на любую интересующую вас тему, будь то покупка или продажа товаров, предоставление услуг, поиск работы. Разместить объявление может каждый желающий абсолютно бесплатно.',
    ),
    'styles'=>array(
        'media/libs/pure-release-0.6.0/buttons-min.css',
        'media/libs/pure-release-0.6.0/pure-extras.css',
        'media/libs/font-awesome-4.5.0/css/font-awesome.min.css',
        'media/css/style.css',
    ),
    'scripts'=>array(
        'media/libs/jquery-1.11.1.min.js',
    ),
    'assets'=>array(
        'debug' => FALSE,
        'compress' => TRUE,
        'rewrites' => array(
            'assets/([^/]*)/(.*)' => 'modules/$1/assets/$2',
        ),
    ),

    'breadcrumb_root'=>'Все объявления России',

    'robot_email' => 'info@doreno.ru',
    'contact_email' => 'vip@doreno.ru',
    'admin_id' => 'vip@doreno.ru',
);
