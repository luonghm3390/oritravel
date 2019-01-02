<div class="row">
    <div class="col-md-12">
        <div class="amadeus-wrapper">
            <?php
            $default = array(
                'st_title' => '',
                'st_font_size' => '3',
                'st_number_flight' => 10
            );
            if (isset($attr)) {
                extract(wp_parse_args($attr, $default));
            } else {
                extract($default);
            }
            if (isset($st_title) && $st_title != '') {
                echo '<h'.$st_font_size.' class="title">' . esc_html($st_title) . '</h'.$st_font_size.'>';
            }
            ?>
            <div class="amadeus-content-search" <?php echo ST_Amadeus_Flight::inst()->get_data_attribute($_GET); ?>>
                <div class="overlay-form"><i class="fa fa-refresh text-color"></i></div>
                <div class="amadeus-list">
                    <div class="departure-title">
                        <i class="fa fa-fighter-jet icon-flight process-flight"></i>
                        <h4 class="title" id="depature-title"></h4>
                        <i class="fa fa-fighter-jet icon-flight"></i>
                    </div>
                    <div class="amadeus-list-result"></div>
                    <div id="load-more" class="load-more"><?php echo esc_html__('Load more', ST_TEXTDOMAIN) ?></div>
                </div>
            </div>
        </div>
    </div>
</div>