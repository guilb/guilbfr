<?php
/**
 * Included by loop.php
 * It is used only on page with posts list: blog, archive, search
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

global $a13_apollo13, $post;

?>

<div class="formatter">
    <?php
    a13_post_meta();
    the_title('<h2 class="post-title"><a href="'. esc_url(get_permalink()) . '">', '</a></h2>');
    ?>
    <div class="real-content">

        <?php

        if($a13_apollo13->get_option('blog', 'excerpt_type') == 'auto'){
            if(strpos($post->post_content, '<!--more-->')){
                the_content( __( 'Read more ...', 'photon' ));
            }
            else{
                the_excerpt();
            }
        }
        //manual post cutting
        else{
            the_content( __( 'Read more ...', 'photon' ));
        }

        ?>

        <div class="clear"></div>

        <?php a13_under_post_content(); ?>
    </div>
</div>