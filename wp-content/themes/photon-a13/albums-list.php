<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly
define( 'A13_ALBUMS_LIST_PAGE', true );

$ajax_call = false;
if(isset($_GET['a13-ajax-get'])){
    $ajax_call = true;
}

if(!$ajax_call) {
	get_header(); //so menu will highlight proper
}

/**
 * The loop that displays albums.
 *
 */

global $wp_query, $a13_apollo13;

//settings
$genre_template     = defined('A13_GENRE_TEMPLATE');
$main_template      = !$genre_template;
$filter             = true;
//$filter             = $a13_apollo13->get_option('album', 'categories_filter') === 'on';

$original_query = $wp_query;
$offset = 0;
$paged = get_query_var('page') === '' ? get_query_var('paged') : get_query_var('page');
$per_page = get_option( 'posts_per_page' );

$args = array(
    'posts_per_page'      => $per_page,
    'paged'               => $paged,
    'post_type'           => A13_CUSTOM_POST_TYPE_ALBUM,
    'post_status'         => 'publish',
    'ignore_sticky_posts' => true,
    'meta_query' => array(
        'relation' => 'OR',
        //new albums
        array(
            'key'     => '_exclude_in_albums_list',
            'value'   => 'off',
            'compare' => '=',
        ),
        //not updated albums
        array(
            'key'     => '_exclude_in_albums_list',
            'compare' => 'NOT EXISTS',
        ),
    ),
);

if(!$ajax_call){
    $title = get_the_title( $a13_apollo13->get_option( 'album', 'albums_list_page' ) );

    if($genre_template === true){
        $term_slug = get_query_var('term');
        if( ! empty( $term_slug ) ){
            $args[A13_CPT_ALBUM_TAXONOMY] = $term_slug;
            $term_obj = get_term_by( 'slug', $term_slug, A13_CPT_ALBUM_TAXONOMY);
            $title = sprintf( __('%1$s : %2$s', 'photon' ), $title, $term_obj->name );
        }
    }
}

if($main_template){
    //make query for albums
    $wp_query = new WP_Query( $args );
}
if(!$ajax_call){
    $column_class = ' bricks-columns-'.$a13_apollo13->get_option('album', 'brick_columns');

    a13_title_bar(true, $title);
    ?>
    <article id="content" class="clearfix">
        <div class="content-limiter">
            <div id="col-mask">
                <div class="content-box">
                    <?php
                    //filter
                    if($filter){
                        get_template_part( 'parts/genre-filter' );
                    }
                    ?>
                    <div class="bricks-frame<?php echo esc_attr($column_class); ?>">
                        <div id="only-albums-here">
                            <div class="grid-master"></div>

        <?php
}

/* If there are no posts to display, such as an empty archive page */
if ( ! have_posts() ) :


/* If there ARE some posts */
elseif ($wp_query->have_posts()) :
    while ( have_posts() ) :
        the_post();
        $post_id        = get_the_ID();
        $href           = get_permalink();

        //get album genres
        $terms = wp_get_post_terms($post_id, A13_CPT_ALBUM_TAXONOMY, array("fields" => "all"));
        $pre = 'data-genre-';
        $suf = '="1" ';
        $genre_string = '';
        $effect = $a13_apollo13->get_option('album', 'albums_list_bricks_hover').'-eff';
        $album_classes = 'archive-item '.$effect;

        //get all genres that item belongs to
        if( count( $terms ) ){
            foreach($terms as $term) {
                $genre_string .= $pre.$term->term_id.$suf;
            }
        }

        //size of brick
        $brick_size = $a13_apollo13->get_meta('_brick_ratio_x');
        $album_classes .= strlen($brick_size)? ' w'.$brick_size : '';
    ?>
        <figure <?php echo 'class="'.$album_classes.'" '.$genre_string.' id="album-'.$post_id.'"'; ?>>
            <?php echo a13_make_album_image($post_id); ?>
            <figcaption>
                <div class="center_group">
                <?php
	                if(post_password_required()){
                    ?>
                    <h2 class="post-title"><span class="fa fa-lock"></span><?php _e( 'This content is password protected', 'photon' ); ?></h2>
	                <div class="excerpt">
		                <p><?php _e( 'Click and enter your password to view content', 'photon' ); ?></p>
	                </div>
                    <?php
				    }
				    else{
					    //return taxonomy for albums
					    if( $a13_apollo13->get_option('album', 'album_categories') === 'on'){
                            echo '<div class="album-categories">'.a13_album_posted_in().'</div>';
					    }

					    //title
					    $title_color = $a13_apollo13->get_meta('_title_bg_color');
					    if($title_color === '' || $title_color === false || $title_color === 'transparent'){
					        //no color
						    the_title('<h2 class="post-title">', '</h2>');
					    }
					    else{
						    the_title('<h2 class="post-title"><span style="background-color:'.$title_color.'">', '</span></h2>');
					    }
				    ?>
                    <div class="excerpt">
                        <?php echo wp_html_excerpt( get_the_content(), 150, '...' ); ?>
                    </div>
				    <?php
				    }
					?>
                </div>
                <a href="<?php echo esc_url($href); ?>"></a>
            </figcaption>
        </figure>
    <?php
    endwhile;
endif;

if(!$ajax_call){ ?>

                        </div>
                    </div>
                    <div class="clear"></div>

                    <?php
                        the_posts_pagination();
                        if($main_template){

                            //restore previous query
                            $wp_query = $original_query;
                            wp_reset_postdata();
                        }
                    ?>

                </div>
            </div>
        </div>
    </article>

    <?php
    get_footer();
}
else{
    //send also current pagination when ajax call
    the_posts_pagination();
}
