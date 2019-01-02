<?php
    /**
     * @package    WordPress
     * @subpackage Traveler
     * @since      1.0
     *
     * User create cars
     *
     * Created by ShineTheme
     *
     */
    wp_enqueue_script( 'bootstrap-datepicker.js' );
    wp_enqueue_script( 'bootstrap-datepicker-lang.js' );
    wp_enqueue_script( 'user_upload.js' );

    $post_id = STInput::request( 'id' );
    $title   = $content = $excerpt = "";
    if ( !empty( $post_id ) ) {
        $post    = get_post( $post_id );
        $title   = $post->post_title;
        $content = $post->post_content;
        $excerpt = $post->post_excerpt;
    }
    $validator = STUser_f::$validator;

    if ( empty( $post_id ) ) {

        //=== Validate package
        $admin_packages     = STAdminPackages::get_inst();
        $author             = get_current_user_id();
        $count_item_publish = $admin_packages->count_item_can_public( $author );
        if ( $admin_packages->enabled_membership() && $admin_packages->get_user_role() == 'partner' ) {
            if ( $count_item_publish !== 'unlimited' && $count_item_publish <= 0 ) {
                $user_link = get_permalink();
                echo '<div class="alert alert-warning mt20">' . __( 'You can not create a new item. Your items can be created is ', ST_TEXTDOMAIN ) . $admin_packages->count_item_package( $author ) . '. ' . '<a href="' . TravelHelper::get_user_dashboared_link( $user_link, 'setting' ) . '" target="_blank">' . __( 'More Details', ST_TEXTDOMAIN ) . '</a>' . '</div>';

                return false;
            }
        }
    }
?>
<div class="row">
    <div class="col-sm-3">
        <div class="add-service-progress">
            <div class="st-create">
                <h3 class="pull-left"><?php esc_html_e('Car Information',ST_TEXTDOMAIN) ?></h3>
            </div>
            <div class="sticky-box" data-width-from=".add-service-progress">
                <ul>
                    <li data-step="#step_general" class="selected"><i class="fa fa-home"></i> <?php esc_html_e('General',ST_TEXTDOMAIN) ?></li>
                    <li data-step="#step_location_settings"><i class="fa fa-map-marker"></i> <?php esc_html_e('Location Settings',ST_TEXTDOMAIN) ?></li>
                    <li data-step="#step_detail"><i class="fa fa-building"></i> <?php esc_html_e('Car Info',ST_TEXTDOMAIN) ?></li>
                    <li data-step="#step_contact_details"><i class="fa fa-user"></i> <?php esc_html_e('Contact Detail',ST_TEXTDOMAIN) ?></li>
                    <li data-step="#step_price_setting"><i class="fa fa-money"></i> <?php esc_html_e('Price Settings',ST_TEXTDOMAIN) ?></li>
                    <li data-step="#step_car_options"><i class="fa fa-building"></i> <?php esc_html_e('Car Settings',ST_TEXTDOMAIN) ?></li>
                    <li data-step="#step_cancel_booking"><i class="fa fa-ban"></i> <?php esc_html_e('Cancel Booking',ST_TEXTDOMAIN) ?></li>

                    <?php $st_is_woocommerce_checkout=apply_filters('st_is_woocommerce_checkout',false);
                    if(!$st_is_woocommerce_checkout):?>
                        <li data-step="#step_payment_settings"><i class="fa fa-credit-card"></i> <?php esc_html_e('Payment Settings',ST_TEXTDOMAIN) ?></li>
                    <?php endif ?>

                    <?php $custom_field = st()->get_option( 'st_cars_unlimited_custom_field' );
                    if(!empty( $custom_field ) and is_array( $custom_field )) { ?>
                        <li data-step="#step_custom_fields"><i class="fa fa-bars"></i> <?php _e("Custom Fields",ST_TEXTDOMAIN) ?></li>
                    <?php } ?>

                </ul>
            </div>
        </div>
    </div>
    <div class="col-sm-9">

        <div class="st-create">
            <h3 class="pull-left">
                <?php if ( !empty( $post_id ) ) { ?>
                    <?php _e( "Edit Car", ST_TEXTDOMAIN ) ?>
                <?php } else { ?>
                    <?php _e( "Add Car", ST_TEXTDOMAIN ) ?>
                <?php } ?>
            </h3>
            <?php if ( !empty( $post_id ) ) { ?>
                <a target="_blank" href="<?php echo get_the_permalink( $post_id ) ?>"
                   class="btn btn-default pull-right btn-icon"><i class="fa fa-save"></i> <?php _e( "Preview", ST_TEXTDOMAIN ) ?></a>
            <?php } else { ?>
                <span
                        class="btn btn-default pull-right btn_save_and_preview"><i class="fa fa-save"></i> <?php _e( "Save & Preview", ST_TEXTDOMAIN ) ?></span>
            <?php } ?>
        </div>
        <div class="msg">
            <?php echo STTemplate::message() ?>
            <?php echo STUser_f::get_msg(); ?>
            <?php echo STUser_f::get_control_data(); ?>
        </div>
        <form action="" method="post" enctype="multipart/form-data" id="st_form_add_partner">
            <?php wp_nonce_field( 'user_setting', 'st_update_post_cars' ); ?>
            <div class="add_service_step_wraps">
                <div class="add_service_step active" id="step_general">
                    <div class="form-group form-group-icon-left">
                        <label for="title" class="head_bol"><?php _e( "Title", ST_TEXTDOMAIN ) ?> <span
                                    class="text-small text-danger">*</span>:</label>
                        <i class="fa  fa-file-text input-icon input-icon-hightlight"></i>
                        <input data-rules="required" id="st_title_car" name="st_title_car" type="text"
                               placeholder="<?php _e( "Title", ST_TEXTDOMAIN ) ?>" class="form-control"
                               value="<?php echo stripslashes( STInput::request( "st_title_car", $title ) ) ?>">
                        <div class="st_msg"><?php echo STUser_f::get_msg_html( $validator->error( 'st_title_car' ), 'danger' ) ?></div>
                        <?php st_validate_msg(esc_html__("The Title field is required.",ST_TEXTDOMAIN),'required') ?>

                    </div>
                    <div class="form-group form-group-icon-left">
                        <label for="st_content" class="head_bol"><?php st_the_language( 'user_create_car_content' ) ?> <span
                                    class="text-small text-danger">*</span>:</label>
                        <?php
                        ob_start();
                        wp_editor( stripslashes(STInput::request("st_content",$content)) ,'st_content');
                        $html=ob_get_clean();
                        echo str_replace('<textarea ','<textarea data-rules="required" ',$html);
                        ?>
                        <div class="st_msg"><?php echo STUser_f::get_msg_html( $validator->error( 'st_content' ), 'danger' ) ?></div>
                        <?php st_validate_msg(esc_html__("The Content field is required.",ST_TEXTDOMAIN),'required') ?>

                    </div>
                    <div class="form-group">
                        <label for="desc" class="head_bol"><?php _e( "Description", ST_TEXTDOMAIN ) ?> <span
                                    class="text-small text-danger">*</span>:</label>
                        <textarea data-rules="required" id="desc" rows="6" name="st_desc"
                                  class="form-control"><?php echo stripslashes( STInput::request( "st_desc", $excerpt ) ) ?></textarea>
                        <div class="st_msg"><?php echo STUser_f::get_msg_html( $validator->error( 'st_desc' ), 'danger' ) ?></div>
                        <?php st_validate_msg(esc_html__("The Description field is required.",ST_TEXTDOMAIN),'required') ?>

                    </div>
                    <div class="form-group form-group-icon-left">
                        <label for="id_featured_image" class="head_bol"><?php _e( "Featured image", ST_TEXTDOMAIN ) ?> <span
                                    class="text-small text-danger">*</span>:</label>
                        <div class="upload-wrapper upload-partner-wrapper">
                            <button class="upload-button-partner btn btn-primary btn-sm"
                                    data-uploader_title="<?php _e( 'Select a image to upload', ST_TEXTDOMAIN ); ?>"
                                    data-uploader_button_text="<?php _e( 'Use this image', ST_TEXTDOMAIN ); ?>"><?php echo __( 'Upload', ST_TEXTDOMAIN ); ?></button>
                            <div class="upload-items">
                                <?php
                                $thumbnail     = STInput::request( 'id_featured_image', get_post_thumbnail_id( $post_id ) );
                                $thumbnail_url = wp_get_attachment_url( $thumbnail );
                                if ( !empty( $thumbnail_url ) ):
                                    ?>
                                    <div class="upload-item">
                                        <img src="<?php echo $thumbnail_url; ?>" alt="<?php echo TravelHelper::get_alt_image(); ?>"
                                             class="frontend-image img-responsive">
                                        <a href="javascript: void(0);" class="delete">&times;</a>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <input data-rules="required" type="hidden" class="save-image-id" name="id_featured_image" value="<?php echo $thumbnail; ?>">
                        </div>
                        <div class="st_msg"><?php echo STUser_f::get_msg_html( $validator->error( 'featured_image' ), 'danger' ) ?></div>
                        <?php st_validate_msg(esc_html__("The Feature Image field is required.",ST_TEXTDOMAIN),'required') ?>
                    </div>
                </div>
                <div class="add_service_step" id="step_location_settings">
                    <div class="row">
                        <div class="col-md-12 multi_location_wrapper">
                            <div class="form-group form-group-icon-left">
                                <label for="multi_location"><?php _e( 'Location', ST_TEXTDOMAIN ); ?>:</label>
                                <div id="setting_multi_location" class="location-front">
                                    <?php
                                    $html_location = TravelHelper::treeLocationHtml();
                                    $post_id       = STInput::request( 'id', '' );

                                    $multi_location = get_post_meta( $post_id, 'multi_location', true );
                                    if ( !empty( $multi_location ) && !is_array( $multi_location ) ) {
                                        $multi_location = explode( ',', $multi_location );
                                    }
                                    if ( empty( $multi_location ) ) {
                                        $multi_location = [ '' ];
                                    }
                                    ?>
                                    <div class="form-group st-select-loction">
                                        <input placeholder="<?php echo __( 'Type to search', ST_TEXTDOMAIN ); ?>"
                                               type="text" class="widefat form-control" name="search" value="">
                                        <div class="list-location-wrapper">
                                            <?php
                                            if ( is_array( $html_location ) && count( $html_location ) ):
                                                foreach ( $html_location as $key => $location ):
                                                    ?>
                                                    <div data-name="<?php echo $location[ 'parent_name' ]; ?>"
                                                         class="item"
                                                         style="margin-left: <?php echo $location[ 'level' ] . 'px;'; ?> margin-bottom: 5px;">
                                                        <label for="<?php echo 'location-' . $location[ 'ID' ]; ?>">
                                                            <input <?php if ( in_array( '_' . $location[ 'ID' ] . '_', $multi_location ) ) echo 'checked'; ?>
                                                                    id="<?php echo 'location-' . $location[ 'ID' ]; ?>"
                                                                    type="checkbox" name="multi_location[]"
                                                                    value="<?php echo '_' . $location[ 'ID' ] . '_'; ?>">
                                                            <span><?php echo $location[ 'post_title' ]; ?></span>
                                                        </label>
                                                    </div>
                                                <?php endforeach; endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div
                                        class="st_msg"><?php echo STUser_f::get_msg_html( $validator->error( 'multi_location' ), 'danger' ) ?></div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group form-group-icon-left">

                                <label for="address"><?php _e( 'Address', ST_TEXTDOMAIN ); ?> <span
                                            class="text-small text-danger">*</span>:</label>
                                <i class="fa fa-home input-icon input-icon-hightlight"></i>
                                <input data-rules="required" id="address" name="address" type="text"
                                       placeholder="<?php st_the_language( 'user_create_car_address' ) ?>"
                                       class="form-control"
                                       value="<?php echo STInput::request( 'address', get_post_meta( $post_id, 'cars_address', true ) ); ?>">
                                <div
                                        class="st_msg"><?php echo STUser_f::get_msg_html( $validator->error( 'address' ), 'danger' ) ?></div>
                                <?php st_validate_msg(esc_html__("The Address field is required.",ST_TEXTDOMAIN),'required') ?>

                            </div>
                        </div>

                        <div class="col-md-12 partner_map">
                            <?php
                            if ( class_exists( 'BTCustomOT' ) ) {
                                BTCustomOT::load_fields();
                                ot_type_bt_gmap_html();
                            }
                            ?>
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            <label for=""><?php echo __( 'Properties near by', ST_TEXTDOMAIN ); ?></label>
                            <div class="list-properties">
                                <?php
                                $properties = get_post_meta( $post_id, 'properties_near_by', true );
                                if ( !empty( $properties ) ):
                                    foreach ( $properties as $key => $val ):
                                        ?>
                                        <div class="property-item tab-item">
                                            <a href="javascript: void(0);" class="delete-tab-item btn btn-danger">x</a>
                                            <div class="tab-title"><?php echo esc_html( $val[ 'title' ] ); ?></div>
                                            <div class="tab-content">
                                                <div class="row">
                                                    <div class="col-xs-12 mb10">
                                                        <label
                                                                for=""><?php echo __( 'Title', ST_TEXTDOMAIN ); ?></label>
                                                        <input type="text" name="property-item[title][]"
                                                               value="<?php echo esc_html( $val[ 'title' ] ); ?>"
                                                               class="tab-content-title form-control">
                                                    </div>
                                                    <div class="col-xs-12 mb10">
                                                        <label
                                                                for=""><?php echo __( 'Featured Image', ST_TEXTDOMAIN ); ?></label>
                                                        <div class="upload-wrapper upload-partner-wrapper-link">
                                                            <button
                                                                    class="upload-button-partner-link btn btn-primary btn-sm"
                                                                    data-uploader_title="<?php _e( 'Select a image to upload', ST_TEXTDOMAIN ); ?>"
                                                                    data-uploader_button_text="<?php _e( 'Use this image', ST_TEXTDOMAIN ); ?>"><?php echo __( 'Upload', ST_TEXTDOMAIN ); ?></button>
                                                            <div class="upload-items">
                                                                <?php
                                                                $featured_image = $val[ 'featured_image' ];
                                                                if ( !empty( $featured_image ) ):
                                                                    ?>
                                                                    <div class="upload-item">
                                                                        <img src="<?php echo $featured_image; ?>"
                                                                             alt="<?php echo TravelHelper::get_alt_image(); ?>"
                                                                             class="frontend-image img-responsive">
                                                                        <a href="javascript: void(0);"
                                                                           class="delete">&times;</a>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                            <input type="hidden" class="save-image-url"
                                                                   name="property-item[featured_image][]"
                                                                   value="<?php echo $featured_image; ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12 mb10">
                                                        <label
                                                                for=""><?php echo __( 'Description', ST_TEXTDOMAIN ); ?></label>
                                                        <textarea name="property-item[description][]" id="" cols="30"
                                                                  rows="10"
                                                                  class="form-control"><?php echo $val[ 'description' ]; ?></textarea>
                                                    </div>
                                                    <div class="col-xs-12 mb10">
                                                        <label
                                                                for=""><?php echo __( 'Icon Map', ST_TEXTDOMAIN ); ?></label>
                                                        <div class="upload-wrapper upload-partner-wrapper-link">
                                                            <button
                                                                    class="upload-button-partner-link btn btn-primary btn-sm"
                                                                    data-uploader_title="<?php _e( 'Select a image to upload', ST_TEXTDOMAIN ); ?>"
                                                                    data-uploader_button_text="<?php _e( 'Use this image', ST_TEXTDOMAIN ); ?>"><?php echo __( 'Upload', ST_TEXTDOMAIN ); ?></button>
                                                            <div class="upload-items">
                                                                <?php
                                                                $featured_image = $val[ 'icon' ];
                                                                if ( !empty( $featured_image ) ):
                                                                    ?>
                                                                    <div class="upload-item">
                                                                        <img src="<?php echo $featured_image; ?>"
                                                                             alt="<?php echo TravelHelper::get_alt_image(); ?>"
                                                                             class="frontend-image img-responsive">
                                                                        <a href="javascript: void(0);"
                                                                           class="delete">&times;</a>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                            <input type="hidden" class="save-image-url"
                                                                   name="property-item[icon][]"
                                                                   value="<?php echo $featured_image; ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12 mb10">
                                                        <label for=""><?php echo __( 'Lat', ST_TEXTDOMAIN ); ?></label>
                                                        <input type="text" name="property-item[map_lat][]"
                                                               value="<?php echo esc_html( $val[ 'map_lat' ] ); ?>"
                                                               class="form-control">
                                                    </div>
                                                    <div class="col-xs-12 mb10">
                                                        <label for=""><?php echo __( 'Lng', ST_TEXTDOMAIN ); ?></label>
                                                        <input type="text" name="property-item[map_lng][]"
                                                               value="<?php echo esc_html( $val[ 'map_lng' ] ); ?>"
                                                               class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; endif; ?>
                            </div>
                            <a href="javascript:void(0);" class="btn btn-primary add-list-item mt10"
                               data-get-html="#list-item-properties">+</a>
                        </div>
                        <div class="col-md-6">
                            <br>
                            <div class='form-group form-group-icon-left'>
                                <label for="is_featured"><?php _e( "Enable street views", ST_TEXTDOMAIN ) ?>:</label>
                                <i class="fa fa-cogs input-icon input-icon-hightlight"></i>
                                <?php $enable_street_views_google_map = STInput::request( 'enable_street_views_google_map', get_post_meta( $post_id, 'enable_street_views_google_map', true ) ) ?>
                                <select class='form-control' name='enable_street_views_google_map'
                                        id="enable_street_views_google_map">
                                    <option
                                            value='on' <?php if ( $enable_street_views_google_map == 'on' ) echo 'selected'; ?> ><?php _e( "On", ST_TEXTDOMAIN ) ?></option>
                                    <option
                                            value='off' <?php if ( $enable_street_views_google_map == 'off' ) echo 'selected'; ?> ><?php _e( "Off", ST_TEXTDOMAIN ) ?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="add_service_step" id="step_detail">
                    <div class="row">
                        <?php
                        $taxonomies_temp = ( get_object_taxonomies( 'st_cars' ) );
                        $taxonomies = array();
                        if(!empty($taxonomies_temp)) {
                            $i = 0;
                            foreach ($taxonomies_temp as $key => $value) {
                                $taxonomy_tmp = get_taxonomy($value);
                                $taxonomy_name = ($taxonomy_tmp->label);
                                $taxonomies[strtolower(str_replace(' ', '_', $taxonomy_name)) . $i] = $value;
                                $i++;
                            }
                            ksort($taxonomies);
                        }
                        if ( is_array( $taxonomies ) and !empty( $taxonomies ) ) {
                            foreach ( $taxonomies as $key => $value ) {
                                ?>
                                <div class="col-md-12">
                                    <?php
                                    $category       = STUser_f::get_list_taxonomy( $value );
                                    $taxonomy_tmp   = get_taxonomy( $value );
                                    $taxonomy_label = ( $taxonomy_tmp->label );
                                    $taxonomy_name  = ( $taxonomy_tmp->name );
                                    if ( !empty( $category ) ):
                                        ?>
                                        <div class="form-group form-group-icon-left">
                                            <label for="check_all"> <?php echo esc_html( $taxonomy_label ); ?>
                                                :</label>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="checkbox-inline checkbox-stroke">
                                                        <label for="check_all">
                                                            <i class="fa fa-cogs"></i>
                                                            <input name="check_all" class="i-check check_all"
                                                                   type="checkbox"/><?php _e( "All", ST_TEXTDOMAIN ) ?>
                                                        </label>
                                                    </div>
                                                </div>
                                                <?php foreach ( $category as $k => $v ):
                                                    $icon = get_tax_meta( $k, 'st_icon' );
                                                    $icon = TravelHelper::handle_icon( $icon );
                                                    $check = '';
                                                    if ( STUser_f::st_check_post_term_partner( $post_id, $value, $k ) == true ) {
                                                        $check = 'checked';
                                                    }
                                                    ?>
                                                    <div class="col-md-3">
                                                        <div class="checkbox-inline checkbox-stroke">
                                                            <label for="taxonomy">
                                                                <i class="<?php echo esc_html( $icon ) ?>"></i>
                                                                <input name="taxonomy[]"
                                                                       class="i-check item_tanoxomy"
                                                                       type="checkbox" <?php echo esc_html( $check ) ?>
                                                                       value="<?php echo esc_attr( $k . ',' . $taxonomy_name ) ?>"/><?php echo esc_html( $v ) ?>
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
                            <div
                                    class="st_msg"><?php echo STUser_f::get_msg_html( $validator->error( 'taxonomy[]' ), 'danger' ) ?></div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group form-group-icon-left">
                                <label
                                        for="create_car_equipment_price"><?php st_the_language( 'user_create_car_equipment_price_list' ) ?>
                                    :</label>
                            </div>
                        </div>
                        <div class="col-xs-12" id="data_equipment_item">
                            <div class="list-properties">
                                <?php if ( !empty( $post_id ) ) { ?>
                                    <?php $data = get_post_meta( $post_id, 'cars_equipment_list', true ); ?>
                                    <?php
                                    if ( !empty( $data ) ) {
                                        foreach ( $data as $k => $v ) {
                                            ?>
                                            <div class="property-item tab-item">
                                                <a href="javascript: void(0);" class="delete-tab-item btn btn-danger">x</a>
                                                <div class="tab-title"><?php echo esc_html( $v[ 'title' ] ) ?></div>
                                                <div class="tab-content">
                                                    <div class="row">
                                                        <div class="col-xs-12 mb10">
                                                            <div class="form-group">
                                                                <label
                                                                        for="policy_title"><?php _e( "Title", ST_TEXTDOMAIN ) ?></label>
                                                                <input id="" name="equipment_item_title[]" type="text"
                                                                       class="tab-content-title form-control"
                                                                       value="<?php echo esc_html( $v[ 'title' ] ) ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-12 mb10">
                                                            <div class="form-group ">
                                                                <label
                                                                        for="equipment_item_price"><?php st_the_language( 'user_create_car_equipment_price' ) ?></label>
                                                                <input id="price" name="equipment_item_price[]" type="text"
                                                                       class="form-control"
                                                                       value="<?php echo esc_html( $v[ 'cars_equipment_list_price' ] ) ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-12 mb10">
                                                            <div class="form-group ">
                                                                <label
                                                                        for="equipment_item_price_unit"><?php _e( "Price unit", ST_TEXTDOMAIN ) ?></label>
                                                                <select class="form-control" id="equipment_item_price_unit"
                                                                        name="equipment_item_price_unit[]">
                                                                    <option
                                                                            value=""><?php _e( "Fixed Price", ST_TEXTDOMAIN ) ?></option>
                                                                    <option
                                                                            value="per_hour" <?php if ( $v[ 'price_unit' ] == 'per_hour' ) echo "selected" ?> ><?php _e( "Price per Hour", ST_TEXTDOMAIN ) ?></option>
                                                                    <option
                                                                            value="per_day" <?php if ( $v[ 'price_unit' ] == 'per_day' ) echo "selected" ?> ><?php _e( "Price per Day", ST_TEXTDOMAIN ) ?></option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-12 mb10">
                                                            <div class="form-group ">
                                                                <label
                                                                        for="price_max"><?php _e( "Price max", ST_TEXTDOMAIN ) ?></label>
                                                                <input id="price_max" name="equipment_item_price_max[]"
                                                                       type="text" class="form-control number"
                                                                       value="<?php echo esc_html( $v[ 'cars_equipment_list_price_max' ] ) ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-12 mb10">
                                                            <div class="form-group ">
                                                                <label
                                                                        for="cars_equipment_list_number"><?php _e( "Number of item", ST_TEXTDOMAIN ) ?></label>
                                                                <input id="cars_equipment_list_number"
                                                                       name="cars_equipment_list_number[]" type="text"
                                                                       class="form-control number"
                                                                       value="<?php if ( isset( $v[ 'cars_equipment_list_number' ] ) ) echo esc_html( $v[ 'cars_equipment_list_number' ] ) ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                    }
                                    ?>
                                <?php } else { ?>
                                    <?php
                                    $equipment_item_title       = STInput::request( 'equipment_item_title' );
                                    $equipment_item_price       = STInput::request( 'equipment_item_price' );
                                    $equipment_item_price_unit  = STInput::request( 'equipment_item_price_unit' );
                                    $equipment_item_price_max   = STInput::request( 'equipment_item_price_max' );
                                    $cars_equipment_list_number = STInput::request( 'cars_equipment_list_number' );
                                    ?>
                                    <?php
                                    if ( !empty( $equipment_item_title ) ) {
                                        foreach ( $equipment_item_title as $k => $v ) {
                                            if ( !empty( $v ) and !empty( $equipment_item_price[ $k ] ) ) {
                                                ?>
                                                <div class="property-item tab-item">
                                                    <a href="javascript: void(0);"
                                                       class="delete-tab-item btn btn-danger">x</a>
                                                    <div class="tab-title"><?php echo esc_html( $v ) ?></div>
                                                    <div class="tab-content">
                                                        <div class="row">
                                                            <div class="col-xs-12 mb10">
                                                                <div class="form-group">
                                                                    <label
                                                                            for="policy_title"><?php _e( "Title", ST_TEXTDOMAIN ) ?></label>
                                                                    <input id="" name="equipment_item_title[]" type="text"
                                                                           class="tab-content-title form-control"
                                                                           value="<?php echo esc_html( $v ) ?>">
                                                                </div>
                                                            </div>
                                                            <div class="col-xs-12 mb10">
                                                                <div class="form-group ">
                                                                    <label
                                                                            for="equipment_item_price"><?php st_the_language( 'user_create_car_equipment_price' ) ?></label>
                                                                    <input id="price" name="equipment_item_price[]"
                                                                           type="text" class="form-control number"
                                                                           value="<?php echo esc_html( $equipment_item_price[ $k ] ) ?>">
                                                                </div>
                                                            </div>
                                                            <div class="col-xs-12 mb10">
                                                                <div class="form-group ">
                                                                    <label
                                                                            for="equipment_item_price_unit"><?php _e( "Price unit", ST_TEXTDOMAIN ) ?></label>
                                                                    <select class="form-control"
                                                                            id="equipment_item_price_unit"
                                                                            name="equipment_item_price_unit[]">
                                                                        <option
                                                                                value=""><?php _e( "Fixed Price", ST_TEXTDOMAIN ) ?></option>
                                                                        <option
                                                                                value="per_hour" <?php if ( $equipment_item_price_unit[ $k ] == 'per_hour' ) echo "selected" ?> ><?php _e( "Price per Hour", ST_TEXTDOMAIN ) ?></option>
                                                                        <option
                                                                                value="per_day" <?php if ( $equipment_item_price_unit[ $k ] == 'per_day' ) echo "selected" ?> ><?php _e( "Price per Day", ST_TEXTDOMAIN ) ?></option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-xs-12 mb10">
                                                                <div class="form-group ">
                                                                    <label
                                                                            for="price_max"><?php _e( "Price max", ST_TEXTDOMAIN ) ?></label>
                                                                    <input id="price_max" name="equipment_item_price_max[]"
                                                                           type="text" class="form-control number"
                                                                           value="<?php echo esc_html( $equipment_item_price_max[ $k ] ) ?>">
                                                                </div>
                                                            </div>
                                                            <div class="col-xs-12 mb10">
                                                                <div class="form-group ">
                                                                    <label
                                                                            for="cars_equipment_list_number"><?php _e( "Number of item", ST_TEXTDOMAIN ) ?></label>
                                                                    <input id="cars_equipment_list_number"
                                                                           name="cars_equipment_list_number[]" type="text"
                                                                           class="form-control number"
                                                                           value="<?php echo esc_html( $cars_equipment_list_number[ $k ] ) ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                        }
                                    }
                                    ?>
                                <?php } ?>
                            </div>
                            <a href="javascript:void(0);" class="btn btn-primary add-list-item mt10"
                               data-get-html="#list-item-equipment">+</a>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group form-group-icon-left">
                                <label for="create_car_features"><?php _e( 'More features', ST_TEXTDOMAIN ); ?>:</label>
                            </div>
                        </div>
                        <?php if ( !empty( $post_id ) ) { ?>
                            <?php $data = get_post_meta( $post_id, 'cars_equipment_info', true ); ?>
                            <div class="" id="data_features">
                                <?php
                                if ( !empty( $data ) ) {
                                    foreach ( $data as $key => $value ) {
                                        ?>
                                        <?php $list = STUser_f::get_list_value_taxonomy( 'st_cars' ); ?>
                                        <div class="item">
                                            <div class="col-md-4">
                                                <div class="form-group form-group-icon-left">

                                                    <label
                                                            for="features_taxonomy"><?php st_the_language( 'user_create_car_features_attributes' ) ?></label>
                                                    <i class="fa fa-arrow-down input-icon input-icon-hightlight"></i>
                                                    <?php
                                                    if ( !empty( $list ) ) {
                                                        ?>
                                                        <select name="features_taxonomy[]"
                                                                class="form-control taxonomy_car">
                                                            <?php foreach ( $list as $k => $v ) { ?>
                                                                <option <?php if ( $v[ 'value' ] == $value[ 'cars_equipment_taxonomy_id' ] ) echo 'selected'; ?>
                                                                        data-icon="<?php echo esc_attr( $v[ 'icon' ] ) ?>"
                                                                        value="<?php echo esc_attr( $v[ 'value' ] . ',' . $v[ 'label' ] ) ?>"><?php echo esc_attr( $v[ 'label' ] ) ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            <div class="col-md-7">
                                                <div class="form-group ">
                                                    <label
                                                            for="taxonomy_info"><?php st_the_language( 'user_create_car_features_attributes_info' ) ?></label>
                                                    <input id="title" name="taxonomy_info[]" type="text"
                                                           class="form-control"
                                                           value="<?php echo esc_html( $value[ 'cars_equipment_taxonomy_info' ] ) ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <div class="form-group form-group-icon-left">
                                                    <div class="btn btn-danger btn_del_program"
                                                         style="margin-top: 27px">
                                                        X
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                }
                                ?>
                            </div>
                        <?php } else { ?>
                            <div class="" id="data_features">
                                <?php
                                $features_taxonomy = STInput::request( 'features_taxonomy' );
                                $taxonomy_info     = STInput::request( 'taxonomy_info' );
                                if ( !empty( $features_taxonomy ) ) {
                                    foreach ( $features_taxonomy as $key => $value ) {
                                        ?>
                                        <?php $list = STUser_f::get_list_value_taxonomy( 'st_cars' ); ?>
                                        <div class="item">
                                            <div class="col-md-4">
                                                <div class="form-group form-group-icon-left">

                                                    <label
                                                            for="features_taxonomy"><?php st_the_language( 'user_create_car_features_attributes' ) ?></label>
                                                    <i class="fa fa-arrow-down input-icon input-icon-hightlight"></i>
                                                    <?php
                                                    if ( !empty( $list ) ) {
                                                        $id = explode( ',', $value );
                                                        $id = $id[ 0 ];
                                                        ?>
                                                        <select name="features_taxonomy[]" id="features_taxonomy"
                                                                class="form-control taxonomy_car">
                                                            <?php foreach ( $list as $k => $v ) { ?>
                                                                <option <?php if ( $v[ 'value' ] == $id )
                                                                    echo 'selected'; ?>
                                                                        data-icon="<?php echo esc_attr( $v[ 'icon' ] ) ?>"
                                                                        value="<?php echo esc_attr( $v[ 'value' ] . ',' . $v[ 'label' ] ) ?>"><?php echo esc_attr( $v[ 'label' ] ) ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            <div class="col-md-7">
                                                <div class="form-group ">
                                                    <label
                                                            for="taxonomy_info"><?php st_the_language( 'user_create_car_features_attributes_info' ) ?></label>
                                                    <input id="title" name="taxonomy_info[]" type="text"
                                                           class="form-control"
                                                           value="<?php echo esc_html( $taxonomy_info[ $key ] ) ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <div class="form-group form-group-icon-left">
                                                    <div class="btn btn-danger btn_del_program"
                                                         style="margin-top: 27px">
                                                        X
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                }
                                ?>
                            </div>
                        <?php } ?>

                        <div class="col-md-12 text-right">
                            <div class="form-group form-group-icon-left">
                                <button id="btn_add_features" type="button"
                                        class="btn btn-info btn-sm"><?php st_the_language( 'user_create_car_add_features' ) ?></button>
                                <br>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class='form-group form-group-icon-left'>

                                <label for="st_custom_layout"><?php _e( "Car single layout", ST_TEXTDOMAIN ) ?>:</label>
                                <i class="fa fa-cogs input-icon input-icon-hightlight"></i>
                                <?php $layout = st_get_layout( 'st_cars' );
                                if ( !empty( $layout ) and is_array( $layout ) ):
                                    ?>
                                    <select class='form-control' name='st_custom_layout' id="st_custom_layout">
                                        <?php
                                        $st_custom_layout = STInput::request( 'st_custom_layout', get_post_meta( $post_id, 'st_custom_layout', true ) );
                                        foreach ( $layout as $k => $v ):
                                            if ( $st_custom_layout == $v[ 'value' ] ) $check = "selected"; else $check = '';
                                            echo '<option ' . $check . ' value=' . $v[ 'value' ] . '>' . $v[ 'label' ] . '</option>';
                                        endforeach;
                                        ?>
                                    </select>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class='form-group form-group-icon-left'>
                                <?php
                                $author         = get_current_user_id();
                                $admin_packages = STAdminPackages::get_inst();
                                $item_featured  = $admin_packages->count_item_can_featured( $author );
                                if ( st()->get_option( 'partner_set_feature' ) == "on" ) { ?>

                                    <label for="is_featured"><?php _e( "Set car as feature", ST_TEXTDOMAIN ) ?>:</label>
                                    <i class="fa fa-cogs input-icon input-icon-hightlight"></i>
                                    <?php $is_featured = STInput::request( 'is_featured', get_post_meta( $post_id, 'is_featured', true ) ) ?>
                                    <select class='form-control' name='is_featured' id="is_featured">
                                        <option
                                                value='off' <?php if ( $is_featured == 'off' ) echo 'selected'; ?> ><?php _e( "No", ST_TEXTDOMAIN ) ?></option>
                                        <option
                                                value='on' <?php if ( $is_featured == 'on' ) echo 'selected'; ?> ><?php _e( "Yes", ST_TEXTDOMAIN ) ?></option>
                                    </select>
                                <?php }; ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group form-group-icon-left">

                                <label for="video"><?php _e( 'Car video', ST_TEXTDOMAIN ); ?>:</label>
                                <i class="fa  fa-youtube-play input-icon input-icon-hightlight"></i>
                                <input id="video" name="video" type="text"
                                       placeholder="<?php _e( "Enter Youtube or Vimeo video link (Eg: https://www.youtube.com/watch?v=JL-pGPVQ1a8)" ) ?>"
                                       class="form-control "
                                       value="<?php echo STInput::request( 'video', get_post_meta( $post_id, 'video', true ) ) ?>">
                                <div
                                        class="st_msg"><?php echo STUser_f::get_msg_html( $validator->error( 'video' ), 'danger' ) ?></div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group form-group-icon-left">
                                <label for="id_gallery"><?php _e( "Car gallery", ST_TEXTDOMAIN ) ?> <span
                                            class="text-small text-danger">*</span>:</label>
                                <div class="upload-wrapper upload-mul-partner-wrapper">
                                    <?php
                                    $gallery     = STInput::request( 'id_gallery', get_post_meta( $post_id, 'gallery', true ) );
                                    $gallery_arr = explode( ',', $gallery );
                                    $gallery_arr = array_filter( $gallery_arr, function ( $value ) {
                                        return $value != '';
                                    } );
                                    ?>
                                    <div class="clearfix">
                                        <button class="mr5 upload-button-partner-multi btn btn-primary btn-sm"
                                                data-uploader_title="<?php _e( 'Select a image to upload', ST_TEXTDOMAIN ); ?>"
                                                data-uploader_button_text="<?php _e( 'Use this image', ST_TEXTDOMAIN ); ?>"><?php echo __( 'Upload', ST_TEXTDOMAIN ); ?></button>
                                        <?php
                                        if ( !empty( $gallery_arr ) ):
                                            ?>
                                            <button
                                                    class=" btn btn-primary btn-sm delete-gallery"><?php echo __( 'Delete', ST_TEXTDOMAIN ); ?></button>
                                        <?php endif; ?>
                                    </div>
                                    <div class="upload-items">
                                        <?php
                                        if ( !empty( $gallery_arr ) ):
                                            foreach ( $gallery_arr as $image ):
                                                $gallery_url = wp_get_attachment_url( $image );
                                                ?>
                                                <div class="upload-item">
                                                    <img src="<?php echo $gallery_url; ?>"
                                                         alt="<?php echo TravelHelper::get_alt_image(); ?>"
                                                         class="frontend-image img-responsive">
                                                </div>
                                            <?php endforeach; endif; ?>
                                    </div>
                                    <input data-rules="required" type="hidden" class="save-image-id" name="id_gallery"
                                           value="<?php echo $gallery; ?>">
                                </div>
                                <div
                                        class="st_msg"><?php echo STUser_f::get_msg_html( $validator->error( 'gallery' ), 'danger' ) ?></div>
                                <?php st_validate_msg(esc_html__("The Gallery field is required.",ST_TEXTDOMAIN),'required') ?>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="add_service_step" id="step_contact_details">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group form-group-icon-left">
                                <label for="id_logo"><?php _e( "Manufacture logo", ST_TEXTDOMAIN ) ?> <span
                                            class="text-small text-danger">*</span>:</label>
                                <div class="upload-wrapper upload-partner-wrapper">
                                    <button class="upload-button-partner btn btn-primary btn-sm"
                                            data-uploader_title="<?php _e( 'Select a image to upload', ST_TEXTDOMAIN ); ?>"
                                            data-uploader_button_text="<?php _e( 'Use this image', ST_TEXTDOMAIN ); ?>"><?php echo __( 'Upload', ST_TEXTDOMAIN ); ?></button>
                                    <div class="upload-items">
                                        <?php
                                        $logo     = STInput::request( 'id_logo', get_post_thumbnail_id( $post_id ) );
                                        $logo_url = wp_get_attachment_url( $logo );
                                        if ( !empty( $logo_url ) ):
                                            ?>
                                            <div class="upload-item">
                                                <img src="<?php echo $logo_url; ?>"
                                                     alt="<?php echo TravelHelper::get_alt_image(); ?>"
                                                     class="frontend-image img-responsive">
                                                <a href="javascript: void(0);" class="delete">&times;</a>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <input data-rules="required" type="hidden" class="save-image-id" name="id_logo" value="<?php echo $logo; ?>">
                                </div>
                                <div
                                        class="st_msg"><?php echo STUser_f::get_msg_html( $validator->error( 'logo' ), 'danger' ) ?></div>
                                <?php st_validate_msg(esc_html__("The Manufacture logo field is required.",ST_TEXTDOMAIN),'required') ?>

                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group form-group-icon-left">

                                <label for="st_name"><?php _e( "Car Manufacturer Name", ST_TEXTDOMAIN ) ?> <span
                                            class="text-small text-danger">*</span>:</label>
                                <i class="fa  fa-star input-icon input-icon-hightlight"></i>
                                <input data-rules="required" id="st_name" name="st_name" type="text"
                                       placeholder="<?php _e( "Car Manufacturer Name", ST_TEXTDOMAIN ) ?>"
                                       class="form-control"
                                       value="<?php echo STInput::request( 'cars_name', get_post_meta( $post_id, 'cars_name', true ) ); ?>">
                                <div
                                        class="st_msg"><?php echo STUser_f::get_msg_html( $validator->error( 'st_name' ), 'danger' ) ?></div>
                                <?php st_validate_msg(esc_html__("The Manufacturer Name field is required.",ST_TEXTDOMAIN),'required') ?>

                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group form-group-icon-left">
                                <label
                                        for="show_agent_contact_info"><?php _e( 'Select contact info will show', ST_TEXTDOMAIN ) ?>
                                    :</label>
                                <?php $select = [
                                    ''                => __( '----Select----', ST_TEXTDOMAIN ),
                                    'user_agent_info' => __( 'Use Agent Contact Info', ST_TEXTDOMAIN ),
                                    'user_item_info'  => __( 'Use Item Info', ST_TEXTDOMAIN ),
                                ] ?>
                                <i class="fa  fa-envelope-o input-icon input-icon-hightlight"></i>
                                <select name="show_agent_contact_info" id="show_agent_contact_info"
                                        class="form-control app">
                                    <?php
                                    if ( !empty( $select ) ) {
                                        foreach ( $select as $s => $v ) {
                                            printf( '<option value="%s" %s >%s</option>', $s, selected( STInput::request( 'show_agent_contact_info', get_post_meta( $post_id, 'show_agent_contact_info', true ) ), $s, false ), $v );
                                        }
                                    }
                                    ?>
                                </select>
                                <div
                                        class="st_msg"><?php echo STUser_f::get_msg_html( $validator->error( 'show_agent_contact_info' ), 'danger' ) ?></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group form-group-icon-left">

                                <label for="email"><?php st_the_language( 'user_create_car_email' ) ?> <span
                                            class="text-small text-danger">*</span>:</label>
                                <i class="fa  fa-envelope-o input-icon input-icon-hightlight"></i>
                                <input data-rules="required,valid_email" id="email" name="email" type="text"
                                       placeholder="<?php st_the_language( 'user_create_car_email' ) ?>"
                                       class="form-control"
                                       value="<?php echo STInput::request( 'email', get_post_meta( $post_id, 'cars_email', true ) ); ?>">
                                <i class="placeholder"><?php _e( "E-mail Car Agent, this address will received email when have new booking", ST_TEXTDOMAIN ) ?></i>
                                <div
                                        class="st_msg"><?php echo STUser_f::get_msg_html( $validator->error( 'email' ), 'danger' ) ?></div>
                                <?php st_validate_msg(esc_html__("The Email field is required.",ST_TEXTDOMAIN),'required') ?>
                                <?php st_validate_msg(esc_html__("The Email field must be a valid email.",ST_TEXTDOMAIN),'valid_email') ?>

                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group form-group-icon-left">

                                <label for="phone"><?php st_the_language( 'user_create_car_phone' ) ?> <span
                                            class="text-small text-danger">*</span>:</label>
                                <i class="fa  fa-phone input-icon input-icon-hightlight"></i>
                                <input data-rules="required" id="phone" name="phone" type="text"
                                       placeholder="<?php st_the_language( 'user_create_car_phone' ) ?>"
                                       class="form-control"
                                       value="<?php echo STInput::request( 'phone', get_post_meta( $post_id, 'cars_phone', true ) ); ?>">
                                <div
                                        class="st_msg"><?php echo STUser_f::get_msg_html( $validator->error( 'phone' ), 'danger' ) ?></div>
                                <?php st_validate_msg(esc_html__("The Phone field is required.",ST_TEXTDOMAIN),'required') ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group form-group-icon-left">

                                <label for="cars_fax"><?php _e( 'Fax Number', ST_TEXTDOMAIN ) ?>:</label>
                                <i class="fa  fa-phone input-icon input-icon-hightlight"></i>
                                <input id="cars_fax" name="cars_fax" type="text"
                                       placeholder="<?php _e( 'Fax number', ST_TEXTDOMAIN ) ?>" class="form-control"
                                       value="<?php echo STInput::request( 'cars_fax', get_post_meta( $post_id, 'cars_fax', true ) ) ?>">
                                <div
                                        class="st_msg"><?php echo STUser_f::get_msg_html( $validator->error( 'cars_fax' ), 'danger' ) ?></div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="about"><?php _e( 'Info about car', ST_TEXTDOMAIN ); ?> <span
                                            class="text-small text-danger">*</span>:</label>
                                <textarea data-rules="required" name="about" rows="5"
                                          class="form-control"><?php echo STInput::request( 'about', get_post_meta( $post_id, 'cars_about', true ) ); ?></textarea>
                                <div
                                        class="st_msg"><?php echo STUser_f::get_msg_html( $validator->error( 'about' ), 'danger' ) ?></div>
                                <?php st_validate_msg(esc_html__("The Info field is required.",ST_TEXTDOMAIN),'required') ?>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="add_service_step" id="step_price_setting">
                    <div class="row">
                        <div class="car-type">
                            <div class="col-md-6">
                                <div class="form-group form-group-icon-left">
                                    <label for="car_type"><?php echo __( 'Car Types', ST_TEXTDOMAIN ); ?> <span
                                                class="text-small text-danger">*</span>:</label>
                                    <i class="fa fa-money input-icon input-icon-hightlight"></i>
                                    <?php
                                    $value = STInput::request( 'car_type', get_post_meta( $post_id, 'car_type', true ) );
                                    ?>
                                    <select name="car_type" id="car_type" class="option-tree-ui-select form-control">
                                        <option value="normal" <?php selected( $value, 'normal' ) ?>><?php echo __( 'Normal', ST_TEXTDOMAIN ) ?></option>
                                        <option value="car_transfer"
                                            <?php selected( $value, 'car_transfer' ) ?>><?php echo __( 'Car Transfer', ST_TEXTDOMAIN ) ?></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="car-price-type">
                            <div class="col-md-6">
                                <div class="form-group form-group-icon-left">
                                    <label for="price_type"><?php echo __( 'Price Type', ST_TEXTDOMAIN ); ?> <span
                                                class="text-small text-danger">*</span>:</label>
                                    <i class="fa fa-money input-icon input-icon-hightlight"></i>
                                    <?php
                                    $value = STInput::request( 'price_type', get_post_meta( $post_id, 'price_type', true ) );
                                    ?>
                                    <select name="price_type" id="price_type" class="option-tree-ui-select form-control">
                                        <option
                                                value="distance" <?php selected( $value, 'distance' ) ?>><?php echo __( 'By Distance', ST_TEXTDOMAIN ) ?></option>
                                        <option
                                                value="fixed" <?php selected( $value, 'fixed' ) ?> ><?php echo __( 'By Fixed', ST_TEXTDOMAIN ); ?></option>
                                        <option
                                                value="passenger" <?php selected( $value, 'passenger' ) ?>><?php echo __( 'By Passenger', ST_TEXTDOMAIN ) ?></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="car-passengers">
                            <div class="col-md-12">
                                <div class="form-group form-group-icon-left">
                                    <label for="num_passenger"><?php echo __( 'Passengers', ST_TEXTDOMAIN ); ?> <span
                                                class="text-small text-danger">*</span>:</label>
                                    <i class="fa fa-money input-icon input-icon-hightlight"></i>
                                    <input id="num_passenger" name="num_passenger" type="text"
                                           placeholder="<?php echo __( 'Passengers', ST_TEXTDOMAIN ) ?>"
                                           class="form-control number"
                                           value="<?php echo STInput::request( 'num_passenger', get_post_meta( $post_id, 'num_passenger', true ) ); ?>">
                                </div>
                            </div>
                        </div>
                        <div class="car-price">
                            <div class="col-md-12">
                                <div class="form-group form-group-icon-left">

                                    <label for="price"><?php st_the_language( 'user_create_car_price' ) ?> <span
                                                class="text-small text-danger">*</span>:</label>
                                    <i class="fa fa-money input-icon input-icon-hightlight"></i>
                                    <input data-rules="required" id="price" name="price" type="text"
                                           placeholder="<?php st_the_language( 'user_create_car_price' ) ?>"
                                           class="form-control number"
                                           value="<?php echo STInput::request( 'price', get_post_meta( $post_id, 'cars_price', true ) ); ?>">
                                    <div
                                            class="st_msg"><?php echo STUser_f::get_msg_html( $validator->error( 'price' ), 'danger' ) ?></div>
                                    <?php st_validate_msg(esc_html__("The Price field is required.",ST_TEXTDOMAIN),'required') ?>
                                </div>
                            </div>
                        </div>
                        <div class="car-journey">
                            <div class="col-md-12">
                                <div class="form-group form-group-icon-left">
                                    <label for="journey"><?php echo __( 'Journey', ST_TEXTDOMAIN ); ?> <span
                                                class="text-small text-danger">*</span>:</label>
                                </div>
                                <div class="content_data_journey clearfix">
                                    <div class="list-properties">
                                        <?php if ( !empty( $post_id ) ) { ?>
                                            <?php
                                            $journeys = get_post_meta( $post_id, 'journey', true );
                                            ?>
                                            <?php if ( !empty( $journeys ) ) {
                                                foreach ( $journeys as $k => $v ) {
                                                    if(!isset($v['return']))
                                                        $v['return'] = 'no';
                                                    ?>
                                                    <div class="property-item tab-item">
                                                        <a href="javascript: void(0);"
                                                           class="delete-tab-item btn btn-danger">x</a>
                                                        <div class="tab-title"><?php echo esc_html( $v[ 'title' ] ) ?></div>
                                                        <div class="tab-content">
                                                            <div class="row">
                                                                <div class="col-xs-12 mb10">
                                                                    <div class="form-group">
                                                                        <label
                                                                                for=""><?php _e( "Title", ST_TEXTDOMAIN ) ?></label>
                                                                        <input id="" name="journey_title[]" type="text"
                                                                               class="tab-content-title form-control"
                                                                               value="<?php echo esc_html( $v[ 'title' ] ) ?>">
                                                                    </div>
                                                                </div>
                                                                <?php
                                                                $transfers = TravelHelper::transferDestination();
                                                                ?>
                                                                <div class="col-xs-12 mb10">
                                                                    <div class="form-group form-group-icon-left">
                                                                        <label
                                                                                for="st_transfer_from"><?php _e( "Transfer from", ST_TEXTDOMAIN ) ?></label>
                                                                        <i class="fa fa-calendar input-icon input-icon-hightlight"></i>
                                                                        <?php
                                                                        if ( !empty( $transfers ) ):
                                                                            ?>
                                                                            <select name="journey_transfer_from[]"
                                                                                    class="form-control">
                                                                                <option
                                                                                        value=""><?php echo __( '--- Select ----', ST_TEXTDOMAIN ); ?></option>
                                                                                <?php
                                                                                foreach ( $transfers as $key => $val ):
                                                                                    ?>
                                                                                    <option
                                                                                            value="<?php echo $val[ 'id' ] ?>"
                                                                                        <?php selected( $v[ 'transfer_from' ], $val[ 'id' ] ) ?>><?php echo ( $val[ 'type' ] == 'hotel' ) ? __( 'Hotel: ', ST_TEXTDOMAIN ) . $val[ 'name' ] : __( 'Airport: ', ST_TEXTDOMAIN ) . $val[ 'name' ]; ?></option>
                                                                                <?php endforeach; ?>
                                                                            </select>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                </div>
                                                                <div class="col-xs-12 mb10">
                                                                    <div class="form-group form-group-icon-left">
                                                                        <label
                                                                                for="transfer_to"><?php _e( "Transfer to", ST_TEXTDOMAIN ) ?></label>
                                                                        <i class="fa fa-calendar input-icon input-icon-hightlight"></i>
                                                                        <?php
                                                                        if ( !empty( $transfers ) ):
                                                                            ?>
                                                                            <select name="journey_transfer_to[]"
                                                                                    class="form-control">
                                                                                <option
                                                                                        value=""><?php echo __( '--- Select ----', ST_TEXTDOMAIN ); ?></option>
                                                                                <?php
                                                                                foreach ( $transfers as $key => $val ):
                                                                                    ?>
                                                                                    <option
                                                                                            value="<?php echo $val[ 'id' ] ?>"
                                                                                        <?php selected( $v[ 'transfer_to' ], $val[ 'id' ] ) ?>><?php echo ( $val[ 'type' ] == 'hotel' ) ? __( 'Hotel: ', ST_TEXTDOMAIN ) . $val[ 'name' ] : __( 'Airport: ', ST_TEXTDOMAIN ) . $val[ 'name' ]; ?></option>
                                                                                <?php endforeach; ?>
                                                                            </select>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                </div>
                                                                <div class="col-xs-12 mb10">
                                                                    <div class="form-group form-group-icon-left">
                                                                        <label
                                                                                for="st_price"><?php _e( "Price", ST_TEXTDOMAIN ) ?></label>
                                                                        <i class="fa fa-calendar input-icon input-icon-hightlight"></i>
                                                                        <input name="journey_price[]"
                                                                               type="text"
                                                                               placeholder="<?php _e( "Price", ST_TEXTDOMAIN ) ?>"
                                                                               class="form-control number"
                                                                               value="<?php echo esc_html( $v[ 'price' ] ) ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="col-xs-12 mb10">
                                                                    <div class="form-group form-group-icon-left">
                                                                        <label
                                                                                for="st_return"><?php _e( "Return", ST_TEXTDOMAIN ) ?></label>
                                                                        <input
                                                                                name="journey_return[<?php echo $k; ?>]" type="checkbox"
                                                                                placeholder="<?php _e( "Return", ST_TEXTDOMAIN ) ?>"
                                                                                class=""
                                                                                value="yes" <?php checked( $v[ 'return' ], 'yes' ); ?>>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php
                                                }
                                            } ?>
                                        <?php } else { ?>
                                            <?php
                                            $journey_title         = STInput::request( 'journey_title' );
                                            $journey_transfer_from = STInput::request( 'journey_transfer_from' );
                                            $journey_transfer_to   = STInput::request( 'journey_transfer_to' );
                                            $journey_price         = STInput::request( 'journey_price' );
                                            $journey_return        = STInput::request( 'journey_return' );
                                            ?>
                                            <?php if ( !empty( $journey_title ) ) {
                                                foreach ( $journey_title as $k => $v ) {
                                                    if ( !empty( $v ) ) {
                                                        ?>
                                                        <div class="property-item tab-item">
                                                            <a href="javascript: void(0);"
                                                               class="delete-tab-item btn btn-danger">x</a>
                                                            <div
                                                                    class="tab-title"><?php echo esc_html( $journey_title[ $k ] ) ?></div>
                                                            <div class="tab-content">
                                                                <div class="row">
                                                                    <div class="col-xs-12 mb10">
                                                                        <div class="form-group">
                                                                            <label
                                                                                    for=""><?php _e( "Title", ST_TEXTDOMAIN ) ?></label>
                                                                            <input id="" name="journey_title[]" type="text"
                                                                                   class="tab-content-title form-control"
                                                                                   value="<?php echo esc_html( $journey_title[ $k ] ) ?>">
                                                                        </div>
                                                                    </div>
                                                                    <?php
                                                                    $transfers = TravelHelper::transferDestination();
                                                                    ?>
                                                                    <div class="col-xs-12 mb10">
                                                                        <div class="form-group form-group-icon-left">
                                                                            <label
                                                                                    for="st_transfer_from"><?php _e( "Transfer from", ST_TEXTDOMAIN ) ?></label>
                                                                            <i class="fa fa-calendar input-icon input-icon-hightlight"></i>
                                                                            <?php
                                                                            if ( !empty( $transfers ) ):
                                                                                ?>
                                                                                <select name="journey_transfer_from[]"
                                                                                        class="form-control">
                                                                                    <option
                                                                                            value=""><?php echo __( '--- Select ----', ST_TEXTDOMAIN ); ?></option>
                                                                                    <?php
                                                                                    foreach ( $transfers as $key => $val ):
                                                                                        ?>
                                                                                        <option
                                                                                                value="<?php echo $journey_transfer_from[ $k ] ?>"
                                                                                            <?php selected( $journey_transfer_from[ $k ], $val[ 'id' ] ) ?>><?php echo ( $val[ 'type' ] == 'hotel' ) ? __( 'Hotel: ', ST_TEXTDOMAIN ) . $val[ 'name' ] : __( 'Airport: ', ST_TEXTDOMAIN ) . $val[ 'name' ]; ?></option>
                                                                                    <?php endforeach; ?>
                                                                                </select>
                                                                            <?php endif; ?>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-xs-12 mb10">
                                                                        <div class="form-group form-group-icon-left">
                                                                            <label
                                                                                    for="transfer_to"><?php _e( "Transfer to", ST_TEXTDOMAIN ) ?></label>
                                                                            <i class="fa fa-calendar input-icon input-icon-hightlight"></i>
                                                                            <?php
                                                                            if ( !empty( $transfers ) ):
                                                                                ?>
                                                                                <select name="journey_transfer_to[]"
                                                                                        class="form-control">
                                                                                    <option
                                                                                            value=""><?php echo __( '--- Select ----', ST_TEXTDOMAIN ); ?></option>
                                                                                    <?php
                                                                                    foreach ( $transfers as $key => $val ):
                                                                                        ?>
                                                                                        <option
                                                                                                value="<?php echo $val[ 'id' ] ?>"
                                                                                            <?php selected( $journey_transfer_to[ $k ], $val[ 'id' ] ) ?>><?php echo ( $val[ 'type' ] == 'hotel' ) ? __( 'Hotel: ', ST_TEXTDOMAIN ) . $val[ 'name' ] : __( 'Airport: ', ST_TEXTDOMAIN ) . $val[ 'name' ]; ?></option>
                                                                                    <?php endforeach; ?>
                                                                                </select>
                                                                            <?php endif; ?>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-xs-12 mb10">
                                                                        <div class="form-group form-group-icon-left">
                                                                            <label
                                                                                    for="st_price"><?php _e( "Price", ST_TEXTDOMAIN ) ?></label>
                                                                            <i class="fa fa-calendar input-icon input-icon-hightlight"></i>
                                                                            <input name="journey_price[]"
                                                                                   type="text"
                                                                                   placeholder="<?php _e( "Price", ST_TEXTDOMAIN ) ?>"
                                                                                   class="form-control number"
                                                                                   value="<?php echo esc_html( $journey_price[ $k ] ) ?>">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-xs-12 mb10">
                                                                        <div class="form-group form-group-icon-left">
                                                                            <label
                                                                                    for="st_return"><?php _e( "Return", ST_TEXTDOMAIN ) ?></label>
                                                                            <input
                                                                                    name="journey_return[]" type="checkbox"
                                                                                    placeholder="<?php _e( "Return", ST_TEXTDOMAIN ) ?>"
                                                                                    class=""
                                                                                    value="yes" <?php checked( $journey_return[ $k ], 'yes' ); ?>>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php
                                                    }
                                                }
                                            } ?>
                                        <?php } ?>
                                    </div>
                                    <a href="javascript:void(0);" class="btn btn-primary add-list-item mt10"
                                       data-get-html="#list-item-journey">+</a>
                                </div>
                            </div>
                        </div>
                        <!--Extra price for car transfer-->
                        <div class="col-md-12 mt30">
                            <div class="form-group form-group-icon-left">
                                <label for="extra"><?php _e("Extra", ST_TEXTDOMAIN) ?>:</label>
                            </div>
                        </div>
                        <div class="content_extra_price col-xs-12 col-md-6 mb30">
                            <div class="list-properties">
                                <?php if (!empty($post_id)) { ?>
                                    <?php
                                    $extra = get_post_meta($post_id, 'extra_price', true);
                                    if (is_array($extra) && count($extra)):
                                        foreach ($extra as $key => $val):
                                            ?>
                                            <div class="property-item tab-item">
                                                <a href="javascript: void(0);" class="delete-tab-item btn btn-danger">x</a>
                                                <div class="tab-title"><?php echo esc_html($val['title']); ?></div>
                                                <div class="tab-content">
                                                    <div class="row">
                                                        <div class="col-xs-12 mb10">
                                                            <div class="form-group">
                                                                <label for="policy_title"><?php _e("Title", ST_TEXTDOMAIN) ?></label>
                                                                <input id="" name="extra[title][]" type="text"
                                                                       class="tab-content-title form-control"
                                                                       value="<?php echo esc_html($val['title']); ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-12 mb10">
                                                            <div class="form-group form-group-icon-left">
                                                                <label for="extra_name"><?php _e("Name", ST_TEXTDOMAIN) ?></label>
                                                                <i class="fa fa-file-text input-icon input-icon-hightlight"></i>
                                                                <input value="<?php echo esc_html($val['extra_name']); ?>"
                                                                       id="extra_name" name="extra[extra_name][]"
                                                                       type="text"
                                                                       placeholder="<?php _e("Name", ST_TEXTDOMAIN) ?>"
                                                                       class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-12 mb10">
                                                            <div class="form-group form-group-icon-left">
                                                                <label for="extra_max_number"><?php _e("Max of number", ST_TEXTDOMAIN) ?></label>
                                                                <i class="fa fa-file-text input-icon input-icon-hightlight"></i>
                                                                <input value="<?php echo esc_html($val['extra_max_number']); ?>"
                                                                       id="extra_max_number"
                                                                       name="extra[extra_max_number][]" type="text"
                                                                       placeholder="<?php _e("Max of number", ST_TEXTDOMAIN) ?>"
                                                                       class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-12 mb10">
                                                            <div class="form-group form-group-icon-left">
                                                                <label for="extra_price"><?php _e("Price", ST_TEXTDOMAIN) ?></label>
                                                                <i class="fa fa-file-text input-icon input-icon-hightlight"></i>
                                                                <input value="<?php echo esc_html($val['extra_price']); ?>"
                                                                       id="extra_price" name="extra[extra_price][]"
                                                                       type="text"
                                                                       placeholder="<?php _e("Price", ST_TEXTDOMAIN) ?>"
                                                                       class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; endif; ?>
                                <?php } else { ?>
                                    <?php
                                    $extra = isset($_POST['extra']) ? $_POST['extra'] : '';
                                    if (isset($extra['title']) && count($extra['title'])):
                                        foreach ($extra['title'] as $key => $val):
                                            ?>
                                            <div class="property-item tab-item">
                                                <a href="javascript: void(0);" class="delete-tab-item btn btn-danger">x</a>
                                                <div class="tab-title"><?php echo esc_html($val); ?></div>
                                                <div class="tab-content">
                                                    <div class="row">
                                                        <div class="col-xs-12 mb10">
                                                            <div class="form-group">
                                                                <label for="policy_title"><?php _e("Title", ST_TEXTDOMAIN) ?></label>
                                                                <input id="" name="extra[title][]" type="text"
                                                                       class="tab-content-title form-control"
                                                                       value="<?php echo esc_html($val); ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-12 mb10">
                                                            <div class="form-group form-group-icon-left">

                                                                <label for="extra_name"><?php _e("Name", ST_TEXTDOMAIN) ?></label>
                                                                <i class="fa fa-file-text input-icon input-icon-hightlight"></i>
                                                                <input value="<?php echo esc_html($extra['extra_name'][$key]); ?>"
                                                                       id="extra_name" data-date-format="yyyy-mm-dd"
                                                                       name="extra[extra_name][]" type="text"
                                                                       placeholder="<?php _e("Name", ST_TEXTDOMAIN) ?>"
                                                                       class="form-control" value="">
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-12 mb10">
                                                            <div class="form-group form-group-icon-left">

                                                                <label for="extra_max_number"><?php _e("Max of number", ST_TEXTDOMAIN) ?></label>
                                                                <i class="fa fa-file-text input-icon input-icon-hightlight"></i>
                                                                <input value="<?php echo esc_html($extra['extra_max_number'][$key]); ?>"
                                                                       id="extra_max_number" data-date-format="yyyy-mm-dd"
                                                                       name="extra[extra_max_number][]" type="text"
                                                                       placeholder="<?php _e("Max of number", ST_TEXTDOMAIN) ?>"
                                                                       class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-12 mb10">
                                                            <div class="form-group form-group-icon-left">

                                                                <label for="extra_price"><?php _e("Price", ST_TEXTDOMAIN) ?></label>
                                                                <i class="fa fa-file-text input-icon input-icon-hightlight"></i>
                                                                <input value="<?php echo esc_html($extra['extra_price'][$key]); ?>"
                                                                       id="extra_price" data-date-format="yyyy-mm-dd"
                                                                       name="extra[extra_price][]" type="text"
                                                                       placeholder="<?php _e("Price", ST_TEXTDOMAIN) ?>"
                                                                       class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; endif; ?>
                                <?php } ?>
                            </div>
                            <a href="javascript:void(0);" class="btn btn-primary add-list-item mt10"
                               data-get-html="#list-item-extra">+</a>
                        </div>
                        <!--End extra price for car transfer-->
                        <div class="col-md-12">
                            <div class="form-group form-group-icon-left">

                                <label for="is_custom_price"><?php _e( "Custom Price", ST_TEXTDOMAIN ) ?>:</label>
                                <i class="fa fa-cogs input-icon input-icon-hightlight"></i>
                                <?php $is_custom_price = STInput::request( 'is_custom_price', get_post_meta( $post_id, 'is_custom_price', true ) ); ?>
                                <select class="form-control is_custom_price" name="is_custom_price">
                                    <option
                                            value="price_by_date" <?php if ( $is_custom_price == 'price_by_date' ) echo 'selected'; ?>><?php _e( "Price by Date", ST_TEXTDOMAIN ) ?></option>
                                    <option
                                            value="price_by_number" <?php if ( $is_custom_price == 'price_by_number' ) echo 'selected'; ?>><?php _e( "Price by number of day/hour", ST_TEXTDOMAIN ) ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="data_price_by_date">
                            <div class="col-md-12">
                                <div class="form-group form-group-icon-left">
                                    <label for="custom_price"><?php _e( "Price by date", ST_TEXTDOMAIN ) ?>:</label>
                                </div>
                            </div>
                            <?php if ( !empty( $post_id ) ) { ?>
                                <?php $data_price = STAdmin::st_get_all_price( $post_id ); ?>
                                <div class="content_data_price">
                                    <?php if ( !empty( $data_price ) ) {
                                        foreach ( $data_price as $k => $v ) {
                                            ?>
                                            <div class="item">
                                                <div class="col-md-4">
                                                    <div class="form-group form-group-icon-left">

                                                        <label
                                                                for="st_start_date"><?php _e( "Start date", ST_TEXTDOMAIN ) ?></label>
                                                        <i class="fa fa-calendar input-icon input-icon-hightlight"></i>
                                                        <input id="st_start_date" data-date-format="yyyy-mm-dd"
                                                               name="st_start_date[]" type="text"
                                                               placeholder="<?php _e( "Start Date", ST_TEXTDOMAIN ) ?>"
                                                               class="form-control date-pick"
                                                               value="<?php echo esc_html( $v->start_date ) ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group form-group-icon-left">

                                                        <label
                                                                for="st_end_date"><?php _e( "End date", ST_TEXTDOMAIN ) ?></label>
                                                        <i class="fa fa-calendar input-icon input-icon-hightlight"></i>
                                                        <input id="st_end_date" data-date-format="yyyy-mm-dd"
                                                               name="st_end_date[]" type="text"
                                                               placeholder="<?php _e( "End Date", ST_TEXTDOMAIN ) ?>"
                                                               class="form-control date-pick"
                                                               value="<?php echo esc_html( $v->end_date ) ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group form-group-icon-left">

                                                        <label for="st_price"><?php _e( "Price", ST_TEXTDOMAIN ) ?></label>
                                                        <i class="fa fa-money input-icon input-icon-hightlight"></i>
                                                        <input id="st_price" name="st_price[]" type="text"
                                                               placeholder="<?php _e( "Price", ST_TEXTDOMAIN ) ?>"
                                                               class="form-control number"
                                                               value="<?php echo esc_html( $v->price ) ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    <input name="st_priority[]" value="0" type="hidden" class="">
                                                    <input name="st_price_type[]" value="default" type="hidden" class="">
                                                    <input name="st_status[]" value="1" type="hidden" class="">
                                                    <div class="btn btn-danger btn_del_price_custom"
                                                         style="margin-top: 27px">-
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                    } ?>
                                </div>
                            <?php } else { ?>
                                <?php
                                $st_start_date = STInput::request( 'st_start_date' );
                                $st_end_date   = STInput::request( 'st_end_date' );
                                $st_price      = STInput::request( 'st_price' );
                                ?>
                                <div class="content_data_price">
                                    <?php if ( !empty( $st_start_date ) ) {
                                        foreach ( $st_start_date as $k => $v ) {
                                            if ( !empty( $v ) and !empty( $st_end_date[ $k ] ) and !empty( $st_price[ $k ] ) ) {
                                                ?>
                                                <div class="item">
                                                    <div class="col-md-4">
                                                        <div class="form-group form-group-icon-left">

                                                            <label
                                                                    for="st_start_date"><?php _e( "Start date", ST_TEXTDOMAIN ) ?></label>
                                                            <i class="fa fa-calendar input-icon input-icon-hightlight"></i>
                                                            <input id="st_start_date" data-date-format="yyyy-mm-dd"
                                                                   name="st_start_date[]" type="text"
                                                                   placeholder="<?php _e( "Start Date", ST_TEXTDOMAIN ) ?>"
                                                                   class="form-control date-pick"
                                                                   value="<?php echo esc_html( $v ) ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group form-group-icon-left">

                                                            <label
                                                                    for="st_end_date"><?php _e( "End date", ST_TEXTDOMAIN ) ?></label>
                                                            <i class="fa fa-calendar input-icon input-icon-hightlight"></i>
                                                            <input id="st_end_date" data-date-format="yyyy-mm-dd"
                                                                   name="st_end_date[]" type="text"
                                                                   placeholder="<?php _e( "End Date", ST_TEXTDOMAIN ) ?>"
                                                                   class="form-control date-pick"
                                                                   value="<?php echo esc_html( $st_end_date[ $k ] ) ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group form-group-icon-left">

                                                            <label
                                                                    for="st_price"><?php _e( "Price", ST_TEXTDOMAIN ) ?></label>
                                                            <i class="fa fa-money input-icon input-icon-hightlight"></i>
                                                            <input id="st_price" name="st_price[]" type="text"
                                                                   placeholder="<?php _e( "Price", ST_TEXTDOMAIN ) ?>"
                                                                   class="form-control number"
                                                                   value="<?php echo esc_html( $st_price[ $k ] ) ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <input name="st_priority[]" value="0" type="hidden" class="">
                                                        <input name="st_price_type[]" value="default" type="hidden"
                                                               class="">
                                                        <input name="st_status[]" value="1" type="hidden" class="">

                                                        <div class="btn btn-danger btn_del_price_custom"
                                                             style="margin-top: 27px">
                                                            -
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                        }
                                    } ?>
                                </div>
                            <?php } ?>

                            <div class="col-md-12 div_btn_add_custom">
                                <div class="form-group form-group-icon-left">
                                    <button id="btn_add_custom_price" class="btn btn-info"
                                            type="button"><?php _e( "Add price custom", ST_TEXTDOMAIN ) ?></button>
                                    <br>
                                </div>
                            </div>
                        </div>
                        <div class="data_price_by_number col-xs-12">
                            <div class="form-group form-group-icon-left">
                                <label for="st_price_by_number"><?php _e( "Price by number", ST_TEXTDOMAIN ) ?>:</label>
                            </div>
                            <div class="content_data_price_by_number">
                                <div class="list-properties">
                                    <?php if ( !empty( $post_id ) ) { ?>
                                        <?php
                                        $price_by_number_of_day_hour = get_post_meta( $post_id, 'price_by_number_of_day_hour', true );
                                        ?>
                                        <?php if ( !empty( $price_by_number_of_day_hour ) ) {
                                            foreach ( $price_by_number_of_day_hour as $k => $v ) {
                                                ?>
                                                <div class="property-item tab-item">
                                                    <a href="javascript: void(0);"
                                                       class="delete-tab-item btn btn-danger">x</a>
                                                    <div class="tab-title"><?php echo esc_html( $v[ 'title' ] ) ?></div>
                                                    <div class="tab-content">
                                                        <div class="row">
                                                            <div class="col-xs-12 mb10">
                                                                <div class="form-group">
                                                                    <label
                                                                            for=""><?php _e( "Title", ST_TEXTDOMAIN ) ?></label>
                                                                    <input id="" name="st_title[]" type="text"
                                                                           class="tab-content-title form-control"
                                                                           value="<?php echo esc_html( $v[ 'title' ] ) ?>">
                                                                </div>
                                                            </div>
                                                            <div class="col-xs-12 mb10">
                                                                <div class="form-group form-group-icon-left">
                                                                    <label
                                                                            for="st_start_date"><?php _e( "Number start", ST_TEXTDOMAIN ) ?></label>
                                                                    <i class="fa fa-calendar input-icon input-icon-hightlight"></i>
                                                                    <input id="st_start_date" name="st_number_start[]"
                                                                           type="text"
                                                                           placeholder="<?php _e( "Number Start", ST_TEXTDOMAIN ) ?>"
                                                                           class="form-control number"
                                                                           value="<?php echo esc_html( $v[ 'number_start' ] ) ?>">
                                                                </div>
                                                            </div>
                                                            <div class="col-xs-12 mb10">
                                                                <div class="form-group form-group-icon-left">
                                                                    <label
                                                                            for="st_end_date"><?php _e( "Number end", ST_TEXTDOMAIN ) ?></label>
                                                                    <i class="fa fa-calendar input-icon input-icon-hightlight"></i>
                                                                    <input id="st_end_date" name="st_number_end[]"
                                                                           type="text"
                                                                           placeholder="<?php _e( "Number End", ST_TEXTDOMAIN ) ?>"
                                                                           class="form-control number"
                                                                           value="<?php echo esc_html( $v[ 'number_end' ] ) ?>">
                                                                </div>
                                                            </div>
                                                            <div class="col-xs-12 mb10">
                                                                <div class="form-group form-group-icon-left">
                                                                    <label
                                                                            for="st_price_by_number"><?php _e( "Price", ST_TEXTDOMAIN ) ?></label>
                                                                    <i class="fa fa-money input-icon input-icon-hightlight"></i>
                                                                    <input id="st_price_by_number"
                                                                           name="st_price_by_number[]" type="text"
                                                                           placeholder="<?php _e( "Price", ST_TEXTDOMAIN ) ?>"
                                                                           class="form-control number"
                                                                           value="<?php echo esc_html( $v[ 'price' ] ) ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                        } ?>
                                    <?php } else { ?>
                                        <?php
                                        $st_number_start    = STInput::request( 'st_number_start' );
                                        $st_number_end      = STInput::request( 'st_number_end' );
                                        $st_price_by_number = STInput::request( 'st_price_by_number' );
                                        $st_title           = STInput::request( 'st_title' );
                                        ?>
                                        <?php if ( !empty( $st_price_by_number ) ) {
                                            foreach ( $st_price_by_number as $k => $v ) {
                                                if ( !empty( $v ) and !empty( $st_number_start[ $k ] ) and !empty( $st_number_end[ $k ] ) ) {
                                                    ?>
                                                    <div class="property-item tab-item">
                                                        <a href="javascript: void(0);"
                                                           class="delete-tab-item btn btn-danger">x</a>
                                                        <div
                                                                class="tab-title"><?php echo esc_html( $st_title[ $k ] ) ?></div>
                                                        <div class="tab-content">
                                                            <div class="row">
                                                                <div class="col-xs-12 mb10">
                                                                    <div class="form-group">
                                                                        <label
                                                                                for=""><?php _e( "Title", ST_TEXTDOMAIN ) ?></label>
                                                                        <input id="" name="st_title[]" type="text"
                                                                               class="tab-content-title form-control"
                                                                               value="<?php echo esc_html( $st_title[ $k ] ) ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="col-xs-12 mb10">
                                                                    <div class="form-group form-group-icon-left">

                                                                        <label
                                                                                for="st_start_date"><?php _e( "Number start", ST_TEXTDOMAIN ) ?></label>
                                                                        <i class="fa fa-calendar input-icon input-icon-hightlight"></i>
                                                                        <input id="st_start_date" name="st_number_start[]"
                                                                               type="text"
                                                                               placeholder="<?php _e( "Number Start", ST_TEXTDOMAIN ) ?>"
                                                                               class="form-control number"
                                                                               value="<?php echo esc_html( $st_number_start[ $k ] ) ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="col-xs-12 mb10">
                                                                    <div class="form-group form-group-icon-left">

                                                                        <label
                                                                                for="st_end_date"><?php _e( "Number end", ST_TEXTDOMAIN ) ?></label>
                                                                        <i class="fa fa-calendar input-icon input-icon-hightlight"></i>
                                                                        <input id="st_end_date" name="st_number_end[]"
                                                                               type="text"
                                                                               placeholder="<?php _e( "Number End", ST_TEXTDOMAIN ) ?>"
                                                                               class="form-control number"
                                                                               value="<?php echo esc_html( $st_number_end[ $k ] ) ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="col-xs-12 mb10">
                                                                    <div class="form-group form-group-icon-left">

                                                                        <label
                                                                                for="st_price_by_number"><?php _e( "Price", ST_TEXTDOMAIN ) ?></label>
                                                                        <i class="fa fa-money input-icon input-icon-hightlight"></i>
                                                                        <input id="st_price_by_number"
                                                                               name="st_price_by_number[]" type="text"
                                                                               placeholder="<?php _e( "Price", ST_TEXTDOMAIN ) ?>"
                                                                               class="form-control number"
                                                                               value="<?php echo esc_html( $v ) ?>">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php
                                                }
                                            }
                                        } ?>
                                    <?php } ?>
                                </div>
                                <a href="javascript:void(0);" class="btn btn-primary add-list-item mt10"
                                   data-get-html="#list-item-customprice">+</a>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group form-group-icon-left">

                                <label for="discount"><?php st_the_language( 'user_create_car_discount' ) ?>:</label>
                                <i class="fa fa-star  input-icon input-icon-hightlight"></i>
                                <input id="discount" name="discount" type="text"
                                       placeholder="<?php _e( "Discount rate (%)", ST_TEXTDOMAIN ) ?>"
                                       class="form-control number"
                                       value="<?php echo STInput::request( 'discount', get_post_meta( $post_id, 'discount', true ) ); ?>">
                                <div
                                        class="st_msg"><?php echo STUser_f::get_msg_html( $validator->error( 'discount' ), 'danger' ) ?></div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group form-group-icon-left">

                                <label for="is_sale_schedule"><?php _e( "Create sale schedule", ST_TEXTDOMAIN ) ?>:</label>
                                <i class="fa fa-cogs input-icon input-icon-hightlight"></i>
                                <?php $is_sale_schedule = STInput::request( 'is_sale_schedule', get_post_meta( $post_id, 'is_sale_schedule', true ) ) ?>
                                <select class="form-control is_sale_schedule" name="is_sale_schedule" id="is_sale_schedule">
                                    <option
                                            value="on" <?php if ( $is_sale_schedule == 'on' ) echo 'selected'; ?>><?php _e( "Yes", ST_TEXTDOMAIN ) ?></option>
                                    <option
                                            value="off" <?php if ( $is_sale_schedule == 'off' ) echo 'selected'; ?>><?php _e( "No", ST_TEXTDOMAIN ) ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="data_is_sale_schedule input-daterange">
                            <div class="col-md-6">
                                <div class="form-group form-group-icon-left">

                                    <label for="sale_price_from"><?php _e( "Sale start date", ST_TEXTDOMAIN ) ?> <span
                                                class="text-small text-danger">*</span>:</label>
                                    <i class="fa fa-calendar input-icon input-icon-hightlight"></i>
                                    <input name="sale_price_from" class="date-pick form-control st_date_start" placeholder="<?php _e( "Sale start date", ST_TEXTDOMAIN ) ?>"
                                           id="sale_price_from" data-date-format="<?php echo TravelHelper::getDateFormatJs(); ?>" type="text"
                                           value="<?php echo STInput::request( 'sale_price_from', get_post_meta( $post_id, 'sale_price_from', true ) ); ?>" readonly/>
                                    <div
                                            class="st_msg"><?php echo STUser_f::get_msg_html( $validator->error( 'sale_price_from' ), 'danger' ) ?></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group form-group-icon-left">

                                    <label for="sale_price_to"><?php _e( "Sale end date", ST_TEXTDOMAIN ) ?> <span
                                                class="text-small text-danger">*</span>:</label>
                                    <i class="fa fa-calendar input-icon input-icon-hightlight"></i>
                                    <input name="sale_price_to" class="date-pick form-control st_date_end" placeholder="<?php _e( "Sale end date", ST_TEXTDOMAIN ) ?>"
                                           id="sale_price_to" data-date-format="<?php echo TravelHelper::getDateFormatJs(); ?>" type="text"
                                           value="<?php echo STInput::request( 'sale_price_to', get_post_meta( $post_id, 'sale_price_to', true ) ); ?>" readonly/>
                                    <div
                                            class="st_msg"><?php echo STUser_f::get_msg_html( $validator->error( 'sale_price_to' ), 'danger' ) ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group form-group-icon-left">

                                <label for="number_car"><?php _e( "Number of cars for rent", ST_TEXTDOMAIN ) ?> <span
                                            class="text-small text-danger">*</span>:</label>
                                <i class="fa fa-cogs  input-icon input-icon-hightlight"></i>
                                <input data-rules="required" id="number_car" name="number_car" type="text"
                                       placeholder="<?php _e( "Number of cars for rent", ST_TEXTDOMAIN ) ?>"
                                       class="form-control number"
                                       value="<?php echo STInput::request( 'number_car', get_post_meta( $post_id, 'number_car', true ) ); ?>">
                                <div
                                        class="st_msg"><?php echo STUser_f::get_msg_html( $validator->error( 'number_car' ), 'danger' ) ?></div>
                                <?php st_validate_msg(esc_html__("The Number of cars field is required.",ST_TEXTDOMAIN),'required') ?>

                            </div>
                        </div>
                        <div class="col-md-6 clear">
                            <div class="form-group form-group-icon-left">

                                <label for="deposit_payment_status"><?php _e( "Deposit payment options", ST_TEXTDOMAIN ) ?>
                                    :</label>
                                <i class="fa fa-cogs input-icon input-icon-hightlight"></i>
                                <?php $deposit_payment_status = STInput::request( 'deposit_payment_status', get_post_meta( $post_id, 'deposit_payment_status', true ) ) ?>
                                <select class="form-control deposit_payment_status" name="deposit_payment_status"
                                        id="deposit_payment_status">
                                    <option value=""><?php _e( "Disallow Deposit", ST_TEXTDOMAIN ) ?></option>
                                    <option
                                            value="percent" <?php if ( $deposit_payment_status == 'percent' ) echo 'selected' ?>><?php _e( "Deposit By Percent", ST_TEXTDOMAIN ) ?></option>
                                    <!--<option value="amount" <?php /*if($deposit_payment_status == 'amount') echo 'selected' */ ?>><?php /*_e("Deposit By Amount",ST_TEXTDOMAIN) */ ?></option>-->
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 data_deposit_payment_status">
                            <div class="form-group form-group-icon-left">

                                <label id="deposit_payment_amount"><?php _e( "Deposit payment amount", ST_TEXTDOMAIN ) ?>
                                    :</label>
                                <i class="fa fa-cogs  input-icon input-icon-hightlight"></i>
                                <input id="deposit_payment_amount" name="deposit_payment_amount" type="text"
                                       placeholder="<?php _e( "Deposit payment amount", ST_TEXTDOMAIN ) ?>"
                                       class="form-control"
                                       value="<?php echo STInput::request( 'deposit_payment_amount', get_post_meta( $post_id, 'deposit_payment_amount', true ) ) ?>">
                                <?php $partner_commission = st()->get_option( 'partner_commission', '0' ); ?>
                                <i><?php echo sprintf( esc_html__( "The deposit amount must be greater than %s the commission", ST_TEXTDOMAIN ), $partner_commission . "%" ) ?></i>
                                <div
                                        class="st_msg"><?php echo STUser_f::get_msg_html( $validator->error( 'deposit_payment_amount' ), 'danger' ) ?></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="add_service_step" id="step_car_options">
                    <div class="row">
                        <div class='col-md-12'>
                            <div class="form-group">
                                <label for="cars_booking_period"><?php _e( "Booking period", ST_TEXTDOMAIN ) ?>:</label>
                                <input id="cars_booking_period" name="cars_booking_period" type="text" min="0"
                                       placeholder="<?php _e( "Booking period (day)", ST_TEXTDOMAIN ) ?>"
                                       class="form-control number"
                                       value="<?php echo STInput::request( 'cars_booking_period', get_post_meta( $post_id, 'cars_booking_period', true ) ) ?>">
                                <div
                                        class="st_msg"><?php echo STUser_f::get_msg_html( $validator->error( 'cars_booking_period' ), 'danger' ) ?></div>
                            </div>
                        </div>
                        <?php $booking_unit = st()->get_option( 'cars_price_unit', 'day' ); ?>
                        <?php if ( $booking_unit == 'day' ) { ?>
                            <div class='col-md-12'>
                                <div class="form-group">
                                    <label for="cars_booking_min_day"><?php _e( 'Minimum days to book', ST_TEXTDOMAIN ) ?>
                                        :</label>
                                    <input id="cars_booking_min_day" name="cars_booking_min_day" type="text" min="0"
                                           placeholder="<?php __( 'Minimum days to book', ST_TEXTDOMAIN ) ?>"
                                           class="form-control number"
                                           value="<?php echo STInput::request( 'cars_booking_min_day', get_post_meta( $post_id, 'cars_booking_min_day', true ) ) ?>">
                                    <div
                                            class="st_msg"><?php echo STUser_f::get_msg_html( $validator->error( 'cars_booking_min_day' ), 'danger' ) ?></div>
                                </div>
                            </div>
                        <?php } else { ?>
                            <div class='col-md-12'>
                                <div class="form-group">
                                    <label for="cars_booking_min_hour"><?php _e( 'Minimum hours to book', ST_TEXTDOMAIN ) ?>
                                        :</label>
                                    <input id="cars_booking_min_hour" name="cars_booking_min_hour" type="text" min="0"
                                           placeholder="<?php __( 'Minimum hours to book', ST_TEXTDOMAIN ) ?>"
                                           class="form-control number"
                                           value="<?php echo STInput::request( 'cars_booking_min_hour', get_post_meta( $post_id, 'cars_booking_min_hour', true ) ) ?>">
                                    <div
                                            class="st_msg"><?php echo STUser_f::get_msg_html( $validator->error( 'cars_booking_min_hour' ), 'danger' ) ?></div>
                                </div>
                            </div>
                        <?php } ?>
                        <?php
                        $set_external = st()->get_option('partner_set_external_link', 'off');
                        $current_uid = get_current_user_id();
                        $user_role = STUser_f::check_role_user_by_id($current_uid);
                        if(($set_external == 'on') || ($set_external == 'off' && in_array($user_role, array('administrator')))){
                            ?>
                            <div class="col-md-6">
                                <div class="form-group form-group-icon-left">

                                    <label for="st_car_external_booking"><?php _e( "External booking", ST_TEXTDOMAIN ) ?>
                                        :</label>
                                    <i class="fa fa-cogs input-icon input-icon-hightlight"></i>
                                    <?php $st_car_external_booking = STInput::request( 'st_car_external_booking', get_post_meta( $post_id, 'st_car_external_booking', true ) ) ?>
                                    <select class="form-control st_car_external_booking" name="st_car_external_booking">
                                        <option
                                                value="off" <?php if ( $st_car_external_booking == 'off' ) echo 'selected'; ?>><?php _e( "No", ST_TEXTDOMAIN ) ?></option>
                                        <option
                                                value="on" <?php if ( $st_car_external_booking == 'on' ) echo 'selected'; ?>><?php _e( "Yes", ST_TEXTDOMAIN ) ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class='col-md-6 data_st_car_external_booking'>
                                <div class="form-group form-group-icon-left">

                                    <label
                                            for="st_car_external_booking_link"><?php _e( "External booking URL", ST_TEXTDOMAIN ) ?>
                                        :</label>
                                    <i class="fa fa-link  input-icon input-icon-hightlight"></i>
                                    <input id="st_car_external_booking_link" name="st_car_external_booking_link" type="text"
                                           placeholder="<?php _e( "Booking Period (day)", ST_TEXTDOMAIN ) ?>"
                                           class="form-control"
                                           value="<?php echo STInput::request( 'st_car_external_booking_link', get_post_meta( $post_id, 'st_car_external_booking_link', true ) ) ?>">
                                    <div
                                            class="st_msg"><?php echo STUser_f::get_msg_html( $validator->error( 'st_car_external_booking_link' ), 'danger' ) ?></div>
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
                    if ( !empty( $data_paypment ) and is_array( $data_paypment ) ) {
                        foreach ( $data_paypment as $k => $v ) {
                            $is_enable = ( st()->get_option( 'pm_gway_' . $k . '_enable' ) );
                            if ( $is_enable == 'off' ) {
                            } else {
                                ?>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group form-group-icon-left">

                                            <label
                                                    for="is_meta_payment_gateway_<?php echo esc_attr( $k ) ?>"><?php echo esc_html( $v->get_name() ) ?>
                                                :</label>
                                            <i class="fa fa-cogs input-icon input-icon-hightlight"></i>
                                            <?php $is_pay = STInput::request( 'is_meta_payment_gateway_' . $k, get_post_meta( $post_id, 'is_meta_payment_gateway_' . $k, true ) ) ?>
                                            <select class="form-control"
                                                    name="is_meta_payment_gateway_<?php echo esc_attr( $k ) ?>"
                                                    id="is_meta_payment_gateway_<?php echo esc_attr( $k ) ?>">
                                                <option
                                                        value="on" <?php if ( $is_pay == 'on' ) echo 'selected' ?>><?php _e( "Yes", ST_TEXTDOMAIN ) ?></option>
                                                <option
                                                        value="off" <?php if ( $is_pay == 'off' ) echo 'selected' ?>><?php _e( "No", ST_TEXTDOMAIN ) ?></option>
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
                    $custom_field = st()->get_option( 'st_cars_unlimited_custom_field' );
                    if ( !empty( $custom_field ) and is_array( $custom_field ) ) {
                        ?>
                        <div class="row">
                            <?php
                            foreach ( $custom_field as $k => $v ) {
                                $key   = str_ireplace( '-', '_', 'st_custom_' . sanitize_title( $v[ 'title' ] ) );
                                $class = 'col-md-12';
                                if ( $v[ 'type_field' ] == "date-picker" ) {
                                    $class = 'col-md-4';
                                }
                                ?>
                                <div class="<?php echo esc_attr( $class ) ?>">
                                    <div class="form-group form-group-icon-left">
                                        <label
                                                for="<?php echo esc_attr( $key ) ?>"><?php echo esc_html( $v[ 'title' ] ) ?>
                                            :</label>
                                        <?php if ( $v[ 'type_field' ] == "text" ) { ?>
                                            <input id="<?php echo esc_attr( $key ) ?>"
                                                   name="<?php echo esc_attr( $key ) ?>" type="text"
                                                   placeholder="<?php echo esc_html( $v[ 'title' ] ) ?>"
                                                   class="form-control"
                                                   value="<?php echo STInput::request( $key, get_post_meta( $post_id, $key, true ) ); ?>">
                                        <?php } ?>
                                        <?php if ( $v[ 'type_field' ] == "date-picker" ) { ?>
                                            <input id="<?php echo esc_attr( $key ) ?>"
                                                   name="<?php echo esc_attr( $key ) ?>" type="text"
                                                   placeholder="<?php echo esc_html( $v[ 'title' ] ) ?>"
                                                   class="date-pick form-control"
                                                   value="<?php echo STInput::request( $key, get_post_meta( $post_id, $key, true ) ); ?>">
                                        <?php } ?>
                                        <?php if ( $v[ 'type_field' ] == "textarea" ) { ?>
                                            <textarea id="<?php echo esc_attr( $key ) ?>"
                                                      name="<?php echo esc_attr( $key ) ?>"
                                                      class="form-control"><?php echo STInput::request( $key, get_post_meta( $post_id, $key, true ) ); ?></textarea>
                                        <?php } ?>

                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <div class="text-center div_btn_submit">
                <?php if ( !empty( $post_id ) ) { ?>
                    <input type="button" id="btn_check_insert_cars" class="btn btn-primary btn-lg"
                           value="<?php _e( "UPDATE CAR", ST_TEXTDOMAIN ) ?>">
                    <input name="btn_update_post_type_cars" id="btn_insert_post_type_cars" type="submit"
                           class="btn btn-primary hidden btn_partner_submit_form" value="SUBMIT">
                <?php } else { ?>
                    <input type="hidden" class="save_and_preview" name="save_and_preview" value="false">
                    <input type="hidden" id="" class="" name="action_partner" value="add_partner">
                    <input name="btn_insert_post_type_cars" id="btn_insert_post_type_cars" type="submit" disabled
                           class="btn btn-primary btn-lg btn_partner_submit_form"
                           value="<?php _e( "SUBMIT CAR", ST_TEXTDOMAIN ) ?>">
                <?php } ?>

            </div>
        </form>
    </div>
</div>

<div id="html_equipment_item" style="display: none">
    <div class="item">
        <div class="col-md-3">
            <div class="form-group ">
                <label for="equipment_item_title"><?php st_the_language( 'user_create_car_equipment_title' ) ?></label>
                <input id="title" name="equipment_item_title[]" type="text" class="form-control">
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group ">
                <label for="equipment_item_price"><?php st_the_language( 'user_create_car_equipment_price' ) ?></label>
                <input id="price" name="equipment_item_price[]" type="text" class="form-control number">
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group ">
                <label for="equipment_item_price_unit"><?php _e( "Price unit", ST_TEXTDOMAIN ) ?></label>
                <select class="form-control" id="equipment_item_price_unit" name="equipment_item_price_unit[]">
                    <option value=""><?php _e( "Fixed Price", ST_TEXTDOMAIN ) ?></option>
                    <option value="per_hour"><?php _e( "Price per Hour", ST_TEXTDOMAIN ) ?></option>
                    <option value="per_day"><?php _e( "Price per Day", ST_TEXTDOMAIN ) ?></option>
                </select>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group ">
                <label for="equipment_item_price_max"><?php _e( "Price max", ST_TEXTDOMAIN ) ?></label>
                <input id="price_max" name="equipment_item_price_max[]" type="text" class="form-control number">
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group ">
                <label for="cars_equipment_list_number"><?php _e( "Number of item", ST_TEXTDOMAIN ) ?></label>
                <input id="cars_equipment_list_number" name="cars_equipment_list_number[]" type="text"
                       class="form-control number" value="">
            </div>
        </div>
        <div class="col-md-1">
            <div class="form-group form-group-icon-left">
                <div class="btn btn-danger btn_del_program" style="margin-top: 27px">
                    X
                </div>
            </div>
        </div>
    </div>
</div>


<div id="html_features" style="display: none">
    <?php $list = STUser_f::get_list_value_taxonomy( 'st_cars' ); ?>
    <?php if ( !empty( $list ) ) { ?>
        <div class="item">
            <div class="col-md-4">
                <div class="form-group form-group-icon-left">

                    <label
                        for="features_taxonomy"><?php st_the_language( 'user_create_car_features_attributes' ) ?></label>
                    <i class="fa fa-arrow-down input-icon input-icon-hightlight"></i>
                    <?php
                        if ( !empty( $list ) ) {
                            ?>
                            <select name="features_taxonomy[]" class="form-control taxonomy_car">
                                <?php foreach ( $list as $k => $v ) { ?>
                                    <option data-icon="<?php //echo esc_attr( $v[ 'icon' ] ) ?>"
                                            value="<?php echo esc_attr( $v[ 'value' ] . ',' . $v[ 'taxonomy' ] ) ?>"><?php echo esc_attr( $v[ 'label' ] ) ?></option>
                                <?php } ?>
                            </select>
                        <?php } ?>
                </div>
            </div>
            <div class="col-md-7">
                <div class="form-group">
                    <label
                        for="taxonomy_info"><?php st_the_language( 'user_create_car_features_attributes_info' ) ?></label>
                    <input id="title" name="taxonomy_info[]" type="text" class="form-control">
                </div>
            </div>
            <div class="col-md-1">
                <div class="form-group form-group-icon-left">
                    <div class="btn btn-danger btn_del_program" style="margin-top: 27px">
                        X
                    </div>
                </div>
            </div>
        </div>
    <?php } else { ?>
        <div class="col-md-12">
            <label for="create_car_no_data"><?php st_the_language( 'user_create_car_no_data' ) ?></label>
        </div>
    <?php } ?>
</div>

<div class="data_price_html" style="display: none">
    <div class="item">
        <div class="col-md-4">
            <div class="form-group form-group-icon-left">

                <label for="st_start_date"><?php _e( "Start date", ST_TEXTDOMAIN ) ?></label>
                <i class="fa fa-calendar input-icon input-icon-hightlight"></i>
                <input id="st_start_date" data-date-format="yyyy-mm-dd" name="st_start_date[]" type="text"
                       placeholder="<?php _e( "Start Date", ST_TEXTDOMAIN ) ?>" class="form-control date-pick">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group form-group-icon-left">

                <label for="st_end_date"><?php _e( "End date", ST_TEXTDOMAIN ) ?></label>
                <i class="fa fa-calendar input-icon input-icon-hightlight"></i>
                <input id="st_end_date" data-date-format="yyyy-mm-dd" name="st_end_date[]" type="text"
                       placeholder="<?php _e( "End Date", ST_TEXTDOMAIN ) ?>" class="form-control date-pick">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group form-group-icon-left">

                <label for="st_price"><?php _e( "Price", ST_TEXTDOMAIN ) ?></label>
                <i class="fa fa-money input-icon input-icon-hightlight"></i>
                <input id="st_price" name="st_price[]" type="text" placeholder="<?php _e( "Price", ST_TEXTDOMAIN ) ?>"
                       class="number form-control number">
            </div>
        </div>
        <div class="col-md-1">
            <input name="st_priority[]" value="0" type="hidden" class="">
            <input name="st_price_type[]" value="default" type="hidden" class="">
            <input name="st_status[]" value="1" type="hidden" class="">
            <div class="btn btn-danger btn_del_price_custom" style="margin-top: 27px"> -</div>
        </div>
    </div>
</div>

<div class="data_price_by_number_html" style="display: none">
    <div class="item">
        <div class="col-md-3">
            <div class="form-group form-group-icon-left">

                <label for="st_title"><?php _e( "Title", ST_TEXTDOMAIN ) ?></label>
                <i class="fa fa-calendar input-icon input-icon-hightlight"></i>
                <input id="st_title" name="st_title[]" type="text" placeholder="<?php _e( "Title", ST_TEXTDOMAIN ) ?>"
                       class="form-control">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group form-group-icon-left">

                <label for="st_start_date"><?php _e( "Number start", ST_TEXTDOMAIN ) ?></label>
                <i class="fa fa-calendar input-icon input-icon-hightlight"></i>
                <input id="st_start_date" name="st_number_start[]" type="text"
                       placeholder="<?php _e( "Number Start", ST_TEXTDOMAIN ) ?>" class="form-control number">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group form-group-icon-left">

                <label for="st_end_date"><?php _e( "Number end", ST_TEXTDOMAIN ) ?></label>
                <i class="fa fa-calendar input-icon input-icon-hightlight"></i>
                <input id="st_end_date" name="st_number_end[]" type="text"
                       placeholder="<?php _e( "Number End", ST_TEXTDOMAIN ) ?>" class="form-control number">
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group form-group-icon-left">

                <label for="st_price_by_number"><?php _e( "Price", ST_TEXTDOMAIN ) ?></label>
                <i class="fa fa-money input-icon input-icon-hightlight"></i>
                <input id="st_price_by_number" name="st_price_by_number[]" type="text"
                       placeholder="<?php _e( "Price", ST_TEXTDOMAIN ) ?>" class="form-control number">
            </div>
        </div>
        <div class="col-md-1">
            <div class="btn btn-danger btn_del_price_custom" style="margin-top: 27px"> -</div>
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
                    <label for=""><?php echo __( 'Title', ST_TEXTDOMAIN ); ?></label>
                    <input type="text" name="property-item[title][]" value="" class="tab-content-title form-control">
                </div>
                <div class="col-xs-12 mb10">
                    <label for=""><?php echo __( 'Featured Image', ST_TEXTDOMAIN ); ?></label>
                    <div class="upload-wrapper upload-partner-wrapper-link">
                        <button class="upload-button-partner-link btn btn-primary btn-sm"
                                data-uploader_title="<?php _e( 'Select a image to upload', ST_TEXTDOMAIN ); ?>"
                                data-uploader_button_text="<?php _e( 'Use this image', ST_TEXTDOMAIN ); ?>"><?php echo __( 'Upload', ST_TEXTDOMAIN ); ?></button>
                        <div class="upload-items">
                            <div class="upload-item">
                            </div>
                        </div>
                        <input type="hidden" class="save-image-url" name="property-item[featured_image][]" value="">
                    </div>
                </div>
                <div class="col-xs-12 mb10">
                    <label for=""><?php echo __( 'Description', ST_TEXTDOMAIN ); ?></label>
                    <textarea name="property-item[description][]" id="" cols="30" rows="10"
                              class="form-control"></textarea>
                </div>
                <div class="col-xs-12 mb10">
                    <label for=""><?php echo __( 'Icon Map', ST_TEXTDOMAIN ); ?></label>
                    <div class="upload-wrapper upload-partner-wrapper-link">
                        <button class="upload-button-partner-link btn btn-primary btn-sm"
                                data-uploader_title="<?php _e( 'Select a image to upload', ST_TEXTDOMAIN ); ?>"
                                data-uploader_button_text="<?php _e( 'Use this image', ST_TEXTDOMAIN ); ?>"><?php echo __( 'Upload', ST_TEXTDOMAIN ); ?></button>
                        <div class="upload-items">
                            <div class="upload-item">
                            </div>
                        </div>
                        <input type="hidden" class="save-image-url" name="property-item[icon][]" value="">
                    </div>
                </div>
                <div class="col-xs-12 mb10">
                    <label for=""><?php echo __( 'Lat', ST_TEXTDOMAIN ); ?></label>
                    <input type="text" name="property-item[map_lat][]" value="" class="form-control">
                </div>
                <div class="col-xs-12 mb10">
                    <label for=""><?php echo __( 'Lng', ST_TEXTDOMAIN ); ?></label>
                    <input type="text" name="property-item[map_lng][]" value="" class="form-control">
                </div>
            </div>
        </div>
    </div>
</div>

<div id="list-item-equipment" style="display: none">
    <div class="property-item tab-item">
        <a href="javascript: void(0);" class="delete-tab-item btn btn-danger">x</a>
        <div class="tab-title">&nbsp;</div>
        <div class="tab-content">
            <div class="row">
                <div class="col-xs-12 mb10">
                    <div class="form-group">
                        <label for="policy_title"><?php _e( "Title", ST_TEXTDOMAIN ) ?></label>
                        <input id="" name="equipment_item_title[]" type="text" class="tab-content-title form-control"
                               value="">
                    </div>
                </div>
                <div class="col-xs-12 mb10">
                    <div class="form-group ">
                        <label
                            for="equipment_item_price"><?php st_the_language( 'user_create_car_equipment_price' ) ?></label>
                        <input id="price" name="equipment_item_price[]" type="text" class="form-control" value="">
                    </div>
                </div>
                <div class="col-xs-12 mb10">
                    <div class="form-group ">
                        <label for="equipment_item_price_unit"><?php _e( "Price unit", ST_TEXTDOMAIN ) ?></label>
                        <select class="form-control" id="equipment_item_price_unit" name="equipment_item_price_unit[]">
                            <option value=""><?php _e( "Fixed Price", ST_TEXTDOMAIN ) ?></option>
                            <option value="per_hour"><?php _e( "Price per Hour", ST_TEXTDOMAIN ) ?></option>
                            <option value="per_day"><?php _e( "Price per Day", ST_TEXTDOMAIN ) ?></option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-12 mb10">
                    <div class="form-group ">
                        <label for="price_max"><?php _e( "Price max", ST_TEXTDOMAIN ) ?></label>
                        <input id="price_max" name="equipment_item_price_max[]" type="text" class="form-control number"
                               value="">
                    </div>
                </div>
                <div class="col-xs-12 mb10">
                    <div class="form-group ">
                        <label for="cars_equipment_list_number"><?php _e( "Number of item", ST_TEXTDOMAIN ) ?></label>
                        <input id="cars_equipment_list_number" name="cars_equipment_list_number[]" type="text"
                               class="form-control number" value="">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="list-item-customprice" style="display: none">
    <div class="property-item tab-item">
        <a href="javascript: void(0);" class="delete-tab-item btn btn-danger">x</a>
        <div class="tab-title">&nbsp;</div>
        <div class="tab-content">
            <div class="row">
                <div class="col-xs-12 mb10">
                    <div class="form-group">
                        <label for=""><?php _e( "Title", ST_TEXTDOMAIN ) ?></label>
                        <input id="" name="st_title[]" type="text" class="tab-content-title form-control" value="">
                    </div>
                </div>
                <div class="col-xs-12 mb10">
                    <div class="form-group form-group-icon-left">
                        <label for="st_start_date"><?php _e( "Number start", ST_TEXTDOMAIN ) ?></label>
                        <i class="fa fa-calendar input-icon input-icon-hightlight"></i>
                        <input id="st_start_date" name="st_number_start[]" type="text"
                               placeholder="<?php _e( "Number Start", ST_TEXTDOMAIN ) ?>" class="form-control number"
                               value="">
                    </div>
                </div>
                <div class="col-xs-12 mb10">
                    <div class="form-group form-group-icon-left">

                        <label for="st_end_date"><?php _e( "Number end", ST_TEXTDOMAIN ) ?></label>
                        <i class="fa fa-calendar input-icon input-icon-hightlight"></i>
                        <input id="st_end_date" name="st_number_end[]" type="text"
                               placeholder="<?php _e( "Number End", ST_TEXTDOMAIN ) ?>" class="form-control number"
                               value="">
                    </div>
                </div>
                <div class="col-xs-12 mb10">
                    <div class="form-group form-group-icon-left">

                        <label for="st_price_by_number"><?php _e( "Price", ST_TEXTDOMAIN ) ?></label>
                        <i class="fa fa-money input-icon input-icon-hightlight"></i>
                        <input id="st_price_by_number" name="st_price_by_number[]" type="text"
                               placeholder="<?php _e( "Price", ST_TEXTDOMAIN ) ?>" class="form-control number" value="">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="list-item-journey" style="display: none">
    <div class="property-item tab-item">
        <a href="javascript: void(0);"
           class="delete-tab-item btn btn-danger">x</a>
        <div
            class="tab-title">&nbsp;</div>
        <div class="tab-content">
            <div class="row">
                <div class="col-xs-12 mb10">
                    <div class="form-group">
                        <label
                            for=""><?php _e( "Title", ST_TEXTDOMAIN ) ?></label>
                        <input id="" name="journey_title[]" type="text"
                               class="tab-content-title form-control"
                               value="">
                    </div>
                </div>
                <?php
                    $transfers = TravelHelper::transferDestination();
                ?>
                <div class="col-xs-12 mb10">
                    <div class="form-group form-group-icon-left">
                        <label
                            for="transfer_from"><?php _e( "Transfer from", ST_TEXTDOMAIN ) ?></label>
                        <i class="fa fa-calendar input-icon input-icon-hightlight"></i>
                        <?php
                            if ( !empty( $transfers ) ):
                                ?>
                                <select name="journey_transfer_from[]"
                                        class="form-control">
                                    <option
                                        value=""><?php echo __( '--- Select ----', ST_TEXTDOMAIN ); ?></option>
                                    <?php
                                        foreach ( $transfers as $key => $val ):
                                            ?>
                                            <option
                                                value="<?php echo $val[ 'id' ] ?>"><?php echo ( $val[ 'type' ] == 'hotel' ) ? __( 'Hotel: ', ST_TEXTDOMAIN ) . $val[ 'name' ] : __( 'Airport: ', ST_TEXTDOMAIN ) . $val[ 'name' ]; ?></option>
                                        <?php endforeach; ?>
                                </select>
                            <?php endif; ?>
                    </div>
                </div>
                <div class="col-xs-12 mb10">
                    <div class="form-group form-group-icon-left">
                        <label
                            for="transfer_to"><?php _e( "Transfer to", ST_TEXTDOMAIN ) ?></label>
                        <i class="fa fa-calendar input-icon input-icon-hightlight"></i>
                        <?php
                            if ( !empty( $transfers ) ):
                                ?>
                                <select name="journey_transfer_to[]"
                                        class="form-control">
                                    <option
                                        value=""><?php echo __( '--- Select ----', ST_TEXTDOMAIN ); ?></option>
                                    <?php
                                        foreach ( $transfers as $key => $val ):
                                            ?>
                                            <option
                                                value="<?php echo $val[ 'id' ] ?>"><?php echo ( $val[ 'type' ] == 'hotel' ) ? __( 'Hotel: ', ST_TEXTDOMAIN ) . $val[ 'name' ] : __( 'Airport: ', ST_TEXTDOMAIN ) . $val[ 'name' ]; ?></option>
                                        <?php endforeach; ?>
                                </select>
                            <?php endif; ?>
                    </div> 
                </div>
                <div class="col-xs-12 mb10">
                    <div class="form-group form-group-icon-left">
                        <label
                            for="st_price"><?php _e( "Price", ST_TEXTDOMAIN ) ?></label>
                        <i class="fa fa-calendar input-icon input-icon-hightlight"></i>
                        <input name="journey_price[]"
                               type="text"
                               placeholder="<?php _e( "Price", ST_TEXTDOMAIN ) ?>"
                               class="form-control number"
                               value="">
                    </div>
                </div>
                <div class="col-xs-12 mb10">
                    <div class="form-group form-group-icon-left">
                        <label
                            for="st_return"><?php _e( "Return", ST_TEXTDOMAIN ) ?></label>
                        <input
                            name="journey_return[]" type="checkbox"
                            placeholder="<?php _e( "Return", ST_TEXTDOMAIN ) ?>"
                            class="" value="yes">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="list-item-extra" style="display: none">
    <div class="property-item tab-item">
        <a href="javascript: void(0);" class="delete-tab-item btn btn-danger">x</a>
        <div class="tab-title">&nbsp;</div>
        <div class="tab-content">
            <div class="row">
                <div class="col-xs-12 mb10">
                    <div class="form-group">
                        <label for="policy_title"><?php _e("Title", ST_TEXTDOMAIN) ?></label>
                        <input id="" name="extra[title][]" type="text" class="tab-content-title form-control">
                    </div>
                </div>
                <div class="col-xs-12 mb10">
                    <div class="form-group form-group-icon-left">

                        <label for="extra_name"><?php _e("Name", ST_TEXTDOMAIN) ?></label>
                        <i class="fa fa-file-text input-icon input-icon-hightlight"></i>
                        <input id="extra_name" data-date-format="yyyy-mm-dd" name="extra[extra_name][]" type="text"
                               placeholder="<?php _e("Name", ST_TEXTDOMAIN) ?>" class="form-control" value="">
                    </div>
                </div>
                <div class="col-xs-12 mb10">
                    <div class="form-group form-group-icon-left">

                        <label for="extra_max_number"><?php _e("Max of number", ST_TEXTDOMAIN) ?></label>
                        <i class="fa fa-file-text input-icon input-icon-hightlight"></i>
                        <input id="extra_max_number" data-date-format="yyyy-mm-dd" name="extra[extra_max_number][]"
                               type="text" placeholder="<?php _e("Max of number", ST_TEXTDOMAIN) ?>"
                               class="form-control">
                    </div>
                </div>
                <div class="col-xs-12 mb10">
                    <div class="form-group form-group-icon-left">

                        <label for="extra_price"><?php _e("Price", ST_TEXTDOMAIN) ?></label>
                        <i class="fa fa-file-text input-icon input-icon-hightlight"></i>
                        <input id="extra_price" data-date-format="yyyy-mm-dd" name="extra[extra_price][]" type="text"
                               placeholder="<?php _e("Price", ST_TEXTDOMAIN) ?>" class="form-control">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>