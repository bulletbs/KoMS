<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Contains the most low-level helpers methods in Kohana:
 */
class Wysiwyg {

    /**
     * TinyMCEInitFLag
     * @var bool
     */
    public static  $TinyMCEInit = false;

    /**
     * SettingUp CKeditor
     * @param $name
     * @param string $value
     * @param null $customConfig
     * @return string
     */
    public static function Ckeditor($name, $value = '', $customConfig = NULL){
        $url_base = URL::base();

        /* Loading config */
        $config = Kohana::$config->load('ckeditor.default');
        if($customConfig){
            $customConfig = (array) Kohana::$config->load('ckeditor.' . $customConfig);
            $config = Arr::merge($config, $customConfig);
        }


        /* Creating editor */
        include_once(DOCROOT . $config['basePath'] . 'ckeditor.php');

        $CKEditor = new CKEditor();
        $CKEditor->basePath = $url_base . $config['basePath'];

        $CKEditor->config['height'] = $config['height'] . 'px';
        $CKEditor->config['width']  = $config['width'] . 'px';
        $CKEditor->config['contentsCss'][] = $url_base . $config['css'];

        if(isset($config['allow_manager']) && $config['allow_manager'] == true){
            $CKEditor->config['filebrowserBrowseUrl']      = $url_base . 'media/libs/ckeditor/ckfinder/ckfinder.html';
            $CKEditor->config['filebrowserImageBrowseUrl'] = $url_base . 'media/libs/ckeditor/ckfinder/ckfinder.html?type=Images';
            $CKEditor->config['filebrowserFlashBrowseUrl'] = $url_base . 'media/libs/ckeditor/ckfinder/ckfinder.html?type=Flash';
            $CKEditor->config['filebrowserUploadUrl']      = $url_base . 'media/libs/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files';
            $CKEditor->config['filebrowserImageUploadUrl'] = $url_base . 'media/libs/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images';
            $CKEditor->config['filebrowserFlashUploadUrl'] = $url_base . 'media/libs/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash';
            $_SESSION['wysiwyg_access'] = TRUE;
//            Session::instance()->set('wysiwyg_access', TRUE);

        }

        /* Output editor */
        ob_start();
        $CKEditor->editor($name, $value, $config);
        if(isset($config['additional_scripts']) && count($config['additional_scripts'])){
            foreach($config['additional_scripts'] as $script)
                echo HTML::script($url_base . $script);
        }
        return ob_get_clean();
    }

    /**
     * SettingUp TinyMCE editor
     * @param $name
     * @param string $value
     * @param null $customConfig
     * @return string
     */
    public static function TinyMCE($name, $value = '', $customConfig = NULL){
        $result = '';

        /* Loading config */
        $config = Kohana::$config->load('tinymce.default');
        if($customConfig){
            $customConfig = (array) Kohana::$config->load('tinymce.' . $customConfig);
            $config = Arr::merge($config, $customConfig);
        }

        /* First initial TinyMCE */
        if(!Wysiwyg::$TinyMCEInit){
            $result .= HTML::script(URL::base() . '/media/libs/tinymce/tinymce.min.js');
            $result .= HTML::script(URL::base() . '/media/libs/tinymce/jquery.tinymce.min.js');
            Wysiwyg::$TinyMCEInit = true;
        }

        /* Output editor */
        $result .= Form::textarea($name, $value, array('id'=>'tinyMCE'.$name));
        $result .= '<script type="text/javascript">
        $(document).ready(function(){
        $("#tinyMCE'.$name.'").tinymce({
            height:"'.$config['height'].'px",'
            .(isset($config['theme']) ? 'theme : "'.$config['theme'].'",'.PHP_EOL : '')
            .(isset($config['mode']) ? 'mode : "'.$config['mode'].'",'.PHP_EOL : '')
            .(isset($config['plugins']) ? 'plugins : "'.$config['plugins'].'",'.PHP_EOL : '')
            .(isset($config['toolbar_location']) ? 'theme_advanced_toolbar_location : "'.$config['toolbar_location'].'",'.PHP_EOL : '')
            .(isset($config['toolbar_align']) ? 'theme_advanced_toolbar_align : "'.$config['toolbar_align'].'",'.PHP_EOL : '')
            .(isset($config['buttons1']) ? 'theme_advanced_buttons1 : "'.$config['buttons1'].'",'.PHP_EOL : '')
            .(isset($config['buttons2']) ? 'theme_advanced_buttons2 : "'.$config['buttons2'].'",'.PHP_EOL : '')
            .(isset($config['buttons3']) ? 'theme_advanced_buttons3 : "'.$config['buttons3'].'",'.PHP_EOL : '').
            'language: "ru",
            plugins: [
                 "jbimages advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
                 "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                 "save table contextmenu directionality emoticons template paste textcolor"
            ],
            toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | l ink image jbimages | print preview media fullpage | forecolor backcolor emoticons",
            width:"'.$config['width'].'px"
         });
         });
        </script>';
        return $result;
    }
}
