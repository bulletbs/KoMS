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
        imagedestroy($this->_image);
        $this->_image = $final;
    }

    /**
     * Make pixel by pixel perfect watermark
     * @param Image_GD $watermark_image
     * @param $place_variant – вариант расположения
     *    1. Верх - лево
     *    2. Верх право
     *    3. Низ лево
     *    4. Верх право
     *    5. Центр
     *    6. Центр + выше
     *    7. Центр + ниже
     * @param int $alpha_level
     */
    protected function _do_smart_watermark($watermark_image, $place_variant=1, $alpha_level = 30){
        $this->_load_image();

        $alpha_level	/= 100;	# convert 0-100 (%) alpha to decimal
        # calculate our images dimensions
        $main_img_obj_w	        = $this->width;
        $main_img_obj_h	        = $this->height;
        $watermark_img_obj_w	= $watermark_image->width;
        $watermark_img_obj_h	= $watermark_image->height;
        $watermark_image = $watermark_image->getImage();

        # determine center position coordinates
        $margin_x = 15;
        $margin_y = 15;
        switch ($place_variant) {
            default:
            case 1:
                $main_img_obj_min_x = $margin_x;
                $main_img_obj_min_y = $margin_y;
                break;
            case 2:
                $main_img_obj_min_x = $main_img_obj_w - ($watermark_img_obj_w + $margin_x);
                $main_img_obj_min_y = $margin_y;
                break;
            case 3:
                $main_img_obj_min_x = $margin_x;
                $main_img_obj_min_y = $main_img_obj_h - ($watermark_img_obj_h + $margin_y);
                break;
            case 4:
                $main_img_obj_min_x = $main_img_obj_w - ($watermark_img_obj_w + $margin_x);
                $main_img_obj_min_y = $main_img_obj_h - ($watermark_img_obj_h + $margin_y);
                break;
            case 5:
                $main_img_obj_min_x = round(($main_img_obj_w - $watermark_img_obj_w) / 2);
                $main_img_obj_min_y = round(($main_img_obj_h - $watermark_img_obj_h) / 2);
                break;
            case 6:
                $main_img_obj_min_x = round(($main_img_obj_w - $watermark_img_obj_w) / 2);
                $main_img_obj_min_y = round(($main_img_obj_h - $watermark_img_obj_h) / 2 - floor($main_img_obj_h / 5));
                break;
            case 7:
                $main_img_obj_min_x = round(($main_img_obj_w - $watermark_img_obj_w) / 2);
                $main_img_obj_min_y = round(($main_img_obj_h - $watermark_img_obj_h) / 2 + floor($main_img_obj_h / 5));
                break;
        }
        $main_img_obj_max_x	= min($main_img_obj_w, $main_img_obj_min_x + $watermark_img_obj_w);
        $main_img_obj_max_y	= min($main_img_obj_h, $main_img_obj_min_y + $watermark_img_obj_h);

        # walk through main image
        for( $y = $main_img_obj_min_y; $y < $main_img_obj_max_y; $y++ ) {
            for( $x = $main_img_obj_min_x; $x < $main_img_obj_max_x; $x++ ) {
                $return_color	= NULL;

                # determine the correct pixel location within our watermark
                $watermark_x	= $x - $main_img_obj_min_x;
                $watermark_y	= $y - $main_img_obj_min_y;

                # fetch color information for both of our images
                $main_rgb = imagecolorsforindex( $this->_image, imagecolorat( $this->_image, $x, $y ) );

                # if our watermark has a non-transparent value at this pixel intersection
                # and we're still within the bounds of the watermark image
                if (	$watermark_x >= 0 && $watermark_x < $watermark_img_obj_w &&
                    $watermark_y >= 0 && $watermark_y < $watermark_img_obj_h ) {
                    $watermark_rbg = imagecolorsforindex( $watermark_image, imagecolorat( $watermark_image, $watermark_x, $watermark_y ) );

                    # using image alpha, and user specified alpha, calculate average
                    $watermark_alpha	= round( ( ( 127 - $watermark_rbg['alpha'] ) / 127 ), 2 );
                    $watermark_alpha	= $watermark_alpha * $alpha_level;

                    # calculate the color 'average' between the two - taking into account the specified alpha level
                    $avg_red	= $this->_get_ave_color( $main_rgb['red'],		$watermark_rbg['red'],		$watermark_alpha );
                    $avg_green	= $this->_get_ave_color( $main_rgb['green'],	$watermark_rbg['green'],	$watermark_alpha );
                    $avg_blue	= $this->_get_ave_color( $main_rgb['blue'],	$watermark_rbg['blue'],		$watermark_alpha );

                    # calculate a color index value using the average RGB values we've determined
                    $return_color	= $this->_get_image_color( $this->_image, $avg_red, $avg_green, $avg_blue );

                    # if we're not dealing with an average color here, then let's just copy over the main color
                } else {
                    $return_color	= imagecolorat( $this->_image, $x, $y );

                } # END if watermark
                imagesetpixel( $this->_image, $x, $y, $return_color );

            } # END for each X pixel
        } # END for each Y pixel
    }

    /**
     * Gets averaged color (used by _do_smart_watermark)
     * @param $color_a
     * @param $color_b
     * @param $alpha_level
     * @return float
     */
    protected function _get_ave_color( $color_a, $color_b, $alpha_level ) {
        return round( ( ( $color_a * ( 1 - $alpha_level ) ) + ( $color_b	* $alpha_level ) ) );
    }

    /**
     * Get closest color (used by _do_smart_watermark)
     * @param $im
     * @param $r
     * @param $g
     * @param $b
     * @return int
     */
    protected function _get_image_color($im, $r, $g, $b) {
        $c=imagecolorexact($im, $r, $g, $b);
        if ($c!=-1) return $c;
        $c=imagecolorallocate($im, $r, $g, $b);
        if ($c!=-1) return $c;
        return imagecolorclosest($im, $r, $g, $b);
    }

    /**
     * Return image object
     * @return string
     */
    public function getImage(){
        $this->_load_image();
        return $this->_image;
    }
}