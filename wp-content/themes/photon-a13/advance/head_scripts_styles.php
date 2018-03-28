<?php

/*
 * Frontend theme scripts
 */
add_action( 'wp_enqueue_scripts', 'a13_theme_scripts', 26 ); //put it later then woocommerce
if(!function_exists('a13_theme_scripts')){
    function a13_theme_scripts($special_pass = false){
        global $a13_apollo13;

        if((is_admin() || 'wp-login.php' == basename($_SERVER['PHP_SELF'])) && !$special_pass){
            return;
        }

        $page_type      = a13_what_page_type_is_it();
        $album          = $page_type['album'];

        //Modernizr custom build
        wp_enqueue_script( 'a13-modernizr-custom', A13_TPL_JS . '/modernizr.custom.js', false, '2.8.3', false);

        /* We add some JavaScript to pages with the comment form
          * to support sites with threaded comments (when in use).
          */
        if ( is_singular() && get_option( 'thread_comments' ) ){
            wp_enqueue_script( 'comment-reply' );
        }

        $script_depends = array( 'apollo13-plugins' );

        //plugins used in theme (cheat sheet)
        wp_register_script('apollo13-plugins', A13_TPL_JS . '/plugins.js',
            array('jquery'), //depends
            A13_THEME_VER, //version number
            true //in footer
        );


	    //AJAXIFY
	    $is_ajax_on             = $a13_apollo13->get_option( 'advanced', 'ajax' ) === 'on';
	    $is_ajax_admin_bar_on   = !is_admin_bar_showing() || (is_admin_bar_showing() && $a13_apollo13->get_option( 'advanced', 'ajax_admin_bar' ) === 'on');
	    $we_are_ajaxing         = false;
	    if(!is_customize_preview() && $is_ajax_on && $is_ajax_admin_bar_on && !a13_is_woocommerce() ){
		    $we_are_ajaxing = true;
	    }


        //Animation library
        wp_register_script( 'a13-green-sock', A13_TPL_JS . '/TweenMax.min.js', array('jquery'), '1.16.1', true);

        //APOLLO Slider
        wp_register_script( 'a13-slider', A13_TPL_JS . '/a13-slider.js', array('jquery', 'a13-green-sock'), A13_THEME_VER, true);

        //counter - for counter shortcode
        wp_register_script( 'jquery.countTo', A13_TPL_JS . '/jquery.countTo.js', array('jquery'), '1.0', true);

		//lightGallery lightbox
	    wp_register_script( 'a13-lightGallery', A13_TPL_JS . '/light-gallery/js/lightgallery-all.min.js', array('jquery'), '1.2.14', true);

	    //nice scroll bars
	    wp_register_script( 'a13-nicescroll', A13_TPL_JS . '/jquery.nicescroll/jquery.nicescroll.min.js', array('jquery'), '3.6.0', true);
	    $script_depends[] = 'a13-nicescroll';

	    //isotope for bricks
	    wp_register_script( 'a13-isotope', A13_TPL_JS . '/isotope.pkgd.min.js', array('jquery'), '2.2.0', true);
	    $script_depends[] = 'a13-isotope';

		//slider for albums
        if( $we_are_ajaxing || ($album && $a13_apollo13->get_meta('_theme') === 'slider') ){
	        $script_depends[] = 'a13-slider';
        }

        //bricks videos
	    if($we_are_ajaxing || ($album && $a13_apollo13->get_meta('_theme') === 'bricks')){
		    $script_depends[] = 'mediaelement';
	    }

	    //lightbox
	    $lightbox = $a13_apollo13->get_option( 'advanced', 'apollo_lightbox' );
	    if( $lightbox === 'lightGallery' ){
		    $script_depends[] = 'a13-lightGallery';
	    }

	    //music enabled
	    $music = $a13_apollo13->get_option( 'settings', 'music' ) === 'on';
	    if( $music ){
		    $script_depends[] = 'wp-util';
		    $script_depends[] = 'backbone';
		    $script_depends[] = 'mediaelement';
	    }



        //options passed to JS
        $apollo_params = a13_js_parameters();
        //hand written scripts for theme
        wp_enqueue_script('apollo13-scripts', A13_TPL_JS . '/script.js', $script_depends, A13_THEME_VER, true );
        //transfer options
        wp_localize_script( 'apollo13-plugins', 'ApolloParams', $apollo_params );
    }
}

if(!function_exists('a13_js_parameters')){
    function a13_js_parameters(){
        global $a13_apollo13;

        $params = array(
            /* GLOBAL OPTIONS */
            'is_ajaxed'                 => $a13_apollo13->get_option( 'advanced', 'ajax' ) === 'on',
            'ajaxurl'                   => admin_url('admin-ajax.php'),
            'site_url'                  => site_url().'/',
            'jsurl'                     => A13_TPL_JS,
            'defimgurl'                 => A13_TPL_GFX . '/holders/photo.png',

	        /* MISC */
            'msg_cookie_string'         => $a13_apollo13->get_option( 'appearance', 'footer_msg_new' ),
            'full_screen_behaviour'     => $a13_apollo13->get_option( 'appearance', 'full_screen_behaviour' ),
            'load_more'                 => __( 'Load more', 'photon' ),
            'loading_items'             => __( 'Loading next items', 'photon' ),

	        /* MUSIC */
	        'music'                     => $a13_apollo13->get_option( 'settings', 'music' ) === 'on',
	        'music_autoplay'            => $a13_apollo13->get_option( 'settings', 'music_autoplay'.(a13_is_woocommerce()? '_shop' : '') ),

	        /* BLOG */
	        'posts_brick_margin'        => $a13_apollo13->get_option( 'blog', 'brick_margin' ),

	        /* ALBUMS */
	        'albums_list_brick_margin'  => $a13_apollo13->get_option( 'album', 'brick_margin' ),
	        'album_bricks_thumb_video'  => $a13_apollo13->get_option( 'album', 'album_bricks_thumb_video' ) === 'on',

	        /* lightGallery lightbox */
	        'lg_lightbox_controls' => $a13_apollo13->get_option( 'advanced', 'lg_lightbox_controls' ) === 'on',
			'lg_lightbox_download' => $a13_apollo13->get_option( 'advanced', 'lg_lightbox_download' ) === 'on',
			'lg_lightbox_counter' => $a13_apollo13->get_option( 'advanced', 'lg_lightbox_counter' ) === 'on',
			'lg_lightbox_thumbnail' => $a13_apollo13->get_option( 'advanced', 'lg_lightbox_thumbnail' ) === 'on',
			'lg_lightbox_show_thumbs' => $a13_apollo13->get_option( 'advanced', 'lg_lightbox_show_thumbs' ) === 'on',
			'lg_lightbox_autoplay' => $a13_apollo13->get_option( 'advanced', 'lg_lightbox_autoplay' ) === 'on',
			'lg_lightbox_autoplay_open' => $a13_apollo13->get_option( 'advanced', 'lg_lightbox_autoplay_open' ) === 'on',
			'lg_lightbox_full_screen' => $a13_apollo13->get_option( 'advanced', 'lg_lightbox_full_screen' ) === 'on',
			'lg_lightbox_zoom' => $a13_apollo13->get_option( 'advanced', 'lg_lightbox_zoom' ) === 'on',
			'lg_lightbox_mode' => $a13_apollo13->get_option( 'advanced', 'lg_lightbox_mode' ),
			'lg_lightbox_speed' => $a13_apollo13->get_option( 'advanced', 'lg_lightbox_speed' ),

        );

        return $params;
    }
}

/*
 * Adds CSS files to theme
 */
add_action( 'wp_enqueue_scripts', 'a13_theme_styles', 26 ); //put it later then woocommerce
if(!function_exists('a13_theme_styles')){
    function a13_theme_styles($special_pass = false){
        if((is_admin() || 'wp-login.php' == basename($_SERVER['PHP_SELF'])) && !$special_pass){
            return;
        }

        global $a13_apollo13;

        $user_css_depends = array('main-style');


	    //woocommerce
	    if(a13_is_woocommerce_activated()){
		    array_push($user_css_depends,'a13-woocommerce');
		    wp_register_style( 'a13-woocommerce', A13_TPL_CSS . '/woocommerce.css', array('main-style'), A13_THEME_VER);
	    }

	    wp_register_style( 'font-awesome', A13_TPL_CSS.'/font-awesome.min.css', false, '4.6.3');
	    wp_register_style( 'icomoon', A13_TPL_CSS.'/icomoon.css', false, A13_THEME_VER);
        wp_register_style( 'main-style', A13_TPL_URI . '/style.css', array('font-awesome', 'icomoon'), A13_THEME_VER);

	    //lightGallery lightbox
	    wp_register_style( 'a13-lightGallery-transitions', A13_TPL_JS . '/light-gallery/css/lg-transitions.min.css', false, '1.2.14' );
	    $lg_default_transition = $a13_apollo13->get_option( 'advanced', 'lg_lightbox_mode' ) === 'lg-slide';
	    wp_register_style( 'a13-lightGallery', A13_TPL_JS . '/light-gallery/css/lightgallery.css', ($lg_default_transition ? false : array('a13-lightGallery-transitions')), '1.2.14' );


	    //lightbox
	    $lightbox = $a13_apollo13->get_option( 'advanced', 'apollo_lightbox' );
	    if( $lightbox === 'lightGallery' ){
		    wp_enqueue_style('a13-lightGallery');
	    }

	    //music enabled
	    $music = $a13_apollo13->get_option( 'settings', 'music' ) === 'on';
	    if( $music ){
		    wp_enqueue_style( 'wp-mediaelement' );
	    }


        wp_register_style('user-css', $a13_apollo13->user_css_name(true), $user_css_depends, A13_THEME_VER);

	    //in customizer we embed user.css file inline
	    if(is_customize_preview()){
		    wp_enqueue_style('main-style');
	    }
	    else{
	        wp_enqueue_style('user-css');
	    }
    }
}

/*
 * adds google fonts (cause JSON is not easily passed by wp_localize_script
 */
if(!function_exists('a13_theme_head')){
    function  a13_theme_head(){
        if(is_admin() || 'wp-login.php' == basename($_SERVER['PHP_SELF'])){
            return;
        }

        global $a13_apollo13;

        //WEB FONTS LOADING
        $fonts = array( 'families' => array());
        //check if classic or google font is selected
        //colon in name = google font
        $temp = $a13_apollo13->get_option('fonts', 'normal_fonts');
        (strpos($temp, ':') !== false)? array_push($fonts['families'], $temp) : false;
        $temp = $a13_apollo13->get_option('fonts', 'titles_fonts');
        (strpos($temp, ':') !== false)? array_push($fonts['families'], $temp) : false;
        $temp = $a13_apollo13->get_option('fonts', 'nav_menu_fonts');
        (strpos($temp, ':') !== false)? array_push($fonts['families'], $temp) : false;

        if(sizeof($fonts['families'])):
            $fonts = wp_json_encode($fonts);
    ?>

    <script type="text/javascript" id="dddd">
        // <![CDATA[
        WebFontConfig = {
            google: <?php echo $fonts; /* no escaping here as it was encoded to json */ ?>,
            active: function() {
                //tell listeners that fonts are loaded
				if (window.jQuery){
                	jQuery(document.body).trigger('webfontsloaded');
				}
            }
        };
        (function() {
            var wf = document.createElement('script');
            wf.src = '<?php echo A13_TPL_JS; ?>/webfontloader.js';
            wf.type = 'text/javascript';
            wf.async = 'true';
            var s = document.getElementsByTagName('script')[0];
            s.parentNode.insertBefore(wf, s);
        })();
	    // ]]>
    </script>

    <?php
        endif;
    }
}

add_action( 'wp_print_footer_scripts', 'a13_custom_js', 100 );
if(!function_exists('a13_custom_js')){
	function a13_custom_js(){
		global $a13_apollo13;


		$js                     = $a13_apollo13->get_option( 'advanced', 'custom_js' );
		$we_are_ajaxing         = false;
		$is_ajax_on             = $a13_apollo13->get_option( 'advanced', 'ajax' ) === 'on';
		$is_ajax_admin_bar_on   = !is_admin_bar_showing() || (is_admin_bar_showing() && $a13_apollo13->get_option( 'advanced', 'ajax_admin_bar' ) === 'on');

		if(!is_customize_preview() && $is_ajax_on && $is_ajax_admin_bar_on && !a13_is_woocommerce() ){
			$we_are_ajaxing = true;
		}

		//no ajax pages array
		if($we_are_ajaxing){
			$no_ajax_pages = a13_get_no_ajax_pages();
			if(sizeof($no_ajax_pages)){
				$temp_js = '';

				for($i = 0; $i < sizeof($no_ajax_pages); $i++){
					if(strlen($no_ajax_pages[$i])){
						//add comma after each position, but not after last
						if(strlen($temp_js)){
							$temp_js .= ',';
						}
						$temp_js .= '\''.$no_ajax_pages[$i].'\'';
					}
				}

				$no_ajax_pages_js = 'var a13_no_ajax_pages = ['.$temp_js.']';
			}
			if( sizeof($no_ajax_pages) ){?>
<script type="text/javascript"><?php echo $no_ajax_pages_js; ?></script>
			<?php
			}
		}

		//custom JS
		if ( ! is_admin() && strlen( $js )  ) {?>
<script type="text/javascript"><?php echo $js; ?></script>
		<?php
		}
	}
}


//remove some conflicts with Visual Composer
add_action( 'vc_base_register_front_js', 'a13_remove_vc_conflicts' );
if(!function_exists('a13_remove_vc_conflicts')){
	function a13_remove_vc_conflicts(){
		global $a13_apollo13;

		if(defined( 'WPB_VC_VERSION' )){
			wp_deregister_script( 'isotope' );
			wp_register_script( 'a13-isotope', A13_TPL_JS . '/isotope.pkgd.min.js', array('jquery'), '2.2.0', true);

			//ajax safe
			$is_ajax_on             = $a13_apollo13->get_option( 'advanced', 'ajax' ) === 'on';
			$is_ajax_admin_bar_on   = !is_admin_bar_showing() || (is_admin_bar_showing() && $a13_apollo13->get_option( 'advanced', 'ajax_admin_bar' ) === 'on');
			if(!is_customize_preview() && $is_ajax_on && $is_ajax_admin_bar_on && !a13_is_woocommerce() ){
				wp_enqueue_script( 'wpb_composer_front_js' );
			}
		}
	}
}