<?php defined('SYSPATH') or die('No direct script access.');

class Date extends Kohana_Date{

    /**
     * Выводит дату
     * @param $timestamp
     * @return bool|string
     */
    public static function smart_date($timestamp) {
        $monthes = array(
            '', 'января', 'февраля', 'марта', 'апреля', 'мая', 'июня',
            'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря'
        );

        //Время
//        $time = ' G:i';
        $time = '';
        $date = $timestamp;

        //Сегодня, вчера, завтра
        if(date('Y') == date('Y',$date)) {
            if(date('z') == date('z', $date)) {
                $result_date = date('Сегодня'.$time, $date);
            } elseif(date('z') == date('z',mktime(0,0,0,date('n',$date),date('j',$date)+1,date('Y',$date)))) {
                $result_date = date('Вчера'.$time, $date);
            } elseif(date('z') == date('z',mktime(0,0,0,date('n',$date),date('j',$date)-1,date('Y',$date)))) {
                $result_date = date('Завтра'.$time, $date);
            }

            if(isset($result_date)) return $result_date;
        }

        //Месяца
        $month = $monthes[date('n',$date)];

        //Года
        if(date('Y') != date('Y', $date)) $year = 'Y г.';
        else $year = '';

        $result_date = date('j '.$month.' '.$year.$time, $date);
        return $result_date;
    }

    /**
     * Выводит дату
     * @param $timestamp
     * @return bool|string
     */
    public static function smart_datetime($timestamp) {
        $monthes = array(
            '', 'января', 'февраля', 'марта', 'апреля', 'мая', 'июня',
            'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря'
        );

        //Время
        $time = ' G:i';
        $date = $timestamp;

        //Сегодня, вчера, завтра
        if(date('Y') == date('Y',$date)) {
            if(date('z') == date('z', $date)) {
                $result_date = date('Сегодня'.$time, $date);
            } elseif(date('z') == date('z',mktime(0,0,0,date('n',$date),date('j',$date)+1,date('Y',$date)))) {
                $result_date = date('Вчера'.$time, $date);
            } elseif(date('z') == date('z',mktime(0,0,0,date('n',$date),date('j',$date)-1,date('Y',$date)))) {
                $result_date = date('Завтра'.$time, $date);
            }

            if(isset($result_date)) return $result_date;
        }

        //Месяца
        $month = $monthes[date('n',$date)];

        //Года
        if(date('Y') != date('Y', $date)) $year = 'Y г.';
        else $year = '';

        $result_date = date('j '.$month.' '.$year.$time, $date);
        return $result_date;
    }
}