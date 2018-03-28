<?php
/**
 * The Template for displaying all single posts.
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly


if(post_password_required()){
    echo get_the_content();
}
else{
    get_header();

    the_post();

    a13_title_bar();
    ?>

    <article id="content" class="clearfix">
        <div class="content-limiter">
            <div id="col-mask">

                <div id="post-<?php the_ID(); ?>" <?php post_class('content-box'); ?>>
                    <?php a13_top_image_video(); ?>
                    <div class="formatter">
                        <?php
                        a13_post_meta();
                        the_title('<h2 class="post-title">', '</h2>');
                        ?>
                        <div class="real-content">

                            <?php the_content(); ?>

                            <div class="clear"></div>

                            <?php a13_under_post_content(); ?>
                        </div>

                        <?php
                        a13_posts_navigation();

                        a13_author_info();
                        ?>

                        <?php comments_template( '', true ); ?>
                    </div>
                </div>

                <?php get_sidebar(); ?>
            </div>
        </div>
    </article>
    <?php get_footer(); ?>
<?php }//end of if password_protected ?>

