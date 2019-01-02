<?php
/*
 * Template Name: User Dashboard
*/

$user_link = get_permalink();
$current_user = wp_get_current_user();
$lever = $current_user->roles;
$lever = array_shift($lever);
$url_id_user = '';
if (!empty($_REQUEST['id_user'])) {
    $id_user_tmp = $_REQUEST['id_user'];
    $current_user = get_userdata($id_user_tmp);
    $url_id_user = $id_user_tmp;
}
$default_page = "setting";
if (STUser_f::check_lever_partner($lever) and st()->get_option('partner_enable_feature') == 'on') {
    $default_page = "dashboard";
}
$sc = get_query_var('sc');
if (empty($sc)) {
    $sc = 'dashboard';
}
//==== Redirect to user settings if not have a package
$admin_packages = STAdminPackages::get_inst();
if ($admin_packages->user_can_register_package(get_current_user_id()) && in_array($sc, array(
        'create-tours',
        'my-tours',
        'booking-tours',
        'create-activity',
        'my-activity',
        'booking-activity',
        'create-cars',
        'my-cars',
        'booking-cars',
        'create-rental',
        'my-rental',
        'booking-rental',
        'booking-booking',
    ))) {
    wp_redirect(TravelHelper::get_user_dashboared_link($user_link, 'setting'));
    exit();
}

get_header();
wp_enqueue_script('template-user-js');
wp_enqueue_script('user.js');

$show_menu = true;
$hide_menu_ins =['create-rental','edit-rental','create-tours','edit-tours','create-activity','edit-activity','create-cars','edit-cars'];

if(STInput::get('sc')=='inbox' && STInput::get('message_id'))
{
    $show_menu=false;
}

if(in_array(STInput::get('sc'),$hide_menu_ins))
{
    $show_menu=false;
}

?>
<?php if ($sc == "details-owner") { ?>
    <?php echo st()->load_template('user/user', $sc); ?>
<?php } else {
    ?>
    <div class="container bg-partner-new <?php echo esc_html($sc) ?>">
        <div class="row row_content_partner">
            <?php if($show_menu){?>
            <div class="col-md-3 user-left-menu ">
                <div class="page-sidebar navbar-collapse st-page-sidebar-new">
                    <ul class="page-sidebar-menu st_menu_new">
                        <li class="heading text-center user-profile-sidebar">
                            <div class="user-profile-avatar text-center">
                                <?php echo st_get_profile_avatar($current_user->ID, 300); ?>
                                <h5><?php echo esc_html($current_user->display_name) ?></h5>
                                <p><?php echo st_get_language('user_member_since') . mysql2date(' M Y', $current_user->data->user_registered); ?></p>
                                <?php
                                if (($lever == "subscriber" || $lever == 'contributor' || $lever == 'author' || $lever == 'editor') && $lever != 'administrator' && $lever != 'partner') {
                                    $stas_partner = get_user_meta($current_user->ID, 'st_pending_partner', true);
                                    if($stas_partner == '1') {
                                        ?>
                                        <button class="btn btn-primary btn-xs"><?php echo __('Waiting for approval', ST_TEXTDOMAIN); ?></button>
                                        <?php
                                    }else{
                                        ?>
                                        <form method="post" action="">
                                            <input class="btn btn-primary btn-xs btn-user-update-to-partner"
                                                   type="submit"
                                                   value="<?php echo __('Upgrade to partner', ST_TEXTDOMAIN); ?>"
                                                   name="btn_upgrade_to_partner"
                                                   data-confirm="<?php echo __('Are you sure want to upgrade to partner?', ST_TEXTDOMAIN); ?>"/>
                                        </form>
                                        <?php
                                    }
                                }
                                ?>
                            </div>
                        </li>
                        <?php if (!empty($_REQUEST['id_user'])) { ?>
                            <li class="item item-setting-info <?php if ($sc == 'setting-info') echo 'active' ?> ">

                                <a href="<?php echo add_query_arg('id_user', $url_id_user, TravelHelper::get_user_dashboared_link($user_link, 'setting-info')) ?>">
                                    <i class="fa fa-cog"></i>
                                    <span class="title"><?php esc_html_e('Settings','traveler') ?></span>
                                </a>
                            </li>
                        <?php } else { ?>
                            <?php if (STUser_f::check_lever_partner($lever) and st()->get_option('partner_enable_feature') == 'on'): ?>
                                <li class="item item-dashboard <?php if ($sc == 'dashboard' or $sc == 'dashboard-info') echo 'active' ?>">
                                    <a href="<?php echo TravelHelper::get_user_dashboared_link($user_link, 'dashboard'); ?>">
                                        <i class="fa fa-cogs"></i>
                                        <span class="title"><?php _e("Dashboard", ST_TEXTDOMAIN) ?></span>
                                        <span class="arrow "></span>
                                    </a>
                                    <ul class="sub-menu item">
                                        <?php if (STUser_f::_check_service_available_partner('st_rental')): ?>
                                            <li class="<?php if ($sc == 'dashboard-info' and STInput::request('type') == 'st_rental') echo 'active' ?>">
                                                <a href="<?php echo add_query_arg('type', 'st_rental', TravelHelper::get_user_dashboared_link($user_link, 'dashboard-info')); ?>">
                                                    <i class="fa fa-home">
                                                        &nbsp; </i><?php _e("Rental Statistics", ST_TEXTDOMAIN) ?></a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if (STUser_f::_check_service_available_partner('st_cars')): ?>
                                            <li class="<?php if ($sc == 'dashboard-info' and STInput::request('type') == 'st_cars') echo 'active' ?>">
                                                <a href="<?php echo add_query_arg('type', 'st_cars', TravelHelper::get_user_dashboared_link($user_link, 'dashboard-info')); ?>">
                                                    <i class="fa fa-cab">
                                                        &nbsp; </i><?php _e("Car Statistics", ST_TEXTDOMAIN) ?></a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if (STUser_f::_check_service_available_partner('st_tours')): ?>
                                            <li class="<?php if ($sc == 'dashboard-info' and STInput::request('type') == 'st_tours') echo 'active' ?>">
                                                <a href="<?php echo add_query_arg('type', 'st_tours', TravelHelper::get_user_dashboared_link($user_link, 'dashboard-info')); ?>">
                                                    <i class="fa fa-flag-o">
                                                        &nbsp; </i><?php _e("Tour Statistics", ST_TEXTDOMAIN) ?></a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if (STUser_f::_check_service_available_partner('st_activity')): ?>
                                            <li class="<?php if ($sc == 'dashboard-info' and STInput::request('type') == 'st_activity') echo 'active' ?>">
                                                <a href="<?php echo add_query_arg('type', 'st_activity', TravelHelper::get_user_dashboared_link($user_link, 'dashboard-info')); ?>">
                                                    <i class="fa fa-bolt">
                                                        &nbsp; </i><?php _e("Activity Statistics", ST_TEXTDOMAIN) ?></a>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </li>
                            <?php endif ?>
                            <li class="item item-overview <?php if ($sc == 'overview') echo 'active' ?>">
                                <a href="<?php echo TravelHelper::get_user_dashboared_link($user_link, 'overview'); ?>"><i
                                            class="fa fa-user"></i><?php esc_html_e('Overview','traveler') ?></a>
                            </li>
                            <li class="item item-setting <?php if ($sc == 'setting') echo 'active' ?>">
                                <a href="<?php echo TravelHelper::get_user_dashboared_link($user_link, 'setting'); ?>"><i
                                            class="fa fa-cog"></i><?php esc_html_e('Settings','traveler') ?></a>
                            </li>
                            <?php
                            $custom_layout = st()->get_option('partner_custom_layout', 'off');
                            $custom_layout_booking_history = st()->get_option('partner_custom_layout_booking_history', 'on');
                            if ($custom_layout == "off") {
                                $custom_layout_booking_history = "on";
                            }
                            ?>
                            <?php if ($custom_layout_booking_history == "on") { ?>
                                <li class="item item-booking-history <?php if ($sc == 'booking-history') echo 'active' ?>">
                                    <a href="<?php echo TravelHelper::get_user_dashboared_link($user_link, 'booking-history'); ?>"><i
                                                class="fa fa-clock-o"></i><?php esc_html_e('Booking History','traveler') ?>
                                    </a>
                                </li>
                            <?php } ?>
                            <li class="item item-wishlist <?php if ($sc == 'wishlist') echo 'active' ?>">
                                <a href="<?php echo TravelHelper::get_user_dashboared_link($user_link, 'wishlist'); ?>"><i
                                            class="fa fa-heart-o"></i><?php esc_html_e('Wishlist','traveler') ?></a>
                            </li>
                            <li class="item item-inbox <?php if ($sc == 'inbox') echo 'active' ?>">
                                <a href="<?php echo TravelHelper::get_user_dashboared_link($user_link, 'inbox'); ?>"><i
                                            class="fa fa-inbox"></i><?php esc_html_e("Inbox Notification ", ST_TEXTDOMAIN) ?>
                                </a>
                            </li>

<!--                            <li class="item item-inbox --><?php //if ($sc == 'message') echo 'active' ?><!--">-->
<!--                                <a href="--><?php //echo TravelHelper::get_user_dashboared_link($user_link, 'message'); ?><!--"><i-->
<!--                                            class="fa fa-inbox"></i>--><?php //esc_html_e("Message", ST_TEXTDOMAIN) ?>
<!--                                </a>-->
<!--                            </li>-->

                            <?php if (STUser_f::check_lever_partner($lever) and st()->get_option('partner_enable_feature') == 'on'): ?>
                                <?php if ($lever != "administrator" && st()->get_option('enable_withdrawal', 'on') == 'on') { ?>
                                    <li class="item item-withdrawal <?php if (in_array($sc, array('withdrawal', 'withdrawal-history'))) echo "active" ?>">
                                        <a href="<?php echo TravelHelper::get_user_dashboared_link($user_link, 'withdrawal'); ?>">
                                            <i class="fa fa-user"></i>
                                            <?php _e("Withdrawal", ST_TEXTDOMAIN) ?>
                                            <span class="arrow"></span>
                                        </a>
                                        <ul class="sub-menu item">
                                            <li <?php if ($sc == 'withdrawal-history') echo 'class="active"' ?>>
                                                <a href="<?php echo TravelHelper::get_user_dashboared_link($user_link, 'withdrawal-history'); ?>">
                                                    <i class="fa fa-clock-o">&nbsp;</i><?php _e("History", ST_TEXTDOMAIN) ?>
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                <?php } ?>
                                
                                <?php if (STUser_f::_check_service_available_partner('st_tours')): ?>
                                    <li class="item item-tours <?php if (in_array($sc, array('create-tours', 'edit-tours', 'my-tours', 'booking-tours', 'add-tour-booking'))) echo "active" ?>">
                                        <a class=" cursor" style="cursor: pointer !important">
                                            <i class="fa fa-flag-o"></i>
                                            <?php _e('Tour', ST_TEXTDOMAIN) ?>
                                            <span class="arrow"></span>
                                        </a>
                                        <ul class="sub-menu item">
                                            <li <?php if ($sc == 'create-tours')
                                                echo 'class="active"' ?>>
                                                <a href="<?php echo TravelHelper::get_user_dashboared_link($user_link, 'create-tours'); ?>"><i
                                                            class="fa fa-flag-o">&nbsp;</i><?php esc_html_e('Add new tour','traveler') ?>
                                                </a>
                                            </li>
                                            <li <?php if ($sc == 'my-tours')
                                                echo 'class="active"' ?>>
                                                <a href="<?php echo TravelHelper::get_user_dashboared_link($user_link, 'my-tours'); ?>">
                                                    <i class="fa fa-flag-o">&nbsp;</i><?php esc_html_e('My tour','traveler') ?>
                                                </a>
                                            </li>
                                            <li <?php if ($sc == 'booking-tours')
                                                echo 'class="active"' ?>>
                                                <a href="<?php echo TravelHelper::get_user_dashboared_link($user_link, 'booking-tours'); ?>"><i
                                                            class="fa fa-flag-o">&nbsp;</i><?php _e("Tour Bookings", ST_TEXTDOMAIN) ?>
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                <?php endif; ?>

                                <?php if (STUser_f::_check_service_available_partner('st_activity')): ?>
                                    <li class="item item-activity <?php if (in_array($sc, array('create-activity', 'edit-activity', 'my-activity', 'booking-activity', 'add-activity-booking'))) echo "active" ?>">
                                        <a class="cursor">
                                            <i class="fa fa-bolt"></i>
                                            <?php _e('Activity', ST_TEXTDOMAIN) ?>
                                            <span class="arrow"></span>
                                        </a>
                                        <ul class="sub-menu item">
                                            <li <?php if ($sc == 'create-activity')
                                                echo 'class="active"' ?>>
                                                <a href="<?php echo TravelHelper::get_user_dashboared_link($user_link, 'create-activity'); ?>"><i
                                                            class="fa fa-bolt">&nbsp;</i><?php esc_html_e('Add new activity','traveler') ?>
                                                </a>
                                            </li>
                                            <li <?php if ($sc == 'my-activity')
                                                echo 'class="active"' ?>>
                                                <a href="<?php echo TravelHelper::get_user_dashboared_link($user_link, 'my-activity'); ?>"><i
                                                            class="fa fa-bolt">&nbsp;</i><?php esc_html_e('My Activities','traveler') ?>
                                                </a>
                                            </li>
                                            <li <?php if ($sc == 'booking-activity')
                                                echo 'class="active"' ?>>
                                                <a href="<?php echo TravelHelper::get_user_dashboared_link($user_link, 'booking-activity'); ?>"><i
                                                            class="fa fa-bolt">&nbsp;</i><?php _e("Activity Bookings", ST_TEXTDOMAIN) ?>
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                <?php endif; ?>

                                <?php if (STUser_f::_check_service_available_partner('st_cars')): ?>
                                    <li class="item item-cars <?php if (in_array($sc, array('create-cars', 'edit-cars', 'my-cars', 'booking-cars', 'add-car-booking'))) echo "active" ?>">
                                        <a class="cursor" style="cursor: pointer !important">
                                            <i class="fa fa-cab"></i><?php _e('Car', ST_TEXTDOMAIN) ?>
                                            <span class="arrow"></span>
                                        </a>
                                        <ul class="sub-menu item">
                                            <li <?php if ($sc == 'create-cars')
                                                echo 'class="active"' ?>>
                                                <a href="<?php echo TravelHelper::get_user_dashboared_link($user_link, 'create-cars'); ?>">
                                                    <i class="fa fa-cab">&nbsp;</i><?php esc_html_e('Add new car','traveler') ?>
                                                </a>
                                            </li>
                                            <li <?php if ($sc == 'my-cars')
                                                echo 'class="active"' ?>>
                                                <a href="<?php echo TravelHelper::get_user_dashboared_link($user_link, 'my-cars'); ?>">
                                                    <i class="fa fa-cab">&nbsp;</i><?php esc_html_e('My Car','traveler') ?>
                                                </a>
                                            </li>
                                            
                                            <li <?php if ($sc == 'booking-cars')
                                                echo 'class="active"' ?>>
                                                <a href="<?php echo TravelHelper::get_user_dashboared_link($user_link, 'booking-cars'); ?>">
                                                    <i class="fa fa-cab">&nbsp;</i><?php _e("Car Bookings", ST_TEXTDOMAIN) ?>
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                <?php endif; ?>

                                <?php if (STUser_f::_check_service_available_partner('st_rental')): ?>
                                    <li class="item item-rental <?php if (in_array($sc, array('create-rental', 'edit-rental', 'my-rental', 'create-room-rental', 'my-room-rental', 'booking-rental', 'add-rental-booking'))) echo "active" ?>">
                                        <a class="cursor" style="cursor: pointer !important">
                                            <i class="fa fa-home"></i></i>
                                            <?php _e('Rental', ST_TEXTDOMAIN) ?>
                                            <span class="arrow"></span>
                                        </a>
                                        <ul class="sub-menu item">
                                            <li <?php if ($sc == 'create-rental')
                                                echo 'class="active"' ?>>
                                                <a href="<?php echo TravelHelper::get_user_dashboared_link($user_link, 'create-rental'); ?>"><i
                                                            class="fa fa-home">&nbsp;</i><?php esc_html_e('Add new rental','traveler') ?>
                                                </a>
                                            </li>
                                            <li <?php if ($sc == 'my-rental')
                                                echo 'class="active"' ?>>
                                                <a href="<?php echo TravelHelper::get_user_dashboared_link($user_link, 'my-rental'); ?>"><i
                                                            class="fa fa-home">&nbsp;</i><?php esc_html_e('My Rental','traveler') ?>
                                                </a>
                                            </li>
                                            <li <?php if ($sc == 'booking-rental')
                                                echo 'class="active"' ?>>
                                                <a href="<?php echo TravelHelper::get_user_dashboared_link($user_link, 'booking-rental'); ?>"><i
                                                            class="fa fa-home">&nbsp;</i><?php _e("Rental Bookings", ST_TEXTDOMAIN) ?>
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                <?php endif; ?>
                                
                                <?php if (is_super_admin()): ?>
                                    <li class="item item-list-refund <?php if (in_array($sc, array('list-refund'))) echo "active" ?>">
                                        <a href="<?php echo TravelHelper::get_user_dashboared_link($user_link, 'list-refund'); ?>"
                                           class="cursor" style="cursor: pointer !important">
                                            <i class="fa fa-money"></i></i>
                                            <?php _e('Refund Manager', ST_TEXTDOMAIN) ?>
                                        </a>
                                    </li>
                                <?php endif; ?>
                                <?php if(function_exists('affiliate_wp'))
                                {
                                    ?>
                                    <li class="item item-list-refund <?php if (in_array($sc, array('affiliate_wp','affiliate_wp_visits','affiliate_wp_referrals'))) echo "active" ?>">
                                        <a href="<?php echo TravelHelper::get_user_dashboared_link($user_link, 'affiliate_wp'); ?>"
                                           class="cursor" style="cursor: pointer !important">
                                            <i class="fa fa-money"></i></i>
                                            <?php _e('Affiliate ', ST_TEXTDOMAIN) ?>
                                            <span class="arrow"></span>
                                        </a>
                                        <ul class="sub-menu item">
                                            <li class="<?php if (in_array($sc, array('affiliate_wp_visits'))) echo "active" ?>">
                                                <a href="<?php echo TravelHelper::get_user_dashboared_link($user_link, 'affiliate_wp_visits'); ?>"><i
                                                            class="fa fa-plane">&nbsp;</i><?php _e("Visits", ST_TEXTDOMAIN) ?>
                                                </a>
                                            </li>
                                            <li class="<?php if (in_array($sc, array('affiliate_wp_referrals'))) echo "active" ?>">
                                                <a href="<?php echo TravelHelper::get_user_dashboared_link($user_link, 'affiliate_wp_referrals'); ?>"><i
                                                            class="fa fa-plane">&nbsp;</i><?php _e("Referrals", ST_TEXTDOMAIN) ?>
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                    <?php
                                }
                                ?>
                                <?php if(st_user_has_partner_features()){?>
                                <li class="item item-list-refund <?php if (in_array($sc, array('verify_user'))) echo "active" ?>">
                                    <a href="<?php echo TravelHelper::get_user_dashboared_link($user_link, 'verify_user'); ?>"
                                       class="cursor" style="cursor: pointer !important">
                                        <i class="fa fa-check"></i></i>
                                        <?php _e('Verifications', ST_TEXTDOMAIN) ?>
                                    </a>
                                </li>
                                <?php }?>


                            <?php endif ?>
                        <?php } ?>
                        <?php
                        do_action('st_menu_template_user', $sc, $user_link);
                        ?>
                    </ul>
                </div>
            </div>
            <?php } ?>
            <div class="user-content <?php echo $show_menu?'col-md-9':'col-md-12' ?>">
                <div class="st-page-bar">
                    <ul class="page-breadcrumb">
                        <?php echo STUser_f::st_get_breadcrumb_partner() ?>
                    </ul>
                </div>
                <?php
                if (!empty($_REQUEST['id_user'])) {
                    echo st()->load_template('user/user', 'setting-info', get_object_vars($current_user));
                } else {
                    if (STUser_f::check_lever_partner($lever)) {
                        if (STUser_f::check_lever_service_partner($sc, $lever)) {
                            switch ($sc) {
                                case "create-rental";
                                    $sc = "edit-rental";
                                    break;
                                case "create-tours";
                                    $sc = "edit-tours";
                                    break;
                                case "create-cars";
                                    $sc = "edit-cars";
                                    break;
                                case "create-activity";
                                    $sc = "edit-activity";
                                    break;
                            }
                            echo st()->load_template('user/user', $sc, get_object_vars($current_user));
                        } else {
                            _e("You don't have permission to access this page", ST_TEXTDOMAIN);
                        }
                    } else {
                        $arr_page_menu = ["overview", "setting", "setting-info", "wishlist", "booking-history", "certificate", "write_review", "inbox"];

                        if (in_array($sc, apply_filters('st_menu_link_page', $arr_page_menu))) {
                            echo st()->load_template('user/user', $sc, get_object_vars($current_user));
                        } else {
                            echo st()->load_template('user/user', 'setting', get_object_vars($current_user));
                        }
                    }
                }
                ?>
            </div>
        </div>
    </div>
<?php } ?>
    <div class="gap"></div>
<?php
$is_expired = $admin_packages->is_package_expired(get_current_user_id());

if ($is_expired) {
    echo st()->load_template('user/user', 'alert_package');
}
get_footer();
?>