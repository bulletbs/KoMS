<?php
/**
 * Created by JetBrains PhpStorm.
 * User: butch
 * Date: 21.07.12
 * Time: 17:28
 * To change this template use File | Settings | File Templates.
 */

class Image_GD extends Kohana_Image_GD{

    protected function _do_smart_resize($tn_w, $tn_h)
    {
        $this->_load_image();

        #Figure out the dimensions of the image and the dimensions of the desired thumbnail
        $src_w = $this->width;
        $src_h = $this->height;

        #Do some math to figure out which way we'll need to crop the image
        #to get it proportional to the new size, then crop or adjust as needed
        $x_ratio = $tn_w / $src_w;
        $y_ratio = $tn_h / $src_h;

        if (($src_w <= $tn_w) && ($src_h <= $tn_h)) {
            $new_w = $src_w;
            $new_h = $src_h;
        } elseif (($x_ratio * $src_h) < $tn_h) {
            $new_h = ceil($x_ratio * $src_h);
            $new_w = $tn_w;
        } else {
            $new_w = ceil($y_ratio * $src_w);
            $new_h = $tn_h;
        }

        $newpic = imagecreatetruecolor(round($new_w), round($new_h));
        imagefill($newpic, 0, 0, imagecolorallocate($newpic, 0xFF, 0xFF, 0xFF));
        imagecopyresampled($newpic, $this->_image, 0, 0, 0, 0, $new_w, $new_h, $src_w, $src_h);
        $final = imagecreatetruecolor($tn_w, $tn_h);
        $backgroundColor = imagecolorallocate($final, 255, 255, 255);
        imagefill($final, 0, 0, $backgroundColor);
        imagecopy($final, $newpic, (($tn_w - $new_w)/ 2), (($tn_h - $new_h) / 2), 0, 0, $new_w, $new_h);

//        #if we need to add a watermark
//        if ($wmsource) {
//            #find out what type of image the watermark is
//            $info    = getimagesize($wmsource);
//            $imgtype = image_type_to_mime_type($info[2]);
//
//            #assuming the mime type is correct
//            switch ($imgtype) {
//                case 'image/jpeg':
//                    $watermark = imagecreatefromjpeg($wmsource);
//                    break;
//                case 'image/gif':
//                    $watermark = imagecreatefromgif($wmsource);
//                    break;
//                case 'image/png':
//                    $watermark = imagecreatefrompng($wmsource);
//                    break;
//                default:
//                    die('Invalid watermark type.');
//            }
//
//            #if we're adding a watermark, figure out the size of the watermark
//            #and then place the watermark image on the bottom right of the image
//            $wm_w = imagesx($watermark);
//            $wm_h = imagesy($watermark);
//            imagecopy($final, $watermark, $tn_w - $wm_w, $tn_h - $wm_h, 0, 0, $tn_w, $tn_h);
//
//        }
        imagedestroy($this->_image);
        $this->_image = $final;
    }
}