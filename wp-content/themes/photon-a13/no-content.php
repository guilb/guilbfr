<?php
/**
 * Used in empty archives and no search results page
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

    global $wp_query, $post, $a13_apollo13;
?>

<p><span class="info-404"><?php printf( __('<a href="%1$s">Go back</a> or use Site Map below:', 'photon' ), 'javascript:history.go(-1)' ); ?></span></p>

<div class="left50">
    <?php
    if ( has_nav_menu( 'header-menu' ) ){
        echo '<h3>'.__( 'Main navigation', 'photon' ).'</h3>';
        wp_nav_menu( array(
                'container'       => false,
                'link_before'     => '',
                'link_after'      => '',
                'menu_class'      => 'styled in-site-map',
                'theme_location'  => 'header-menu' )
        );
    }
    ?>

    <h3><?php _e( 'Categories', 'photon' ); ?></h3>
    <ul class="styled">
        <?php wp_list_categories('title_li='); ?>
    </ul>
</div>

<div class="right50">
    <h3><?php _e( 'Pages', 'photon' ); ?></h3>
    <ul class="styled">
        <?php wp_list_pages('title_li='); ?>
    </ul>
    <?php
        /* List albums */
        $original_query = $wp_query;
        $original_post = $post;

        $args = array(
            'posts_per_page'      => -1,
            'offset'              => -1,
            'post_type'           => A13_CUSTOM_POST_TYPE_ALBUM,
            'post_status'         => 'publish',
            'ignore_sticky_posts' => true,
        );

        //make query for albums
        $wp_query = new WP_Query( $args );

        if ($wp_query->have_posts()) :
            echo '<h3>'.__( 'Albums', 'photon' ).'</h3>';
            echo '<ul class="styled">';

            while ( have_posts() ) :
                the_post();
                echo '<li><a href="'. get_permalink() . '">' . get_the_title() . '</a></li>';
            endwhile;

            echo '</ul>';
        endif;

        //restore previous query
        $wp_query = $original_query;
        $post = $original_post;
        wp_reset_postdata();
    ?>
</div>

<div class="clear"></div>