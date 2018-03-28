<?php
/* Credits to http://hirizh.name/blog/styling-chat-transcript-for-custom-post-format/ */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly
?>

<div class="formatter">
    <h2 class="post-title"><a href="<?php echo esc_url(get_permalink()); ?>"><?php the_title(); ?></a></h2>
    <div class="real-content">
        <?php echo a13_daoon_chat_post($post->post_content);?>
        <div class="clear"></div>
    </div>
    <?php a13_post_meta(true); ?>
</div>