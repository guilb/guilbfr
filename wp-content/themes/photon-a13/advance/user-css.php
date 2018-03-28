<?php

global $a13_apollo13;

/*
 * body part
 */
$predefined_colors          = $a13_apollo13->get_option( 'appearance' , 'predefined_colors' );
$global_bg_color              = a13_make_css_rule( 'background-color', $a13_apollo13->get_option( 'appearance' , 'body_bg_color' ));
$global_image                 = a13_make_css_rule( 'background-image', $a13_apollo13->get_option( 'appearance' , 'body_image' ), 'url(%s)');
$global_image_fit             = a13_bg_fit_helper($a13_apollo13->get_option( 'appearance' , 'body_image_fit' ));
$error404_bg_image          = a13_make_css_rule( 'background-image', $a13_apollo13->get_option( 'page' , 'page_404_bg_image' ), 'url(%s)');
$password_bg_image          = a13_make_css_rule( 'background-image', $a13_apollo13->get_option( 'page' , 'page_password_bg_image' ), 'url(%s)');
$headings_color             = a13_make_css_rule( 'color', $a13_apollo13->get_option( 'fonts' , 'headings_color' ));
$headings_color_hover       = a13_make_css_rule( 'color', $a13_apollo13->get_option( 'fonts' , 'headings_color_hover' ));
$headings_weight            = a13_make_css_rule( 'font-weight', $a13_apollo13->get_option( 'fonts' , 'headings_weight' ));
$headings_transform         = a13_make_css_rule( 'text-transform', $a13_apollo13->get_option( 'fonts' , 'headings_transform' ));
$cursor_css                 = 'cursor: default';
$custom_cursor              = $a13_apollo13->get_option( 'appearance' , 'custom_cursor' );
if( $custom_cursor  === 'custom' ){
    $cursor_css             = a13_make_css_rule( 'cursor', $a13_apollo13->get_option( 'appearance' , 'cursor_image' ), 'url("%s"), default');
}
elseif( $custom_cursor === 'select' ){
    $cursor = $a13_apollo13->get_option( 'appearance' , 'cursor_select' );
    $cursor_css             = a13_make_css_rule( 'cursor', A13_TPL_GFX.'/cursors/'.$cursor , 'url("%s"), default');
}

$prelaoder_bg_color     = a13_make_css_rule( 'background-color', $a13_apollo13->get_option( 'appearance' , 'preloader_bg_color' ));
$prelaoder_bg_image     = a13_make_css_rule( 'background-image', $a13_apollo13->get_option( 'appearance' , 'preloader_bg_image' ), 'url(%s)');
$prelaoder_bg_image_fit = a13_bg_fit_helper($a13_apollo13->get_option( 'appearance' , 'preloader_bg_image_fit' ));

//global sidebars
$basket_sidebar_bg_color     = a13_make_css_rule( 'background-color', $a13_apollo13->get_option( 'appearance' , 'basket_sidebar_bg_color' ));
$basket_sidebar_font_size    = a13_make_css_rule( 'font-size', $a13_apollo13->get_option( 'appearance' , 'basket_sidebar_font_size' ));
$hidden_sidebar_bg_color     = a13_make_css_rule( 'background-color', $a13_apollo13->get_option( 'appearance' , 'hidden_sidebar_bg_color' ));
$hidden_sidebar_font_size    = a13_make_css_rule( 'font-size', $a13_apollo13->get_option( 'appearance' , 'hidden_sidebar_font_size' ));




/*
 *  logo
 */
$logo_image             = a13_make_css_rule( 'background-image', $a13_apollo13->get_option( 'appearance' , 'logo_image' ), 'url(%s)');
$logo_image_2x          = a13_make_css_rule( 'background-image', $a13_apollo13->get_option( 'appearance' , 'logo_image_high_dpi' ), 'url(%s)');
$logo_image_height      = a13_make_css_rule( 'height', $a13_apollo13->get_option( 'appearance' , 'logo_image_height' ));

$logo_image_hover       = (int)$a13_apollo13->get_option( 'appearance' , 'logo_image_opacity' );
$logo_image_hover_o     = $logo_image_hover/100;
$logo_color             = a13_make_css_rule( 'color', $a13_apollo13->get_option( 'appearance' , 'logo_color' ));
$logo_color_hover       = a13_make_css_rule( 'color', $a13_apollo13->get_option( 'appearance' , 'logo_color_hover' ));
$logo_font_size         = a13_make_css_rule( 'font-size', $a13_apollo13->get_option( 'appearance' , 'logo_font_size' ));
$logo_weight            = a13_make_css_rule( 'font-weight', $a13_apollo13->get_option( 'appearance' , 'logo_weight' ));
$logo_padding           = $a13_apollo13->get_option( 'appearance' , 'logo_padding' );



/*
 *  header part
 */
$header_bg_color        = a13_make_css_rule( 'background-color', $a13_apollo13->get_option( 'appearance' , 'header_bg_color' ));
$header_tools_bg        = a13_make_css_rule( 'background-color', a13_hex2rgba( $a13_apollo13->get_option( 'appearance' , 'header_bg_color' ), '0.18' ) );
$header_tools_color     = a13_make_css_rule( 'color', $a13_apollo13->get_option( 'appearance' , 'header_tools_color' ));
$menu_weight            = a13_make_css_rule( 'font-weight', $a13_apollo13->get_option( 'appearance' , 'menu_weight' ));
$menu_transform         = a13_make_css_rule( 'text-transform', $a13_apollo13->get_option( 'appearance' , 'menu_transform' ));
$menu_font_size         = a13_make_css_rule( 'font-size', $a13_apollo13->get_option( 'appearance' , 'menu_font_size' ));
$menu_element_padding   = $a13_apollo13->get_option( 'appearance' , 'menu_element_padding' );
$menu_color             = a13_make_css_rule( 'color', $a13_apollo13->get_option( 'appearance' , 'menu_color' ));
$menu_hover_color       = $a13_apollo13->get_option( 'appearance' , 'menu_hover_color' );
$submenu_weight         = a13_make_css_rule( 'font-weight', $a13_apollo13->get_option( 'appearance' , 'submenu_weight' ));
$submenu_transform      = a13_make_css_rule( 'text-transform', $a13_apollo13->get_option( 'appearance' , 'submenu_transform' ));
$submenu_font_size      = a13_make_css_rule( 'font-size', $a13_apollo13->get_option( 'appearance' , 'submenu_font_size' ));
$submenu_color          = a13_make_css_rule( 'color', $a13_apollo13->get_option( 'appearance' , 'submenu_color' ));
$menu_label_color       = $a13_apollo13->get_option( 'appearance' , 'menu_label_color' );
$submenu_top_line       = $a13_apollo13->get_option( 'appearance' , 'submenu_top_line' ) === 'off'? 'border-top: none;': '';



/*
 *  footer
 */
$footer_bg_color        = a13_make_css_rule( 'background-color', $a13_apollo13->get_option( 'appearance' , 'footer_bg_color' ));
$footer_font_size       = a13_make_css_rule( 'font-size', $a13_apollo13->get_option( 'appearance' , 'footer_font_size' ));
$footer_font_color      = a13_make_css_rule( 'color', $a13_apollo13->get_option( 'appearance' , 'footer_font_color' ));
$footer_link_color      = a13_make_css_rule( 'color', $a13_apollo13->get_option( 'appearance' , 'footer_link_color' ));
$footer_hover_color     = a13_make_css_rule( 'color', $a13_apollo13->get_option( 'appearance' , 'footer_hover_color' ));
$fw_border_color        = a13_make_css_rule( 'border-color', $a13_apollo13->get_option( 'appearance' , 'footer_widgets_border_color' ));



/*
 *  top space
 */
$top_space_bg_color = a13_make_css_rule( 'background-color', $a13_apollo13->get_option( 'appearance', 'top_space_bg_color' ));
$top_space_space    = a13_make_css_rule( 'height', $a13_apollo13->get_option( 'appearance', 'top_space_height' ));



/*
 *  title bar
 */
$title_bar_size                 = a13_make_css_rule( 'font-size', $a13_apollo13->get_option( 'appearance', 'title_bar_title_size' ));
$title_bar_weight               = a13_make_css_rule( 'font-weight', $a13_apollo13->get_option( 'appearance', 'title_bar_title_weight' ));
$title_bar_color_val            = $a13_apollo13->get_option( 'appearance', 'title_bar_title_color' );
$title_bar_color                = a13_make_css_rule( 'color', $title_bar_color_val );
$title_bar_bg_color             = a13_make_css_rule( 'background-color', $a13_apollo13->get_option( 'appearance', 'title_bar_bg_color' ));
$title_bar_space                = $a13_apollo13->get_option( 'appearance', 'title_bar_space_width' );



/*
 *  posts list(blog)
 */
$blog_bg_color = $blog_image = $blog_image_fit = '';
if( $a13_apollo13->get_option( 'blog' , 'custom_background' ) === 'on' ){
    $blog_bg_color              = a13_make_css_rule( 'background-color', $a13_apollo13->get_option( 'blog' , 'body_bg_color' ));
    $blog_image                 = a13_make_css_rule( 'background-image', $a13_apollo13->get_option( 'blog' , 'body_image' ), 'url(%s)');
    $blog_image_fit             = a13_bg_fit_helper($a13_apollo13->get_option( 'blog' , 'body_image_fit' ));
}
$blog_top_space_bg_color = $blog_top_space_space = '';
if( $a13_apollo13->get_option( 'blog' , 'custom_top_space' ) === 'on' ){
    $blog_top_space_bg_color    = a13_make_css_rule( 'background-color', $a13_apollo13->get_option( 'blog', 'top_space_bg_color' ));
    $blog_top_space_space       = a13_make_css_rule( 'height', $a13_apollo13->get_option( 'blog', 'top_space_height' ));
}
$blog_title_bar_size = $blog_title_bar_weight = $blog_title_bar_color_val = $blog_title_bar_color = $blog_title_bar_bg_color = $blog_title_bar_space = '';
if( $a13_apollo13->get_option( 'blog' , 'custom_title_bar' ) === 'on' ){
    $blog_title_bar_size        = a13_make_css_rule( 'font-size', $a13_apollo13->get_option( 'blog', 'title_bar_title_size' ));
    $blog_title_bar_weight      = a13_make_css_rule( 'font-weight', $a13_apollo13->get_option( 'blog', 'title_bar_title_weight' ));
    $blog_title_bar_color_val   = $a13_apollo13->get_option( 'blog', 'title_bar_title_color' );
    $blog_title_bar_color       = a13_make_css_rule( 'color', $blog_title_bar_color_val );
    $blog_title_bar_bg_color    = a13_make_css_rule( 'background-color', $a13_apollo13->get_option( 'blog', 'title_bar_bg_color' ));
    $blog_title_bar_space       = $a13_apollo13->get_option( 'blog', 'title_bar_space_width' );
}

$blog_bricks_max_width       = a13_make_css_rule( 'max-width', $a13_apollo13->get_option( 'blog' , 'bricks_max_width' ));
$blog_margin                 = $a13_apollo13->get_option( 'blog' , 'brick_margin' );
$onimage_title_color         = a13_make_css_rule( 'color', $a13_apollo13->get_option( 'blog' , 'blog_omimage_title_color' ));
$onimage_text_color          = a13_make_css_rule( 'color', $a13_apollo13->get_option( 'blog' , 'blog_omimage_text_color' ));
$onimage_link_color          = a13_make_css_rule( 'color', $a13_apollo13->get_option( 'blog' , 'blog_omimage_link_color' ));
$onimage_link_hover_color    = a13_make_css_rule( 'color', $a13_apollo13->get_option( 'blog' , 'blog_omimage_link_hover_color' ));
$onimage_border_color        = a13_make_css_rule( 'border-color', $a13_apollo13->get_option( 'blog' , 'blog_omimage_link_color' ));
$onimage_border_hover_color  = a13_make_css_rule( 'border-color', $a13_apollo13->get_option( 'blog' , 'blog_omimage_link_hover_color' ));



/*
 *  shop
 */
$shop_bg_color = $shop_image = $shop_image_fit = '';
if( $a13_apollo13->get_option( 'shop' , 'custom_background' ) === 'on' ){
    $shop_bg_color              = a13_make_css_rule( 'background-color', $a13_apollo13->get_option( 'shop' , 'body_bg_color' ));
    $shop_image                 = a13_make_css_rule( 'background-image', $a13_apollo13->get_option( 'shop' , 'body_image' ), 'url(%s)');
    $shop_image_fit             = a13_bg_fit_helper($a13_apollo13->get_option( 'shop' , 'body_image_fit' ));
}
$shop_top_space_bg_color = $shop_top_space_space = '';
if( $a13_apollo13->get_option( 'shop' , 'custom_top_space' ) === 'on' ){
    $shop_top_space_bg_color    = a13_make_css_rule( 'background-color', $a13_apollo13->get_option( 'shop', 'top_space_bg_color' ));
    $shop_top_space_space       = a13_make_css_rule( 'height', $a13_apollo13->get_option( 'shop', 'top_space_height' ));
}
$shop_title_bar_size = $shop_title_bar_weight = $shop_title_bar_color_val = $shop_title_bar_color = $shop_title_bar_bg_color = $shop_title_bar_space = '';
if( $a13_apollo13->get_option( 'shop' , 'custom_title_bar' ) === 'on' ){
    $shop_title_bar_size        = a13_make_css_rule( 'font-size', $a13_apollo13->get_option( 'shop', 'title_bar_title_size' ));
    $shop_title_bar_weight      = a13_make_css_rule( 'font-weight', $a13_apollo13->get_option( 'shop', 'title_bar_title_weight' ));
    $shop_title_bar_color_val   = $a13_apollo13->get_option( 'shop', 'title_bar_title_color' );
    $shop_title_bar_color       = a13_make_css_rule( 'color', $shop_title_bar_color_val );
    $shop_title_bar_bg_color    = a13_make_css_rule( 'background-color', $a13_apollo13->get_option( 'shop', 'title_bar_bg_color' ));
    $shop_title_bar_space       = $a13_apollo13->get_option( 'shop', 'title_bar_space_width' );
}



/*
 *  Albums list
 */
$album_bg_color = $album_image = $album_image_fit = '';
if( $a13_apollo13->get_option( 'album' , 'custom_background' ) === 'on' ){
    $album_bg_color              = a13_make_css_rule( 'background-color', $a13_apollo13->get_option( 'album' , 'body_bg_color' ));
    $album_image                 = a13_make_css_rule( 'background-image', $a13_apollo13->get_option( 'album' , 'body_image' ), 'url(%s)');
    $album_image_fit             = a13_bg_fit_helper($a13_apollo13->get_option( 'album' , 'body_image_fit' ));
}
$album_top_space_bg_color = $album_top_space_space = '';
if( $a13_apollo13->get_option( 'album' , 'custom_top_space' ) === 'on' ){
    $album_top_space_bg_color    = a13_make_css_rule( 'background-color', $a13_apollo13->get_option( 'album', 'top_space_bg_color' ));
    $album_top_space_space       = a13_make_css_rule( 'height', $a13_apollo13->get_option( 'album', 'top_space_height' ));
}
$album_title_bar_size = $album_title_bar_weight = $album_title_bar_color_val = $album_title_bar_color = $album_title_bar_bg_color = $album_title_bar_space = '';
if( $a13_apollo13->get_option( 'album' , 'custom_title_bar' ) === 'on' ){
    $album_title_bar_size        = a13_make_css_rule( 'font-size', $a13_apollo13->get_option( 'album', 'title_bar_title_size' ));
    $album_title_bar_weight      = a13_make_css_rule( 'font-weight', $a13_apollo13->get_option( 'album', 'title_bar_title_weight' ));
    $album_title_bar_color_val   = $a13_apollo13->get_option( 'album', 'title_bar_title_color' );
    $album_title_bar_color       = a13_make_css_rule( 'color', $album_title_bar_color_val );
    $album_title_bar_bg_color    = a13_make_css_rule( 'background-color', $a13_apollo13->get_option( 'album', 'title_bar_bg_color' ));
    $album_title_bar_space       = $a13_apollo13->get_option( 'album', 'title_bar_space_width' );
}

$album_bricks_max_width       = a13_make_css_rule( 'max-width', $a13_apollo13->get_option( 'album' , 'bricks_max_width' ));
$album_margin                 = $a13_apollo13->get_option( 'album' , 'brick_margin' );



/*
 *  Pages layout
 */
$page_bg_color = $page_image = $page_image_fit = '';
if( $a13_apollo13->get_option( 'page' , 'custom_background' ) === 'on' ){
    $page_bg_color              = a13_make_css_rule( 'background-color', $a13_apollo13->get_option( 'page' , 'body_bg_color' ));
    $page_image                 = a13_make_css_rule( 'background-image', $a13_apollo13->get_option( 'page' , 'body_image' ), 'url(%s)');
    $page_image_fit             = a13_bg_fit_helper($a13_apollo13->get_option( 'page' , 'body_image_fit' ));
}
$page_top_space_bg_color = $page_top_space_space = '';
if( $a13_apollo13->get_option( 'page' , 'custom_top_space' ) === 'on' ){
    $page_top_space_bg_color    = a13_make_css_rule( 'background-color', $a13_apollo13->get_option( 'page', 'top_space_bg_color' ));
    $page_top_space_space       = a13_make_css_rule( 'height', $a13_apollo13->get_option( 'page', 'top_space_height' ));
}
$page_title_bar_size = $page_title_bar_weight = $page_title_bar_color_val = $page_title_bar_color = $page_title_bar_bg_color = $page_title_bar_space = '';
if( $a13_apollo13->get_option( 'page' , 'custom_title_bar' ) === 'on' ){
    $page_title_bar_size        = a13_make_css_rule( 'font-size', $a13_apollo13->get_option( 'page', 'title_bar_title_size' ));
    $page_title_bar_weight      = a13_make_css_rule( 'font-weight', $a13_apollo13->get_option( 'page', 'title_bar_title_weight' ));
    $page_title_bar_color_val   = $a13_apollo13->get_option( 'page', 'title_bar_title_color' );
    $page_title_bar_color       = a13_make_css_rule( 'color', $page_title_bar_color_val );
    $page_title_bar_bg_color    = a13_make_css_rule( 'background-color', $a13_apollo13->get_option( 'page', 'title_bar_bg_color' ));
    $page_title_bar_space       = $a13_apollo13->get_option( 'page', 'title_bar_space_width' );
}





/*
 *  content
 */
$content_font_size    = a13_make_css_rule( 'font-size', $a13_apollo13->get_option( 'fonts' , 'content_font_size' ));
$content_font_color   = a13_make_css_rule( 'color', $a13_apollo13->get_option( 'fonts' , 'content_color' ));
$content_first_p_show = ($a13_apollo13->get_option( 'fonts' , 'first_paragraph' ) === 'off')? 'font-size: inherit; color: inherit;' : '';
$content_first_p_color= ($a13_apollo13->get_option( 'fonts' , 'first_paragraph' ) === 'off')? '' : a13_make_css_rule( 'color', $a13_apollo13->get_option( 'fonts' , 'first_paragraph_color' ));



/*
 *  lightbox
 */
$lg_lightbox_bg_color                     = a13_make_css_rule( 'background-color', $a13_apollo13->get_option( 'advanced', 'lg_lightbox_bg_color' ) );
$lg_lightbox_elements_bg_color            = a13_make_css_rule( 'background-color', $a13_apollo13->get_option( 'advanced', 'lg_lightbox_elements_bg_color' ) );
$lg_lightbox_elements_color               = a13_make_css_rule( 'color', $a13_apollo13->get_option( 'advanced', 'lg_lightbox_elements_color' ) );
$lg_lightbox_elements_color_hover         = a13_make_css_rule( 'color', $a13_apollo13->get_option( 'advanced', 'lg_lightbox_elements_color_hover' ) );
$lg_lightbox_elements_text_color          = a13_make_css_rule( 'color', $a13_apollo13->get_option( 'advanced', 'lg_lightbox_elements_text_color' ) );
$lg_lightbox_thumbs_bg_color              = a13_make_css_rule( 'background-color', $a13_apollo13->get_option( 'advanced', 'lg_lightbox_thumbs_bg_color' ) );
$lg_lightbox_thumbs_border_bg_color       = a13_make_css_rule( 'border-color', $a13_apollo13->get_option( 'advanced', 'lg_lightbox_thumbs_border_bg_color' ) );
$lg_lightbox_thumbs_border_bg_color_hover = a13_make_css_rule( 'border-color', $a13_apollo13->get_option( 'advanced' , 'lg_lightbox_thumbs_border_bg_color_hover' ));


/*
 *  fonts
 */
$temp               = explode(':', $a13_apollo13->get_option( 'fonts' , 'normal_fonts' ));
$normal_fonts       = ($temp[0] === 'default')? '' : a13_make_css_rule( 'font-family', $temp[0], '%s, sans-serif' );
$temp               = explode(':', $a13_apollo13->get_option( 'fonts' , 'titles_fonts' ));
$titles_fonts       = ($temp[0] === 'default')? '' : a13_make_css_rule( 'font-family', $temp[0], '%s, sans-serif' );
$temp               = explode(':', $a13_apollo13->get_option( 'fonts' , 'nav_menu_fonts' ));
$nav_menu_font      = ($temp[0] === 'default')? '' : a13_make_css_rule( 'font-family', $temp[0], '%s, sans-serif' );


$custom_CSS = $a13_apollo13->get_option( 'customize' , 'custom_css' );

/**********************************
 * START OF CSS
 **********************************/
$user_css = '';
$string_to_replace = '#fff/*$color*/';

//prelaoder
if($a13_apollo13->get_option( 'appearance' , 'preloader' ) === 'on'){
    $prelaoder_type     = $a13_apollo13->get_option( 'appearance' , 'preloader_type' );
    $prelaoder_color    = $a13_apollo13->get_option( 'appearance' , 'preloader_color' );
    if($prelaoder_type !== 'none'){
        $user_css .= str_replace($string_to_replace, $prelaoder_color, file_get_contents(A13_TPL_CSS_DIR . '/preloaders/'.$prelaoder_type.'.css'));
    }
}


//predefined colors
if($predefined_colors  !== 'default'){
    if($predefined_colors  === 'custom'){
        //use user defined color
        $predefined_colors = $a13_apollo13->get_option( 'appearance' , 'predefined_color_custom' );
    }
    $user_css .= str_replace($string_to_replace, $predefined_colors, file_get_contents(A13_TPL_CSS_DIR . '/schemes/scheme.css'));
}

$user_css .= "
/* ==================
   GLOBAL
   ==================*/
body{
    $cursor_css
}
.page-background{
    $global_bg_color
    $global_image
    $global_image_fit
}
.page .page-background{
    $page_bg_color
    $page_image
    $page_image_fit
}
.single-post .page-background,
.posts-list .page-background{
    $blog_bg_color
    $blog_image
    $blog_image_fit
}
.woocommerce-page .page-background{
    $shop_bg_color
    $shop_image
    $shop_image_fit
}
.single-album .page-background,
.albums-list-page .page-background{
    $album_bg_color
    $album_image
    $album_image_fit
}
.error404 .page-background{
	$error404_bg_image
}
.password-protected .page-background{
	$password_bg_image
}
#mid::before{
    $top_space_bg_color
    $top_space_space
}

/* PRELOADER */
#preloader{
    $prelaoder_bg_color
    $prelaoder_bg_image
    $prelaoder_bg_image_fit
}

/* GLOBAL SIDEBARS */
#basket-menu{
	$basket_sidebar_bg_color
}
#basket-menu, #basket-menu .widget{
	$basket_sidebar_font_size
}
#side-menu{
	$hidden_sidebar_bg_color
}
#side-menu, #side-menu .widget{
	$hidden_sidebar_font_size
}

/* lightbox */
.lg-backdrop {
    $lg_lightbox_bg_color
}
.lg-toolbar,
.lg-sub-html,
.lg-actions .lg-next, .lg-actions .lg-prev{
    $lg_lightbox_elements_bg_color
}
.lg-toolbar .lg-icon,
.lg-actions .lg-next, .lg-actions .lg-prev{
    $lg_lightbox_elements_color
}
.lg-toolbar .lg-icon:hover,
.lg-actions .lg-next:hover, .lg-actions .lg-prev:hover{
    $lg_lightbox_elements_color_hover
}
#lg-counter,
.lg-sub-html,
.customHtml > h4{
    $lg_lightbox_elements_text_color
}
.lg-outer .lg-thumb-outer,
.lg-outer .lg-toogle-thumb{
    $lg_lightbox_thumbs_bg_color
}
.lg-outer .lg-thumb-item {
    $lg_lightbox_thumbs_border_bg_color
}
.lg-outer .lg-thumb-item:hover {
    $lg_lightbox_thumbs_border_bg_color_hover
}


/* ==================
   TYPOGRAPHY
   ==================*/
/* Titles and titles alike font */
h1,h2,h3,h4,h5,h6,
h1 a,h2 a,h3 a,h4 a,h5 a, h6 a,
.page-title,
.widget .title{
    $headings_color
    $titles_fonts
    $headings_weight
    $headings_transform
}
h1 a:hover,h2 a:hover,h3 a:hover,h4 a:hover,h5 a:hover,h6 a:hover,
.post .post-title a:hover, .post a.post-title:hover{
    $headings_color_hover
}
input[type=\"submit\"],
button,
.posts-nav a span,
.woocommerce #respond input#submit,
.woocommerce a.button,
.woocommerce button.button,
.woocommerce input.button,
ul.products .product-meta .product_name{
    $titles_fonts
}

/* Top menu font */
ul.top-menu,
.lt-ie10 #header-tools input[type=\"text\"], #header-tools input[type=\"search\"]{
	$nav_menu_font
}

/* Text content font */
html,input,select,textarea{
    $normal_fonts
}


/* ==================
   TITLE BAR
   ==================*/
.title-bar .in h2{
   $headings_weight
}
.title-bar.outside{
    $title_bar_bg_color
}
.title-bar.outside .in{
    padding-top:$title_bar_space;
    padding-bottom:$title_bar_space;
}
.title-bar.outside .page-title,
.title-bar.outside .in h2{
    $title_bar_color
}
.title-bar .page-title{
    $title_bar_weight
    $title_bar_size
}
.title-bar.outside.subtitle .page-title:after{
    border-color:$title_bar_color_val;
}


/* ==================
   HEADER
   ==================*/
#header-tools .tool{
	$header_tools_bg
}
#header .head,
#header-tools .tool:hover,
#header-tools .tool.highlight,
#header-tools .tool.active,
#header-tools .search.opened{
    $header_bg_color
}
.lt-ie10 #header-tools input[type=\"text\"],
#header-tools input[type=\"search\"],
#header-tools .tool{
    $header_tools_color
}
#header-tools #mobile-menu-opener{
	$menu_color
}
/* LOGO */
a.logo{
	$logo_color
    $logo_font_size
    $logo_weight
    $titles_fonts
    padding-top: $logo_padding;
    padding-bottom: $logo_padding;
    $logo_image
    $logo_image_height
}
a.logo:hover{
	$logo_color_hover
    opacity: $logo_image_hover_o;
}

/* MAIN MENU */
.top-menu ul{
    $header_bg_color
    border-color: $menu_hover_color;
    $submenu_top_line
}
.top-menu > li{
    padding-left: $menu_element_padding;
    padding-right: $menu_element_padding;
}
.top-menu > li > a,
.top-menu > li > span.title,
.top-menu .mega-menu > ul > li > span.title,
.top-menu .mega-menu > ul > li > a{
    $menu_font_size
    $menu_weight
    $menu_transform
}
.top-menu li a,
.top-menu li span.title,
/* group titles */
.top-menu .mega-menu > ul > li > span.title,
.top-menu .mega-menu > ul > li > a{
    $menu_color
}
.top-menu i.sub-mark{
    $menu_color
}
/* hover and active */
.top-menu a:hover,
.top-menu li.menu-parent-item:hover > span.title,
.top-menu li.open > a,
.top-menu li.open > span.title,
.top-menu li.current-menu-item > a,
.top-menu li.current-menu-ancestor > a,
.top-menu li.current-menu-item > span.title,
.top-menu li.current-menu-ancestor > span.title,
.top-menu .mega-menu > ul > li > a:hover{
    color: $menu_hover_color;
}
.top-menu li.menu-parent-item:hover > span.title + i.sub-mark,
.top-menu i.sub-mark:hover,
.top-menu li.open > i.sub-mark{
    color: $menu_hover_color;
}
.top-menu span em, .top-menu a em{
    border-color: $menu_label_color;
    color: $menu_label_color;
}
.top-menu li li a,
.top-menu li li span.title{
    $submenu_font_size
    $submenu_color
    $submenu_weight
    $submenu_transform
}
.navigation-bar.touch .menu-container,
.navigation-bar.touch .top-menu ul{
    $header_bg_color
}



/* ==================
   FOOTER
   ==================*/
#footer{
   $footer_bg_color
}
#footer,
#footer .widget{
	$footer_font_size
}
.foot-widgets{
    $fw_border_color
}
.foot-items,
.footer-msg{
    $footer_font_color
}
.foot-items a,
#f-switch,
.f-audio .skip-button,
.f-audio .mejs-controls .mejs-button{
    $footer_link_color
}
.foot-items a:hover,
#f-switch:hover,
.open #f-switch,
.f-audio .skip-button:hover,
.f-audio .mejs-controls .mejs-button:hover{
    $footer_hover_color
}

@media only screen and (max-width: 768px) {
	#f-switch{
		$footer_bg_color
	}
}


/* ==================
   PAGES
   ==================*/
.page #mid:before{
    $page_top_space_bg_color
    $page_top_space_space
}
/* Title bar */
.page .title-bar.outside{
    $page_title_bar_bg_color
}
.page .title-bar.outside .in{
    padding-top:$page_title_bar_space;
    padding-bottom:$page_title_bar_space;
}
.page .title-bar.outside .page-title,
.page .title-bar.outside .in h2{
    $page_title_bar_color
}
.page .title-bar .page-title{
    $page_title_bar_weight
    $page_title_bar_size
}
.page .title-bar.outside.subtitle .page-title:after{
    border-color:$page_title_bar_color_val;
}


/* ==================
   WORKS LIST
   ==================*/
.single-album #mid:before,
.albums-list-page #mid:before{
    $album_top_space_bg_color
    $album_top_space_space
}
/* Title bar */
.single-album .title-bar.outside,
.albums-list-page .title-bar.outside{
    $album_title_bar_bg_color
}
.single-album .title-bar.outside .in,
.albums-list-page .title-bar.outside .in{
    padding-top:$album_title_bar_space;
    padding-bottom:$album_title_bar_space;
}
.single-album .title-bar.outside .page-title,
.single-album .title-bar.outside .in h2,
.albums-list-page .title-bar.outside .page-title,
.albums-list-page .title-bar.outside .in h2{
    $album_title_bar_color
}
.single-album .title-bar .page-title,
.albums-list-page .title-bar .page-title{
    $album_title_bar_weight
    $album_title_bar_size
}
.single-album .title-bar.outside.subtitle .page-title:after,
.albums-list-page .title-bar.outside.subtitle .page-title:after{
    border-color:$album_title_bar_color_val;
}


.albums-list-page .bricks-frame{
	$album_bricks_max_width
}
#only-albums-here{
	margin-right: -$album_margin;
}

/* 4 columns */
.albums-list-page .bricks-columns-4 .archive-item,
.albums-list-page .bricks-columns-4 .grid-master{
	width: -webkit-calc(25% - $album_margin);
	width:         calc(25% - $album_margin);
}
.albums-list-page .bricks-columns-4 .archive-item.w2{
	width: -webkit-calc(50% - $album_margin);
	width:         calc(50% - $album_margin);
}
.albums-list-page .bricks-columns-4 .archive-item.w3{
	width: -webkit-calc(75% - $album_margin);
	width:         calc(75% - $album_margin);
}

/* 3 columns */
.albums-list-page .bricks-columns-3 .archive-item,
.albums-list-page .bricks-columns-3 .grid-master{
	width: -webkit-calc(33.3% - $album_margin);
	width:         calc(33.3% - $album_margin);
}
.albums-list-page .bricks-columns-3 .archive-item.w2{
	width: -webkit-calc(66.6% - $album_margin);
	width:         calc(66.6% - $album_margin);
}

/* 2 columns */
.albums-list-page .bricks-columns-2 .archive-item,
.albums-list-page .bricks-columns-2 .grid-master{
	width: -webkit-calc(50% - $album_margin);
	width:         calc(50% - $album_margin);
}

/* 100% width bricks */
.albums-list-page .bricks-columns-1 .archive-item,
.albums-list-page .bricks-columns-2 .archive-item.w2,
.albums-list-page .bricks-columns-2 .archive-item.w3,
.albums-list-page .bricks-columns-2 .archive-item.w4,
.albums-list-page .bricks-columns-3 .archive-item.w3,
.albums-list-page .bricks-columns-3 .archive-item.w4,
.albums-list-page .bricks-columns-4 .archive-item.w4{
	width: -webkit-calc(100% - $album_margin);
	width:         calc(100% - $album_margin);
}

/* responsive rules */
@media only screen and (max-width: 1270px) {
	/* 3 columns */
	.albums-list-page .bricks-columns-4 .archive-item,
	.albums-list-page .bricks-columns-4 .grid-master{
		width: -webkit-calc(33.3% - $album_margin);
		width:         calc(33.3% - $album_margin);
	}
	.albums-list-page .bricks-columns-4 .archive-item.w2{
		width: -webkit-calc(66.6% - $album_margin);
		width:         calc(66.6% - $album_margin);
	}
	.albums-list-page .bricks-columns-4 .archive-item.w3{
		width: -webkit-calc(100% - $album_margin);
		width:         calc(100% - $album_margin);
	}
}
@media only screen and (max-width: 1024px) {
	/* 2 columns */
	.albums-list-page .bricks-columns-4 .archive-item,
	.albums-list-page .bricks-columns-4 .grid-master,
	.albums-list-page .bricks-columns-4 .archive-item.w2,
	.albums-list-page .bricks-columns-3 .archive-item,
	.albums-list-page .bricks-columns-3 .grid-master{
		width: -webkit-calc(50% - $album_margin);
		width:         calc(50% - $album_margin);
	}
	.albums-list-page .bricks-columns-4 .archive-item.w3,
	.albums-list-page .bricks-columns-3 .archive-item.w2{
		width: -webkit-calc(100% - $album_margin);
		width:         calc(100% - $album_margin);
	}
}
@media only screen and (max-width: 600px) {
	/* 1 column */
	.albums-list-page .bricks-frame .archive-item{
		width: 100% !important; /* we unify all possible options of bricks width */
	}
}


   
/* ==================
   POSTS LIST & POST
   ==================*/
.single-post #mid:before,
.posts-list #mid:before{
    $blog_top_space_bg_color
    $blog_top_space_space
}
/* Title bar */
.single-post .title-bar.outside,
.posts-list .title-bar.outside{
    $blog_title_bar_bg_color
}
.single-post .title-bar.outside .in,
.posts-list .title-bar.outside .in{
    padding-top:$blog_title_bar_space;
    padding-bottom:$blog_title_bar_space;
}
.single-post .title-bar.outside .page-title,
.single-post .title-bar.outside .in h2,
.posts-list .title-bar.outside .page-title,
.posts-list .title-bar.outside .in h2{
    $blog_title_bar_color
}
.single-post .title-bar .page-title,
.posts-list .title-bar .page-title{
    $blog_title_bar_weight
    $blog_title_bar_size
}
.single-post .title-bar.outside.subtitle .page-title:after,
.posts-list .title-bar.outside.subtitle .page-title:after{
    border-color:$blog_title_bar_color_val;
}


.posts-list .bricks-frame{
	$blog_bricks_max_width
}
#only-posts-here{
	margin-right: -$blog_margin;
}

/* 4 columns */
.posts-list .bricks-columns-4 .archive-item,
.posts-list .bricks-columns-4 .grid-master{
	width: -webkit-calc(25% - $blog_margin);
	width:         calc(25% - $blog_margin);
}
.posts-list .bricks-columns-4 .archive-item.w2{
	width: -webkit-calc(50% - $blog_margin);
	width:         calc(50% - $blog_margin);
}
.posts-list .bricks-columns-4 .archive-item.w3{
	width: -webkit-calc(75% - $blog_margin);
	width:         calc(75% - $blog_margin);
}

/* 3 columns */
.posts-list .bricks-columns-3 .archive-item,
.posts-list .bricks-columns-3 .grid-master{
	width: -webkit-calc(33.3% - $blog_margin);
	width:         calc(33.3% - $blog_margin);
}
.posts-list .bricks-columns-3 .archive-item.w2{
	width: -webkit-calc(66.6% - $blog_margin);
	width:         calc(66.6% - $blog_margin);
}

/* 2 columns */
.posts-list .bricks-columns-2 .archive-item,
.posts-list .bricks-columns-2 .grid-master{
	width: -webkit-calc(50% - $blog_margin);
	width:         calc(50% - $blog_margin);
}

/* 100% width bricks */
.posts-list .bricks-columns-1 .archive-item,
.posts-list .bricks-columns-2 .archive-item.w2,
.posts-list .bricks-columns-2 .archive-item.w3,
.posts-list .bricks-columns-2 .archive-item.w4,
.posts-list .bricks-columns-3 .archive-item.w3,
.posts-list .bricks-columns-3 .archive-item.w4,
.posts-list .bricks-columns-4 .archive-item.w4{
	width: -webkit-calc(100% - $blog_margin);
	width:         calc(100% - $blog_margin);
}

/* ON IMAGE */
.on-image .post-title,
.on-image .post-title a{
    $onimage_title_color
}
.on-image .formatter,
.post .on-image .post-meta,
.post .on-image .real-content > p:first-child{
    $onimage_text_color
}
.on-image .formatter a,
.post .on-image .post-meta a{
    $onimage_link_color
}
.on-image .formatter a:hover,
.post .on-image .post-meta a:hover{
    $onimage_link_hover_color
}

.post .on-image .under_content_tags a{
	$onimage_border_color
}
.post .on-image .under_content_tags a:hover{
	background-color: transparent;
	$onimage_border_hover_color
}

/* sidebars */
.posts-list .layout-full.with-sidebar .content-box,
.posts-list .layout-full_fixed.with-sidebar .content-box,
.posts-list .layout-full_padding.with-sidebar .content-box{
	margin-left: $blog_margin;
	width: -webkit-calc(75% - $blog_margin);
	width: 		   calc(75% - $blog_margin);
}
.posts-list .layout-full.right-sidebar .content-box,
.posts-list .layout-full_fixed.right-sidebar .content-box,
.posts-list .layout-full_padding.right-sidebar .content-box{
	margin-left: 0;
	margin-right: $blog_margin;
}

/* responsive rules */
@media only screen and (min-width: 1280px) {
	.posts-list .layout-full.with-sidebar .content-box{
		width: -webkit-calc(100% - 320px - $blog_margin); /* 320 sidebar*/
		width: 		   calc(100% - 320px - $blog_margin);
	}
}
@media only screen and (min-width: 1360px) {
	.posts-list .layout-full_padding.with-sidebar .content-box{
		width: -webkit-calc(100% - 320px - $blog_margin); /* 320 sidebar*/
		width: 		   calc(100% - 320px - $blog_margin);
	}
}
@media only screen and (max-width: 1270px) {
	/* 3 columns */
	.posts-list .bricks-columns-4 .archive-item,
	.posts-list .bricks-columns-4 .grid-master{
		width: -webkit-calc(33.3% - $blog_margin);
		width:         calc(33.3% - $blog_margin);
	}
	.posts-list .bricks-columns-4 .archive-item.w2{
		width: -webkit-calc(66.6% - $blog_margin);
		width:         calc(66.6% - $blog_margin);
	}
	.posts-list .bricks-columns-4 .archive-item.w3{
		width: -webkit-calc(100% - $blog_margin);
		width:         calc(100% - $blog_margin);
	}

	/* 2 columns */
	.posts-list .with-sidebar .bricks-columns-3 .archive-item,
	.posts-list .with-sidebar .bricks-columns-3 .grid-master{
		width: -webkit-calc(50% - $blog_margin);
		width:         calc(50% - $blog_margin);
	}
	.posts-list .with-sidebar .bricks-columns-3 .archive-item.w2{
		width: -webkit-calc(100% - $blog_margin);
		width:         calc(100% - $blog_margin);
	}

}
@media only screen and (max-width: 1024px) {
	.posts-list .layout-full.with-sidebar .content-box,
	.posts-list .layout-full_fixed.with-sidebar .content-box,
	.posts-list .layout-full_padding.with-sidebar .content-box{
		width: -webkit-calc(70% - $blog_margin);
		width: 		   calc(70% - $blog_margin);
	}

	/* 2 columns */
	.posts-list .bricks-columns-4 .archive-item,
	.posts-list .bricks-columns-4 .grid-master,
	.posts-list .bricks-columns-4 .archive-item.w2,
	.posts-list .bricks-columns-3 .archive-item,
	.posts-list .bricks-columns-3 .grid-master{
		width: -webkit-calc(50% - $blog_margin);
		width:         calc(50% - $blog_margin);
	}
	.posts-list .bricks-columns-4 .archive-item.w3,
	.posts-list .bricks-columns-3 .archive-item.w2{
		width: -webkit-calc(100% - $blog_margin);
		width:         calc(100% - $blog_margin);
	}
}
@media only screen and (max-width: 768px) {
	.posts-list .layout-full.with-sidebar .content-box,
	.posts-list .layout-full_fixed.with-sidebar .content-box,
	.posts-list .layout-full_padding.with-sidebar .content-box{
		width: auto;
		margin-left: 0;
		margin-right: 0;
	}
}
@media only screen and (max-width: 600px) {
	#only-posts-here{
		margin-right: 0;
	}
	/* 1 column */
	.posts-list .bricks-frame .archive-item{
		width: 100% !important; /* we unify all possible options of bricks width */
	}
}

             
/* ==================
   SHOP & PRODUCT
   ==================*/
.woocommerce-page #mid:before{
    $shop_top_space_bg_color
    $shop_top_space_space
}
/* Title bar */
.woocommerce-page .title-bar.outside{
    $shop_title_bar_bg_color
}
.woocommerce-page .title-bar.outside .in{
    padding-top:$shop_title_bar_space;
    padding-bottom:$shop_title_bar_space;
}
.woocommerce-page .title-bar.outside .page-title,
.woocommerce-page .title-bar.outside .in h2{
    $shop_title_bar_color
}
.woocommerce-page .title-bar .page-title{
    $shop_title_bar_weight
    $shop_title_bar_size
}
.woocommerce-page .title-bar.outside.subtitle .page-title:after{
    border-color:$shop_title_bar_color_val;
}



/* ==================
   CONTENT
   ==================*/
#content{
    $content_font_size
    $content_font_color
}
.real-content > p:first-child{
    $content_first_p_show
    $content_first_p_color
}


/* ==================
   RESPONSIVE
   ==================*/
@media print,
(-o-min-device-pixel-ratio: 5/4),
(-webkit-min-device-pixel-ratio: 1.25),
(min-resolution: 120dpi) {
	a.logo{
	    $logo_image_2x
	}
}

/* ==================
   CUSTOM CSS
   ==================*/
".stripslashes($custom_CSS)."
";

return $user_css;
