<?php
/**
 * Created by wpbooking.
 * Developer: nasanji
 * Date: 6/23/2017
 * Version: 1.0
 */

if(!class_exists('ST_Amadeus_Flight')){
    class ST_Amadeus_Flight{

        static $_inst;

        function __construct()
        {

        }

        function get_data_attribute($data){
            $attr_str = '';
            if(!empty($data)){
                foreach ($data as $k => $v){
                    if($v != ''){
                        $attr_str .= ' data-' . $k . '=' . '"'. $v .'"';
                    }
                }
            }
            return $attr_str;
        }

        static function inst(){
            if(!self::$_inst)
                self::$_inst = new self();

            return self::$_inst;
        }
    }

    ST_Amadeus_Flight::inst();
}