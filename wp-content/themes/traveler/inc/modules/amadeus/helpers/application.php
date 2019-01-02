<?php
/**
 * Created by wpbooking.
 * Developer: nasanji
 * Date: 6/14/2017
 * Version: 1.0
 */
if(!function_exists('st_amadeus_load_view')) {
    function st_amadeus_load_view($slug, $name = false, $data = array())
    {
        if (is_array($data))
            extract($data);

        if ($name) {
            $slug = $slug . '-' . $name;
        }

        $template_dir = 'inc/modules/amadeus/views';

        $template = locate_template($template_dir . '/' . $slug . '.php');

        if (is_file($template)) {
            ob_start();

            include $template;

            $data = @ob_get_clean();

            return $data;
        }

        return false;
    }
}