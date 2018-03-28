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

the_post();

if(post_password_required()){
	echo get_the_content();
}
else{
	get_header();

	$theme              = $a13_apollo13->get_meta('_theme');
	$id                 = get_the_ID();
	$show_desc          = (int)( get_post_meta( $id, '_enable_desc', true) === 'on' );
	$title_color        = get_post_meta( $id, '_slide_title_bg_color', true);
	$title_color        = ($title_color === '' || $title_color === false || $title_color === 'transparent')? '' : $title_color;

	if($theme === 'bricks'){
		$column_class       = ' bricks-columns-'.get_post_meta( $id, '_brick_columns', true);
		$content_brick_width = get_post_meta( $id, '_content_brick_ratio_x', true);

		$brick_margin       = (int)get_post_meta( $id, '_brick_margin', true);//no px
		$hover_effect       = get_post_meta( $id, '_bricks_hover', true);
		$data_attr          = ' data-margin="'.esc_attr($brick_margin).'" data-hover="'.esc_attr($hover_effect).'-eff" data-desc="'.esc_attr($show_desc).'" data-title-color="'.esc_attr($title_color).'"';

		a13_title_bar();
		?>
	<article id="content" class="clearfix">
        <div class="content-limiter">
            <div id="col-mask">
                <div class="content-box">
	                <div class="bricks-frame<?php echo esc_attr($column_class); ?>">
						<?php
			            //media collection as first element
		                a13_make_media_collection();
			            ?>
		                <div id="only-album-items-here"<?php echo $data_attr; ?>>
			                <div class="grid-master"></div>
			                <?php if( $a13_apollo13->get_meta( '_album_content') === 'on'){ ?>
				            <div class="album-content w<?php echo esc_attr($content_brick_width);?>">
					            <div class="inside">
						            <?php
						            if( $a13_apollo13->get_option('album', 'album_content_categories') === 'on'){
							            echo '<div class="album-categories">'.a13_album_posted_in().'</div>';
						            }
						            if( $a13_apollo13->get_option('album', 'album_content_title') === 'on'){
							            echo '<h2 class="post-title">'.get_the_title().'</h2>';
						            }
						            ?>
					                <div class="real-content">
					                    <?php the_content(); ?>
					                    <div class="clear"></div>
						                <?php a13_album_meta_data(); ?>
					                </div>
					            </div>
				            </div>
							<?php } ?>
	                    </div>
                    </div>
                </div>
            </div>
        </div>
    </article>
		<?php
	}

	elseif($theme === 'slider'){
		$thumbs = $a13_apollo13->get_meta( '_thumbs' );
		$thumbs_on_load = $a13_apollo13->get_meta( '_thumbs_on_load' );
		$ken_scale = $a13_apollo13->get_meta( '_ken_scale' );

		$slider_opts = array(
			'autoplay '             => $a13_apollo13->get_meta( '_autoplay' ),
			'transition'            => $a13_apollo13->get_meta( '_transition' ),
			'fit_variant'           => $a13_apollo13->get_meta( '_fit_variant' ),
			'pattern'               => $a13_apollo13->get_meta( '_pattern' ),
			'gradient'              => $a13_apollo13->get_meta( '_gradient' ),
			'ken_burns_scale'       => strlen($ken_scale) ? $ken_scale : 120,
			'texts'                 => $show_desc,
			'title_color'           => $title_color,
			'transition_time'       => $a13_apollo13->get_option( 'album', 'transition_time' ),
			'slide_interval'        => $a13_apollo13->get_option( 'album', 'slide_interval' ),
			'thumbs'                => $thumbs,
			'thumbs_on_load'        => $thumbs_on_load,
		);

		//collect all options of slider in data attributes
		$data_attr = '';
		foreach( $slider_opts as $key => $val ){
			$data_attr .= ' data-'.$key.'="'.esc_attr($val).'"';
		}

		//media collection as first element
		a13_make_media_collection();
		echo '<div class="in-post-slider" id="album-slider"'.$data_attr.'></div>';
	}

    get_footer();
}
