<?php

if(!function_exists('st_vc_amadeus_search_flight')){
    function st_vc_amadeus_search_flight($attr,$content=false)
    {
        $data = shortcode_atts(
            array(
                'st_style_search' =>'style_1',
                'st_direction'=>'horizontal',
                'st_box_shadow'=>'no',
                'st_search_tabs'=>'yes',
                'st_title_search'=>'',
                'field_size'    =>'lg',
                'active'            =>1
            ), $attr, 'st_single_search' );
        extract($data);
        $txt = st_amadeus_load_view('elements/st-amadeus-search-flight/search','form',array('data'=>$data));
        return $txt;
    }
}
st_reg_shortcode('st_amadeus_search_flight','st_vc_amadeus_search_flight');