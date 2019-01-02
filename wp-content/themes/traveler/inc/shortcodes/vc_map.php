<?php
/**
 * Created by PhpStorm.
 * User: Dannie
 * Date: 8/30/2018
 * Time: 2:01 PM
 */
add_action( 'vc_before_init', 'st_map_general_shortcodes' );
add_action( 'vc_before_init', 'st_map_tour_shortcodes' );
//add_action( 'vc_before_init', 'st_map_rental_shortcodes' );
//add_action( 'vc_before_init', 'st_map_rental_room_shortcodes' );
//add_action( 'vc_before_init', 'st_map_hotel_shortcodes' );
add_action( 'vc_before_init', 'st_map_car_shortcodes' );
add_action( 'vc_before_init', 'st_map_activity_shortcodes' );


function st_map_activity_shortcodes()
{
    if(!st_check_service_available( 'st_activity' )) return;

    vc_map(
        array(
            'name' => __("ST Activity Thumbnail", ST_TEXTDOMAIN),
            'base' => 'st_thumbnail_activity',
            'content_element' => true,
            'icon' => 'icon-st',
            'category' => 'Activity',
            'show_settings_on_create' => false,
            'params'=>array(
                array(
                    'type' => 'textfield',
                    'heading' => esc_html__('There is no option in this element', ST_TEXTDOMAIN),
                    'param_name' => 'description_field',
                    'edit_field_class' => 'vc_column vc_col-sm-12 st_vc_hidden_input'
                )
            )
        )
    );

    vc_map(
        array(
            'name' => __("ST Activity Booking Form", ST_TEXTDOMAIN),
            'base' => 'st_form_book',
            'content_element' => true,
            'icon' => 'icon-st',
            'category' => 'Activity',
            'show_settings_on_create' => false,
            'params'=>array(
                array(
                    'type' => 'textfield',
                    'heading' => esc_html__('There is no option in this element', ST_TEXTDOMAIN),
                    'param_name' => 'description_field',
                    'edit_field_class' => 'vc_column vc_col-sm-12 st_vc_hidden_input'
                )
            )
        )
    );


    vc_map(
        array(
            'name' => __("ST Detailed Activity Map", ST_TEXTDOMAIN),
            'base' => 'st_activity_detail_map',
            'content_element' => true,
            'icon' => 'icon-st',
            'category' => 'Activity',
            'show_settings_on_create' => true,
            'params'=>array(
                array(
                    "type"        => "textfield" ,
                    "holder"      => "div" ,
                    "heading"     => __( "Range" , ST_TEXTDOMAIN ) ,
                    "param_name"  => "range" ,
                    "description" => "Km" ,
                    "value"       => "20" ,
                ) ,
                array(
                    "type"        => "textfield" ,
                    "holder"      => "div" ,
                    "heading"     => __( "Number" , ST_TEXTDOMAIN ) ,
                    "param_name"  => "number" ,
                    "description" => "" ,
                    "value"       => "12" ,
                ) ,
                array(
                    "type"        => "dropdown" ,
                    "holder"      => "div" ,
                    "heading"     => __( "Show Circle" , ST_TEXTDOMAIN ) ,
                    "param_name"  => "show_circle" ,
                    "description" => "" ,
                    "value"       => array(
                        __( "No" , ST_TEXTDOMAIN )  => "no" ,
                        __( "Yes" , ST_TEXTDOMAIN ) => "yes"
                    ) ,
                )
            )
        )
    );

    vc_map(
        array(
            'name' => __("ST Activity Review Summary", ST_TEXTDOMAIN),
            'base' => 'st_activity_detail_review_summary',
            'content_element' => true,
            'icon' => 'icon-st',
            'category' => 'Activity',
            'show_settings_on_create' => false,
            'params'=>array(
                array(
                    'type' => 'textfield',
                    'heading' => esc_html__('There is no option in this element', ST_TEXTDOMAIN),
                    'param_name' => 'description_field',
                    'edit_field_class' => 'vc_column vc_col-sm-12 st_vc_hidden_input'
                )
            )
        )
    );

    vc_map(
        array(
            'name' => __("ST Detailed Activity Review", ST_TEXTDOMAIN),
            'base' => 'st_activity_detail_review_detail',
            'content_element' => true,
            'icon' => 'icon-st',
            'category' => 'Activity',
            'show_settings_on_create' => false,
            'params'=>array(
                array(
                    'type' => 'textfield',
                    'heading' => esc_html__('There is no option in this element', ST_TEXTDOMAIN),
                    'param_name' => 'description_field',
                    'edit_field_class' => 'vc_column vc_col-sm-12 st_vc_hidden_input'
                )
            )
        )
    );

    vc_map(
        array(
            'name' => __("ST Activity Review", ST_TEXTDOMAIN),
            'base' => 'st_activity_review',
            'content_element' => true,
            'icon' => 'icon-st',
            'category' => 'Activity',
            'show_settings_on_create' => true,
            'params'=>array(
                array(
                    "type"        => "textfield" ,
                    "holder"      => "div" ,
                    "heading"     => __( "Title" , ST_TEXTDOMAIN ) ,
                    "param_name"  => "title" ,
                    "description" => "" ,
                    "value"       => "",
                    'edit_field_class'=>'vc_col-sm-6',
                ) ,
                array(
                    "type"        => "dropdown" ,
                    "holder"      => "div" ,
                    "heading"     => __( "Font Size" , ST_TEXTDOMAIN ) ,
                    "param_name"  => "font_size" ,
                    "description" => "" ,
                    "value"       => array(
                        __('--Select--',ST_TEXTDOMAIN)=>'',
                        __( "H1" , ST_TEXTDOMAIN ) => '1' ,
                        __( "H2" , ST_TEXTDOMAIN ) => '2' ,
                        __( "H3" , ST_TEXTDOMAIN ) => '3' ,
                        __( "H4" , ST_TEXTDOMAIN ) => '4' ,
                        __( "H5" , ST_TEXTDOMAIN ) => '5' ,
                    ),
                    'edit_field_class'=>'vc_col-sm-6',
                ) ,
            )
        )
    );

    vc_map(
        array(
            'name' => __("ST Activity Video", ST_TEXTDOMAIN),
            'base' => 'st_activity_video',
            'content_element' => true,
            'icon' => 'icon-st',
            'category' => 'Activity',
            'show_settings_on_create' => false,
            'params'=>array(
                array(
                    'type' => 'textfield',
                    'heading' => esc_html__('There is no option in this element', ST_TEXTDOMAIN),
                    'param_name' => 'description_field',
                    'edit_field_class' => 'vc_column vc_col-sm-12 st_vc_hidden_input'
                )
            )
        )
    );

    vc_map(
        array(
            'name' => __("ST Activity Nearby", ST_TEXTDOMAIN),
            'base' => 'st_activity_nearby',
            'content_element' => true,
            'icon' => 'icon-st',
            'category' => 'Activity',
            'show_settings_on_create' => true,
            'params'=>array(
                array(
                    "type"        => "textfield" ,
                    "holder"      => "div" ,
                    "heading"     => __( "Title" , ST_TEXTDOMAIN ) ,
                    "param_name"  => "title" ,
                    "description" => "" ,
                    "value"       => "",
                    'edit_field_class'=>'vc_col-sm-6',
                ) ,
                array(
                    "type"        => "dropdown" ,
                    "holder"      => "div" ,
                    "heading"     => __( "Font Size" , ST_TEXTDOMAIN ) ,
                    "param_name"  => "font_size" ,
                    "description" => "" ,
                    "value"       => array(
                        __('--Select--',ST_TEXTDOMAIN)=>'',
                        __( "H1" , ST_TEXTDOMAIN ) => '1' ,
                        __( "H2" , ST_TEXTDOMAIN ) => '2' ,
                        __( "H3" , ST_TEXTDOMAIN ) => '3' ,
                        __( "H4" , ST_TEXTDOMAIN ) => '4' ,
                        __( "H5" , ST_TEXTDOMAIN ) => '5' ,
                    ),
                    'edit_field_class'=>'vc_col-sm-6',
                ) ,
            )
        )
    );

    vc_map(
        array(
            'name'                    => __( 'ST Activity Show Discount' , ST_TEXTDOMAIN ) ,
            'base'                    => 'st_activity_show_discount' ,
            'content_element'         => true ,
            'icon'                    => 'icon-st' ,
            'category'                => 'Activity' ,
            'show_settings_on_create' => false,
            'params'=>array(
                array(
                    'type' => 'textfield',
                    'heading' => esc_html__('There is no option in this element', ST_TEXTDOMAIN),
                    'param_name' => 'description_field',
                    'edit_field_class' => 'vc_column vc_col-sm-12 st_vc_hidden_input'
                )
            )
        )
    );


}

function st_map_car_shortcodes()
{
    if(!st_check_service_available( 'st_cars' )) return;
    vc_map(
        array(
            'name'                    => __( "ST Car Thumbnail" , ST_TEXTDOMAIN ) ,
            'base'                    => 'st_thumbnail_cars' ,
            'content_element'         => true ,
            'icon'                    => 'icon-st' ,
            'category'                => 'Car' ,
            'show_settings_on_create' => false,
            'params' => array(
                array(
                    'type' => 'textfield',
                    'heading' => esc_html__('There is no option in this element', ST_TEXTDOMAIN),
                    'param_name' => 'description_field',
                    'edit_field_class' => 'vc_column vc_col-sm-12 st_vc_hidden_input'
                )
            )
        )
    );

    vc_map(
        array(
            'name'                    => __( "ST Car Excerpt" , ST_TEXTDOMAIN ) ,
            'base'                    => 'st_excerpt_cars' ,
            'content_element'         => true ,
            'icon'                    => 'icon-st' ,
            'category'                => 'Car' ,
            'show_settings_on_create' => false,
            'params'=>array(
                array(
                    'type' => 'textfield',
                    'heading' => esc_html__('There is no option in this element', ST_TEXTDOMAIN),
                    'param_name' => 'description_field',
                    'edit_field_class' => 'vc_column vc_col-sm-12 st_vc_hidden_input'
                )
            )
        )
    );

    vc_map(
        array(
            'name'            => __( "ST Detailed Car Location" , ST_TEXTDOMAIN ) ,
            'base'            => 'st_detail_date_location_cars' ,
            'content_element' => true ,
            'icon'            => 'icon-st' ,
            'category'        => 'Car' ,
            'params'          => array(
                array(
                    'type'       => 'textfield' ,
                    'heading'    => __( 'Drop Off' , ST_TEXTDOMAIN ) ,
                    'param_name' => 'drop-off' ,
                    'value'      => ''
                ) ,
                array(
                    'type'       => 'textfield' ,
                    'heading'    => __( 'Pick Up' , ST_TEXTDOMAIN ) ,
                    'param_name' => 'pick-up' ,
                    'value'      => ''
                ) ,
                array(
                    'type'       => 'textfield' ,
                    'heading'    => __( 'Location ID Drop Off' , ST_TEXTDOMAIN ) ,
                    'param_name' => 'location_id_drop_off' ,
                    'value'      => ''
                ) ,
                array(
                    'type'       => 'textfield' ,
                    'heading'    => __( 'Location ID Pick Up' , ST_TEXTDOMAIN ) ,
                    'param_name' => 'location_id_pick_up' ,
                    'value'      => ''
                ) ,
            )
        )
    );

    vc_map(
        array(
            'name'                    => __( "ST Car Video" , ST_TEXTDOMAIN ) ,
            'base'                    => 'st_car_video' ,
            'content_element'         => true ,
            'icon'                    => 'icon-st' ,
            'category'                => 'Car' ,
            'show_settings_on_create' => false,
            'params'=>array(
                array(
                    'type' => 'textfield',
                    'heading' => esc_html__('There is no option in this element', ST_TEXTDOMAIN),
                    'param_name' => 'description_field',
                    'edit_field_class' => 'vc_column vc_col-sm-12 st_vc_hidden_input'
                )
            )
        )
    );

    vc_map(
        array(
            'name'                    => __( "ST Car - Write Review Form" , ST_TEXTDOMAIN ) ,
            'base'                    => 'st_car_review' ,
            'content_element'         => true ,
            'icon'                    => 'icon-st' ,
            'category'                => 'Car' ,
            'show_settings_on_create' => false,
            'params'=>array(
                array(
                    'type' => 'textfield',
                    'heading' => esc_html__('There is no option in this element', ST_TEXTDOMAIN),
                    'param_name' => 'description_field',
                    'edit_field_class' => 'vc_column vc_col-sm-12 st_vc_hidden_input'
                )
            )
        )
    );
    vc_map(
        array(
            'name'                    => __( "ST Detailed Car Map" , ST_TEXTDOMAIN ) ,
            'base'                    => 'st_cars_detail_map' ,
            'content_element'         => true ,
            'icon'                    => 'icon-st' ,
            'category'                => 'Car' ,
            'show_settings_on_create' => true ,
            'params'                  => array(
                array(
                    "type"        => "textfield" ,
                    "holder"      => "div" ,
                    "heading"     => __( "Range" , ST_TEXTDOMAIN ) ,
                    "param_name"  => "range" ,
                    "description" => "Km" ,
                    "value"       => "20" ,
                ) ,
                array(
                    "type"        => "textfield" ,
                    "holder"      => "div" ,
                    "heading"     => __( "Number" , ST_TEXTDOMAIN ) ,
                    "param_name"  => "number" ,
                    "description" => "" ,
                    "value"       => "12" ,
                ) ,
                array(
                    "type"        => "dropdown" ,
                    "holder"      => "div" ,
                    "heading"     => __( "Show Circle" , ST_TEXTDOMAIN ) ,
                    "param_name"  => "show_circle" ,
                    "description" => "" ,
                    "value"       => array(
                        __( "No" , ST_TEXTDOMAIN )  => "no" ,
                        __( "Yes" , ST_TEXTDOMAIN ) => "yes"
                    ) ,
                ) ,
            )
        )
    );

    vc_map(
        array(
            'name'                    => __( 'ST Detailed Car Review' , ST_TEXTDOMAIN ) ,
            'base'                    => 'st_car_detail_review_detail' ,
            'content_element'         => true ,
            'icon'                    => 'icon-st' ,
            'category'                => 'Car' ,
            'show_settings_on_create' => false,
            'params'=>array(
                array(
                    'type' => 'textfield',
                    'heading' => esc_html__('There is no option in this element', ST_TEXTDOMAIN),
                    'param_name' => 'description_field',
                    'edit_field_class' => 'vc_column vc_col-sm-12 st_vc_hidden_input'
                )
            )
        )
    );

    vc_map(
        array(
            'name'                    => __( "ST Car  detail review summary" , ST_TEXTDOMAIN ) ,
            'base'                    => 'st_car_detail_review_summary' ,
            'content_element'         => true ,
            'icon'                    => 'icon-st' ,
            'category'                => 'Car' ,
            'show_settings_on_create' => false,
            'params'=>array(
                array(
                    'type' => 'textfield',
                    'heading' => esc_html__('There is no option in this element', ST_TEXTDOMAIN),
                    'param_name' => 'description_field',
                    'edit_field_class' => 'vc_column vc_col-sm-12 st_vc_hidden_input'
                )
            )
        )
    );

    vc_map(
        array(
            'name'                    => __( 'ST Car Show Discount' , ST_TEXTDOMAIN ) ,
            'base'                    => 'st_car_show_discount' ,
            'content_element'         => true ,
            'icon'                    => 'icon-st' ,
            'category'                => 'Car' ,
            'show_settings_on_create' => false,
            'params'=>array(
                array(
                    'type' => 'textfield',
                    'heading' => esc_html__('There is no option in this element', ST_TEXTDOMAIN),
                    'param_name' => 'description_field',
                    'edit_field_class' => 'vc_column vc_col-sm-12 st_vc_hidden_input'
                )
            )
        )
    );

    vc_map(
        array(
            'name'                    => __( 'ST Car Show Distance' , ST_TEXTDOMAIN ) ,
            'base'                    => 'st_car_show_distance' ,
            'content_element'         => true ,
            'icon'                    => 'icon-st' ,
            'category'                => 'Car' ,
            'show_settings_on_create' => false,
            'params'=>array(
                array(
                    'type' => 'textfield',
                    'heading' => esc_html__('There is no option in this element', ST_TEXTDOMAIN),
                    'param_name' => 'description_field',
                    'edit_field_class' => 'vc_column vc_col-sm-12 st_vc_hidden_input'
                )
            )
        )
    );

}
function st_map_general_shortcodes()
{
    vc_map(
        array(
            'name'                    => __( "ST Post Data" , ST_TEXTDOMAIN ) ,
            'base'                    => 'st_post_data' ,
            'content_element'         => true ,
            'icon'                    => 'icon-st' ,
            'category'                => 'Shinetheme' ,
            'show_settings_on_create' => true ,
            "params"                  => array(
                array(
                    "type"        => "textfield" ,
                    "holder"      => "div" ,
                    "heading"     => __( "Title" , ST_TEXTDOMAIN ) ,
                    "param_name"  => "title" ,
                    "description" => "" ,
                    "value"       => "",
                    'edit_field_class'=>'vc_col-sm-6',
                ) ,
                array(
                    "type"        => "dropdown" ,
                    "holder"      => "div" ,
                    "heading"     => __( "Font Size" , ST_TEXTDOMAIN ) ,
                    "param_name"  => "font_size" ,
                    "description" => "" ,
                    "value"       => array(
                        __('--Select--',ST_TEXTDOMAIN)=>'',
                        __( "H1" , ST_TEXTDOMAIN ) => '1' ,
                        __( "H2" , ST_TEXTDOMAIN ) => '2' ,
                        __( "H3" , ST_TEXTDOMAIN ) => '3' ,
                        __( "H4" , ST_TEXTDOMAIN ) => '4' ,
                        __( "H5" , ST_TEXTDOMAIN ) => '5' ,
                    ),
                    'edit_field_class'=>'vc_col-sm-6',
                ) ,
                array(
                    "type"        => "dropdown" ,
                    "holder"      => "div" ,
                    "heading"     => __( "Data Type" , ST_TEXTDOMAIN ) ,
                    "param_name"  => "field" ,
                    "description" => "" ,
                    "value"       => array(
                        __('--Select--',ST_TEXTDOMAIN)=>'',
                        __( "Title" , ST_TEXTDOMAIN )   => 'title' ,
                        __( "Content" , ST_TEXTDOMAIN ) => 'content' ,
                        __( "Excerpt" , ST_TEXTDOMAIN ) => 'excerpt' ,
                        __( "Thumbnail" , ST_TEXTDOMAIN ) => 'thumbnail' ,
                    )
                ) ,

                array(
                    'type'        => 'dropdown',
                    'holder'      => "div" ,
                    'heading'     => __("Thumbnail size " , ST_TEXTDOMAIN),
                    'param_name'  => "thumb_size",
                    'description' => "",
                    "value"       => array(
                        __('--Select--',ST_TEXTDOMAIN)=>'',
                        __( "Thumbnail" , ST_TEXTDOMAIN ) => 'thumbnail' ,
                        __( "Medium" , ST_TEXTDOMAIN ) => 'medium' ,
                        __( "Large" , ST_TEXTDOMAIN ) => 'large' ,
                        __( "Full" , ST_TEXTDOMAIN ) => 'full' ,
                    ),
                    'dependency'    => array(
                        'element'   => "field",
                        'value'     => 'thumbnail'
                    )
                ),

            ) ,
        )
    );
    vc_map(
        array(
            'name'                    => __( "ST Partner Info" , ST_TEXTDOMAIN ) ,
            'base'                    => 'st_partner_info' ,
            'content_element'         => true ,
            'icon'                    => 'icon-st' ,
            'category'                => __('Shinetheme', ST_TEXTDOMAIN) ,
            'show_settings_on_create' => false,
            'params'=>array(
                array(
                    'type' => 'textfield',
                    'heading' => esc_html__('There is no option in this element', ST_TEXTDOMAIN),
                    'param_name' => 'description_field',
                    'edit_field_class' => 'vc_column vc_col-sm-12 st_vc_hidden_input'
                )
            )
        )
    );

    vc_map(
        array(
            'name'                    => __( "ST Partner Average Rating" , ST_TEXTDOMAIN ) ,
            'base'                    => 'st_partner_average_rating' ,
            'content_element'         => true ,
            'icon'                    => 'icon-st' ,
            'category'                => __('Shinetheme', ST_TEXTDOMAIN) ,
            'params'=>array(
                array(
                    "type"             => "textfield" ,
                    "holder"           => "div" ,
                    "heading"          => __( "Title" , ST_TEXTDOMAIN ) ,
                    "param_name"       => "title" ,
                    "description"      => "" ,
                    "value"            => "" ,
                    'edit_field_class' => 'vc_col-sm-6' ,
                ),
                array(
                    "type"             => "dropdown" ,
                    "holder"           => "div" ,
                    "heading"          => __( "Font Size" , ST_TEXTDOMAIN ) ,
                    "param_name"       => "font_size" ,
                    "description"      => "" ,
                    "value"            => array(
                        __('--Select--',ST_TEXTDOMAIN)=>'',
                        __( "H1" , ST_TEXTDOMAIN ) => '1' ,
                        __( "H2" , ST_TEXTDOMAIN ) => '2' ,
                        __( "H3" , ST_TEXTDOMAIN ) => '3' ,
                        __( "H4" , ST_TEXTDOMAIN ) => '4' ,
                        __( "H5" , ST_TEXTDOMAIN ) => '5' ,
                    ) ,
                    'edit_field_class' => 'vc_col-sm-6' ,
                ) ,
            )
        )
    );
    vc_map(
        array(
            'name'                    => __( "ST Partner Contact Form" , ST_TEXTDOMAIN ) ,
            'base'                    => 'st_partner_contact_form' ,
            'content_element'         => true ,
            'icon'                    => 'icon-st' ,
            'category'                => __('Shinetheme', ST_TEXTDOMAIN) ,
            'params'=>array(
                array(
                    "type"             => "textfield" ,
                    "holder"           => "div" ,
                    "heading"          => __( "Title" , ST_TEXTDOMAIN ) ,
                    "param_name"       => "title" ,
                    "description"      => "" ,
                    "value"            => "" ,
                    'edit_field_class' => 'vc_col-sm-6' ,
                ),
                array(
                    "type"             => "dropdown" ,
                    "holder"           => "div" ,
                    "heading"          => __( "Font Size" , ST_TEXTDOMAIN ) ,
                    "param_name"       => "font_size" ,
                    "description"      => "" ,
                    "value"            => array(
                        __('--Select--',ST_TEXTDOMAIN)=>'',
                        __( "H1" , ST_TEXTDOMAIN ) => '1' ,
                        __( "H2" , ST_TEXTDOMAIN ) => '2' ,
                        __( "H3" , ST_TEXTDOMAIN ) => '3' ,
                        __( "H4" , ST_TEXTDOMAIN ) => '4' ,
                        __( "H5" , ST_TEXTDOMAIN ) => '5' ,
                    ) ,
                    'edit_field_class' => 'vc_col-sm-6' ,
                ) ,
            )
        )
    );
    vc_map(
        array(
            'name'                    => __( "ST Partner List Services" , ST_TEXTDOMAIN ) ,
            'base'                    => 'st_partner_list_service' ,
            'content_element'         => true ,
            'icon'                    => 'icon-st' ,
            'category'                => __('Shinetheme', ST_TEXTDOMAIN) ,
            'params'=>array(
                array(
                    "type"             => "textfield" ,
                    "holder"           => "div" ,
                    "heading"          => __( "Title" , ST_TEXTDOMAIN ) ,
                    "param_name"       => "title" ,
                    "description"      => "" ,
                    "value"            => "" ,
                    'edit_field_class' => 'vc_col-sm-6' ,
                ),
                array(
                    "type"             => "dropdown" ,
                    "holder"           => "div" ,
                    "heading"          => __( "Font Size" , ST_TEXTDOMAIN ) ,
                    "param_name"       => "font_size" ,
                    "description"      => "" ,
                    "value"            => array(
                        __('--Select--',ST_TEXTDOMAIN)=>'',
                        __( "H1" , ST_TEXTDOMAIN ) => '1' ,
                        __( "H2" , ST_TEXTDOMAIN ) => '2' ,
                        __( "H3" , ST_TEXTDOMAIN ) => '3' ,
                        __( "H4" , ST_TEXTDOMAIN ) => '4' ,
                        __( "H5" , ST_TEXTDOMAIN ) => '5' ,
                    ) ,
                    'edit_field_class' => 'vc_col-sm-6' ,
                ) ,
                array(
                    "type"             => "textfield" ,
                    "holder"           => "div" ,
                    "heading"          => __( "Post per page of service" , ST_TEXTDOMAIN ) ,
                    "param_name"       => "post_per_page_service" ,
                    "description"      => "" ,
                    "value"            => "" ,
                    'edit_field_class' => 'vc_col-sm-6' ,
                ),
                array(
                    "type"             => "textfield" ,
                    "holder"           => "div" ,
                    "heading"          => __( "Post per page of review" , ST_TEXTDOMAIN ) ,
                    "param_name"       => "post_per_page_review" ,
                    "description"      => "" ,
                    "value"            => "" ,
                    'edit_field_class' => 'vc_col-sm-6' ,
                ),
            )
        )
    );

    $list_tabs = array(
        esc_html__('All') => 'all',
        esc_html__('Avatar') => 'avatar',
        esc_html__('Email') => 'email',
        esc_html__('Phone') => 'phone',
        esc_html__('Email PayPal') => 'email_paypal',
        esc_html__('Home Airport') => 'home_airport',
        esc_html__('Address') => 'address'
    );
    vc_map(
        array(
            'name'                    => __( "ST Partner Info (Single Post)" , ST_TEXTDOMAIN ) ,
            'base'                    => 'st_partner_info_in_post' ,
            'content_element'         => true ,
            'icon'                    => 'icon-st' ,
            'category'                => __('Shinetheme', ST_TEXTDOMAIN) ,
            'params'=>array(
                array(
                    "type"             => "textfield" ,
                    "holder"           => "div" ,
                    "heading"          => __( "Title" , ST_TEXTDOMAIN ) ,
                    "param_name"       => "title" ,
                    "description"      => "" ,
                    "value"            => "" ,
                    'edit_field_class' => 'vc_col-sm-6' ,
                ),
                array(
                    "type"             => "dropdown" ,
                    "holder"           => "div" ,
                    "heading"          => __( "Font Size" , ST_TEXTDOMAIN ) ,
                    "param_name"       => "font_size" ,
                    "description"      => "" ,
                    "value"            => array(
                        __('--Select--',ST_TEXTDOMAIN)=>'',
                        __( "H1" , ST_TEXTDOMAIN ) => '1' ,
                        __( "H2" , ST_TEXTDOMAIN ) => '2' ,
                        __( "H3" , ST_TEXTDOMAIN ) => '3' ,
                        __( "H4" , ST_TEXTDOMAIN ) => '4' ,
                        __( "H5" , ST_TEXTDOMAIN ) => '5' ,
                    ) ,
                    'edit_field_class' => 'vc_col-sm-6' ,
                ) ,
                array(
                    "type"             => "dropdown" ,
                    "holder"           => "div" ,
                    "heading"          => __( "Avatar type" , ST_TEXTDOMAIN ) ,
                    "param_name"       => "avatar_type" ,
                    "description"      => "" ,
                    "value"            => array(
                        __('--Select--',ST_TEXTDOMAIN)=>'',
                        __( "Square" , ST_TEXTDOMAIN ) => 'square' ,
                        __( "Circle" , ST_TEXTDOMAIN ) => 'circle' ,
                    ) ,
                    'edit_field_class' => 'vc_col-sm-6' ,
                ) ,
                array(
                    "type"             => "dropdown" ,
                    "holder"           => "div" ,
                    "heading"          => __( "Layout" , ST_TEXTDOMAIN ) ,
                    "param_name"       => "format_column" ,
                    "description"      => "" ,
                    "value"            => array(
                        __('--Select--',ST_TEXTDOMAIN)=>'',
                        __( "1 Column" , ST_TEXTDOMAIN ) => '1' ,
                        __( "2 Column" , ST_TEXTDOMAIN ) => '2' ,
                    ) ,
                    'edit_field_class' => 'vc_col-sm-6' ,
                ) ,
                array(
                    'type' => 'checkbox',
                    'admin_label' => true,
                    'heading' => __('Select Information Display', ST_TEXTDOMAIN),
                    'param_name' => 'display_info',
                    'description' => __('Please choose information to display in page', ST_TEXTDOMAIN),
                    'value' => $list_tabs,
                    'std' => 'all'
                )
            )
        )
    );
    vc_map(array(
        "name" => __("Custom menu", ST_TEXTDOMAIN),
        "base" => "st_custom_menu",
        "content_element" => true,
        "icon" => "icon-st",
        "category" => "Content",
        'show_settings_on_create' => true,
        "params" => array(
            array(
                "type" => "textfield",
                "heading" => __("Title", ST_TEXTDOMAIN),
                "param_name" => "title",
                "description" => "",
            ),
            array(
                "type" => "st_dropdown",
                "heading" => __("Style", ST_TEXTDOMAIN),
                "param_name" => "menu",
                'stype' => 'list_terms',
                'sparam' => 'nav_menu',
            ),
        )
    ));
    vc_map(array(
        'name' => __('ST Cancellation Data', ST_TEXTDOMAIN),
        'base' => 'st_cancellation',
        'content_element' => true,
        'icon' => 'icon-st',
        'category' => 'Rental',
        'show_settings_on_create' => true,
        'params' => array(
            array(
                "type" => "textfield",
                "holder" => "div",
                "heading" => __("Title", ST_TEXTDOMAIN),
                "param_name" => "title",
                "description" => "",
                "value" => "",
                'edit_field_class' => 'vc_col-sm-6',
            ),
            array(
                "type" => "dropdown",
                "holder" => "div",
                "heading" => __("Font Size", ST_TEXTDOMAIN),
                "param_name" => "font_size",
                "description" => "",
                "value" => array(
                    __('--Select--', ST_TEXTDOMAIN) => '',
                    __("H1", ST_TEXTDOMAIN) => '1',
                    __("H2", ST_TEXTDOMAIN) => '2',
                    __("H3", ST_TEXTDOMAIN) => '3',
                    __("H4", ST_TEXTDOMAIN) => '4',
                    __("H5", ST_TEXTDOMAIN) => '5',
                ),
                'edit_field_class' => 'vc_col-sm-6',
            ),
        )
    ));
}

function st_map_tour_shortcodes()
{

    if(!st_check_service_available( 'st_tours' )) return;

    vc_map(
        array(
            'name'                    => __( "ST Tour Thumbnail" , ST_TEXTDOMAIN ) ,
            'base'                    => 'st_thumbnail_tours' ,
            'content_element'         => true ,
            'icon'                    => 'icon-st' ,
            'category'                => 'Tour' ,
            'show_settings_on_create' => false,
            'params'=>array(
                array(
                    'type' => 'textfield',
                    'heading' => esc_html__('There is no option in this element', ST_TEXTDOMAIN),
                    'param_name' => 'description_field',
                    'edit_field_class' => 'vc_column vc_col-sm-12 st_vc_hidden_input'
                )
            )
        )
    );

    vc_map(
        array(
            'name'                    => __( "ST Tour Excerpt" , ST_TEXTDOMAIN ) ,
            'base'                    => 'st_excerpt_tour' ,
            'content_element'         => true ,
            'icon'                    => 'icon-st' ,
            'category'                => 'Tour' ,
            'show_settings_on_create' => true ,
            'params'                  => array(
                array(
                    "type"             => "textfield" ,
                    "holder"           => "div" ,
                    "heading"          => __( "Title" , ST_TEXTDOMAIN ) ,
                    "param_name"       => "title" ,
                    "description"      => "" ,
                    "value"            => "" ,
                    'edit_field_class' => 'vc_col-sm-6' ,
                ) ,
                array(
                    "type"             => "dropdown" ,
                    "holder"           => "div" ,
                    "heading"          => __( "Font Size" , ST_TEXTDOMAIN ) ,
                    "param_name"       => "font_size" ,
                    "description"      => "" ,
                    "value"            => array(
                        __('--Select--',ST_TEXTDOMAIN)=>'',
                        __( "H1" , ST_TEXTDOMAIN ) => '1' ,
                        __( "H2" , ST_TEXTDOMAIN ) => '2' ,
                        __( "H3" , ST_TEXTDOMAIN ) => '3' ,
                        __( "H4" , ST_TEXTDOMAIN ) => '4' ,
                        __( "H5" , ST_TEXTDOMAIN ) => '5' ,
                    ) ,
                    'edit_field_class' => 'vc_col-sm-6' ,
                ) ,
            )
        )
    );

    vc_map(
        array(
            'name'                    => __( "ST Tour Content" , ST_TEXTDOMAIN ) ,
            'base'                    => 'st_tour_content' ,
            'content_element'         => true ,
            'icon'                    => 'icon-st' ,
            'category'                => 'Tour' ,
            'show_settings_on_create' => true ,
            'params'                  => array(
                array(
                    "type"             => "textfield" ,
                    "holder"           => "div" ,
                    "heading"          => __( "Title" , ST_TEXTDOMAIN ) ,
                    "param_name"       => "title" ,
                    "description"      => "" ,
                    "value"            => "" ,
                    'edit_field_class' => 'vc_col-sm-6' ,
                ) ,
                array(
                    "type"             => "dropdown" ,
                    "holder"           => "div" ,
                    "heading"          => __( "Font Size" , ST_TEXTDOMAIN ) ,
                    "param_name"       => "font_size" ,
                    "description"      => "" ,
                    "value"            => array(
                        __('--Select--',ST_TEXTDOMAIN)=>'',
                        __( "H1" , ST_TEXTDOMAIN ) => '1' ,
                        __( "H2" , ST_TEXTDOMAIN ) => '2' ,
                        __( "H3" , ST_TEXTDOMAIN ) => '3' ,
                        __( "H4" , ST_TEXTDOMAIN ) => '4' ,
                        __( "H5" , ST_TEXTDOMAIN ) => '5' ,
                    ) ,
                    'edit_field_class' => 'vc_col-sm-6' ,
                ) ,
            )
        )
    );

    vc_map(
        array(
            'name'                    => __( "ST Tour Info" , ST_TEXTDOMAIN ) ,
            'base'                    => 'st_info_tours' ,
            'content_element'         => true ,
            'icon'                    => 'icon-st' ,
            'category'                => 'Tour' ,
            'show_settings_on_create' => false ,
            'params'                  => array(
                array(
                    "type"        => "dropdown" ,
                    "holder"      => "div" ,
                    "heading"     => __( "Style" , ST_TEXTDOMAIN ) ,
                    "param_name"  => "style" ,
                    "value"       => array(
                        __( "--Select--" , ST_TEXTDOMAIN )  => "" ,
                        __( "Style 1" , ST_TEXTDOMAIN ) => "1",
                        __( "Style 2" , ST_TEXTDOMAIN ) => "2"
                    ) ,
                ) ,
                array(
                    "type"        => "textfield" ,
                    "holder"      => "div" ,
                    "heading"     => __( "Title 1" , ST_TEXTDOMAIN ) ,
                    "param_name"  => "title1" ,
                    'dependency' => array(
                        'element' => 'style',
                        'value' => array( '2' )
                    ),
                ) ,
                array(
                    "type"        => "textfield" ,
                    "holder"      => "div" ,
                    "heading"     => __( "Title 2" , ST_TEXTDOMAIN ) ,
                    "param_name"  => "title2" ,
                    'dependency' => array(
                        'element' => 'style',
                        'value' => array( '2' )
                    ),
                ) ,
                array(
                    "type"             => "dropdown" ,
                    "holder"           => "div" ,
                    "heading"          => __( "Font Size" , ST_TEXTDOMAIN ) ,
                    "param_name"       => "font_size" ,
                    "description"      => "" ,
                    "value"            => array(
                        __('--Select--',ST_TEXTDOMAIN)=>'',
                        __( "H1" , ST_TEXTDOMAIN ) => '1' ,
                        __( "H2" , ST_TEXTDOMAIN ) => '2' ,
                        __( "H3" , ST_TEXTDOMAIN ) => '3' ,
                        __( "H4" , ST_TEXTDOMAIN ) => '4' ,
                        __( "H5" , ST_TEXTDOMAIN ) => '5' ,
                    ) ,
                    'dependency' => array(
                        'element' => 'style',
                        'value' => array( '2' )
                    ),
                ) ,
            )

        )
    );

    vc_map(
        array(
            'name'                    => __( "ST Detailed Tour Map" , ST_TEXTDOMAIN ) ,
            'base'                    => 'st_tour_detail_map' ,
            'content_element'         => true ,
            'icon'                    => 'icon-st' ,
            'category'                => 'Tour' ,
            'show_settings_on_create' => true ,
            'params'                  => array(
                array(
                    "type"        => "textfield" ,
                    "holder"      => "div" ,
                    "heading"     => __( "Range" , ST_TEXTDOMAIN ) ,
                    "param_name"  => "range" ,
                    "description" => "Km" ,
                    "value"       => "20" ,
                ) ,
                array(
                    "type"        => "textfield" ,
                    "holder"      => "div" ,
                    "heading"     => __( "Number" , ST_TEXTDOMAIN ) ,
                    "param_name"  => "number" ,
                    "description" => "" ,
                    "value"       => "12" ,
                ) ,
                array(
                    "type"        => "dropdown" ,
                    "holder"      => "div" ,
                    "heading"     => __( "Show Circle" , ST_TEXTDOMAIN ) ,
                    "param_name"  => "show_circle" ,
                    "description" => "" ,
                    "value"       => array(
                        __( "No" , ST_TEXTDOMAIN )  => "no" ,
                        __( "Yes" , ST_TEXTDOMAIN ) => "yes"
                    ) ,
                ),
            )
        )
    );

    vc_map(
        array(
            'name'                    => __( "ST Tour Review Summary" , ST_TEXTDOMAIN ) ,
            'base'                    => 'st_tour_detail_review_summary' ,
            'content_element'         => true ,
            'icon'                    => 'icon-st' ,
            'category'                => 'Tour' ,
            'show_settings_on_create' => false,
            'params'=>array(
                array(
                    'type' => 'textfield',
                    'heading' => esc_html__('There is no option in this element', ST_TEXTDOMAIN),
                    'param_name' => 'description_field',
                    'edit_field_class' => 'vc_column vc_col-sm-12 st_vc_hidden_input'
                )
            )
        )
    );

    vc_map(
        array(
            'name'                    => __( "ST Detailed Tour Review" , ST_TEXTDOMAIN ) ,
            'base'                    => 'st_tour_detail_review_detail' ,
            'content_element'         => true ,
            'icon'                    => 'icon-st' ,
            'category'                => 'Tour' ,
            'show_settings_on_create' => false,
            'params'=>array(
                array(
                    'type' => 'textfield',
                    'heading' => esc_html__('There is no option in this element', ST_TEXTDOMAIN),
                    'param_name' => 'description_field',
                    'edit_field_class' => 'vc_column vc_col-sm-12 st_vc_hidden_input'
                )
            )
        )
    );

    vc_map(
        array(
            'name'                    => __( "ST Tour Program" , ST_TEXTDOMAIN ) ,
            'base'                    => 'st_tour_program' ,
            'content_element'         => true ,
            'icon'                    => 'icon-st' ,
            'category'                => 'Tour' ,
            'show_settings_on_create' => true ,
            'params'                  => array(
                array(
                    "type"             => "textfield" ,
                    "holder"           => "div" ,
                    "heading"          => __( "Title" , ST_TEXTDOMAIN ) ,
                    "param_name"       => "title" ,
                    "description"      => "" ,
                    "value"            => "" ,
                    'edit_field_class' => 'vc_col-sm-6' ,
                ) ,
                array(
                    "type"             => "dropdown" ,
                    "holder"           => "div" ,
                    "heading"          => __( "Font Size" , ST_TEXTDOMAIN ) ,
                    "param_name"       => "font_size" ,
                    "description"      => "" ,
                    "value"            => array(
                        __('--Select--',ST_TEXTDOMAIN)=>'',
                        __( "H1" , ST_TEXTDOMAIN ) => '1' ,
                        __( "H2" , ST_TEXTDOMAIN ) => '2' ,
                        __( "H3" , ST_TEXTDOMAIN ) => '3' ,
                        __( "H4" , ST_TEXTDOMAIN ) => '4' ,
                        __( "H5" , ST_TEXTDOMAIN ) => '5' ,
                    ) ,
                    'edit_field_class' => 'vc_col-sm-6' ,
                ) ,
            )
        )
    );

    vc_map(
        array(
            'name'                    => __( "ST Tour Share" , ST_TEXTDOMAIN ) ,
            'base'                    => 'st_tour_share' ,
            'content_element'         => true ,
            'icon'                    => 'icon-st' ,
            'category'                => 'Tour' ,
            'show_settings_on_create' => false,
            'params'=>array(
                array(
                    'type' => 'dropdown',
                    'heading' => esc_html__('Style', ST_TEXTDOMAIN),
                    'param_name' => 'style',
                    'description' => esc_html__('Select a style', ST_TEXTDOMAIN),
                    'value' => array(
                        esc_html__('Style 1', ST_TEXTDOMAIN) => 'style-1',
                        esc_html__('Style 2', ST_TEXTDOMAIN) => 'style-2'
                    ),
                    'std' => 'style-1'
                ),
                array(
                    'type' => 'textfield',
                    'heading' => esc_html__('Extra Class', ST_TEXTDOMAIN),
                    'param_name' => 'extra_class'
                )
            )
        )
    );

    vc_map(
        array(
            'name'                    => __( "ST Tour Review" , ST_TEXTDOMAIN ) ,
            'base'                    => 'st_tour_review' ,
            'content_element'         => true ,
            'icon'                    => 'icon-st' ,
            'category'                => 'Tour' ,
            'show_settings_on_create' => true ,
            'params'                  => array(
                array(
                    "type"             => "textfield" ,
                    "holder"           => "div" ,
                    "heading"          => __( "Title" , ST_TEXTDOMAIN ) ,
                    "param_name"       => "title" ,
                    "description"      => "" ,
                    "value"            => "" ,
                    'edit_field_class' => 'vc_col-sm-6' ,
                ) ,
                array(
                    "type"             => "dropdown" ,
                    "holder"           => "div" ,
                    "heading"          => __( "Font Size" , ST_TEXTDOMAIN ) ,
                    "param_name"       => "font_size" ,
                    "description"      => "" ,
                    "value"            => array(
                        __('--Select--',ST_TEXTDOMAIN)=>'',
                        __( "H1" , ST_TEXTDOMAIN ) => '1' ,
                        __( "H2" , ST_TEXTDOMAIN ) => '2' ,
                        __( "H3" , ST_TEXTDOMAIN ) => '3' ,
                        __( "H4" , ST_TEXTDOMAIN ) => '4' ,
                        __( "H5" , ST_TEXTDOMAIN ) => '5' ,
                    ) ,
                    'edit_field_class' => 'vc_col-sm-6' ,
                ) ,
            )
        )
    );

    vc_map(
        array(
            'name'                    => __( "ST Tour Price" , ST_TEXTDOMAIN ) ,
            'base'                    => 'st_tour_price' ,
            'content_element'         => true ,
            'icon'                    => 'icon-st' ,
            'category'                => 'Tour' ,
            'show_settings_on_create' => false,
            'params'=>array(
                array(
                    'type' => 'textfield',
                    'heading' => esc_html__('There is no option in this element', ST_TEXTDOMAIN),
                    'param_name' => 'description_field',
                    'edit_field_class' => 'vc_column vc_col-sm-12 st_vc_hidden_input'
                )
            )
        )
    );

    vc_map(
        array(
            'name'                    => __( "ST Tour Video" , ST_TEXTDOMAIN ) ,
            'base'                    => 'st_tour_video' ,
            'content_element'         => true ,
            'icon'                    => 'icon-st' ,
            'category'                => 'Tour' ,
            'show_settings_on_create' => false,
            'params'=>array(
                array(
                    'type' => 'textfield',
                    'heading' => esc_html__('There is no option in this element', ST_TEXTDOMAIN),
                    'param_name' => 'description_field',
                    'edit_field_class' => 'vc_column vc_col-sm-12 st_vc_hidden_input'
                )
            )
        )
    );

    vc_map(
        array(
            'name'                    => __( "ST Tour Nearby" , ST_TEXTDOMAIN ) ,
            'base'                    => 'st_tour_nearby' ,
            'content_element'         => true ,
            'icon'                    => 'icon-st' ,
            'category'                => 'Tour' ,
            'show_settings_on_create' => true ,
            'params'                  => array(
                array(
                    'type' => 'dropdown',
                    'admin_label' => true,
                    'heading' => esc_html__('Style', ST_TEXTDOMAIN),
                    'param_name' => 'style',
                    'value' => array(
                        esc_html__('Style 1', ST_TEXTDOMAIN) => 'style-1',
                        esc_html__('Style 2', ST_TEXTDOMAIN) => 'style-2'
                    ),
                    'std' => 'style-1'
                ),
                array(
                    "type"             => "textfield" ,
                    'admin_label' => true,
                    "heading"          => __( "Title" , ST_TEXTDOMAIN ) ,
                    "param_name"       => "title" ,
                    "description"      => "" ,
                    "value"            => "" ,
                    'edit_field_class' => 'vc_col-sm-6' ,
                    'dependency' => array(
                        'element' => 'style',
                        'value' => array('style-1')
                    )
                ) ,
                array(
                    "type"             => "dropdown" ,
                    'admin_label' => true,
                    "heading"          => __( "Font Size" , ST_TEXTDOMAIN ) ,
                    "param_name"       => "font_size" ,
                    "description"      => "" ,
                    "value"            => array(
                        __('--Select--',ST_TEXTDOMAIN)=>'',
                        __( "H1" , ST_TEXTDOMAIN ) => '1' ,
                        __( "H2" , ST_TEXTDOMAIN ) => '2' ,
                        __( "H3" , ST_TEXTDOMAIN ) => '3' ,
                        __( "H4" , ST_TEXTDOMAIN ) => '4' ,
                        __( "H5" , ST_TEXTDOMAIN ) => '5' ,
                    ) ,
                    'edit_field_class' => 'vc_col-sm-6' ,
                    'dependency' => array(
                        'element' => 'style',
                        'value' => array('style-1')
                    )
                ) ,
            )
        )
    );

    vc_map(
        array(
            'name'                    => __( 'ST Tour Show Discount' , ST_TEXTDOMAIN ) ,
            'base'                    => 'st_tour_show_discount' ,
            'content_element'         => true ,
            'icon'                    => 'icon-st' ,
            'category'                => 'Tour' ,
            'show_settings_on_create' => false,
            'params'=>array(
                array(
                    'type' => 'textfield',
                    'heading' => esc_html__('There is no option in this element', ST_TEXTDOMAIN),
                    'param_name' => 'description_field',
                    'edit_field_class' => 'vc_column vc_col-sm-12 st_vc_hidden_input'
                )
            )
        )
    );

    vc_map( array(
        "name"            => esc_html__( "ST Tour Gallery Map" , ST_TEXTDOMAIN ) ,
        "base"            => "st_tour_gallery_map" ,
        "content_element" => true ,
        "icon"            => "icon-st" ,
        "category"        => 'Tour',
        'description' => esc_html__('Display gallery image and map', ST_TEXTDOMAIN),
        "params"          => array(
            array(
                'type' => 'dropdown',
                'heading' => esc_html__('Style', ST_TEXTDOMAIN),
                'admin_label' => true,
                'param_name' => 'style',
                'description' => esc_html__('Select a style', ST_TEXTDOMAIN),
                'value' => array(
                    esc_html__('Default', ST_TEXTDOMAIN) => 'style-1',
                    esc_html__('Half Map', ST_TEXTDOMAIN) => 'half_map'
                ),
                'std' => 'default'
            ),
            array(
                'type' => 'dropdown',
                'heading' => esc_html__('Map Style', ST_TEXTDOMAIN),
                'param_name' => 'map_style',
                'description' => esc_html__('Select a style for map', ST_TEXTDOMAIN),
                'value' => array(
                    esc_html__('Normal', ST_TEXTDOMAIN) => 'style_normal',
                    esc_html__('Midnight', ST_TEXTDOMAIN) => 'style_midnight',
                    esc_html__('Icy Blue', ST_TEXTDOMAIN) => 'style_icy_blue',
                    esc_html__('Family Fest', ST_TEXTDOMAIN) => 'style_family_fest',
                    esc_html__('Open Dark', ST_TEXTDOMAIN) => 'style_open_dark',
                    esc_html__('Riverside', ST_TEXTDOMAIN) => 'style_riverside',
                    esc_html__('Ozan', ST_TEXTDOMAIN) => 'style_ozan'
                ),
                'std' => 'style_icy_blue'
            )
        )
    ) );

    vc_map( array(
        "name"            => esc_html__( "ST Tour Title - Address" , ST_TEXTDOMAIN ) ,
        "base"            => "st_tour_title_address" ,
        "icon"            => "icon-st" ,
        "category"        => 'Tour',
        'content_element'         => true ,
        'description' => esc_html__('Display title and address', ST_TEXTDOMAIN),
        "params"          => array(
            array(
                "type"        => "textfield" ,
                'admin_label' => true,
                "heading"     => esc_html__( "Extra Class" , ST_TEXTDOMAIN ) ,
                "param_name"  => "extra_class" ,
                "description" => ""
            ) ,
        )
    ) );


    vc_map( array(
        "name"            => esc_html__( "ST Tour List Info" , ST_TEXTDOMAIN ) ,
        "base"            => "st_tour_list_info" ,
        "icon"            => "icon-st" ,
        "category"        => 'Tour',
        'content_element'         => true ,
        'description' => esc_html__('Display list tour info', ST_TEXTDOMAIN),
        "params"          => array(
            array(
                "type"        => "textfield" ,
                'admin_label' => true,
                "heading"     => esc_html__( "Extra Class" , ST_TEXTDOMAIN ) ,
                "param_name"  => "extra_class" ,
                "description" => ""
            ) ,
        )
    ) );

    vc_map( array(
        "name"            => esc_html__( "ST Tour Tabs Content" , ST_TEXTDOMAIN ) ,
        "base"            => "st_tour_tabs_content" ,
        "icon"            => "icon-st" ,
        "category"        => 'Tour',
        'content_element'         => true ,
        'description' => esc_html__('Display tabs content', ST_TEXTDOMAIN),
        "params"          => array(
            array(
                'type' => 'checkbox',
                'heading' => esc_html__('Display Tabs', ST_TEXTDOMAIN),
                'param_name' => 'display_tabs',
                'description' => esc_html__('Select tabs to show in single', ST_TEXTDOMAIN),
                'value' => array(
                    esc_html__('Overview', ST_TEXTDOMAIN) => 'overview',
                    esc_html__('Itinerary', ST_TEXTDOMAIN) => 'itinerary',
                    esc_html__('FAQ & Reviews', ST_TEXTDOMAIN) => 'review',
                    esc_html__('Gallery', ST_TEXTDOMAIN) => 'gallery',
                    esc_html__('Prices & Payment', ST_TEXTDOMAIN) => 'payment',
                    esc_html__('Request To Book', ST_TEXTDOMAIN) => 'request',
                ),
                'std' => 'overview,itinerary,review,gallery,payment,request'
            ),
            array(
                "type"        => "textfield" ,
                'admin_label' => true,
                "heading"     => esc_html__( "Extra Class" , ST_TEXTDOMAIN ) ,
                "param_name"  => "extra_class" ,
                "description" => ""
            ) ,
        )
    ) );
}

function st_map_rental_room_shortcodes()
{
    if(!st_check_service_available( 'st_rental' )) return;

    vc_map(
        array(
            'name'                    => __( "ST Detailed Rental Map" , ST_TEXTDOMAIN ) ,
            'base'                    => 'st_rental_map' ,
            'content_element'         => true ,
            'icon'                    => 'icon-st' ,
            'category'                => 'Rental' ,
            'show_settings_on_create' => true,
            'params'=>array(
                array(
                    "type"        => "textfield" ,
                    "holder"      => "div" ,
                    "heading"     => __( "Range" , ST_TEXTDOMAIN ) ,
                    "param_name"  => "range" ,
                    "description" => "Km" ,
                    "value"       => "20" ,
                ) ,
                array(
                    "type"        => "textfield" ,
                    "holder"      => "div" ,
                    "heading"     => __( "Number" , ST_TEXTDOMAIN ) ,
                    "param_name"  => "number" ,
                    "description" => "" ,
                    "value"       => "12" ,
                ) ,
                array(
                    "type"        => "dropdown" ,
                    "holder"      => "div" ,
                    "heading"     => __( "Show Circle" , ST_TEXTDOMAIN ) ,
                    "param_name"  => "show_circle" ,
                    "description" => "" ,
                    "value"       => array(
                        __( "No" , ST_TEXTDOMAIN )  => "no" ,
                        __( "Yes" , ST_TEXTDOMAIN ) => "yes"
                    ) ,
                )
            )
        )
    );
    vc_map(
        array(
            'name'                    => __( "ST Rental Review Summary" , ST_TEXTDOMAIN ) ,
            'base'                    => 'st_rental_review_summary' ,
            'content_element'         => true ,
            'icon'                    => 'icon-st' ,
            'category'                => 'Rental' ,
            'show_settings_on_create' => false,
            'params'=>array(
                array(
                    'type' => 'textfield',
                    'heading' => esc_html__('There is no option in this element', ST_TEXTDOMAIN),
                    'param_name' => 'description_field',
                    'edit_field_class' => 'vc_column vc_col-sm-12 st_vc_hidden_input'
                )
            )
        )
    );
    vc_map(
        array(
            'name'                    => __( "ST Detailed Rental Review" , ST_TEXTDOMAIN ) ,
            'base'                    => 'st_rental_review_detail' ,
            'content_element'         => true ,
            'icon'                    => 'icon-st' ,
            'category'                => 'Rental' ,
            'show_settings_on_create' => false,
            'params'=>array(
                array(
                    'type' => 'textfield',
                    'heading' => esc_html__('There is no option in this element', ST_TEXTDOMAIN),
                    'param_name' => 'description_field',
                    'edit_field_class' => 'vc_column vc_col-sm-12 st_vc_hidden_input'
                )
            )
        )
    );
    vc_map(
        array(
            'name'                    => __( "ST Rental Review" , ST_TEXTDOMAIN ) ,
            'base'                    => 'st_rental_review' ,
            'content_element'         => true ,
            'icon'                    => 'icon-st' ,
            'category'                => 'Rental' ,
            'show_settings_on_create' => true ,
            'params'                  => array(
                array(
                    "type"             => "textfield" ,
                    "holder"           => "div" ,
                    "heading"          => __( "Title" , ST_TEXTDOMAIN ) ,
                    "param_name"       => "title" ,
                    "description"      => "" ,
                    "value"            => "" ,
                    'edit_field_class' => 'vc_col-sm-6' ,
                ) ,
                array(
                    "type"             => "dropdown" ,
                    "holder"           => "div" ,
                    "heading"          => __( "Font Size" , ST_TEXTDOMAIN ) ,
                    "param_name"       => "font_size" ,
                    "description"      => "" ,
                    "value"            => array(
                        __('--Select--',ST_TEXTDOMAIN)=>'',
                        __( "H1" , ST_TEXTDOMAIN ) => '1' ,
                        __( "H2" , ST_TEXTDOMAIN ) => '2' ,
                        __( "H3" , ST_TEXTDOMAIN ) => '3' ,
                        __( "H4" , ST_TEXTDOMAIN ) => '4' ,
                        __( "H5" , ST_TEXTDOMAIN ) => '5' ,
                    ) ,
                    'edit_field_class' => 'vc_col-sm-6' ,
                ) ,
            )
        )
    );
    vc_map(
        array(
            'name'                    => __( "ST Rental Nearby" , ST_TEXTDOMAIN ) ,
            'base'                    => 'st_rental_nearby' ,
            'content_element'         => true ,
            'icon'                    => 'icon-st' ,
            'category'                => 'Rental' ,
            'show_settings_on_create' => true ,
            'params'                  => array(
                array(
                    "type"             => "textfield" ,
                    "holder"           => "div" ,
                    "heading"          => __( "Title" , ST_TEXTDOMAIN ) ,
                    "param_name"       => "title" ,
                    "description"      => "" ,
                    "value"            => "" ,
                    'edit_field_class' => 'vc_col-sm-6' ,
                ) ,
                array(
                    "type"             => "dropdown" ,
                    "holder"           => "div" ,
                    "heading"          => __( "Font Size" , ST_TEXTDOMAIN ) ,
                    "param_name"       => "font_size" ,
                    "description"      => "" ,
                    "value"            => array(
                        __('--Select--',ST_TEXTDOMAIN)=>'',
                        __( "H1" , ST_TEXTDOMAIN ) => '1' ,
                        __( "H2" , ST_TEXTDOMAIN ) => '2' ,
                        __( "H3" , ST_TEXTDOMAIN ) => '3' ,
                        __( "H4" , ST_TEXTDOMAIN ) => '4' ,
                        __( "H5" , ST_TEXTDOMAIN ) => '5' ,
                    ) ,
                    'edit_field_class' => 'vc_col-sm-6' ,
                ) ,
            )
        )
    );
    vc_map(
        array(
            'name'                    => __( "ST Add Rental Rental Review" , ST_TEXTDOMAIN ) ,
            'base'                    => 'st_rental_add_review' ,
            'content_element'         => true ,
            'icon'                    => 'icon-st' ,
            'category'                => 'Rental' ,
            'show_settings_on_create' => false,
            'params'=>array(
                array(
                    'type' => 'textfield',
                    'heading' => esc_html__('There is no option in this element', ST_TEXTDOMAIN),
                    'param_name' => 'description_field',
                    'edit_field_class' => 'vc_column vc_col-sm-12 st_vc_hidden_input'
                )
            )
        )
    );
    vc_map(
        array(
            'name'                    => __( "ST Rental Price" , ST_TEXTDOMAIN ) ,
            'base'                    => 'st_rental_price' ,
            'content_element'         => true ,
            'icon'                    => 'icon-st' ,
            'category'                => 'Rental' ,
            'show_settings_on_create' => false,
            'params'=>array(
                array(
                    'type' => 'textfield',
                    'heading' => esc_html__('There is no option in this element', ST_TEXTDOMAIN),
                    'param_name' => 'description_field',
                    'edit_field_class' => 'vc_column vc_col-sm-12 st_vc_hidden_input'
                )
            )
        )
    );
    vc_map(
        array(
            'name'                    => __( "ST Rental Video" , ST_TEXTDOMAIN ) ,
            'base'                    => 'st_rental_video' ,
            'content_element'         => true ,
            'icon'                    => 'icon-st' ,
            'category'                => 'Rental' ,
            'show_settings_on_create' => false,
            'params'=>array(
                array(
                    'type' => 'textfield',
                    'heading' => esc_html__('There is no option in this element', ST_TEXTDOMAIN),
                    'param_name' => 'description_field',
                    'edit_field_class' => 'vc_column vc_col-sm-12 st_vc_hidden_input'
                )
            )
        )
    );
    vc_map(
        array(
            'name'                    => __( "ST Rental Header" , ST_TEXTDOMAIN ) ,
            'base'                    => 'st_rental_header' ,
            'content_element'         => true ,
            'icon'                    => 'icon-st' ,
            'category'                => 'Rental' ,
            'show_settings_on_create' => false,
            'params'=>array(
                array(
                    "type" => "dropdown",
                    "holder" => "div",
                    "heading" => __("Show Location ?", ST_TEXTDOMAIN),
                    "param_name" => "is_location",
                    "description" =>"",
                    "value" => array(
                        __('--Select--',ST_TEXTDOMAIN)=>'',
                        __('Yes',ST_TEXTDOMAIN)=>'1',
                        __('No',ST_TEXTDOMAIN)=>'2',
                    ),
                ),
                array(
                    "type" => "dropdown",
                    "holder" => "div",
                    "heading" => __("Show contact", ST_TEXTDOMAIN),
                    "param_name" => "is_contact",
                    "description" =>"",
                    "value" => array(
                        __('--Select--',ST_TEXTDOMAIN)=>'',
                        __('Yes',ST_TEXTDOMAIN)=>'1',
                        __('No',ST_TEXTDOMAIN)=>'2',
                    ),
                ),
            )
        )
    );
    vc_map(
        array(
            'name'                    => __( "ST Rental Book Form" , ST_TEXTDOMAIN ) ,
            'base'                    => 'st_rental_book_form' ,
            'content_element'         => true ,
            'icon'                    => 'icon-st' ,
            'category'                => 'Rental' ,
            'show_settings_on_create' => false,
            'params'=>array(
                array(
                    'type' => 'textfield',
                    'heading' => esc_html__('There is no option in this element', ST_TEXTDOMAIN),
                    'param_name' => 'description_field',
                    'edit_field_class' => 'vc_column vc_col-sm-12 st_vc_hidden_input'
                )
            )
        )
    );
    vc_map(array(
        'name' => __('ST Rental Calendar',ST_TEXTDOMAIN),
        'base' => 'st_rental_calendar',
        'content_element' => true,
        'icon' => 'icon-st',
        'category' => 'Rental',
        'show_settings_on_create' => false,
        'params'=>array(
            array(
                'type' => 'textfield',
                'heading' => esc_html__('There is no option in this element', ST_TEXTDOMAIN),
                'param_name' => 'description_field',
                'edit_field_class' => 'vc_column vc_col-sm-12 st_vc_hidden_input'
            )
        )
    ));

}