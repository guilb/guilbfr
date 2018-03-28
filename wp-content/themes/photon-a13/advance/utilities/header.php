<?php
/*
 * Gets logo image and check for HIGH DPI cookie
 */
if(!function_exists('a13_header_logo')){
    function a13_header_logo() {
        global $a13_apollo13;
        $img_logo = $a13_apollo13->get_option( 'appearance', 'logo_type' ) === 'image';
        $html = '<a class="logo'.esc_attr($img_logo? '' : ' text-logo').'" href="'.esc_url( home_url( '/' ) ).'" title="'.esc_attr( get_bloginfo( 'name', 'display' ) ).'" rel="home">';
        if($img_logo){
            $src = $a13_apollo13->get_option( 'appearance', 'logo_image' );
            $html .= '<img src="'.esc_url($src).'" alt="'. esc_attr( get_bloginfo( 'name', 'display' ) ).'" />';
        }
        else{
            $html .= $a13_apollo13->get_option( 'appearance', 'logo_text' );
        }

        $html .= '</a>';

        echo $html;
    }
}


/*
 * Header search form
 */
if(!function_exists('a13_header_search')){
    function a13_header_search() {
        return
            '<div class="search-container">'.
            '<div class="search">'.
            '<span class="icon-search open tool"></span>'.
            get_search_form(false).
            '<span class="icon-cross close tool"></span>'.
            '</div>'.
            '</div>';
    }
}




/*
 * Displays header menu
 */
if(!function_exists('a13_header_menu')){
    function a13_header_menu(){
        /* Our navigation menu.  If one isn't filled out, wp_nav_menu falls back to wp_page_menu.
         * The menu assigned to the primary position is the one used.
         * If none is assigned, the menu with the lowest ID is used.
         */
        ?>
        <div class="menu-container">
        <?php
        if ( has_nav_menu( 'header-menu' ) ):
            wp_nav_menu( array(
                    'container'       => false,
                    'link_before'     => '<span>',
                    'link_after'      => '</span>',
                    'menu_class'      => 'top-menu',
                    'theme_location'  => 'header-menu',
                    'walker'          => new A13_menu_walker)
            );
        else:
            echo '<ul class="top-menu">';
            wp_list_pages(
                array(
                    'link_before'     => '<span>',
                    'link_after'      => '</span>',
                    'title_li' 		  => ''
                )
            );
            echo '</ul>';
        endif;
        ?>
        </div>
    <?php
    }
}


if(!function_exists('a13_get_header_toolbar')) {
	function a13_get_header_toolbar(&$icons) {
		global $a13_apollo13, $woocommerce;

		$classes = '';
        $header_search  = $a13_apollo13->get_option( 'appearance', 'header_search' ) === 'on';
		$hidden_sidebar = is_active_sidebar( 'side-widget-area' );
		$basket_sidebar = a13_is_woocommerce_activated() && is_active_sidebar( 'basket-widget-area' );
		$icons = 4;

		if($header_search){
			$classes .= ' with-search';
		}
		else{
			$icons--;
		}

		if(!$hidden_sidebar){
			$icons--;
		}

		if(!$basket_sidebar){
			$icons--;
		}

		$html = '
			<div id="header-tools" class="to-move'.$classes.'">
				<div id="fs-switch" class="icon-expand tool highlight" title="'.esc_attr(__('Full screen', 'photon')).'"></div>'.
		        '<div id="mobile-menu-opener" class="icon-menu tool highlight"  title="'.esc_attr(__('Main menu', 'photon')).'"></div>'.
                ($basket_sidebar? '<div id="basket-menu-switch" class="icon-bag tool" title="'.esc_attr(__('Shop sidebar', 'photon')).'"><span id="basket-items-count" class="zero">'.esc_html($woocommerce->cart->cart_contents_count).'</span></div>' : '').
                ($hidden_sidebar? '<div id="side-menu-switch" class="icon-menu tool" title="'.esc_attr(__('Hidden sidebar', 'photon')).'"></div>' : '').
		        ($header_search? a13_header_search() : '').
			'</div>';

		return $html;
	}
}


/*
 * Displays whole header
 */
if(!function_exists('a13_theme_header')){
    function a13_theme_header(){
        global $a13_apollo13;

        $variant        = $a13_apollo13->get_option( 'appearance', 'header_variant' );  //left or right

        //message indicator
        $msg_cookie_string          = $a13_apollo13->get_option( 'appearance', 'footer_msg_new' );
		$is_cookie_for_msg_set      = isset($_COOKIE["a13_footer_msg_".$msg_cookie_string]);
        $footer_message             = $a13_apollo13->get_option( 'appearance', 'footer_msg' ) === 'on';
		$indicator_show             = $footer_message && $a13_apollo13->get_option( 'appearance', 'footer_msg_indicator' ) === '1';

        $icons_no       = 0;
	    $header_tools   = a13_get_header_toolbar($icons_no);
        $header_classes = '';

        $header_classes .= 'to-move header-variant-'.$variant;
	    if(!$icons_no){
            $header_classes .= ' no-tools';
	    }
	    else{
            $header_classes .= ' tools-icons-'.$icons_no; //number of icons
	    }
?>
    <header id="header" class="<?php echo esc_attr($header_classes); ?>">
        <div class="head">
            <div class="logo-container"><?php a13_header_logo(); ?></div>

            <nav id="access" role="navigation" class="navigation-bar">
                <?php a13_header_menu(); ?>
                <?php
                    if($indicator_show){
                        echo '<div id="footer-msg-indicator"'.($is_cookie_for_msg_set? '' : ' class="new"').'>i</div>';
                    }
                ?>
            </nav><!-- #access -->
        </div>
    </header>
    <?php echo $header_tools; ?>
    <?php
    }
}
