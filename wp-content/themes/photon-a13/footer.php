<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

global $a13_apollo13;
?>
	</div><!-- #mid -->

<footer id="footer">
<?php
    a13_footer_items();
    a13_footer_widgets();
    ?>
</footer>
FOOTER
<?php
//hidden sidebar
if( is_active_sidebar( 'side-widget-area' ) ){
	$hidden_sb_classes = ' '.$a13_apollo13->get_option( 'appearance', 'hidden_sidebar_widgets_color' );
	$hidden_sb_classes .= ' at-'.$a13_apollo13->get_option( 'appearance', 'hidden_sidebar_side' );
?>
<nav id="side-menu" class="side-widget-menu<?php echo esc_attr($hidden_sb_classes) ?>">
	<div class="scroll-wrap">
		<?php dynamic_sidebar( 'side-widget-area' ); ?>
	</div>
	<span class="icon-cross close-sidebar"></span>
</nav>
<?php
}


//basket sidebar
if( a13_is_woocommerce_activated() && is_active_sidebar( 'basket-widget-area' ) ){
	$basket_sb_classes = ' '.$a13_apollo13->get_option( 'appearance', 'basket_sidebar_widgets_color' );
	$basket_sb_classes .= ' at-'.$a13_apollo13->get_option( 'appearance', 'basket_sidebar_side' );
?>
<nav id="basket-menu" class="side-widget-menu<?php echo esc_attr($basket_sb_classes) ?>">
	<div class="scroll-wrap2">
		<?php dynamic_sidebar( 'basket-widget-area' ); ?>
	</div>
	<span class="icon-cross close-sidebar"></span>
</nav>
<?php
}


?>
<div id="content-overlay" class="to-move"></div>

<?php
    a13_demo_switcher();


        /* Always have wp_footer() just before the closing </body>
         * tag of your theme, or you will break many plugins, which
         * generally use this hook to reference JavaScript files.
         */

        wp_footer();
?>
</body>
</html>