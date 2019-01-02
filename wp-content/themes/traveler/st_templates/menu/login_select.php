<?php 
// login dropdown 
// from 1.1.9
if (empty($container)){$container = "div"; }
if (empty($class)) {$class = "top-user-area-avatar" ;}
$class_default = "nav-drop" ; 

$is_user_nav = st()->get_option('enable_user_nav','on') ?>
<?php if($is_user_nav == 'on'): ?>
    <?php if(is_user_logged_in()):?>
    <?php echo '<'.$container.' class="'.$class.'">'; ?>    
        <?php
        $account_dashboard = st()->get_option('page_my_account_dashboard');
        $location='#';
        if(!empty($account_dashboard)){
            $location = get_permalink($account_dashboard);
        }
        ?>
        
            <?php
            $current_user = wp_get_current_user();
            echo st_get_profile_avatar($current_user->ID,40);
            printf(__('Welcome, %s',ST_TEXTDOMAIN),$current_user->display_name);
            ?>
            <ul>
                <li>
                    <a href="<?php echo esc_url($location) ?>"> <?php echo __("My Dashboard", ST_TEXTDOMAIN);?></a>
                </li>
                <li>
                    <?php echo  '</'.esc_attr($container).'>' ;?>
                    <?php echo  '<'.esc_attr($container).'>' ;?>
                        <a class="btn-st-logout" href="<?php echo wp_logout_url(home_url())?>"><?php st_the_language('sign_out')?></a>
                    <?php echo  '</'.$container.'>' ;?>
                </li>
            
            </ul>
    
    <?php else: ?>
         <?php echo '<'.esc_attr($container).' class="'.esc_attr($class_default).'">'; ?>    
            <?php
            $enable_popup_login = st()->get_option('enable_popup_login','off');
            $page_login = st()->get_option('page_user_login');
            $page_user_register = st()->get_option('page_user_register');
            $login_modal = $res_modal = '';
            $page_login = esc_url(get_the_permalink($page_login));
            $page_user_register = esc_url(get_the_permalink($page_user_register));
            if($enable_popup_login == 'on'){
                $login_modal = 'data-toggle="modal" data-target="#login_popup"';
                $res_modal = 'data-toggle="modal" data-target="#register_popup"';
                $page_login = $page_user_register = 'javascript:void(0)';
            }
            ?>
                <li><a  class="" <?php echo ($login_modal); ?>  href="<?php echo ($page_login) ?>"><?php echo __("Sign In", ST_TEXTDOMAIN);?></a></li>
                <li><a  class="" <?php echo ($res_modal); ?>  href="<?php echo ($page_user_register) ?>" ><?php echo __("Sign Up", ST_TEXTDOMAIN);?></a></li>
        <?php echo  '</'.esc_attr($container).'>' ;?>
    <?php endif;?>
<?php endif; ?>
