<?php
wp_enqueue_script( 'bootstrap-datepicker.js' ); wp_enqueue_script( 'bootstrap-datepicker-lang.js' );
wp_enqueue_script('st.amadeus');
$default=array(
    'st_style_search' =>'style_1',
    'st_direction'=>'horizontal',
    'st_box_shadow'=>'no',
    'st_search_tabs'=>'yes',
    'st_title_search'=>'',
    'field_size'    =>''
);

if(isset($data)){
    extract($data=wp_parse_args($data,$default));
}else{
    extract($data=$default);
}
?>
<div class="amadeus-search-box search-tabs search-tabs-bg <?php if($st_box_shadow=='no') echo 'no-boder-search'; else echo 'booking-item-dates-change'; ?>">
    <div class="tabbable">

        <div class="tab-content">
            <?php
            $fields = array(
                array(
                    'title' => esc_html__('Origin', ST_TEXTDOMAIN),
                    'name' => 'origin',
                    'placeholder' => esc_html__('Origin', ST_TEXTDOMAIN),
                    'layout_col' => '3',
                    'layout2_col' => '3',
                    'is_required' => 'on'
                ),
                array(
                    'title' => esc_html__('Destination', ST_TEXTDOMAIN),
                    'name' => 'destination',
                    'placeholder' => esc_html__('Destination', ST_TEXTDOMAIN),
                    'layout_col' => '3',
                    'layout2_col' => '3',
                    'is_required' => 'on'
                ),
                array(
                    'title' => esc_html__('Depart date', ST_TEXTDOMAIN),
                    'name' => 'depart',
                    'placeholder' => esc_html__('Depart date', ST_TEXTDOMAIN),
                    'layout_col' => '2',
                    'layout2_col' => '2',
                    'is_required' => 'on'
                ),
                array(
                    'title' => esc_html__('Return date', ST_TEXTDOMAIN),
                    'name' => 'return',
                    'placeholder' => esc_html__('Return date', ST_TEXTDOMAIN),
                    'layout_col' => '2',
                    'layout2_col' => '2',
                    'is_required' => 'off'
                ),
                array(
                    'title' => esc_html__('Passengers', ST_TEXTDOMAIN),
                    'name' => 'passengers',
                    'placeholder' => esc_html__('Passengers', ST_TEXTDOMAIN),
                    'layout_col' => '2',
                    'layout2_col' => '2',
                    'is_required' => 'off'
                )
            );
            $st_direction = !empty($st_direction) ? $st_direction : "horizontal";
            if (!isset($field_size)) $field_size = '';
            $id_page = st()->get_option('amadeus_aff_flight_search_result_page');
            if(!empty($id_page)){
                $link_action = get_the_permalink($id_page);
            }else{
                $link_action = home_url( '/' );
            }
            ?>
            <h2 class='mb20 title'><?php echo esc_html($st_title_search) ?></h2>
            <form role="search" method="get" class="search main-search" autocomplete="off" action="<?php echo esc_url($link_action); ?>">
                <div class="row">
                    <?php
                    if (!empty($fields)) {
                        foreach ($fields as $key => $value) {
                            $default = array(
                                'placeholder' => ''
                            );
                            $value = wp_parse_args($value, $default);
                            $name = $value['name'];

                            $size = '4';
                            if ($st_style_search == "style_1") {
                                $size = $value['layout_col'];
                            } else {
                                if (!empty($value['layout2_col'])) {
                                    $size = $value['layout2_col'];
                                }
                            }

                            if ($st_direction == 'vertical') {
                                $size = '12';
                            }
                            $size_class = " col-md-" . $size . " col-lg-" . $size . " col-sm-12 col-xs-12 ";
                            ?>
                            <div class="<?php echo esc_attr($size_class); ?>">
                                <?php echo st()->load_template('amadeus_api/search/aff-flight/field-' . $name, false, array('data' => $value, 'field_size' => $field_size, 'location_from' => 'a1', 'location_to' => 'a2', 'placeholder' => $value['placeholder'], 'st_direction' => $st_direction, 'is_required' => $value['is_required'])) ?>
                            </div>
                            <?php
                        }
                    } ?>
                </div>
                <button class="btn btn-primary btn-lg btn-amd-search-flight" type="submit"><?php echo esc_html__('Search For Flights', ST_TEXTDOMAIN); ?></button>
            </form>
        </div>
    </div>
</div>