<?php

	function apollo13_settings_options(){

		$opt = array(
            array(
                'name' =>  a13__be( 'Front page' ),
                'type' => 'fieldset',
                'id' => 'fieldset_front_page',
            ),
            array(
                'name' =>  a13__be( 'What to show on front page?' ),
                'description' => a13__be( 'If you choose <strong>Page</strong> then make sure that in Settings->Reading->Front page displays'
                                . ' you selected <strong>A static page</strong>, that you wish to use.<br />' ),

                'id' => 'fp_variant',
                'default' => 'page',
                'options' => array(
                    'page'          =>  a13__be( 'Page' ),
                    'blog'          =>  a13__be( 'Blog' ),
                    'single_album'    =>  a13__be( 'Single album' ),
                    'albums_list'    =>  a13__be( 'Albums list' ),
                ),
                'type' => 'select',
            ),
			array(
				'name' => a13__be( 'Select album to use as front page' ),
				'id' => 'fp_album',
				'default' => '',
				'type' => 'wp_dropdown_albums',
				'required' => array(
					'fp_variant' => 'single_album',
				)
			),



			array(
				'name' =>  a13__be( 'Music' ),
				'type' => 'fieldset',
                'id' => 'fieldset_music'
			),
			array(
				'name' =>  a13__be( 'Music' ),
				'description' => a13__be( 'If disabled then audio player will be hidden.' ),
				'id' => 'music',
				'default' => 'off',
				'type' => 'radio',
				'options' => array(
					'on' =>  a13__be( 'On' ),
					'off'    =>  a13__be( 'Turn it off' ),
				),
			),
			array(
				'name' =>  a13__be( 'Autoplay' ),
				'id' => 'music_autoplay',
				'default' => '1',
				'options' => array(
					'1' =>  a13__be( 'Enable' ),
					'0' =>  a13__be( 'Disable' ),
				),
				'type' => 'radio',
				'required' => array(
					'music' => 'on',
				)
			),
			array(
				'name' =>  a13__be( 'Autoplay for shop pages' ),
				'id' => 'music_autoplay_shop',
				'description' => a13__be( 'Shop pages can\'t be loaded with AJAX, so music playing from start on each page may be annoying for your users. '),
				'default' => '0',
				'options' => array(
					'1' =>  a13__be( 'Enable' ),
					'0' =>  a13__be( 'Disable' ),
				),
				'type' => 'radio',
				'required' => array(
					'music' => 'on',
				)
			),
			array(
				'name' =>  a13__be( 'Random track order' ),
				'id' => 'music_random',
				'default' => '0',
				'options' => array(
					'1' =>  a13__be( 'Enable' ),
					'0' =>  a13__be( 'Disable' ),
				),
				'type' => 'radio',
				'required' => array(
					'music' => 'on',
				)
			),
			array(
				'name' =>  a13__be( 'Song 1' ),
				'id' => 'song_1',
				'default' => '',
				'type' => 'upload',
				'required' => array(
					'music' => 'on',
				)
			),
			array(
				'name' =>  a13__be( 'Song 2' ),
				'id' => 'song_2',
				'default' => '',
				'type' => 'upload',
				'required' => array(
					'music' => 'on',
				)
			),
			array(
				'name' =>  a13__be( 'Song 3' ),
				'id' => 'song_3',
				'default' => '',
				'type' => 'upload',
				'required' => array(
					'music' => 'on',
				)
			),
			array(
				'name' =>  a13__be( 'Song 4' ),
				'id' => 'song_4',
				'default' => '',
				'type' => 'upload',
				'required' => array(
					'music' => 'on',
				)
			),
			array(
				'name' =>  a13__be( 'Song 5' ),
				'id' => 'song_5',
				'default' => '',
				'type' => 'upload',
				'required' => array(
					'music' => 'on',
				)
			),
		);


		$args = array(
			'title'         => a13__be( 'General' ),
			'opt'           => $opt
		);
		
		return $args;
	}

	function apollo13_appearance_options(){
        $cursors = array();
        $dir = A13_TPL_GFX_DIR.'/cursors';
        if( is_dir( $dir ) ) {
            //The GLOB_BRACE flag is not available on some non GNU systems, like Solaris. So we use merge:-)
            foreach ( (array)glob($dir.'/*.png') as $file ){
                $cursors[ basename($file) ] = basename($file);
            }
        }

		$opt = array(
            array(
                'name' =>  a13__be( 'Main Settings' ),
                'type' => 'fieldset',
                'id' => 'fieldset_main_app_settings',
            ),
            array(
                'name' =>  a13__be( 'Predefined colors' ),
                'description' => a13__be( 'It changes colors of various links(mainly hovers), buttons and interactive elements. Some of these can be overwritten with other settings.' ),
                'id' => 'predefined_colors',
                'default' => 'default',
                'options' => array(
                    'default'       =>  a13__be( 'Default' ),
                    'custom'        =>  a13__be( 'Custom color' ),
                    '#27ae60'       =>  a13__be( 'Green' ),
                    '#1abc9c'       =>  a13__be( 'Green (Turquoise)' ),
                    '#3498db'       =>  a13__be( 'Blue' ),
                    '#475577'       =>  a13__be( 'Dark Blue' ),
                    '#9365B8'       =>  a13__be( 'Violet' ),
                    '#f39c12'       =>  a13__be( 'Orange' ),
                    '#e67e22'       =>  a13__be( 'Carrot' ),
                    '#e74c3c'       =>  a13__be( 'Red' ),
                    '#75706B'       =>  a13__be( 'Iron Grey' ),
                    '#A38F84'       =>  a13__be( 'Light Brown' ),
                ),
                'type' => 'select',
            ),
            array(
                'name' =>  a13__be( 'Custom color' ),
                'id' => 'predefined_color_custom',
                'default' => '',
                'type' => 'color',
                'required' => array(
	                'predefined_colors' => 'custom',
                )
            ),
            array(
                'name' =>  a13__be( 'Favicon' ),
                'description' => a13__be( 'It will appear in adress bar or on tab in browser. Image should be square (16x16px or 32x32px).' ),
                'id' => 'favicon',
                'default' => get_template_directory_uri().'/images/defaults/favicon.png',
                'type' => 'image',
            ),
            array(
                'name' =>  a13__be( 'Background image' ),
                'id' => 'body_image',
                'default' => '',
                'type' => 'image'
            ),
            array(
                'name' =>  a13__be( 'How to fit background image' ),
                'id' => 'body_image_fit',
                'default' => 'cover',
                'options' => array(
                    'cover'     =>  a13__be( 'Cover' ),
                    'contain'   =>  a13__be( 'Contain' ),
                    'fitV'      =>  a13__be( 'Fit Vertically' ),
                    'fitH'      =>  a13__be( 'Fit Horizontally' ),
                    'center'    =>  a13__be( 'Just center' ),
                    'repeat'    =>  a13__be( 'Repeat' ),
                    'repeat-x'  =>  a13__be( 'Repeat X' ),
                    'repeat-y'  =>  a13__be( 'Repeat Y' ),
                ),
                'type' => 'select',
            ),
            array(
                'name' =>  a13__be( 'Background color' ),
                'id' => 'body_bg_color',
                'default' => '',
                'type' => 'color'
            ),
            array(
                'name' =>  a13__be( 'Mouse cursor' ),
                'id' => 'custom_cursor',
                'default' => 'default',
                'options' => array(
                    'default' =>  a13__be( 'Normal' ),
                    'select'  =>  a13__be( 'Predefined' ),
                    'custom' =>  a13__be( 'Custom' ),
                ),
                'type' => 'radio',
            ),
            array(
                'name' =>  a13__be( 'Cursors' ),
                'id' => 'cursor_select',
                'default' => 'empty_black_white.png',
                'options' => $cursors,
                'type' => 'select',
                'required' => array(
	                'custom_cursor' => 'select',
                )
            ),
            array(
                'name' =>  a13__be( 'Custom cursor image' ),
                'id' => 'cursor_image',
                'default' => '',
                'type' => 'image',
                'required' => array(
	                'custom_cursor' => 'custom',
                )
            ),



            array(
                'name' =>  a13__be( 'Page preloader' ),
                'type' => 'fieldset',
                'id' => 'fieldset_page_preloader',
            ),
            array(
                'name' =>  a13__be( 'Page preloader' ),
                'description' => a13__be( 'CSS animations used in preloader works best in modern browsers.' ),
                'id' => 'preloader',
                'default' => 'on',
                'type' => 'radio',
                'options' => array(
                    'on' =>  a13__be( 'On' ),
                    'off'    =>  a13__be( 'Turn it off' ),
                ),
            ),
			array(
				'name' =>  a13__be( 'Hide event' ),
				'description' => a13__be( '<strong>On load</strong> is called when whole site with all images are loaded, what can take lot of time on heavier sites, and even more on mobile. Also it can sometimes hang and never hide, when there is problem with some resource. <br /><strong>On DOM ready</strong> is called when whole HTML with CSS is loaded, so after preloader will hide, you can still see loading images.' ),
				'id' => 'preloader_hide_event',
				'default' => 'ready',
				'type' => 'radio',
				'options' => array(
					'ready'    =>  a13__be( 'On DOM ready' ),
					'load' =>  a13__be( 'On load' ),
				),
				'required' => array(
					'preloader' => 'on',
				)
			),
			array(
				'name' =>  a13__be( 'Background image' ),
				'id' => 'preloader_bg_image',
				'default' => '',
				'type' => 'image',
				'required' => array(
					'preloader' => 'on',
				)

			),
			array(
				'name' =>  a13__be( 'How to fit background image' ),
				'id' => 'preloader_bg_image_fit',
				'default' => 'cover',
				'options' => array(
					'cover'     =>  a13__be( 'Cover' ),
					'contain'   =>  a13__be( 'Contain' ),
					'fitV'      =>  a13__be( 'Fit Vertically' ),
					'fitH'      =>  a13__be( 'Fit Horizontally' ),
					'center'    =>  a13__be( 'Just center' ),
					'repeat'    =>  a13__be( 'Repeat' ),
					'repeat-x'  =>  a13__be( 'Repeat X' ),
					'repeat-y'  =>  a13__be( 'Repeat Y' ),
				),
				'type' => 'select',
				'required' => array(
					'preloader' => 'on',
				)
			),
            array(
                'name' =>  a13__be( 'Background color' ),
                'id' => 'preloader_bg_color',
                'default' => '',
                'type' => 'color',
                'required' => array(
	                'preloader' => 'on',
                )
            ),
            array(
                'name' =>  a13__be( 'Type' ),
                'id' => 'preloader_type',
                'default' => 'flash',
                'options' => array(
                    'none' =>  a13__be( 'none' ),
                    'atom' =>  a13__be( 'Atom' ),
                    'flash' =>  a13__be( 'Flash' ),
                    'indicator' =>  a13__be( 'Indicator' ),
                    'radar' =>  a13__be( 'Radar' ),
                    'circle_illusion' =>  a13__be( 'Circle Illusion' ),
                    'square_of_squares' =>  a13__be( 'Square of squares' ),
                    'plus_minus' =>  a13__be( 'Plus minus' ),
                    'hand' =>  a13__be( 'Hand' ),
                    'blurry' =>  a13__be( 'Blurry' ),
                    'arcs' =>  a13__be( 'Arcs' ),
                    'tetromino' =>  a13__be( 'Tetromino' ),
                    'infinity' =>  a13__be( 'Infinity' ),
                    'cloud_circle' =>  a13__be( 'Cloud circle' ),
                    'dots' =>  a13__be( 'Dots' ),
                    'photon_man' =>  a13__be( 'Photon-Man' ),
	                'circle' => 'Circle'
                ),
                'type' => 'select',
                'required' => array(
	                'preloader' => 'on',
                )
            ),
            array(
                'name' =>  a13__be( 'Animation color' ),
                'id' => 'preloader_color',
                'default' => '#ffffff',
                'type' => 'color',
                'required' => array(
	                'preloader' => 'on',
                )
            ),



            array(
                'name' =>  a13__be( 'Logo' ),
                'type' => 'fieldset',
                'id' => 'fieldset_logo_settings',
            ),
            array(
                'name' =>  a13__be( 'Logo type' ),
                'id' => 'logo_type',
                'default' => 'image',
                'options' => array(
                    'image' =>  a13__be( 'Image' ),
                    'text' =>  a13__be( 'Text' ),
                ),
                'type' => 'radio',
            ),
            array(
                'name' =>  a13__be( 'Logo image' ),
                'description' => a13__be( 'Upload an image for logo.' ),
                'id' => 'logo_image',
                'default' => get_template_directory_uri().'/images/defaults/logo.png',
                'type' => 'image',
                'required' => array(
	                'logo_type' => 'image',
                )
            ),
            array(
                'name' =>  a13__be( 'Logo image for HIGH DPI screen' ),
                'description' => a13__be( 'For example Retina(iPhone/iPad) screen is HIGH DPI.' ).' '.  a13__be( 'Upload an image for logo.' ),
                'id' => 'logo_image_high_dpi',
                'default' => get_template_directory_uri().'/images/defaults/logo@2x.png',
                'type' => 'image',
                'required' => array(
	                'logo_type' => 'image',
                )
            ),
			array(
				'name' =>  a13__be( 'Logo height' ),
				'id' => 'logo_image_height',
				'description' => a13__be( 'Leave empty if you don\'t need anything fancy' ),
				'default' => '',
				'unit' => 'px',
				'min' => 10,
				'max' => 100,
				'type' => 'slider',
				'required' => array(
					'logo_type' => 'image',
				)
			),
            array(
                'name' =>  a13__be( 'Logo hover opacity' ),
                'id' => 'logo_image_opacity',
                'default' => '50%',
                'unit' => '%',
                'min' => 0,
                'max' => 100,
                'type' => 'slider',
                'required' => array(
	                'logo_type' => 'image',
                )
            ),
            array(
                'name' =>  a13__be( 'Text in your logo' ),
                'description' => a13__be( 'If you use more then one word in logo, then you might want to use <code>&amp;nbsp;</code> instead of white space, so words wont break in many lines.' ),
                'id' => 'logo_text',
                'default' => 'PHOTON',
                'type' => 'text',
                'required' => array(
	                'logo_type' => 'text',
                ),
                'js' => '
                var l = $("#header").find("a.logo");
                if( l.hasClass("text-logo") ){
                    l.text(to);
                }
                '
            ),
            array(
                'name' =>  a13__be( 'Logo text color' ),
                'id' => 'logo_color',
                'default' => '',
                'type' => 'color',
                'required' => array(
	                'logo_type' => 'text',
                ),
                'js' => '
                $("#header").find("a.logo").css("color", to);
                '
            ),
            array(
                'name' =>  a13__be( 'Logo hover text color' ),
                'id' => 'logo_color_hover',
                'default' => '',
                'type' => 'color',
                'required' => array(
	                'logo_type' => 'text',
                )
            ),
            array(
                'name' =>  a13__be( 'Logo font size' ),
                'id' => 'logo_font_size',
                'default' => '26px',
                'unit' => 'px',
	            'max' => 60,
                'type' => 'slider',
                'required' => array(
	                'logo_type' => 'text',
                ),
                'js' => '
                $("#header").find("a.logo").css("font-size", to);
                '
            ),
            array(
                'name' =>  a13__be( 'Logo font weight' ),
                'id' => 'logo_weight',
                'default' => 'normal',
                'options' => array(
                    'normal' =>  a13__be( 'Normal' ),
                    'bold' =>  a13__be( 'Bold' ),
                ),
                'type' => 'radio',
                'required' => array(
	                'logo_type' => 'text',
                ),
                'js' => '
                $("#header").find("a.logo").css("font-weight", to);
                '
            ),
            array(
                'name' =>  a13__be( 'Logo top/bottom padding' ),
                'id' => 'logo_padding',
                'default' => '10px',
                'unit' => 'px',
                'min' => 0,
                'max' => 50,
                'type' => 'slider',
                'js' => '
                $("#header").find("a.logo").css({
                    "padding-top" : to,
                    "padding-bottom" : to
	            });
                '
            ),


            array(
                'name'  =>  a13__be( 'Top space' ),
                'description'  => a13__be( 'Distance of content and title bar from top edge of page.' ),
                'type'  => 'fieldset',
                'id'    => 'fieldset_top_space'
            ),
            array(
                'name' =>  a13__be( 'Top space value' ),
                'id' => 'top_space_height',
                'default' => '105px',
                'unit' => 'px',
                'min' => 0,
                'max' => 600,
                'type' => 'slider'
            ),
            array(
                'name' =>  a13__be( 'Top space background color' ),
                'id' => 'top_space_bg_color',
                'default' => '',
                'type' => 'color'
            ),



			array(
				'name' =>  a13__be( 'Header - Main Settings' ),
				'type' => 'fieldset',
				'id' => 'fieldset_header_app'
			),
			array(
				'name' =>  a13__be( 'Header variant' ),
				'id' => 'header_variant',
				'default' => 'left',
				'options' => array(
					'left' =>  a13__be( 'Left' ),
					'right' =>  a13__be( 'Right' ),
				),
				'type' => 'radio',
				'js' => '
	            if(to === "left"){
	                $("#header").removeClass("header-variant-right").addClass("header-variant-left");
	            }
	            else{
	                $("#header").removeClass("header-variant-left").addClass("header-variant-right");
	            }'
			),
			array(
				'name' =>  a13__be( 'Background color' ),
				'id' => 'header_bg_color',
				'default' => '',
				'type' => 'color',
//				'js' => '$("#header").find("div.head").css("background-color", to ? to : "" );',
			),
            array(
                'name' =>  a13__be( 'Icons color' ),
                'description' => a13__be( 'Search, basket, sidebar and fullscreen icons.' ),
                'id' => 'header_tools_color',
                'default' => '',
                'type' => 'color'
            ),
			array(
				'name' =>  a13__be( 'Switch on/off header search form' ),
				'id' => 'header_search',
				'default' => 'on',
				'options' => array(
					'on' =>  a13__be( 'Enable' ),
					'off' =>  a13__be( 'Disable' ),
				),
				'type' => 'radio',
			),
            array(
                'name' =>  a13__be( 'Behaviour of full screen button on pages and posts' ),
                'id' => 'full_screen_behaviour',
                'description' => a13__be( 'You can choose if full screen button should reveal background image or focus on content. In other cases(posts list, albums list, album, shop, single product) it always focus on content.' ),
                'default' => 'bg',
                'options' => array(
                    'bg' =>  a13__be( 'Background' ),
                    'content' =>  a13__be( 'Content' ),
                ),
                'type' => 'radio',
            ),




            array(
                'name' =>  a13__be( 'Header - Menu' ),
                'type' => 'fieldset',
                'id' => 'fieldset_header_menu_app'
            ),
            array(
                'name' =>  a13__be( 'Menu main links font size' ),
                'id' => 'menu_font_size',
                'default' => '',
                'unit' => 'px',
                'min' => 10,
                'max' => 30,
                'type' => 'slider'
            ),
            array(
                'name' =>  a13__be( 'Menu main links side padding' ),
                'id' => 'menu_element_padding',
                'default' => '',
                'unit' => 'px',
                'min' => 0,
                'max' => 50,
                'type' => 'slider'
            ),
            array(
                'name' =>  a13__be( 'Menu links color' ),
                'id' => 'menu_color',
                'default' => '',
                'type' => 'color'
            ),
            array(
                'name' =>  a13__be( 'Menu links hover/active color' ),
                'id' => 'menu_hover_color',
                'default' => '',
                'type' => 'color'
            ),
            array(
                'name' =>  a13__be( 'Menu font weight' ),
                'id' => 'menu_weight',
                'default' => 'bold',
                'options' => array(
                    'normal' =>  a13__be( 'Normal' ),
                    'bold' =>  a13__be( 'Bold' ),
                ),
                'type' => 'radio',
            ),
            array(
                'name' =>  a13__be( 'Menu text transform' ),
                'id' => 'menu_transform',
                'default' => 'uppercase',
                'options' => array(
                    'none' =>  a13__be( 'None' ),
                    'uppercase' =>  a13__be( 'Uppercase' ),
                ),
                'type' => 'radio',
            ),
            array(
                'name' =>  a13__be( 'Submenu/mega menu links color' ),
                'id' => 'submenu_color',
                'default' => '',
                'type' => 'color'
            ),
            array(
                'name' =>  a13__be( 'Submenu/mega menu links font size' ),
                'id' => 'submenu_font_size',
                'default' => '',
                'unit' => 'px',
                'type' => 'slider'
            ),
            array(
                'name' =>  a13__be( 'Submenu/mega menu font weight' ),
                'id' => 'submenu_weight',
                'default' => 'bold',
                'options' => array(
                    'normal' =>  a13__be( 'Normal' ),
                    'bold' =>  a13__be( 'Bold' ),
                ),
                'type' => 'radio',
            ),
            array(
                'name' =>  a13__be( 'Submenu/mega menu text transform' ),
                'id' => 'submenu_transform',
                'default' => 'uppercase',
                'options' => array(
                    'none' =>  a13__be( 'None' ),
                    'uppercase' =>  a13__be( 'Uppercase' ),
                ),
                'type' => 'radio',
            ),
            array(
                'name' =>  a13__be( 'Custom label color' ),
                'id' => 'menu_label_color',
                'default' => '',
                'type' => 'color'
            ),
            array(
                'name' =>  a13__be( 'Submenu/mega menu Top Line' ),
                'id' => 'submenu_top_line',
                'default' => 'on',
                'options' => array(
                    'on' =>  a13__be( 'Enable' ),
                    'off' =>  a13__be( 'Disable' ),
                ),
                'type' => 'radio',
            ),



            array(
                'name' =>  a13__be( 'Title Bar' ),
                'type' => 'fieldset',
                'id' => 'fieldset_title_bar'
            ),
			array(
				'name' =>  a13__be( 'Title Bar' ),
				'id' => 'title_bar',
				'default' => 'on',
				'options' => array(
					'on' =>  a13__be( 'Enable' ),
					'off' =>  a13__be( 'Disable' ),
				),
				'type' => 'radio',
			),
            array(
                'name' =>  a13__be( 'Position' ),
                'id' => 'title_bar_position',
                'default' => 'outside',
                'type' => 'radio',
                'options' => array(
                    'outside'   =>  a13__be( 'Outside content' ),
                    'inside'    =>  a13__be( 'Inside content' ),
                ),
                'required' => array(
                    'title_bar' => 'on',
                ),
            ),
            array(
                'name' =>  a13__be( 'Title font size' ),
                'id' => 'title_bar_title_size',
                'default' => '',
                'unit' => 'px',
                'type' => 'slider',
                'min' => 0,
                'max' => 60,
                'required' => array(
	                'title_bar' => 'on',
                ),
            ),
            array(
                'name' =>  a13__be( 'Title font weight' ),
                'id' => 'title_bar_title_weight',
                'default' => 'bold',
                'options' => array(
                    'normal' =>  a13__be( 'Normal' ),
                    'bold' =>  a13__be( 'Bold' ),
                ),
                'type' => 'radio',
                'required' => array(
	                'title_bar' => 'on',
                ),
            ),
            array(
                'name' =>  a13__be( 'Title color' ),
                'id' => 'title_bar_title_color',
                'default' => '',
                'type' => 'color',
                'required' => array(
	                'title_bar' => 'on',
                    'title_bar_position' => 'outside',
                ),
            ),
            array(
                'name' =>  a13__be( 'Title background color' ),
                'id' => 'title_bar_bg_color',
                'default' => '',
                'type' => 'color',
                'required' => array(
	                'title_bar' => 'on',
                    'title_bar_position' => 'outside',
                ),
            ),
            array(
                'name' =>  a13__be( 'Space in top and bottom' ),
                'id' => 'title_bar_space_width',
                'default' => '47px',
                'unit' => 'px',
                'min' => 0,
                'max' => 200,
                'type' => 'slider',
                'required' => array(
	                'title_bar' => 'on',
                    'title_bar_position' => 'outside',
                ),
            ),



            array(
                'name' =>  a13__be( 'Footer colors' ),
                'type' => 'fieldset',
                'id' => 'fieldset_footer_colors'
            ),
            array(
                'name' =>  a13__be( 'Background color' ),
                'id' => 'footer_bg_color',
                'default' => '',
                'type' => 'color'
            ),
            array(
                'name' =>  a13__be( 'Line color' ),
                'description' => a13__be( 'Line above widgets area' ),
                'id' => 'footer_widgets_border_color',
                'default' => 'transparent',
                'type' => 'color'
            ),
            array(
                'name' =>  a13__be( 'Font size' ),
                'description' => a13__be( 'Works for whole footer' ),
                'id' => 'footer_font_size',
                'default' => '',
                'unit' => 'px',
                'type' => 'slider',
                'js' => '
                $("#footer, #footer .widget").css("font-size", to);
                '
            ),
            array(
                'name' =>  a13__be( 'Font color' ),
                'description' => a13__be( 'Only for bar above footer widgets' ),
                'id' => 'footer_font_color',
                'default' => '',
                'type' => 'color'
            ),
            array(
                'name' =>  a13__be( 'Links color' ),
                'description' => a13__be( 'Only for bar above footer widgets' ),
                'id' => 'footer_link_color',
                'default' => '',
                'type' => 'color'
            ),
            array(
                'name' =>  a13__be( 'Links color hover' ),
                'description' => a13__be( 'Only for bar above footer widgets' ),
                'id' => 'footer_hover_color',
                'default' => '',
                'type' => 'color'
            ),
			array(
				'name' =>  a13__be( 'Widgets colors' ),
				'description' => a13__be( 'Depending on what background you have setup, choose proper option.' ),
				'id' => 'footer_widgets_color',
				'default' => 'dark-sidebar',
				'options' => array(
					'dark-sidebar'   =>  a13__be( 'On dark' ),
					'light-sidebar'  =>  a13__be( 'On light' ),
				),
				'type' => 'radio',
				'js' => '$("div.foot-widgets").removeClass("dark-sidebar light-sidebar").addClass(to);'
			),


            array(
                'name' =>  a13__be( 'Footer content' ),
                'type' => 'fieldset',
                'id' => 'fieldset_footer_lower'
            ),
            array(
                'name' =>  a13__be( 'Footer copyright text' ),
                'description' => a13__be( 'You can use HTML here.' ),
                'id' => 'footer_text',
                'default' => '',
                'type' => 'textarea',
                'js' => '$("div.foot-text").html(to);',
            ),
            array(
                'name' =>  a13__be( 'Footer message' ),
                'id' => 'footer_msg',
                'default' => 'off',
                'options' => array(
                    'on' =>  a13__be( 'Enable' ),
                    'off' =>  a13__be( 'Disable' ),
                ),
                'type' => 'radio',
            ),
            array(
                'name' =>  a13__be( 'Message text' ),
                'description' => a13__be( 'You can use HTML here.' ),
                'id' => 'footer_msg_text',
                'default' => '',
                'type' => 'textarea',
                'required' => array(
                    'footer_msg' => 'on'
                ),
                'js' => '
                var fm_div = $("div.footer-msg");
                if(fm_div.length){
                    fm_div.find("div.msg_text").html(to);
                }
                ',
            ),
            array(
                'name' =>  a13__be( 'New message?' ),
                'description' => a13__be( 'Click button so it will create new cookie for this message. So if user read previous message this will inform him that there is new message. You still need to save changes to make it work.' ),
                'id' => 'footer_msg_new',
                'default' => 'some_rand_string',
                'type' => 'reset_cookie',
                'required' => array(
                    'footer_msg' => 'on'
                )
            ),
            array(
                'name' =>  a13__be( 'Header indicator?' ),
                'description' => a13__be( 'It will display icon in header to inform about this message.' ),
                'id' => 'footer_msg_indicator',
                'default' => '1',
                'options' => array(
                    '1' =>  a13__be( 'Yes' ),
                    '0' =>  a13__be( 'No' ),
                ),
                'type' => 'radio',
                'required' => array(
                    'footer_msg' => 'on'
                )
            ),
            array(
                'name' =>  a13__be( 'Theme social icons' ),
                'id' => 'footer_socials',
                'default' => 'off',
                'options' => array(
                    'on' =>  a13__be( 'Enable' ),
                    'off' =>  a13__be( 'Disable' ),
                ),
                'type' => 'radio',
            ),
            array(
                'name' =>  a13__be( 'Social icons color' ),
                'id' => 'footer_socials_color',
                'default' => 'light-bg',
                'options' => array(
                    'dark-bg'   =>  a13__be( 'White' ),
                    'light-bg'  =>  a13__be( 'Black' ),
                    'colors'    =>  a13__be( 'Colors' ),
                ),
                'type' => 'radio',
                'required' => array(
	                'footer_socials' => 'on',
                ),
                'js' => '$("#footer .socials").removeClass("dark-bg light-bg colors").addClass(to);'
            ),



			array(
				'name' =>  a13__be( 'Hidden sidebar' ),
				'type' => 'fieldset',
				'id' => 'fieldset_hidden_sidebar',
				'description' => a13__be( 'If "Hidden sidebar" widget area will be empty then it will not be displayed.' ),
			),
			array(
				'name' =>  a13__be( 'Background color' ),
				'id' => 'hidden_sidebar_bg_color',
				'default' => '',
				'type' => 'color',
				'js' => '
                $("#side-menu").css("background-color", to);
                '
			),
			array(
				'name' =>  a13__be( 'Font size' ),
				'id' => 'hidden_sidebar_font_size',
				'default' => '',
				'unit' => 'px',
				'type' => 'slider',
				'js' => '
                $("#side-menu, #side-menu .widget").css("font-size", to);
                '
			),
			array(
				'name' =>  a13__be( 'Widgets colors' ),
				'description' => a13__be( 'Depending on what background you have setup, choose proper option.' ),
				'id' => 'hidden_sidebar_widgets_color',
				'default' => 'dark-sidebar',
				'options' => array(
					'dark-sidebar'   =>  a13__be( 'On dark' ),
					'light-sidebar'  =>  a13__be( 'On light' ),
				),
				'type' => 'radio',
				'js' => '$("#side-menu").removeClass("dark-sidebar light-sidebar").addClass(to);'
			),
			array(
				'name' =>  a13__be( 'Sidebar side' ),
				'id' => 'hidden_sidebar_side',
				'default' => 'left',
				'options' => array(
					'left'  =>  a13__be( 'Left' ),
					'right' =>  a13__be( 'Right' ),
				),
				'type' => 'radio'
			),
			array(
				'name' =>  a13__be( 'Sidebar effect' ),
				'id' => 'hidden_sidebar_effect',
				'default' => '2',
				'options' => array(
					'1' => a13__be( 'Slide in on top' ),
					'2' => a13__be( 'Reveal' ),
					'3' => a13__be( 'Push' ),
					'4' => a13__be( 'Slide along' ),
					'5' => a13__be( 'Reverse slide out' ),
					'6' => a13__be( 'Fall down' ),
				),
				'type' => 'select',
			),



			array(
				'name' =>  a13__be( 'Basket sidebar' ),
				'type' => 'fieldset',
				'id' => 'fieldset_basket_sidebar',
				'description' => a13__be( 'If "Basket sidebar" widget area will be empty then it will not be displayed.' ),
			),
			array(
				'name' =>  a13__be( 'Background color' ),
				'id' => 'basket_sidebar_bg_color',
				'default' => '',
				'type' => 'color',
				'js' => '
                $("#basket-menu").css("background-color", to);
                '
			),
			array(
				'name' =>  a13__be( 'Font size' ),
				'id' => 'basket_sidebar_font_size',
				'default' => '',
				'unit' => 'px',
				'type' => 'slider',
				'js' => '
                $("#basket-menu, #side-menu .widget").css("font-size", to);
                '
			),
			array(
				'name' =>  a13__be( 'Widgets colors' ),
				'description' => a13__be( 'Depending on what background you have setup, choose proper option.' ),
				'id' => 'basket_sidebar_widgets_color',
				'default' => 'light-sidebar',
				'options' => array(
					'dark-sidebar'   =>  a13__be( 'On dark' ),
					'light-sidebar'  =>  a13__be( 'On light' ),
				),
				'type' => 'radio',
				'js' => '$("#basket-menu").removeClass("dark-sidebar light-sidebar").addClass(to);'
			),
			array(
				'name' =>  a13__be( 'Sidebar side' ),
				'id' => 'basket_sidebar_side',
				'default' => 'right',
				'options' => array(
					'left'  =>  a13__be( 'Left' ),
					'right' =>  a13__be( 'Right' ),
				),
				'type' => 'radio'
			),
			array(
				'name' =>  a13__be( 'Sidebar effect' ),
				'id' => 'basket_sidebar_effect',
				'default' => '1',
				'options' => array(
					'1' => a13__be( 'Slide in on top' ),
					'2' => a13__be( 'Reveal' ),
					'3' => a13__be( 'Push' ),
					'4' => a13__be( 'Slide along' ),
					'5' => a13__be( 'Reverse slide out' ),
					'6' => a13__be( 'Fall down' ),
				),
				'type' => 'select',
			),
		);


		$args = array(
			'title'         => a13__be( 'Global Layout' ),
			'opt'           => $opt
		);

		return $args;
	}

    function apollo13_fonts_options(){
        $classic_fonts = array(
            'default'           =>  a13__be( 'Defined in CSS' ),
            'arial'             =>  a13__be( 'Arial' ),
            'calibri'           =>  a13__be( 'Calibri' ),
            'cambria'           =>  a13__be( 'Cambria' ),
            'georgia'           =>  a13__be( 'Georgia' ),
            'tahoma'            =>  a13__be( 'Tahoma' ),
            'times new roman'   =>  a13__be( 'Times new roman' ),
        );

        $opt = array(
            array(
                'name' =>  a13__be( 'Fonts settings' ),
                'type' => 'fieldset',
                'id' => 'fieldset_fonts',
            ),
            array(
                'name' =>  a13__be( 'Font for top nav menu, interactive elements, short labels, etc.:' ),
                'id' => 'nav_menu_fonts',
                'default' => 'Raleway:600,800',
                'options' => $classic_fonts,
                'type' => 'font',
            ),
            array(
                'name' =>  a13__be( 'Font for Titles/Headings:' ),
                'id' => 'titles_fonts',
                'default' => 'Raleway:600,800',
                'options' => $classic_fonts,
                'type' => 'font',
            ),
            array(
                'name' =>  a13__be( 'Font for normal(content) text:' ),
                'id' => 'normal_fonts',
                'default' => 'Roboto:regular,700',
                'options' => $classic_fonts,
                'type' => 'font',
            ),



            array(
                'name' =>  a13__be( 'Headings styles' ),
                'type' => 'fieldset',
                'id' => 'fieldset_headings_styles',
            ),
            array(
                'name' =>  a13__be( 'Headings/Titles color' ),
                'id' => 'headings_color',
                'default' => '',
                'type' => 'color'
            ),
            array(
                'name' =>  a13__be( 'Headings/Titles color hover' ),
                'id' => 'headings_color_hover',
                'default' => '',
                'type' => 'color'
            ),
            array(
                'name' =>  a13__be( 'Headings/Titles font weight' ),
                'id' => 'headings_weight',
                'default' => 'bold',
                'options' => array(
                    'normal' =>  a13__be( 'Normal' ),
                    'bold' =>  a13__be( 'Bold' ),
                ),
                'type' => 'radio',
            ),
            array(
                'name' =>  a13__be( 'Headings/Titles text transform' ),
                'id' => 'headings_transform',
                'default' => 'uppercase',
                'options' => array(
                    'none' =>  a13__be( 'None' ),
                    'uppercase' =>  a13__be( 'Uppercase' ),
                ),
                'type' => 'radio',
            ),



            array(
                'name' =>  a13__be( 'Content styles' ),
                'type' => 'fieldset',
                'id' => 'fieldset_content_styles',
            ),
            array(
                'name' =>  a13__be( 'Font color' ),
                'id' => 'content_color',
                'default' => '',
                'type' => 'color'
            ),
            array(
                'name' =>  a13__be( 'Font size' ),
                'id' => 'content_font_size',
                'default' => '13px',
                'unit' => 'px',
                'type' => 'slider'
            ),
            array(
                'name' =>  a13__be( 'First paragraph mark out' ),
                'description' => a13__be( 'If enabled it marks out(font size and color) first paragraph in many places(blog, post, page). It will do nothing when using builder.' ),
                'id' => 'first_paragraph',
                'default' => 'on',
                'options' => array(
                    'on' =>  a13__be( 'Enable' ),
                    'off' =>  a13__be( 'Disable' ),
                ),
                'type' => 'radio',
            ),
            array(
                'name' =>  a13__be( 'First paragraph color' ),
                'id' => 'first_paragraph_color',
                'default' => '',
                'type' => 'color',
                'required' => array(
	                'first_paragraph' => 'on',
                )
            ),
        );

	    $args = array(
		    'title'         => a13__be( 'Typography' ),
		    'opt'           => $opt
	    );

	    return $args;
    }

    function apollo13_blog_options(){

        $opt = array(
            array(
                'name' =>  a13__be( 'General layout' ),
                'type' => 'fieldset',
                'id' => 'fieldset_blog_general'
            ),
            array(
                'name' =>  a13__be( 'Subtitle' ),
                'description' =>  a13__be( 'You can use HTML here.' ),
                'id' => 'subtitle',
                'default' => '',
                'type' => 'text'
            ),
            array(
                'name' =>  a13__be( 'Custom background' ),
                'id' => 'custom_background',
                'default' => 'off',
                'options' => array(
                    'on' =>  a13__be( 'Enable' ),
                    'off' =>  a13__be( 'Disable' ),
                ),
                'type' => 'radio',
            ),
            array(
                'name' =>  a13__be( 'Background image' ),
                'id' => 'body_image',
                'default' => '',
                'type' => 'image',
                'required' => array(
                    'custom_background' => 'on',
                ),
            ),
            array(
                'name' =>  a13__be( 'How to fit background image' ),
                'id' => 'body_image_fit',
                'default' => 'cover',
                'options' => array(
                    'cover'     =>  a13__be( 'Cover' ),
                    'contain'   =>  a13__be( 'Contain' ),
                    'fitV'      =>  a13__be( 'Fit Vertically' ),
                    'fitH'      =>  a13__be( 'Fit Horizontally' ),
                    'center'    =>  a13__be( 'Just center' ),
                    'repeat'    =>  a13__be( 'Repeat' ),
                    'repeat-x'  =>  a13__be( 'Repeat X' ),
                    'repeat-y'  =>  a13__be( 'Repeat Y' ),
                ),
                'type' => 'select',
                'required' => array(
                    'custom_background' => 'on',
                ),
            ),
            array(
                'name' =>  a13__be( 'Background color' ),
                'id' => 'body_bg_color',
                'default' => '',
                'type' => 'color',
                'required' => array(
                    'custom_background' => 'on',
                ),
            ),



            array(
                'name'  =>  a13__be( 'Top space' ),
                'description'  => a13__be( 'Distance of content and title bar from top edge of page.' ),
                'type'  => 'fieldset',
                'id'    => 'fieldset_blog_top_space'
            ),
            array(
                'name' =>  a13__be( 'Custom top space' ),
                'id' => 'custom_top_space',
                'default' => 'off',
                'options' => array(
                    'on' =>  a13__be( 'Enable' ),
                    'off' =>  a13__be( 'Disable' ),
                ),
                'type' => 'radio',
            ),
            array(
                'name' =>  a13__be( 'Top space value' ),
                'id' => 'top_space_height',
                'default' => '100px',
                'unit' => 'px',
                'min' => 0,
                'max' => 600,
                'type' => 'slider',
                'required' => array(
                    'custom_top_space' => 'on',
                ),
            ),
            array(
                'name' =>  a13__be( 'Top space background color' ),
                'id' => 'top_space_bg_color',
                'default' => '',
                'type' => 'color',
                'required' => array(
                    'custom_top_space' => 'on',
                ),
            ),


            array(
                'name' =>  a13__be( 'Title Bar' ),
                'type' => 'fieldset',
                'id' => 'fieldset_blog_title_bar'
            ),
            array(
                'name' =>  a13__be( 'Custom title bar' ),
                'id' => 'custom_title_bar',
                'default' => 'off',
                'options' => array(
                    'on' =>  a13__be( 'Enable' ),
                    'off' =>  a13__be( 'Disable' ),
                ),
                'type' => 'radio',
            ),
            array(
                'name' =>  a13__be( 'Title Bar' ),
                'id' => 'title_bar',
                'default' => 'on',
                'options' => array(
                    'on' =>  a13__be( 'Enable' ),
                    'off' =>  a13__be( 'Disable' ),
                ),
                'type' => 'radio',
                'required' => array(
                    'custom_title_bar' => 'on',
                ),
            ),
            array(
                'name' =>  a13__be( 'Title font size' ),
                'id' => 'title_bar_title_size',
                'default' => '',
                'unit' => 'px',
                'type' => 'slider',
                'min' => 0,
                'max' => 60,
                'required' => array(
                    'custom_title_bar' => 'on',
                    'title_bar' => 'on',
                ),
            ),
            array(
                'name' =>  a13__be( 'Title font weight' ),
                'id' => 'title_bar_title_weight',
                'default' => 'bold',
                'options' => array(
                    'normal' =>  a13__be( 'Normal' ),
                    'bold' =>  a13__be( 'Bold' ),
                ),
                'type' => 'radio',
                'required' => array(
                    'custom_title_bar' => 'on',
                    'title_bar' => 'on',
                ),
            ),
            array(
                'name' =>  a13__be( 'Title color' ),
                'id' => 'title_bar_title_color',
                'default' => '',
                'type' => 'color',
                'required' => array(
                    'custom_title_bar' => 'on',
                    'title_bar' => 'on',
                ),
            ),
            array(
                'name' =>  a13__be( 'Title background color' ),
                'id' => 'title_bar_bg_color',
                'default' => '',
                'type' => 'color',
                'required' => array(
                    'custom_title_bar' => 'on',
                    'title_bar' => 'on',
                ),
            ),
            array(
                'name' =>  a13__be( 'Space in top and bottom' ),
                'id' => 'title_bar_space_width',
                'default' => '40px',
                'unit' => 'px',
                'min' => 0,
                'max' => 200,
                'type' => 'slider',
                'required' => array(
                    'custom_title_bar' => 'on',
                    'title_bar' => 'on',
                ),
            ),



            array(
                'name' =>  a13__be( 'Posts list appearance' ),
                'type' => 'fieldset',
                'id' => 'fieldset_blog',
            ),
            array(
                'name' =>  a13__be( 'Content Layout' ),
                'id' => 'blog_content_layout',
                'default' => 'center',
                'type' => 'select',
                'options' => array(
                    'center'        =>  a13__be( 'Center fixed width' ),
                    'left'          =>  a13__be( 'Left fixed width' ),
                    'left_padding'  =>  a13__be( 'Left fixed width + padding' ),
                    'right'         =>  a13__be( 'Right fixed width' ),
                    'right_padding' =>  a13__be( 'Right fixed width + padding' ),
                    'full_fixed'    =>  a13__be( 'Full width + fixed content' ),
                    'full_padding'  =>  a13__be( 'Full width + padding' ),
                    'full'          =>  a13__be( 'Full width' ),
                ),
            ),
            array(
                'name' =>  a13__be( 'Sidebar' ),
                'id' => 'blog_sidebar',
                'default' => 'right-sidebar',
                'options' => array(
                    'left-sidebar'  =>  a13__be( 'Left' ),
                    'right-sidebar' =>  a13__be( 'Right' ),
                    'off'           =>  a13__be( 'Off' ),
                ),
                'type' => 'select',
            ),
            array(
                'name' =>  a13__be( 'Bricks columns' ),
                'description' => a13__be('It only affects wider screen resolutions.'),
                'id' => 'brick_columns',
                'default' => '3',
                'unit' => '',
                'min' => 1,
                'max' => 4,
                'type' => 'slider',
            ),
            array(
                'name' =>  a13__be( 'Max width of bricks content.' ),
                'description' => a13__be('Depending on actual screen width and content style used for blog page, available space for bricks might be smaller, but newer greater then this number.'),
                'id' => 'bricks_max_width',
                'default' => '1920px',
                'unit' => 'px',
                'min' => 200,
                'max' => 2500,
                'type' => 'slider',
            ),
            array(
                'name' =>  a13__be( 'Brick margin' ),
                'id' => 'brick_margin',
                'default' => '10px',
                'unit' => 'px',
                'min' => 0,
                'max' => 100,
                'type' => 'slider',
            ),

            array(
                'name' =>  a13__be( 'Type of post excerpts' ),
                'description' => a13__be( 'In Manual mode excerpts are used only if you add more tag (&lt;!--more--&gt;).<br />' .
                    'In Automatic mode if you won\'t provide more tag or explicit excerpt, content of post will be cut automatic.<br />' .
                    'This setting only concerns blog list, archive list, search results. <br />' ),
                'id' => 'excerpt_type',
                'default' => 'auto',
                'options' => array(
                    'auto'      =>  a13__be( 'Automatic' ),
                    'manual'    =>  a13__be( 'Manual' ),
                ),
                'type' => 'radio',
            ),
            array(
                'name' =>  a13__be( 'Number of words to cut post' ),
                'description' => a13__be('After this many words post will be cut in automatic mode.'),
                'id' => 'excerpt_length',
                'default' => '40',
                'unit' => '',
                'min' => 3,
                'max' => 200,
                'type' => 'slider',
                'required' => array(
	                'excerpt_type' => 'auto',
                )
            ),
            array(
                'name' =>  a13__be( 'Display post Media' ),
                'description' => a13__be( 'You can set to not display post media(featured image/video/slider) inside of post brick.' ),
                'id' => 'blog_media',
                'default' => 'on',
                'options' => array(
                    'on'    =>  a13__be( 'On' ),
                    'off'   =>  a13__be( 'Off' ),
                ),
                'type' => 'radio',
            ),
            array(
                'name' =>  a13__be( 'Display of posts video' ),
                'description' => a13__be( 'You can set to not display videos as featured media on posts list. This can speed up loading of page with many posts(blog, archive, search results) when videos are used.' ),
                'id' => 'blog_videos',
                'default' => 'on',
                'options' => array(
                    'on'    =>  a13__be( 'Show videos' ),
                    'off'   =>  a13__be( 'Show featured image' ),
                ),
                'type' => 'radio',
            ),
            array(
                'name' =>  a13__be( 'Post meta: Date' ),
                'id' => 'blog_date',
                'default' => 'on',
                'options' => array(
                    'on'    =>  a13__be( 'On' ),
                    'off'   =>  a13__be( 'Off' ),
                ),
                'type' => 'radio',
            ),
            array(
                'name' =>  a13__be( 'Post meta: Comments number' ),
                'id' => 'blog_comments',
                'default' => 'on',
                'options' => array(
                    'on'    =>  a13__be( 'On' ),
                    'off'   =>  a13__be( 'Off' ),
                ),
                'type' => 'radio',
            ),
            array(
                'name' =>  a13__be( 'Post meta: Categories' ),
                'id' => 'blog_cats',
                'default' => 'on',
                'options' => array(
                    'on'    =>  a13__be( 'On' ),
                    'off'   =>  a13__be( 'Off' ),
                ),
                'type' => 'radio',
            ),
            array(
                'name' =>  a13__be( 'Under post content tags' ),
                'id' => 'blog_under_content_tags',
                'default' => 'off',
                'options' => array(
                    'on'    =>  a13__be( 'On' ),
                    'off'   =>  a13__be( 'Off' ),
                ),
                'type' => 'radio',
            ),



            array(
                'name' =>  a13__be( 'Bricks colors in "Display post intro on image"' ),
                'type' => 'fieldset',
                'id' => 'fieldset_blog_onimage'
            ),
            array(
                'name' =>  a13__be( 'Title color' ),
                'id' => 'blog_omimage_title_color',
                'default' => '',
                'type' => 'color'
            ),
            array(
                'name' =>  a13__be( 'Text color' ),
                'id' => 'blog_omimage_text_color',
                'default' => '',
                'type' => 'color'
            ),
            array(
                'name' =>  a13__be( 'Link color' ),
                'id' => 'blog_omimage_link_color',
                'default' => '',
                'type' => 'color'
            ),
            array(
                'name' =>  a13__be( 'Link hover color' ),
                'id' => 'blog_omimage_link_hover_color',
                'default' => '',
                'type' => 'color'
            ),



            array(
                'name' =>  a13__be( 'Post appearance' ),
                'type' => 'fieldset',
                'id' => 'fieldset_post'
            ),
            array(
                'name' =>  a13__be( 'Content Layout' ),
                'description' => '',
                'id' => 'post_content_layout',
                'default' => 'center',
                'type' => 'select',
                'options' => array(
                    'center'        =>  a13__be( 'Center fixed width' ),
                    'left'          =>  a13__be( 'Left fixed width' ),
                    'left_padding'  =>  a13__be( 'Left fixed width + padding' ),
                    'right'         =>  a13__be( 'Right fixed width' ),
                    'right_padding' =>  a13__be( 'Right fixed width + padding' ),
                    'full_fixed'    =>  a13__be( 'Full width + fixed content' ),
                    'full_padding'  =>  a13__be( 'Full width + padding' ),
                    'full'          =>  a13__be( 'Full width' ),
                ),
            ),
            array(
                'name' =>  a13__be( 'Post sidebar' ),
                'description' => a13__be( 'It affects look of posts.' ),
                'id' => 'post_sidebar',
                'default' => 'right-sidebar',
                'options' => array(
                    'left-sidebar'  =>  a13__be( 'Left' ),
                    'right-sidebar' =>  a13__be( 'Right' ),
                    'off'           =>  a13__be( 'Off' ),
                ),
                'type' => 'select',
            ),
            array(
                'name' =>  a13__be( 'Display post Media' ),
                'description' => a13__be( 'You can set to not display post media(featured image/video/slider) inside of post.' ),
                'id' => 'post_media',
                'default' => 'on',
                'options' => array(
                    'on'    =>  a13__be( 'On' ),
                    'off'   =>  a13__be( 'Off' ),
                ),
                'type' => 'radio',
            ),
            array(
                'name' =>  a13__be( 'Display outside title also' ),
                'description' => a13__be( 'It is addition to better notice post title.' ),
                'id' => 'post_outside_title',
                'default' => 'on',
                'options' => array(
                    'on'    =>  a13__be( 'On' ),
                    'off'   =>  a13__be( 'Off' ),
                ),
                'type' => 'radio',
            ),
            array(
                'name' =>  a13__be( 'Author info in post' ),
                'description' => a13__be( 'Will show information about author below post content. Not displayed in blog post list.' ),
                'id' => 'author_info',
                'default' => 'on',
                'options' => array(
                    'on'    =>  a13__be( 'On' ),
                    'off'   =>  a13__be( 'Off' ),
                ),
                'type' => 'radio',
            ),
            array(
                'name' =>  a13__be( 'Post meta: Date' ),
                'id' => 'post_date',
                'default' => 'on',
                'options' => array(
                    'on'    =>  a13__be( 'On' ),
                    'off'   =>  a13__be( 'Off' ),
                ),
                'type' => 'radio',
            ),
            array(
                'name' =>  a13__be( 'Post meta: Comments number' ),
                'id' => 'post_comments',
                'default' => 'on',
                'options' => array(
                    'on'    =>  a13__be( 'On' ),
                    'off'   =>  a13__be( 'Off' ),
                ),
                'type' => 'radio',
            ),
            array(
                'name' =>  a13__be( 'Post meta: Categories' ),
                'id' => 'post_cats',
                'default' => 'on',
                'options' => array(
                    'on'    =>  a13__be( 'On' ),
                    'off'   =>  a13__be( 'Off' ),
                ),
                'type' => 'radio',
            ),
            array(
                'name' =>  a13__be( 'Under post content tags' ),
                'id' => 'post_under_content_tags',
                'default' => 'on',
                'options' => array(
                    'on'    =>  a13__be( 'On' ),
                    'off'   =>  a13__be( 'Off' ),
                ),
                'type' => 'radio',
            ),
            array(
                'name' =>  a13__be( 'Posts navigation' ),
                'description' => a13__be( 'Links to next and prev post.' ),
                'id' => 'posts_navigation',
                'default' => 'on',
                'options' => array(
                    'on'    =>  a13__be( 'On' ),
                    'off'   =>  a13__be( 'Off' ),
                ),
                'type' => 'radio',
            ),
        );

	    $args = array(
		    'title'         => a13__be( 'Blog Layout' ),
            'description' => a13__be( 'Posts list refers to Blog, Search and Archives pages' ),
		    'opt'           => $opt
	    );

	    return $args;
    }

	function apollo13_shop_options(){

        $opt = array(
            array(
                'name' =>  a13__be( 'General layout' ),
                'type' => 'fieldset',
                'id' => 'fieldset_shop_general'
            ),
            array(
                'name' =>  a13__be( 'Subtitle' ),
                'description' =>  a13__be( 'You can use HTML here.' ),
                'id' => 'subtitle',
                'default' => '',
                'type' => 'text'
            ),
	        array(
		        'name' => a13__be( 'Search in products instead of pages' ),
		        'desc' => a13__be( 'It will change wordpress default search function to make shop search. So when this is activated search function in header or search widget will act as woocommerece search widget.' ),
		        'id' => 'shop_search',
		        'default' => 'off',
		        'options' => array(
			        'on'    => a13__be( 'On' ),
			        'off'   => a13__be( 'Off' ),
		        ),
		        'type' => 'radio',
	        ),
            array(
                'name' =>  a13__be( 'Custom background' ),
                'id' => 'custom_background',
                'default' => 'off',
                'options' => array(
                    'on' =>  a13__be( 'Enable' ),
                    'off' =>  a13__be( 'Disable' ),
                ),
                'type' => 'radio',
            ),
            array(
                'name' =>  a13__be( 'Background image' ),
                'id' => 'body_image',
                'default' => '',
                'type' => 'image',
                'required' => array(
                    'custom_background' => 'on',
                ),
            ),
            array(
                'name' =>  a13__be( 'How to fit background image' ),
                'id' => 'body_image_fit',
                'default' => 'cover',
                'options' => array(
                    'cover'     =>  a13__be( 'Cover' ),
                    'contain'   =>  a13__be( 'Contain' ),
                    'fitV'      =>  a13__be( 'Fit Vertically' ),
                    'fitH'      =>  a13__be( 'Fit Horizontally' ),
                    'center'    =>  a13__be( 'Just center' ),
                    'repeat'    =>  a13__be( 'Repeat' ),
                    'repeat-x'  =>  a13__be( 'Repeat X' ),
                    'repeat-y'  =>  a13__be( 'Repeat Y' ),
                ),
                'type' => 'select',
                'required' => array(
                    'custom_background' => 'on',
                ),
            ),
            array(
                'name' =>  a13__be( 'Background color' ),
                'id' => 'body_bg_color',
                'default' => '',
                'type' => 'color',
                'required' => array(
                    'custom_background' => 'on',
                ),
            ),



            array(
                'name'  =>  a13__be( 'Top space' ),
                'description'  => a13__be( 'Distance of content and title bar from top edge of page.' ),
                'type'  => 'fieldset',
                'id'    => 'fieldset_shop_top_space'
            ),
            array(
                'name' =>  a13__be( 'Custom top space' ),
                'id' => 'custom_top_space',
                'default' => 'off',
                'options' => array(
                    'on' =>  a13__be( 'Enable' ),
                    'off' =>  a13__be( 'Disable' ),
                ),
                'type' => 'radio',
            ),
            array(
                'name' =>  a13__be( 'Top space value' ),
                'id' => 'top_space_height',
                'default' => '100px',
                'unit' => 'px',
                'min' => 0,
                'max' => 600,
                'type' => 'slider',
                'required' => array(
                    'custom_top_space' => 'on',
                ),
            ),
            array(
                'name' =>  a13__be( 'Top space background color' ),
                'id' => 'top_space_bg_color',
                'default' => '',
                'type' => 'color',
                'required' => array(
                    'custom_top_space' => 'on',
                ),
            ),


            array(
                'name' =>  a13__be( 'Title Bar' ),
                'type' => 'fieldset',
                'id' => 'fieldset_shop_title_bar'
            ),
            array(
                'name' =>  a13__be( 'Custom title bar' ),
                'id' => 'custom_title_bar',
                'default' => 'off',
                'options' => array(
                    'on' =>  a13__be( 'Enable' ),
                    'off' =>  a13__be( 'Disable' ),
                ),
                'type' => 'radio',
            ),
            array(
                'name' =>  a13__be( 'Title Bar' ),
                'id' => 'title_bar',
                'default' => 'on',
                'options' => array(
                    'on' =>  a13__be( 'Enable' ),
                    'off' =>  a13__be( 'Disable' ),
                ),
                'type' => 'radio',
                'required' => array(
                    'custom_title_bar' => 'on',
                ),
            ),
            array(
                'name' =>  a13__be( 'Title font size' ),
                'id' => 'title_bar_title_size',
                'default' => '',
                'unit' => 'px',
                'type' => 'slider',
                'min' => 0,
                'max' => 60,
                'required' => array(
                    'custom_title_bar' => 'on',
                    'title_bar' => 'on',
                ),
            ),
            array(
                'name' =>  a13__be( 'Title font weight' ),
                'id' => 'title_bar_title_weight',
                'default' => 'bold',
                'options' => array(
                    'normal' =>  a13__be( 'Normal' ),
                    'bold' =>  a13__be( 'Bold' ),
                ),
                'type' => 'radio',
                'required' => array(
                    'custom_title_bar' => 'on',
                    'title_bar' => 'on',
                ),
            ),
            array(
                'name' =>  a13__be( 'Title color' ),
                'id' => 'title_bar_title_color',
                'default' => '',
                'type' => 'color',
                'required' => array(
                    'custom_title_bar' => 'on',
                    'title_bar' => 'on',
                ),
            ),
            array(
                'name' =>  a13__be( 'Title background color' ),
                'id' => 'title_bar_bg_color',
                'default' => '',
                'type' => 'color',
                'required' => array(
                    'custom_title_bar' => 'on',
                    'title_bar' => 'on',
                ),
            ),
            array(
                'name' =>  a13__be( 'Space in top and bottom' ),
                'id' => 'title_bar_space_width',
                'default' => '40px',
                'unit' => 'px',
                'min' => 0,
                'max' => 200,
                'type' => 'slider',
                'required' => array(
                    'custom_title_bar' => 'on',
                    'title_bar' => 'on',
                ),
            ),



            array(
                'name' =>  a13__be( 'Products list appearance' ),
                'type' => 'fieldset',
                'id' => 'fieldset_shop',
            ),
            array(
                'name' =>  a13__be( 'Content Layout' ),
                'id' => 'shop_content_layout',
                'default' => 'full',
                'type' => 'select',
                'options' => array(
                    'center'        =>  a13__be( 'Center fixed width' ),
                    'left'          =>  a13__be( 'Left fixed width' ),
                    'left_padding'  =>  a13__be( 'Left fixed width + padding' ),
                    'right'         =>  a13__be( 'Right fixed width' ),
                    'right_padding' =>  a13__be( 'Right fixed width + padding' ),
                    'full_fixed'    =>  a13__be( 'Full width + fixed content' ),
                    'full_padding'  =>  a13__be( 'Full width + padding' ),
                    'full'          =>  a13__be( 'Full width' ),
                ),
            ),
            array(
                'name' =>  a13__be( 'Sidebar' ),
                'id' => 'shop_sidebar',
                'default' => 'left-sidebar',
                'options' => array(
                    'left-sidebar'  =>  a13__be( 'Left' ),
                    'right-sidebar' =>  a13__be( 'Right' ),
                    'off'           =>  a13__be( 'Off' ),
                ),
                'type' => 'select',
            ),
            array(
                'name' =>  a13__be( 'Columns(per row)' ),
                'description' => a13__be('It only affects wider screen resolutions.'),
                'id' => 'product_columns',
                'default' => '4',
                'unit' => '',
                'min' => 1,
                'max' => 6,
                'type' => 'slider',
            ),
            array(
                'name' =>  a13__be( 'Products per page' ),
                'description' => '',
                'id' => 'product_per_page',
                'default' => '12',
                'unit' => '',
                'min' => 1,
                'max' => 30,
                'type' => 'slider',
            ),




            array(
                'name' =>  a13__be( 'Product appearance' ),
                'type' => 'fieldset',
                'id' => 'fieldset_product'
            ),
            array(
                'name' =>  a13__be( 'Content Layout' ),
                'description' => '',
                'id' => 'product_content_layout',
                'default' => 'full',
                'type' => 'select',
                'options' => array(
                    'center'        =>  a13__be( 'Center fixed width' ),
                    'left'          =>  a13__be( 'Left fixed width' ),
                    'left_padding'  =>  a13__be( 'Left fixed width + padding' ),
                    'right'         =>  a13__be( 'Right fixed width' ),
                    'right_padding' =>  a13__be( 'Right fixed width + padding' ),
                    'full_fixed'    =>  a13__be( 'Full width + fixed content' ),
                    'full_padding'  =>  a13__be( 'Full width + padding' ),
                    'full'          =>  a13__be( 'Full width' ),
                ),
            ),
            array(
                'name' =>  a13__be( 'Product sidebar' ),
                'description' => a13__be( 'It affects look of posts.' ),
                'id' => 'product_sidebar',
                'default' => 'left-sidebar',
                'options' => array(
                    'left-sidebar'  =>  a13__be( 'Left' ),
                    'right-sidebar' =>  a13__be( 'Right' ),
                    'off'           =>  a13__be( 'Off' ),
                ),
                'type' => 'select',
            ),
            array(
                'name' =>  a13__be( 'Display outside title also' ),
                'description' => a13__be( 'It is addition to better notice product title.' ),
                'id' => 'product_outside_title',
                'default' => 'off',
                'options' => array(
                    'on'    =>  a13__be( 'On' ),
                    'off'   =>  a13__be( 'Off' ),
                ),
                'type' => 'radio',
            ),
        );

	    $args = array(
		    'title'         => a13__be( 'Shop(woocommerce) Layout' ),
            'description' => '',
		    'opt'           => $opt
	    );

	    return $args;
    }

    function apollo13_page_options(){

        $opt = array(
            array(
                'name' =>  a13__be( 'General layout' ),
                'type' => 'fieldset',
                'id' => 'fieldset_pages'
            ),
            array(
                'name' =>  a13__be( 'Content Layout' ),
                'id' => 'content_layout',
                'default' => 'center',
                'type' => 'select',
                'options' => array(
                    'center'        =>  a13__be( 'Center fixed width' ),
                    'left'          =>  a13__be( 'Left fixed width' ),
                    'left_padding'  =>  a13__be( 'Left fixed width + padding' ),
                    'right'         =>  a13__be( 'Right fixed width' ),
                    'right_padding' =>  a13__be( 'Right fixed width + padding' ),
                    'full_fixed'    =>  a13__be( 'Full width + fixed content' ),
                    'full_padding'  =>  a13__be( 'Full width + padding' ),
                    'full'          =>  a13__be( 'Full width' ),
                ),
            ),
            array(
                'name' =>  a13__be( 'Page sidebar' ),
                'description' => a13__be( 'You can change it in each page settings.' ),
                'id' => 'page_sidebar',
                'default' => 'off',
                'options' => array(
                    'left-sidebar'              =>  a13__be( 'Sidebar on the left' ),
                    'left-sidebar_and_nav'      =>  a13__be( 'Children Navigation + sidebar on the left' ),
                    'left-nav'                  =>  a13__be( 'Only children Navigation on the left' ),
                    'right-sidebar'             =>  a13__be( 'Sidebar on the right' ),
                    'right-sidebar_and_nav'     =>  a13__be( 'Children Navigation + sidebar on the right' ),
                    'right-nav'                 =>  a13__be( 'Only children Navigation on the right' ),
                    'off'                       =>  a13__be( 'Off' ),
                ),
                'type' => 'select',
            ),
            array(
                'name' =>  a13__be( 'Custom background' ),
                'id' => 'custom_background',
                'default' => 'off',
                'options' => array(
                    'on' =>  a13__be( 'Enable' ),
                    'off' =>  a13__be( 'Disable' ),
                ),
                'type' => 'radio',
            ),
            array(
                'name' =>  a13__be( 'Background image' ),
                'id' => 'body_image',
                'default' => '',
                'type' => 'image',
                'required' => array(
                    'custom_background' => 'on',
                ),
            ),
            array(
                'name' =>  a13__be( 'How to fit background image' ),
                'id' => 'body_image_fit',
                'default' => 'cover',
                'options' => array(
                    'cover'     =>  a13__be( 'Cover' ),
                    'contain'   =>  a13__be( 'Contain' ),
                    'fitV'      =>  a13__be( 'Fit Vertically' ),
                    'fitH'      =>  a13__be( 'Fit Horizontally' ),
                    'center'    =>  a13__be( 'Just center' ),
                    'repeat'    =>  a13__be( 'Repeat' ),
                    'repeat-x'  =>  a13__be( 'Repeat X' ),
                    'repeat-y'  =>  a13__be( 'Repeat Y' ),
                ),
                'type' => 'select',
                'required' => array(
                    'custom_background' => 'on',
                ),
            ),
            array(
                'name' =>  a13__be( 'Background color' ),
                'id' => 'body_bg_color',
                'default' => '',
                'type' => 'color',
                'required' => array(
                    'custom_background' => 'on',
                ),
            ),

            array(
                'name'  =>  a13__be( 'Top space' ),
                'description'  => a13__be( 'Distance of content and title bar from top edge of page.' ),
                'type'  => 'fieldset',
                'id'    => 'fieldset_page_top_space'
            ),
            array(
                'name' =>  a13__be( 'Custom top space' ),
                'id' => 'custom_top_space',
                'default' => 'off',
                'options' => array(
                    'on' =>  a13__be( 'Enable' ),
                    'off' =>  a13__be( 'Disable' ),
                ),
                'type' => 'radio',
            ),
            array(
                'name' =>  a13__be( 'Top space value' ),
                'id' => 'top_space_height',
                'default' => '100px',
                'unit' => 'px',
                'min' => 0,
                'max' => 600,
                'type' => 'slider',
                'required' => array(
                    'custom_top_space' => 'on',
                ),
            ),
            array(
                'name' =>  a13__be( 'Top space background color' ),
                'id' => 'top_space_bg_color',
                'default' => '',
                'type' => 'color',
                'required' => array(
                    'custom_top_space' => 'on',
                ),
            ),


            array(
                'name' =>  a13__be( 'Title Bar' ),
                'type' => 'fieldset',
                'id' => 'fieldset_page_title_bar'
            ),
            array(
                'name' =>  a13__be( 'Custom title bar' ),
                'id' => 'custom_title_bar',
                'default' => 'off',
                'options' => array(
                    'on' =>  a13__be( 'Enable' ),
                    'off' =>  a13__be( 'Disable' ),
                ),
                'type' => 'radio',
            ),
            array(
                'name' =>  a13__be( 'Title Bar' ),
                'id' => 'title_bar',
                'default' => 'on',
                'options' => array(
                    'on' =>  a13__be( 'Enable' ),
                    'off' =>  a13__be( 'Disable' ),
                ),
                'type' => 'radio',
                'required' => array(
                    'custom_title_bar' => 'on',
                ),
            ),
            array(
                'name' =>  a13__be( 'Position' ),
                'id' => 'title_bar_position',
                'default' => 'outside',
                'type' => 'radio',
                'options' => array(
                    'outside'   =>  a13__be( 'Outside content' ),
                    'inside'    =>  a13__be( 'Inside content' ),
                ),
                'required' => array(
                    'custom_title_bar' => 'on',
                    'title_bar' => 'on',
                ),
            ),
            array(
                'name' =>  a13__be( 'Title font size' ),
                'id' => 'title_bar_title_size',
                'default' => '',
                'unit' => 'px',
                'type' => 'slider',
                'min' => 0,
                'max' => 60,
                'required' => array(
                    'custom_title_bar' => 'on',
                    'title_bar' => 'on',
                ),
            ),
            array(
                'name' =>  a13__be( 'Title font weight' ),
                'id' => 'title_bar_title_weight',
                'default' => 'bold',
                'options' => array(
                    'normal' =>  a13__be( 'Normal' ),
                    'bold' =>  a13__be( 'Bold' ),
                ),
                'type' => 'radio',
                'required' => array(
                    'custom_title_bar' => 'on',
                    'title_bar' => 'on',
                ),
            ),
            array(
                'name' =>  a13__be( 'Title color' ),
                'id' => 'title_bar_title_color',
                'default' => '',
                'type' => 'color',
                'required' => array(
                    'custom_title_bar' => 'on',
                    'title_bar' => 'on',
                    'title_bar_position' => 'outside',
                ),
            ),
            array(
                'name' =>  a13__be( 'Title background color' ),
                'id' => 'title_bar_bg_color',
                'default' => '',
                'type' => 'color',
                'required' => array(
                    'custom_title_bar' => 'on',
                    'title_bar' => 'on',
                    'title_bar_position' => 'outside',
                ),
            ),
            array(
                'name' =>  a13__be( 'Space in top and bottom' ),
                'id' => 'title_bar_space_width',
                'default' => '40px',
                'unit' => 'px',
                'min' => 0,
                'max' => 200,
                'type' => 'slider',
                'required' => array(
                    'custom_title_bar' => 'on',
                    'title_bar' => 'on',
                    'title_bar_position' => 'outside',
                ),
            ),


            array(
                'name' =>  a13__be( '404 page template' ),
                'type' => 'fieldset',
                'id' => 'fieldset_404_page',
            ),
            array(
                'name' =>  a13__be( 'Type' ),
                'id' => 'page_404_template_type',
                'default' => 'default',
                'options' => array(
                    'default' =>  a13__be( 'Default' ),
                    'custom' =>  a13__be( 'Custom' ),
                ),
                'type' => 'radio',
            ),
            array(
                'name' =>  a13__be( 'Default but I want to change background image' ),
                'id' => 'page_404_bg_image',
                'default' => '',
                'type' => 'image',
                'required' => array(
                    'page_404_template_type' => 'default',
                )
            ),
            array(
                'name' =>  a13__be( 'Select page as your template' ),
                'id' => 'page_404_template',
                'default' => 0,
                'type' => 'wp_dropdown_pages',
                'required' => array(
                    'page_404_template_type' => 'custom',
                )
            ),


            array(
                'name' =>  a13__be( 'Password protected page template' ),
                'type' => 'fieldset',
                'id' => 'fieldset_password_page',
                'description' => a13__be( 'While using default type, top space &amp; title bar will be styled depending on what post type was password protected. Page like pages, post like posts etc.' )
            ),
            array(
                'name' =>  a13__be( 'Type' ),
                'id' => 'page_password_template_type',
                'default' => 'default',
                'options' => array(
                    'default' =>  a13__be( 'Default' ),
                    'custom' =>  a13__be( 'Custom' ),
                ),
                'type' => 'radio',
            ),
            array(
                'name' =>  a13__be( 'Default but I want to change background image' ),
                'id' => 'page_password_bg_image',
                'default' => '',
                'type' => 'image',
                'required' => array(
                    'page_password_template_type' => 'default',
                )
            ),
            array(
                'name' =>  a13__be( 'Select page as your template' ),
                'id' => 'page_password_template',
                'default' => 0,
                'type' => 'wp_dropdown_pages',
                'required' => array(
                    'page_password_template_type' => 'custom',
                )
            ),
        );

        $args = array(
            'title'         => a13__be( 'Pages Layout' ),
            'opt'           => $opt
        );

        return $args;
    }
	
	function apollo13_album_options(){
			
		$opt = array(
            array(
                'name' =>  a13__be( 'General layout' ),
                'type' => 'fieldset',
                'id' => 'fieldset_album_general'
            ),
            array(
                'name' =>  a13__be( 'Subtitle' ),
                'description' =>  a13__be( 'You can use HTML here.' ),
                'id' => 'subtitle',
                'default' => '',
                'type' => 'text'
            ),
            array(
                'name' =>  a13__be( 'Custom background' ),
                'id' => 'custom_background',
                'default' => 'off',
                'options' => array(
                    'on' =>  a13__be( 'Enable' ),
                    'off' =>  a13__be( 'Disable' ),
                ),
                'type' => 'radio',
            ),
            array(
                'name' =>  a13__be( 'Background image' ),
                'id' => 'body_image',
                'default' => '',
                'type' => 'image',
                'required' => array(
                    'custom_background' => 'on',
                ),
            ),
            array(
                'name' =>  a13__be( 'How to fit background image' ),
                'id' => 'body_image_fit',
                'default' => 'cover',
                'options' => array(
                    'cover'     =>  a13__be( 'Cover' ),
                    'contain'   =>  a13__be( 'Contain' ),
                    'fitV'      =>  a13__be( 'Fit Vertically' ),
                    'fitH'      =>  a13__be( 'Fit Horizontally' ),
                    'center'    =>  a13__be( 'Just center' ),
                    'repeat'    =>  a13__be( 'Repeat' ),
                    'repeat-x'  =>  a13__be( 'Repeat X' ),
                    'repeat-y'  =>  a13__be( 'Repeat Y' ),
                ),
                'type' => 'select',
                'required' => array(
                    'custom_background' => 'on',
                ),
            ),
            array(
                'name' =>  a13__be( 'Background color' ),
                'id' => 'body_bg_color',
                'default' => '',
                'type' => 'color',
                'required' => array(
                    'custom_background' => 'on',
                ),
            ),



            array(
                'name'  =>  a13__be( 'Top space' ),
                'description'  => a13__be( 'Distance of content and title bar from top edge of page.' ),
                'type'  => 'fieldset',
                'id'    => 'fieldset_album_top_space'
            ),
            array(
                'name' =>  a13__be( 'Custom top space' ),
                'id' => 'custom_top_space',
                'default' => 'off',
                'options' => array(
                    'on' =>  a13__be( 'Enable' ),
                    'off' =>  a13__be( 'Disable' ),
                ),
                'type' => 'radio',
            ),
            array(
                'name' =>  a13__be( 'Top space value' ),
                'id' => 'top_space_height',
                'default' => '100px',
                'unit' => 'px',
                'min' => 0,
                'max' => 600,
                'type' => 'slider',
                'required' => array(
                    'custom_top_space' => 'on',
                ),
            ),
            array(
                'name' =>  a13__be( 'Top space background color' ),
                'id' => 'top_space_bg_color',
                'default' => '',
                'type' => 'color',
                'required' => array(
                    'custom_top_space' => 'on',
                ),
            ),


            array(
                'name' =>  a13__be( 'Title Bar' ),
                'type' => 'fieldset',
                'id' => 'fieldset_album_title_bar'
            ),
            array(
                'name' =>  a13__be( 'Custom title bar' ),
                'id' => 'custom_title_bar',
                'default' => 'off',
                'options' => array(
                    'on' =>  a13__be( 'Enable' ),
                    'off' =>  a13__be( 'Disable' ),
                ),
                'type' => 'radio',
            ),
            array(
                'name' =>  a13__be( 'Title Bar' ),
                'id' => 'title_bar',
                'default' => 'on',
                'options' => array(
                    'on' =>  a13__be( 'Enable' ),
                    'off' =>  a13__be( 'Disable' ),
                ),
                'type' => 'radio',
                'required' => array(
                    'custom_title_bar' => 'on',
                ),
            ),
            array(
                'name' =>  a13__be( 'Title font size' ),
                'id' => 'title_bar_title_size',
                'default' => '',
                'unit' => 'px',
                'type' => 'slider',
                'min' => 0,
                'max' => 60,
                'required' => array(
                    'custom_title_bar' => 'on',
                    'title_bar' => 'on',
                ),
            ),
            array(
                'name' =>  a13__be( 'Title font weight' ),
                'id' => 'title_bar_title_weight',
                'default' => 'bold',
                'options' => array(
                    'normal' =>  a13__be( 'Normal' ),
                    'bold' =>  a13__be( 'Bold' ),
                ),
                'type' => 'radio',
                'required' => array(
                    'custom_title_bar' => 'on',
                    'title_bar' => 'on',
                ),
            ),
            array(
                'name' =>  a13__be( 'Title color' ),
                'id' => 'title_bar_title_color',
                'default' => '',
                'type' => 'color',
                'required' => array(
                    'custom_title_bar' => 'on',
                    'title_bar' => 'on',
                ),
            ),
            array(
                'name' =>  a13__be( 'Title background color' ),
                'id' => 'title_bar_bg_color',
                'default' => '',
                'type' => 'color',
                'required' => array(
                    'custom_title_bar' => 'on',
                    'title_bar' => 'on',
                ),
            ),
            array(
                'name' =>  a13__be( 'Space in top and bottom' ),
                'id' => 'title_bar_space_width',
                'default' => '40px',
                'unit' => 'px',
                'min' => 0,
                'max' => 200,
                'type' => 'slider',
                'required' => array(
                    'custom_title_bar' => 'on',
                    'title_bar' => 'on',
                ),
            ),


            array(
                'name' =>  a13__be( 'Albums list appearance' ),
                'type' => 'fieldset',
                'id' => 'fieldset_albums_list',
            ),
			array(
				'name' =>  a13__be( 'Albums list main page' ),
				'desc' =>  a13__be( 'This page will list all your albums and also give main title for album categories pages.' ),
				'id' => 'albums_list_page',
				'default' => 0,
				'type' => 'wp_dropdown_pages',
			),
            array(
                'name' =>  a13__be( 'Bricks columns' ),
                'description' => a13__be('It only affects wider screen resolutions.'),
                'id' => 'brick_columns',
                'default' => '3',
                'unit' => '',
                'min' => 1,
                'max' => 4,
                'type' => 'slider',
            ),
            array(
                'name' =>  a13__be( 'Max width of bricks content.' ),
                'description' => a13__be('Depending on actual screen width and content style used for blog page, available space for bricks might be smaller, but newer greater then this number.'),
                'id' => 'bricks_max_width',
                'default' => '2000px',
                'unit' => 'px',
                'min' => 200,
                'max' => 2500,
                'type' => 'slider',
            ),
            array(
                'name' =>  a13__be( 'Brick margin' ),
                'id' => 'brick_margin',
                'default' => '10px',
                'unit' => 'px',
                'min' => 0,
                'max' => 100,
                'type' => 'slider',
            ),
            array(
                'name' =>  a13__be( 'Hover effect' ),
                'description' => a13__be( 'Hover on bricks in albums list.' ),
                'id' => 'albums_list_bricks_hover',
                'default' => 'default',
                'options' => array(
                    'default'   =>  a13__be( 'Default' ),
                    'none'    => a13__be( 'None' ),
                    'drop'      =>  a13__be( 'Drop' ),
                    'shift'     =>  a13__be( 'Shift' ),
                    'clap'      =>  a13__be( 'Clap' ),
                    'reveal'    =>  a13__be( 'Reveal' ),
                    'classic'   =>  a13__be( 'Classic' ),
                    'border'    =>  a13__be( 'border' ),
                    'uncover'   =>  a13__be( 'Uncover' ),
                ),
                'type' => 'select',
            ),
			array(
				'name' =>  a13__be( 'Album meta: Categories' ),
				'id' => 'album_categories',
				'default' => 'on',
				'options' => array(
					'on'    =>  a13__be( 'On' ),
					'off'   =>  a13__be( 'Off' ),
				),
				'type' => 'radio',
			),



			array(
				'name' =>  a13__be( 'Single album slug' ),
				'type' => 'fieldset',
                'id' => 'fieldset_album_slug',
			),
			array(
				'name' =>  a13__be( 'Album slug name' ),
				'description' => a13__be( 'Don\'t change this if you don\'t have to. Remember that if you use nice permalinks(eg. <code>yoursite.com/page-about-me</code>, <code>yoursite.com/album/damn-empty/</code>) then <strong>NONE of your static pages</strong> should have same slug as this, or pagination will break and other problems may appear.' ),
				'id' => 'cpt_post_type_album',
				'default' => 'album',
				'type' => 'text',
			),



            array(
			'name' => a13__be( 'Single album social icons' ),
			'type' => 'fieldset',
			'id'   => 'fieldset_album_social_icons'
            ),
            array(
                'id'      => 'album_social_icons',
                'type'    => 'radio',
                'name'    => a13__be( 'Use social icons in albums. If you are using AddToAny plugin for sharing, then you should check these options.' ),
                'options' => array(
                    'on'  => a13__be( 'Enable' ),
                    'off' => a13__be( 'Disable' ),
                ),
                'default' => 'on',
            ),
            array(
                'name'        => a13__be( 'Share link to album or to attachment page' ),
                'description' => a13__be( 'When using share plugin choose one way of sharing. More details in documentation.' ),
                'id'          => 'album_bricks_share_type',
                'default'     => 'album',
                'options'     => array(
                    'album'           => a13__be( 'Album' ),
                    'attachment_page' => a13__be( 'Attachment page' ),
                ),
                'type'        => 'radio',
                'required' => array(
                    'album_social_icons' => 'on',
                )
            ),



			array(
				'name' =>  a13__be( 'Single album appearance(Bricks)' ),
				'type' => 'fieldset',
                'id' => 'fieldset_album_app_bricks',
			),

            array(
                'name' =>  a13__be( 'Display content' ),
                'description' => a13__be( 'This will display separate brick with text about album.' ),
                'id' => 'album_content',
                'default' => 'on',
                'options' => array(
                    'on'    =>  a13__be( 'On' ),
                    'off'   =>  a13__be( 'Off' ),
                ),
                'type' => 'radio',
            ),
            array(
                'name' =>  a13__be( 'Title in content' ),
                'id' => 'album_content_title',
                'default' => 'on',
                'options' => array(
                    'on'    =>  a13__be( 'On' ),
                    'off'   =>  a13__be( 'Off' ),
                ),
                'type' => 'radio',
                'required' => array(
	                'album_content' => 'on',
                )
            ),
            array(
                'name' =>  a13__be( 'Categories in content' ),
                'id' => 'album_content_categories',
                'default' => 'on',
                'options' => array(
                    'on'    =>  a13__be( 'On' ),
                    'off'   =>  a13__be( 'Off' ),
                ),
                'type' => 'radio',
                'required' => array(
	                'album_content' => 'on',
                )
            ),
            array(
                'name' =>  a13__be( 'Display thumbs instead of video' ),
                'description' => a13__be( 'Video will be displayed in lightbox if enabled.' ),
                'id' => 'album_bricks_thumb_video',
                'default' => 'off',
                'options' => array(
                    'on'    =>  a13__be( 'On' ),
                    'off'   =>  a13__be( 'Off' ),
                ),
                'type' => 'radio',
            ),



            array(
                'name' =>  a13__be( 'Single album appearance(Slider)' ),
                'type' => 'fieldset',
                'id' => 'fieldset_album_app_slider'
            ),
            array(
                'name' =>  a13__be( 'Autoplay' ),
                'description' => a13__be( 'If autoplay is on, slider will run on page load. Global setting, but you can change this in each album.' ),
                'id' => 'autoplay',
                'default' => '1',
                'options' => array(
                    '1' =>  a13__be( 'Enable' ),
                    '0' =>  a13__be( 'Disable' ),
                ),
                'type' => 'radio',
            ),
            array(
                'name' =>  a13__be( 'Slide interval(ms)' ),
                'description' => a13__be( 'Global for all albums.' ),
                'id' => 'slide_interval',
                'default' => 7000,
                'unit' => '',
                'min' => 0,
                'max' => 15000,
                'type' => 'slider'
            ),
            array(
                'name' =>  a13__be( 'Transition type' ),
                'description' => a13__be( 'Animation between slides.' ),
                'id' => 'transition_type',
                'default' => '2',
                'options' => array(
                    '0' =>  a13__be( 'None' ),
                    '1' =>  a13__be( 'Fade' ),
                    '2' =>  a13__be( 'Carousel' ),
                    '3' =>  a13__be( 'Zooming' ),
                ),
                'type' => 'select',
            ),
            array(
                'name' =>  a13__be( 'Transition speed(ms)' ),
                'description' => a13__be( 'Speed of transition.' ) . ' ' .  a13__be( 'Global for all albums.' ),
                'id' => 'transition_time',
                'default' => 600,
                'unit' => '',
                'min' => 0,
                'max' => 10000,
                'type' => 'slider'
            ),
            array(
                'name' =>  a13__be( 'List of Thumbs' ),
                'description' => a13__be( 'Global for all albums.' ).' '.a13__be( 'Can be overwritten in each album.' ),
                'id' => 'thumbs',
                'default' => 'on',
                'options' => array(
                    'on' =>  a13__be( 'Enable' ),
                    'off' =>  a13__be( 'Disable' ),
                ),
                'type' => 'radio',
            ),
            array(
                'name' =>  a13__be( 'Show thumbs on page load' ),
                'description' => a13__be( 'Global for all albums.' ).' '.a13__be( 'Can be overwritten in each album.' ),
                'id' => 'thumbs_on_load',
                'default' => 'on',
                'options' => array(
                    'on' =>  a13__be( 'Enable' ),
                    'off' =>  a13__be( 'Disable' ),
                ),
                'type' => 'radio',
                'required' => array(
	                'thumbs' => 'on',
                )
            ),
		);

		$args = array(
			'title'         => a13__be( 'Albums Layout' ),
			'opt'           => $opt
		);

		return $args;
	}

	function apollo13_socials_options(){
		$socials = array(
			'500px'         => '500px',
			'aim'           => 'Aim',
			'behance'       => 'Behance',
			'blogger'       => 'Blogger',
			'delicious'     => 'Delicious',
			'deviantart'    => 'Deviantart',
			'digg'          => 'Digg',
			'dribbble'      => 'Dribbble',
            'dropbox'       => 'Dropbox',
            'mailto'        => 'E-mail',
			'evernote'      => 'Evernote',
			'facebook'      => 'Facebook',
			'flickr'        => 'Flickr',
			'forrst'        => 'Forrst',
			'foursquare'    => 'Foursquare',
			'github'        => 'Github',
			'googleplus'    => 'Google Plus',
			'instagram'     => 'Instagram',
			'lastfm'        => 'Lastfm',
			'linkedin'      => 'Linkedin',
			'paypal'        => 'Paypal',
			'pinterest'     => 'Pinterest',
			'quora'         => 'Quora',
            'reddit'        => "Reddit",
			'rss'           => 'RSS',
			'sharethis'     => 'Sharethis',
			'skype'         => 'Skype',
            'spotify'       => 'Spotify',
			'stumbleupon'   => 'Stumbleupon',
			'tumblr'        => 'Tumblr',
			'twitter'       => 'Twitter',
			'vimeo'         => 'Vimeo',
            'vkontakte'     => 'Vkontakte',
			'wordpress'     => 'WordPress',
			'yahoo'         => 'Yahoo',
			'yelp'          => 'Yelp',
			'youtube'       => 'Youtube',
            'custom'        => 'Custom'
		);

		//just ids and empty links as values : array('social_id' => '', ... )
		$services = array_keys($socials);
		//prepare clean array
		foreach($services as $id){
			$default_settings[] = array('id' => $id, 'link' => '');
		}
		//default value for social services
		$default = json_encode($default_settings);

		$opt = array(
			array(
				'name' =>  a13__be( 'Social settings' ),
				'type' => 'fieldset',
                'id' => 'fieldset_social',
			),
            array(
                'name' =>  a13__be( 'Type of icons' ),
                'id' => 'socials_variant',
                'default' => 'squares',
                'options' => array(
                    'squares' =>  a13__be( 'Squares' ),
                    'icons-only' =>  a13__be( 'Only icons' ),
                ),
                'type' => 'radio',
            ),



			array(
				'name' =>  a13__be( 'Social services' ),
				'type' => 'fieldset',
                'id'   => 'sortable-socials'
			),
			array(
				'name' =>  a13__be( 'Social services' ),
				//If you face problems with saving this options, then please remove <code>http://</code> from your social links.<br />It will be converted to proper links on front-end, so don\'t worry;-)
				'description' => a13__be( 'Drag and drop to change order of icons. Only filled links will show up as social icons.' ),
				'id' => 'social_services',
				'default' => $default,
				'type' => 'socials',
				'options' => $socials
			),
		);

		$args = array(
			'title'         => a13__be( 'Social icons' ),
			'opt'           => $opt
		);

		return $args;
	}

	function apollo13_customize_options(){

		$opt = array(
            array(
                'name' =>  a13__be( 'Custom CSS' ),
                'type' => 'fieldset',
                'id' => 'fieldset_custom_css',
            ),
            array(
                'name' =>  a13__be( 'Custom CSS' ),
                'id' => 'custom_css',
                'default' => '',
                'type' => 'textarea',
            ),
		);

	    $args = array(
		    'title'         => a13__be( 'Custom CSS' ),
		    'opt'           => $opt
	    );

	    return $args;
	}

	function apollo13_sidebars_options(){

        $opt = array(
            array(
                'name' =>  a13__be( 'Add custom sidebars' ),
                'type' => 'fieldset',
                'default' => 1,
                'id' => 'fieldset_sidebars',
            ),
            array(
                'name' =>  a13__be( 'New sidebar name' ),
                'description' => a13__be( 'Choose name for new sidebar and click <b>Save Changes</b> to add it.' ),
                'id' => 'custom_sidebars',
                'default' => '',
                'placeholder' => 'New sidebar name',
                'type' => 'sidebars',
            ),
        );

	    $args = array(
		    'title'         => a13__be( 'Sidebars' ),
		    'opt'           => $opt
	    );

	    return $args;
    }

	function apollo13_advanced_options(){

		$opt = array(//so it wont be removed from translation
			array(
				'name' =>  a13__be( 'AJAX settings' ),
				'type' => 'fieldset',
                'default' => 1,
                'id' => 'fieldset_ajax',
			),
			array(
				'name' =>  a13__be( 'Dynamic site loading(AJAX)' ),
				'description' => a13__be( 'If you face serious problems with your site front-end try switch it off.' ),
				'id' => 'ajax',
				'default' => 'off',
				'type' => 'radio',
				'options' => array(
					'on' =>  a13__be( 'On' ),
					'off'    =>  a13__be( 'Turn it off' ),
				),
			),
			array(
				'name' =>  a13__be( 'AJAX when admin bar is active' ),
				'description' => a13__be( 'Admin bar is not refreshed properly when full page AJAX is activated. For people that use it a lot it can be annoying.' ),
				'id' => 'ajax_admin_bar',
				'default' => 'on',
				'type' => 'radio',
				'options' => array(
					'on' =>  a13__be( 'On' ),
					'off'    =>  a13__be( 'Turn it off' ),
				),
			),
			array(
				'name' =>  a13__be( 'Sites that shouldn\'t be loaded dynamic' ),
				'description' => a13__be( 'One link per row.' ),
				'id' => 'no_ajax_links',
				'default' => '',
				'type' => 'textarea',
			),
			array(
				'name' =>  a13__be( 'Custom JavaScript' ),
				'description' => a13__be( 'Can be used to bind to AJAX callback for example.' ),
				'id' => 'custom_js',
				'default' => '',
				'type' => 'textarea',
			),



			array(
				'name' =>  a13__be( 'Miscellaneous settings' ),
				'type' => 'fieldset',
                'default' => 1,
                'id' => 'fieldset_misc',
			),
			array(
				'name' =>  a13__be( 'Theme lightbox' ),
				'description' => a13__be( 'If you wish to use some other plugin/script for images and items switch it off.' ),
				'id' => 'apollo_lightbox',
				'default' => 'lightGallery',
				'options' => array(
					'lightGallery' =>  a13__be( 'lightGallery' ),
					'off' =>  a13__be( 'Disable' ),
				),
				'type' => 'select',
                'switch'      => true,
			),
            array(
                'name' => 'lightGallery',
                'type' => 'switch-group'
            ),
            array(
                'name' =>  a13__be( 'lightGallery transition between images' ),
                'id' => 'lg_lightbox_mode',
                'default' => 'lg-slide',
                'type' => 'select',
                'options' => array(
                    'lg-slide'                    => 'lg-slide',
                    'lg-fade'                     => 'lg-fade',
                    'lg-zoom-in'                  => 'lg-zoom-in',
                    'lg-zoom-in-big'              => 'lg-zoom-in-big',
                    'lg-zoom-out'                 => 'lg-zoom-out',
                    'lg-zoom-out-big'             => 'lg-zoom-out-big',
                    'lg-zoom-out-in'              => 'lg-zoom-out-in',
                    'lg-zoom-in-out'              => 'lg-zoom-in-out',
                    'lg-soft-zoom'                => 'lg-soft-zoom',
                    'lg-scale-up'                 => 'lg-scale-up',
                    'lg-slide-circular'           => 'lg-slide-circular',
                    'lg-slide-circular-vertical'  => 'lg-slide-circular-vertical',
                    'lg-slide-vertical'           => 'lg-slide-vertical',
                    'lg-slide-vertical-growth'    => 'lg-slide-vertical-growth',
                    'lg-slide-skew-only'          => 'lg-slide-skew-only',
                    'lg-slide-skew-only-rev'      => 'lg-slide-skew-only-rev',
                    'lg-slide-skew-only-y'        => 'lg-slide-skew-only-y',
                    'lg-slide-skew-only-y-rev'    => 'lg-slide-skew-only-y-rev',
                    'lg-slide-skew'               => 'lg-slide-skew',
                    'lg-slide-skew-rev'           => 'lg-slide-skew-rev',
                    'lg-slide-skew-cross'         => 'lg-slide-skew-cross',
                    'lg-slide-skew-cross-rev'     => 'lg-slide-skew-cross-rev',
                    'lg-slide-skew-ver'           => 'lg-slide-skew-ver',
                    'lg-slide-skew-ver-rev'       => 'lg-slide-skew-ver-rev',
                    'lg-slide-skew-ver-cross'     => 'lg-slide-skew-ver-cross',
                    'lg-slide-skew-ver-cross-rev' => 'lg-slide-skew-ver-cross-rev',
                    'lg-lollipop'                 => 'lg-lollipop',
                    'lg-lollipop-rev'             => 'lg-lollipop-rev',
                    'lg-rotate'                   => 'lg-rotate',
                    'lg-rotate-rev'               => 'lg-rotate-rev',
                    'lg-tube'                     => 'lg-tube',
                ),
            ),
            array(
                'name' =>  a13__be( 'lightGallery transition speed(in ms)' ),
                'id' => 'lg_lightbox_speed',
                'default' => '600',
				'unit' => '',
				'min' => 100,
				'max' => 1000,
				'type' => 'slider',
			),
            array(
                'name' =>  a13__be( 'lightGallery arrow controls' ),
                'description' => a13__be( 'Arrows to next nad previous slide.' ),
                'id' => 'lg_lightbox_controls',
                'default' => 'on',
                'type' => 'radio',
                'options' => array(
                    'on' =>  a13__be( 'On' ),
                    'off'    =>  a13__be( 'Turn it off' ),
                ),
            ),
            array(
                'name' =>  a13__be( 'lightGallery download control' ),
                'id' => 'lg_lightbox_download',
                'default' => 'off',
                'type' => 'radio',
                'options' => array(
                    'on' =>  a13__be( 'On' ),
                    'off'    =>  a13__be( 'Turn it off' ),
                ),
            ),
            array(
                'name' =>  a13__be( 'lightGallery full screen control' ),
                'id' => 'lg_lightbox_full_screen',
                'default' => 'on',
                'type' => 'radio',
                'options' => array(
                    'on' =>  a13__be( 'On' ),
                    'off'    =>  a13__be( 'Turn it off' ),
                ),
            ),
            array(
                'name' =>  a13__be( 'lightGallery zoom controls' ),
                'id' => 'lg_lightbox_zoom',
                'default' => 'off',
                'type' => 'radio',
                'options' => array(
                    'on' =>  a13__be( 'On' ),
                    'off'    =>  a13__be( 'Turn it off' ),
                ),
            ),
            array(
                'name' =>  a13__be( 'lightGallery autoplay control' ),
                'id' => 'lg_lightbox_autoplay',
                'default' => 'on',
                'type' => 'radio',
                'options' => array(
                    'on' =>  a13__be( 'On' ),
                    'off'    =>  a13__be( 'Turn it off' ),
                ),
            ),
            array(
                'name' =>  a13__be( 'lightGallery autoplay on open' ),
                'id' => 'lg_lightbox_autoplay_open',
                'default' => 'off',
                'type' => 'radio',
                'options' => array(
                    'on' =>  a13__be( 'On' ),
                    'off'    =>  a13__be( 'Turn it off' ),
                ),
            ),
            array(
                'name' =>  a13__be( 'lightGallery slides counter' ),
                'id' => 'lg_lightbox_counter',
                'default' => 'on',
                'type' => 'radio',
                'options' => array(
                    'on' =>  a13__be( 'On' ),
                    'off'    =>  a13__be( 'Turn it off' ),
                ),
            ),
            array(
                'name' =>  a13__be( 'lightGallery thumbnails' ),
                'id' => 'lg_lightbox_thumbnail',
                'default' => 'on',
                'type' => 'radio',
                'options' => array(
                    'on' =>  a13__be( 'On' ),
                    'off'    =>  a13__be( 'Turn it off' ),
                ),
            ),
            array(
                'name' =>  a13__be( 'lightGallery show thumbnails on open' ),
                'id' => 'lg_lightbox_show_thumbs',
                'default' => 'off',
                'type' => 'radio',
                'options' => array(
                    'on' =>  a13__be( 'On' ),
                    'off'    =>  a13__be( 'Turn it off' ),
                ),
            ),
            array(
                'name'        => a13__be( 'lightGallery main background color' ),
                'id'          => 'lg_lightbox_bg_color',
                'default'     => '',
                'type'        => 'color'
            ),
            array(
                'name'        => a13__be( 'lightGallery semi transparent elements background color' ),
                'description' => a13__be( 'Set it to transparent if you wish it wont cover your images at all.' ),
                'id'          => 'lg_lightbox_elements_bg_color',
                'default'     => 'rgba(0, 0, 0, 0.45)',
                'type'        => 'color'
            ),
            array(
                'name'        => a13__be( 'lightGallery semi transparent elements color' ),
                'id'          => 'lg_lightbox_elements_color',
                'default'     => '',
                'type'        => 'color'
            ),
            array(
                'name'        => a13__be( 'lightGallery semi transparent elements hover color' ),
                'id'          => 'lg_lightbox_elements_color_hover',
                'default'     => '',
                'type'        => 'color'
            ),
            array(
                'name'        => a13__be( 'lightGallery semi transparent elements text color' ),
                'id'          => 'lg_lightbox_elements_text_color',
                'default'     => '',
                'type'        => 'color'
            ),
            array(
                'name'        => a13__be( 'lightGallery thumbnails tray background color' ),
                'id'          => 'lg_lightbox_thumbs_bg_color',
                'default'     => '',
                'type'        => 'color'
            ),
            array(
                'name'        => a13__be( 'lightGallery thumbs border color' ),
                'id'          => 'lg_lightbox_thumbs_border_bg_color',
                'default'     => '',
                'type'        => 'color'
            ),
            array(
                'name'        => a13__be( 'lightGallery thumbs hover border color' ),
                'id'          => 'lg_lightbox_thumbs_border_bg_color_hover',
                'default'     => '',
                'type'        => 'color'
            ),
            array(
                /*'name' => 'lightGallery',*/
                'type' => 'switch-group-end'
            ),
            array(
                /*'id' => 'apollo_lightbox',  just for readability */
                'type' => 'end-switch',
            ),
		);

        if(a13_is_home_server()){
            $opt[] = array(
                'name' =>  a13__be( 'Demo switcher' ),
                
                'id' => 'demo_switcher',
                'default' => 'off',
                'options' => array(
                    'on' =>  a13__be( 'Enable' ),
                    'off' =>  a13__be( 'Disable' ),
                ),
                'type' => 'radio',
            );
        }

		$args = array(
			'title'         => a13__be( 'Advanced' ),
			'opt'           => $opt
		);

		return $args;
	}

    function apollo13_import_options(){
        $demo_sets = array('none' => '');
        $dir = A13_TPL_ADV_DIR.'/demo_settings';
        if( is_dir( $dir ) ) {
            foreach ( (array)glob($dir.'/*') as $file ){
                $name = basename($file);
                if($name === '_order'){
                    continue;
                }
                $demo_sets[ basename($name) ] = basename($name);
            }
        }

        $opt = array(
			array(
				'name' => a13__be( 'Import demo data' ),
				'type' => 'fieldset',
				'default' => 1,
				'id' => 'fieldset_import_demo_data',
				'no_save_button' => true
			),
			array(
				'name' => a13__be( 'Import demo data' ),
				'description' => a13__be( 'This will import demo data as seen on our demo page. It will also wipe any pages, posts and other theme settings that are in your site. So it is best to use on fresh WordPress installation. Also this import does NOT include many of images or sound files, that you can see on our demo, as we don\'t have any rights to redistribute them.' ),
				'id' => 'import_demo_data',
				'default' => '',
				'type' => 'import_demo_data',
			),



            array(
                'name' =>  a13__be( 'Import/export theme options' ),
                'type' => 'fieldset',
                'default' => 1,
                'id' => 'fieldset_import',
				'no_save_button' => true
            ),
            array(
                'name' => 'Import info',
                
                'id' => 'import_page',
                'default' => 'yes',
                'type' => 'hidden'
            ),
            array(
                'name' =>  a13__be( 'Predefined demo sets' ),
                'description' => a13__be( 'You can select one of our demo sets. It will overwrite all theme settings you have made in panel.' ),
                'id' => 'import_options_select',
                'default' => '',
                'options' => $demo_sets,
                'type' => 'import_set_select',
            ),
            array(
                'name' =>  a13__be( 'Import' ),
                'description' => a13__be( 'Depending is it whole or partial import, it will overwrite current theme options.' ),
                'id' => 'import_options_field',
                'default' => '',
                'type' => 'import_textarea',
            ),
            array(
                'name' =>  a13__be( 'Export' ),
                'description' => a13__be( 'If you care about your current theme settings: copy and save above string in file before importing anything.' ),
                'id' => 'export_options_field',
                'default' => '',
                'type' => 'export_textarea',
            ),
            array(
                'name' =>  a13__be( 'Reset to default' ),
                'description' => a13__be( "It will reset theme options to default. It doesn't change any pages, or other content that is not set by theme options." ),
                'id' => 'reset_options',
                'default' => 'off',
                'options' => array(
                    'on' =>  a13__be( 'Reset' ),
                    'off' =>  a13__be( 'Do nothing' ),
                ),
                'type' => 'import_radio_reset',
            ),
        );

		if(a13_is_home_server()){
			$demo_data_export_options = array(
				array(
					'name' => a13__be( 'Export demo data options' ),
					'type' => 'fieldset',
					'default' => 1,
					'id' => 'fieldset_demo_data_export',
					'no_save_button' => true
				),
				array(
					'name' => a13__be( 'site_config file' ),
					
					'id' => 'export_site_config',
					'default' => '',
					'type' => 'export_site_config',
				),
			);

			$opt = array_merge($opt, $demo_data_export_options);
		}

	    $args = array(
		    'title'         => a13__be( 'Import &amp; export' ),
		    'opt'           => $opt
	    );

	    return $args;
    }
