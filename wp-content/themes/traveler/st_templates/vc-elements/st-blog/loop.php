<?php
if (!$st_blog_style){ $st_blog_style = 4; }
$st_blog_style = ((int) $st_blog_style <=0)? 1: (int) $st_blog_style;
 $col = 12 / $st_blog_style;
?>
<div class="col-md-<?php echo esc_attr($col) ?> col-sm-6 col-xs-12">
    <div class="thumb text-center blog-grid-layout">
        <header class="thumb-header">
            <a class="hover-img curved" href="<?php the_permalink() ?>">
                <?php
                $img = get_the_post_thumbnail( get_the_ID() , array(800,600), array('alt' => TravelHelper::get_alt_image(get_post_thumbnail_id( )))) ;
                if(!empty($img)){
                    echo balanceTags($img);
                }else{
                    echo '<img width="800" height="600" alt="no-image" class="wp-post-image" src="'.ST_TRAVELER_URI.'/img/no-image.png">';
                }
                ?>

             
            </a>
            <?php echo '<div class="list-cate">'; the_category(', '); echo '</div>'; ?>    
        </header>
        <ul class="blog-meta">
            <li><i class="fa fa-calendar"></i><?php the_time(get_option('date_format').' ')?></li>
            <li><i class="fa fa-user"></i><a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><?php the_author() ?></a> </li>
        </ul>
        <h5><?php the_title() ?></h5>
        <div class="thumb-caption text-center">
            <!--<p class="thumb-desc"><?php echo get_the_excerpt(); ?></p> -->
            <a class="btn btn-default btn-ghost mt10 blog-read-more" href="<?php echo get_permalink(get_the_ID()) ?>">
                <?php esc_html_e('Read More','traveler') ?>
                <i class="fa fa-long-arrow-right"></i>
            </a>
        </div>
    </div>
</div>