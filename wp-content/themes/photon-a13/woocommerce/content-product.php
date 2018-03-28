<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see     http://docs.woothemes.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product, $woocommerce_loop;

// Ensure visibility
if ( ! $product || ! $product->is_visible() )
	return;

?>
<li <?php post_class(); ?>>

	<?php
	/**
	 * woocommerce_before_shop_loop_item hook.
	 *
	 * @hooked woocommerce_template_loop_product_link_open - 10
	 */
	do_action( 'woocommerce_before_shop_loop_item' );

	//thumbnail
	echo '
    <div class="thumb-space">
        <a class="thumb" href="'.get_permalink().'">';

	//main thumb
	$img_size = 'shop_catalog';

	echo woocommerce_get_product_thumbnail($img_size);

	//second thumb
	$attachment_ids = $product->get_gallery_image_ids();
	if ( $attachment_ids ) {
		$image = wp_get_attachment_image( $attachment_ids[0], $img_size );
		if ( strlen( $image )){
			echo '<span class="sec-img">'.$image.'</span>';
		}
	}

	//name categories and price
	echo '<div class="product-meta">
			<div>';
			//categories
			$terms = get_the_terms( a13_wc_get_product_id($product), 'product_cat' );
			if( sizeof( $terms ) ){
				echo '<span class="posted_in">';

				$temp = 1;
				foreach ( $terms as $term ) {
					if($temp > 1){
						echo '<span class="sep">/</span>';
					}
					echo esc_html($term->name);
					$temp++;
				}

				echo '</span>';
			}

			//product name
			echo '<span class="product_name">'.get_the_title().'</span>';

			//price
			woocommerce_template_loop_price();
	echo '	</div>
		</div>';

	//labels
	//in stock
	if(!$product->is_in_stock()){
		echo '<span class="ribbon out-of-stock"><em>'.__( 'Out of stock', 'woocommerce' ).'</em></span>';
	}
	else{
		//sale
		if($product->is_on_sale()){
			echo '<span class="ribbon sale"><em>'.__( 'Sale!', 'woocommerce' ).'</em></span>';
		}
		//new
		if(a13_is_product_new()){
			echo '<span class="ribbon new"><em>'.__( 'New!', 'photon' ).'</em></span>';
		}
	}

	echo '</a>';

	//add to cart
	woocommerce_template_loop_add_to_cart();


	/**
	 * woocommerce_after_shop_loop_item hook.
	 *
	 * @hooked woocommerce_template_loop_product_link_close - 5
	 * @hooked woocommerce_template_loop_add_to_cart - 10
	 */
	do_action( 'woocommerce_after_shop_loop_item' );
	?>

</li>
