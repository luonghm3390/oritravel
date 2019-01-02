<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * User create rental
 *
 * Created by ShineTheme
 *
 */
    wp_enqueue_script( 'bootstrap-datepicker.js' ); 
    wp_enqueue_script( 'bootstrap-datepicker-lang.js' ); 
    wp_enqueue_script( 'user_upload.js' );

$post_id = STInput::request('id');
$title = $content = $excerpt = "";
if(!empty($post_id)){
    $post = get_post( $post_id );
    $title = $post->post_title;
    $content = $post->post_content;
    $excerpt = $post->post_excerpt;
}
$validator= STUser_f::$validator;
if(empty($post_id)){

    //=== Validate package
    $admin_packages = STAdminPackages::get_inst();
    $author = get_current_user_id();
    $count_item_publish = $admin_packages->count_item_can_public($author);
    if($admin_packages->enabled_membership() && $admin_packages->get_user_role() == 'partner'){
        if( $count_item_publish !== 'unlimited' && $count_item_publish<= 0){
            $user_link = get_permalink( );
            echo '<div class="alert alert-warning mt20">'. __('You can not create a new item. Your items can be created is ', ST_TEXTDOMAIN). $admin_packages->count_item_package($author) .'. '.'<a href="'.TravelHelper::get_user_dashboared_link($user_link, 'setting').'" target="_blank">'.__('More Details', ST_TEXTDOMAIN).'</a>'.'</div>';
            return false;
        }
    }

}

?>
<div class="row">
    <div class="col-sm-3">
        <div class="add-service-progress">
            <div class="st-create">
                <h3 class="pull-left"><?php esc_html_e('Rental Information',ST_TEXTDOMAIN) ?></h3>
            </div>
            <div class="sticky-box" data-width-from=".add-service-progress">
                <ul>
                    <li data-step="#step_general" class="selected"><i class="fa fa-home"></i> <?php esc_html_e('General',ST_TEXTDOMAIN) ?></li>
                    <li data-step="#step_location_settings"><i class="fa fa-map-marker"></i> <?php esc_html_e('Location Settings',ST_TEXTDOMAIN) ?></li>
                    <li data-step="#step_detail"><i class="fa fa-building"></i> <?php esc_html_e('Rental Info',ST_TEXTDOMAIN) ?></li>
                    <li data-step="#step_agent_info"><i class="fa fa-user"></i> <?php esc_html_e('Agent Info',ST_TEXTDOMAIN) ?></li>
                    <li data-step="#step_price_setting"><i class="fa fa-money"></i> <?php esc_html_e('Price Settings',ST_TEXTDOMAIN) ?></li>
                    <li data-step="#step_rental_options"><i class="fa fa-building"></i> <?php esc_html_e('Rental Settings',ST_TEXTDOMAIN) ?></li>
                    <li data-step="#step_cancel_booking"><i class="fa fa-ban"></i> <?php esc_html_e('Cancel Booking',ST_TEXTDOMAIN) ?></li>

                    <?php $st_is_woocommerce_checkout=apply_filters('st_is_woocommerce_checkout',false);
                    if(!$st_is_woocommerce_checkout):?>
                        <li data-step="#step_payment_settings"><i class="fa fa-credit-card"></i> <?php esc_html_e('Payment Settings',ST_TEXTDOMAIN) ?></li>
                    <?php endif ?>

                    <?php $custom_field = st()->get_option( 'rental_unlimited_custom_field' );
                    if(!empty( $custom_field ) and is_array( $custom_field )) { ?>
                        <li data-step="#step_custom_fields"><i class="fa fa-bars"></i> <?php _e("Custom Fields",ST_TEXTDOMAIN) ?></li>
                    <?php } ?>

                    <?php if(!empty($post_id)){ ?>
                        <li data-step="#step_availablility"><i class="fa fa-calendar"></i> <?php _e("Availability",ST_TEXTDOMAIN) ?></li>
                        <li data-step="#step_ical"><i class="fa fa-calendar"></i> <?php _e("Ical Sync",ST_TEXTDOMAIN) ?></li>
                    <?php } ?>

                </ul>
            </div>
        </div>
    </div>
    <div class="col-sm-9">
        <div class="st-create">
            <h3 class="pull-left">
                <?php if(!empty($post_id)){?>
                    <?php _e("Edit Rental",ST_TEXTDOMAIN) ?>
                <?php }else{ ?>
                    <?php _e("Add Rental",ST_TEXTDOMAIN) ?>
                <?php } ?>
            </h3>
            <?php if(!empty($post_id)){  ?>
                <a target="_blank" href="<?php echo get_the_permalink($post_id) ?>" class="btn btn-default pull-right btn-icon"><i class="fa fa-save"></i> <?php _e("Preview",ST_TEXTDOMAIN) ?></a>
            <?php }else{ ?>
                <span class="btn btn-default pull-right btn_save_and_preview btn-icon"><i class="fa fa-save"></i> <?php _e("Save & Preview",ST_TEXTDOMAIN) ?></span>
            <?php } ?>
        </div>
        <div class="msg">
            <?php echo STTemplate::message() ?>
            <?php echo STUser_f::get_msg(); ?>
            <?php echo STUser_f::get_control_data(); ?>
        </div>
        <form action="" method="post" enctype="multipart/form-data" id="st_form_add_partner">
            <?php wp_nonce_field('user_setting','st_update_post_rental'); ?>
            <div class="add_service_step_wraps">
                <div class="add_service_step active" id="step_general">
                    <div class="form-group form-group-icon-left">

                        <label for="title" class="head_bol"><?php echo __('Title', ST_TEXTDOMAIN); ?> <span class="text-small text-danger">*</span>:</label>
                        <i class="fa  fa-file-text input-icon input-icon-hightlight"></i>
                        <input data-rules="required" id="title" name="st_title" type="text" placeholder="<?php echo __('Title', ST_TEXTDOMAIN); ?>" class="form-control" value="<?php echo stripslashes(STInput::request("st_title",$title)) ?>">
                        <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('st_title'),'danger') ?></div>
                        <?php st_validate_msg(esc_html__("The Title field is required.",ST_TEXTDOMAIN),'required') ?>
                    </div>
                    <div  class="form-group form-group-icon-left">
                        <label for="st_content" class="head_bol"><?php st_the_language('user_create_rental_content') ?> <span class="text-small text-danger">*</span>:</label>
                        <?php
                        ob_start();
                        wp_editor( stripslashes(STInput::request("st_content",$content)) ,'st_content');
                        $html=ob_get_clean();
                        echo str_replace('<textarea ','<textarea data-rules="required" ',$html);
                        ?>
                        <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('st_content'),'danger') ?></div>
                        <?php st_validate_msg(esc_html__("The Content field is required.",ST_TEXTDOMAIN),'required') ?>
                    </div>
                    <div class="form-group">
                        <label for="desc" class="head_bol"><?php _e("Description",ST_TEXTDOMAIN) ?> <span class="text-small text-danger">*</span>:</label>
                        <textarea data-rules="required" id="desc" rows="6" name="st_desc" class="form-control"><?php echo stripslashes(STInput::request("st_desc",$excerpt)) ?></textarea>
                        <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('st_desc'),'danger') ?></div>
                        <?php st_validate_msg(esc_html__("The Description field is required.",ST_TEXTDOMAIN),'required') ?>
                    </div>
                    <div class="form-group form-group-icon-left">
                        <label for="id_featured_image" class="head_bol"><?php _e("Featured Image",ST_TEXTDOMAIN) ?> <span class="text-small text-danger">*</span>:</label>
                        <div class="upload-wrapper upload-partner-wrapper">
                            <button class="upload-button-partner btn btn-primary btn-sm" data-uploader_title="<?php _e('Select a image to upload', ST_TEXTDOMAIN); ?>" data-uploader_button_text="<?php _e('Use this image', ST_TEXTDOMAIN); ?>"><?php echo __('Upload', ST_TEXTDOMAIN); ?></button>
                            <div class="upload-items">
                                <?php
                                $thumbnail = STInput::request('id_featured_image', get_post_thumbnail_id( $post_id ));
                                $thumbnail_url = wp_get_attachment_url( $thumbnail );
                                if( !empty( $thumbnail_url ) ):
                                    ?>
                                    <div class="upload-item">
                                        <img src="<?php echo $thumbnail_url; ?>" alt="<?php echo TravelHelper::get_alt_image(); ?>" class="frontend-image img-responsive">
                                        <a href="javascript: void(0);" class="delete">&times;</a>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <input type="hidden" data-rules="required" class="save-image-id" name="id_featured_image" value="<?php echo $thumbnail; ?>">
                        </div>
                        <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('featured_image'),'danger') ?></div>
                        <?php st_validate_msg(esc_html__("The Featured Image field is required.",ST_TEXTDOMAIN),'required') ?>
                    </div>
                </div>
                <div class="add_service_step" id="step_location_settings">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group form-group-icon-left">
                                <label for="multi_location"><?php st_the_language( 'user_create_car_location' ) ?>:</label>
                                <div id="setting_multi_location" class="location-front">
                                    <?php
                                    $html_location = TravelHelper::treeLocationHtml();
                                    $post_id = STInput::request('id','');

                                    $multi_location = get_post_meta( $post_id, 'multi_location', true );
                                    if( !empty( $multi_location ) && !is_array( $multi_location ) ){
                                        $multi_location = explode(',', $multi_location);
                                    }
                                    if( empty( $multi_location ) ){
                                        $multi_location = array('');
                                    }
                                    ?>
                                    <div class="form-group st-select-loction">
                                        <input placeholder="<?php echo __('Type to search', ST_TEXTDOMAIN); ?>" type="text" class="widefat form-control" name="search" value="">
                                        <div class="list-location-wrapper">
                                            <?php
                                            if(is_array($html_location) && count($html_location)):
                                                foreach($html_location as $key => $location):
                                                    ?>
                                                    <div data-name="<?php echo $location['parent_name']; ?>" class="item" style="margin-left: <?php echo $location['level'].'px;'; ?> margin-bottom: 5px;">
                                                        <label for="<?php echo 'location-'.$location['ID']; ?>">
                                                            <input <?php if(in_array('_'.$location['ID'].'_', $multi_location)) echo 'checked'; ?>  id="<?php echo 'location-'.$location['ID']; ?>" type="checkbox" name="multi_location[]" value="<?php echo '_'.$location['ID'].'_'; ?>">
                                                            <span><?php echo $location['post_title']; ?></span>
                                                        </label>
                                                    </div>
                                                <?php  endforeach; endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('multi_location'),'danger') ?></div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group form-group-icon-left">

                                <label for="address"><?php st_the_language( 'user_create_car_address' ) ?> <span class="text-small text-danger">*</span>:</label>
                                <i class="fa fa-home input-icon input-icon-hightlight"></i>
                                <input data-rules="required" id="address" name="address" type="text"
                                       placeholder="<?php st_the_language( 'user_create_car_address' ) ?>" class="form-control" value="<?php echo STInput::request("address",get_post_meta( $post_id , 'address' , true)); ?>" >

                                <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('address'),'danger') ?></div>
                                <?php st_validate_msg(esc_html__("The Address field is required.",ST_TEXTDOMAIN),'required') ?>
                            </div>
                        </div>
                        <div class="col-md-12 partner_map">
                            <?php
                            if(class_exists('BTCustomOT')){
                                BTCustomOT::load_fields();
                                ot_type_bt_gmap_html();
                            }
                            ?>
                        </div>
                        <div class="col-xs-12 col-sm-6 mt15">
                            <label for=""><?php echo __('Properties near by', ST_TEXTDOMAIN); ?></label>
                            <div class="list-properties">
                                <?php
                                $properties = get_post_meta($post_id, 'properties_near_by', true);
                                if( !empty( $properties)):
                                    foreach( $properties as $key => $val):
                                        ?>
                                        <div class="property-item tab-item">
                                            <a href="javascript: void(0);" class="delete-tab-item btn btn-danger">x</a>
                                            <div class="tab-title"><?php echo esc_html( $val['title']); ?></div>
                                            <div class="tab-content">
                                                <div class="row">
                                                    <div class="col-xs-12 mb10">
                                                        <label for=""><?php echo __('Title', ST_TEXTDOMAIN); ?></label>
                                                        <input type="text" name="property-item[title][]" value="<?php echo esc_html( $val['title']); ?>" class="tab-content-title form-control">
                                                    </div>
                                                    <div class="col-xs-12 mb10">
                                                        <label for=""><?php echo __('Featured Image', ST_TEXTDOMAIN); ?></label>
                                                        <div class="upload-wrapper upload-partner-wrapper-link">
                                                            <button class="upload-button-partner-link btn btn-primary btn-sm" data-uploader_title="<?php _e('Select a image to upload', ST_TEXTDOMAIN); ?>" data-uploader_button_text="<?php _e('Use this image', ST_TEXTDOMAIN); ?>"><?php echo __('Upload', ST_TEXTDOMAIN); ?></button>
                                                            <div class="upload-items">
                                                                <?php
                                                                $featured_image = $val['featured_image'];
                                                                if( !empty( $featured_image ) ):
                                                                    ?>
                                                                    <div class="upload-item">
                                                                        <img src="<?php echo $featured_image; ?>" alt="<?php echo TravelHelper::get_alt_image(); ?>" class="frontend-image img-responsive">
                                                                        <a href="javascript: void(0);" class="delete">&times;</a>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                            <input type="hidden" class="save-image-url" name="property-item[featured_image][]" value="<?php echo $featured_image; ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12 mb10">
                                                        <label for=""><?php echo __('Description', ST_TEXTDOMAIN); ?></label>
                                                        <textarea name="property-item[description][]" id="" cols="30" rows="10" class="form-control"><?php echo $val['description']; ?></textarea>
                                                    </div>
                                                    <div class="col-xs-12 mb10">
                                                        <label for=""><?php echo __('Icon Map',ST_TEXTDOMAIN); ?></label>
                                                        <div class="upload-wrapper upload-partner-wrapper-link">
                                                            <button class="upload-button-partner-link btn btn-primary btn-sm" data-uploader_title="<?php _e('Select a image to upload', ST_TEXTDOMAIN); ?>" data-uploader_button_text="<?php _e('Use this image', ST_TEXTDOMAIN); ?>"><?php echo __('Upload', ST_TEXTDOMAIN); ?></button>
                                                            <div class="upload-items">
                                                                <?php
                                                                $featured_image = $val['icon'];
                                                                if( !empty( $featured_image ) ):
                                                                    ?>
                                                                    <div class="upload-item">
                                                                        <img src="<?php echo $featured_image; ?>" alt="<?php echo TravelHelper::get_alt_image(); ?>" class="frontend-image img-responsive">
                                                                        <a href="javascript: void(0);" class="delete">&times;</a>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                            <input type="hidden" class="save-image-url" name="property-item[icon][]" value="<?php echo $featured_image; ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12 mb10">
                                                        <label for=""><?php echo __('Lat', ST_TEXTDOMAIN); ?></label>
                                                        <input type="text" name="property-item[map_lat][]" value="<?php echo esc_html($val['map_lat'] ); ?>" class="form-control">
                                                    </div>
                                                    <div class="col-xs-12 mb10">
                                                        <label for=""><?php echo __('Lng', ST_TEXTDOMAIN); ?></label>
                                                        <input type="text" name="property-item[map_lng][]" value="<?php echo esc_html($val['map_lng'] ); ?>" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; endif; ?>
                            </div>
                            <a href="javascript:void(0);" class="btn btn-primary add-list-item mt10" data-get-html="#list-item-properties">+</a>
                        </div>
                        <div class="col-md-6">
                            <br>
                            <div class='form-group form-group-icon-left'>
                                <label for="is_featured"><?php _e( "Enable Street Views" , ST_TEXTDOMAIN ) ?>:</label>
                                <i class="fa fa-cogs input-icon input-icon-hightlight"></i>
                                <?php $enable_street_views_google_map  = STInput::request('enable_street_views_google_map',get_post_meta($post_id,'enable_street_views_google_map',true)) ?>
                                <select class='form-control' name='enable_street_views_google_map' id="enable_street_views_google_map">
                                    <option value='on' <?php if($enable_street_views_google_map == 'on') echo 'selected'; ?> ><?php _e("On",ST_TEXTDOMAIN) ?></option>
                                    <option value='off' <?php if($enable_street_views_google_map == 'off') echo 'selected'; ?> ><?php _e("Off",ST_TEXTDOMAIN) ?></option>
                                </select>
                            </div>
                        </div>
                        <!-- rental distance -->
                        <div class="col-xs-12 col-sm-6 mt20 clearfix">
                            <label for=""><?php echo __('Distance', ST_TEXTDOMAIN); ?></label>
                            <div class="list-properties">
                                <?php
                                $distance_closest = get_post_meta($post_id, 'distance_closest', true);
                                if( !empty( $distance_closest)):
                                    foreach( $distance_closest as $key => $val):
                                        ?>
                                        <div class="property-item tab-item">
                                            <a href="javascript: void(0);" class="delete-tab-item btn btn-danger">x</a>
                                            <div class="tab-title"><?php echo esc_html( $val['title']); ?></div>
                                            <div class="tab-content">
                                                <div class="row">
                                                    <div class="col-xs-12 mb10">
                                                        <label for=""><?php echo __('Title', ST_TEXTDOMAIN); ?></label>
                                                        <input type="text" name="rdistance-item[title][]" value="<?php echo esc_html( $val['title']); ?>" class="tab-content-title form-control">
                                                    </div>
                                                    <div class="col-xs-12 mb10">
                                                        <label for=""><?php echo __('Featured Image', ST_TEXTDOMAIN); ?></label>
                                                        <div class="upload-wrapper upload-partner-wrapper-link">
                                                            <button class="upload-button-partner-link btn btn-primary btn-sm" data-uploader_title="<?php _e('Select a image to upload', ST_TEXTDOMAIN); ?>" data-uploader_button_text="<?php _e('Use this image', ST_TEXTDOMAIN); ?>"><?php echo __('Upload', ST_TEXTDOMAIN); ?></button>
                                                            <div class="upload-items">
                                                                <?php
                                                                $featured_image = $val['icon'];
                                                                if( !empty( $featured_image ) ):
                                                                    ?>
                                                                    <div class="upload-item">
                                                                        <img src="<?php echo $featured_image; ?>" alt="<?php echo TravelHelper::get_alt_image(); ?>" class="frontend-image img-responsive">
                                                                        <a href="javascript: void(0);" class="delete">&times;</a>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                            <input type="hidden" class="save-image-url" name="rdistance-item[icon][]" value="<?php echo $featured_image; ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12 mb10">
                                                        <label for=""><?php echo __('Name position', ST_TEXTDOMAIN); ?></label>
                                                        <input type="text" name="rdistance-item[name][]" value="<?php echo esc_html($val['name'] ); ?>" class="form-control">
                                                    </div>
                                                    <div class="col-xs-12 mb10">
                                                        <label for=""><?php echo __('Distance', ST_TEXTDOMAIN); ?></label>
                                                        <input type="text" name="rdistance-item[distance][]" value="<?php echo esc_html($val['distance'] ); ?>" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; endif; ?>
                            </div>
                            <a href="javascript:void(0);" class="btn btn-primary add-list-item mt10" data-get-html="#list-item-rdistance">+</a>
                        </div>
                        <!-- end rental distance -->
                    </div>
                </div>
                <div class="add_service_step" id="step_detail">
                    <div class="row">
                        <?php
                        $taxonomies = (get_object_taxonomies('st_rental'));
                        if (is_array($taxonomies) and !empty($taxonomies)){
                            foreach ($taxonomies as $key => $value) {
                                ?>
                                <div class="col-md-12">
                                    <?php
                                    $category = STUser_f::get_list_taxonomy($value);
                                    $taxonomy_tmp = get_taxonomy( $value );
                                    $taxonomy_label =  ($taxonomy_tmp->label );
                                    $taxonomy_name =  ($taxonomy_tmp->name );
                                    if(!empty($category)):
                                        ?>
                                        <div class="form-group form-group-icon-left">
                                            <label for="check_all"> <?php echo esc_html($taxonomy_label); ?>:</label>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="checkbox-inline checkbox-stroke">
                                                        <label for="check_all">
                                                            <i class="fa fa-cogs"></i>
                                                            <input name="check_all" class="i-check check_all" type="checkbox"  /><?php _e("All",ST_TEXTDOMAIN) ?>
                                                        </label>
                                                    </div>
                                                </div>
                                                <?php foreach($category as $k=>$v):
                                                    $icon = get_tax_meta($k,'st_icon');
                                                    $icon = TravelHelper::handle_icon($icon);
                                                    $check = '';
                                                    if(STUser_f::st_check_post_term_partner( $post_id  ,$value , $k) == true ){
                                                        $check = 'checked';
                                                    }
                                                    ?>
                                                    <div class="col-md-3">
                                                        <div class="checkbox-inline checkbox-stroke">
                                                            <label for="taxonomy">
                                                                <i class="<?php echo esc_html($icon) ?>"></i>
                                                                <input name="taxonomy[]" class="i-check item_tanoxomy" type="checkbox" <?php echo esc_html($check) ?> value="<?php echo esc_attr($k.','.$taxonomy_name) ?>" /><?php echo esc_html($v) ?>
                                                            </label>
                                                        </div>
                                                    </div>
                                                <?php endforeach ?>
                                            </div>
                                        </div>
                                    <?php endif ?>
                                </div>
                                <?php
                            }
                        } else { ?>
                            <input name="no_taxonomy" type="hidden" value="no_taxonomy">
                        <?php } ?>
                        <div class="col-md-12">
                            <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('taxonomy[]'),'danger') ?></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class='form-group form-group-icon-left'>

                                <label for="st_custom_layout"><?php _e( "Detail Rental Layout" , ST_TEXTDOMAIN ) ?>:</label>
                                <i class="fa fa-cogs input-icon input-icon-hightlight"></i>
                                <?php $layout = st_get_layout('st_rental');
                                if(!empty($layout) and is_array($layout)):
                                    ?>
                                    <select class='form-control' name='st_custom_layout' id="st_custom_layout">
                                        <?php
                                        $st_custom_layout = STInput::request('st_custom_layout',get_post_meta($post_id , 'custom_layout' , true));
                                        foreach($layout as $k=>$v):
                                            if($st_custom_layout == $v['value']) $check = "selected"; else $check = '';
                                            echo '<option '.$check.' value='.$v['value'].'>'.$v['label'].'</option>';
                                        endforeach;
                                        ?>
                                    </select>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class='form-group form-group-icon-left'>
                                <?php
                                $author = get_current_user_id();
                                $admin_packages = STAdminPackages::get_inst();
                                $item_featured = $admin_packages->count_item_can_featured($author);
                                if(st()->get_option( 'partner_set_feature' ) == "on") { ?>

                                    <label for="is_featured"><?php _e( "Set as Featured" , ST_TEXTDOMAIN ) ?>:</label>
                                    <i class="fa fa-cogs input-icon input-icon-hightlight"></i>
                                    <?php $is_featured = STInput::request('is_featured',get_post_meta($post_id,'is_featured',true)) ?>
                                    <select class='form-control' name='is_featured' id="is_featured">
                                        <option value='off' <?php if($is_featured == 'off') echo 'selected'; ?> ><?php _e("No",ST_TEXTDOMAIN) ?></option>
                                        <option value='on'  <?php if($is_featured == 'on') echo 'selected'; ?> ><?php _e("Yes",ST_TEXTDOMAIN) ?></option>
                                    </select>
                                <?php }; ?>
                            </div>
                        </div>
                        <div class="col-md-6 clear">
                            <div class="form-group form-group-icon-left">

                                <label for="rental_number"><?php _e("Numbers",ST_TEXTDOMAIN) ?> <span class="text-small text-danger">*</span>:</label>
                                <i class="fa  fa-cogs input-icon input-icon-hightlight"></i>
                                <input data-rules="required" id="rental_number" name="rental_number" type="text" placeholder="<?php _e("Number of rental available for booking",ST_TEXTDOMAIN) ?>" class="form-control number" value="<?php echo STInput::request('rental_number',get_post_meta($post_id,'rental_number',true)) ?>">
                                <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('rental_number'),'danger') ?></div>
                                <?php st_validate_msg(esc_html__("The Numbers field is required.",ST_TEXTDOMAIN),'required') ?>
                            </div>
                        </div>
                        <div class='col-md-6'>
                            <div class="form-group form-group-icon-left">

                                <label for="rental_max_adult"><?php echo __("Max of Adult",ST_TEXTDOMAIN);?> <span class="text-small text-danger">*</span>:</label>
                                <i class="fa fa-plus-square input-icon input-icon-hightlight"></i>
                                <input data-rules="required" name="rental_max_adult" type="text" placeholder="1" class="form-control number" value="<?php echo STInput::request('rental_max_adult',get_post_meta($post_id,'rental_max_adult',true)) ?>">
                                <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('rental_max_adult'),'danger') ?></div>
                                <?php st_validate_msg(esc_html__("The Max of Adult field is required.",ST_TEXTDOMAIN),'required') ?>
                            </div>
                        </div>
                        <div class='col-md-6'>
                            <div class="form-group form-group-icon-left">

                                <label for="rental_max_children"><?php echo __("Max of Children",ST_TEXTDOMAIN);?> <span class="text-small text-danger">*</span>:</label>
                                <i class="fa fa-plus-square input-icon input-icon-hightlight"></i>
                                <input data-rules="required" name="rental_max_children" type="text" placeholder="1" class="form-control number" value="<?php echo STInput::request('rental_max_children',get_post_meta($post_id,'rental_max_children',true)) ?>">
                                <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('rental_max_children'),'danger') ?></div>
                                <?php st_validate_msg(esc_html__("The Max of Children field is required.",ST_TEXTDOMAIN),'required') ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group form-group-icon-left">

                                <label for="video"><?php st_the_language('user_create_rental_video') ?>:</label>
                                <i class="fa  fa-youtube-play input-icon input-icon-hightlight"></i>
                                <input id="video" name="video" type="text"  placeholder="<?php _e("Enter Youtube or Vimeo video link (Eg: https://www.youtube.com/watch?v=JL-pGPVQ1a8)") ?>" class="form-control" value="<?php echo STInput::request('video',get_post_meta($post_id,'video',true)) ?>">
                                <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('video'),'danger') ?></div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group form-group-icon-left">
                                <label for="gallery"><?php _e( "Gallery" , ST_TEXTDOMAIN ) ?> <span class="text-small text-danger">*</span>:</label>
                                <div class="upload-wrapper upload-mul-partner-wrapper">
                                    <?php
                                    $gallery = STInput::request('id_gallery', get_post_meta( $post_id, 'gallery', true ));
                                    $gallery_arr = explode( ',', $gallery);
                                    $gallery_arr = array_filter($gallery_arr, function($value){ return $value != '';});
                                    ?>
                                    <div class="clearfix">
                                        <button class="mr5 upload-button-partner-multi btn btn-primary btn-sm" data-uploader_title="<?php _e('Select a image to upload', ST_TEXTDOMAIN); ?>" data-uploader_button_text="<?php _e('Use this image', ST_TEXTDOMAIN); ?>"><?php echo __('Upload', ST_TEXTDOMAIN); ?></button>
                                        <?php
                                        if( !empty( $gallery_arr)):
                                            ?>
                                            <button class=" btn btn-primary btn-sm delete-gallery"><?php echo __('Delete', ST_TEXTDOMAIN); ?></button>
                                        <?php endif; ?>
                                    </div>
                                    <div class="upload-items">
                                        <?php

                                        if( !empty( $gallery_arr ) ):
                                            foreach( $gallery_arr as $image):
                                                $gallery_url = wp_get_attachment_url( $image );
                                                ?>
                                                <div class="upload-item">
                                                    <img src="<?php echo $gallery_url; ?>" alt="<?php echo TravelHelper::get_alt_image(); ?>" class="frontend-image img-responsive">
                                                </div>
                                            <?php endforeach; endif; ?>
                                    </div>
                                    <input type="hidden" data-rules="required" class="save-image-id" name="id_gallery" value="<?php echo $gallery; ?>">
                                </div>
                                <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('gallery'),'danger') ?></div>
                                <?php st_validate_msg(esc_html__("The Gallery field is required.",ST_TEXTDOMAIN),'required') ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="add_service_step" id="step_agent_info">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group form-group-icon-left">
                                <label for="show_agent_contact_info"><?php _e('Choose which contact info will be shown?',ST_TEXTDOMAIN) ?>:</label>
                                <?php $select=array(
                                    ''=>__('----Select----',ST_TEXTDOMAIN),
                                    'user_agent_info'=>__('Use Agent Contact Info',ST_TEXTDOMAIN),
                                    'user_item_info'=>__('Use Item Info',ST_TEXTDOMAIN),
                                ) ?>
                                <i class="fa  fa-envelope-o input-icon input-icon-hightlight"></i>
                                <select name="show_agent_contact_info" id="show_agent_contact_info" class="form-control app">
                                    <?php
                                    if(!empty($select)){
                                        foreach($select as $s=>$v){
                                            printf('<option value="%s" %s >%s</option>',$s,selected(STInput::request('show_agent_contact_info',get_post_meta($post_id,'show_agent_contact_info',true)),$s,FALSE),$v);
                                        }
                                    }
                                    ?>
                                </select>
                                <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('show_agent_contact_info'),'danger') ?></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group form-group-icon-left">

                                <label for="email"><?php st_the_language('user_create_rental_email') ?>:</label>
                                <i class="fa  fa-envelope-o input-icon input-icon-hightlight"></i>
                                <input id="email" name="email" type="email" placeholder="<?php st_the_language('user_create_rental_email') ?>" class="form-control" value="<?php echo STInput::request('agent_email',get_post_meta($post_id,'agent_email',true)) ?>">
                                <i class="placeholder"><?php _e("E-mail of Rental Agent which will receive email when new booking is recorded",ST_TEXTDOMAIN) ?></i>
                                <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('email'),'danger') ?></div>
                            </div>
                        </div>
                        <div class="col-md-6 clear">
                            <div class="form-group form-group-icon-left">

                                <label for="website"><?php st_the_language('user_create_rental_website') ?>:</label>
                                <i class="fa fa-link input-icon input-icon-hightlight"></i>
                                <input id="website" name="website" type="text" placeholder="<?php st_the_language('user_create_rental_website') ?>" class="form-control" value="<?php echo STInput::request('agent_website',get_post_meta($post_id,'agent_website',true)) ?>">
                                <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('website'),'danger') ?></div>
                            </div>
                        </div>
                        <div class="col-md-6 ">
                            <div class="form-group form-group-icon-left">

                                <label for="phone"><?php st_the_language('user_create_rental_phone') ?> <span class="text-small text-danger">*</span>:</label>
                                <i class="fa  fa-phone input-icon input-icon-hightlight"></i>
                                <input data-rules="required" id="phone" name="phone" type="text" placeholder="<?php st_the_language('user_create_rental_phone') ?>" class="form-control" value="<?php echo STInput::request('phone',get_post_meta($post_id,'agent_phone',true)) ?>">
                                <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('phone'),'danger') ?></div>
                                <?php st_validate_msg(esc_html__("The Rental Phone field is required.",ST_TEXTDOMAIN),'required') ?>

                            </div>
                        </div>
                        <div class="col-md-6 clear">
                            <div class="form-group form-group-icon-left">

                                <label for="st_fax"><?php _e('Fax Number',ST_TEXTDOMAIN) ?>:</label>
                                <i class="fa  fa-phone input-icon input-icon-hightlight"></i>
                                <input id="st_fax" name="st_fax" type="text" placeholder="<?php _e('Fax Number',ST_TEXTDOMAIN) ?>" class="form-control" value="<?php echo STInput::request('st_fax',get_post_meta($post_id,'st_fax',true)) ?>">
                                <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('st_fax'),'danger') ?></div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="add_service_step" id="step_price_setting">

                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group form-group-icon-left">

                                <label for="price"><?php st_the_language('user_create_rental_price') ?> <span class="text-small text-danger">*</span>:</label>
                                <i class="fa fa-money input-icon input-icon-hightlight"></i>
                                <input data-rules="required" id="price" name="price" type="text" placeholder="<?php st_the_language('user_create_rental_price') ?>" class="form-control number" value="<?php echo STInput::request('price',get_post_meta($post_id,'price',true)) ?>">
                                <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('price'),'danger') ?></div>
                                <?php st_validate_msg(esc_html__("The Price field is required.",ST_TEXTDOMAIN),'required') ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group form-group-icon-left">

                                <label for="discount"><?php _e("Discount Rate",ST_TEXTDOMAIN) ?>:</label>
                                <i class="fa fa-star input-icon input-icon-hightlight"></i>
                                <input id="discount" name="discount" type="text" placeholder="<?php _e("Discount Rate (%)",ST_TEXTDOMAIN) ?>" class="form-control number" value="<?php echo STInput::request('discount', get_post_meta($post_id,'discount_rate',true)) ?>">
                                <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('discount'),'danger') ?></div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group form-group-icon-left">

                                <label for="is_sale_schedule"><?php _e("Sale Schedule",ST_TEXTDOMAIN) ?>:</label>
                                <i class="fa fa-cogs input-icon input-icon-hightlight"></i>
                                <?php $is_sale_schedule = STInput::request('is_sale_schedule',get_post_meta($post_id,'is_sale_schedule',true)) ?>
                                <select class="form-control is_sale_schedule" name="is_sale_schedule" id="is_sale_schedule">
                                    <option value="on" <?php if($is_sale_schedule == 'on') echo 'selected'; ?>><?php _e("Yes",ST_TEXTDOMAIN) ?></option>
                                    <option value="off" <?php if($is_sale_schedule == 'off') echo 'selected'; ?>><?php _e("No",ST_TEXTDOMAIN) ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="data_is_sale_schedule">
                            <div class="col-md-6 input-daterange">
                                <div class="form-group form-group-icon-left" >

                                    <label for="sale_price_from"><?php _e("Sale Start Date",ST_TEXTDOMAIN) ?> <span class="text-small text-danger">*</span>:</label>
                                    <i class="fa fa-calendar input-icon input-icon-hightlight"></i>
                                    <input name="sale_price_from" class="date-pick form-control st_date_start" placeholder="<?php _e( "Sale start date", ST_TEXTDOMAIN ) ?>" data-date-format="<?php echo TravelHelper::getDateFormatJs(); ?>" type="text" value="<?php echo STInput::request('sale_price_from',get_post_meta($post_id,'sale_price_from',true)) ?>" readonly/>
                                    <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('sale_price_from'),'danger') ?></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group form-group-icon-left" >

                                    <label for="sale_price_to"><?php _e("Sale End Date",ST_TEXTDOMAIN) ?> <span class="text-small text-danger">*</span>:</label>
                                    <i class="fa fa-calendar input-icon input-icon-hightlight"></i>
                                    <input name="sale_price_to" class="date-pick form-control st_date_end" placeholder="<?php _e( "Sale end date", ST_TEXTDOMAIN ) ?>" data-date-format="<?php echo TravelHelper::getDateFormatJs(); ?>" type="text" value="<?php echo STInput::request('sale_price_to',get_post_meta($post_id,'sale_price_to',true)) ?>" readonly/>
                                    <div class="st_msg console_sale_price_to"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group form-group-icon-left">

                                <label for="deposit_payment_status"><?php _e("Deposit Payment Options",ST_TEXTDOMAIN) ?>:</label>
                                <i class="fa fa-cogs input-icon input-icon-hightlight"></i>
                                <?php $deposit_payment_status = STInput::request('deposit_payment_status',get_post_meta($post_id ,'deposit_payment_status',true) )?>
                                <select class="form-control deposit_payment_status" name="deposit_payment_status" id="deposit_payment_status">
                                    <option value=""><?php _e("Disallow Deposit",ST_TEXTDOMAIN) ?></option>
                                    <option value="percent" <?php if($deposit_payment_status == 'percent') echo 'selected' ?>><?php _e("Deposit By Percent",ST_TEXTDOMAIN) ?></option>
                                    <!-- <option value="amount" <?php /*if($deposit_payment_status == 'amount') echo 'selected' */?>><?php /*_e("Deposit By Amount",ST_TEXTDOMAIN) */?></option>-->
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 data_deposit_payment_status">
                            <div class="form-group form-group-icon-left">

                                <label for="deposit_payment_amount"><?php _e("Deposit Payment Amount",ST_TEXTDOMAIN) ?>:</label>
                                <i class="fa fa-cogs  input-icon input-icon-hightlight"></i>
                                <input id="deposit_payment_amount" name="deposit_payment_amount" type="text" placeholder="<?php _e("Deposit payment amount",ST_TEXTDOMAIN) ?>" class="form-control number" value="<?php echo STInput::request('deposit_payment_amount',get_post_meta($post_id,'deposit_payment_amount',true)) ?>">
                                <?php $partner_commission = st()->get_option('partner_commission','0'); ?>
                                <i><?php echo sprintf(esc_html__("The deposit amount must be greater than %s the commission",ST_TEXTDOMAIN),$partner_commission."%") ?></i>
                                <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('deposit_payment_amount'),'danger') ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group form-group-icon-left">
                                <label for="extra"><?php _e("Extra",ST_TEXTDOMAIN) ?>:</label>
                            </div>
                        </div>
                        <div class="content_extra_price col-xs-12">
                            <div class="list-properties">
                                <?php if(!empty($post_id)){ ?>
                                    <?php
                                    $extra = get_post_meta($post_id, 'extra_price', true);
                                    if(!empty($extra) and is_array($extra)):
                                        foreach($extra as $key => $val):
                                            ?>
                                            <div class="property-item tab-item">
                                                <a href="javascript: void(0);" class="delete-tab-item btn btn-danger">x</a>
                                                <div class="tab-title"><?php echo esc_html($val['title']); ?></div>
                                                <div class="tab-content">
                                                    <div class="row">
                                                        <div class="col-xs-12 mb10">
                                                            <div class="form-group">
                                                                <label for=""><?php _e( "Title" , ST_TEXTDOMAIN ) ?></label>
                                                                <input id="" name="extra[title][]" type="text" class="tab-content-title form-control" value="<?php echo esc_html($val['title']); ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-12 mb10">
                                                            <div class="form-group form-group-icon-left">
                                                                <label for="extra_name"><?php _e("Name",ST_TEXTDOMAIN) ?></label>
                                                                <i class="fa fa-file-text input-icon input-icon-hightlight"></i>
                                                                <input value="<?php echo esc_html($val['extra_name']); ?>" id="extra_name"  name="extra[extra_name][]" type="text" placeholder="<?php _e("Name",ST_TEXTDOMAIN) ?>" class="form-control" >
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-12 mb10">
                                                            <div class="form-group form-group-icon-left">
                                                                <label for="extra_max_number"><?php _e("Max Of Number",ST_TEXTDOMAIN) ?></label>
                                                                <i class="fa fa-file-text input-icon input-icon-hightlight"></i>
                                                                <input value="<?php echo esc_html($val['extra_max_number']); ?>" id="extra_max_number" name="extra[extra_max_number][]" type="text" placeholder="<?php _e("Max of number",ST_TEXTDOMAIN) ?>" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-12 mb10">
                                                            <div class="form-group form-group-icon-left">
                                                                <label for="extra_price"><?php _e("Price",ST_TEXTDOMAIN) ?></label>
                                                                <i class="fa fa-file-text input-icon input-icon-hightlight"></i>
                                                                <input value="<?php echo esc_html($val['extra_price']); ?>" id="extra_price" name="extra[extra_price][]" type="text" placeholder="<?php _e("Price",ST_TEXTDOMAIN) ?>" class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; endif; ?>
                                <?php }else{ ?>
                                    <?php
                                    $extra = isset($_POST['extra']) ? $_POST['extra'] : '';
                                    if(isset($extra['title']) && count($extra['title'])):
                                        foreach($extra['title'] as $key => $val):
                                            ?>
                                            <div class="property-item tab-item">
                                                <a href="javascript: void(0);" class="delete-tab-item btn btn-danger">x</a>
                                                <div class="tab-title"><?php echo esc_html($val); ?></div>
                                                <div class="tab-content">
                                                    <div class="row">
                                                        <div class="col-xs-12 mb10">
                                                            <div class="form-group">
                                                                <label for=""><?php _e( "Title" , ST_TEXTDOMAIN ) ?></label>
                                                                <input id="" name="extra[title][]" type="text" class="tab-content-title form-control" value="<?php echo esc_html($val); ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-12 mb10">
                                                            <div class="form-group form-group-icon-left">
                                                                <label for="extra_name"><?php _e("Name",ST_TEXTDOMAIN) ?></label>
                                                                <i class="fa fa-file-text input-icon input-icon-hightlight"></i>
                                                                <input value="<?php echo esc_html($extra['extra_name'][$key]); ?>" id="extra_name"  name="extra[extra_name][]" type="text" placeholder="<?php _e("Name",ST_TEXTDOMAIN) ?>" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-12 mb10">
                                                            <div class="form-group form-group-icon-left">
                                                                <label for="extra_max_number"><?php _e("Max Of Number",ST_TEXTDOMAIN) ?></label>
                                                                <i class="fa fa-file-text input-icon input-icon-hightlight"></i>
                                                                <input value="<?php echo esc_html($extra['extra_max_number'][$key]); ?>" id="extra_max_number"  name="extra[extra_max_number][]" type="text" placeholder="<?php _e("Max of number",ST_TEXTDOMAIN) ?>" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-12 mb10">
                                                            <div class="form-group form-group-icon-left">

                                                                <label for="extra_price"><?php _e("Price",ST_TEXTDOMAIN) ?></label>
                                                                <i class="fa fa-file-text input-icon input-icon-hightlight"></i>
                                                                <input value="<?php echo esc_html($extra['extra_price'][$key]); ?>" id="extra_price" name="extra[extra_price][]" type="text" placeholder="<?php _e("Price",ST_TEXTDOMAIN) ?>" class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; endif; ?>
                                <?php } ?>
                            </div>
                            <a href="javascript:void(0);" class="btn btn-primary add-list-item mt10" data-get-html="#list-item-extraprice">+</a>
                        </div>
                    </div>

                    <!--Discount by number day-->
                    <div class="row mt20">
                        <div class="col-md-12">
                            <div class="form-group form-group-icon-left">
                                <label for="discount_by_number_of_day"><?php _e("Discount by number of days",ST_TEXTDOMAIN) ?>:</label>
                            </div>
                        </div>
                        <div class="content_discount_by_number_of_day col-xs-12">
                            <div class="list-properties">
                                <?php if(!empty($post_id)){ ?>
                                    <?php
                                    $discount_by_day = get_post_meta($post_id, 'discount_by_day', true);
                                    if(!empty($discount_by_day) and is_array($discount_by_day)):
                                        foreach($discount_by_day as $key => $val):
                                            ?>
                                            <div class="property-item tab-item">
                                                <a href="javascript: void(0);" class="delete-tab-item btn btn-danger">x</a>
                                                <div class="tab-title"><?php echo esc_html($val['title']); ?></div>
                                                <div class="tab-content">
                                                    <div class="row">
                                                        <div class="col-xs-12 mb10">
                                                            <div class="form-group">
                                                                <label for=""><?php _e( "Title" , ST_TEXTDOMAIN ) ?></label>
                                                                <input id="" name="discount_by_day[title][]" type="text" class="tab-content-title form-control" value="<?php echo esc_html($val['title']); ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-12 mb10">
                                                            <div class="form-group form-group-icon-left">
                                                                <label for="discount_by_day_number_day"><?php _e("No. days",ST_TEXTDOMAIN) ?></label>
                                                                <i class="fa fa-file-text input-icon input-icon-hightlight"></i>
                                                                <input value="<?php echo esc_html($val['number_day']); ?>" id="discount_by_day_number_day"  name="discount_by_day[number_day][]" type="text" placeholder="<?php _e("Name",ST_TEXTDOMAIN) ?>" class="form-control" >
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-12 mb10">
                                                            <div class="form-group form-group-icon-left">
                                                                <label for="discount_by_day_discount"><?php _e("Discount",ST_TEXTDOMAIN) ?></label>
                                                                <i class="fa fa-file-text input-icon input-icon-hightlight"></i>
                                                                <input value="<?php echo esc_html($val['discount']); ?>" id="discount_by_day_discount" name="discount_by_day[discount][]" type="text" placeholder="<?php _e("Max of number",ST_TEXTDOMAIN) ?>" class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; endif; ?>
                                <?php }else{ ?>
                                    <?php
                                    $discount_by_day = isset($_POST['discount_by_day']) ? $_POST['discount_by_day'] : '';
                                    if(isset($discount_by_day['title']) && count($discount_by_day['title'])):
                                        foreach($discount_by_day['title'] as $key => $val):
                                            ?>
                                            <div class="property-item tab-item">
                                                <a href="javascript: void(0);" class="delete-tab-item btn btn-danger">x</a>
                                                <div class="tab-title"><?php echo esc_html($val); ?></div>
                                                <div class="tab-content">
                                                    <div class="row">
                                                        <div class="col-xs-12 mb10">
                                                            <div class="form-group">
                                                                <label for=""><?php _e( "Title" , ST_TEXTDOMAIN ) ?></label>
                                                                <input id="" name="discount_by_day[title][]" type="text" class="tab-content-title form-control" value="<?php echo esc_html($val); ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-12 mb10">
                                                            <div class="form-group form-group-icon-left">
                                                                <label for="discount_by_day_number_day"><?php _e("No. days",ST_TEXTDOMAIN) ?></label>
                                                                <i class="fa fa-file-text input-icon input-icon-hightlight"></i>
                                                                <input value="<?php echo esc_html($discount_by_day['number_day'][$key]); ?>" id="discount_by_day_number_day"  name="discount_by_day[number_day][]" type="text" placeholder="<?php _e("Name",ST_TEXTDOMAIN) ?>" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-12 mb10">
                                                            <div class="form-group form-group-icon-left">
                                                                <label for="discount_by_day_discount"><?php _e("Discount",ST_TEXTDOMAIN) ?></label>
                                                                <i class="fa fa-file-text input-icon input-icon-hightlight"></i>
                                                                <input value="<?php echo esc_html($discount_by_day['discount'][$key]); ?>" id="discount_by_day_discount"  name="discount_by_day[discount][]" type="text" placeholder="<?php _e("Max of number",ST_TEXTDOMAIN) ?>" class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; endif; ?>
                                <?php } ?>
                            </div>
                            <a href="javascript:void(0);" class="btn btn-primary add-list-item mt10" data-get-html="#list-item-discount-by-day">+</a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-xs-12">
                            <div class="form-group">
                                <label for=""><?php echo __('Discount type', ST_TEXTDOMAIN); ?></label>
                                <?php $discount_type_no_day = STInput::request('discount_type_no_day',get_post_meta($post_id  ,'discount_type_no_day' ,true)); ?>
                                <select name="discount_type_no_day" id="discount_type_no_day" class="form-control">
                                    <option <?php if($discount_type_no_day == "percent") echo "selected"?> value="percent"><?php echo __('Percent (%)', ST_TEXTDOMAIN); ?></option>
                                    <option <?php if($discount_type_no_day == "fixed") echo "selected"?> value="fixed"><?php echo __('Amount', ST_TEXTDOMAIN); ?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!--End discount by number day-->
                </div>
                <div class="add_service_step" id="step_rental_options">
                    <div class="row">
                        <div class="col-xs-12 col-sm-3">
                            <div class="form-group">
                                <label for=""><?php echo __('Allow booking full day', ST_TEXTDOMAIN); ?></label>
                                <?php $is_auto_caculate = STInput::request('allow_full_day',get_post_meta($post_id  ,'allow_full_day' ,true)); ?>
                                <select name="allow_full_day" id="allow_full_day" class="form-control">
                                    <option <?php if($is_auto_caculate == "on") echo "selected"?> value="on"><?php echo __('On', ST_TEXTDOMAIN); ?></option>
                                    <option <?php if($is_auto_caculate == "off") echo "selected"?> value="off"><?php echo __('Off', ST_TEXTDOMAIN); ?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class='col-md-6'>
                            <div class="form-group">
                                <label for="rentals_booking_period"><?php _e("Booking Period",ST_TEXTDOMAIN) ?>:</label>
                                <input id="rentals_booking_period" name="rentals_booking_period" type="number" min="0" placeholder="<?php _e("Booking Period (day)",ST_TEXTDOMAIN) ?>" class="form-control number" value="<?php echo STInput::request('rentals_booking_period',get_post_meta($post_id,'rentals_booking_period',true)) ?>">
                                <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('rentals_booking_period'),'danger') ?></div>
                            </div>
                        </div>
                        <div class='col-md-6'>
                            <div class="form-group">
                                <label for="rentals_booking_min_day"><?php _e("Minimum stay",ST_TEXTDOMAIN) ?>:</label>
                                <input id="rentals_booking_min_day" name="rentals_booking_min_day" type="number" min="0" placeholder="<?php _e("Minimum stay",ST_TEXTDOMAIN) ?>" class="form-control number" value="<?php echo STInput::request('rentals_booking_min_day',get_post_meta($post_id,'rentals_booking_min_day',true)) ?>">
                                <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('rentals_booking_min_day'),'danger') ?></div>
                            </div>
                        </div>
                        <?php
                        $set_external = st()->get_option('partner_set_external_link', 'off');
                        $current_uid = get_current_user_id();
                        $user_role = STUser_f::check_role_user_by_id($current_uid);
                        if(($set_external == 'on') || ($set_external == 'off' && in_array($user_role, array('administrator')))){
                            ?>
                            <div class="col-md-6">
                                <div class="form-group form-group-icon-left">

                                    <label for="st_rental_external_booking"><?php _e("External Booking",ST_TEXTDOMAIN) ?>:</label>
                                    <i class="fa fa-cogs input-icon input-icon-hightlight"></i>
                                    <?php $st_car_external_booking = STInput::request('st_rental_external_booking',get_post_meta($post_id , 'st_rental_external_booking' , true)) ?>
                                    <select class="form-control st_rental_external_booking" name="st_rental_external_booking" id="st_rental_external_booking">
                                        <option value="off" <?php if($st_car_external_booking == 'off') echo 'selected'; ?>><?php _e("No",ST_TEXTDOMAIN) ?></option>
                                        <option value="on" <?php if($st_car_external_booking == 'on') echo 'selected'; ?>><?php _e("Yes",ST_TEXTDOMAIN) ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class='col-md-6 data_st_rental_external_booking'>
                                <div class="form-group form-group-icon-left">

                                    <label for="st_rental_external_booking_link"><?php _e("External Booking URL",ST_TEXTDOMAIN) ?>:</label>
                                    <i class="fa fa-link  input-icon input-icon-hightlight"></i>
                                    <input id="st_rental_external_booking_link" name="st_rental_external_booking_link" type="text" placeholder="<?php _e("Eg: https://domain.com") ?>" class="form-control" value="<?php echo STInput::request('st_rental_external_booking_link',get_post_meta($post_id,'st_rental_external_booking_link',true)) ?>">
                                    <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('st_rental_external_booking_link'),'danger') ?></div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="add_service_step" id="step_cancel_booking">
                    <?php echo st()->load_template('user/tabs/cancel-booking',FALSE,array('validator'=>$validator,'hide_tab'=>true)) ?>
                </div>
                <div class="add_service_step" id="step_payment_settings">
                    <?php
                    $data_paypment = STPaymentGateways::get_payment_gateways();
                    if (!empty($data_paypment) and is_array($data_paypment)) {
                        foreach( $data_paypment as $k => $v ) {
                            $is_enable  = (st()->get_option('pm_gway_'.$k.'_enable'));
                            if ($is_enable =='off') {}else{
                                ?>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group form-group-icon-left">

                                            <label for="is_meta_payment_gateway_<?php echo esc_attr($k) ?>"><?php echo esc_html($v->get_name()) ?>:</label>
                                            <i class="fa fa-cogs input-icon input-icon-hightlight"></i>
                                            <?php $is_pay = STInput::request('is_meta_payment_gateway_'.$k,get_post_meta($post_id , 'is_meta_payment_gateway_'.$k , true)) ?>
                                            <select class="form-control" name="is_meta_payment_gateway_<?php echo esc_attr($k) ?>" id="is_meta_payment_gateway_<?php echo esc_attr($k) ?>">
                                                <option value="on" <?php if($is_pay == 'on') echo 'selected' ?>><?php _e( "Yes" , ST_TEXTDOMAIN ) ?></option>
                                                <option value="off" <?php if($is_pay == 'off') echo 'selected' ?>><?php _e( "No" , ST_TEXTDOMAIN ) ?></option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            <?php }
                        }
                    }
                    ?>
                </div>
                <div class="add_service_step" id="step_custom_fields">
                    <?php
                    $custom_field = st()->get_option( 'rental_unlimited_custom_field' );
                    if(!empty( $custom_field ) and is_array( $custom_field )) {
                        ?>
                        <div class="row">
                            <?php
                            foreach( $custom_field as $k => $v ) {
                                $key   = str_ireplace( '-' , '_' , 'st_custom_' . sanitize_title( $v[ 'title' ] ) );
                                $class = 'col-md-12';
                                if($v[ 'type_field' ] == "date-picker") {
                                    $class = 'col-md-4';
                                }
                                ?>
                                <div class="<?php echo esc_attr( $class ) ?>">
                                    <div class="form-group form-group-icon-left">
                                        <label for="<?php echo esc_attr( $key ) ?>"><?php echo esc_html($v[ 'title' ]) ?>:</label>
                                        <?php if($v[ 'type_field' ] == "text") { ?>
                                            <input id="<?php echo esc_attr( $key ) ?>" name="<?php echo esc_attr( $key ) ?>" type="text"
                                                   placeholder="<?php echo esc_html($v[ 'title' ]) ?>" class="form-control" value="<?php echo STInput::request($key,get_post_meta( $post_id , $key , true)) ?>">
                                        <?php } ?>
                                        <?php if($v[ 'type_field' ] == "date-picker") { ?>
                                            <input id="<?php echo esc_attr( $key ) ?>" name="<?php echo esc_attr( $key ) ?>" type="text"
                                                   placeholder="<?php echo esc_html($v[ 'title' ]) ?>"
                                                   class="date-pick form-control" value="<?php echo STInput::request($key,get_post_meta( $post_id , $key , true)); ?>">
                                        <?php } ?>
                                        <?php if($v[ 'type_field' ] == "textarea") { ?>
                                            <textarea id="<?php echo esc_attr( $key ) ?>" name="<?php echo esc_attr( $key ) ?>" class="form-control" ><?php echo STInput::request($key, get_post_meta( $post_id , $key , true)); ?></textarea>
                                        <?php } ?>

                                        <div class="st_msg console_msg_"></div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
                <?php if(!empty($post_id)){ ?>
                    <div class="add_service_step" id="step_availablility">
                        <?php echo st()->load_template('availability/form'); ?>
                    </div>
                    <div class="add_service_step" id="step_ical">

                        <div class="form-group form-group-icon-left">
                            <label for="default_state"><?php _e("Ical Sysc",ST_TEXTDOMAIN) ?>:</label>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <?php
                                $ical_url = get_post_meta($post_id, 'ical_url', true);
                                ?>
                                <div class="form-group">
                                    <div class="ical-sysc-wrapper">
                                        <div class="form-message"></div>
                                        <input name="ical_url" id="ical_url"
                                               value="<?php echo esc_attr( $ical_url ); ?>"
                                               class="form-control ical_input"
                                               type="text">
                                        <input type="hidden" name="post_id" value="<?php echo esc_attr( $post_id ); ?>">
                                        <button class="btn btn-primary btn-sm btn-ical-sysc"
                                                id="save_ical"><?php echo __( 'Import', ST_TEXTDOMAIN ); ?></button>
                                        <img class="spinner spinner-import" style="display: none; float: none; visibility: visible;"
                                             src="<?php echo admin_url( '/images/spinner.gif' ); ?>" alt="spinner">
                                        <p><small><i>
                                                    <?php
                                                    $time = get_post_meta( $post_id, 'sys_created', true );
                                                    if ( !empty( $time ) ) {
                                                        echo '(Last updated: ' . date( 'Y-m-d H:i:s', $time ) . ')';
                                                    }
                                                    ?>
                                                </i></small></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>

            <div class="text-center div_btn_submit">
                <?php if(!empty($post_id)){?>
                    <input  type="button" id="btn_check_insert_post_type_rental"  class="btn btn-primary btn-lg" value="<?php _e("UPDATE RENTAL",ST_TEXTDOMAIN) ?>">
                    <input name="btn_update_post_type_rental" id="btn_insert_post_type_rental" type="submit"  class="btn btn-primary hidden btn_partner_submit_form" value="SUBMIT">
                <?php }else{ ?>
                    <input  type="hidden"  class="save_and_preview" name="save_and_preview" value="false">
                    <input  type="hidden" id=""  class="" name="action_partner" value="add_partner">
                    <input name="btn_insert_post_type_rental" id="btn_insert_post_type_rental" type="submit" disabled class="btn btn-primary btn-lg btn_partner_submit_form" value="<?php _e("SUBMIT RENTAL",ST_TEXTDOMAIN) ?>">
                <?php } ?>


            </div>
        </form>

        <div class="data-extra-price-html" style="display: none">
            <div class="item">
                <div class="col-xs-12 col-sm-3">
                    <div class="form-group form-group-icon-left">

                        <label for="extra_title"><?php _e("Title",ST_TEXTDOMAIN) ?></label>
                        <i class="fa fa-file-text input-icon input-icon-hightlight"></i>
                        <input id="extra_title" data-date-format="yyyy-mm-dd" name="extra[title][]" type="text" placeholder="<?php _e("Title",ST_TEXTDOMAIN) ?>" class="form-control">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-3">
                    <div class="form-group form-group-icon-left">

                        <label for="extra_name"><?php _e("Name",ST_TEXTDOMAIN) ?></label>
                        <i class="fa fa-file-text input-icon input-icon-hightlight"></i>
                        <input id="extra_name" data-date-format="yyyy-mm-dd" name="extra[extra_name][]" type="text" placeholder="<?php _e("Name",ST_TEXTDOMAIN) ?>" class="form-control">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-3">
                    <div class="form-group form-group-icon-left">

                        <label for="extra_max_number"><?php _e("Max Of Number",ST_TEXTDOMAIN) ?></label>
                        <i class="fa fa-file-text input-icon input-icon-hightlight"></i>
                        <input id="extra_max_number" data-date-format="yyyy-mm-dd" name="extra[extra_max_number][]" type="text" placeholder="<?php _e("Max of number",ST_TEXTDOMAIN) ?>" class="form-control">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-2">
                    <div class="form-group form-group-icon-left">

                        <label for="extra_price"><?php _e("Price",ST_TEXTDOMAIN) ?></label>
                        <i class="fa fa-file-text input-icon input-icon-hightlight"></i>
                        <input id="extra_price" data-date-format="yyyy-mm-dd" name="extra[extra_price][]" type="text" placeholder="<?php _e("Price",ST_TEXTDOMAIN) ?>" class="form-control">
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group form-group-icon-left">
                        <div class="btn btn-danger btn_del_extra_price" style="margin-top: 27px">
                            X
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="list-item-properties" style="display: none">
            <div class="property-item tab-item">
                <a href="javascript: void(0);" class="delete-tab-item btn btn-danger">x</a>
                <div class="tab-title">&nbsp;</div>
                <div class="tab-content">
                    <div class="row">
                        <div class="col-xs-12 mb10">
                            <label for=""><?php echo __('Title', ST_TEXTDOMAIN); ?></label>
                            <input type="text" name="property-item[title][]" value="" class="tab-content-title form-control">
                        </div>
                        <div class="col-xs-12 mb10">
                            <label for=""><?php echo __('Featured Image', ST_TEXTDOMAIN); ?></label>
                            <div class="upload-wrapper upload-partner-wrapper-link">
                                <button class="upload-button-partner-link btn btn-primary btn-sm" data-uploader_title="<?php _e('Select a image to upload', ST_TEXTDOMAIN); ?>" data-uploader_button_text="<?php _e('Use this image', ST_TEXTDOMAIN); ?>"><?php echo __('Upload', ST_TEXTDOMAIN); ?></button>
                                <div class="upload-items">
                                    <div class="upload-item">
                                    </div>
                                </div>
                                <input type="hidden" class="save-image-url" name="property-item[featured_image][]" value="">
                            </div>
                        </div>
                        <div class="col-xs-12 mb10">
                            <label for=""><?php echo __('Description', ST_TEXTDOMAIN); ?></label>
                            <textarea name="property-item[description][]" id="" cols="30" rows="10" class="form-control"></textarea>
                        </div>
                        <div class="col-xs-12 mb10">
                            <label for=""><?php echo __('Icon Map',ST_TEXTDOMAIN); ?></label>
                            <div class="upload-wrapper upload-partner-wrapper-link">
                                <button class="upload-button-partner-link btn btn-primary btn-sm" data-uploader_title="<?php _e('Select a image to upload', ST_TEXTDOMAIN); ?>" data-uploader_button_text="<?php _e('Use this image', ST_TEXTDOMAIN); ?>"><?php echo __('Upload', ST_TEXTDOMAIN); ?></button>
                                <div class="upload-items">
                                    <div class="upload-item">
                                    </div>
                                </div>
                                <input type="hidden" class="save-image-url" name="property-item[icon][]" value="">
                            </div>
                        </div>
                        <div class="col-xs-12 mb10">
                            <label for=""><?php echo __('Lat', ST_TEXTDOMAIN); ?></label>
                            <input type="text" name="property-item[map_lat][]" value="" class="form-control">
                        </div>
                        <div class="col-xs-12 mb10">
                            <label for=""><?php echo __('Lng', ST_TEXTDOMAIN); ?></label>
                            <input type="text" name="property-item[map_lng][]" value="" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="list-item-rdistance" style="display: none">
            <div class="property-item tab-item">
                <a href="javascript: void(0);" class="delete-tab-item btn btn-danger">x</a>
                <div class="tab-title">&nbsp;</div>
                <div class="tab-content">
                    <div class="row">
                        <div class="col-xs-12 mb10">
                            <label for=""><?php echo __('Title', ST_TEXTDOMAIN); ?></label>
                            <input type="text" name="rdistance-item[title][]" value="" class="tab-content-title form-control">
                        </div>
                        <div class="col-xs-12 mb10">
                            <label for=""><?php echo __('Featured Image', ST_TEXTDOMAIN); ?></label>
                            <div class="upload-wrapper upload-partner-wrapper-link">
                                <button class="upload-button-partner-link btn btn-primary btn-sm" data-uploader_title="<?php _e('Select a image to upload', ST_TEXTDOMAIN); ?>" data-uploader_button_text="<?php _e('Use this image', ST_TEXTDOMAIN); ?>"><?php echo __('Upload', ST_TEXTDOMAIN); ?></button>
                                <div class="upload-items">
                                    <div class="upload-item">
                                    </div>
                                </div>
                                <input type="hidden" class="save-image-url" name="rdistance-item[icon][]" value="">
                            </div>
                        </div>
                        <div class="col-xs-12 mb10">
                            <label for=""><?php echo __('Name position', ST_TEXTDOMAIN); ?></label>
                            <input type="text" name="rdistance-item[name][]" value="" class="form-control">
                        </div>
                        <div class="col-xs-12 mb10">
                            <label for=""><?php echo __('Distance', ST_TEXTDOMAIN); ?></label>
                            <input type="text" name="rdistance-item[distance][]" value="" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="list-item-extraprice" style="display: none">
            <div class="property-item tab-item">
                <a href="javascript: void(0);" class="delete-tab-item btn btn-danger">x</a>
                <div class="tab-title">&nbsp;</div>
                <div class="tab-content">
                    <div class="row">
                        <div class="col-xs-12 mb10">
                            <div class="form-group">
                                <label for=""><?php _e( "Title" , ST_TEXTDOMAIN ) ?></label>
                                <input id="" name="extra[title][]" type="text" class="tab-content-title form-control" value="">
                            </div>
                        </div>
                        <div class="col-xs-12 mb10">
                            <div class="form-group form-group-icon-left">
                                <label for="extra_name"><?php _e("Name",ST_TEXTDOMAIN) ?></label>
                                <i class="fa fa-file-text input-icon input-icon-hightlight"></i>
                                <input value="" id="extra_name"  name="extra[extra_name][]" type="text" placeholder="<?php _e("Name",ST_TEXTDOMAIN) ?>" class="form-control">
                            </div>
                        </div>
                        <div class="col-xs-12 mb10">
                            <div class="form-group form-group-icon-left">
                                <label for="extra_max_number"><?php _e("Max Of Number",ST_TEXTDOMAIN) ?></label>
                                <i class="fa fa-file-text input-icon input-icon-hightlight"></i>
                                <input value="" id="extra_max_number"  name="extra[extra_max_number][]" type="text" placeholder="<?php _e("Max of number",ST_TEXTDOMAIN) ?>" class="form-control">
                            </div>
                        </div>
                        <div class="col-xs-12 mb10">
                            <div class="form-group form-group-icon-left">

                                <label for="extra_price"><?php _e("Price",ST_TEXTDOMAIN) ?></label>
                                <i class="fa fa-file-text input-icon input-icon-hightlight"></i>
                                <input value="" id="extra_price" name="extra[extra_price][]" type="text" placeholder="<?php _e("Price",ST_TEXTDOMAIN) ?>" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="list-item-discount-by-day" style="display: none">
            <div class="property-item tab-item">
                <a href="javascript: void(0);" class="delete-tab-item btn btn-danger">x</a>
                <div class="tab-title">&nbsp;</div>
                <div class="tab-content">
                    <div class="row">
                        <div class="col-xs-12 mb10">
                            <div class="form-group">
                                <label for=""><?php _e( "Title" , ST_TEXTDOMAIN ) ?></label>
                                <input id="" name="discount_by_day[title][]" type="text" class="tab-content-title form-control" value="">
                            </div>
                        </div>
                        <div class="col-xs-12 mb10">
                            <div class="form-group form-group-icon-left">
                                <label for="discount_by_day_number_day"><?php _e("No. days",ST_TEXTDOMAIN) ?></label>
                                <i class="fa fa-file-text input-icon input-icon-hightlight"></i>
                                <input value="" id="discount_by_day_number_day"  name="discount_by_day[number_day][]" type="text" placeholder="<?php _e("No. days",ST_TEXTDOMAIN) ?>" class="form-control">
                            </div>
                        </div>
                        <div class="col-xs-12 mb10">
                            <div class="form-group form-group-icon-left">
                                <label for="discount_by_day_discount"><?php _e("Discount",ST_TEXTDOMAIN) ?></label>
                                <i class="fa fa-file-text input-icon input-icon-hightlight"></i>
                                <input value="" id="discount_by_day_discount"  name="discount_by_day[discount][]" type="text" placeholder="<?php _e("Discount",ST_TEXTDOMAIN) ?>" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>