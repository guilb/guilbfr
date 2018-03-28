<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

//uses post content as title, and title as author name

?>
<div class="formatter">
    <h2 class="post-title"><?php echo $post->post_content; ?></h2>
    <span class="cite-author">&mdash; <?php the_title(); ?></span>
    <?php a13_post_meta(true); ?>
</div>


