<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * User create tours
 *
 * Created by ShineTheme
 *
 */
wp_enqueue_script('bootstrap-datepicker.js');
wp_enqueue_script('bootstrap-datepicker-lang.js');
wp_enqueue_script('user_upload.js');

$post_id = STInput::request('id');
$title = $content = $excerpt = "";
if (!empty($post_id)) {
    $post = get_post($post_id);
    $title = $post->post_title;
    $content = $post->post_content;
    $excerpt = $post->post_excerpt;
}
$validator = STUser_f::$validator;

if (empty($post_id)) {

    //=== Validate package
    $admin_packages = STAdminPackages::get_inst();
    $author = get_current_user_id();
    $count_item_publish = $admin_packages->count_item_can_public($author);
    if ($admin_packages->enabled_membership() && $admin_packages->get_user_role() == 'partner') {
        if ($count_item_publish !== 'unlimited' && $count_item_publish <= 0) {
            $user_link = get_permalink();
            echo '<div class="alert alert-warning mt20">' . __('You can not create a new item. Your items can be created is ', ST_TEXTDOMAIN) . $admin_packages->count_item_package($author) . '. ' . '<a href="' . TravelHelper::get_user_dashboared_link($user_link, 'setting') . '" target="_blank">' . __('More Details', ST_TEXTDOMAIN) . '</a>' . '</div>';
            return false;
        }
    }

}

?>
<div class="row">
    <div class="col-sm-3">
        <div class="add-service-progress">
            <div class="st-create">
                <h3 class="pull-left"><?php esc_html_e('Tour Information',ST_TEXTDOMAIN) ?></h3>
            </div>
            <div class="sticky-box" data-width-from=".add-service-progress">
                <ul>
                    <li data-step="#step_general" class="selected"><i class="fa fa-home"></i> <?php esc_html_e('General',ST_TEXTDOMAIN) ?></li>
                    <li data-step="#step_location_settings"><i class="fa fa-map-marker"></i> <?php esc_html_e('Location Settings',ST_TEXTDOMAIN) ?></li>
                    <li data-step="#step_detail"><i class="fa fa-building"></i> <?php esc_html_e('Tour Info',ST_TEXTDOMAIN) ?></li>
                    <li data-step="#step_information"><i class="fa fa-building"></i> <?php esc_html_e('Information',ST_TEXTDOMAIN) ?></li>
                    <li data-step="#step_price_setting"><i class="fa fa-money"></i> <?php esc_html_e('Price Settings',ST_TEXTDOMAIN) ?></li>
                    <li data-step="#step_cancel_booking"><i class="fa fa-ban"></i> <?php esc_html_e('Cancel Booking',ST_TEXTDOMAIN) ?></li>

                    <?php $st_is_woocommerce_checkout=apply_filters('st_is_woocommerce_checkout',false);
                    if(!$st_is_woocommerce_checkout):?>
                        <li data-step="#step_payment_settings"><i class="fa fa-credit-card"></i> <?php esc_html_e('Payment Settings',ST_TEXTDOMAIN) ?></li>
                    <?php endif ?>

                    <?php $custom_field = st()->get_option( 'tours_unlimited_custom_field' );
                    if(!empty( $custom_field ) and is_array( $custom_field )) { ?>
                        <li data-step="#step_custom_fields"><i class="fa fa-bars"></i> <?php _e("Custom Fields",ST_TEXTDOMAIN) ?></li>
                    <?php } ?>


                    <li data-step="#step_tour_package"><i class="fa fa-calendar"></i> <?php _e("Tour Packages",ST_TEXTDOMAIN) ?></li>
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
                <?php if (!empty($post_id)) { ?>
                    <?php _e("Edit Tour", ST_TEXTDOMAIN) ?>
                <?php } else { ?>
                    <?php _e("Add Tour", ST_TEXTDOMAIN) ?>
                <?php } ?>
            </h3>
            <?php if (!empty($post_id)) { ?>
                <a target="_blank" href="<?php echo get_the_permalink($post_id) ?>"
                   class="btn btn-default pull-right btn-icon"><i class="fa fa-save"></i> <?php _e("Preview", ST_TEXTDOMAIN) ?></a>
            <?php } else { ?>
                <span class="btn btn-default pull-right btn_save_and_preview btn-icon"><i class="fa fa-save"></i> <?php _e("Save & Preview", ST_TEXTDOMAIN) ?></span>
            <?php } ?>
        </div>
        <div class="msg">
            <?php echo STTemplate::message() ?>
            <?php echo STUser_f::get_msg(); ?>
            <?php echo STUser_f::get_control_data(); ?>
        </div>
        <form action="" method="post" enctype="multipart/form-data" id="st_form_add_partner">
            <?php wp_nonce_field('user_setting', 'st_update_post_tours'); ?>
            
            <div class="add_service_step_wraps">
                <div class="add_service_step active" id="step_general">
                    <div class="form-group form-group-icon-left">

                        <label for="title" class="head_bol"><?php echo __('Name of tour', ST_TEXTDOMAIN); ?> <span
                                    class="text-small text-danger">*</span>:</label>
                        <i class="fa  fa-file-text input-icon input-icon-hightlight"></i>
                        <input data-rules="required" id="title" name="st_title" type="text" placeholder="<?php echo __('Name of tour', ST_TEXTDOMAIN); ?>"
                               class="form-control" value="<?php echo stripslashes(STInput::request("st_title", $title)); ?>">
                        <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('st_title'), 'danger') ?></div>                        
                        <?php st_validate_msg(esc_html__("The Title field is required.",ST_TEXTDOMAIN),'required') ?>

                    </div>
                    <div class="form-group form-group-icon-left">
                        <label for="st_content" class="head_bol"><?php st_the_language('user_create_tour_content') ?> <span
                                    class="text-small text-danger">*</span>:</label>
                        <?php
                        ob_start();
                        wp_editor( stripslashes(STInput::request("st_content",$content)) ,'st_content');
                        $html=ob_get_clean();
                        echo str_replace('<textarea ','<textarea data-rules="required" ',$html);
                        ?>
                        <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('st_content'), 'danger') ?></div>
                        <?php st_validate_msg(esc_html__("The Content field is required.",ST_TEXTDOMAIN),'required') ?>
                    </div>
                    <div class="form-group">
                        <label for="desc" class="head_bol"><?php _e("Tour description", ST_TEXTDOMAIN) ?> <span
                                    class="text-small text-danger">*</span>:</label>
                        <textarea data-rules="required" id="desc" name="st_desc"
                                  class="form-control"><?php echo stripslashes(STInput::request("st_desc", $excerpt)) ?></textarea>
                        <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('st_desc'), 'danger') ?></div>
                        <?php st_validate_msg(esc_html__("The Description field is required.",ST_TEXTDOMAIN),'required') ?>
                    </div>
                    <div class="form-group form-group-icon-left">
                        <label for="id_featured_image" class="head_bol"><?php _e("Featured image", ST_TEXTDOMAIN) ?> <span
                                    class="text-small text-danger">*</span>:</label>
                        <div class="upload-wrapper upload-partner-wrapper">
                            <button class="upload-button-partner btn btn-primary btn-sm"
                                    data-uploader_title="<?php _e('Select a image to upload', ST_TEXTDOMAIN); ?>"
                                    data-uploader_button_text="<?php _e('Use this image', ST_TEXTDOMAIN); ?>"><?php echo __('Upload', ST_TEXTDOMAIN); ?></button>
                            <div class="upload-items">
                                <?php
                                $thumbnail = STInput::request('id_featured_image', get_post_thumbnail_id($post_id));
                                $thumbnail_url = wp_get_attachment_url($thumbnail);
                                if (!empty($thumbnail_url)):
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
                        <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('featured_image'), 'danger') ?></div>
                        <?php st_validate_msg(esc_html__("The Featured image field is required.",ST_TEXTDOMAIN),'required') ?>
                    </div>
                </div>
                <div class="add_service_step" id="step_location_settings">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group form-group-icon-left">
                                <label for="multi_location"><?php _e('Location', ST_TEXTDOMAIN); ?>:</label>
                                <div id="setting_multi_location" class="location-front">
                                    <?php
                                    $html_location = TravelHelper::treeLocationHtml();
                                    $post_id = STInput::request('id', '');

                                    $multi_location = get_post_meta($post_id, 'multi_location', true);
                                    if (!empty($multi_location) && !is_array($multi_location)) {
                                        $multi_location = explode(',', $multi_location);
                                    }
                                    if (empty($multi_location)) {
                                        $multi_location = array('');
                                    }
                                    ?>
                                    <div class="form-group st-select-loction">
                                        <input placeholder="<?php echo __('Type to search', ST_TEXTDOMAIN); ?>" type="text"
                                               class="widefat form-control" name="search" value="">
                                        <div class="list-location-wrapper">
                                            <?php
                                            if (is_array($html_location) && count($html_location)):
                                                foreach ($html_location as $key => $location):
                                                    ?>
                                                    <div data-name="<?php echo $location['parent_name']; ?>" class="item"
                                                         style="margin-left: <?php echo $location['level'] . 'px;'; ?> margin-bottom: 5px;">
                                                        <label for="<?php echo 'location-' . $location['ID']; ?>">
                                                            <input <?php if (in_array('_' . $location['ID'] . '_', $multi_location)) echo 'checked'; ?>
                                                                    id="<?php echo 'location-' . $location['ID']; ?>"
                                                                    type="checkbox" name="multi_location[]"
                                                                    value="<?php echo '_' . $location['ID'] . '_'; ?>">
                                                            <span><?php echo $location['post_title']; ?></span>
                                                        </label>
                                                    </div>
                                                <?php endforeach; endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('multi_location'), 'danger') ?></div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group form-group-icon-left">

                                <label for="address"><?php _e("Address", ST_TEXTDOMAIN) ?> <span
                                            class="text-small text-danger">*</span>:</label>
                                <i class="fa fa-home input-icon input-icon-hightlight"></i>
                                <input data-rules="required" id="address" name="address" type="text"
                                       placeholder="<?php _e("Address", ST_TEXTDOMAIN) ?>" class="form-control"
                                       value="<?php echo STInput::request("address", get_post_meta($post_id, 'address', true)); ?>">

                                <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('address'), 'danger') ?></div>
                                <?php st_validate_msg(esc_html__("The Address field is required.",ST_TEXTDOMAIN),'required') ?>

                            </div>
                        </div>
                        <div class="col-md-12 partner_map">
                            <?php
                            if (class_exists('BTCustomOT')) {
                                BTCustomOT::load_fields();
                                ot_type_bt_gmap_html();
                            }
                            ?>
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            <label for=""><?php echo __('Properties near by', ST_TEXTDOMAIN); ?></label>
                            <div class="list-properties">
                                <?php
                                $properties = get_post_meta($post_id, 'properties_near_by', true);
                                if (!empty($properties)):
                                    foreach ($properties as $key => $val):
                                        ?>
                                        <div class="property-item tab-item">
                                            <a href="javascript: void(0);" class="delete-tab-item btn btn-danger">x</a>
                                            <div class="tab-title"><?php echo esc_html($val['title']); ?></div>
                                            <div class="tab-content">
                                                <div class="row">
                                                    <div class="col-xs-12 mb10">
                                                        <label for=""><?php echo __('Title', ST_TEXTDOMAIN); ?></label>
                                                        <input type="text" name="property-item[title][]"
                                                               value="<?php echo esc_html($val['title']); ?>"
                                                               class="tab-content-title form-control">
                                                    </div>
                                                    <div class="col-xs-12 mb10">
                                                        <label for=""><?php echo __('Featured Image', ST_TEXTDOMAIN); ?></label>
                                                        <div class="upload-wrapper upload-partner-wrapper-link">
                                                            <button class="upload-button-partner-link btn btn-primary btn-sm"
                                                                    data-uploader_title="<?php _e('Select a image to upload', ST_TEXTDOMAIN); ?>"
                                                                    data-uploader_button_text="<?php _e('Use this image', ST_TEXTDOMAIN); ?>"><?php echo __('Upload', ST_TEXTDOMAIN); ?></button>
                                                            <div class="upload-items">
                                                                <?php
                                                                $featured_image = $val['featured_image'];
                                                                if (!empty($featured_image)):
                                                                    ?>
                                                                    <div class="upload-item">
                                                                        <img src="<?php echo $featured_image; ?>"
                                                                             alt="<?php echo TravelHelper::get_alt_image(); ?>"
                                                                             class="frontend-image img-responsive">
                                                                        <a href="javascript: void(0);" class="delete">&times;</a>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                            <input type="hidden" class="save-image-url"
                                                                   name="property-item[featured_image][]"
                                                                   value="<?php echo $featured_image; ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12 mb10">
                                                        <label for=""><?php echo __('Description', ST_TEXTDOMAIN); ?></label>
                                                        <textarea name="property-item[description][]" id="" cols="30"
                                                                  rows="10"
                                                                  class="form-control"><?php echo $val['description']; ?></textarea>
                                                    </div>
                                                    <div class="col-xs-12 mb10">
                                                        <label for=""><?php echo __('Icon Map', ST_TEXTDOMAIN); ?></label>
                                                        <div class="upload-wrapper upload-partner-wrapper-link">
                                                            <button class="upload-button-partner-link btn btn-primary btn-sm"
                                                                    data-uploader_title="<?php _e('Select a image to upload', ST_TEXTDOMAIN); ?>"
                                                                    data-uploader_button_text="<?php _e('Use this image', ST_TEXTDOMAIN); ?>"><?php echo __('Upload', ST_TEXTDOMAIN); ?></button>
                                                            <div class="upload-items">
                                                                <?php
                                                                $featured_image = $val['icon'];
                                                                if (!empty($featured_image)):
                                                                    ?>
                                                                    <div class="upload-item">
                                                                        <img src="<?php echo $featured_image; ?>"
                                                                             alt="<?php echo TravelHelper::get_alt_image(); ?>"
                                                                             class="frontend-image img-responsive">
                                                                        <a href="javascript: void(0);" class="delete">&times;</a>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                            <input type="hidden" class="save-image-url"
                                                                   name="property-item[icon][]"
                                                                   value="<?php echo $featured_image; ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12 mb10">
                                                        <label for=""><?php echo __('Lat', ST_TEXTDOMAIN); ?></label>
                                                        <input type="text" name="property-item[map_lat][]"
                                                               value="<?php echo esc_html($val['map_lat']); ?>"
                                                               class="form-control">
                                                    </div>
                                                    <div class="col-xs-12 mb10">
                                                        <label for=""><?php echo __('Lng', ST_TEXTDOMAIN); ?></label>
                                                        <input type="text" name="property-item[map_lng][]"
                                                               value="<?php echo esc_html($val['map_lng']); ?>"
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
                                <label for="is_featured"><?php _e("Enable street views", ST_TEXTDOMAIN) ?>:</label>
                                <i class="fa fa-cogs input-icon input-icon-hightlight"></i>
                                <?php $enable_street_views_google_map = STInput::request('enable_street_views_google_map', get_post_meta($post_id, 'enable_street_views_google_map', true)) ?>
                                <select class='form-control' name='enable_street_views_google_map'
                                        id="enable_street_views_google_map">
                                    <option value='on' <?php if ($enable_street_views_google_map == 'on') echo 'selected'; ?> ><?php _e("On", ST_TEXTDOMAIN) ?></option>
                                    <option value='off' <?php if ($enable_street_views_google_map == 'off') echo 'selected'; ?> ><?php _e("Off", ST_TEXTDOMAIN) ?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="add_service_step" id="step_detail">
                    <div class="row">
                        <?php
                        $taxonomies = (get_object_taxonomies('st_tours'));
                        if (is_array($taxonomies) and !empty($taxonomies)) {
                            foreach ($taxonomies as $key => $value) {
                                ?>
                                <div class="col-md-12">
                                    <?php
                                    $category = STUser_f::get_list_taxonomy($value);
                                    $taxonomy_tmp = get_taxonomy($value);
                                    $taxonomy_label = ($taxonomy_tmp->label);
                                    $taxonomy_name = ($taxonomy_tmp->name);
                                    if (!empty($category)):
                                        ?>
                                        <div class="form-group form-group-icon-left">
                                            <label for="check_all"> <?php echo esc_html($taxonomy_label); ?>:</label>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="checkbox-inline checkbox-stroke">
                                                        <label for="check_all">
                                                            <i class="fa fa-cogs"></i>
                                                            <input name="check_all" class="i-check check_all"
                                                                   type="checkbox"/><?php _e("All", ST_TEXTDOMAIN) ?>
                                                        </label>
                                                    </div>
                                                </div>
                                                <?php foreach ($category as $k => $v):
                                                    $icon = get_tax_meta($k, 'st_icon');
                                                    $icon = TravelHelper::handle_icon($icon);
                                                    $check = '';
                                                    if (STUser_f::st_check_post_term_partner($post_id, $value, $k) == true) {
                                                        $check = 'checked';
                                                    }
                                                    ?>
                                                    <div class="col-md-3">
                                                        <div class="checkbox-inline checkbox-stroke">
                                                            <label for="taxonomy">
                                                                <i class="<?php echo esc_html($icon) ?>"></i>
                                                                <input name="taxonomy[]" class="i-check item_tanoxomy"
                                                                       type="checkbox" <?php echo esc_html($check) ?>
                                                                       value="<?php echo esc_attr($k . ',' . $taxonomy_name) ?>"/><?php echo esc_html($v) ?>
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
                            <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('taxonomy[]'), 'danger') ?></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class='form-group form-group-icon-left'>

                                <label for="st_custom_layout"><?php _e("Detail tour layout", ST_TEXTDOMAIN) ?>:</label>
                                <i class="fa fa-cogs input-icon input-icon-hightlight"></i>
                                <?php $layout = st_get_layout('st_tours');
                                if (!empty($layout) and is_array($layout)):
                                    ?>
                                    <select class='form-control' name='st_custom_layout' id="st_custom_layout">
                                        <?php
                                        $st_custom_layout = STInput::request('st_custom_layout', get_post_meta($post_id, 'st_custom_layout', true));
                                        foreach ($layout as $k => $v):
                                            if ($st_custom_layout == $v['value']) $check = "selected"; else $check = '';
                                            echo '<option ' . $check . ' value="' . $v['value'] . '">' . $v['label'] . '</option>';
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
                                if (st()->get_option('partner_set_feature') == "on") { ?>

                                    <label for="is_featured"><?php _e("Set as featured", ST_TEXTDOMAIN) ?>:</label>
                                    <i class="fa fa-cogs input-icon input-icon-hightlight"></i>
                                    <?php $is_featured = STInput::request('is_featured', get_post_meta($post_id, 'is_featured', true)) ?>
                                    <select class='form-control' name='is_featured' id="is_featured">
                                        <option value='off' <?php if ($is_featured == 'off') echo 'selected'; ?> ><?php _e("No", ST_TEXTDOMAIN) ?></option>
                                        <option value='on' <?php if ($is_featured == 'on') echo 'selected'; ?> ><?php _e("Yes", ST_TEXTDOMAIN) ?></option>
                                    </select>
                                <?php }; ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group form-group-icon-left">
                                <label for="show_agent_contact_info"><?php _e('Choose which contact info will be shown?', ST_TEXTDOMAIN) ?>
                                    :</label>
                                <?php $select = array(
                                    '' => __('----Select----', ST_TEXTDOMAIN),
                                    'user_agent_info' => __('Use agent contact Info', ST_TEXTDOMAIN),
                                    'user_item_info' => __('Use item info', ST_TEXTDOMAIN),
                                ) ?>
                                <i class="fa  fa-envelope-o input-icon input-icon-hightlight"></i>
                                <select name="show_agent_contact_info" id="show_agent_contact_info"
                                        class="form-control app">
                                    <?php
                                    if (!empty($select)) {
                                        foreach ($select as $s => $v) {
                                            printf('<option value="%s" %s >%s</option>', $s, selected(STInput::request('show_agent_contact_info', get_post_meta($post_id, 'show_agent_contact_info', true)), $s, FALSE), $v);
                                        }
                                    }
                                    ?>
                                </select>
                                <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('show_agent_contact_info'), 'danger') ?></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group form-group-icon-left">

                                <label for="email"><?php _e("Contact email addresses", ST_TEXTDOMAIN) ?> <span
                                            class="text-small text-danger">*</span>:</label>
                                <i class="fa  fa-envelope-o input-icon input-icon-hightlight"></i>
                                <input data-rules="required,valid_email" id="email" name="email" type="text"
                                       placeholder="<?php _e("Contact email addresses", ST_TEXTDOMAIN) ?>"
                                       class="form-control"
                                       value="<?php echo STInput::request('contact_email', get_post_meta($post_id, 'contact_email', true)) ?>">
                                <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('email'), 'danger') ?></div>
                                <?php st_validate_msg(esc_html__("The Contact email address field is required.",ST_TEXTDOMAIN),'required') ?>
                                <?php st_validate_msg(esc_html__("The Contact email address field must be a valid email.",ST_TEXTDOMAIN),'valid_email') ?>

                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group form-group-icon-left">

                                <label id="website"><?php _e("Website", ST_TEXTDOMAIN) ?>:</label>
                                <i class="fa  fa-home input-icon input-icon-hightlight"></i>
                                <input id="website" name="website" type="text"
                                       placeholder="<?php _e("Website", ST_TEXTDOMAIN) ?>" class="form-control"
                                       value="<?php echo STInput::request('website', get_post_meta($post_id, 'website', true)) ?>">
                                <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('website'), 'danger') ?></div>
                            </div>
                        </div>
                        <div class="col-md-6 clear">
                            <div class="form-group form-group-icon-left">

                                <label id="website"><?php _e("Phone", ST_TEXTDOMAIN) ?>:</label>
                                <i class="fa  fa-phone input-icon input-icon-hightlight"></i>
                                <input id="phone" name="phone" type="text" placeholder="<?php _e("Phone", ST_TEXTDOMAIN) ?>"
                                       class="form-control"
                                       value="<?php echo STInput::request('phone', get_post_meta($post_id, 'phone', true)) ?>">
                                <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('phone'), 'danger') ?></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group form-group-icon-left">

                                <label id="fax"><?php _e("Fax", ST_TEXTDOMAIN) ?>:</label>
                                <i class="fa  fa-fax input-icon input-icon-hightlight"></i>
                                <input id="fax" name="fax" type="text" placeholder="<?php _e("Fax", ST_TEXTDOMAIN) ?>"
                                       class="form-control"
                                       value="<?php echo STInput::request('fax', get_post_meta($post_id, 'fax', true)) ?>">
                                <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('fax'), 'danger') ?></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group form-group-icon-left">

                                <label for="video"><?php st_the_language('user_create_tour_video') ?>:</label>
                                <i class="fa fa-youtube-play input-icon input-icon-hightlight"></i>
                                <input id="video" name="video" type="text"
                                       placeholder="<?php _e("Enter Youtube or Vimeo video link (Eg: https://www.youtube.com/watch?v=JL-pGPVQ1a8)") ?>"
                                       class="form-control"
                                       value="<?php echo STInput::request('video', get_post_meta($post_id, 'video', true)) ?>">
                                <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('video'), 'danger') ?></div>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group form-group-icon-left">
                                <label for="id_gallery"><?php _e("Gallery", ST_TEXTDOMAIN) ?> <span
                                            class="text-small text-danger">*</span>:</label>
                                <div class="upload-wrapper upload-mul-partner-wrapper">
                                    <?php
                                    $gallery = STInput::request('id_gallery', get_post_meta($post_id, 'gallery', true));
                                    $gallery_arr = explode(',', $gallery);
                                    $gallery_arr = array_filter($gallery_arr, function ($value) {
                                        return $value != '';
                                    });
                                    ?>
                                    <div class="clearfix">
                                        <button class="mr5 upload-button-partner-multi btn btn-primary btn-sm"
                                                data-uploader_title="<?php _e('Select a image to upload', ST_TEXTDOMAIN); ?>"
                                                data-uploader_button_text="<?php _e('Use this image', ST_TEXTDOMAIN); ?>"><?php echo __('Upload', ST_TEXTDOMAIN); ?></button>
                                        <?php
                                        if (!empty($gallery_arr)):
                                            ?>
                                            <button class=" btn btn-primary btn-sm delete-gallery"><?php echo __('Delete', ST_TEXTDOMAIN); ?></button>
                                        <?php endif; ?>
                                    </div>
                                    <div class="upload-items">
                                        <?php

                                        if (!empty($gallery_arr)):
                                            foreach ($gallery_arr as $image):
                                                $gallery_url = wp_get_attachment_url($image);
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
                                <?php st_validate_msg(esc_html__("The Gallery field is required.",ST_TEXTDOMAIN),'required') ?>
                            </div>
                            <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('gallery'), 'danger') ?></div>
                        </div>
                    </div>
                </div>
                <div class="add_service_step" id="step_price_setting">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group form-group-icon-left">
                                <label for="tour_price_type"><?php _e("Show price by", ST_TEXTDOMAIN) ?>
                                    :</label>
                                <i class="fa fa-cogs input-icon input-icon-hightlight"></i>
                                <?php
                                $price_type = STInput::request('tour_price_by', STTour::get_price_type($post_id));
                                ?>
                                <select class="form-control price_type"
                                        name="tour_price_by" id="tour_price_by">
                                    <option value="person" <?php if ($price_type == 'person') echo 'selected'; ?>><?php _e("Price by person", ST_TEXTDOMAIN) ?></option>
                                    <option value="fixed" <?php if ($price_type == 'fixed') echo 'selected'; ?>><?php _e("Price by fixed", ST_TEXTDOMAIN) ?></option>
                                    <option value="fixed_depart" <?php if ($price_type == 'fixed_depart') echo 'selected'; ?>><?php _e("Fixed departure", ST_TEXTDOMAIN) ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="fixed_price">
                            <div class="col-md-4">
                                <div class="form-group form-group-icon-left">
                                    <label for="base_price"><?php _e("Base price", ST_TEXTDOMAIN) ?> <span
                                                class="text-small text-danger">*</span>:</label>
                                    <i class="fa fa-money input-icon input-icon-hightlight"></i>
                                    <input id="base_price" name="base_price" type="text"
                                           placeholder="<?php _e("Base price", ST_TEXTDOMAIN) ?>"
                                           class="number form-control"
                                           value="<?php echo STInput::request('base_price', get_post_meta($post_id, 'base_price', true)) ?>">
                                    <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('base_price'), 'danger') ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="people_price">
                            <div class="col-md-4 input-daterange date_fixed_depart">
                                <div class="form-group form-group-icon-left">

                                    <label for="sale_price_from"><?php _e("Start date", ST_TEXTDOMAIN) ?> <span
                                                class="text-small text-danger">*</span>:</label>
                                    <i class="fa fa-calendar input-icon input-icon-hightlight"></i>
                                    <?php
                                    $start_date_fixed =  STInput::request('start_date_fixed', '');
                                    if(empty($start_date_fixed)) {
                                        $start_date_fixed = get_post_meta($post_id, 'start_date_fixed', true);
                                        $start_date_fixed = date(TravelHelper::getDateFormat(), strtotime($start_date_fixed));
                                    }
                                    ?>
                                    <input name="start_date_fixed" id="start_date_fixed"
                                           class="date-pick form-control st_date_start"
                                           placeholder="<?php _e("Start Date", ST_TEXTDOMAIN) ?>"
                                           data-date-format="<?php echo TravelHelper::getDateFormatJs(); ?>" type="text"
                                           value="<?php echo $start_date_fixed; ?>" readonly/>
                                    <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('start_date_fixed'), 'danger') ?></div>
                                </div>
                            </div>
                            <div class="col-md-4 date_fixed_depart">
                                <div class="form-group form-group-icon-left">
                                    <label for="sale_price_to"><?php _e("End date", ST_TEXTDOMAIN) ?> <span
                                                class="text-small text-danger">*</span>:</label>
                                    <i class="fa fa-calendar input-icon input-icon-hightlight"></i>
                                    <?php
                                    $end_date_fixed =  STInput::request('end_date_fixed', '');
                                    if(empty($end_date_fixed)) {
                                        $end_date_fixed = get_post_meta($post_id, 'end_date_fixed', true);
                                        $end_date_fixed = date(TravelHelper::getDateFormat(), strtotime($end_date_fixed));
                                    }
                                    ?>
                                    <input name="end_date_fixed" id="end_date_fixed"
                                           class="date-pick form-control st_date_end"
                                           placeholder="<?php _e("End Date", ST_TEXTDOMAIN) ?>"
                                           data-date-format="<?php echo TravelHelper::getDateFormatJs(); ?>" type="text"
                                           value="<?php echo $end_date_fixed; ?>" readonly/>
                                    <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('end_date_fixed'), 'danger') ?></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group form-group-icon-left">
                                    <label for="adult_price"><?php _e("Adult price", ST_TEXTDOMAIN) ?> <span
                                                class="text-small text-danger">*</span>:</label>
                                    <i class="fa fa-money input-icon input-icon-hightlight"></i>
                                    <input data-rules="required" id="adult_price" name="adult_price" type="text"
                                           placeholder="<?php _e("Adult price", ST_TEXTDOMAIN) ?>"
                                           class="number form-control"
                                           value="<?php echo STInput::request('adult_price', get_post_meta($post_id, 'adult_price', true)) ?>">
                                    <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('adult_price'), 'danger') ?></div>
                                    <?php st_validate_msg(esc_html__("The Adult Price field is required.",ST_TEXTDOMAIN),'required') ?>

                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group form-group-icon-left">

                                    <label for="child_price"><?php _e("Child price", ST_TEXTDOMAIN) ?> <span
                                                class="text-small text-danger">*</span>:</label>
                                    <i class="fa fa-money input-icon input-icon-hightlight"></i>
                                    <input data-rules="required" id="child_price" name="child_price" type="text"
                                           placeholder="<?php _e("Child price", ST_TEXTDOMAIN) ?>"
                                           class="number form-control"
                                           value="<?php echo STInput::request('child_price', get_post_meta($post_id, 'child_price', true)) ?>">
                                    <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('child_price'), 'danger') ?></div>
                                    <?php st_validate_msg(esc_html__("The Child Price field is required.",ST_TEXTDOMAIN),'required') ?>

                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group form-group-icon-left">
                                    <label for="infant_price"><?php _e("Infant price", ST_TEXTDOMAIN) ?> <span
                                                class="text-small text-danger">*</span>:</label>
                                    <i class="fa fa-money input-icon input-icon-hightlight"></i>
                                    <input data-rules="required" id="infant_price" name="infant_price" type="text"
                                           placeholder="<?php _e("Infant price", ST_TEXTDOMAIN) ?>"
                                           class="form-control number"
                                           value="<?php echo STInput::request('infant_price', get_post_meta($post_id, 'infant_price', true)) ?>">

                                    <?php st_validate_msg(esc_html__("The Infant Price field is required.",ST_TEXTDOMAIN),'required') ?>
                                </div>
                                <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('infant_price'), 'danger') ?></div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group form-group-icon-left">
                                    <label for="is_sale_schedule"><?php _e("Hide adult on booking form", ST_TEXTDOMAIN) ?>
                                        :</label>
                                    <i class="fa fa-cogs input-icon input-icon-hightlight"></i>
                                    <?php $is_hide_adult = STInput::request('hide_adult_in_booking_form', get_post_meta($post_id, 'hide_adult_in_booking_form', true)) ?>
                                    <select class="form-control hide_adult_in_booking_form"
                                            name="hide_adult_in_booking_form" id="hide_adult_in_booking_form">
                                        <option value="off" <?php if ($is_hide_adult == 'off') echo 'selected'; ?>><?php _e("No", ST_TEXTDOMAIN) ?></option>
                                        <option value="on" <?php if ($is_hide_adult == 'on') echo 'selected'; ?>><?php _e("Yes", ST_TEXTDOMAIN) ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group form-group-icon-left">
                                    <label for="is_sale_schedule"><?php _e("Hide child on booking form", ST_TEXTDOMAIN) ?>
                                        :</label>
                                    <i class="fa fa-cogs input-icon input-icon-hightlight"></i>
                                    <?php $is_hide_child = STInput::request('hide_children_in_booking_form', get_post_meta($post_id, 'hide_children_in_booking_form', true)) ?>
                                    <select class="form-control hide_children_in_booking_form"
                                            name="hide_children_in_booking_form" id="hide_children_in_booking_form">
                                        <option value="off" <?php if ($is_hide_child == 'off') echo 'selected'; ?>><?php _e("No", ST_TEXTDOMAIN) ?></option>
                                        <option value="on" <?php if ($is_hide_child == 'on') echo 'selected'; ?>><?php _e("Yes", ST_TEXTDOMAIN) ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group form-group-icon-left">
                                    <label for="is_sale_schedule"><?php _e("Hide infant on booking form", ST_TEXTDOMAIN) ?>
                                        :</label>
                                    <i class="fa fa-cogs input-icon input-icon-hightlight"></i>
                                    <?php $is_hide_infant = STInput::request('hide_infant_in_booking_form', get_post_meta($post_id, 'hide_infant_in_booking_form', true)) ?>
                                    <select class="form-control hide_infant_in_booking_form"
                                            name="hide_infant_in_booking_form" id="hide_infant_in_booking_form">
                                        <option value="off" <?php if ($is_hide_infant == 'off') echo 'selected'; ?>><?php _e("No", ST_TEXTDOMAIN) ?></option>
                                        <option value="on" <?php if ($is_hide_infant == 'on') echo 'selected'; ?>><?php _e("Yes", ST_TEXTDOMAIN) ?></option>
                                    </select>
                                </div>
                            </div>
                            <!--Fields list discount by Child number booking-->
                            <div class="adult col-xs-12 col-md-6">
                                <div class="form-group form-group-icon-left">
                                    <label for="discount_by_adult"
                                           class="head_bol"><?php _e("Discount by Adults", ST_TEXTDOMAIN) ?>:</label>
                                </div>
                                <div class="" id="data_discount_by_adult">
                                    <div class="list-properties">
                                        <?php if (!empty($post_id)) { ?>
                                            <?php $discount_by_adult = get_post_meta($post_id, 'discount_by_adult', true); ?>
                                            <?php
                                            if (!empty($discount_by_adult)) {
                                                foreach ($discount_by_adult as $k => $v) {
                                                    ?>
                                                    <div class="property-item tab-item">
                                                        <a href="javascript: void(0);"
                                                           class="delete-tab-item btn btn-danger">x</a>
                                                        <div class="tab-title"><?php echo esc_attr($v['title']) ?></div>
                                                        <div class="tab-content">
                                                            <div class="row">
                                                                <div class="col-xs-12 mb10">
                                                                    <div class="form-group">
                                                                        <label for="discount_by_adult_title"><?php _e("Title", ST_TEXTDOMAIN) ?></label>
                                                                        <input id="" name="discount_by_adult_title[]"
                                                                               type="text"
                                                                               class="tab-content-title form-control"
                                                                               value="<?php echo esc_attr($v['title']) ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="col-xs-12 mb10">
                                                                    <div class="form-group">
                                                                        <label for="discount_by_adult_key"><?php _e("No. Adult (From)", ST_TEXTDOMAIN) ?></label>
                                                                        <input id="discount_by_adult_key"
                                                                               name="discount_by_adult_key[]" type="text"
                                                                               class="form-control"
                                                                               value="<?php echo esc_attr($v['key']) ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="col-xs-12 mb10">
                                                                    <div class="form-group">
                                                                        <label for="discount_by_adult_key_to"><?php _e("No. Adult (To)", ST_TEXTDOMAIN) ?></label>
                                                                        <input id="discount_by_adult_key_to"
                                                                               name="discount_by_adult_key_to[]" type="text"
                                                                               class="form-control"
                                                                               value="<?php echo (isset($v['key_to'])) ? esc_attr($v['key_to']) : ''; ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="col-xs-12 mb10">
                                                                    <div class="form-group">
                                                                        <label for="discount_by_adult_value"><?php _e("Percentage of discount", ST_TEXTDOMAIN) ?></label>
                                                                        <input id="discount_by_adult_value"
                                                                               name="discount_by_adult_value[]" type="text"
                                                                               class="form-control"
                                                                               value="<?php echo esc_attr($v['value']) ?>">
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
                                            $discount_by_adult_title = STInput::request('discount_by_adult_title');
                                            $discount_by_adult_key = STInput::request('discount_by_adult_key');
                                            $discount_by_adult_key_to = STInput::request('discount_by_adult_key_to');
                                            $discount_by_adult_value = STInput::request('discount_by_adult_value');
                                            ?>
                                            <?php
                                            if (!empty($discount_by_adult_title)) {
                                                foreach ($discount_by_adult_title as $k => $v) {
                                                    if (!empty($v) and !empty($discount_by_adult_key[$k]) and !empty($discount_by_adult_key_to[$k]) and !empty($discount_by_adult_value[$k])) {
                                                        ?>
                                                        <div class="property-item tab-item">
                                                            <a href="javascript: void(0);"
                                                               class="delete-tab-item btn btn-danger">x</a>
                                                            <div class="tab-title"><?php echo esc_attr($v) ?></div>
                                                            <div class="tab-content">
                                                                <div class="row">
                                                                    <div class="col-xs-12 mb10">
                                                                        <div class="form-group">
                                                                            <label for="discount_by_adult_title"><?php _e("Title", ST_TEXTDOMAIN) ?></label>
                                                                            <input id="" name="discount_by_adult_title[]"
                                                                                   type="text"
                                                                                   class="tab-content-title form-control"
                                                                                   value="<?php echo esc_attr($v) ?>">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-xs-12 mb10">
                                                                        <div class="form-group">
                                                                            <label for="discount_by_adult_key"><?php _e("No. Adult (To)", ST_TEXTDOMAIN) ?></label>
                                                                            <input id="" name="discount_by_adult_key[]"
                                                                                   type="text" class="form-control"
                                                                                   value="<?php echo esc_attr($discount_by_adult_key[$k]) ?>"
                                                                                   placeholder="<?php _e("No. Adult (To)", ST_TEXTDOMAIN) ?>">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-xs-12 mb10">
                                                                        <div class="form-group">
                                                                            <label for="discount_by_adult_key"><?php _e("No. Adult (From)", ST_TEXTDOMAIN) ?></label>
                                                                            <input id="" name="discount_by_adult_key_to[]"
                                                                                   type="text" class="form-control"
                                                                                   value="<?php echo esc_attr($discount_by_adult_key_to[$k]) ?>"
                                                                                   placeholder="<?php _e("No. Adult (From)", ST_TEXTDOMAIN) ?>">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-xs-12 mb10">
                                                                        <div class="form-group">
                                                                            <label for="discount_by_adult_value"><?php _e("Percentage of discount", ST_TEXTDOMAIN) ?></label>
                                                                            <input id="" name="discount_by_adult_value[]"
                                                                                   type="text" class="form-control"
                                                                                   value="<?php echo esc_attr($discount_by_adult_value[$k]) ?>"
                                                                                   placeholder="<?php _e("Percentage of discount", ST_TEXTDOMAIN) ?>">
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
                                       data-get-html="#list-item-adultprice">+</a>
                                </div>
                            </div>
                            <div class="child col-xs-12 col-md-6">
                                <div class="form-group form-group-icon-left">
                                    <label for="discount_by_child"
                                           class="head_bol"><?php _e("Discount by children", ST_TEXTDOMAIN) ?>:</label>
                                </div>
                                <div class="" id="data_discount_by_child">
                                    <div class="list-properties">
                                        <?php if (!empty($post_id)) { ?>
                                            <?php $discount_by_child = get_post_meta($post_id, 'discount_by_child', true); ?>
                                            <?php
                                            if (!empty($discount_by_child)) {
                                                foreach ($discount_by_child as $k => $v) {
                                                    ?>
                                                    <div class="property-item tab-item">
                                                        <a href="javascript: void(0);"
                                                           class="delete-tab-item btn btn-danger">x</a>
                                                        <div class="tab-title"><?php echo esc_attr($v['title']) ?></div>
                                                        <div class="tab-content">
                                                            <div class="row">
                                                                <div class="col-xs-12 mb10">
                                                                    <div class="form-group">
                                                                        <label for="discount_by_child_title"><?php _e("Title", ST_TEXTDOMAIN) ?></label>
                                                                        <input id="" name="discount_by_child_title[]"
                                                                               type="text"
                                                                               class="tab-content-title form-control"
                                                                               value="<?php echo esc_attr($v['title']) ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="col-xs-12 mb10">
                                                                    <div class="form-group">
                                                                        <label for="discount_by_child_key"><?php _e("No. Children (From)", ST_TEXTDOMAIN) ?></label>
                                                                        <input id="discount_by_child_key"
                                                                               name="discount_by_child_key[]" type="text"
                                                                               class="form-control"
                                                                               value="<?php echo esc_attr($v['key']) ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="col-xs-12 mb10">
                                                                    <div class="form-group">
                                                                        <label for="discount_by_child_key_to"><?php _e("No. Children (To)", ST_TEXTDOMAIN) ?></label>
                                                                        <input id="discount_by_child_key_to"
                                                                               name="discount_by_child_key_to[]" type="text"
                                                                               class="form-control"
                                                                               value="<?php echo (isset($v['key_to'])) ? esc_attr($v['key_to']) : ''; ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="col-xs-12 mb10">
                                                                    <div class="form-group">
                                                                        <label for="discount_by_child_value"><?php _e("Percentage of discount", ST_TEXTDOMAIN) ?></label>
                                                                        <input id="discount_by_child_value"
                                                                               name="discount_by_child_value[]" type="text"
                                                                               class="form-control"
                                                                               value="<?php echo esc_attr($v['value']) ?>">
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
                                            $discount_by_child_title = STInput::request('discount_by_child_title');
                                            $discount_by_child_key = STInput::request('discount_by_child_key');
                                            $discount_by_child_key_to = STInput::request('discount_by_child_key_to');
                                            $discount_by_child_value = STInput::request('discount_by_adult_value');
                                            ?>
                                            <?php
                                            if (!empty($discount_by_child_title)) {
                                                foreach ($discount_by_child_title as $k => $v) {
                                                    if (!empty($v) and !empty($discount_by_child_key[$k]) and !empty($discount_by_child_key_to[$k]) and !empty($discount_by_child_value[$k])) {
                                                        ?>
                                                        <div class="property-item tab-item">
                                                            <a href="javascript: void(0);"
                                                               class="delete-tab-item btn btn-danger">x</a>
                                                            <div class="tab-title"><?php echo esc_attr($v) ?></div>
                                                            <div class="tab-content">
                                                                <div class="row">
                                                                    <div class="col-xs-12 mb10">
                                                                        <div class="form-group">
                                                                            <label for="discount_by_adult_title"><?php _e("Title", ST_TEXTDOMAIN) ?></label>
                                                                            <input id="" name="discount_by_adult_title[]"
                                                                                   type="text"
                                                                                   class="tab-content-title form-control"
                                                                                   value="<?php echo esc_attr($v) ?>">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-xs-12 mb10">
                                                                        <div class="form-group">
                                                                            <label for="discount_by_child_key"><?php _e("No. Children (From)", ST_TEXTDOMAIN) ?></label>
                                                                            <input id="" name="discount_by_child_key[]"
                                                                                   type="text" class="form-control"
                                                                                   value="<?php echo esc_attr($discount_by_child_key[$k]) ?>"
                                                                                   placeholder="<?php _e("No. Children (From)", ST_TEXTDOMAIN) ?>">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-xs-12 mb10">
                                                                        <div class="form-group">
                                                                            <label for="discount_by_child_key_to"><?php _e("No. Children (To)", ST_TEXTDOMAIN) ?></label>
                                                                            <input id="" name="discount_by_child_key_to[]"
                                                                                   type="text" class="form-control"
                                                                                   value="<?php echo esc_attr($discount_by_child_key_to[$k]) ?>"
                                                                                   placeholder="<?php _e("No. Children (To)", ST_TEXTDOMAIN) ?>">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-xs-12 mb10">
                                                                        <div class="form-group">
                                                                            <label for="discount_by_child_value"><?php _e("Percentage of discount", ST_TEXTDOMAIN) ?></label>
                                                                            <input id="" name="discount_by_child_value[]"
                                                                                   type="text" class="form-control"
                                                                                   value="<?php echo esc_attr($discount_by_child_value[$k]) ?>"
                                                                                   placeholder="<?php _e("Percentage of Discount", ST_TEXTDOMAIN) ?>">
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
                                       data-get-html="#list-item-childprice">+</a>
                                </div>
                            </div>
                        </div>
                        <div class="people_price">
                            <div class="col-md-6">
                                <div class="form-group form-group-icon-left">
                                    <label for="discount_by_people_type"><?php _e("Type of discount by people", ST_TEXTDOMAIN) ?>
                                        :</label>
                                    <i class="fa fa-cogs input-icon input-icon-hightlight"></i>
                                    <?php $is_hide_infant = STInput::request('discount_by_people_type', get_post_meta($post_id, 'discount_by_people_type', true)) ?>
                                    <select class="form-control discount_by_people_type" name="discount_by_people_type"
                                            id="discount_by_people_type">
                                        <option value="percent" <?php if ($is_hide_infant == 'percent') echo 'selected'; ?>><?php _e("Percent", ST_TEXTDOMAIN) ?></option>
                                        <option value="amount" <?php if ($is_hide_infant == 'amount') echo 'selected'; ?>><?php _e("Amount", ST_TEXTDOMAIN) ?></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group form-group-icon-left">
                                <label for="extra"><?php _e("Extra", ST_TEXTDOMAIN) ?>:</label>
                            </div>
                        </div>
                        <div class="content_extra_price col-xs-12 col-md-6">
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
                                                                <label for="extra_max_number"><?php _e("Maximum people for tour. Leave empty or enter '0' for unlimited", ST_TEXTDOMAIN) ?></label>
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

                        <div class="col-md-6">
                            <div class="form-group form-group-icon-left">

                                <label for="discount"><?php _e("Discount Rate", ST_TEXTDOMAIN) ?>:</label>
                                <i class="fa fa-star input-icon input-icon-hightlight"></i>
                                <input id="discount" name="discount" type="text"
                                       placeholder="<?php _e("Discount rate (%)", ST_TEXTDOMAIN) ?>"
                                       class="form-control number"
                                       value="<?php echo STInput::request('discount', get_post_meta($post_id, 'discount', true)) ?>">
                                <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('discount'), 'danger') ?></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group form-group-icon-left">
                                <label for="discount_type"><?php _e("Type of discount", ST_TEXTDOMAIN) ?>:</label>
                                <i class="fa fa-cogs input-icon input-icon-hightlight"></i>
                                <?php $is_hide_infant = STInput::request('discount_type', get_post_meta($post_id, 'discount_type', true)) ?>
                                <select class="form-control discount_type" name="discount_type" id="discount_type">
                                    <option value="percent" <?php if ($is_hide_infant == 'percent') echo 'selected'; ?>><?php _e("Percent", ST_TEXTDOMAIN) ?></option>
                                    <option value="amount" <?php if ($is_hide_infant == 'amount') echo 'selected'; ?>><?php _e("Amount", ST_TEXTDOMAIN) ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group form-group-icon-left">

                                <label for="is_sale_schedule"><?php _e("Sale Schedule", ST_TEXTDOMAIN) ?>:</label>
                                <i class="fa fa-cogs input-icon input-icon-hightlight"></i>
                                <?php $is_sale_schedule = STInput::request('is_sale_schedule', get_post_meta($post_id, 'is_sale_schedule', true)) ?>
                                <select class="form-control is_sale_schedule" name="is_sale_schedule" id="is_sale_schedule">
                                    <option value="on" <?php if ($is_sale_schedule == 'on') echo 'selected'; ?>><?php _e("Yes", ST_TEXTDOMAIN) ?></option>
                                    <option value="off" <?php if ($is_sale_schedule == 'off') echo 'selected'; ?>><?php _e("No", ST_TEXTDOMAIN) ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="data_is_sale_schedule">
                            <div class="col-md-6 clear input-daterange">
                                <div class="form-group form-group-icon-left">

                                    <label for="sale_price_from"><?php _e("Sale start date", ST_TEXTDOMAIN) ?> <span
                                                class="text-small text-danger">*</span>:</label>
                                    <i class="fa fa-calendar input-icon input-icon-hightlight"></i>
                                    <input name="sale_price_from" id="sale_price_from"
                                           class="date-pick form-control st_date_start"
                                           placeholder="<?php _e("Sale Start Date", ST_TEXTDOMAIN) ?>"
                                           data-date-format="<?php echo TravelHelper::getDateFormatJs(); ?>" type="text"
                                           value="<?php echo STInput::request('sale_price_from', get_post_meta($post_id, 'sale_price_from', true)) ?>" readonly/>
                                    <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('sale_price_from'), 'danger') ?></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group form-group-icon-left">
                                    <label for="sale_price_to"><?php _e("Sale end date", ST_TEXTDOMAIN) ?> <span
                                                class="text-small text-danger">*</span>:</label>
                                    <i class="fa fa-calendar input-icon input-icon-hightlight"></i>
                                    <input name="sale_price_to" id="sale_price_to"
                                           class="date-pick form-control st_date_end"
                                           placeholder="<?php _e("Sale End Date", ST_TEXTDOMAIN) ?>"
                                           data-date-format="<?php echo TravelHelper::getDateFormatJs(); ?>" type="text"
                                           value="<?php echo STInput::request('sale_price_to', get_post_meta($post_id, 'sale_price_to', true)) ?>" readonly/>
                                    <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('sale_price_to'), 'danger') ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 clear">
                            <div class="form-group form-group-icon-left">
                                <label for="deposit_payment_status"><?php _e("Deposit payment options", ST_TEXTDOMAIN) ?>
                                    :</label>
                                <i class="fa fa-cogs input-icon input-icon-hightlight"></i>
                                <?php $deposit_payment_status = STInput::request('deposit_payment_status', get_post_meta($post_id, 'deposit_payment_status', true)) ?>
                                <select class="form-control deposit_payment_status" name="deposit_payment_status"
                                        id="deposit_payment_status">
                                    <option value=""><?php _e("Disallow Deposit", ST_TEXTDOMAIN) ?></option>
                                    <option value="percent" <?php if ($deposit_payment_status == 'percent') echo 'selected' ?>><?php _e("Deposit By Percent", ST_TEXTDOMAIN) ?></option>
                                    <!--<option value="amount" <?php /*if($deposit_payment_status == 'amount') echo 'selected' */ ?>><?php /*_e("Deposit By Amount",ST_TEXTDOMAIN) */ ?></option>-->
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 data_deposit_payment_status">
                            <div class="form-group form-group-icon-left">
                                <label for="deposit_payment_amount"><?php _e("Deposit amount", ST_TEXTDOMAIN) ?>:</label>
                                <i class="fa fa-cogs  input-icon input-icon-hightlight"></i>
                                <input id="deposit_payment_amount" name="deposit_payment_amount" type="text"
                                       placeholder="<?php _e("Deposit Amount", ST_TEXTDOMAIN) ?>"
                                       class="form-control number"
                                       value="<?php echo STInput::request('deposit_payment_amount', get_post_meta($post_id, 'deposit_payment_amount', true)) ?>">
                                <?php $partner_commission = st()->get_option('partner_commission', '0'); ?>
                                <i><?php echo sprintf(esc_html__("The deposit amount must be greater than %s the commission", ST_TEXTDOMAIN), $partner_commission . "%") ?></i>
                                <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('deposit_payment_amount'), 'danger') ?></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="add_service_step" id="step_information">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group form-group-icon-left">

                                <label for="type_tour"><?php _e("Tour type", ST_TEXTDOMAIN) ?>:</label>
                                <i class="fa  fa-cogs input-icon input-icon-hightlight"></i>
                                <?php $tour_type = STInput::request('type_tour', get_post_meta($post_id, 'type_tour', true)) ?>
                                <select id="type_tour" name="tour_type" class="form-control">
                                    <option value="specific_date" <?php if ($tour_type == 'specific_date') echo 'selected'; ?>><?php _e("Specific Date", ST_TEXTDOMAIN) ?></option>
                                    <option value="daily_tour" <?php if ($tour_type == 'daily_tour') echo 'selected'; ?>><?php _e("Daily Tour", ST_TEXTDOMAIN) ?></option>
                                </select>
                                <div class="st_msg console_msg_tour_type"></div>
                            </div>
                        </div>
                        <div class="col-md-6 data_duration">
                            <div class="form-group form-group-icon-left">

                                <label for="duration"><?php _e("Duration", ST_TEXTDOMAIN) ?>:</label>
                                <i class="fa fa-star input-icon input-icon-hightlight"></i>
                                <input id="duration" name="duration" type="text"
                                       placeholder="<?php _e("Duration", ST_TEXTDOMAIN) ?>" class="duration form-control"
                                       value="<?php echo STInput::request('duration', get_post_meta($post_id, 'duration_day', true)) ?>">
                                <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('duration'), 'danger') ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class='col-md-6'>
                            <div class="form-group">
                                <label for="tours_booking_period"><?php _e("Booking period", ST_TEXTDOMAIN) ?>:</label>
                                <input id="tours_booking_period" name="tours_booking_period" type="text" min="0"
                                       placeholder="<?php _e("Booking period (day)", ST_TEXTDOMAIN) ?>"
                                       class="form-control number"
                                       value="<?php echo STInput::request('tours_booking_period', get_post_meta($post_id, 'tours_booking_period', true)) ?>">
                            </div>
                        </div>
                        <div class='col-md-6'>
                            <div class="form-group">
                                <label for="min_people"><?php _e("Min people", ST_TEXTDOMAIN) ?> <span
                                            class="text-small text-danger">*</span>:</label>
                                <input data-rules="required" id="min_people" name="min_people" type="text" min="1"
                                       placeholder="<?php _e("Min people", ST_TEXTDOMAIN) ?>" class="form-control number"
                                       value="<?php echo STInput::request('min_people', get_post_meta($post_id, 'min_people', true)) ?>">
                                <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('min_people'), 'danger') ?></div>
                                <?php st_validate_msg(esc_html__("The Min People field is required.",ST_TEXTDOMAIN),'required') ?>

                            </div>
                        </div>
                        <div class='col-md-6'>
                            <div class="form-group">
                                <label for="max_people"><?php _e("Max people", ST_TEXTDOMAIN) ?>:</label>
                                <input id="max_people" name="max_people" type="text" min="0"
                                       placeholder="<?php _e("Max people", ST_TEXTDOMAIN) ?>" class="form-control number"
                                       value="<?php echo STInput::request('max_people', get_post_meta($post_id, 'max_people', true)) ?>">
                                <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('max_people'), 'danger') ?></div>
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

                                    <label for="st_tour_external_booking"><?php _e("External booking", ST_TEXTDOMAIN) ?>
                                        :</label>
                                    <i class="fa fa-cogs input-icon input-icon-hightlight"></i>
                                    <?php $external_booking = STInput::request('st_tour_external_booking', get_post_meta($post_id, 'st_tour_external_booking', true)); ?>
                                    <select class="form-control st_tour_external_booking" name="st_tour_external_booking"
                                            id="st_tour_external_booking">
                                        <option value="off" <?php if ($external_booking == 'off') echo 'selected'; ?> ><?php _e("No", ST_TEXTDOMAIN) ?></option>
                                        <option value="on" <?php if ($external_booking == 'on') echo 'selected'; ?> ><?php _e("Yes", ST_TEXTDOMAIN) ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class='col-md-6 data_st_tour_external_booking'>
                                <div class="form-group form-group-icon-left">

                                    <label for="st_tour_external_booking"><?php _e("External booking uRL", ST_TEXTDOMAIN) ?>
                                        :</label>
                                    <i class="fa fa-link  input-icon input-icon-hightlight"></i>
                                    <input id="st_tour_external_booking_link" name="st_tour_external_booking_link" type="text"
                                           placeholder="<?php _e("Eg: https://domain.com") ?>" class="form-control"
                                           value="<?php echo STInput::request('st_tour_external_booking_link', get_post_meta($post_id, 'st_tour_external_booking_link', true)) ?>">
                                    <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('st_tour_external_booking_link'), 'danger') ?></div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group form-group-icon-left">
                                <label for="tour_program"><?php _e("Tour's program", ST_TEXTDOMAIN) ?>:</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <?php if (!empty($post_id)) { ?>
                            <?php $tours_program = get_post_meta($post_id, 'tours_program', true); ?>
                            <div class="" id="data_program">
                                <?php
                                if (!empty($tours_program)) {
                                    foreach ($tours_program as $k => $v) {
                                        ?>
                                        <div class="item">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="program_title"><?php st_the_language('user_create_tour_program_title') ?></label>
                                                    <input id="title" name="program_title[]" type="text"
                                                           class="form-control" value="<?php echo esc_html($v['title']) ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-7">
                                                <div class="form-group">
                                                    <label for="program_desc"><?php st_the_language('user_create_tour_program_desc') ?></label>
                                                    <textarea name="program_desc[]"
                                                              class="form-control h_35"><?php echo balanceTags($v['desc']) ?></textarea>
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
                                        <?php
                                    }
                                }
                                ?>
                            </div>
                        <?php } else { ?>
                            <?php
                            $program_title = STInput::request('program_title');
                            $program_desc = STInput::request('program_desc');
                            ?>
                            <div class="" id="data_program">
                                <?php
                                if (!empty($program_title)) {
                                    foreach ($program_title as $k => $v) {
                                        if (!empty($v) and !empty($program_desc[$k])) {
                                            ?>
                                            <div class="item">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="program_title"><?php st_the_language('user_create_tour_program_title') ?></label>
                                                        <input id="title" name="program_title[]" type="text"
                                                               class="form-control" value="<?php echo esc_html($v) ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-7">
                                                    <div class="form-group">
                                                        <label for="program_desc"><?php st_the_language('user_create_tour_program_desc') ?></label>
                                                        <textarea name="program_desc[]"
                                                                  class="form-control h_35"><?php echo stripslashes($program_desc[$k]) ?></textarea>
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
                                }
                                ?>
                            </div>
                        <?php } ?>
                        <div class="col-md-8">
                            <div class="form-group form-group-icon-left">
                                <button id="btn_add_program" type="button"
                                        class="btn btn-info"><?php st_the_language('user_create_tour_add_program') ?></button>
                                <br>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="add_service_step" id="step_cancel_booking">
                    <?php echo st()->load_template('user/tabs/cancel-booking',FALSE,array('validator'=>$validator,'hide_tab'=>true)) ?>
                </div>
                <div class="add_service_step" id="step_payment_settings">
                    <?php
                    $data_paypment = STPaymentGateways::get_payment_gateways();
                    if (!empty($data_paypment) and is_array($data_paypment)) {
                        foreach ($data_paypment as $k => $v) {
                            $is_enable = (st()->get_option('pm_gway_' . $k . '_enable'));
                            if ($is_enable == 'off') {
                            } else {
                                ?>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group form-group-icon-left">

                                            <label for="is_meta_payment_gateway_<?php echo esc_attr($k) ?>"><?php echo esc_html($v->get_name()) ?>
                                                :</label>
                                            <i class="fa fa-cogs input-icon input-icon-hightlight"></i>
                                            <?php $is_pay = STInput::request('is_meta_payment_gateway_' . $k, get_post_meta($post_id, 'is_meta_payment_gateway_' . $k, true)) ?>
                                            <select class="form-control"
                                                    name="is_meta_payment_gateway_<?php echo esc_attr($k) ?>"
                                                    id="is_meta_payment_gateway_<?php echo esc_attr($k) ?>">
                                                <option value="on" <?php if ($is_pay == 'on') echo 'selected' ?>><?php _e("Yes", ST_TEXTDOMAIN) ?></option>
                                                <option value="off" <?php if ($is_pay == 'off') echo 'selected' ?>><?php _e("No", ST_TEXTDOMAIN) ?></option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                        }
                    }
                    ?>
                </div>
                <div class="add_service_step" id="step_custom_fields">
                    <?php
                    $custom_field = st()->get_option('tours_unlimited_custom_field');
                    if (!empty($custom_field) and is_array($custom_field)) {
                        ?>
                        <div class="row">
                            <?php
                            foreach ($custom_field as $k => $v) {
                                $key = str_ireplace('-', '_', 'st_custom_' . sanitize_title($v['title']));
                                $class = 'col-md-12';
                                if ($v['type_field'] == "date-picker") {
                                    $class = 'col-md-6';
                                }
                                ?>
                                <div class="<?php echo esc_attr($class) ?>">
                                    <div class="form-group">
                                        <label for="<?php echo esc_attr($key) ?>"><?php echo esc_html($v['title']) ?></label>
                                        <?php if ($v['type_field'] == "text") { ?>
                                            <input id="<?php echo esc_attr($key) ?>" name="<?php echo esc_attr($key) ?>"
                                                   type="text"
                                                   placeholder="<?php echo esc_html($v['title']) ?>" class="form-control"
                                                   value="<?php echo STInput::request($key, get_post_meta($post_id, $key, true)); ?>">
                                        <?php } ?>
                                        <?php if ($v['type_field'] == "date-picker") { ?>
                                            <input id="<?php echo esc_attr($key) ?>" name="<?php echo esc_attr($key) ?>"
                                                   type="text"
                                                   placeholder="<?php echo esc_html($v['title']) ?>"
                                                   class="date-pick form-control"
                                                   value="<?php echo STInput::request($key, get_post_meta($post_id, $key, true)); ?>">
                                        <?php } ?>
                                        <?php if ($v['type_field'] == "textarea") { ?>
                                            <textarea id="<?php echo esc_attr($key) ?>" name="<?php echo esc_attr($key) ?>"
                                                      class="form-control"><?php echo STInput::request($key, get_post_meta($post_id, $key, true)); ?></textarea>
                                        <?php } ?>

                                        <div class="st_msg console_msg_"></div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>

                <div class="add_service_step" id="step_tour_package">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group form-group-icon-left">
                                <label for="default_state"><?php _e("Tour Packages", ST_TEXTDOMAIN) ?>:</label>
                            </div>
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="stour-package">
                                        <div class="form-message"></div>
                                        <input type="hidden" id="stour-no-location" value="<?php echo __('Please select location or put address value', ST_TEXTDOMAIN); ?>" />
                                        <div id="stour-list-hotel">
                                            <div class="overlay-form" style="display: none;"><i class="fa fa-refresh text-color"></i></div>
                                            <div class="panel-group stour-package-user" id="accordion" role="tablist"
                                                 aria-multiselectable="true">
                                               

                                                <div class="panel panel-default">
                                                    <div class="panel-heading" role="tab" id="headingTwo">
                                                        <h4 class="panel-title">
                                                            <a class="collapsed" role="button" data-toggle="collapse"
                                                               data-parent="#accordion" href="#collapseTwo"
                                                               aria-expanded="false" aria-controls="collapseTwo">
                                                                <?php echo __('Activity Service', ST_TEXTDOMAIN); ?>
                                                            </a>
                                                        </h4>
                                                    </div>
                                                    <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel"
                                                         aria-labelledby="headingTwo">
                                                        <div class="panel-body">
                                                            <div id="tour-package-activity" class="tab-content stour-tab-content">
                                                                <?php if (STUser_f::_check_service_available_partner('st_activity')) { ?>
                                                                    <input type="submit"
                                                                           class="option-tree-ui-button button button-primary btn-load-hotel tour-package-load-hotel btn btn-primary btn-sm" name="tour-package-load-hotel"
                                                                           value="<?php echo __('Getting Activities By Location', ST_TEXTDOMAIN); ?>" data-post-id="<?php echo $post_id; ?>" data-type="activity">
                                                                    <div class="list-content">
                                                                        <?php
                                                                        $tour_package_activity = get_post_meta($post_id, 'tour_packages_activity', true);
                                                                        $activity_ids = [];
                                                                        if(is_object($tour_package_activity)) {
                                                                            if (!empty((array)$tour_package_activity)) {
                                                                                $i = 0;
                                                                                foreach ($tour_package_activity as $k => $v) {
                                                                                    $activity_ids[$i] = array('ID' => $v->activity_id);
                                                                                    $i++;
                                                                                }
                                                                            }
                                                                        }
                                                                        if(!empty($activity_ids)){
                                                                            echo st()->load_template('tours/elements/stour', 'package-activity', array('ids' => $activity_ids, 'post_id' => $post_id));
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                <?php } ?>
                                                                <div class="list-custom-hotel">
                                                                    <h4><?php echo __('Custom activity data', ST_TEXTDOMAIN); ?></h4>
                                                                    <?php echo st()->load_template('tours/elements/stour', 'package-custom-activity', array('post_id' => $post_id)); ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="panel panel-default">
                                                    <div class="panel-heading" role="tab" id="headingThree">
                                                        <h4 class="panel-title">
                                                            <a class="collapsed" role="button" data-toggle="collapse"
                                                               data-parent="#accordion" href="#collapseThree"
                                                               aria-expanded="false" aria-controls="collapseThree">
                                                                <?php echo __('Car Service', ST_TEXTDOMAIN); ?>
                                                            </a>
                                                        </h4>
                                                    </div>
                                                    <div id="collapseThree" class="panel-collapse collapse" role="tabpanel"
                                                         aria-labelledby="headingThree">
                                                        <div class="panel-body">
                                                            <div id="tour-package-car" class="tab-content  stour-tab-content">
                                                                <?php if (STUser_f::_check_service_available_partner('st_cars')) { ?>
                                                                    <input type="submit"
                                                                           class="option-tree-ui-button button button-primary btn-load-hotel tour-package-load-hotel btn btn-primary btn-sm" name="tour-package-load-hotel"
                                                                           value="<?php echo __('Getting Cars By Location', ST_TEXTDOMAIN); ?>" data-post-id="<?php echo $post_id; ?>" data-type="car">
                                                                    <div class="list-content">
                                                                        <?php
                                                                        $tour_package_car = get_post_meta($post_id, 'tour_packages_car', true);
                                                                        $car_ids = [];
                                                                        if(is_object($tour_package_car)) {
                                                                            if (!empty((array)$tour_package_car)) {
                                                                                $i = 0;
                                                                                foreach ($tour_package_car as $k => $v) {
                                                                                    $car_ids[$i] = array('ID' => $v->car_id);
                                                                                    $i++;
                                                                                }
                                                                            }
                                                                        }
                                                                        if(!empty($car_ids)){
                                                                            echo st()->load_template('tours/elements/stour', 'package-car', array('ids' => $car_ids, 'post_id' => $post_id));
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                <?php } ?>
                                                                <div class="list-custom-hotel">
                                                                    <h4><?php echo __('Custom car data', ST_TEXTDOMAIN); ?></h4>
                                                                    <?php echo st()->load_template('tours/elements/stour', 'package-custom-car', array('post_id' => $post_id)); ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                               

                                            </div>
                                            <input type="submit" id="tour-package-save-hotel"
                                                   class="option-tree-ui-button button button-primary stour-package-button btn btn-primary btn-sm" name="tour-package-save-hotel"
                                                   data-post-id="<?php echo $post_id; ?>"
                                                   value="<?php echo __('Save Tour Packages', ST_TEXTDOMAIN); ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if(!empty($post_id)){ ?>
                    <div class="add_service_step" id="step_availablility">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group form-group-icon-left">
                                    <label for="availability"><?php _e("Availability", ST_TEXTDOMAIN) ?>:</label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <?php echo st()->load_template('availability/form-tour'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="add_service_step" id="step_ical">
                        <div class="form-group form-group-icon-left">
                            <label for="default_state"><?php _e("Ical Sysc", ST_TEXTDOMAIN) ?>:</label>
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
                                               value="<?php echo esc_attr($ical_url); ?>"
                                               class="form-control ical_input"
                                               type="text">
                                        <input type="hidden" name="post_id"
                                               value="<?php echo esc_attr($post_id); ?>">
                                        <button class="btn btn-primary btn-sm btn-ical-sysc"
                                                id="save_ical"><?php echo __('Import', ST_TEXTDOMAIN); ?></button>
                                        <img class="spinner spinner-import"
                                             style="display: none; float: none; visibility: visible;"
                                             src="<?php echo admin_url('/images/spinner.gif'); ?>" alt="spinner">
                                        <p>
                                            <small><i>
                                                    <?php
                                                    $time = get_post_meta($post_id, 'sys_created', true);
                                                    if (!empty($time)) {
                                                        echo '(Last updated: ' . date('Y-m-d H:i:s', $time) . ')';
                                                    }
                                                    ?>
                                                </i></small>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php }?>
            </div>

            <div class="text-center div_btn_submit">
                <?php if (!empty($post_id)) { ?>
                    <input type="button" id="btn_check_insert_post_type_tours" class="btn btn-primary btn-lg"
                           value="<?php _e("UPDATE TOUR", ST_TEXTDOMAIN) ?>">
                    <input name="btn_update_post_type_tours" id="btn_insert_post_type_tours" type="submit"
                           class="btn btn-primary hidden btn_partner_submit_form" value="SUBMIT">
                <?php } else { ?>
                    <input type="hidden" class="save_and_preview" name="save_and_preview" value="false">
                    <input type="hidden" id="" class="" name="action_partner" value="add_partner">
                    <input name="btn_insert_post_type_tours" id="btn_insert_post_type_tours" type="submit" disabled
                           class="btn btn-primary btn-lg btn_partner_submit_form"
                           value="<?php _e("SUBMIT TOUR", ST_TEXTDOMAIN) ?>">
                <?php } ?>

            </div>
        </form>
    </div>
</div>
<div id="html_program" style="display: none">
    <div class="item">
        <div class="col-md-4">
            <div class="form-group form-group-icon-left">
                <label for="title"><?php st_the_language('user_create_tour_program_title') ?></label>
                <input id="title" name="program_title[]" type="text" class="form-control">
            </div>
        </div>
        <div class="col-md-7">
            <div class="form-group form-group-icon-left">
                <label for="program_desc"><?php st_the_language('user_create_tour_program_desc') ?></label>
                <textarea name="program_desc[]" class="form-control h_35"></textarea>
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
<div id="list-item-properties" style="display: none">
    <div class="property-item tab-item">
        <a href="javascript: void(0);" class="delete-tab-item btn btn-danger">x</a>
        <div class="tab-title">&nbsp;</div>
        <div class="tab-content">
            <div class="row">
                <div class="col-xs-12 mb10">
                    <label for=""><?php echo __('Title', ST_TEXTDOMAIN); ?></label>
                    <input type="text" name="property-item[title][]" class="tab-content-title form-control">
                </div>
                <div class="col-xs-12 mb10">
                    <label for=""><?php echo __('Featured Image', ST_TEXTDOMAIN); ?></label>
                    <div class="upload-wrapper upload-partner-wrapper-link">
                        <button class="upload-button-partner-link btn btn-primary btn-sm"
                                data-uploader_title="<?php _e('Select a image to upload', ST_TEXTDOMAIN); ?>"
                                data-uploader_button_text="<?php _e('Use this image', ST_TEXTDOMAIN); ?>"><?php echo __('Upload', ST_TEXTDOMAIN); ?></button>
                        <div class="upload-items">
                            <div class="upload-item">
                            </div>
                        </div>
                        <input type="hidden" class="save-image-url" name="property-item[featured_image][]">
                    </div>
                </div>
                <div class="col-xs-12 mb10">
                    <label for=""><?php echo __('Description', ST_TEXTDOMAIN); ?></label>
                    <textarea name="property-item[description][]" id="" cols="30" rows="10"
                              class="form-control"></textarea>
                </div>
                <div class="col-xs-12 mb10">
                    <label for=""><?php echo __('Icon Map', ST_TEXTDOMAIN); ?></label>
                    <div class="upload-wrapper upload-partner-wrapper-link">
                        <button class="upload-button-partner-link btn btn-primary btn-sm"
                                data-uploader_title="<?php _e('Select a image to upload', ST_TEXTDOMAIN); ?>"
                                data-uploader_button_text="<?php _e('Use this image', ST_TEXTDOMAIN); ?>"><?php echo __('Upload', ST_TEXTDOMAIN); ?></button>
                        <div class="upload-items">
                            <div class="upload-item">
                            </div>
                        </div>
                        <input type="hidden" class="save-image-url" name="property-item[icon][]">
                    </div>
                </div>
                <div class="col-xs-12 mb10">
                    <label for=""><?php echo __('Lat', ST_TEXTDOMAIN); ?></label>
                    <input type="text" name="property-item[map_lat][]" class="form-control">
                </div>
                <div class="col-xs-12 mb10">
                    <label for=""><?php echo __('Lng', ST_TEXTDOMAIN); ?></label>
                    <input type="text" name="property-item[map_lng][]" class="form-control">
                </div>
            </div>
        </div>
    </div>
</div>
<div id="list-item-adultprice" style="display: none">
    <div class="property-item tab-item">
        <a href="javascript: void(0);" class="delete-tab-item btn btn-danger">x</a>
        <div class="tab-title">&nbsp;</div>
        <div class="tab-content">
            <div class="row">
                <div class="col-xs-12 mb10">
                    <div class="form-group">
                        <label for="discount_by_adult_title"><?php _e("Title", ST_TEXTDOMAIN) ?></label>
                        <input id="" name="discount_by_adult_title[]" type="text"
                               class="tab-content-title form-control">
                    </div>
                </div>
                <div class="col-xs-12 mb10">
                    <div class="form-group">
                        <label for="discount_by_adult_key"><?php _e("No. Adult (From)", ST_TEXTDOMAIN) ?></label>
                        <input id="" name="discount_by_adult_key[]" type="text" class="form-control"
                               placeholder="<?php _e("No. Adult (From)", ST_TEXTDOMAIN) ?>">
                    </div>
                </div>
                <div class="col-xs-12 mb10">
                    <div class="form-group">
                        <label for="discount_by_adult_key_to"><?php _e("No. Adult (To)", ST_TEXTDOMAIN) ?></label>
                        <input id="" name="discount_by_adult_key_to[]" type="text" class="form-control"
                               placeholder="<?php _e("No. Adult (To)", ST_TEXTDOMAIN) ?>">
                    </div>
                </div>
                <div class="col-xs-12 mb10">
                    <div class="form-group">
                        <label for="discount_by_adult_value"><?php _e("Percentage of discount", ST_TEXTDOMAIN) ?></label>
                        <input id="" name="discount_by_adult_value[]" type="text" class="form-control"
                               placeholder="<?php _e("Percentage of discount", ST_TEXTDOMAIN) ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="list-item-childprice" style="display: none">
    <div class="property-item tab-item">
        <a href="javascript: void(0);" class="delete-tab-item btn btn-danger">x</a>
        <div class="tab-title">&nbsp;</div>
        <div class="tab-content">
            <div class="row">
                <div class="col-xs-12 mb10">
                    <div class="form-group">
                        <label for="discount_by_child_title"><?php _e("Title", ST_TEXTDOMAIN) ?></label>
                        <input id="" name="discount_by_child_title[]" type="text"
                               class="tab-content-title form-control">
                    </div>
                </div>
                <div class="col-xs-12 mb10">
                    <div class="form-group">
                        <label for="discount_by_child_key"><?php _e("No. Children (From)", ST_TEXTDOMAIN) ?></label>
                        <input id="" name="discount_by_child_key[]" type="text" class="form-control"
                               placeholder="<?php _e("No. Children (From)", ST_TEXTDOMAIN) ?>">
                    </div>
                </div>
                <div class="col-xs-12 mb10">
                    <div class="form-group">
                        <label for="discount_by_child_key_to"><?php _e("No. Children (To)", ST_TEXTDOMAIN) ?></label>
                        <input id="" name="discount_by_child_key_to[]" type="text" class="form-control"
                               placeholder="<?php _e("No. Children (To)", ST_TEXTDOMAIN) ?>">
                    </div>
                </div>
                <div class="col-xs-12 mb10">
                    <div class="form-group">
                        <label for="discount_by_child_value"><?php _e("Percentage of discount", ST_TEXTDOMAIN) ?></label>
                        <input id="" name="discount_by_child_value[]" type="text" class="form-control"
                               placeholder="<?php _e("Percentage of Discount", ST_TEXTDOMAIN) ?>">
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