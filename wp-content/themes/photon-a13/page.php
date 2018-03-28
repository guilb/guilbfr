<?php
/**
 * The template for displaying all pages.
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
		            <?php
		                a13_top_image_video();
		            ?>
			        <div class="formatter">
				        <?php a13_title_bar(false); ?>
			            <div class="real-content">
			                <?php the_content(); ?>
				            <div class="clear"></div>

			                <?php
			                wp_link_pages( array(
			                        'before' => '<div id="page-links">'.__( 'Pages: ', 'photon' ),
			                        'after'  => '</div>')
			                );
			                ?>
			            </div>
		            </div>
		        </div>
		        <?php get_sidebar(); ?>
		    </div>
		</div>
	</article>

	<?php get_footer(); ?>
<?php }//end of if password_protected ?>

