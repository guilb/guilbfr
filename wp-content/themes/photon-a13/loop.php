<?php
/**
 * The loop that displays posts.
 *
 * It is used only on page with posts list: blog, archive, search
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

global $a13_apollo13;
?>


<?php
/* If there are no posts to display, such as an empty archive page */

if ( ! have_posts() ):
    ?>
    <div class="formatter">
        <div class="real-content empty-blog">
            <?php
            echo '<p>'.__( 'Apologies, but no results were found for the requested archive.', 'photon' ).'</p>';
            get_template_part( 'no-content');
            ?>
        </div>
    </div>
    <?php

else:
    $column_class = ' bricks-columns-'.$a13_apollo13->get_option('blog', 'brick_columns');
    echo '<div class="bricks-frame'.$column_class.'"><div id="only-posts-here">';
    echo '<div class="grid-master"></div>';
    $special_post_formats = array('link', 'status', 'quote');

    while ( have_posts() ) : the_post();

        $post_classes = 'archive-item';

        //we style different when some post formats are used
        $post_format            = get_post_format();
        $is_special_post_format = (in_array($post_format, $special_post_formats));
        $post_classes           .= $is_special_post_format? ' special-post-format' : '';
        $link_it                = ($is_special_post_format || $post_format === 'chat')? false : true;

        //size of brick
        $brick_size = $a13_apollo13->get_meta('_brick_ratio_x');
        $post_classes .= strlen($brick_size)? ' w'.$brick_size : '';

        ?>
        <div id="post-<?php the_ID(); ?>" <?php post_class($post_classes); ?>>

                <?php
                if(post_password_required()){
                    ?>
                    <div class="formatter">
                        <h2 class="post-title"><a href="<?php echo esc_url(get_permalink()); ?>"><span class="fa fa-lock"></span><?php _e( 'This content is password protected', 'photon' ); ?></a></h2>
                        <div class="real-content">
                            <p><?php _e( 'To view it please enter your password below', 'photon' ); ?></p>
                            <?php echo a13_password_form(); ?>
                        </div>
		            </div>
                    <?php
                }
                else{
                    $on_image  =   $a13_apollo13->get_meta('_image_or_video')=== 'post_image'
                                   && $a13_apollo13->get_meta('_display_on_image') === 'on';
                    $has_image = has_post_thumbnail();

                    $is_on_image = $on_image && $has_image;

                    //if post is displayed as image
                    if($is_on_image){
                        $image_cover_color =  $a13_apollo13->get_meta('_image_cover_color');
                        $image_top_space = $a13_apollo13->get_meta('_image_cover_top_space');
                        $top_space_style = (int)$image_top_space > 0 ? ('padding-top:'.$image_top_space.';') : '';
                        $image = a13_get_top_image_video(false, array('return_src' =>  true));
                        $html = '<div class="on-image" style="background-image:url('.esc_url($image).');'.esc_attr($top_space_style).'">';
                        if( strlen($image_cover_color) ){
                            $html .= '<div class="on-image-cover" style="background-color: '.esc_attr($image_cover_color).'">';
                            if($link_it){
                                $html .= '<a href="'.esc_url(get_permalink()).'"></a>';
                            }
                            $html .= '</div>';
                        }

                        echo $html;
                    }
                    else{
                        a13_top_image_video($link_it);
                    }

                    get_template_part( 'content', get_post_format() );

                    if($is_on_image){
                        echo '</div>';
                    }
                }
                ?>

		</div>

    <?php endwhile;

    echo '</div></div>'; /* needed in case of masonry variant*/

endif;