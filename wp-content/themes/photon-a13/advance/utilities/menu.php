<?php

/* CUSTOM MENU WALKER CLASSES */

/* Used in main menu */
class A13_menu_walker extends Walker_Nav_Menu {

    /**
     * @see Walker::start_el()
     * @since 3.0.0
     *
     * @param string $output Passed by reference. Used to append additional content.
     * @param object $item Menu item data object.
     * @param int $depth Depth of menu item. Used for padding.
     * @param object $args
     * @param int $id Menu item ID.
     */
    function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
        static $mega_menu = false; //are we printing mega menu right now
        static $mega_menu_counter = 0; //we count columns of mega menu
        static $mm_columns = 1; //to remember how many columns this mega menu has
        static $displaying_html = false; //we don't display descendants if displaying html
        $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

        $value = '';

        $classes = empty( $item->classes ) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;

        //mega_menu

        if($depth === 0){
            $displaying_html = false; //reset
            if($item->a13_mega_menu === '1'){
                $mega_menu_counter = 0;
                $mega_menu = true;
                $mm_columns = $item->a13_mm_columns;
                $classes[] = 'mega-menu';
                $classes[] = 'mm_columns_'.$item->a13_mm_columns;
            }
            else{
                $mega_menu = false;
                $classes[] = 'normal-menu';
            }
        }
        if($depth === 1 && $mega_menu){
            if($mega_menu_counter % $mm_columns === 0){
                $classes[] = 'mm_new_row';
            }
            if($item->a13_mm_remove_item === '1'){
                $classes[] = 'mm_dont_show';
            }
            if(strlen($item->a13_mm_html)){
                $displaying_html = true;
                $classes[] = 'html_item';
            }
            else{
                $displaying_html = false;
            }

            $mega_menu_counter++;
        }

        //don't display descendants if displaying html
        if($depth > 1 && $displaying_html){
            //we print opening of list element cause wordpress will print close tag of this element anyway.
            $output .= '<li>';
            return;
        }

        //checks if this element is parent element
        $is_parent  = (bool)array_search('menu-parent-item', $classes);
        $icon       = trim($item->a13_item_icon);
        $label      = trim($item->a13_custom_label);
	    $dont_link  = $item->a13_unlinkable === '1';
        $name       = apply_filters( 'the_title', $item->title, $item->ID );

        $caret_class = $depth === 0? 'fa-caret-down' : 'fa-caret-right';

        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
        $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

        $id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
        $id = $id ? ' id="' . esc_attr( $id ) . '"' : '';


        $output .= $indent . '<li' . $id . $value . $class_names .'>';

        if($displaying_html){
            $output .= $item->a13_mm_html;
            return;
        }

        $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
        $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
        $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
        $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';

        $item_output = $args->before;
        $item_output .= $dont_link? '<span class="title">' : '<a'. $attributes .'>';
        $item_output .= strlen($icon)? '<i class="fa fa-'.$icon.'"></i>' : '';
        $item_output .= strlen(trim($name))? ($args->link_before . $name . $args->link_after) : '';
        $item_output .= strlen($label)? '<em class="custom-label">'.$label.'</em>' : '';
        $item_output .= $dont_link? '</span>' : '</a>';
	    $item_output .= $is_parent? '<i class="fa sub-mark '.$caret_class.'"></i>' : '';
        $item_output .= $args->after;

        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }
}


/* Used in Custom Menu Widget */
class A13_widget_menu_walker extends Walker_Nav_Menu {

    /**
     * @see Walker::start_el()
     * @since 3.0.0
     *
     * @param string $output Passed by reference. Used to append additional content.
     * @param object $item Menu item data object.
     * @param int $depth Depth of menu item. Used for padding.
     * @param object $args
     * @param int $id Menu item ID.
     */
    function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
        $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

        $value = '';

        $classes = empty( $item->classes ) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;

        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
        $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

        $id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
        $id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

        $menu_icon = trim($item->a13_item_icon);
        //checking for icons
        if(strlen($menu_icon)){
            $icons = explode(' ', $menu_icon);
            $menu_icon = '';
            foreach($icons as $icon){
                if(strlen($icon))
                    $menu_icon .= '<i class="fa fa-'.esc_attr( $icon ).'"></i>';
            }
        }

        $output .= $indent . '<li' . $id . $value . $class_names .'>';

        $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
        $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
        $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
        $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';

        $item_output = $args->before;
        $item_output .= '<a'. $attributes .'>';
        $item_output .= strlen($menu_icon)? '<span class="m_icon">'.$menu_icon.'</span>' : '';
        $item_output .= '<em class="icon-angle-right fa fa-angle-right"></em>';
        $item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
        $item_output .= '</a>';
        $item_output .= $args->after;

        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }
}

/* Used in Children Navigation Menu in sidebar */
class A13_list_pages_walker extends Walker_Page {
    /**
     * @see Walker::start_el()
     * @since 2.1.0
     *
     * @param string $output Passed by reference. Used to append additional content.
     * @param object $page Page data object.
     * @param int $depth Depth of page. Used for padding.
     * @param int $current_page Page ID.
     * @param array $args
     */
    function start_el( &$output, $page, $depth = 0, $args = array(), $current_page = 0 ) {
        if ( $depth )
            $indent = str_repeat("\t", $depth);
        else
            $indent = '';

        extract($args, EXTR_SKIP);
        $css_class = array('page_item', 'page-item-'.$page->ID);
        if ( !empty($current_page) ) {
            $_current_page = get_post( $current_page );
            if ( in_array( $page->ID, $_current_page->ancestors ) )
                $css_class[] = 'current_page_ancestor';
            if ( $page->ID == $current_page )
                $css_class[] = 'current_page_item';
            elseif ( $_current_page && $page->ID == $_current_page->post_parent )
                $css_class[] = 'current_page_parent';
        } elseif ( $page->ID == get_option('page_for_posts') ) {
            $css_class[] = 'current_page_parent';
        }

        $css_class = implode( ' ', apply_filters( 'page_css_class', $css_class, $page, $depth, $args, $current_page ) );

        $output .= $indent . '<li class="' . $css_class . '"><a href="' . get_permalink($page->ID) . '">'
            . $link_before . apply_filters( 'the_title', $page->post_title, $page->ID ) . $link_after . '</a>';

        //$show_date & $link_before are from extract function running
        if ( !empty($show_date) ) {
            if ( 'modified' == $show_date )
                $time = $page->post_modified;
            else
                $time = $page->post_date;

            $output .= " " . mysql2date($date_format, $time);
        }
    }
}
/* END OF: CUSTOM MENU WALKER CLASSES */
/* ********************************** */



/*
 * Check if current page is subpage
 */
if(!function_exists('a13_is_subpage')){
    function a13_is_subpage() {
        global $post;                              // load details about this page

        if ( is_page() && $post->post_parent ) {   // test to see if the page has a parent
            return $post->post_parent;             // return the ID of the parent post

        } else {                                   // there is no parent so ...
            return false;                          // ... the answer to the question is false
        }
    }
}


/*
 * Adds menu-parent-item class to parent elements in menu
 */
if(!function_exists('a13_add_menu_parent_class')){
    function a13_add_menu_parent_class( $items ) {

        $parents = array();
        foreach ( $items as $item ) {
            if ( $item->menu_item_parent && $item->menu_item_parent > 0 ) {
                $parents[] = $item->menu_item_parent;
            }
        }

        foreach ( $items as $item ) {
            if ( in_array( $item->ID, $parents ) ) {
                $item->classes[] = 'menu-parent-item';
            }
        }

        return $items;
    }
}
add_filter( 'wp_nav_menu_objects', 'a13_add_menu_parent_class' );


/*
 * Prints side menu for static pages that has parents or children
 */
if(!function_exists('a13_page_menu')){
    function a13_page_menu($only_check = false) {
        global $post;

        $there_is_menu = false;

        $has_children_args = array(
            'post_parent' => $post->ID,
            'post_status' => 'publish',
            'post_type' => 'any',
        );

        $list_pages_params = array(
            'child_of'      => $post->post_parent,
            'sort_column'   => 'menu_order',
            'depth'         => 0,
            'title_li'      => '',
            'walker'        => new A13_list_pages_walker
        );

        if(a13_is_subpage()){
            if($only_check){ return true; }
            $there_is_menu = true;
        }
        elseif(get_children( $has_children_args )){
            if($only_check){ return true; }
            $list_pages_params['child_of'] = $post->ID;
            $there_is_menu = true;
        }

        //display menu
        if($there_is_menu){
            echo '<div class="widget a13_page_menu widget_nav_menu">
                    <ul>';

            wp_list_pages($list_pages_params);

            echo '</ul>
                </div>';
        }
        return false;
    }
}




/* WPML MENU SWITCHER */
if(defined( 'ICL_SITEPRESS_VERSION')){
	global $sitepress_settings, $icl_language_switcher;

	//we are removing default filter for language switcher and alter it a bit
	if(!empty($sitepress_settings['display_ls_in_menu']) && ( !function_exists( 'wpml_home_url_ls_hide_check' ) || !wpml_home_url_ls_hide_check() ) ) {
		remove_filter( 'wp_nav_menu_items', array( $icl_language_switcher, 'wp_nav_menu_items_filter' ), 10 );
		add_filter( 'wp_nav_menu_items', 'a13_wpml_add_custom_menu', 10, 2 );
	}
}
/*
 * We add here some customization so language switcher will work with our main menu
 * It is copy of default WPML function, see above remove_filter
 */
function a13_wpml_add_custom_menu($items, $args){
	global $sitepress_settings, $sitepress;

	$current_language = $sitepress->get_current_language();
	$default_language = $sitepress->get_default_language();
	// menu can be passed as integer or object
	if(isset($args->menu->term_id)) $args->menu = $args->menu->term_id;

	$abs_menu_id = icl_object_id($args->menu, 'nav_menu', false, $default_language );
	$settings_menu_id = icl_object_id( $sitepress_settings[ 'menu_for_ls' ], 'nav_menu', false, $default_language );

	if ( $abs_menu_id == $settings_menu_id  || false === $abs_menu_id ) {

		$languages = $sitepress->get_ls_languages();

		$items .= '<li class="menu-item menu-item-language menu-item-language-current'.(sizeof($languages) > 1 ? ' menu-parent-item' : '').'">';
		if(isset($args->before)){
			$items .= $args->before;
		}
		$items .= '<span class="title">';
		if(isset($args->link_before)){
			$items .= $args->link_before;
		}

		$language_name = '';
		if ( $sitepress_settings[ 'icl_lso_native_lang' ] ) {
			$language_name .= $languages[ $current_language ][ 'native_name' ];
		}
		if ( $sitepress_settings[ 'icl_lso_display_lang' ] && $sitepress_settings[ 'icl_lso_native_lang' ] ) {
			$language_name .= ' (';
		}
		if ( $sitepress_settings[ 'icl_lso_display_lang' ] ) {
			$language_name .= $languages[ $current_language ][ 'translated_name' ];
		}
		if ( $sitepress_settings[ 'icl_lso_display_lang' ] && $sitepress_settings[ 'icl_lso_native_lang' ] ) {
			$language_name .= ')';
		}

		$alt_title_lang = esc_attr($language_name);

		if( $sitepress_settings['icl_lso_flags'] ){
			$items .= '<img class="iclflag" src="' . $languages[ $current_language ][ 'country_flag_url' ] . '" width="18" height="12" alt="' . $alt_title_lang . '" title="' . esc_attr( $language_name ) . '" />';
		}

		$items .= $language_name;

		if(isset($args->link_after)){
			$items .= $args->link_after;
		}
		$items .= '</span>';
		if(sizeof($languages) > 1 ){
			$items .= '<i class="fa sub-mark fa-caret-down"></i>';
		}
		if(isset($args->after)){
			$items .= $args->after;
		}

		unset($languages[ $current_language ]);
		$sub_items = false;
		$menu_is_vertical = !isset($sitepress_settings['icl_lang_sel_orientation']) || $sitepress_settings['icl_lang_sel_orientation'] == 'vertical';
		if(!empty($languages)){
			foreach($languages as $lang){
				$sub_items .= '<li class="menu-item menu-item-language menu-item-language-current">';
				$sub_items .= '<a href="'.$lang['url'].'">';

				$language_name = '';
				if ( $sitepress_settings[ 'icl_lso_native_lang' ] ) {
					$language_name .= $lang[ 'native_name' ];
				}
				if ( $sitepress_settings[ 'icl_lso_display_lang' ] && $sitepress_settings[ 'icl_lso_native_lang' ] ) {
					$language_name .= ' (';
				}
				if ( $sitepress_settings[ 'icl_lso_display_lang' ] ) {
					$language_name .= $lang[ 'translated_name' ];
				}
				if ( $sitepress_settings[ 'icl_lso_display_lang' ] && $sitepress_settings[ 'icl_lso_native_lang' ] ) {
					$language_name .= ')';
				}
				$alt_title_lang = esc_attr($language_name);

				if( $sitepress_settings['icl_lso_flags'] ){
					$sub_items .= '<img class="iclflag" src="'.$lang['country_flag_url'].'" width="18" height="12" alt="'.$alt_title_lang.'" title="' . $alt_title_lang . '" />';
				}
				$sub_items .= $language_name;

				$sub_items .= '</a>';
				$sub_items .= '</li>';

			}
			if( $sub_items && $menu_is_vertical ) {
				$sub_items = '<ul class="sub-menu submenu-languages">' . $sub_items . '</ul>';
			}
		}
		if( $menu_is_vertical ) {
			$items .= $sub_items;
			$items .= '</li>';
		} else {
			$items .= '</li>';
			$items .= $sub_items;
		}

	}

	return $items;
}