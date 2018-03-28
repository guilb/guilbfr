<?php
/**
 * The template for displaying attachments.
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

the_post();

get_header();

a13_title_bar();
?>

<article id="content" class="clearfix">
    <div class="content-limiter">
        <div id="col-mask">

            <div id="post-<?php the_ID(); ?>" <?php post_class('content-box'); ?>>
                <div class="formatter">
                    <div class="real-content">

                        <?php
                        if ( wp_attachment_is_image() ){
                            echo '<p class="attachment">'.wp_get_attachment_image( get_the_ID(), 'large' ).'</p>';
                        }
                        else{
                            echo prepend_attachment('');
                            the_content();
                        }
                        ?>


                        <div class="attachment-info">
                            <?php if ( ! empty( $post->post_parent ) ) : ?>
                            <span><a href="<?php echo esc_url(get_permalink( $post->post_parent )); ?>" title="<?php esc_attr( sprintf( __( 'Return to %s', 'photon' ), get_the_title( $post->post_parent ) ) ); ?>" rel="gallery"><?php
                                /* translators: %s - title of parent post */
                                printf(  __( 'Return to %s', 'photon' ), get_the_title( $post->post_parent ) );
                                ?></a></span>
                            <?php endif; ?>

                            <span><?php
                                printf(  __( 'By %1$s', 'photon' ),
                                    sprintf( '<a class="author" href="%1$s" title="%2$s" rel="author">%3$s</a>',
                                        esc_url(get_author_posts_url( get_the_author_meta( 'ID' ) )),
                                        sprintf( esc_attr( __('View all posts by %s', 'photon' ) ), get_the_author() ),
                                        get_the_author()
                                    )
                                );
                                ?></span>

                            <?php
                            printf( __( '<span>Published %1$s</span>', 'photon' ),
                                sprintf( '<abbr class="published" title="%1$s">%2$s</abbr>',
                                    esc_attr( get_the_time() ),
                                    get_the_date()
                                )
                            );
                            if ( wp_attachment_is_image() ) {
                                $metadata = wp_get_attachment_metadata();
                                echo ' <span>';
                                printf( __( 'Full size is %s pixels', 'photon' ),
                                    sprintf( '<a href="%1$s" title="%2$s">%3$s &times; %4$s</a>',
                                        esc_url(wp_get_attachment_url()),
                                        esc_attr( __( 'Link to full-size image', 'photon' ) ),
                                        $metadata['width'],
                                        $metadata['height']
                                    )
                                );
                                echo '</span>';
                            }
                            ?>
                            <?php edit_post_link( __( 'Edit', 'photon' ), '' ); ?>
                        </div>


                        <div class="clear"></div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</article>
<?php get_footer(); ?>