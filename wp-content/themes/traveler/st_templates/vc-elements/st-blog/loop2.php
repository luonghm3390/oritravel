<div class="col-md-12 blog-list-layout">
    <div class="row">
        <div class="col-md-3">
            <header class="thumb-header">
                    <?php
                    $img = get_the_post_thumbnail( get_the_ID() , array(800,600,'bfi_thumb'=>true), array('alt' => TravelHelper::get_alt_image(get_post_thumbnail_id( )))) ;
                    if(!empty($img)){
                        echo balanceTags($img);
                    }else{
                        echo '<img width="800" height="600" alt="no-image" class="wp-post-image" src="'.ST_TRAVELER_URI.'/img/no-image.png">';
                    }
                    ?>
            </header>
        </div>
        <div class="col-md-9">
            <div class="thumb text-left">
                <a href="<?php the_permalink() ?>">
                    <h5 class="text-color"><?php the_title() ?></h5>
                </a>
                <div class="thumb-caption">
                    <ul class="blog-meta">
                        <li><i class="fa fa-calendar"></i><?php the_time(get_option('date_format').' '.get_option('time_format'))?></li>
                        <li><a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><?php _e('By ',ST_TEXTDOMAIN) ?><?php the_author() ?></a></li>
                        <li><i class="fa fa-comments"></i><a href="<?php echo get_comments_link()?>"><?php  comments_number( st_get_language('0_comment'), st_get_language('1_comment'), st_get_language('s_comment'))?></a></li>
                    </ul>
                    <!--<?php the_category(', '); ?> -->
                    <p class="thumb-desc blog-content"><?php echo st_get_the_excerpt_max_charlength(300) ?></p>
                    <a class="btn btn-default btn-ghost mt10 blog-read-more" href="<?php echo get_permalink(get_the_ID()) ?>">
                        <?php esc_html_e('Read More','traveler') ?>
                        <i class="fa fa-long-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>