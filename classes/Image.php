<?php
/**
 * Created by JetBrains PhpStorm.
 * User: butch
 * Date: 21.07.12
 * Time: 17:28
 * To change this template use File | Settings | File Templates.
 */

abstract class Image extends Kohana_Image{

    CONST WATERMARK_TOP_LEFT = 1;
    CONST WATERMARK_TOP_RIGHT = 2;
    CONST WATERMARK_BOTTOM_LEFT = 3;
    CONST WATERMARK_BOTTOM_RIGHT = 4;
    CONST WATERMARK_CENTER = 5;
    CONST WATERMARK_CENTER_TOP = 6;
    CONST WATERMARK_CENTER_BOTTOM = 7;

    public function smart_resize($width, $height){
        $this->_do_smart_resize($width, $height);
    }

    public function smart_watermark($mark, $position, $opacity){
        $this->_do_smart_watermark($mark, $position, $opacity);
    }

    /**
     * Resize image to fixed size
     * @param $width
     * @param $height
     * @param bool $crop
     */
    public function image_fixed_resize($width, $height, $crop = true){
        $ratio = $this->width / $this->height;
        $original_ratio = $width / $height;

        $crop_width = $this->width;
        $crop_height = $this->height;

        if($ratio > $original_ratio)
        {
            $crop_width = round($original_ratio * $crop_height);
        }
        else
        {
            $crop_height = round($crop_width / $original_ratio);
        }
        if($crop)
            $this->crop($crop_width, $crop_height);
        $this->resize($width, $height);
    }

    /**
     * Set max image edges size
     * @param $maxwidth
     * @param int $maxheight
     */
    public function image_set_max_edges($maxwidth, $maxheight = 0){
        if(!$maxheight)
            $maxheight = $maxwidth;
        if($this->width > $maxwidth)
            $this->resize($maxwidth);
        if($this->height > $maxheight)
            $this->resize(NULL, $maxheight);
    }

    /**
     * @return bool
     */
    public function findExtension() {
        $types = array(
            1 => 'gif',
            2 => 'jpg',
            3 => 'png',
            4 => 'swf',
            5 => 'psd',
            6 => 'bmp',
            7 => 'tiff',
            8 => 'tiff',
            9 => 'jpc',
            10 => 'jp2',
            11 => 'jpX',
            12 => 'jb2',
            13 => 'swc',
            14 => 'iff',
            15 => 'wbmp',
            16 => 'xbm'
        );
        if(isset($this->type) && isset($types[$this->type]))
            return $types[$this->type];
        return false;

    }

    /**
     * Check for file is image
     * @param $file
     * @return bool
     */
    public static function isImage($file){
        if(is_file($file)){
            $info = getimagesize($file);
            if(isset($info) && $info[2] > 0)
                return true;
        }
        return false;
    }

    /**
     * Check image URL exists
     * @param $url
     * @return bool
     */
    public static function urlImageExists($url){
        if(strpos($url,'http://') === false)
            return false;
        $headers = @get_headers($url);
        if(is_array($headers))
            return strpos($headers[0],'200') !== false;
    }

    public static function getImageTag($path, $alt= '', $attributes = array()){
        $base = KoMS::config()->project['protocol'] == 'https' ? '//'.$_SERVER['HTTP_HOST'].'/' : Kohana::$base_url;
        $attributes['src'] = $base . $path;
        $attributes['alt'] = $alt;
        $attributes['title'] = $alt;
        if($attributes['src'])
            return "<img ".HTML::attributes($attributes).">";
        return NULL;
    }
}