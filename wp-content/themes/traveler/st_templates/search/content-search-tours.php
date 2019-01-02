<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * Content search tours
 *
 * Created by ShineTheme
 *
 */
if(!class_exists('STTour')) return;
$tours=new STTour();
$fields=$tours->get_search_fields();
if(!isset($field_size)) $field_size='';
?>

<h2 class='mb20'><?php echo esc_html($st_title_search) ?></h2>
<?php $id_page = st()->get_option('tours_search_result_page');
if(!empty($id_page)){
    $link_action = get_the_permalink($id_page);
}else{
    $link_action = home_url( '/' );
}
$st_direction = !empty($st_direction)? $st_direction :  "horizontal";
?>
    <form role="search" method="get" class="search main-search" action="<?php echo esc_url($link_action) ?>">
        <?php if(empty($id_page)): ?>
        <input type="hidden" name="post_type" value="st_tours">
        <input type="hidden" name="s" value="">
        <?php endif ?>
        <?php echo TravelHelper::get_input_multilingual_wpml() ?>
        <div class="row search-form">
            <?php
            if(!empty($fields))
            { 
                foreach($fields as $key=>$value)
                {
                    $default=array(
                        'placeholder'=>''
                    );
                    $value=wp_parse_args($value,$default);
                    $name=$value['tours_field_search'];
                    if( $name == 'google_map_location' ){
                        $name = 'address';
                    }
                    $size='4';
                    if($st_style_search=="style_1")
                    {
                        $size=$value['layout_col'];
                    }else
                    {
                        if($value['layout2_col'])
                        {
                            $size=$value['layout2_col'];
                        }
                    }
                    if($st_direction=='vertical'){
                        $size='12';
                    }
                    $size_class = " col-md-".$size." col-lg-".$size. " col-sm-12 col-xs-12 " ;
                    ?>
                    <div class="<?php echo esc_attr($size_class); ?>">
                        <?php 
                        echo st()->load_template('tours/elements/search/field',$name,array('data'=>$value,'field_size'=>$field_size,'location_name'=>'location_name','placeholder'=>$value['placeholder'],'st_direction'=>$st_direction)) ?>
                    </div>
                    
                <?php 
                }
            }?>
            <button class="btn btn-primary btn-lg btn-search-tour" type="submit"><?php st_the_language('search_for_tour')?></button>
        </div>
    </form>
