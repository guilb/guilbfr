<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly



global $a13_apollo13, $wp_query;

//custom template
$custom_404_page = $a13_apollo13->get_option( 'page', 'page_404_template' );

if($a13_apollo13->get_option( 'page', 'page_404_template_type' ) === 'custom' && $custom_404_page !== ''&& $custom_404_page !== '0'){
	//save original query
	$original_query = $wp_query;
	//reset
	$wp_query = null;

	//make query
	$wp_query = new WP_Query( array('page_id' => $custom_404_page ) );

	a13_page_like_content();

	//return old query
	$wp_query = null;
	$wp_query = $original_query;

	// Reset Post Data
	wp_reset_postdata();

	return;
}

//default template
else{
	define( 'A13_NO_RESULTS', true );
	get_header();

	$title = '<span style="display:block; font-size:2em;">404</span>'.__('The page you are looking for can\'t be found!', 'photon');
	$subtitle = sprintf(
					__( 'Go to our <a href="%1$s">home page</a> or go back to <a href="%2$s">previous page</a>', 'photon'  ),
					esc_url( home_url( '/' ) ),
					'javascript:history.go(-1)'
				);

	a13_title_bar( true, $title, $subtitle );

	get_footer();
}
