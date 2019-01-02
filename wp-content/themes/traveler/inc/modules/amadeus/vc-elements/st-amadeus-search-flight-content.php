<?php

if(!function_exists('st_vc_amadeus_search_flight_content')){
    function st_vc_amadeus_search_flight_content($attr,$content=false)
    {
        $data = array(
                'st_title'=> '',
                'st_font_size'=>'3',
                'st_number_flight' => 10
            );
        $attr    = wp_parse_args( $attr , $data );
        $txt = st_amadeus_load_view('elements/st-amadeus-search-flight-content/search','content',array('attr'=>$attr));
        return $txt;
    }
}
st_reg_shortcode('st_amadeus_search_flight_content','st_vc_amadeus_search_flight_content');