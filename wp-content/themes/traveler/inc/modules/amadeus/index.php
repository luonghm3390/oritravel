<?php
/**
 * Created by wpbooking.
 * Developer: nasanji
 * Date: 6/7/2017
 * Version: 1.0
 */

if(!class_exists('ST_Amadeus')){
    class ST_Amadeus{

        static $_inst;

        protected $dir;

        function __construct()
        {
            $this->dir = dirname(__FILE__);
            $this->loadModels();
            $this->loadHelpers();
            $this->loadController();
            add_action('init', array($this, 'loadElements'));
        }

        function loadModels()
        {
            $models=glob($this->dir.'/models/*');

            if(!is_array($models) or empty($models)) return false;

            if(!empty($models))
            {
                foreach($models as $key => $value)
                {
                    $dirname = basename($value, '.php');

                    $file = ST_TRAVELER_DIR.'/'.$this->dir_name('models/'.$dirname).'.php';

                    if(file_exists($file)) include_once $file;
                }
            }

            return true;
        }

        function loadController()
        {
            $files = glob($this->dir.'/controllers/*');

            if(!is_array($files) or empty($files)) return false;

            if(!empty($files))
            {
                foreach($files as $key => $value)
                {
                    $dirname = basename($value, '.php');

                    $file = ST_TRAVELER_DIR.'/'.$this->dir_name('controllers/'.$dirname).'.php';

                    if(file_exists($file)) include_once $file;
                }
            }

            return true;
        }

        function loadHelpers()
        {
            $files = glob($this->dir.'/helpers/*');

            if(!is_array($files) or empty($files)) return false;

            if(!empty($files))
            {
                foreach($files as $key => $value)
                {
                    $dirname = basename($value, '.php');

                    $file = ST_TRAVELER_DIR.'/'.$this->dir_name('helpers/'.$dirname).'.php';

                    if(file_exists($file)) include_once $file;
                }
            }

            return true;
        }

        function loadElements(){

            $files = glob($this->dir.'/vc-elements/*');

            if(!is_array($files) or empty($files)) return false;

            if(!empty($files)){
                foreach($files as $key => $val){
                    $dirname = basename($val, '.php');

                    $file = ST_TRAVELER_DIR.'/'.$this->dir_name('vc-elements/'.$dirname).'.php';

                    if (!function_exists('vc_map') or !function_exists('st_reg_shortcode')) return false;

                    if(file_exists($file)) include_once $file;
                }
            }

            return true;
        }

        function dir_name($url=false)
        {
            return "inc/modules/amadeus/".$url;
        }

        static function inst(){
            if(!self::$_inst)
                self::$_inst = new self();

            return self::$_inst;
        }
    }
    ST_Amadeus::inst();
}