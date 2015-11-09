<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Text helper class. Provides simple methods for working with text.
 *
 * @package    Kohana
 * @category   Helpers
 * @copyright  (c) 2013 Bullet Factory
 * @license    http://kohanaframework.org/license
 */
class Text extends Kohana_Text {

    /**
     * String transliteration from cyrillic to latin
     * @param $source
     * @param bool $lowercase - lowecase result string
     * @return string
     */
    public static function transliterate($source, $lowercase = false){
        $mapEn = explode('|', '||||i|I|yi|Yi|ye|Ye|eh|yu|ya|sch|ch|sh|kh|e|zh|a|b|v|g|d|e|z|i|j|k|l|m|n|o|p|r|s|t|u|f|ts|y|Eh|Ju|Ja|Sch|Ch|Sh|Kh|Jo|Zh|A|B|V|G|D|E|Z|I|J|K|L|M|N|O|P|R|S|T|U|F|TS|Y');
        $mapRu = array_flip(explode('|', 'ь|Ь|ъ|Ъ|і|І|ї|Ї|є|Є|э|ю|я|щ|ч|ш|х|ё|ж|а|б|в|г|д|е|з|и|й|к|л|м|н|о|п|р|с|т|у|ф|ц|ы|Э|Ю|Я|Щ|Ч|Ш|Х|Ё|Ж|А|Б|В|Г|Д|Е|З|И|Й|К|Л|М|Н|О|П|Р|С|Т|У|Ф|Ц|Ы'));

        $transliterated = '';
        for($i=0; $i < mb_strlen($source, 'UTF-8'); $i++){
            $letter = mb_substr($source, $i, 1, 'UTF-8');
            if(isset($mapRu[$letter]))
                $transliterated .= $mapEn[$mapRu[$letter]];
            elseif(preg_match('~[0-9a-zA-Z]~', $letter))
                $transliterated .= $lowercase ? mb_strtolower($letter) : $letter;
            elseif(mb_substr($transliterated, -1, 1, 'UTF-8') != '-')
                $transliterated .= '-';
        }
        if($lowercase)
            $transliterated = mb_strtolower($transliterated);

        return $transliterated;
    }

    /**
     * Multibyte ucfirst function analog
     * @param $str
     * @param string $encoding
     * @return string
     */
    public static function mb_ucfirst($str, $encoding='UTF-8'){
        if (!function_exists('mb_ucfirst') && extension_loaded('mbstring'))
        {
            $str = mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding).
                mb_substr($str, 1, mb_strlen($str), $encoding);
            return $str;
        }
        else
           return mb_ucfirst($str, $encoding);
    }
}