<?php
/**
 * The Template for displaying portfolio items.
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

global $a13_apollo13;
define( 'A13_ALBUM_PAGE', true );

get_header();

$id                 = get_the_ID();
$show_desc          = (int)( get_post_meta( $id, '_enable_desc', true) === 'on' );
$column_class       = ' bricks-columns-4';
$data_attr          = ' data-margin="0px" data-hover="none-eff" data-desc="off" data-title-color=""';

?>
<article id="content" class="clearfix">
    <div class="content-limiter">
        <div id="col-mask">
            <div class="content-box">
                <div class="bricks-frame<?php echo esc_attr($column_class); ?>">
					<?php
					$html = '<ul id="album-media-collection" class="screen-reader-text">';

					while ( have_posts() ) : the_post();
						//element
						$href_full = wp_get_attachment_image_src( get_the_ID(), 'full' );
						$href_medium = wp_get_attachment_image_src( get_the_ID(), 'medium' );
						$href_thumb = wp_get_attachment_image_src( get_the_ID(), 'thumbnail' );
						$html .= "\n".
						         '<li class="album-item type-image" data-ratio_x="1" data-brick_image="'.esc_url($href_medium[0]).'" data-thumb="'.esc_url($href_thumb[0]).'"><a href="'.esc_url($href_full[0]).'">'.get_the_title().'</a></li>';
					endwhile;

					$html .= '</ul>';

					echo $html;
		            ?>
	                <div id="only-album-items-here"<?php echo $data_attr; ?>>
		                <div class="grid-master"></div>
                    </div>
                </div>
	            <?php the_posts_pagination(); ?>
            </div>
        </div>
    </div>
</article>

<?php
get_footer();
?>
