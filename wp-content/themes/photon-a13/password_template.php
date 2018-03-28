<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly


global $a13_apollo13, $wp_query, $post;


//custom template
if($a13_apollo13->get_option( 'page', 'page_password_template_type' ) === 'custom' ){
	$page = $a13_apollo13->get_option( 'page', 'page_password_template' );

	//save original query
	$original_query = $wp_query;
	//reset
	$wp_query = null;

	//make query
	$wp_query = new WP_Query( array('page_id' => $page ) );

	//add password form to content
	add_filter( 'the_content', 'a13_add_password_form_to_template' );

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
	define('A13_PASSWORD_PROTECTED', true); //to get proper class in body

//	$post = get_post( $post );

	$title      = '<span class="fa fa-lock"></span>' . __( 'This content is password protected', 'photon' );
	$subtitle   = __( 'To view it please enter your password below', 'photon' );

	get_header();

	a13_title_bar( true, $title, $subtitle );

	echo a13_password_form();

	get_footer();
}