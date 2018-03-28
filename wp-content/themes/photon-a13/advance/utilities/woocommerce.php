<?php

//Change WC initial images sizes
if(!function_exists('a13_woocommerce_image_dimensions')){
    function a13_woocommerce_image_dimensions() {
        /**
         * Define image sizes
         */
        $catalog = array(
            'width' 	=> '320',	// px
            'height'	=> '426',	// px
            'crop'		=> 1 		// true
        );

        $single = array(
            'width' 	=> '590',	// px
            'height'	=> '810',	// px
            'crop'		=> 1 		// true
        );

        $thumbnail = array(
            'width' 	=> '140',	// px
            'height'	=> '0',	// px
            'crop'		=> 1 		// true
        );

        // Image sizes
        update_option( 'shop_catalog_image_size', $catalog ); 		// Product category thumbs
        update_option( 'shop_single_image_size', $single ); 		// Single product image
        update_option( 'shop_thumbnail_image_size', $thumbnail ); 	// Image gallery thumbs
    }
}
//overwrite image sizes only when theme is activated but ONLY on theme activation
global $pagenow;
if ( is_admin() && isset( $_GET['activated'] ) && $pagenow == 'themes.php' ){
	add_action( 'init', 'a13_woocommerce_image_dimensions', 1 );
}



/***** BREADCRUMBS ****/
//remove breadcrumbs from shop page
function a13_woocommerce_custom_breadcrumbs() {
	remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0);
	if( is_product() ){
		add_action( 'woocommerce_single_product_summary', 'woocommerce_breadcrumb', 4, 0);
	}
}
add_filter('woocommerce_before_main_content','a13_woocommerce_custom_breadcrumbs');


// Add product categories to the "Product" breadcrumb in WooCommerce.
// Get breadcrumbs on product pages that read: Home > Shop > Product category > Product Name
add_filter( 'woo_breadcrumbs_trail', 'woo_custom_breadcrumbs_trail_add_product_categories', 20 );
function woo_custom_breadcrumbs_trail_add_product_categories ( $trail ) {
	if ( ( get_post_type() === 'product' ) && is_singular() ) {
		global $post;
		$taxonomy = 'product_cat';
		$terms = get_the_terms( $post->ID, $taxonomy );
		$links = array();
		if ( $terms && ! is_wp_error( $terms ) ) {
			$count = 0;
			foreach ( $terms as $c ) {
				$count++;
//if ( $count > 1 ) { continue; }
				$parents = woo_get_term_parents( $c->term_id, $taxonomy, true, ', ', $c->name, array() );
				if ( $parents != '' && ! is_wp_error( $parents ) ) {
					$parents_arr = explode( ', ', $parents );
					foreach ( $parents_arr as $p ) {
						if ( $p != '' ) { $links[] = $p; }
					}
				}
			}
// Add the trail back on to the end.
// $links[] = $trail['trail_end'];
			$trail_end = get_the_title($post->ID);
// Add the new links, and the original trail's end, back into the trail.
			array_splice( $trail, 2, count( $trail ) - 1, $links );
			$trail['trail_end'] = $trail_end;
//remove any duplicate breadcrumbs
			$trail = array_unique($trail);
		}
	}
	return $trail;
} // End woo_custom_breadcrumbs_trail_add_product_categories()
/**
 * Retrieve term parents with separator.
 *
 * @param int $id Term ID.
 * @param string $taxonomy.
 * @param bool $link Optional, default is false. Whether to format with link.
 * @param string $separator Optional, default is '/'. How to separate terms.
 * @param bool $nicename Optional, default is false. Whether to use nice name for display.
 * @param array $visited Optional. Already linked to terms to prevent duplicates.
 * @return string
 */
if ( ! function_exists( 'woo_get_term_parents' ) ) {
	function woo_get_term_parents( $id, $taxonomy, $link = false, $separator = '/', $nicename = false, $visited = array() ) {
		$chain = '';
		$parent = &get_term( $id, $taxonomy );
		if ( is_wp_error( $parent ) )
			return $parent;
		if ( $nicename ) {
			$name = $parent->slug;
		} else {
			$name = $parent->name;
		}
		if ( $parent->parent && ( $parent->parent != $parent->term_id ) && !in_array( $parent->parent, $visited ) ) {
			$visited[] = $parent->parent;
			$chain .= woo_get_term_parents( $parent->parent, $taxonomy, $link, $separator, $nicename, $visited );
		}
		if ( $link ) {
			$chain .= '<a href="' . get_term_link( $parent, $taxonomy ) . '" title="' . esc_attr( sprintf( __( "View all posts in %s", 'photon' ), $parent->name ) ) . '">'.$parent->name.'</a>' . $separator;
		} else {
			$chain .= $name.$separator;
		}
		return $chain;
	} // End woo_get_term_parents()
}

function a13_wp_change_breadcrumb_delimiter( $defaults ) {
	// Change the breadcrumb delimeter from '/' to '>'
	$defaults['delimiter'] = '<span class="sep">/</span>';
	return $defaults;
}
add_filter( 'woocommerce_breadcrumb_defaults', 'a13_wp_change_breadcrumb_delimiter' );



//start html of WC templates
if(!function_exists('a13_woocommerce_theme_wrapper_start')){
    function a13_woocommerce_theme_wrapper_start() {
        add_filter( 'woocommerce_show_page_title', '__return_false');
        a13_title_bar();
        $post_ID = 0;

        $no_property_page = a13_is_no_property_page();
        if(!$no_property_page){ //not search page without results
            $post_ID = get_the_ID();
        }
        ?>
	    <article id="content" class="clearfix">
	        <div class="content-limiter">
	            <div id="col-mask">
	                <div class="content-box">
	                    <div class="formatter">
        <?php
    }
}
add_action('woocommerce_before_main_content', 'a13_woocommerce_theme_wrapper_start', 10);



//end html of WC templates
if(!function_exists('a13_woocommerce_theme_wrapper_end')){
    function a13_woocommerce_theme_wrapper_end() {
        ?>
                            <div class="clear"></div>
                        </div>
		            </div>
		            <?php get_sidebar(); ?>
		        </div>
			</div>
		</article>
    <?php
    }
}
add_action('woocommerce_after_main_content', 'a13_woocommerce_theme_wrapper_end', 10);



//is WC activated
if(!function_exists('a13_is_woocommerce_activated')){
    function a13_is_woocommerce_activated() {
        return class_exists( 'woocommerce' );
    }
}



//is current page one of WC
if(!function_exists('a13_is_woocommerce')){
    function a13_is_woocommerce() {
        return (a13_is_woocommerce_activated() && (is_woocommerce() || is_cart() || is_account_page() || is_checkout() || is_order_received_page()));
    }
}



//is current page one of WC pages without proper title
if(!function_exists('a13_is_woocommerce_no_title_page')){
    function a13_is_woocommerce_no_title_page() {
        return (a13_is_woocommerce_activated() && (is_shop() || is_product_category() || is_product_tag()));
    }
}



//is current page one of WC pages where sidebar is useful
if(!function_exists('a13_is_woocommerce_sidebar_page')){
    function a13_is_woocommerce_sidebar_page() {
        return (a13_is_woocommerce_activated() && is_woocommerce());
    }
}



//is current product new
if(!function_exists('a13_is_product_new')){
    function a13_is_product_new() {
        global $product;
        return is_object_in_term( a13_wc_get_product_id($product), 'product_tag', 'new' );
    }
}


function a13_wc_get_product_id($product){
	return method_exists( $product, 'get_id' ) ? $product->get_id() : $product->id;
}


//add labels above single product image
//display labels on product photo
add_filter( 'woocommerce_single_product_image_html', 'a13_single_product_labels');

if(!function_exists('a13_single_product_labels')){
    function a13_single_product_labels($html) {
        global $product;

        $html = '<div class="thumb-space">'.$html;

        //labels
        //out of stock
        if(!$product->is_in_stock()){
            $html .= '<span class="ribbon out-of-stock"><em>'.__( 'Out of stock', 'woocommerce' ).'</em></span>';
        }
        else{
            //sale
            if($product->is_on_sale()){
                $html .= '<span class="ribbon sale"><em>'.__( 'Sale!', 'woocommerce' ).'</em></span>';
            }
            //new
            if(a13_is_product_new()){
                $html .= '<span class="ribbon new"><em>'.__( 'New!', 'photon' ).'</em></span>';
            }
        }

        $html .= '</div>';

        return $html;
    }
}




// Change number or products per page
add_filter( 'loop_shop_per_page', 'a13_wc_loop_shop_per_page', 20 );

if (!function_exists('a13_wc_loop_shop_per_page')) {
	function a13_wc_loop_shop_per_page($cols) {
		global $a13_apollo13;
		return $a13_apollo13->get_option('shop', 'product_per_page');
	}
}


// Change number or products per row
add_filter('loop_shop_columns', 'a13_wc_loop_columns');

if (!function_exists('a13_wc_loop_columns')) {
	function a13_wc_loop_columns() {
		global $a13_apollo13;
		return $a13_apollo13->get_option('shop', 'product_columns');
	}
}



//overwrite WC function
function woocommerce_output_related_products() {
	global $a13_apollo13;

    $args = array(
        'posts_per_page' => 2,
        'columns'        => NULL, /* empty so template will decide */
    );
    woocommerce_related_products( $args );
}


//update cart quantity
add_filter('add_to_cart_fragments', 'a13_wc_header_add_to_cart_fragment');

function a13_wc_header_add_to_cart_fragment( $fragments )
{
	global $woocommerce;
	$number = $woocommerce->cart->cart_contents_count;
	$fragments['span#basket-items-count'] = '<span id="basket-items-count"'.($number > 0 ? '' : 'class="zero"' ).'>'.$number.'</span>';
	return $fragments;
}



//bigger thumbnails
add_filter( 'single_product_small_thumbnail_size', 'a13_wc_single_product_small_thumbnail_size', 25, 1 );

function a13_wc_single_product_small_thumbnail_size( $size ) {
	$size = 'shop_single';
	return $size;
}



//bigger avatars
add_filter( 'woocommerce_review_gravatar_size', 'a13_wc_single_product_avatars' );

function a13_wc_single_product_avatars( $size ) {
	return 90;
}



/*
 *
 * REMOVING FILTERS AND ACTIONS
 *
 * Just to make it feel good:-)
 *
 */
//tell WC how our content wraper should look
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);


//PRODUCTS LIST
//link opening and closing Since WooCommerce 2.5.0
remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );

//remove sale badge from loop
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash' );
//remove rating from loop
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
//remove add to cart from loop
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart');


//SINGLE PRODUCT
//remove sale badge
remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );





