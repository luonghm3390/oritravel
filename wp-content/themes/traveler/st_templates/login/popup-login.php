<?php
/**
 * Created by ShineTheme.
 * User: NAZUMI
 * Date: 9/26/2016
 * Version: 1.0
 */
$url_reset = st()->get_option('page_reset_password', '');
if( $url_reset ){
    $url_reset = get_permalink( $url_reset );
}else{
    $url_reset = wp_lostpassword_url();
}
?>
<div class="modal fade login_popup" id="login_popup" tabindex="-1" data-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="smallModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel"><?php echo esc_html__('Sign In',ST_TEXTDOMAIN)?></h4>
            </div>
            <div class="modal-body">
                <form class="st_login_form_popup" method="post" action="<?php echo esc_url(add_query_arg(array(
                    'url'=>STInput::request('url')
                )))?>">
                    <div class="notice_login"></div>
                    <div class="form-group form-group-icon-left">
                        <label for="pop-login_name"><?php _e("User Name",ST_TEXTDOMAIN) ?></label>
                        <i class="fa fa-user input-icon input-icon-show"></i>
                        <input id="pop-login_name" name="login_name" class="form-control" placeholder="<?php _e('e.g. johndoe',ST_TEXTDOMAIN)?>" type="text" value="<?php echo STInput::request('login_name') ?>" />
                    </div>
                    <div class="form-group form-group-icon-left">
                        <label for="pop-login_password"><?php st_the_language('password') ?></label>
                        <i class="fa fa-lock input-icon input-icon-show"></i>
                        <input id="pop-login_password" name="login_password" class="form-control" type="password"  placeholder="<?php st_the_language('my_secret_password') ?>" />
                    </div>
                    <?php
                    $btn_sing_in = get_post_meta(get_the_ID(),'btn_sing_in',true);
                    if(empty($btn_sing_in)){
                        $btn_sing_in = __("Sign In",ST_TEXTDOMAIN);
                    }
                    ?>
                    <div class="btn-submit-form">
                    <input class="btn btn-primary btn-login-popup" name="login_submit" type="submit" value="<?php echo esc_attr($btn_sing_in) ?>" />
                        <img  alt="loading" src="<?php echo esc_url(ST_TRAVELER_URI.'/img/ajax-login.gif')?>">
                    </div>
                    <br>
                    <a class="popup_forget_pass" href="<?php echo esc_url( $url_reset ); ?>" title="<?php _e("Forget Password",ST_TEXTDOMAIN) ?>"><?php _e("Forget Password ?",ST_TEXTDOMAIN) ?></a>
                    <div class="checkbox st_check_term_conditions">
		                <?php
		                $page_privacy_policy = get_option('wp_page_for_privacy_policy');
		                if(!empty($page_privacy_policy)){
			                $page_privacy_policy_link = get_permalink($page_privacy_policy);
			                ?>
                            <a href="<?php echo esc_html($page_privacy_policy_link); ?>" ><?php echo __('Privacy Policy', ST_TEXTDOMAIN); ?></a>
			                <?php
		                }
		                ?>
                    </div>
                    <ul class="list nav-drop-menu user_nav_big social_login_nav_drop">
                        <?php if(st_social_channel_status('facebook')): ?>
                            <li class="social_img"><a onclick="return false" class="btn_login_fb_link st_login_social_link" data-channel="facebook" href="#"><img width="100" height="37" alt="<?php echo TravelHelper::get_alt_image(); ?>" src="<?php echo get_template_directory_uri()."/img/social/facebook-logo.jpg"; ?>"/></a></li>
                        <?php endif;?>

                        <?php if(st_social_channel_status('google')): ?>
                            <li class="social_img"><a onclick="return false" class="btn_login_gg_link st_login_social_link" data-channel="google" href="#"><img width="100" height="37" alt="<?php echo TravelHelper::get_alt_image(); ?>" src="<?php echo get_template_directory_uri()."/img/social/google-plus.jpg"; ?>"/></a></li>
                        <?php endif;?>

                        <?php if(st_social_channel_status('twitter')): ?>
                            <li class="social_img"><a onclick="return false" class="btn_login_tw_link login_social_link" data-channel="twitter" href="<?php echo site_url() ?>/social-login/twitter"><img width="100" height="37" alt="<?php echo TravelHelper::get_alt_image(); ?>" src="<?php echo get_template_directory_uri()."/img/social/twitter-logo.png"; ?>"/></a></li>
                        <?php endif;?>
                    </ul>
                    <script>
                        jQuery(document).ready(function($){

                            if(typeof window.gapi!='undefined')
                            {
                                initGoogleClient();
                            }

                            function initGoogleClient()
                            {
                                var config = { client_id:st_social_params.google_client_id, scope: 'profile email https://www.googleapis.com/auth/plus.login' };

                                window.gapi.load('auth2', function () {
                                    window.gapi.auth2.init(config);
                                })
                            }

                            function startLoginWithFacebook(btn){
                                btn.addClass('loading');

                                FB.getLoginStatus(function(response) {
                                    if (response.status === 'connected') {
                                        sendLoginData({
                                            'channel':'facebook',
                                            'access_token':response.authResponse.accessToken
                                        });

                                    } else{
                                        FB.login(function(response) {
                                            if (response.authResponse) {
                                                sendLoginData({
                                                    'channel':'facebook',
                                                    'access_token':response.authResponse.accessToken
                                                });

                                            } else {
                                                alert('User cancelled login or did not fully authorize.');
                                            }
                                        }, {
                                            scope: 'email',
                                            return_scopes: true
                                        });
                                    }
                                });
                            }


                            function startLoginWithGoogle(btn){
                                btn.addClass('loading');

                                if(typeof window.gapi.auth2 =='undefined') return;

                                window.gapi.auth2.getAuthInstance().grantOfflineAccess({'redirect_uri': 'postmessage'}).then(function (response) {

                                    sendLoginData({
                                        'channel':'google',
                                        'authorizationCode':response.code
                                    });
                                }, function (error) {
                                    console.log(error);
                                    alert('Google Server SIGN-IN ERROR');
                                })
                            }

                            function sendLoginData(data)
                            {
                                data._s = st_params._s;
                                data.action = 'traveler.socialLogin';

                                $.ajax({
                                    data:data,
                                    type:'post',
                                    dataType:'json',
                                    url:st_params.ajax_url,
                                    success:function (rs) {
                                        handleSocialLoginResult(rs);
                                    },
                                    error:function (e) {

                                        alert('Can not login. Please try again later');
                                    }
                                })
                            }


                            function handleSocialLoginResult(rs)
                            {
                                if(rs.reload) window.location.reload();
                                if(rs.message) alert(rs.message);
                            }


                            $('.st_login_social_link').on('click',function () {
                                var channel = $(this).data('channel');

                                switch (channel) {
                                    case "facebook":
                                        startLoginWithFacebook($(this));
                                        break;
                                    case "google":
                                        startLoginWithGoogle($(this));
                                        break;
                                }
                            })
                        })
                    </script>
                </form>
            </div>
        </div>
    </div>
</div>
