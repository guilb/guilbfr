<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly


$content = get_the_content();
?>

<div class="formatter">
    <h2 class="post-title"><?php the_title(); ?></h2>
    <?php echo strlen($content)? '<div class="real-content">'.$content.'</div>' : ''; ?>
    <?php a13_post_meta(true); ?>
</div>
