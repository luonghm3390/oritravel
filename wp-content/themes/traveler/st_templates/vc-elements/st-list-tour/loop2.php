<div class="row row-wrap">
    <?php
    $tours = new STTour();
    while(have_posts()):
        the_post();
        if(intval($st_tour_of_row) <= 0 ) $st_tour_of_row =4;
        $col = 12 / $st_tour_of_row;

        $info_price = STTour::get_info_price();
        ?>
        <div class="col-md-<?php echo esc_attr($col) ?> style_box col-sm-6 col-xs-12 st_fix_<?php echo esc_attr($st_tour_of_row); ?>_col st_lazy_load">
            <?php echo STFeatured::get_featured(); ?>
            <div class="thumb list-tour-2">
                <header class="thumb-header">
                    <?php if(!empty( $info_price['discount'] ) and $info_price['discount']>0 and $info_price['price_new'] >0) { ?>
                        <?php echo STFeatured::get_sale($info_price['discount']); ?>
                    <?php } ?>
                    <a href="<?php the_permalink() ?>" class="hover-img">
                        <?php
                        TravelHelper::getLazyLoadingImage(array(800,600,'bfi_thumb'=>true));
                        ?>
                    </a>
                </header>
                <div class="thumb-caption">
                    <a href="<?php the_permalink() ?>" class="title"><?php the_title(); ?></a>
                    <div class="row col-md-12 location-tours">
                        <?php if($location=TravelHelper::locationHtml(get_the_ID())){ ?>
                            <i class="fa fa-map-marker"></i>
                            <?php
                              echo ($location);
                            ?>
                    </div>
                    <div class="col-md-12 list-package">
                        <div class="col-md-4 list-tour-package list-tour-duration">
                            <div class="package-info">
                                <?php
                                $type_tour = get_post_meta(get_the_ID() , 'type_tour' ,true);
                                if($type_tour == 'daily_tour'){
                                    $day = STTour::get_duration_unit();
                                    echo ' <i class="fa fa-clock-o"></i> '.'<p class="duration">DURATION</p>'.esc_html($day);
                                }else{
                                    $check_in = get_post_meta(get_the_ID() , 'check_in' ,true);
                                    $check_out = get_post_meta(get_the_ID() , 'check_out' ,true);
                                    if(!empty($check_in) and !empty($check_out)){
                                        $format=TravelHelper::getDateFormat();
                                        $date = date_i18n($format,strtotime($check_in)).' <i class="fa fa-long-arrow-right"></i> '.date_i18n($format,strtotime($check_out));
                                        echo ' <i class="fa fa-clock-o"></i> '.'<p class="duration">DURATION</p>'. balanceTags($date);
                                    }
                                }
                                ?>
                            </div>
                        </div>
                        <div class="people-info list-tour-package col-md-4">
                            <?php $max_people = get_post_meta(get_the_ID(),'max_people', true) ?>
                            <i class="fa fa-users"></i>
                            <p><?php if($max_people>1)st_the_language('user_peoples');else st_the_language('user_people') ?></p>
                            <?php echo esc_html($max_people) ?>                           
                        </div>
                        <?php }?>
                        <?php if($price_html=STTour::get_price_html(false,false,' <br> -')){?>
                            <div class="col-md-4 list-tour-package list-tour-price">
                                <p class="mb0 text-darken">
                                    <i class="fa fa-money"></i>
                                    <p><?php _e('FROM',ST_TEXTDOMAIN) ?></p>
                                    <?php echo ($price_html); ?>
                                </p>
                            </div>
                        <?php }?>
                    </div>
                    <!--<div class="row">
                        
                        <div class="col-md-12">
                            <ul class="icon-group text-color pull-left">
                                <?php echo  TravelHelper::rate_to_string(STReview::get_avg_rate()); ?>
                            </ul>
                        </div>
                    </div>-->
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12 text-right book-tour">
                    <a href="<?php the_permalink() ?>" type="button" class="btn btn-primary"><?php _e('MORE DETAILS',ST_TEXTDOMAIN) ?></a>
                </div>
            </div>
        </div>
    <?php
    endwhile;
    ?>
</div>