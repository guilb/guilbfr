<?php
/**
 * Plugin Name: Apollo13 Photon Post Types
 * Description: Envato(ThemeForest) require to add custom post types in separate plugin, so here you go:-)
 * Version: 1.0.0
 * Author: Apollo13
 * Author URI: http://apollo13.eu/
 * License: GPL2
 */

/*
 * Register custom post type for special use
 */
if(!function_exists('a13_photon_cpt')){
	function a13_photon_cpt(){
		global $a13_apollo13;
		
		//need to add it to settings
		$is_hierarchical = false;

		$album_type = defined('A13_CUSTOM_POST_TYPE_ALBUM') ? A13_CUSTOM_POST_TYPE_ALBUM : 'album';
		$album_slug = defined('A13_CUSTOM_POST_TYPE_ALBUM_SLUG') ? A13_CUSTOM_POST_TYPE_ALBUM_SLUG : 'album';
		$album_tax  = defined('A13_CPT_ALBUM_TAXONOMY') ? A13_CPT_ALBUM_TAXONOMY : 'genre';

		$labels = array(
			'name' =>            __( 'Albums', 'a13_photon_cpt' ),
			'singular_name' =>   __( 'Album', 'a13_photon_cpt' ),
			'add_new' =>         __( 'Add New', 'a13_photon_cpt' ),
			'add_new_item' =>    __( 'Add New Album', 'a13_photon_cpt' ),
			'edit_item' =>       __( 'Edit Album', 'a13_photon_cpt' ),
			'new_item' =>        __( 'New Album', 'a13_photon_cpt' ),
			'view_item' =>       __( 'View Album', 'a13_photon_cpt' ),
			'search_items' =>    __( 'Search Albums', 'a13_photon_cpt' ),
			'not_found' =>       __( 'Nothing found', 'a13_photon_cpt' ),
			'not_found_in_trash' =>  __( 'Nothing found in Trash', 'a13_photon_cpt' ),
			'parent_item_colon' => ''
		);

		$supports = array( 'title','thumbnail','editor' );
		if(isset($apollo13)) {
			if ( $a13_apollo13->get_option( 'album', 'comments' ) == 'on' ) {
				array_push( $supports, 'comments' );
			}
		}

		$args = array(
			'labels' => $labels,
			'public' => true,
			'query_var' => true,
			//'has_archive' => true, //will make that yoursite.com/album/ will work as list of all albums with pagination
			'menu_position' => 5,
			'rewrite' =>  array('slug' => $album_slug),

		);

		if($is_hierarchical){
			$args['hierarchical'] = true;
			array_push($supports, 'page-attributes');
		}

		$args['supports'] = $supports;
		//register albums
		register_post_type( $album_type , $args );



		$genre_labels = array(
			'name'                       => __( 'Album Categories', 'a13_photon_cpt' ),
			'singular_name'              => __( 'Album Category', 'a13_photon_cpt' ),
			'search_items'               => __( 'Search Album Categories', 'a13_photon_cpt' ),
			'popular_items'              => __( 'Popular Album Categories', 'a13_photon_cpt' ),
			'all_items'                  => __( 'All Album Categories', 'a13_photon_cpt' ),
			'parent_item'                => __( 'Parent Album Category', 'a13_photon_cpt' ),
			'parent_item_colon'          => __( 'Parent Album Category:', 'a13_photon_cpt' ),
			'edit_item'                  => __( 'Edit Album Category', 'a13_photon_cpt' ),
			'update_item'                => __( 'Update Album Category', 'a13_photon_cpt' ),
			'add_new_item'               => __( 'Add New Album Category', 'a13_photon_cpt' ),
			'new_item_name'              => __( 'New Album Category Name', 'a13_photon_cpt' ),
			'menu_name'                  => __( 'Categories', 'a13_photon_cpt' ),
			'separate_items_with_commas' => __( 'Separate items with commas', 'a13_photon_cpt' ),
			'add_or_remove_items'        => __( 'Add or remove items', 'a13_photon_cpt' ),
			'choose_from_most_used'      => __( 'Choose from the most used items', 'a13_photon_cpt' ),
			'not_found'                  => __( 'Not Found', 'a13_photon_cpt' ),
		);

		register_taxonomy($album_tax, array($album_type),
			array(
				"hierarchical" => true,
				"label" =>  __( 'Albums Genres', 'a13_photon_cpt' ),
				"labels" => $genre_labels,
				"rewrite" => array(
//	                'slug' => 'category',
					'hierarchical' => true
				),
				'show_admin_column' => true
			)
		);
	}
}

add_action( 'init', 'a13_photon_cpt' );
