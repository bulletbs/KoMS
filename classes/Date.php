<?php defined('SYSPATH') or die('No direct script access.');

class Date extends Kohana_Date{

    static $of_months = array(
        '', 'января', 'февраля', 'марта', 'апреля', 'мая', 'июня',
        'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря');
    static $months = array(
        '', 'январь', 'февраль', 'марть', 'апрель', 'май', 'июнь',
        'июль', 'август', 'сентябрь', 'октябрь', 'ноябрь', 'декабрь');

    /**
     * Выводит дату
     * @param $timestamp
     * @return bool|string
     */
    public static function smart_date($timestamp) {
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
        $month = self::$of_months[date('n',$date)];

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
        $month = self::$of_months[date('n',$date)];

        //Года
        if(date('Y') != date('Y', $date)) $year = 'Y г.';
        else $year = '';

        $result_date = date('j '.$month.' '.$year.$time, $date);
        return $result_date;
    }

    /**
     * Возвращает текущий месяц
     * @param $name_of - месяц в родительском падеже
     * @param $ucfirst - большая первая буква месяца
     * @return mixed
     */
    public static function currentMonth($name_of = false, $ucfirst = false){
        $month = $name_of ? self::$of_months[(int) date('m')] : self::$months[(int) date('m')];
        if($ucfirst)
            $month = Text::ucfirst($month);
        return $month;
    }
}