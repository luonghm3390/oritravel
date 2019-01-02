<?php
/**
 * Created by PhpStorm.
 * User: Dannie
 * Date: 8/30/2018
 * Time: 2:45 PM
 */
add_action( 'vc_before_init', 'st_map_amadeus_shortcodes' );

function st_map_amadeus_shortcodes()
{
    vc_map( array(
        "name" => __("ST Search Flight", ST_TEXTDOMAIN),
        "base" => "st_amadeus_search_flight",
        "content_element" => true,
        "icon" => "icon-st",
        "category"=>"Amadeus",
        "params" => array(
            array(
                "type" => "textfield",
                "holder" => "div",
                "heading" => __("Title form search", ST_TEXTDOMAIN),
                "param_name" => "st_title_search",
                "description" =>"",
            ),
            array(
                "type" => "dropdown",
                "holder" => "div",
                "heading" => __("Form's direction", ST_TEXTDOMAIN),
                "param_name" => "st_direction",
                "description" =>"",
                'value'=>array(
                    __('--Select--',ST_TEXTDOMAIN)=>'',
                    __('Vertical form',ST_TEXTDOMAIN)=>'vertical',
                    __('Horizontal form',ST_TEXTDOMAIN)=>'horizontal'
                ),
            ),
            array(
                "type" => "dropdown",
                "holder" => "div",
                "heading" => __("Style", ST_TEXTDOMAIN),
                "param_name" => "st_style_search",
                "description" =>"",
                'value'=>array(
                    __('--Select--',ST_TEXTDOMAIN)=>'',
                    __('Large',ST_TEXTDOMAIN)=>'style_1',
                    __('Normal',ST_TEXTDOMAIN)=>'style_2',
                )
            ),
            array(
                "type" => "dropdown",
                "holder" => "div",
                "heading" => __("Show border box", ST_TEXTDOMAIN),
                "param_name" => "st_box_shadow",
                "description" =>"",
                'value'=>array(
                    __('--Select--',ST_TEXTDOMAIN)=>'',
                    __('No',ST_TEXTDOMAIN)=>'no',
                    __('Yes',ST_TEXTDOMAIN)=>'yes'
                ),
            ),
            array(
                "type" => "dropdown",
                "holder" => "div",
                "heading" => __("Field Size", ST_TEXTDOMAIN),
                "param_name" => "field_size",
                "description" =>"",
                'value'=>array(
                    __('--Select--',ST_TEXTDOMAIN)=>'',
                    __('Large',ST_TEXTDOMAIN)=>'lg',
                    __('Normal',ST_TEXTDOMAIN)=>'sm',
                )
            ),
        )
    ) );
    vc_map( array(
        "name" => __("ST Search Flight Result", ST_TEXTDOMAIN),
        "base" => "st_amadeus_search_flight_content",
        "content_element" => true,
        "icon" => "icon-st",
        "category"=>"Amadeus",
        "params" => array(
            array(
                "type"             => "textfield" ,
                'admin_label' => true,
                "heading"          => __( "Title" , ST_TEXTDOMAIN ) ,
                "param_name"       => "st_title" ,
                "description"      => "" ,
                "value"            => "" ,
                'edit_field_class' => 'vc_col-sm-6' ,
            ) ,
            array(
                "type"             => "dropdown" ,
                'admin_label' => true,
                "heading"          => __( "Font Size" , ST_TEXTDOMAIN ) ,
                "param_name"       => "st_font_size" ,
                "description"      => "" ,
                "value"            => array(
                    __('--Select--',ST_TEXTDOMAIN)=>'',
                    __( "H1" , ST_TEXTDOMAIN ) => '1' ,
                    __( "H2" , ST_TEXTDOMAIN ) => '2' ,
                    __( "H3" , ST_TEXTDOMAIN ) => '3' ,
                    __( "H4" , ST_TEXTDOMAIN ) => '4' ,
                    __( "H5" , ST_TEXTDOMAIN ) => '5' ,
                    __( "H6" , ST_TEXTDOMAIN ) => '6' ,
                ) ,
                'edit_field_class' => 'vc_col-sm-6' ,
            ) ,
            array(
                "type"        => "textfield" ,
                'admin_label' => true,
                "heading"     => __( "Number of flight" , ST_TEXTDOMAIN ) ,
                "param_name"  => "st_number_flight" ,
                "description" => "" ,
                'value'       => 10 ,
            )
        )
    ) );

}
