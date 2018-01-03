<?php


/**
 * Class AssetsManager
 * Minify and concatenate sets of JS and CSS
 * --------------------------------------------------
 * Minify functions by https://gist.github.com/tovic/d7b310dea3b33e4732c0
 */
class AssetsManager{
    const ASSETS_CSS_PATH = 'assets/css/';
    const ASSETS_JS_PATH = 'assets/js/';

    const MINIFY_STRING = '"(?:[^"\\\]|\\\.)*"|\'(?:[^\'\\\]|\\\.)*\'';
    const MINIFY_COMMENT_CSS = '/\*[\s\S]*?\*/';
    const MINIFY_COMMENT_HTML = '<!\-{2}[\s\S]*?\-{2}>';
    const MINIFY_COMMENT_JS = '//[^\n]*';
    const MINIFY_PATTERN_JS = '/[^\n]+?/[gimuy]*';

    // escape character
    const X = "\x1A";

    public static $_instance;

    /* options */
//    public $protocol;
//    public $host;
    public $debug = FALSE; // Debug information in compresed file
    public $compress = TRUE; // compress assets content
    public $rewrites = array(); // paths to rewrited files (rewrited_mask => real_path)

    /* holders */
    public $styles = array();
    public $scripts = array();

    /**
     * Singleton initialization
     * @param $options
     * @return AssetsManager
     */
    public static function instance($options = array()){
        if(!self::$_instance instanceof AssetsManager)
            self::$_instance = new AssetsManager($options);
        return self::$_instance;
    }

    /**
     * AssetsManager constructor.
     * @param $options
     */
    private function __construct($options){
        $this->_init();
    }

    /**
     * Setup options
     * Look section `options` in class properties
     * @param $options
     */
    public function setup($options){
        foreach($options as $_option_id=>$_option){
            if(property_exists($this, $_option_id))
                $this->{$_option_id} = $_option;
        }
    }

    /**
     * Initialization
     */
    protected function _init(){
//        $this->protocol = isset($options['protocol']) ? $options['protocol'] : KoMS::config()->project['protocol'];
//        $this->host = isset($options['host']) ? $options['host'] : KoMS::config()->project['host'];
        $config = KoMS::config()->assets;
        if(is_array($config))
            $this->setup($config);
        if($this->debug)
            AssetsManager::cleanAssets();
    }

    /**************************
     * MAIN PART
     **************************/

    /**
     * Get styles array
     * @return array
     */
    public function getStyles(){
        if($this->compress && count($this->styles))
            $this->styles = $this->_compressStyles();
        return $this->styles;
    }

    /**
     * Get scripts array
     * @return array
     */
    public function getScripts(){
        if($this->compress && count($this->scripts))
            $this->scripts = $this->_compressScripts();
        return $this->scripts;
    }

    /**
     * Add style to array
     * @param $style
     */
    public function addStyle($style){
        $this->styles[] = $style;
    }

    /**
     * Add script to array
     * @param $script
     */
    public function addScript($script){
        $this->scripts[] = $script;
    }

    /**
     * Compress styles fileset
     * @return array
     */
    protected function _compressStyles(){
        $styles = "";
        if($this->debug){
            $styles = "/*Created while request: ".Request::current()->uri() . PHP_EOL . implode(PHP_EOL, $this->styles)."*/" . PHP_EOL . PHP_EOL;
        }

        $path_hash = self::ASSETS_CSS_PATH . md5(implode(',', $this->styles)).'.css';
        if(!file_exists(DOCROOT . $path_hash)) {
            foreach ($this->styles as $_style) {
	            $_style = $this->cleanFilename($_style);
                $_rewrited = !file_exists(DOCROOT . $_style) ? $this->applyRewrites($_style) : $_style;
//                $_rewrited = $this->cleanFilename($_rewrited);
                if (!file_exists(DOCROOT . $_rewrited))
                    continue;
                $content = file_get_contents(DOCROOT . $_rewrited);
                $content = $this->replaceCssUrls($content, $_style);
//                if(!$this->debug)
//                    $content = $this->fn_minify_css($content);
                // TODO: Look another CSS runtime minifier (with no problem BACKGROUND COLOR minify)
                if($this->debug)
                    $content = PHP_EOL .'/* Style from: '.$_style.' (rewrited: '.$_rewrited.') */' . PHP_EOL . $content;
                $styles .= PHP_EOL . $content;
            }
            file_put_contents(DOCROOT . $path_hash, $styles);
        }
        return array($path_hash);
    }

    protected function _compressScripts(){
        $scripts = "";
        if($this->debug){
            $scripts = "/* Created while request: ".Request::current()->action() . PHP_EOL . implode(PHP_EOL, $this->scripts)."*/" . PHP_EOL . PHP_EOL;
        }

        $path_hash = self::ASSETS_JS_PATH . md5(implode(',', $this->scripts)).'.js';
        if(!file_exists(DOCROOT . $path_hash)) {
            foreach ($this->scripts as $_script) {
                $_rewrited = !file_exists(DOCROOT . $_script) ? $this->applyRewrites($_script) : $_script;
                $_rewrited = $this->cleanFilename($_rewrited);
                if (!file_exists(DOCROOT . $_rewrited))
                    continue;
                $content = file_get_contents(DOCROOT . $_rewrited);
//                if(!$this->debug)
//                    $content = $this->fn_minify_js($content);
                // TODO: Look another JS runtime minifier (with no problem Jquery minify)
                if($this->debug)
                    $content = PHP_EOL .'/* Style from: '.$_script.' (rewrited: '.$_rewrited.') */' . PHP_EOL . $content;
                $scripts .= '{'.PHP_EOL . $content.'}';
            }
            file_put_contents(DOCROOT . $path_hash, $scripts);
        }
        return array($path_hash);
    }

    /**
     * Apply rewrite masks to path
     * @param $path
     * @return mixed
     */
    public function applyRewrites($path){
        foreach($this->rewrites as $_rewrite_path=>$_real_path)
            $path = preg_replace('~'.$_rewrite_path.'~', $_real_path, $path);
        return $path;
    }

    /**
     * Clean filename of query parameters
     * and check before if filename not URL
     * @param $filename
     * @return array
     */
    public function cleanFilename($filename){
        if(!filter_var($filename, FILTER_VALIDATE_URL) && FALSE !== ($q = strchr($filename, '?', TRUE)))
            $filename = $q;
        return $filename;
    }

    /**
     * Replace relative paths to absolute
     * @param $content
     * @param $source_path - path to style file
     * @return mixed
     */
    public function replaceCssUrls($content, $source_path){
        $source_path = DIRECTORY_SEPARATOR . dirname($source_path) . DIRECTORY_SEPARATOR;
        $content = preg_replace('~url\([\'"]?([\w.]{1}[^")\']+)[\'"]?\)~', 'url("'.$source_path.'$1")', $content);
        return $content;
    }

    /**
     * Clean assets JS and CSS folders
     */
    public static function cleanAssets(){
        $files = glob(DOCROOT . self::ASSETS_CSS_PATH . '*.css');
        foreach($files as $_file)
            unlink($_file);
        $files = glob(DOCROOT . self::ASSETS_JS_PATH . '*.js');
        foreach($files as $_file)
            unlink($_file);
    }

    /**************************
     *
     * MINIFY PART
     **************************/

    /**
     * Minify CSS content
     * @param $input
     * @param int $comment
     * @param int $quote
     * @return mixed|string
     */
    public function fn_minify_css($input, $comment = 2, $quote = 2) {
        if (!is_string($input) || !$input = $this->norm_br(trim($input))) return $input;
        $output = $prev = "";
        foreach ($this->fn_minify([self::MINIFY_COMMENT_CSS, self::MINIFY_STRING], $input) as $part) {
            if (trim($part) === "") continue;
            if ($comment !== 1 && strpos($part, '/*') === 0 && substr($part, -2) === '*/') {
                if (
                    $comment === 2 && (
                        // Detect special comment(s) from the third character. It should be a `!` or `*` → `/*! keep */` or `/** keep */`
                        strpos('*!', $part[2]) !== false ||
                        // Detect license comment(s) from the content. It should contains character(s) like `@license`
                        stripos($part, '@licence') !== false || // noun
                        stripos($part, '@license') !== false || // verb
                        stripos($part, '@preserve') !== false
                    )
                ) {
                    $output .= $part;
                }
                continue;
            }
            if ($part[0] === '"' && substr($part, -1) === '"' || $part[0] === "'" && substr($part, -1) === "'") {
                // Remove quote(s) where possible …
                $q = $part[0];
                if (
                    $quote !== 1 && (
                        // <https://www.w3.org/TR/CSS2/syndata.html#uri>
                        substr($prev, -4) === 'url(' && preg_match('#\burl\($#', $prev) ||
                        // <https://www.w3.org/TR/CSS2/syndata.html#characters>
                        substr($prev, -1) === '=' && preg_match('#^' . $q . '[a-zA-Z_][\w-]*?' . $q . '$#', $part)
                    )
                ) {
                    $part = $this->trim_once($part, $q); // trim quote(s)
                }
                $output .= $part;
            } else {
                $output .= $this->fn_minify_css_union($part);
            }
            $prev = $part;
        }
        return trim($output);
    }

    /**
     * Minify CSS union
     * @param $input
     * @return string
     */
    public function fn_minify_css_union($input) {
        if (stripos($input, 'calc(') !== false) {
            // Keep important white–space(s) in `calc()`
            $input = preg_replace_callback('#\b(calc\()\s*(.*?)\s*\)#i', function($m) {
                return $m[1] . preg_replace('#\s+#', self::X, $m[2]) . ')';
            }, $input);
        }
        $input = preg_replace([
            // Fix case for `#foo<space>[bar="baz"]`, `#foo<space>*` and `#foo<space>:first-child` [^1]
            '#(?<=[\w])\s+(\*|\[|:[\w-]+)#',
            // Fix case for `[bar="baz"]<space>.foo`, `*<space>.foo`, `:nth-child(2)<space>.foo` and `@media<space>(foo: bar)<space>and<space>(baz: qux)` [^2]
            '#([*\]\)])\s+(?=[\w\#.])#', '#\b\s+\(#', '#\)\s+\b#',
            // Minify HEX color code … [^3]
            '#\#([a-f\d])\1([a-f\d])\2([a-f\d])\3\b#i',
            // Remove white–space(s) around punctuation(s) [^4]
            '#\s*([~!@*\(\)+=\{\}\[\]:;,>\/])\s*#',
            // Replace zero unit(s) with `0` [^5]
            '#\b(?:0\.)?0([a-z]+\b)#i',
            // Replace `0.6` with `.6` [^6]
            '#\b0+\.(\d+)#',
            // Replace `:0 0`, `:0 0 0` and `:0 0 0 0` with `:0` [^7]
            '#:(0\s+){0,3}0(?=[!,;\)\}]|$)#',
            // Replace `background(?:-position)?:(0|none)` with `background$1:0 0` [^8]
            '#\b(background(?:-position)?):(?:0|none)([;,\}])#i',
            // Replace `(border(?:-radius)?|outline):none` with `$1:0` [^9]
            '#\b(border(?:-radius)?|outline):none\b#i',
            // Remove empty selector(s) [^10]
            '#(^|[\{\}])(?:[^\{\}]+)\{\}#',
            // Remove the last semi–colon and replace multiple semi–colon(s) with a semi–colon [^11]
            '#;+([;\}])#',
            // Replace multiple white–space(s) with a space [^12]
            '#\s+#'
        ], [
            // [^1]
            self::X . '$1',
            // [^2]
            '$1' . self::X, self::X . '(', ')' . self::X,
            // [^3]
            '#$1$2$3',
            // [^4]
            '$1',
            // [^5]
            '0',
            // [^6]
            '.$1',
            // [^7]
            ':0',
            // [^8]
            '$1:0 0$2',
            // [^9]
            '$1:0',
            // [^10]
            '$1',
            // [^11]
            '$1',
            // [^12]
            ' '
        ], $input);
        return trim(str_replace(self::X, ' ', $input));
    }

    /**
     * Minify JS content
     * @param $input
     * @param int $comment
     * @return mixed|string
     */
    public function fn_minify_js($input, $comment = 2) { //, $quote = 2
        if (!is_string($input) || !$input = $this->norm_br(trim($input))) return $input;
        $output = $prev = "";
        foreach ($this->fn_minify([self::MINIFY_COMMENT_CSS, self::MINIFY_STRING, self::MINIFY_COMMENT_JS, self::MINIFY_PATTERN_JS], $input) as $part) {
            if (trim($part) === "") continue;
            if ($comment !== 1 && (
                    strpos($part, '//') === 0 || // Remove inline comment(s)
                    strpos($part, '/*') === 0 && substr($part, -2) === '*/'
                )) {
                if (
                    $comment === 2 && (
                        // Detect special comment(s) from the third character. It should be a `!` or `*` → `/*! keep */` or `/** keep */`
                        strpos('*!', $part[2]) !== false ||
                        // Detect license comment(s) from the content. It should contains character(s) like `@license`
                        stripos($part, '@licence') !== false || // noun
                        stripos($part, '@license') !== false || // verb
                        stripos($part, '@preserve') !== false
                    )
                ) {
                    $output .= $part;
                }
                continue;
            }
            if ($part[0] === '/' && (substr($part, -1) === '/' || preg_match('#\/[gimuy]*$#', $part))) {
            } else if ($part[0] === '"' && substr($part, -1) === '"' || $part[0] === "'" && substr($part, -1) === "'") {
                // TODO: Remove quote(s) where possible …
                $output .= $part;
            } else {
                $output .= $this->fn_minify_js_union($part);
            }
            $prev = $part;
        }
        return $output;
    }

    public function fn_minify_js_union($input) {
        return preg_replace([
            // Remove white–space(s) around punctuation(s) [^1]
            '#\s*([!%&*\(\)\-=+\[\]\{\}|;:,.<>?\/])\s*#',
            // Remove the last semi–colon and comma [^2]
            '#[;,]([\]\}])#',
            // Replace `true` with `!0` and `false` with `!1` [^3]
            '#\btrue\b#', '#\bfalse\b#', '#\b(return\s?)\s*\b#',
            // Replace `new Array(x)` with `[x]` … [^4]
            '#\b(?:new\s+)?Array\((.*?)\)#', '#\b(?:new\s+)?Object\((.*?)\)#'
        ], [
            // [^1]
            '$1',
            // [^2]
            '$1',
            // [^3]
            '!0', '!1', '$1',
            // [^4]
            '[$1]', '{$1}'
        ], $input);
    }


    /**
     * normalize line–break(s)
     * @param $s
     * @return mixed
     */
    public function norm_br($s) {
        return str_replace(["\r\n", "\r"], "\n", $s);
    }

    /**
     * trim once
     * @param $a
     * @param $b
     * @return string
     */
    public function trim_once($a, $b) {
        if ($a && strpos($a, $b) === 0 && substr($a, -strlen($b)) === $b) {
            return substr(substr($a, strlen($b)), 0, -strlen($b));
        }
        return $a;
    }

    /**
     * Minify action
     * @param $pattern
     * @param $input
     * @return array
     */
    public function fn_minify($pattern, $input) {
        return preg_split('#(' . implode('|', $pattern) . ')#', $input, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
    }

}