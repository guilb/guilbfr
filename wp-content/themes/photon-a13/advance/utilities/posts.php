<?php
/*
 * Serves all post meta: author, date, comments, tags, categories
 */
if(!function_exists('a13_post_meta')){
    function a13_post_meta($date_only = false) {
        global $a13_apollo13;

        $types      = a13_what_page_type_is_it();
        $post       = $types['post'];
        $post_list  = $types['blog_type'];
        $album       = $types['album'];
        $return     = '';
        $classes    = '';

        //return date
        if(
            ($post && $a13_apollo13->get_option('blog', 'post_date') === 'on')
            ||
            ($post_list && $a13_apollo13->get_option('blog', 'blog_date') === 'on')
        ){
            $return = a13_posted_on();
        }

        if(!$date_only){
            //return categories
            if(
                ($post && $a13_apollo13->get_option('blog', 'post_cats') === 'on')
                ||
                ($post_list && $a13_apollo13->get_option('blog', 'blog_cats') === 'on')
            ){
                $return .= a13_post_categories().' ';
            }



            //return comments number
            if(
                ($post && $a13_apollo13->get_option('blog', 'post_comments') === 'on')
                ||
                ($post_list && $a13_apollo13->get_option('blog', 'blog_comments') === 'on')
            ){
                $return .= a13_post_comments();
            }
        }
        else{
            $classes = ' date-only';
        }


        if(strlen($return)){
            echo '<div class="post-meta'.$classes.'">'.$return.'</div>';
        }
    }
}


/*
 * Date of post
 */
if(!function_exists('a13_posted_on')){
    function a13_posted_on() {
        return '<time class="entry-date" datetime="'.get_the_date( 'c' ).'"><i class="fa fa-clock-o"></i> '.get_the_date().'</time> ';
    }
}




/*
 * comments link
 */
if(!function_exists('a13_post_comments')){
    function a13_post_comments() {
        return '<a class="comments" href="' . esc_url(get_comments_link()) . '"><i class="fa fa-comment-o"></i> '
            .get_comments_number(). '</a>';
    }
}



/*
 * Categories that post was posted in
 */
if(!function_exists('a13_post_categories')){
    function a13_post_categories( ) {
        $cats = '';
        $cat_list = get_the_category_list(', ');
        if ( $cat_list ) {
            $cats = '<span class="cats"><i class="fa fa-folder-open-o"></i> '.$cat_list.'</span>';
        }

        return $cats;
    }
}



/*
 * Return subtitle for page/post
 */
if(!function_exists('a13_subtitle')){
    function a13_subtitle($tag = 'h2', $id = 0) {
        if($id === 0){
            $id = get_the_ID();
        }

        $s = get_post_meta($id, '_subtitle', true);
        if(strlen($s))
            $s = '<'.$tag.'>'.$s.'</'.$tag.'>';

        return $s;
    }
}



/*
 * Displays some elements after post content
 */
if(!function_exists('a13_under_post_content')){
    function a13_under_post_content() {
        global $a13_apollo13;

        $types      = a13_what_page_type_is_it();
        $post       = $types['post'];
        $post_list  = $types['blog_type'];

        //links to other subpages
        wp_link_pages( array(
            'before' => '<div id="page-links"><span class="page-links-title">'. __( 'Pages: ', 'photon' ).'</span>',
            'after'  => '</div>')
        );

        //Tags under content
        if(
            ($post && $a13_apollo13->get_option( 'blog', 'post_under_content_tags' ) === 'on')
            ||
            ($post_list && $a13_apollo13->get_option( 'blog', 'blog_under_content_tags' ) === 'on')
        ){
            $tag_list = get_the_tag_list( '',' ' );
            if ( $tag_list ) {
                echo '<p class="under_content_tags">'.$tag_list.'</p>';
            }
        }
    }
}



/*
 * Displays author info in posts(if enabled)
 */
if(!function_exists('a13_author_info')){
    function a13_author_info() {
        global $a13_apollo13;

        if($a13_apollo13->get_option( 'blog', 'author_info' ) === 'on'): ?>
            <div class="about-author comment clearfix">
                <div class="comment-body">
                    <?php $author_ID = get_the_author_meta( 'ID' );
                        echo '<a href="'.get_author_posts_url($author_ID).'" class="avatar">'.get_avatar( $author_ID, 90 ).'</a>';
                        echo '<strong class="comment-author">'.get_the_author();
                        $u_url = get_the_author_meta( 'user_url' );
                        if( ! empty( $u_url ) ){
                            echo '<a href="' . esc_url($u_url) . '" class="url">(' . $u_url . ')</a>';
                        }
                        echo '</strong>';
                    ?>
                    <div class="comment-content">
                        <?php
                        the_author_meta( 'description' );
                        ?>
                    </div>
                </div>
            </div>
        <?php endif;
    }
}



/*
 * Displays navigation to next and previous post
 */
if(!function_exists('a13_posts_navigation')){
    function a13_posts_navigation() {
        global $a13_apollo13;

        if($a13_apollo13->get_option( 'blog', 'posts_navigation' ) === 'on'){
            //posts navigation
            $prev_post = get_previous_post();
            $next_post = get_next_post();
            $is_next = is_object($next_post);
            $is_prev = is_object($prev_post);

            if($is_prev || $is_next){
                echo '<div class="posts-nav clearfix">';

                if($is_prev){
                    $id = $prev_post->ID;
                    echo '<a href="'.get_permalink($id).'" class="item prev">'
                         .'<span><i class="fa fa-long-arrow-left"></i> '.__( 'Previous article', 'photon' ).'</span>'
                         .'<span class="title">'.$prev_post->post_title.'</span>'
                         .'</a>';
                }
                if($is_next){
                    $id = $next_post->ID;
                    echo '<a href="'.get_permalink($id).'" class="item next">'
                         .'<span>'.__( 'Next article', 'photon' ).' <i class="fa fa-long-arrow-right"></i></span>'
                         .'<span class="title">'.$next_post->post_title.'</span>'
                         .'</a>';
                }

                echo '</div>';
            }
        }
    }
}



/*
 * Modify password form
 */
if(!function_exists('a13_password_form')){
    function a13_password_form() {
        //copy of function get_the_password_form() from \wp-includes\post-template.php ~1570
        //with small changes
        return
            '<form action="' . esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) ) . '" class="post-password-form" method="post">
            <p class="inputs"><input name="post_password" type="password" size="20" placeholder="password" /><input type="submit" name="Submit" value="' . esc_attr( 'Submit', 'photon' ) . '" /></p>
            </form>
            ';
    }
}


/*
 * Print password page template
 */
if(!function_exists('a13_custom_password_form')){
    function a13_custom_password_form($content) {
        //we get template to buffer and return it so other filters can do something with it
        ob_start();
        get_template_part('password_template');
        $output = ob_get_contents();
        ob_end_clean();

        return $output;
    }
}
add_filter( 'the_password_form', 'a13_custom_password_form');



/*
 * Sets the post excerpt length to 30 words.
 */
if(!function_exists('a13_excerpt_length')){
    function a13_excerpt_length( $length ) {
        global $a13_apollo13;
        return $a13_apollo13->get_option('blog', 'excerpt_length');
    }
}
add_filter( 'excerpt_length', 'a13_excerpt_length' );


/*
* This filter is used by wp_trim_excerpt() function.
* By default it set to echo '[...]' more string at the end of the excerpt.
*/
if(!function_exists('a13_new_excerpt_more')){
    function a13_new_excerpt_more($more) {
        global $post;
        return ' <a class="more-link" href="'. esc_url(get_permalink($post->ID)) . '">'.__( 'Read more ...', 'photon' ).'</a>';
    }
}
add_filter( 'excerpt_more', 'a13_new_excerpt_more' );



/*
* Make excerpt for comments
* used in widgets
*/
if(!function_exists('a13_get_comment_excerpt')){
    function a13_get_comment_excerpt($comment_ID = 0, $num_words = 20) {
        $comment = get_comment( $comment_ID );
        $comment_text = strip_tags($comment->comment_content);
        $blah = explode(' ', $comment_text);
        if (count($blah) > $num_words) {
            $k = $num_words;
            $use_dotdotdot = 1;
        } else {
            $k = count($blah);
            $use_dotdotdot = 0;
        }
        $excerpt = '';
        for ($i=0; $i<$k; $i++) {
            $excerpt .= $blah[$i] . ' ';
        }
        $excerpt .= ($use_dotdotdot) ? '[...]' : '';
        return apply_filters('get_comment_excerpt', $excerpt);
    }
}



/*
 * Comments navigation
 *
 */
if(!function_exists('a13_comments_navigation')){
    function a13_comments_navigation() {
        if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
            ?>
            <nav class="navigation comment-navigation" role="navigation">
                <h2 class="screen-reader-text"><?php _e( 'Comment navigation', 'photon' ); ?></h2>
                <div class="nav-links">
                    <?php
                    if ( $prev_link = get_previous_comments_link( __( 'Older Comments', 'photon' ) ) ) :
                        printf( '<div class="nav-previous">%s</div>', $prev_link );
                    endif;

                    if ( $next_link = get_next_comments_link( __( 'Newer Comments', 'photon' ) ) ) :
                        printf( '<div class="nav-next">%s</div>', $next_link );
                    endif;
                    ?>
                </div><!-- .nav-links -->
            </nav><!-- .comment-navigation -->
        <?php
        endif;
    }
}



if(!function_exists('a13_daoon_chat_post')){
    function a13_daoon_chat_post($content) {
        $chatoutput = "<div class=\"chat\">\n";
        $split = preg_split("/(\r?\n)+|(<br\s*\/?>\s*)+/", $content);
        foreach($split as $haystack) {
            if (strpos($haystack, ":")) {
                $string = explode(":", trim($haystack), 2);
                $who = strip_tags(trim($string[0]));
                $what = strip_tags(trim($string[1]));
                $row_class = empty($row_class)? " class=\"chat-highlight\"" : "";
                $chatoutput .= "<p><strong class=\"who\">$who:</strong> $what</p>\n";
            } else {
                $chatoutput .= $haystack . "\n";
            }
        }

        // print our new formated chat post
        $content = $chatoutput . "</div>\n";
        return $content;
    }
}


if(!function_exists('a13_add_password_form_to_template')) {
    function a13_add_password_form_to_template( $content ) {
        return $content . a13_password_form();
    }
}