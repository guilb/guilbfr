<?php
/*
 * Prints favicon
 */
if(!function_exists('a13_favicon')){
    function a13_favicon() {
        global $a13_apollo13;
        
        $wp_site_icon = get_option( 'site_icon' );
        if(strlen($wp_site_icon) && $wp_site_icon !== '0'){
            //site uses build in WordPress site icon function
            return;
        }
        
        $fav_icon = $a13_apollo13->get_option( 'appearance', 'favicon' );
        if(!empty($fav_icon))
            echo '<link rel="shortcut icon" href="'.esc_url($fav_icon).'" />';
    }
}