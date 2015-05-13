<?php
/**
 * Created by JetBrains PhpStorm.
 * User: butch
 * Date: 21.07.12
 * Time: 17:28
 * To change this template use File | Settings | File Templates.
 */

abstract class Image extends Kohana_Image{


    public function smart_resize($width, $height){
        $this->_do_smart_resize($width, $height);
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
        if(is_file($file))
            $info = getimagesize($file);
        if(isset($info) && $info[2] > 0)
            return true;
        return false;
    }
}