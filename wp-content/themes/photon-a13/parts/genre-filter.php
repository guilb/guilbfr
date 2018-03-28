<?php
/**
 * Displays albums genre filter
 *
 *
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

global $a13_apollo13;

$genre_template = defined('A13_GENRE_TEMPLATE');

$terms = array();

//prepare filter
$args = array(
	'hide_empty' => true,
	'parent' => 0,
);

if($genre_template === true){
	$term_slug = get_query_var('term');
	if( ! empty( $term_slug ) ) {
		$term_obj= get_term_by( 'slug', $term_slug, A13_CPT_ALBUM_TAXONOMY );
		$term_id = $term_obj->term_id;
		$args['parent'] = $term_id;
	}
}
$terms = get_terms(A13_CPT_ALBUM_TAXONOMY, $args);

if( count( $terms ) ):
    echo '<ul class="genre-filter clearfix'.'">';

    echo '<li class="label"><i class="fa fa-bars"></i>'.__( 'Filter', 'photon' ).'</li>';
    echo '<li class="selected" data-filter="__all"><a href="' . a13_current_url() . '">'.__( 'All', 'photon' ) . '</a></li>';
    foreach($terms as $term) {
        echo '<li data-filter="'.$term->term_id.'"><a href="'.esc_url(get_term_link($term)).'">' . $term->name . '</a></li>';
    }

    echo '</ul>';
endif;