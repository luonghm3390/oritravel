<header id="main-header" class="st_menu logo-left">
    <?php $logo_url = st()->get_option('logo'); ?>
    <div class="header-top <?php echo apply_filters('st_header_top_class', '') ?>">
        <div class="container">
            <div class="row">
                <div class="col-md-2">
                    <a class="logo" href="<?php echo home_url('/') ?>">
                        <?php
                        if (!empty($logo_url)) {
                            ?>
                            <img style="height: 50px;" src="<?php echo esc_url($logo_url); ?>" alt="logo"
                                 title="<?php bloginfo('name') ?>">
                            <?php
                        }
                        ?>
                    </a>
                </div>
                <div class="main_menu_wrap col-md-8" id="menu1">
                    <div class="<?php echo apply_filters('st_default_menu_wrapper', "container"); ?>">
                        <div class="nav">
                            <?php
                            $logo__mobile_url = st()->get_option('logo_mobile', $logo_url);
                            $html_logo_mobile = "";
                            if (!empty($logo__mobile_url)) {
                                $html_logo_mobile = '<a href=\'' . home_url('/') . '\'><img alt=\'' . TravelHelper::get_alt_image() . '\' width=auto height=40px class=st_logo_mobile src=' . $logo__mobile_url . ' /></a>';
                            }
                            if (has_nav_menu('primary')) {
                                $mega_menu = st()->get_option('allow_megamenu', 'off');
                                if ($mega_menu == 'on') {
                                    wp_nav_menu(array(
                                        'theme_location' => 'primary',
                                        "container" => "",
                                        'items_wrap' => '<ul id="slimmenu" data-title="' . $html_logo_mobile . '" class="%2$s slimmenu">%3$s</ul>',
                                        'depth' => 10,
                                        'walker' => new ST_Mega_Menu_Walker(),
                                    ));
                                } else {
                                    wp_nav_menu(array(
                                        'theme_location' => 'primary',
                                        "container" => "",
                                        'items_wrap' => '<ul id="slimmenu" data-title="' . $html_logo_mobile . '" class="%2$s slimmenu">%3$s</ul>',
                                        'walker' => new st_menu_walker(),
                                    ));
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-2" style="padding: 9px 20px;">
                   
                        <?php
                            $page_user_register = st()->get_option('page_user_register');
                            $login_modal = $res_modal = '';
                            $page_user_register = esc_url(get_the_permalink($page_user_register));
                            ?>
                            <a href="<?php echo ($page_user_register) ?>" class="btn btn-primary btn-lg btn-become-partner" style="float: right;"><?php st_the_language("Become Partner", ST_TEXTDOMAIN)?></a>
                </div>
            </div>
        </div>
    </div>
</header>