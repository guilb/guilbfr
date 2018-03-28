<?php
/**
 * Cart item data (when outputting non-flat)
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version 	2.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div class="variation">
	<?php foreach ( $item_data as $data ) : ?>
		<span class="variation-<?php echo sanitize_html_class( $data['key'] ); ?>"><?php
			printf( '%1$s: %2$s', wp_kses_post( $data['key'] ), wp_kses_post( $data['display'] ) ); /* removed wpautop() from value*/
		?></span>
	<?php endforeach; ?>
</div>
