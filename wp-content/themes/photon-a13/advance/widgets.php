<?php

/* to unify look of post in widgets */
if(!function_exists('a13_widget_posts')){
    function a13_widget_posts($r, $instance, $type = 'normal' ){
        while ($r->have_posts()) : $r->the_post();
            $page_title = get_the_title();

            echo '<div class="item">';

            echo '<a class="post-title" href="' . esc_url(get_permalink()) . '" title="' . esc_attr($page_title) . '">' . $page_title . '</a>';
            if($type === 'popular'){
                echo '<a class="comments" href="' . get_comments_link() . '" title="' . get_comments_number() . ' '.__( 'comment(s)', 'photon' ). '">'.get_comments_number().' '.__( 'comment(s)', 'photon' ).'</a>';
            }
            else{
                echo a13_posted_on(false);
            }

            //if user want excerpt also and post is not password protected
            if(!empty( $instance['content'] ) && !post_password_required()){
                echo  '<a class="content" href="' . get_permalink() . '" title="' . esc_attr($page_title) . '">';
                $text = get_the_content('');
                $text = strip_shortcodes( $text );
                $text = wp_trim_words( $text, 30, '' );
                echo $text;
                echo '</a>';
            }
            echo '</div>';

        endwhile;
    }
}


if(!function_exists('a13_add_sidebars')){
    function a13_add_sidebars() {
        //defined sidebars
        $widget_areas = array(
            // Shown on blog
            'blog-widget-area' => array(
                'name' =>  a13__be( 'Blog sidebar' ),
                'description' =>  a13__be( 'Widgets from this sidebar will appear on blog, search results, archive page and 404 error page.' ),
            ),

            // Shown in post
            'post-widget-area' => array(
                'name' =>  a13__be( 'Post sidebar' ),
                'description' =>  a13__be( 'Widgets from this sidebar will appear in single posts.' ),
            ),

            // Shown in pages
            'page-widget-area' => array(
                'name' =>  a13__be( 'Page sidebar' ),
                'description' =>  a13__be( 'Widgets from this sidebar will appear in static pages.' ),
            ),

            // Shown in pages
            'shop-widget-area' => array(
                'name' =>  a13__be( 'Shop sidebar' ),
                'description' =>  a13__be( 'Widgets from this sidebar will appear only in shop pages. Woocommerce have to be installed and activated.' ),
            ),

            // Shown in pages
            'side-widget-area' => array(
                'name' =>  a13__be( 'Hidden sidebar' ),
                'description' =>  a13__be( 'It is always available sidebar, that is activated by clicking on icon in header. Good for some special menus or other tips, information for user.' ),
            ),

            // Shown in pages
            'basket-widget-area' => array(
                'name' =>  a13__be( 'Basket sidebar' ),
                'description' =>  a13__be( 'It is always available sidebar (but only active if woocommerce is installed and activated), that is activated by clicking on icon in header. You should place there cart widget and also some promotions widgets for example.' ),
            ),

            // Shown in footer
            'footer-widget-area-1' => array(
                'name' => sprintf( a13__be( 'Footer widget area %d' ), 1),
                'description' =>  a13__be( 'Widgets from this area will appear in footer.' ),
                'before_widget' => '<div id="%1$s" class="footer-widget widget %2$s">',
            ),
            'footer-widget-area-2' => array(
                'name' => sprintf( a13__be( 'Footer widget area %d' ), 2),
                'description' =>  a13__be( 'Widgets from this area will appear in footer.' ),
                'before_widget' => '<div id="%1$s" class="footer-widget widget %2$s">',
            ),
            'footer-widget-area-3' => array(
                'name' => sprintf( a13__be( 'Footer widget area %d' ), 3),
                'description' =>  a13__be( 'Widgets from this area will appear in footer.' ),
                'before_widget' => '<div id="%1$s" class="footer-widget widget %2$s">',
            ),
            'footer-widget-area-4' => array(
                'name' => sprintf( a13__be( 'Footer widget area %d' ), 4),
                'description' =>  a13__be( 'Widgets from this area will appear in footer.' ),
                'before_widget' => '<div id="%1$s" class="footer-widget widget %2$s">',
            ),
            'footer-widget-area-5' => array(
                'name' => sprintf( a13__be( 'Footer widget area %d' ), 5),
                'description' =>  a13__be( 'Widgets from this area will appear in footer.' ),
                'before_widget' => '<div id="%1$s" class="footer-widget widget %2$s">',
            ),
        );

        //custom sidebars
        global $a13_apollo13;
        $custom_sidebars = unserialize($a13_apollo13->get_option( 'sidebars', 'custom_sidebars' ));
        $sidebars_count = count($custom_sidebars);
        if(is_array($custom_sidebars) && $sidebars_count > 0){
            foreach($custom_sidebars as $sidebar){
                $widget_areas[$sidebar['id']] = array(
                    'name' => $sidebar['name'],
                    'description' =>  a13__be( 'Widgets from this sidebar will appear in static pages.' ),
                );
            }
        }

        /**
         * Register widgets areas
         */
        foreach($widget_areas as $id => $sidebar){
            register_sidebar( array(
                'name'          => $sidebar['name'],
                'id'            => $id,
                'description'   => $sidebar['description'],
                'before_widget' => (isset($sidebar['before_widget'])? $sidebar['before_widget'] : '<div id="%1$s" class="widget %2$s">'),
                'after_widget'  => '</div>',
                'before_title'  => '<h3 class="title"><span>',
                'after_title'   => '</span></h3>',
            ) );
        }


        class Apollo13_Widget_Recent_Posts extends WP_Widget {

            function __construct() {
                $widget_ops = array('classname' => 'widget_recent_posts widget_about_posts', 'description' =>  a13__be( 'The most recent posts on your site' ) );
                parent::__construct('recent-posts', A13_TPL_NAME.' - '. a13__be( 'Recent Posts' ), $widget_ops);
                $this->alt_option_name = 'widget_recent_entries';

                add_action( 'save_post', array(&$this, 'flush_widget_cache') );
                add_action( 'deleted_post', array(&$this, 'flush_widget_cache') );
                add_action( 'switch_theme', array(&$this, 'flush_widget_cache') );
            }

            function widget($args, $instance) {
	            $before_widget = $after_widget = $before_title = $after_title = '';
                $cache = wp_cache_get('widget_recent_entries', 'widget');

                if ( !is_array($cache) ){
                    $cache = array();
                }

                if ( isset($cache[$args['widget_id']]) ) {
                    echo $cache[$args['widget_id']];
                    return;
                }

                ob_start();
                extract($args);

                $title = apply_filters('widget_title', empty($instance['title']) ? __( 'Recent Posts', 'photon' ) : $instance['title'], $instance, $this->id_base);
                if ( ! $number = absint( $instance['number'] ) ){
                    $number = 10;
                }

                $r = new WP_Query(array('posts_per_page' => $number, 'no_found_rows' => true, 'post_status' => 'publish', 'ignore_sticky_posts' => true));
                if ($r->have_posts()) :
                    echo $before_widget;

                    if( $title ){
                        echo $before_title . $title . $after_title;
                    }

                    a13_widget_posts($r, $instance);

                    echo $after_widget;

                    // Reset the global $the_post as this query will have stomped on it
                    wp_reset_postdata();

                endif;

                $cache[$args['widget_id']] = ob_get_flush();
                wp_cache_set('widget_recent_entries', $cache, 'widget');
            }

            function update( $new_instance, $old_instance ) {
                $instance = $old_instance;
                $instance['title'] = strip_tags($new_instance['title']);
                $instance['number'] = (int) $new_instance['number'];
                $instance['content'] = isset($new_instance['content']);

                $this->flush_widget_cache();

                $alloptions = wp_cache_get( 'alloptions', 'options' );
                if ( isset($alloptions['widget_recent_entries']) )
                    delete_option('widget_recent_entries');

                return $instance;
            }

            function flush_widget_cache() {
                wp_cache_delete('widget_recent_entries', 'widget');
            }

            function form( $instance ) {
                $title = isset($instance['title']) ? esc_attr($instance['title']) : '';
                $number = isset($instance['number']) ? absint($instance['number']) : 5;
                ?>
            <p><label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php a13_be('Title:' ); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>

            <p><label for="<?php echo esc_attr($this->get_field_id('number')); ?>"><?php a13_be('Number of posts to show:' ); ?></label>
                <input id="<?php echo esc_attr($this->get_field_id('number')); ?>" name="<?php echo esc_attr($this->get_field_name('number')); ?>" type="text" value="<?php echo esc_attr($number); ?>" size="3" /></p>

            <p><input id="<?php echo esc_attr($this->get_field_id('content')); ?>" name="<?php echo esc_attr($this->get_field_name('content')); ?>" type="checkbox" <?php checked(isset($instance['content']) ? $instance['content'] : 0); ?> />&nbsp;<label for="<?php echo esc_attr($this->get_field_id('content')); ?>"><?php a13_be('Add posts excerpt'); ?></label></p>
            <?php
            }
        }
        register_widget('Apollo13_Widget_Recent_Posts');


        class Apollo13_Widget_Popular_Posts extends WP_Widget {

            function __construct() {
                $widget_ops = array('classname' => 'widget_popular_entries widget_about_posts', 'description' =>  a13__be( 'The most popular posts on your site' ) );
                parent::__construct('popular-posts',  a13__be( 'Popular Posts' ), $widget_ops);
                $this->alt_option_name = 'widget_popular_entries';

                add_action( 'save_post', array(&$this, 'flush_widget_cache') );
                add_action( 'deleted_post', array(&$this, 'flush_widget_cache') );
                add_action( 'switch_theme', array(&$this, 'flush_widget_cache') );
            }

            function widget($args, $instance) {
	            $before_widget = $after_widget = $before_title = $after_title = '';
                $cache = wp_cache_get('widget_popular_entries', 'widget');

                if ( !is_array($cache) ){
                    $cache = array();
                }

                if ( isset($cache[$args['widget_id']]) ) {
                    echo $cache[$args['widget_id']];
                    return;
                }

                ob_start();
                extract($args);

                $title = apply_filters('widget_title', empty($instance['title']) ? __( 'Popular Posts', 'photon' ) : $instance['title'], $instance, $this->id_base);
                if ( ! $number = absint( $instance['number'] ) ){
                    $number = 10;
                }

                $r = new WP_Query(array('posts_per_page' => $number, 'no_found_rows' => true, 'orderby'=> 'comment_count', 'post_status' => 'publish', 'ignore_sticky_posts' => true));
                if ($r->have_posts()) :
                    echo $before_widget;

                    if( $title ){
                        echo $before_title . $title . $after_title;
                    }

                    a13_widget_posts($r, $instance, 'popular');

                    echo $after_widget;

                    // Reset the global $the_post as this query will have stomped on it
                    wp_reset_postdata();

                endif;

                $cache[$args['widget_id']] = ob_get_flush();
                wp_cache_set('widget_popular_entries', $cache, 'widget');
            }

            function update( $new_instance, $old_instance ) {
                $instance = $old_instance;
                $instance['title'] = strip_tags($new_instance['title']);
                $instance['number'] = (int) $new_instance['number'];
                $instance['content'] = isset($new_instance['content']);

                $this->flush_widget_cache();

                $alloptions = wp_cache_get( 'alloptions', 'options' );
                if ( isset($alloptions['widget_popular_entries']) )
                    delete_option('widget_popular_entries');

                return $instance;
            }

            function flush_widget_cache() {
                wp_cache_delete('widget_popular_entries', 'widget');
            }

            function form( $instance ) {
                $title = isset($instance['title']) ? esc_attr($instance['title']) : '';
                $number = isset($instance['number']) ? absint($instance['number']) : 5;
                ?>
            <p><label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php a13_be('Title:' ); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>

            <p><label for="<?php echo esc_attr($this->get_field_id('number')); ?>"><?php a13_be('Number of posts to show:' ); ?></label>
                <input id="<?php echo esc_attr($this->get_field_id('number')); ?>" name="<?php echo esc_attr($this->get_field_name('number')); ?>" type="text" value="<?php echo esc_attr($number); ?>" size="3" /></p>

            <p><input id="<?php echo esc_attr($this->get_field_id('content')); ?>" name="<?php echo esc_attr($this->get_field_name('content')); ?>" type="checkbox" <?php checked(isset($instance['content']) ? $instance['content'] : 0); ?> />&nbsp;<label for="<?php echo esc_attr($this->get_field_id('content')); ?>"><?php a13_be('Add posts excerpt'); ?></label></p>
            <?php
            }
        }
        register_widget('Apollo13_Widget_Popular_Posts');


        class Apollo13_Widget_Related_Posts extends WP_Widget {

            function __construct() {
                $widget_ops = array('classname' => 'widget_related_entries widget_about_posts', 'description' =>  a13__be( 'Related posts to current post' ) );
                parent::__construct('related-posts',  a13__be( 'Related Posts' ), $widget_ops);
                $this->alt_option_name = 'widget_related_entries';

                add_action( 'save_post', array(&$this, 'flush_widget_cache') );
                add_action( 'deleted_post', array(&$this, 'flush_widget_cache') );
                add_action( 'switch_theme', array(&$this, 'flush_widget_cache') );
            }

            function widget($args, $instance) {
	            $before_widget = $after_widget = $before_title = $after_title = '';
                $cache = wp_cache_get('widget_related_entries', 'widget');

                if ( !is_array($cache) ){
                    $cache = array();
                }

                if ( isset($cache[$args['widget_id']]) ) {
                    echo $cache[$args['widget_id']];
                    return;
                }

                ob_start();
                extract($args);

                $title = apply_filters('widget_title', empty($instance['title']) ? __( 'Related Posts', 'photon' ) : $instance['title'], $instance, $this->id_base);
                if ( ! $number = absint( $instance['number'] ) ){
                    $number = 10;
                }

                global $post;

                $__search = wp_get_post_tags($post->ID);
                $search_string = 'tags__in';
                //if no tags try categories
                if( !count($__search) ){
                    $__search = wp_get_post_categories($post->ID);
                    $search_string = 'category__in';
                }

                if ( count($__search) ) {

                    $r = new WP_Query(array($search_string => $__search,'post__not_in' => array($post->ID), 'posts_per_page' => $number, 'no_found_rows' => true, 'post_status' => 'publish', 'ignore_sticky_posts' => true));
                    if ($r->have_posts()) :
                        echo $before_widget;

                        if( $title ){
                            echo $before_title . $title . $after_title;
                        }

                        a13_widget_posts($r, $instance);

                        echo $after_widget;

                        // Reset the global $the_post as this query will have stomped on it
                        wp_reset_postdata();

                    endif;

                    $cache[$args['widget_id']] = ob_get_flush();
                    wp_cache_set('widget_related_entries', $cache, 'widget');
                }
            }

            function update( $new_instance, $old_instance ) {
                $instance = $old_instance;
                $instance['title'] = strip_tags($new_instance['title']);
                $instance['number'] = (int) $new_instance['number'];
                $instance['content'] = isset($new_instance['content']);

                $this->flush_widget_cache();

                $alloptions = wp_cache_get( 'alloptions', 'options' );
                if ( isset($alloptions['widget_related_entries']) )
                    delete_option('widget_related_entries');

                return $instance;
            }

            function flush_widget_cache() {
                wp_cache_delete('widget_related_entries', 'widget');
            }

            function form( $instance ) {
                $title = isset($instance['title']) ? esc_attr($instance['title']) : '';
                $number = isset($instance['number']) ? absint($instance['number']) : 5;
                ?>
            <p><label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php a13_be('Title:' ); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>

            <p><label for="<?php echo esc_attr($this->get_field_id('number')); ?>"><?php a13_be('Number of posts to show:' ); ?></label>
                <input id="<?php echo esc_attr($this->get_field_id('number')); ?>" name="<?php echo esc_attr($this->get_field_name('number')); ?>" type="text" value="<?php echo esc_attr($number); ?>" size="3" /></p>

            <p><input id="<?php echo esc_attr($this->get_field_id('content')); ?>" name="<?php echo esc_attr($this->get_field_name('content')); ?>" type="checkbox" <?php checked(isset($instance['content']) ? $instance['content'] : 0); ?> />&nbsp;<label for="<?php echo esc_attr($this->get_field_id('content')); ?>"><?php a13_be('Add posts excerpt'); ?></label></p>
            <?php
            }
        }
        register_widget('Apollo13_Widget_Related_Posts');


        class Apollo13_Nav_Menu_Widget extends WP_Widget {

            function __construct() {
                $widget_ops = array( 'description' =>  a13__be('Use this widget to add one of your custom menus as a widget.') );
                parent::__construct( 'nav_menu', A13_TPL_NAME.' - '.a13__be('Custom Menu'), $widget_ops );
            }

            function widget($args, $instance) {
                // Get menu
                $nav_menu = ! empty( $instance['nav_menu'] ) ? wp_get_nav_menu_object( $instance['nav_menu'] ) : false;

                if ( !$nav_menu )
                    return;

                $instance['title'] = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

                echo $args['before_widget'];

                if ( !empty($instance['title']) )
                    echo $args['before_title'] . $instance['title'] . $args['after_title'];

                wp_nav_menu( array( 'fallback_cb' => '', 'menu' => $nav_menu ) );

                echo $args['after_widget'];
            }

            function update( $new_instance, $old_instance ) {
                $instance['title'] = strip_tags( stripslashes($new_instance['title']) );
                $instance['nav_menu'] = (int) $new_instance['nav_menu'];
                return $instance;
            }

            function form( $instance ) {
                $title = isset( $instance['title'] ) ? $instance['title'] : '';
                $nav_menu = isset( $instance['nav_menu'] ) ? $instance['nav_menu'] : '';

                // Get menus
                $menus = get_terms( 'nav_menu', array( 'hide_empty' => false ) );

                // If no menus exists, direct the user to go and create some.
                if ( !$menus ) {
                    echo '<p>'. sprintf( a13__be('No menus have been created yet. <a href="%s">Create some</a>.'), esc_url(admin_url('nav-menus.php')) ) .'</p>';
                    return;
                }
                ?>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php a13_be('Title:') ?></label>
                <input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" value="<?php echo esc_attr($title); ?>" />
            </p>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('nav_menu')); ?>"><?php a13_be('Select Menu:'); ?></label>
                <select id="<?php echo esc_attr($this->get_field_id('nav_menu')); ?>" name="<?php echo esc_attr($this->get_field_name('nav_menu')); ?>">
                    <?php
                    foreach ( $menus as $menu ) {
                        echo '<option value="' . esc_attr($menu->term_id) . '"'
                            . selected( $nav_menu, $menu->term_id, false )
                            . '>'. $menu->name . '</option>';
                    }
                    ?>
                </select>
            </p>
            <?php
            }
        }
        register_widget('Apollo13_Nav_Menu_Widget');


        class Apollo13_Widget_Recent_Albums extends WP_Widget {

            function __construct() {
                $widget_ops = array('classname' => 'widget_recent_albums', 'description' =>  a13__be( 'Your most recent added albums' ) );
                parent::__construct('recent-albums',  a13__be( 'Recent Albums' ), $widget_ops);
                $this->alt_option_name = 'widget_recent_albums';

                add_action( 'save_post', array(&$this, 'flush_widget_cache') );
                add_action( 'deleted_post', array(&$this, 'flush_widget_cache') );
                add_action( 'switch_theme', array(&$this, 'flush_widget_cache') );
            }

            function widget($args, $instance) {
	            $before_widget = $after_widget = $before_title = $after_title = '';
                $cache = wp_cache_get('widget_recent_albums', 'widget');

                if ( !is_array($cache) )
                    $cache = array();

                if ( isset($cache[$args['widget_id']]) ) {
                    echo $cache[$args['widget_id']];
                    return;
                }

                ob_start();
                extract($args);

                $title = apply_filters('widget_title', empty($instance['title']) ? __( 'Recent Albums', 'photon' ) : $instance['title'], $instance, $this->id_base);
                if ( ! $number = absint( $instance['number'] ) )
                    $number = 10;

                $r = new WP_Query(array(
                    'posts_per_page' => $number,
                    'no_found_rows' => true,
                    'post_type' => A13_CUSTOM_POST_TYPE_ALBUM,
                    'post_status' => 'publish',
                    'ignore_sticky_posts' => true,
                    'orderby' => 'date'
                ));
                if ($r->have_posts()) :
                    echo $before_widget;

                    if( $title ){
                        echo $before_title . $title . $after_title;
                    }

                    echo '<div class="items clearfix">';

                    while ($r->have_posts()) : $r->the_post();
                        //title
                        $page_title = get_the_title();

                        //image
                        $img = a13_make_album_image(get_the_ID(), array(100,100) );
                        echo '<div class="item"><a href="'.get_permalink().'" title="'.esc_attr($page_title).'">'.$img.'</a></div>';

                    endwhile;

                    echo '</div>';

                    echo $after_widget;

                    // Reset the global $the_post as this query will have stomped on it
                    wp_reset_postdata();

                endif;

                $cache[$args['widget_id']] = ob_get_flush();
                wp_cache_set('widget_recent_albums', $cache, 'widget');
            }

            function update( $new_instance, $old_instance ) {
                $instance = $old_instance;
                $instance['title'] = strip_tags($new_instance['title']);
                $instance['number'] = (int) $new_instance['number'];

                $this->flush_widget_cache();

                $alloptions = wp_cache_get( 'alloptions', 'options' );
                if ( isset($alloptions['widget_recent_albums']) )
                    delete_option('widget_recent_albums');

                return $instance;
            }

            function flush_widget_cache() {
                wp_cache_delete('widget_recent_albums', 'widget');
            }

            function form( $instance ) {
                $title = isset($instance['title']) ? esc_attr($instance['title']) : '';
                $number = isset($instance['number']) ? absint($instance['number']) : 5;
                ?>
            <p><label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php a13_be('Title:' ); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>

            <p><label for="<?php echo esc_attr($this->get_field_id('number')); ?>"><?php a13_be('Number of posts to show:' ); ?></label>
                <input id="<?php echo esc_attr($this->get_field_id('number')); ?>" name="<?php echo esc_attr($this->get_field_name('number')); ?>" type="text" value="<?php echo esc_attr($number); ?>" size="3" /></p>
            <?php
            }
        }
        register_widget('Apollo13_Widget_Recent_Albums');

        class Apollo13_Widget_Contact_Info extends WP_Widget {

            function __construct() {
                $widget_ops = array('classname' => 'widget_contact_info', 'description' =>  a13__be( 'Contact information' ) );
                parent::__construct('contact-info',  a13__be( 'Contact information' ), $widget_ops);
                $this->alt_option_name = 'widget_contact_info';

                add_action( 'save_post', array(&$this, 'flush_widget_cache') );
                add_action( 'deleted_post', array(&$this, 'flush_widget_cache') );
                add_action( 'switch_theme', array(&$this, 'flush_widget_cache') );
            }

            function widget($args, $instance) {
	            $before_widget = $after_widget = $before_title = $after_title = '';
                $cache = wp_cache_get('widget_contact_info', 'widget');

                if ( !is_array($cache) ){
                    $cache = array();
                }

                if ( isset($cache[$args['widget_id']]) ) {
                    echo $cache[$args['widget_id']];
                    return;
                }

                ob_start();
                extract($args);

                $title = apply_filters('widget_title', empty($instance['title']) ? __( 'Contact information', 'photon' ) : $instance['title'], $instance, $this->id_base);

                echo $before_widget;

                if( $title ){
                    echo $before_title . $title . $after_title;
                }

                echo '<div class="info">';

                if(!empty($instance['content'])){
                    echo '<div class="content-text">'.$instance['content'].'</div>';
                }
                if(!empty($instance['phone'])){
                    echo '<div class="phone with_icon"><i class="fa fa-phone"></i>'.$instance['phone'].'</div>';
                }
                if(!empty($instance['fax'])){
                    echo '<div class="fax with_icon"><i class="fa fa-print"></i>'.$instance['fax'].'</div>';
                }
                if(!empty($instance['email'])){
                    echo '<a class="email with_icon" href="mailto:'.esc_attr($instance['email']).'"><i class="fa fa-envelope-o"></i>'.$instance['email'].'</a>';
                }
                if(!empty($instance['www'])){
                    echo '<a class="www with_icon" href="'.esc_url($instance['www']).'"><i class="fa fa-external-link"></i>'.$instance['www'].'</a>';
                }
                if(!empty($instance['open'])){
                    echo '<div class="content-open with_icon"><i class="fa fa-clock-o"></i>'.nl2br($instance['open']).'</div>';
                }

                echo '</div>';

                echo $after_widget;

                $cache[$args['widget_id']] = ob_get_flush();
                wp_cache_set('widget_related_entries', $cache, 'widget');
            }

            function update( $new_instance, $old_instance ) {
                $instance = $old_instance;
                $instance['title']  = strip_tags($new_instance['title']);
                $instance['phone']  = strip_tags($new_instance['phone']);
                $instance['email']  = strip_tags($new_instance['email']);
                $instance['fax']    = strip_tags($new_instance['fax']);
                $instance['www']    = strip_tags($new_instance['www']);
                $instance['content']= $new_instance['content'];
                $instance['open']   = strip_tags($new_instance['open']);

                $this->flush_widget_cache();

                $alloptions = wp_cache_get( 'alloptions', 'options' );
                if ( isset($alloptions['widget_contact_info']) )
                    delete_option('widget_contact_info');

                return $instance;
            }

            function flush_widget_cache() {
                wp_cache_delete('widget_contact_info', 'widget');
            }

            function form( $instance ) {
                $title  = isset($instance['title']) ? esc_attr($instance['title']) : '';
                $phone  = isset($instance['phone']) ? esc_attr($instance['phone']) : '';
                $email  = isset($instance['email']) ? esc_attr($instance['email']) : '';
                $fax    = isset($instance['fax']) ? esc_attr($instance['fax']) : '';
                $www    = isset($instance['www']) ? esc_attr($instance['www']) : '';
                $content= isset($instance['content']) ? esc_textarea($instance['content']) : '';
                $open   = isset($instance['open']) ? esc_textarea($instance['open']) : '';
                ?>
            <p><label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php a13_be('Title:' ); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
            <p><label for="<?php echo esc_attr($this->get_field_id('content')); ?>"><?php a13_be('Content:' ); ?></label>
                <textarea class="widefat" id="<?php echo esc_attr($this->get_field_id('content')); ?>" name="<?php echo esc_attr($this->get_field_name('content')); ?>" cols="20" rows="8"><?php echo esc_textarea($content); ?></textarea></p>
            <p><label for="<?php echo esc_attr($this->get_field_id('phone')); ?>"><?php a13_be('Phone:' ); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('phone')); ?>" name="<?php echo esc_attr($this->get_field_name('phone')); ?>" type="text" value="<?php echo esc_attr($phone); ?>" /></p>
            <p><label for="<?php echo esc_attr($this->get_field_id('fax')); ?>"><?php a13_be('Fax:' ); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('fax')); ?>" name="<?php echo esc_attr($this->get_field_name('fax')); ?>" type="text" value="<?php echo esc_attr($fax); ?>" /></p>
            <p><label for="<?php echo esc_attr($this->get_field_id('email')); ?>"><?php a13_be('E-mail:' ); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('email')); ?>" name="<?php echo esc_attr($this->get_field_name('email')); ?>" type="text" value="<?php echo esc_attr($email); ?>" /></p>
            <p><label for="<?php echo esc_attr($this->get_field_id('www')); ?>"><?php a13_be('Site:' ); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('www')); ?>" name="<?php echo esc_attr($this->get_field_name('www')); ?>" type="text" value="<?php echo esc_attr($www); ?>" /></p>
            <p><label for="<?php echo esc_attr($this->get_field_id('open')); ?>"><?php a13_be('Open hours info:' ); ?></label>
                <textarea class="widefat" id="<?php echo esc_attr($this->get_field_id('open')); ?>" name="<?php echo esc_attr($this->get_field_name('open')); ?>" cols="20" rows="8"><?php echo esc_textarea($open); ?></textarea></p>

            <?php
            }
        }
        register_widget('Apollo13_Widget_Contact_Info');


        class Apollo13_Widget_Shortcodes extends WP_Widget {

            function __construct() {
                $widget_ops = array('classname' => 'widget_shortcodes', 'description' =>  a13__be('Widget to put shortcodes in'));
                $control_ops = array('width' => 400, 'height' => 350);
                parent::__construct('a13-shortcodes',  a13__be('Shortcodes'), $widget_ops, $control_ops);
            }

            function widget( $args, $instance ) {
	            $before_widget = $after_widget = $before_title = $after_title = '';
                extract($args);
                $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
                $text = apply_filters( 'widget_text', empty( $instance['text'] ) ? '' : $instance['text'], $instance );
                echo $before_widget;
                if ( !empty( $title ) ) { echo $before_title . $title . $after_title; } ?>
            <div class="textwidget"><?php echo do_shortcode( $text ); ?></div>
            <?php
                echo $after_widget;
            }

            function update( $new_instance, $old_instance ) {
                $instance = $old_instance;
                $instance['title'] = strip_tags($new_instance['title']);
                if ( current_user_can('unfiltered_html') )
                    $instance['text'] =  $new_instance['text'];
                else
                    $instance['text'] = stripslashes( wp_filter_post_kses( addslashes($new_instance['text']) ) ); // wp_filter_post_kses() expects slashed
                $instance['filter'] = isset($new_instance['filter']);
                return $instance;
            }

            function form( $instance ) {
                $instance = wp_parse_args( (array) $instance, array( 'title' => '', 'text' => '' ) );
                $title = strip_tags($instance['title']);
                $text = esc_textarea($instance['text']);
                ?>
            <p><label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php a13_be('Title:'); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>

            <textarea class="widefat" rows="16" cols="20" id="<?php echo esc_attr($this->get_field_id('text')); ?>" name="<?php echo esc_attr($this->get_field_name('text')); ?>"><?php echo esc_textarea($text); ?></textarea>

            <p><input id="<?php echo esc_attr($this->get_field_id('filter')); ?>" name="<?php echo esc_attr($this->get_field_name('filter')); ?>" type="checkbox" <?php checked(isset($instance['filter']) ? $instance['filter'] : 0); ?> />&nbsp;<label for="<?php echo esc_attr($this->get_field_id('filter')); ?>"><?php a13_be('Automatically add paragraphs'); ?></label></p>
            <?php
            }
        }
        register_widget('Apollo13_Widget_Shortcodes');

        class Apollo13_Widget_Social_Icons extends WP_Widget {

            function __construct() {
                $widget_ops = array('classname' => 'widget_a13_social_icons', 'description' =>  a13__be( 'Social icons form theme settings' ) );
                parent::__construct('a13-social-icons',  a13__be( 'Apollo13 Social Icons' ), $widget_ops);
                $this->alt_option_name = 'widget_a13_social_icons';

                add_action( 'save_post', array(&$this, 'flush_widget_cache') );
                add_action( 'deleted_post', array(&$this, 'flush_widget_cache') );
                add_action( 'switch_theme', array(&$this, 'flush_widget_cache') );
            }

            function widget($args, $instance) {
	            $before_widget = $after_widget = $before_title = $after_title = '';
                $cache = wp_cache_get('widget_a13_social_icons', 'widget');

                if ( !is_array($cache) ){
                    $cache = array();
                }

                if ( isset($cache[$args['widget_id']]) ) {
                    echo $cache[$args['widget_id']];
                    return;
                }

                ob_start();
                extract($args);

                $title = apply_filters('widget_title', empty($instance['title']) ? __( 'Social Icons', 'photon' ) : $instance['title'], $instance, $this->id_base);

                $icons = a13_social_icons($instance['icons_color']);
                if (strlen($icons)) :
                    echo $before_widget;

                    if( $title ){
                        echo $before_title . $title . $after_title;
                    }

                    echo $icons;

                    echo $after_widget;

                endif;

                $cache[$args['widget_id']] = ob_get_flush();
                wp_cache_set('widget_a13_social_icons', $cache, 'widget');
            }

            function update( $new_instance, $old_instance ) {
                $instance = $old_instance;
                $instance['title'] = strip_tags($new_instance['title']);
                $instance['icons_color'] = $new_instance['icons_color'];

                $this->flush_widget_cache();

                $alloptions = wp_cache_get( 'alloptions', 'options' );
                if ( isset($alloptions['widget_a13_social_icons']) )
                    delete_option('widget_a13_social_icons');

                return $instance;
            }

            function flush_widget_cache() {
                wp_cache_delete('widget_a13_social_icons', 'widget');
            }

            function form( $instance ) {
                $title = isset($instance['title']) ? esc_attr($instance['title']) : '';
                $color = isset($instance['icons_color']) ? esc_attr($instance['icons_color']) : '';
                ?>
            <p><label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php a13_be('Title:' ); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('icons_color')); ?>"><?php a13_be('Icons color:'); ?></label>
                <select id="<?php echo esc_attr($this->get_field_id('icons_color')); ?>" name="<?php echo esc_attr($this->get_field_name('icons_color')); ?>">
                    <option value="dark-bg"<?php echo selected( $color, 'dark-bg' ); ?>><?php a13_be( 'White' ); ?></option>
                    <option value="light-bg"<?php echo selected( $color, 'light-bg'); ?>><?php a13_be( 'Black' ); ?></option>
                    <option value="colors"<?php echo selected( $color, 'colors'); ?>><?php a13_be( 'Colors' ); ?></option>
                </select>
            </p>
            <?php
            }
        }
        register_widget('Apollo13_Widget_Social_Icons');

    }
}

add_action( 'widgets_init', 'a13_add_sidebars' );
?>