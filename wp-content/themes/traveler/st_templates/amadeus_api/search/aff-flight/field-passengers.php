<?php
/**
 * Created by wpbooking.
 * Developer: nasanji
 * Date: 2/6/2017
 * Version: 1.0
 */
$default=array(
    'title'=>'',
    'is_required'=>'',
    'placeholder'=> ''
);

if(isset($data)){
    extract(wp_parse_args($data,$default));
}else{
    extract($default);
}
if(!isset($field_size)) $field_size='lg';

?>
<div class="form-group form-passengers-class form-group-<?php echo esc_attr($field_size)?> amd-form-passengers">
    <label><?php echo esc_html( $title)?></label>
    <div class="amd_group_display">
        <span class="display-passengers"><span class="quantity-passengers">1</span> <?php echo esc_html__('passenger(s)', ST_TEXTDOMAIN)?></span>
        <span class="display-icon-dropdown"><i class="fa fa-chevron-down"></i></span>
    </div>
    <div class="amd-form-passengers-class none">
        <div class="twidget-passenger-form-wrapper">
            <ul class="twidget-age-group amd-passengers-class">
                <li>
                    <div class="twidget-cell twidget-age-name"><?php echo esc_html__('Adults', ST_TEXTDOMAIN); ?></div>
                    <div class="twidget-cell twidget-age-select">
                        <span class="twidget-num"><input type="number" min="0" max="9" name="adults" value="<?php echo STInput::get('adults', '1'); ?>"></span>
                    </div>
                </li>
                <li>
                    <div class="twidget-cell twidget-age-name"><?php echo wp_kses(__('Children to 12<br>years', ST_TEXTDOMAIN), array('br'=>array()))?></div>
                    <div class="twidget-cell twidget-age-select">
                        <span class="twidget-num"><input type="number" min="0" max="9" name="children" value="<?php echo STInput::get('children', '0'); ?>"></span>
                    </div>
                </li>
                <li>
                    <div class="twidget-cell twidget-age-name"><?php echo wp_kses(__('Infants to 2<br>years', ST_TEXTDOMAIN), array('br'=>array()))?></div>
                    <div class="twidget-cell twidget-age-select">
                        <span class="twidget-num"><input type="number" min="0" max="9" name="infants" value="<?php echo STInput::get('infants', '0'); ?>"></span>
                    </div>
                </li>
            </ul>
            <span class="notice none" data-maxup="<?php echo esc_html__('You may only search for up to 9 passengers at a time', ST_TEXTDOMAIN); ?>" data-maxinf="<?php echo esc_html__('The number of infants should be an integer less than or equal to the adult number.', ST_TEXTDOMAIN); ?>"></span>
        </div>
    </div>
</div>