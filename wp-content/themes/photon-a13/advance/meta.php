<?php

function apollo13_metaboxes_post() {
	$meta = array(
		array(
			'name' => '',
			'type' => 'fieldset',
		),
		array(
			'name'        => a13__be( 'Alternative Link' ),
			'description' => a13__be( 'If you fill this then when selecting post from Blog(post list), it will lead to this link instead of opening post.' ),
			'id'          => 'alt_link',
			'default'     => '',
			'type'        => 'text',
		),
		array(
			'name'        => a13__be( 'Post media' ),
			'description' => a13__be( 'Choose between Image, Video and Sliders. For image use Featured Image Option' ),
			'id'          => 'image_or_video',
			'default'     => 'post_image',
			'options'     => array(
				'post_image' => a13__be( 'Image' ),
				'post_video' => a13__be( 'Video' ),
			),
			'switch'      => true,
			'type'        => 'radio',
		),
		array(
			'name' => 'post_image',
			'type' => 'switch-group'
		),
		array(
			'name'        => a13__be( 'Display post intro on image' ),
			'description' => a13__be( 'You can set text colors in Customizer-> Blog Layout-> Bricks colors in "Display post intro on image"' ),
			'id'          => 'display_on_image',
			'default'     => 'off',
			'type'        => 'radio',
			'options'     => array(
				'off' => a13__be( 'No' ),
				'on'  => a13__be( 'Yes' ),
			),
			'switch'      => true,
		),
		array(
			'name' => 'on',
			'type' => 'switch-group'
		),
		array(
			'name'        => a13__be( 'Color covering image' ),
			'description' => a13__be( 'Leave empty if no cover is needed. Useful if image is too colorful.' ),
			'id'          => 'image_cover_color',
			'default'     => '',
			'type'        => 'color'
		),
		array(
			'name'        => a13__be( 'Empty space in top' ),
			'description' => a13__be( 'To better show up image.' ),
			'id'          => 'image_cover_top_space',
			'default'     => '0',
			'unit'        => 'px',
			'min'         => 0,
			'max'         => 400,
			'type'        => 'slider'
		),
		array(
			/*'name' => 'on',*/
			'type' => 'switch-group-end'
		),
		array(
			/*'id' => 'display_on_image',  just for readability */
			'type' => 'end-switch',
		),
		array(
			/*'name' => 'post_image',*/
			'type' => 'switch-group-end'
		),
		array(
			'name' => 'post_video',
			'type' => 'switch-group'
		),
		array(
			'name'              => a13__be( 'Link to video' ),
			'description'       => a13__be( 'Insert here link to your video file or upload it. You can also add video from youtube or vimeo by pasting here link to movie.' ),
			'id'                => 'post_video',
			'default'           => '',
			'type'              => 'upload',
			'button_text'       => a13__be( 'Upload media file' ),
			'media_button_text' => a13__be( 'Insert media file' ),
			'media_type'        => 'video', /* 'audio,video' */
		),
		array(
			/*'name' => 'post_video',*/
			'type' => 'switch-group-end'
		),
		array(
			/*'id' => 'image_or_video',  just for readability */
			'type' => 'end-switch',
		),
		array(
			'name'        => a13__be( 'Size of brick' ),
			'description' => a13__be( 'How many bricks area should take this post in posts list.' ),
			'id'          => 'brick_ratio_x',
			'default'     => 1,
			'unit'        => '',
			'min'         => 1,
			'max'         => 4,
			'type'        => 'slider'
		),
	);

	return $meta;
}

function apollo13_metaboxes_post_layout() {
	$meta = array(
		array(
			'name' => '',
			'type' => 'fieldset',
		),
		array(
			'name'        => a13__be( 'Page background' ),
			'description' => a13__be( 'You can use global settings or overwrite them here' ),
			'id'          => 'page_bg_settings',
			'default'     => 'global',
			'type'        => 'radio',
			'options'     => array(
				'global' => a13__be( 'Global settings' ),
				'custom' => a13__be( 'Use custom settings' ),
			),
			'switch'      => true,
		),
		array(
			'name' => 'custom',
			'type' => 'switch-group'
		),
		array(
			'name'        => a13__be( 'Page Background image file' ),
			'description' => '',
			'id'          => 'page_image',
			'default'     => '',
			'button_text' => a13__be( 'Upload Image' ),
			'type'        => 'upload'
		),
		array(
			'name'        => a13__be( 'How to fit background image' ),
			'description' => '',
			'id'          => 'page_image_fit',
			'default'     => 'cover',
			'options'     => array(
				'cover'    => a13__be( 'Cover' ),
				'contain'  => a13__be( 'Contain' ),
				'fitV'     => a13__be( 'Fit Vertically' ),
				'fitH'     => a13__be( 'Fit Horizontally' ),
				'center'   => a13__be( 'Just center' ),
				'repeat'   => a13__be( 'Repeat' ),
				'repeat-x' => a13__be( 'Repeat X' ),
				'repeat-y' => a13__be( 'Repeat Y' ),
			),
			'type'        => 'select',
		),
		array(
			'name'        => a13__be( 'Page Background color' ),
			'description' => '',
			'id'          => 'page_bg_color',
			'default'     => '',
			'type'        => 'color'
		),
		array(
			/*'name' => 'custom',*/
			'type' => 'switch-group-end'
		),
		array(
			/*'id' => 'page_bg_settings',  just for readability */
			'type' => 'end-switch',
		),
	);

	return $meta;
}

function apollo13_metaboxes_page() {
	$meta = array(
		array(
			'name' => '',
			'type' => 'fieldset'
		),
		array(
			'name'        => a13__be( 'Subtitle' ),
			'description' => a13__be( 'You can use HTML here.' ),
			'id'          => 'subtitle',
			'default'     => '',
			'type'        => 'text'
		),
		array(
			'name'        => a13__be( 'Post media' ),
			'description' => a13__be( 'Choose between Image, Video and Sliders. For image use Featured Image Option' ),
			'id'          => 'image_or_video',
			'default'     => 'post_image',
			'options'     => array(
				'post_image' => a13__be( 'Image' ),
				'post_video' => a13__be( 'Video' ),
			),
			'switch'      => true,
			'type'        => 'radio',
		),
		array(
			'name' => 'post_video',
			'type' => 'switch-group'
		),
		array(
			'name'              => a13__be( 'Link to video' ),
			'description'       => a13__be( 'Insert here link to your video file or upload it. You can also add video from youtube or vimeo by pasting here link to movie.' ),
			'id'                => 'post_video',
			'default'           => '',
			'type'              => 'upload',
			'button_text'       => a13__be( 'Upload media file' ),
			'media_button_text' => a13__be( 'Insert media file' ),
			'media_type'        => 'video', /* 'audio,video' */
		),
		array(
			/*'name' => 'post_video',*/
			'type' => 'switch-group-end'
		),
		array(
			/*'id' => 'image_or_video',  just for readability */
			'type' => 'end-switch',
		),
	);

	return $meta;
}

function apollo13_metaboxes_page_layout() {
	global $a13_apollo13;
	$sidebars        = array(
		'default' => a13__be( 'Default for pages' ),
	);
	$custom_sidebars = unserialize( $a13_apollo13->get_option( 'sidebars', 'custom_sidebars' ) );
	$sidebars_count  = count( $custom_sidebars );
	if ( is_array( $custom_sidebars ) && $sidebars_count > 0 ) {
		foreach ( $custom_sidebars as $sidebar ) {
			$sidebars[ $sidebar['id'] ] = $sidebar['name'];
		}
	}

	$meta = array(
		array(
			'name' => '',
			'type' => 'fieldset'
		),
		array(
			'name'          => a13__be( 'Content Layout' ),
			'description'   => '',
			'id'            => 'content_layout',
			'default'       => 'global',
			'global_value'  => 'global',
			'parent_option' => array( 'page', 'content_layout' ),
			'type'          => 'select',
			'options'       => array(
				'global'        => a13__be( 'Global settings' ),
				'center'        => a13__be( 'Center fixed width' ),
				'left'          => a13__be( 'Left fixed width' ),
				'left_padding'  => a13__be( 'Left fixed width + padding' ),
				'right'         => a13__be( 'Right fixed width' ),
				'right_padding' => a13__be( 'Right fixed width + padding' ),
				'full_fixed'    => a13__be( 'Full width + fixed content' ),
				'full_padding'  => a13__be( 'Full width + padding' ),
				'full'          => a13__be( 'Full width' ),
			),
		),
		array(
			'name'        => a13__be( 'Title bar look' ),
			'description' => a13__be( 'You can use global settings or overwrite them here' ),
			'id'          => 'title_bar_settings',
			'default'     => 'global',
			'type'        => 'radio',
			'options'     => array(
				'global' => a13__be( 'Global settings' ),
				'custom' => a13__be( 'Use custom settings' ),
				'off'    => a13__be( 'Turn it off' ),
			),
			'switch'      => true,
		),
		array(
			'name' => 'custom',
			'type' => 'switch-group'
		),
		array(
			'name'        => a13__be( 'Position' ),
			'description' => '',
			'id'          => 'title_bar_position',
			'default'     => 'outside',
			'type'        => 'radio',
			'options'     => array(
				'outside' => a13__be( 'Outside content' ),
				'inside'  => a13__be( 'Inside content' ),
			),
			'switch'      => true,
		),
		array(
			'name' => 'outside',
			'type' => 'switch-group'
		),
		array(
			'name'        => a13__be( 'Background color' ),
			'description' => '',
			'id'          => 'title_bar_bg_color',
			'default'     => '',
			'type'        => 'color'
		),
		array(
			'name'        => a13__be( 'Space in top and bottom' ),
			'description' => '',
			'id'          => 'title_bar_space_width',
			'default'     => '20px',
			'unit'        => 'px',
			'min'         => 0,
			'max'         => 200,
			'type'        => 'slider'
		),
		array(
			'name'        => a13__be( 'Text color' ),
			'description' => '',
			'id'          => 'title_bar_title_color',
			'default'     => '',
			'type'        => 'color'
		),
		array(
			/*'name' => 'outside',*/
			'type' => 'switch-group-end'
		),
		array(
			/*'id' => 'title_bar_position',  just for readability */
			'type' => 'end-switch',
		),
		array(
			/*'name' => 'custom',*/
			'type' => 'switch-group-end'
		),
		array(
			/*'id' => 'title_bar_settings',  just for readability */
			'type' => 'end-switch',
		),
		array(
			'name'        => a13__be( 'Top space' ),
			'description' => a13__be( 'Distance of content and title bar from top edge of page.' ),
			'id'          => 'top_space_settings',
			'default'     => 'global',
			'type'        => 'radio',
			'options'     => array(
				'global' => a13__be( 'Global settings' ),
				'custom' => a13__be( 'Use custom settings' ),
			),
			'switch'      => true,
		),
		array(
			'name' => 'custom',
			'type' => 'switch-group'
		),
		array(
			'name'        => a13__be( 'Top space value' ),
			'description' => '',
			'id'          => 'top_space_height',
			'default'     => '100px',
			'unit'        => 'px',
			'min'         => 0,
			'max'         => 600,
			'type'        => 'slider'
		),
		array(
			'name'        => a13__be( 'Top space background color' ),
			'description' => '',
			'id'          => 'top_space_bg_color',
			'default'     => '',
			'type'        => 'color'
		),
		array(
			/*'name' => 'custom',*/
			'type' => 'switch-group-end'
		),
		array(
			/*'id' => 'top_space_settings',  just for readability */
			'type' => 'end-switch',
		),
		array(
			'name'          => a13__be( 'Sidebar' ),
			'description'   => a13__be( 'If turned off, content will take full width.' ),
			'id'            => 'widget_area',
			'global_value'  => 'G',
			'default'       => 'G',
			'parent_option' => array( 'page', 'page_sidebar' ),
			'options'       => array(
				'G'                     => a13__be( 'Global settings' ),
				'left-sidebar'          => a13__be( 'Sidebar on the left' ),
				'left-sidebar_and_nav'  => a13__be( 'Children Navigation + sidebar on the left' ),
				'left-nav'              => a13__be( 'Only children Navigation on the left' ),
				'right-sidebar'         => a13__be( 'Sidebar on the right' ),
				'right-sidebar_and_nav' => a13__be( 'Children Navigation + sidebar on the right' ),
				'right-nav'             => a13__be( 'Only children Navigation on the right' ),
				'off'                   => a13__be( 'Off' ),
			),
			'type'          => 'select',
		),
		array(
			'name'        => a13__be( 'Sidebar to show' ),
			'description' => '',
			'id'          => 'sidebar_to_show',
			'default'     => 'default',
			'options'     => $sidebars,
			'type'        => 'select',
		),
		array(
			'name'        => a13__be( 'Page background' ),
			'description' => a13__be( 'You can use global settings or overwrite them here' ),
			'id'          => 'page_bg_settings',
			'default'     => 'global',
			'type'        => 'radio',
			'options'     => array(
				'global' => a13__be( 'Global settings' ),
				'custom' => a13__be( 'Use custom settings' ),
			),
			'switch'      => true,
		),
		array(
			'name' => 'custom',
			'type' => 'switch-group'
		),
		array(
			'name'        => a13__be( 'Page Background image file' ),
			'description' => '',
			'id'          => 'page_image',
			'default'     => '',
			'button_text' => a13__be( 'Upload Image' ),
			'type'        => 'upload'
		),
		array(
			'name'        => a13__be( 'How to fit background image' ),
			'description' => '',
			'id'          => 'page_image_fit',
			'default'     => 'cover',
			'options'     => array(
				'cover'    => a13__be( 'Cover' ),
				'contain'  => a13__be( 'Contain' ),
				'fitV'     => a13__be( 'Fit Vertically' ),
				'fitH'     => a13__be( 'Fit Horizontally' ),
				'center'   => a13__be( 'Just center' ),
				'repeat'   => a13__be( 'Repeat' ),
				'repeat-x' => a13__be( 'Repeat X' ),
				'repeat-y' => a13__be( 'Repeat Y' ),
			),
			'type'        => 'select',
		),
		array(
			'name'        => a13__be( 'Page Background color' ),
			'description' => '',
			'id'          => 'page_bg_color',
			'default'     => '',
			'type'        => 'color'
		),
		array(
			/*'name' => 'custom',*/
			'type' => 'switch-group-end'
		),
		array(
			/*'id' => 'page_bg_settings',  just for readability */
			'type' => 'end-switch',
		),
	);

	return $meta;
}

function apollo13_metaboxes_album_list() {
	$meta = array(
		array(
			'name' => '',
			'type' => 'fieldset'
		),
		array(
			'name'        => a13__be( 'Alternative Link' ),
			'description' => a13__be( 'If you fill this then clicking in your album on albums list will not lead to single album page but to link from this field.' ),
			'id'          => 'alt_link',
			'default'     => '',
			'type'        => 'text',
		),
		array(
			'name'        => a13__be( 'Size of brick' ),
			'description' => a13__be( 'How many bricks area should take this album in albums list.' ),
			'id'          => 'brick_ratio_x',
			'default'     => 1,
			'unit'        => '',
			'min'         => 1,
			'max'         => 4,
			'type'        => 'slider'
		),
		array(
			'name'        => a13__be( 'Color under title' ),
			'description' => a13__be( 'Leave empty to not set any background' ),
			'id'          => 'title_bg_color',
			'default'     => '',
			'type'        => 'color'
		),
		array(
			'name'        => a13__be( 'Exclude from Albums list page.' ),
			'description' => a13__be( 'If enabled, then this album wont be listed on Albums list page, but you can still select it for front page or in other places.' ),
			'id'          => 'exclude_in_albums_list',
			'default'     => 'off',
			'type'        => 'radio',
			'options'     => array(
				'off' => a13__be( 'No' ),
				'on'  => a13__be( 'Yes' ),
			),
		),
	);

	return $meta;
}

function apollo13_metaboxes_album() {
	$meta = array(
		array(
			'name' => '',
			'type' => 'fieldset'
		),
		array(
			'name'        => a13__be( 'Items order' ),
			'description' => a13__be( 'It will display your images/videos from first to last, or another way.' ),
			'id'          => 'order',
			'default'     => 'ASC',
			'options'     => array(
				'ASC'    => a13__be( 'First on list, first displayed' ),
				'DESC'   => a13__be( 'First on list, last displayed' ),
				'random' => a13__be( 'Random' ),
			),
			'type'        => 'select',
		),
		array(
			'name'        => a13__be( 'Show title and description of album items.' ),
			'description' => a13__be( 'If enabled, then it will affect displaying in bricks and slider option, and also in lightbox.' ),
			'id'          => 'enable_desc',
			'default'     => 'on',
			'type'        => 'radio',
			'options'     => array(
				'off' => a13__be( 'No' ),
				'on'  => a13__be( 'Yes' ),
			),
			'switch'      => true,
		),
		array(
			'name' => 'on',
			'type' => 'switch-group'
		),
		array(
			'name'        => a13__be( 'Color under title' ),
			'description' => a13__be( 'Leave empty to not set any background' ),
			'id'          => 'slide_title_bg_color',
			'default'     => '',
			'type'        => 'color'
		),
		array(
			/*'name' => 'on',*/
			'type' => 'switch-group-end'
		),
		array(
			/*'id' => 'enable_desc',  just for readability */
			'type' => 'end-switch',
		),

		array(
			'name'        => a13__be( 'Present media in:' ),
			'description' => '',
			'id'          => 'theme',
			'default'     => 'scroller',
			'options'     => array(
				'bricks' => a13__be( 'Bricks' ),
				'slider' => a13__be( 'Slider' ),
			),
			'switch'      => true,
			'type'        => 'select',
		),
		array(
			'name' => 'bricks',
			'type' => 'switch-group'
		),
		array(
			'name' =>  a13__be( 'Display content' ),
			'description' => a13__be( 'This will display separate brick with text about album.' ),
			'id' => 'album_content',
			'default' => 'G',
			'global_value'  => 'G',
			'parent_option' => array( 'album', 'album_content' ),
			'options' => array(
				'G' => a13__be( 'Global settings' ),
				'on'    =>  a13__be( 'On' ),
				'off'   =>  a13__be( 'Off' ),
			),
			'type' => 'radio',
		),
		array(
			'name'        => a13__be( 'Size of content brick(width)' ),
			'description' => a13__be( 'How many bricks area should take description of this album.' ),
			'id'          => 'content_brick_ratio_x',
			'default'     => 1,
			'unit'        => '',
			'min'         => 1,
			'max'         => 4,
			'type'        => 'slider'
		),
		array(
			'name'        => a13__be( 'Bricks columns' ),
			'description' => a13__be( 'It only affects wider screen resolutions.' ),
			'id'          => 'brick_columns',
			'default'     => '3',
			'unit'        => '',
			'min'         => 1,
			'max'         => 4,
			'type'        => 'slider',
		),
		array(
			'name'        => a13__be( 'Max width of bricks content.' ),
			'description' => a13__be( 'Depending on actual screen width, available space for bricks might be smaller, but newer greater then this number.' ),
			'id'          => 'bricks_max_width',
			'default'     => '1920px',
			'unit'        => 'px',
			'min'         => 200,
			'max'         => 2500,
			'type'        => 'slider',
		),
		array(
			'name'    => a13__be( 'Brick margin' ),
			'id'      => 'brick_margin',
			'default' => '10px',
			'unit'    => 'px',
			'min'     => 0,
			'max'     => 100,
			'type'    => 'slider',
		),
		array(
			'name'        => a13__be( 'Hover effect' ),
			'description' => a13__be( 'Hover on bricks in albums list.' ),
			'id'          => 'bricks_hover',
			'default'     => 'default',
			'options'     => array(
				'default' => a13__be( 'Default' ),
				'none'    => a13__be( 'None' ),
				'drop'    => a13__be( 'Drop' ),
				'shift'   => a13__be( 'Shift' ),
				'clap'    => a13__be( 'Clap' ),
				'reveal'  => a13__be( 'Reveal' ),
				'classic' => a13__be( 'Classic' ),
				'border'  => a13__be( 'border' ),
				'uncover' => a13__be( 'Uncover' ),
			),
			'type'        => 'select',
		),
		array(
			/*'name' => 'bricks',*/
			'type' => 'switch-group-end'
		),
		array(
			'name' => 'slider',
			'type' => 'switch-group'
		),
		array(
			'name'        => a13__be( 'Fit images' ),
			'description' => a13__be( 'How will images fit area. <strong>Fit when needed</strong> is best for small images, that shouldn\'t be stretched to bigger sizes, only to smaller(to keep them visible).' ),
			'id'          => 'fit_variant',
			'default'     => '0',
			'options'     => array(
				'0' => a13__be( 'Fit always' ),
				'1' => a13__be( 'Fit landscape' ),
				'2' => a13__be( 'Fit portrait' ),
				'3' => a13__be( 'Fit when needed' ),
				'4' => a13__be( 'Cover whole screen' ),
			),
			'type'        => 'select',
		),
		array(
			'name'          => a13__be( 'Autoplay' ),
			'description'   => a13__be( 'If autoplay is on, slider items will start sliding on page load' ),
			'id'            => 'autoplay',
			'default'       => 'G',
			'global_value'  => 'G',
			'parent_option' => array( 'album', 'autoplay' ),
			'options'       => array(
				'G' => a13__be( 'Global settings' ),
				'1' => a13__be( 'Enable' ),
				'0' => a13__be( 'Disable' ),
			),
			'type'          => 'select',
		),
		array(
			'name'          => a13__be( 'Transition type' ),
			'description'   => a13__be( 'Animation between slides.' ),
			'id'            => 'transition',
			'default'       => '-1',
			'global_value'  => '-1',
			'parent_option' => array( 'album', 'transition_type' ),
			'options'       => array(
				'-1' => a13__be( 'Global settings' ),
				'0'  => a13__be( 'None' ),
				'1'  => a13__be( 'Fade' ),
				'2'  => a13__be( 'Carousel' ),
				'3'  => a13__be( 'Zooming' ),
			),
			'type'          => 'select',
			'switch'      => true,
		),
		array(
			'name' => '3',
			'type' => 'switch-group'
		),
		array(
			'name'        => a13__be( 'Scale in %' ),
			'description' => a13__be( 'How big zooming effect will be' ),
			'id'          => 'ken_scale',
			'default'     => 120,
			'unit'        => '%',
			'min'         => 100,
			'max'         => 200,
			'type'        => 'slider'
		),
		array(
			/*'name' => '3',*/
			'type' => 'switch-group-end'
		),
		array(
			/*'id' => 'transition',  just for readability */
			'type' => 'end-switch',
		),
		array(
			'name'        => a13__be( 'Gradient above photos' ),
			'description' => a13__be( 'Good for better readability of slide titles.' ),
			'id'          => 'gradient',
			'default'     => '1',
			'options'     => array(
				'1' => a13__be( 'Enable' ),
				'0' => a13__be( 'Disable' ),
			),
			'type'        => 'radio',
		),
		array(
			'name'    => a13__be( 'Pattern above photos' ),
			'id'      => 'pattern',
			'default' => '0',
			'options' => array(
				'0' => a13__be( 'None' ),
				'1' => a13__be( 'Type 1' ),
				'2' => a13__be( 'Type 2' ),
				'3' => a13__be( 'Type 3' ),
				'4' => a13__be( 'Type 4' ),
				'5' => a13__be( 'Type 5' ),
			),
			'type'    => 'select',
		),
		array(
			'name' =>  a13__be( 'List of Thumbs' ),
			'id' => 'thumbs',
			'default'       => 'G',
			'global_value'  => 'G',
			'parent_option' => array( 'album', 'thumbs' ),
			'options' => array(
				'G' => a13__be( 'Global settings' ),
				'on' =>  a13__be( 'Enable' ),
				'off' =>  a13__be( 'Disable' ),
			),
			'type'          => 'select',
			'switch'    => true,
		),
		array(
			'name' => 'on',
			'type' => 'switch-group'
		),
		array(
			'name' =>  a13__be( 'Show thumbs on page load' ),
			'id' => 'thumbs_on_load',
			'default'       => 'G',
			'global_value'  => 'G',
			'parent_option' => array( 'album', 'thumbs_on_load' ),
			'options' => array(
				'G' => a13__be( 'Global settings' ),
				'on' =>  a13__be( 'Enable' ),
				'off' =>  a13__be( 'Disable' ),
			),
			'type' => 'select',
		),
		array(
			/*'name' => 'on',*/
			'type' => 'switch-group-end'
		),
		array(
			/*'id' => 'thumbs',  just for readability */
			'type' => 'end-switch',
		),
		array(
			/*'name' => 'slider',*/
			'type' => 'switch-group-end'
		),
		array(
			/*'id' => 'theme',  just for readability */
			'type' => 'end-switch',
		),
		array(
			'name'        => a13__be( 'Internet address' ),
			'description' => a13__be( 'If empty it won\'t be displayed.' ),
			'id'          => 'www',
			'default'     => '',
			'placeholder' => 'http://link-to-somewhere.com',
			'type'        => 'text'
		),
		array(
			'name'        => a13__be( 'Custom info 1' ),
			'description' => a13__be( 'If empty it won\'t be displayed. Use pattern <b>Field name: Field value</b>. Colon(:) is most important to get full result.' ),
			'id'          => 'custom_1',
			'default'     => '',
			'placeholder' => 'Label: value',
			'type'        => 'text'
		),
		array(
			'name'        => a13__be( 'Custom info 2' ),
			'description' => a13__be( 'If empty it won\'t be displayed. Use pattern <b>Field name: Field value</b>. Colon(:) is most important to get full result.' ),
			'id'          => 'custom_2',
			'default'     => '',
			'placeholder' => 'Label: value',
			'type'        => 'text'
		),
		array(
			'name'        => a13__be( 'Custom info 3' ),
			'description' => a13__be( 'If empty it won\'t be displayed. Use pattern <b>Field name: Field value</b>. Colon(:) is most important to get full result.' ),
			'id'          => 'custom_3',
			'default'     => '',
			'placeholder' => 'Label: value',
			'type'        => 'text'
		),
		array(
			'name'        => a13__be( 'Custom info 4' ),
			'description' => a13__be( 'If empty it won\'t be displayed. Use pattern <b>Field name: Field value</b>. Colon(:) is most important to get full result.' ),
			'id'          => 'custom_4',
			'default'     => '',
			'placeholder' => 'Label: value',
			'type'        => 'text'
		),
		array(
			'name'        => a13__be( 'Custom info 5' ),
			'description' => a13__be( 'If empty it won\'t be displayed. Use pattern <b>Field name: Field value</b>. Colon(:) is most important to get full result.' ),
			'id'          => 'custom_5',
			'default'     => '',
			'placeholder' => 'Label: value',
			'type'        => 'text'
		),
	);

	return $meta;
}

function apollo13_metaboxes_cpt_images() {
	$meta = array(
		array(
			'name' => '',
			'type' => 'fieldset'
		),
		array(
			'name'        => a13__be( 'Multi upload' ),
			'description' => '',
			'id'          => 'images_n_videos',
			'type'        => 'multi-upload',
			'default'     => '[]', //empty JSON
			'media_type'  => 'image,video', /* 'audio,video' */
		),
		array(
			'name'         => '',
			'type'         => 'fieldset',
			'is_prototype' => true,
			'id'           => 'mu-prototype-image',
		),
		array(
			'name'        => a13__be( 'Link' ),
			'description' => a13__be( 'Alternative link' ),
			'id'          => 'image_link',
			'default'     => '',
			'type'        => 'text',
		),
		array(
			'name'        => a13__be( 'Product this image represents' ),
			'description' => a13__be( 'If you fill this, then on image you will see "add to cart" button.' ),
			'id'          => 'image_product_id',
			'default'     => '',
			'type'        => 'wp_dropdown_products',
		),
		array(
			'name'        => a13__be( 'Color under photo' ),
			'description' => '',
			'id'          => 'image_bg_color',
			'default'     => '',
			'type'        => 'color'
		),
		array(
			'name'        => a13__be( 'RatioX in bricks theme' ),
			'description' => a13__be( 'How many bricks area should take this image.' ),
			'id'          => 'image_ratio_x',
			'default'     => 1,
			'unit'        => '',
			'min'         => 1,
			'max'         => 4,
			'type'        => 'slider'
		),
		array(
			'name'         => '',
			'type'         => 'fieldset',
			'is_prototype' => true,
			'id'           => 'mu-prototype-video',
		),
		array(
			'name'        => a13__be( 'Autoplay video' ),
			'description' => a13__be( 'Works only in slider' ),
			'id'          => 'video_autoplay',
			'default'     => '0',
			'options'     => array(
				'1' => a13__be( 'On' ),
				'0' => a13__be( 'Off' ),
			),
			'type'        => 'radio',
		),
		array(
			'name'        => a13__be( 'RatioX in bricks theme' ),
			'description' => a13__be( 'How many bricks area should take this video.' ),
			'id'          => 'video_ratio_x',
			'default'     => 1,
			'unit'        => '',
			'min'         => 1,
			'max'         => 4,
			'type'        => 'slider'
		),
		array(
			'name'         => '',
			'type'         => 'fieldset',
			'is_prototype' => true,
			'id'           => 'mu-prototype-audio',
		),
		array(
			'name'        => a13__be( 'Autoplay audio' ),
			'description' => '',
			'id'          => 'audio_autoplay',
			'default'     => '0',
			'options'     => array(
				'1' => a13__be( 'On' ),
				'0' => a13__be( 'Off' ),
			),
			'type'        => 'radio',
		),
		array(
			'name'        => a13__be( 'RatioX in bricks theme' ),
			'description' => a13__be( 'How many bricks area should take this audio.' ),
			'id'          => 'audio_ratio_x',
			'default'     => 1,
			'unit'        => '',
			'min'         => 1,
			'max'         => 4,
			'type'        => 'slider'
		),
		array(
			'name'         => '',
			'type'         => 'fieldset',
			'is_prototype' => true,
			'id'           => 'mu-prototype-videolink',
		),
		array(
			'name'        => a13__be( 'Link to video' ),
			'description' => a13__be( 'Insert here link to your  youtube/vimeo video.' ),
			'id'          => 'videolink_link',
			'default'     => '',
			'type'        => 'text',
		),
		array(
			'name'             => a13__be( 'Video Thumb' ),
			'description'      => a13__be( 'Displayed instead of video placeholder in some cases. If none, placeholder will be used(for youtube movies default thumbnail will show).' ),
			'id'               => 'videolink_poster',
			'default'          => '',
			'button_text'      => a13__be( 'Upload Image' ),
			'attachment_field' => 'videolink_attachment_id',
			'type'             => 'upload'
		),
		array(
			'name'        => a13__be( 'Attachment id' ),
			'description' => '',
			'id'          => 'videolink_attachment_id',
			'default'     => '',
			'type'        => 'hidden'
		),
		array(
			'name'        => a13__be( 'Autoplay video' ),
			'description' => a13__be( 'Works only in slider' ),
			'id'          => 'videolink_autoplay',
			'default'     => '0',
			'options'     => array(
				'1' => a13__be( 'On' ),
				'0' => a13__be( 'Off' ),
			),
			'type'        => 'radio',
		),
		array(
			'name'        => a13__be( 'RatioX in bricks theme' ),
			'description' => a13__be( 'How many bricks area should take this video.' ),
			'id'          => 'videolink_ratio_x',
			'default'     => 1,
			'unit'        => '',
			'min'         => 1,
			'max'         => 4,
			'type'        => 'slider'
		),
		array(
			'name'        => a13__be( 'Title' ),
			'description' => '',
			'id'          => 'videolink_title',
			'default'     => '',
			'type'        => 'text'
		),
		array(
			'name'        => a13__be( 'Description' ),
			'description' => '',
			'id'          => 'videolink_desc',
			'default'     => '',
			'type'        => 'textarea',
		),
	);

	return $meta;
}