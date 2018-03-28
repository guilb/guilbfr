<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * In this theme we use it as home.php, archive.php and search.php to reduce number of templates
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

$title =  '';

//Lets decide what is the title
if(is_search()){
	/* Search Count */
	$all_search = new WP_Query("s=$s&showposts=-1");
	$count = $all_search->post_count;
	//here was wp_reset_query(); but it looks like it was useless.
	$title = sprintf( __( '%1$d Search results for "%2$s"', 'photon' ), $count, get_search_query() );
}
elseif(is_archive()){
	if ( is_author() )
		$title = sprintf( __( 'Author Archives: %s', 'photon' ), "<span class='vcard'>" . get_the_author() . "</span>" );
	elseif ( is_category() )
		$title = sprintf( __( 'Category Archives: %s', 'photon' ), '<span>' . single_cat_title( '', false ) . '</span>' );
	elseif ( is_tag() )
		$title = sprintf( __( 'Tag Archives: %s', 'photon' ), '<span>' . single_tag_title( '', false ) . '</span>' );
	elseif ( is_day() )
		$title = sprintf( __( 'Daily Archives: %s', 'photon' ), '<span>' . get_the_date() . '</span>' );
	elseif ( is_month() )
		$title = sprintf( __( 'Monthly Archives: %s', 'photon' ), '<span>' . get_the_date( 'F Y' ) . '</span>' );
	elseif ( is_year() )
		$title = sprintf( __( 'Yearly Archives: %s', 'photon' ), '<span>' . get_the_date( 'Y' ) . '</span>' );
	else
		$title = __( 'Blog Archives', 'photon' );
}


//lets print that damn layout
get_header();

a13_title_bar(true, $title); ?>

<article id="content" class="clearfix">
	<div class="content-limiter">
		<div id="col-mask">
			<div class="content-box">

				<?php get_template_part( 'loop' ); ?>

				<div class="clear"></div>

				<?php the_posts_pagination(); ?>

			</div>
			<?php get_sidebar(); ?>
		</div>
	</div>
</article>

<?php get_footer(); ?>