<?php

/*
 * For getting URL of current page
 */
if(!function_exists('a13_current_url')){
    function a13_current_url(){
        global $wp;

        //no permalinks
        if($wp->request === NULL){
            $current_url = esc_url( add_query_arg( $wp->query_string, '', home_url( $wp->request ) ) );
        }
        else{
            $current_url = esc_url( trailingslashit(home_url(add_query_arg(array(),$wp->request))) );
        }

        return $current_url;
    }
}


/*
 * Filter that change default permalinks for posts and custom post types
 */
if(!function_exists('a13_custom_permalink')){
    function a13_custom_permalink($url, $post){
        $custom_link_types = array('post', A13_CUSTOM_POST_TYPE_ALBUM);
        if ( in_array($post->post_type, $custom_link_types) ) {
            $custom_url = get_post_meta($post->ID,'_alt_link', true);
            //use custom link if available
            if(strlen($custom_url)){
                return $custom_url;
            }
            return $url;
        }
        return $url;
    }
}
add_filter( 'post_link', 'a13_custom_permalink', 10, 3 );
add_filter( 'post_type_link', 'a13_custom_permalink', 10, 3 );



/*
 * Checks if current page has active sidebar
 * returns false if there is no active sidebar,
 * if there is active sidebar it returns its name
 */
if(!function_exists('a13_has_active_sidebar')){
    function a13_has_active_sidebar() {
        global $a13_apollo13;
        $test = '';
        $page_type = a13_what_page_type_is_it();
	    $shop_with_sidebar = a13_is_woocommerce_sidebar_page();


        if($shop_with_sidebar){//have to be first as special type of blog_type
	        $test = 'shop-widget-area';
        }
        elseif(a13_is_woocommerce()){
	        return false;
        }
        elseif($page_type['blog_type']){
            $test = 'blog-widget-area';
        }
        elseif(defined('A13_ALBUM_PAGE')){}
        elseif( $page_type['post'] ){
            $test = 'post-widget-area';
        }
        elseif( $page_type['page'] ){
            $test = 'page-widget-area';
            $meta_id = get_the_ID();
            $custom_sidebar = $a13_apollo13->get_meta('_sidebar_to_show', $meta_id);
            if(strlen($custom_sidebar) && $custom_sidebar !== 'default'){
                $test = $custom_sidebar;
            }

            //if has children nav and it is activated then sidebar is active
            $sidebar_meta = $a13_apollo13->get_meta('_widget_area', $meta_id);
            if(strrchr($sidebar_meta, 'nav') && a13_page_menu(true)){
                return $test;
            }
        }

        if( is_active_sidebar($test)){
            return $test;
        }
        else{
            return false;
        }
    }
}


/*
 * Get classes for body element
 */
if(!function_exists('a13_body_classes')){
    function a13_body_classes( $classes ) {
        global $a13_apollo13;

        $page_type = a13_what_page_type_is_it();

	    //hidden sidebar
	    if( is_active_sidebar( 'side-widget-area' ) ){
		    $side   = $a13_apollo13->get_option( 'appearance', 'hidden_sidebar_side' );
		    $effect = (int)$a13_apollo13->get_option( 'appearance', 'hidden_sidebar_effect' );
		    if( $side === 'right' ){
			    $effect += 6;//right side effects have number bigger by 6
		    }

		    $classes[] = 'side-menu-eff-'.$effect;
	    }

	    //basket sidebar
	    if( a13_is_woocommerce_activated() && is_active_sidebar( 'basket-widget-area' ) ){
		    $side   = $a13_apollo13->get_option( 'appearance', 'basket_sidebar_side' );
		    $effect = (int)$a13_apollo13->get_option( 'appearance', 'basket_sidebar_effect' );
		    if( $side === 'right' ){
			    $effect += 6;//right side effects have number bigger by 6
		    }

		    $classes[] = 'basket-eff-'.$effect;
	    }

        if(defined('A13_ALBUM_PAGE') && !defined( 'A13_PASSWORD_PROTECTED' ) ){
	        $classes[] = 'single-album';
        }

        if(defined('A13_ALBUMS_LIST_PAGE')){
            $classes[] = 'albums-list-page';
            $classes[] = 'cpt-list-page';
        }

        //page with posts list
        if($page_type['blog_type'] && !defined('A13_NO_RESULTS') && !$page_type['shop']){
            $classes[] = 'posts-list';
        }

        //no results page
        if(defined('A13_NO_RESULTS')){
            $classes[] = 'no-results';
        }

        //password protected
        if(defined('A13_PASSWORD_PROTECTED')){
            $classes[] = 'password-protected';
        }


        return $classes;
    }
}
add_filter( 'body_class', 'a13_body_classes' );



/*
 * Get classes for mid element
 */
if(!function_exists('a13_get_mid_classes')){
    function a13_get_mid_classes() {
        global $a13_apollo13;

        //mid classes for type of layout align and widget area display(on/off)
        $mid_classes = '';

        $page_type = a13_what_page_type_is_it();
        $page = $page_type['page'];
        $post = $page_type['post'];
        $attachment = $page_type['attachment'];
	    $shop    = $page_type['shop'];
	    $product = $page_type['product'];



        /*
         * content layout classes
         * */
        $meta_id = get_the_ID();
        //layouts that have space between content and sidebar
        $parted_layouts = array('left', 'right', 'left_padding', 'right_padding', 'center');
        //layouts that sit on one edge of screen
        $edge_layouts = array('left', 'right', 'left_padding', 'right_padding');

        $layout = 'center';

        if($attachment){
            //nothing, but we add it cause every attachment has also type of post, page or album, depending to which
            //it was attached
        }

        //albums are Full width
        elseif( $page_type['albums_list'] || $page_type['album'] ){
            $layout = 'full';
        }

        //shop
        elseif($page_type['shop'] && !$page_type['product']){
            $layout = $a13_apollo13->get_option( 'shop', 'shop_content_layout' );

	        //only on pages where list of products are displayed
	        if(is_shop() || is_product_taxonomy()){
	            $mid_classes .= ' shop-columns-'.$a13_apollo13->get_option('shop', 'product_columns');
	        }
        }
        //product
        elseif($page_type['product']){
            $layout = $a13_apollo13->get_option( 'shop', 'product_content_layout' );
        }

        //page
        elseif($page){
            $layout_option = $a13_apollo13->get_meta('_content_layout', $meta_id);
            $layout = $layout_option === 'global' ?
                $a13_apollo13->get_option( 'page', 'content_layout' )
                :
                $layout_option;
        }

        //single post
        elseif($post){
            $layout = $a13_apollo13->get_option( 'blog', 'post_content_layout' );
        }

        //blog type
        elseif($page_type['blog_type']){
            $layout = $a13_apollo13->get_option( 'blog', 'blog_content_layout' );
        }


        $mid_classes .= ' layout-'.$layout;
        if(in_array($layout, $parted_layouts)){
            $mid_classes .= ' layout-parted';
        }
        if(in_array($layout, $edge_layouts)){
            $mid_classes .= ' layout-edge';
        }
        //layouts that sit on edge of screen and have margin
        if(strpos($layout, 'padding') !== false){
            $mid_classes .= ' layout-padding';
        }



        /*
         * sidebar classes
         * */

        //check if there is active sidebar for current page
        $force_full_width = false;
        if( $page_type['cpt_list'] || //it is page, so it can gain page sidebar
            $page_type['cpt']       || //it doesn't have sidebar
            $attachment ||
            a13_has_active_sidebar() === false
        ){
            $force_full_width = true;
        }

        function __inner_a13_set_full_width(&$mid_classes){
            define('A13_NO_SIDEBARS', true); /* so we don't have to check again in sidebar.php */
            $mid_classes .= ' no-sidebars';
        }
        function __inner_a13_set_sidebar_class(&$mid_classes, $sidebar){
            if(($sidebar == 'off')){
                __inner_a13_set_full_width($mid_classes);
            }
            else{
                $mid_classes .= ' with-sidebar '.$sidebar;
            }
        }

        if($force_full_width){
            __inner_a13_set_full_width($mid_classes);
        }
        //shop type
        elseif($shop && !$product){
	        __inner_a13_set_sidebar_class($mid_classes, $a13_apollo13->get_option('shop', 'shop_sidebar'));
        }
        //product type
        elseif($product){
	        __inner_a13_set_sidebar_class($mid_classes, $a13_apollo13->get_option('shop', 'product_sidebar'));
        }
        //blog type
        elseif($page_type['blog_type']){
            __inner_a13_set_sidebar_class($mid_classes, $a13_apollo13->get_option('blog', 'blog_sidebar'));
        }
        //single post
        elseif($post){
            __inner_a13_set_sidebar_class($mid_classes,$a13_apollo13->get_option('blog', 'post_sidebar'));
        }
        //single page
        elseif($page){
            //special treatment cause of children menu option
            $sidebar = $a13_apollo13->get_meta('_widget_area', $meta_id);
            if(strrchr($sidebar, 'left')){
                $sidebar = 'left-sidebar';
            }
            elseif(strrchr($sidebar, 'right')){
                $sidebar = 'right-sidebar';
            }
            __inner_a13_set_sidebar_class($mid_classes, $sidebar);
        }

        return $mid_classes;
    }
}


/*
 * Returns array with type of current page
 */
if(!function_exists('a13_what_page_type_is_it')){
    function a13_what_page_type_is_it() {
        static $types;

        if ( empty( $types ) ) {
            $types = array(
                'page'          => is_page(),
                'album'         => defined('A13_ALBUM_PAGE'),
                'home'          => is_home(),
                'front_page'    => is_front_page(),
                'archive'       => is_archive(),
                'search'        => is_search(),
                'single'        => is_single(),
                'post'          => is_singular('post'),
                'attachment'    => is_attachment(),
                'albums_list'   => defined('A13_ALBUMS_LIST_PAGE'),
	            'shop'          => a13_is_woocommerce(),
	            'product'          => a13_is_woocommerce() && is_product(),
            );

            $types['singular_not_post']   = is_singular() && !$types['post'];
            $types['cpt']               = $types['album'];
            $types['cpt_list']          = $types['albums_list'];
            $types['blog_type']         = ($types['home'] || $types['archive'] || $types['search']) && !$types['cpt_list'];
        }

        return $types;
    }
}


/*
 * If page is empty search result or 404 it is no property page
 */
if(!function_exists('a13_is_no_property_page')){
    function a13_is_no_property_page() {
        global $post;

        return !is_object($post);
    }
}


/*
 * Adding class for compatibility with Wp-paginate plugin + infinite scroll configuration
 */
if(!function_exists('a13_next_posts_link_class')){
    function a13_next_posts_link_class() {
        return 'class="next"';
    }
}

if(!function_exists('a13_prev_posts_link_class')){
    function a13_prev_posts_link_class() {
        return 'class="prev"';
    }
}
add_filter( 'next_posts_link_attributes', 'a13_next_posts_link_class' );
add_filter( 'previous_posts_link_attributes', 'a13_prev_posts_link_class' );



/**
 * ADDING THUMBNAIL TO RSS
 */
if(!function_exists('a13_rss_post_thumbnail')){
    function a13_rss_post_thumbnail() {
        global $post;
        if(has_post_thumbnail($post->ID)) {
            $content = '<p>' . get_the_post_thumbnail($post->ID, 'medium') .
                '</p>' . get_the_excerpt();
        }
        else
            $content = get_the_excerpt();

        return $content;
    }
}
add_filter( 'the_excerpt_rss', 'a13_rss_post_thumbnail');
add_filter( 'the_content_feed', 'a13_rss_post_thumbnail');


/**
 * Return array of no AJAX pages
 */
if(!function_exists('a13_get_no_ajax_pages')) {
	function a13_get_no_ajax_pages() {
		global $a13_apollo13;

		//get pages that was disabled by admin area
		$pages = explode("\r\n", $a13_apollo13->get_option( 'advanced', 'no_ajax_links' ) );

		//get woocommerce pages
		if(a13_is_woocommerce_activated()){
			$pages[] = get_permalink(wc_get_page_id( 'cart' ));
			$pages[] = get_permalink(wc_get_page_id( 'checkout' ));
			$pages[] = get_permalink(wc_get_page_id( 'myaccount' ));
			$pages[] = get_permalink(wc_get_page_id( 'shop' ));
			$pages[] = get_permalink(wc_get_page_id( 'terms' ));
			$pages[] = wc_customer_edit_account_url();
			$pages[] = wc_get_endpoint_url( 'customer-logout', '', wc_get_page_permalink( 'myaccount' ) );

			$all_products = get_posts(array('post_type' => 'product','post_status' => 'publish', 'posts_per_page' => '-1') );
			foreach($all_products as $product) {
				$pages[] = get_permalink($product->ID);
			}
		}

        //wpml languages
        if(defined( 'ICL_SITEPRESS_VERSION')){
            $languages = apply_filters( 'wpml_active_languages', '', 'skip_missing=0&orderby=code&order=ASC' );;

            foreach($languages as $key => $language) {
                $pages[] = $language["url"];
            }
        }

		return $pages;
	}
}