<?php
/*
* Function that return featured image or video for post
*/
if(!function_exists('a13_get_top_image_video')) {
    function a13_get_top_image_video( $link_it = false, $args = '' ) {
        global $a13_apollo13;

        $html = '';

        $default_args = array(
            'force_image'	=> false,
            'return_src'	=> false,
            'height'        => 0,
        );

        $args = wp_parse_args($args, $default_args);

        $sizes = array(
            'sidebar-size'              => array(100,100),
            'apollo-post-thumb'         => array(800,0),
            'apollo-post-thumb-smaller' => array(740,0), //for 700 and 740 layouts
            'apollo-post-thumb-big'     => array(1080,0),
        );

        if(a13_is_no_property_page()){
            return $html; //empty string
        }

        $page_type = a13_what_page_type_is_it();
        $is_post = $page_type['post'];
        $is_page = $page_type['page'];
        $is_post_list = $page_type['blog_type'];

        //check if media should be displayed
        if(
            ($is_post && $a13_apollo13->get_option('blog', 'post_media') == 'off')
            ||
            ($is_post_list && $a13_apollo13->get_option('blog', 'blog_media') == 'off')
        ){
            return $html; //empty string
        }

        $post_id        = get_the_ID();
        $img_or_vid     = get_post_meta($post_id, '_image_or_video', true);
        $img_or_vid     = strlen($img_or_vid)? $img_or_vid : 'post_image'; //default value for albums, or other pages when displayed on search results

        $image_video    = $a13_apollo13->get_option('blog', 'blog_videos') === 'off' && $is_post_list;

        $thumb_size = 'apollo-post-thumb'; //default for post

        if($is_page || $is_post){
            $layout = $is_page? $a13_apollo13->get_meta('_content_layout', $post_id) : $a13_apollo13->get_option('blog', 'post_content_layout');
            $full_layouts =  array(
                'full_padding',
                'full',
            );
            $small_layouts =  array(
                'left',
                'left_padding',
                'right',
                'right_padding',
            );

            if(in_array($layout, $full_layouts)){
                $thumb_size = 'apollo-post-thumb-big';
            }
            elseif(in_array($layout, $small_layouts)){
                $thumb_size = 'apollo-post-thumb-smaller';
            }
            else{
                if( defined('A13_NO_SIDEBARS') || $a13_apollo13->get_meta( '_widget_area' ) == 'off'){
                    $thumb_size = 'apollo-post-thumb-big';
                }
            }
        }
        elseif($is_post_list){
            $thumb_size = 'apollo-blog';
            $brick_size         = $a13_apollo13->get_meta('_brick_ratio_x', $post_id);
            $columns            = (int)$a13_apollo13->get_option( 'blog', 'brick_columns' );
            $bricks_max_width   = (int)$a13_apollo13->get_option( 'blog', 'bricks_max_width' );
            $brick_margin       = (int)$a13_apollo13->get_option( 'blog', 'brick_margin' );

            /* brick_size can't be bigger then columns for calculations */
            $brick_size         = strlen($brick_size)? min((int)$brick_size, $columns) : 1;
            $ratio              = $brick_size/$columns;

            //many possible sizes, but one RULE to rule them all
            $image_width =  ceil($ratio * $bricks_max_width - (1-$ratio) * $brick_margin);
            $sizes[$thumb_size] = array($image_width, $args['height']);
        }

        if( $args['force_image'] || $img_or_vid === 'post_image' ){

            if($args['return_src']){
                $html = a13_make_post_image($post_id, $sizes[$thumb_size], true);
            }
            else{
                $img = a13_make_post_image($post_id, $sizes[$thumb_size]);

                if( !empty( $img ) ){
                    if($link_it){
                        $img = '<a href="'.esc_url(get_permalink()).'">'.$img.'</a>';
                    }

                    $html = '<div class="item-image post-media">'.$img.'</div>';
                }
            }
        }

        elseif( $img_or_vid === 'post_video' ){
            //featured image instead of video?
            if($image_video){
	            $html = a13_get_top_image_video($link_it, array_merge($args, array('force_image' => true )));
            }
            else{
                $src = get_post_meta($post_id, '_post_video', true);
                if( !empty( $src ) ){
                    $html = '<div class="item-video post-media">';

                    $width = $sizes[$thumb_size][0];
                    $height = $sizes[$thumb_size][1];
                    if( $height == 0){
                        $height = ceil((9/16) * $width);
                    }

                    $media_dimensions = array(
                        'width' => $width,
                        'height' => $height
                    );
                    $v_code = wp_oembed_get($src, $media_dimensions);

                    //if no code, try theme function
                    if($v_code === false){
                        $html .= a13_get_movie($src, $width, $height);
                    }
                    else{
                        $html .= $v_code;
                    }
                    $html .= '</div>';
                }
            }
        }

        return $html;
    }
}
if(!function_exists('a13_top_image_video')){
    function a13_top_image_video($link_it = false, $args = ''){
        echo a13_get_top_image_video($link_it, $args);
    }
}


/*
 * Making featured images
 */
if(!function_exists('a13_make_post_image')){
    function a13_make_post_image( $post_id, $sizes, $only_src = false ){
        if(empty($post_id)){
            $post_id = get_the_ID();
        }
        if ( has_post_thumbnail( $post_id) ) {
            $size = array( $sizes[0], $sizes[1], 'bfi_thumb' => true );

            if($only_src){
                $attachment = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), $size );
                return $attachment[0];
            }
            else{
                return get_the_post_thumbnail( $post_id, $size );
            }
        }

        return false;
    }
}


/*
 * Detection of type of movie
 * returns array(type, video_id)
 */
if(!function_exists('a13_detect_movie')){
    function a13_detect_movie($src){
        //used to check if it is audio file
        $parts = pathinfo($src);
        $ext = isset($parts['extension'])? strtolower($parts['extension']) : false;

        //http://www.youtube.com/watch?v=e8Z0YTWDFXI
        if (preg_match("/(youtube\.com\/watch\?)?v=([a-zA-Z0-9\-_]+)&?/s", $src, $matches)){
            $type = 'youtube';
            $video_id = $matches[2];
        }
        //http://youtu.be/e8Z0YTWDFXI
        elseif (preg_match("/(https?:\/\/youtu\.be\/)([a-zA-Z0-9\-_]+)&?/s", $src, $matches)){
            $type = 'youtube';
            $video_id = $matches[2];
        }
        // regexp $src http://vimeo.com/16998178
        elseif (preg_match("/(vimeo\.com\/)([0-9]+)/s", $src, $matches)){
            $type = 'vimeo';
            $video_id = $matches[2];
        }
        elseif(strlen($ext) && in_array($ext, array('mp3', 'ogg', 'm4a'))){
            $type = 'audio';
            $video_id = $src;
        }
        else{
            $type = 'html5';
            $video_id = $src;
        }

        return array(
            'type' => $type,
            'id' => $video_id
        );
    }
}


/*
 * Returns movie thumb(for youtube, vimeo)
 */
if(!function_exists('a13_get_movie_thumb_src')){
    function a13_get_movie_thumb_src( $video_data, $thumb = '' ){
        if(!empty($thumb)){
            return $thumb;
        }

        $type = $video_data['type'];
        $video_id = $video_data['id'];

        if ( $type == 'youtube' ){
            return 'http://img.youtube.com/vi/'.$video_id.'/hqdefault.jpg';
        }
        elseif ( $type == 'vimeo' ){
            return A13_TPL_GFX . '/holders/vimeo.png';
        }
        elseif ( $type == 'html5' ){
            return A13_TPL_GFX . '/holders/video.png';
        }

        return false;
    }
}


/*
 * Returns movie link to insert it in iframe
 */
if(!function_exists('a13_get_movie_link')){
    function a13_get_movie_link( $video_data ){
        $type       = $video_data['type'];
        $video_id   = $video_data['id'];

        if ( $type == 'youtube' ){
            return 'http://www.youtube.com/embed/'.$video_id.'?enablejsapi=1&amp;controls=1&amp;fs=1&amp;hd=1&amp;rel=0&amp;loop=0&amp;rel=0&amp;showinfo=1&amp;showsearch=0&amp;wmode=transparent';
        }
        elseif ( $type == 'vimeo' ){
            return 'http://player.vimeo.com/video/'.$video_id.'?api=1&amp;title=1&amp;loop=0';
        }
        else{
            return false;
        }
    }
}


/*
 * Returns movie iframe or link to movie
 */
if(!function_exists('a13_get_movie')){
    function a13_get_movie( $src, $width = 295, $height = 0 ){
        if( $height == 0){
            $height = ceil((9/16) * $width);
        }

        $video_data  = a13_detect_movie($src);
        $type       = $video_data['type'];
	    
	    if( $type === 'html5' ){
		    return wp_video_shortcode( array( 'src' =>  $src ) );
	    }
	    else{
	        $link       = a13_get_movie_link($video_data, array( 'width' => $width, 'height' => $height, 'poster' => "" ));

	        return '<iframe data-vid-id="'.$video_data['video_id'].'" id="a13-crazy'.$type . mt_rand() . '" style="height: ' . $height . 'px; width: ' . $width . 'px; border: none;" src="' . esc_url($link) . '" allowfullscreen></iframe>';
	    }
    }
}



// retrieves the attachment ID from the file URL
if(!function_exists('pippin_get_attachment_id')) {
	function pippin_get_attachment_id( $media_url ) {
		global $wpdb;
		$attachment = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE guid='%s';", $media_url ) );

		return (is_array($attachment) && sizeof($attachment))? $attachment[0] : 0;
	}
}



function a13_audio( $attr ) {
	//based on wp_audio_shortcode
	static $instances = 0;
	$instances++;

	$audio = null;
	$post_id = 0;
	$default_types = wp_get_audio_extensions();
	$defaults_atts = array(
		'src'      => '',
		'loop'     => '',
		'autoplay' => '',
		'preload'  => 'none'
	);
	foreach ( $default_types as $type ) {
		$defaults_atts[$type] = '';
	}

	$atts = shortcode_atts( $defaults_atts, $attr );

	$primary = false;
	if ( ! empty( $atts['src'] ) ) {
		$type = wp_check_filetype( $atts['src'], wp_get_mime_types() );
		if ( ! in_array( strtolower( $type['ext'] ), $default_types ) ) {
			return sprintf( '<a class="wp-embedded-audio" href="%s">%s</a>', esc_url( $atts['src'] ), esc_html( $atts['src'] ) );
		}
		$primary = true;
		array_unshift( $default_types, 'src' );
	} else {
		foreach ( $default_types as $ext ) {
			if ( ! empty( $atts[ $ext ] ) ) {
				$type = wp_check_filetype( $atts[ $ext ], wp_get_mime_types() );
				if ( strtolower( $type['ext'] ) === $ext ) {
					$primary = true;
				}
			}
		}
	}

	if ( ! $primary ) {
		$audios = get_attached_media( 'audio', $post_id );
		if ( empty( $audios ) ) {
			return;
		}

		$audio = reset( $audios );
		$atts['src'] = wp_get_attachment_url( $audio->ID );
		if ( empty( $atts['src'] ) ) {
			return;
		}

		array_unshift( $default_types, 'src' );
	}

	wp_enqueue_style( 'mediaelement' );
	wp_enqueue_script( 'mediaelement' );

	$html_atts = array(
		'class'    => '',
		'id'       => sprintf( 'a13-audio-%d-%d', $post_id, $instances ),
		'loop'     => wp_validate_boolean( $atts['loop'] ),
		'autoplay' => wp_validate_boolean( $atts['autoplay'] ),
		'preload'  => $atts['preload'],
		'style'    => 'width: 100%; visibility: hidden;',
	);

	// These ones should just be omitted altogether if they are blank
	foreach ( array( 'loop', 'autoplay', 'preload' ) as $a ) {
		if ( empty( $html_atts[$a] ) ) {
			unset( $html_atts[$a] );
		}
	}

	$attr_strings = array();
	foreach ( $html_atts as $k => $v ) {
		$attr_strings[] = $k . '="' . esc_attr( $v ) . '"';
	}

	$html = sprintf( '<audio %s controls="controls">', join( ' ', $attr_strings ) );

	$fileurl = '';
	$source = '<source type="%s" src="%s" />';
	foreach ( $default_types as $fallback ) {
		if ( ! empty( $atts[ $fallback ] ) ) {
			if ( empty( $fileurl ) ) {
				$fileurl = $atts[ $fallback ];
			}
			$type = wp_check_filetype( $atts[ $fallback ], wp_get_mime_types() );
			$url = esc_url( add_query_arg( '_', $instances, $atts[ $fallback ] ) );
			$html .= sprintf( $source, $type['type'], esc_url( $url ) );
		}
	}

	$html .= wp_mediaelement_fallback( $fileurl );
	$html .= '</audio>';

	return $html;
}

function a13_playlist( $ids ) {
	//based on wp_playlist_shortcode
	static $instance = 0;
	$instance++;

	if ( empty( $ids ) ) {
		return '';
	}

	$args = array(
		'post_status' => 'inherit',
		'post_type' => 'attachment',
		'post_mime_type' => 'audio',
		'order' => 'ASC',
		'orderby' => 'post__in',
		'include' => $ids
	);

	$_attachments = get_posts( $args );
	$attachments = array();
	foreach ( $_attachments as $key => $val ) {
		$attachments[$val->ID] = $_attachments[$key];
	}

	if ( empty( $attachments ) ) {
		return '';
	}

	if ( is_feed() ) {
		$output = "\n";
		foreach ( $attachments as $att_id => $attachment ) {
			$output .= wp_get_attachment_link( $att_id ) . "\n";
		}
		return $output;
	}

	$data = array(
		'type' => 'audio',
		// don't pass strings to JSON, will be truthy in JS
		'tracklist' => false,
		'tracknumbers' => false,
		'images' => false,
		'artists' => false,
	);

	$tracks = array();
	foreach ( $attachments as $attachment ) {
		$url = wp_get_attachment_url( $attachment->ID );
		$ftype = wp_check_filetype( $url, wp_get_mime_types() );
		$track = array(
			'src' => $url,
			'type' => $ftype['type'],
			'title' => $attachment->post_title,
			'caption' => $attachment->post_excerpt,
			'description' => $attachment->post_content
		);

		$track['meta'] = array();
		$meta = wp_get_attachment_metadata( $attachment->ID );
		if ( ! empty( $meta ) ) {

			foreach ( wp_get_attachment_id3_keys( $attachment ) as $key => $label ) {
				if ( ! empty( $meta[ $key ] ) ) {
					$track['meta'][ $key ] = $meta[ $key ];
				}
			}
		}

		$tracks[] = $track;
	}
	$data['tracks'] = $tracks;

	ob_start();

	if ( 1 === $instance ) {
		add_action( 'wp_footer', 'wp_underscore_playlist_templates', 0 );
		add_action( 'admin_footer', 'wp_underscore_playlist_templates', 0 );
	} ?>
<div class="a13-audio-playlist">
	<audio controls="controls" preload="none" style="visibility: hidden"></audio>
	<div class="playlist-next skip-button"></div>
	<div class="playlist-prev skip-button"></div>
	<noscript>
	<ol><?php
	foreach ( $attachments as $att_id => $attachment ) {
		printf( '<li>%s</li>', wp_get_attachment_link( $att_id ) );
	}
	?></ol>
	</noscript>
	<script type="application/json" class="a13-playlist-script"><?php echo wp_json_encode( $data ) ?></script>
</div>
	<?php
	return ob_get_clean();
}

function a13_video( $attr, $dont_load_video_library = false ) {
	global $content_width;
	$post_id = 0;

	static $instances = 0;
	$instances ++;


	$video = null;

	$default_types = wp_get_video_extensions();
	$defaults_atts = array(
		'src'      => '',
		'poster'   => '',
		'loop'     => '',
		'autoplay' => '',
		'preload'  => 'metadata',
		'width'    => 640,
		'height'   => 360,
	);

	foreach ( $default_types as $type ) {
		$defaults_atts[ $type ] = '';
	}

	$atts = shortcode_atts( $defaults_atts, $attr, 'video' );

	// if the video is bigger than the theme
	if ( ! empty( $content_width ) && $atts['width'] > $content_width ) {
		$atts['height'] = round( ( $atts['height'] * $content_width ) / $atts['width'] );
		$atts['width']  = $content_width;
	}

	$yt_pattern = '#^https?://(?:www\.)?(?:youtube\.com/watch|youtu\.be/)#';

	$primary = false;
	if ( ! empty( $atts['src'] ) ) {
		if ( ! preg_match( $yt_pattern, $atts['src'] ) ) {
			$type = wp_check_filetype( $atts['src'], wp_get_mime_types() );
			if ( ! in_array( strtolower( $type['ext'] ), $default_types ) ) {
				return sprintf( '<a class="wp-embedded-video" href="%s">%s</a>', esc_url( $atts['src'] ), esc_html( $atts['src'] ) );
			}
		}
		$primary = true;
		array_unshift( $default_types, 'src' );
	} else {
		foreach ( $default_types as $ext ) {
			if ( ! empty( $atts[ $ext ] ) ) {
				$type = wp_check_filetype( $atts[ $ext ], wp_get_mime_types() );
				if ( strtolower( $type['ext'] ) === $ext ) {
					$primary = true;
				}
			}
		}
	}

	if ( ! $primary ) {
		$videos = get_attached_media( 'video', $post_id );
		if ( empty( $videos ) ) {
			return;
		}

		$video       = reset( $videos );
		$atts['src'] = wp_get_attachment_url( $video->ID );
		if ( empty( $atts['src'] ) ) {
			return;
		}

		array_unshift( $default_types, 'src' );
	}

	if(!$dont_load_video_library){
		wp_enqueue_style( 'wp-mediaelement' );
		wp_enqueue_script( 'wp-mediaelement' );
	}

	$html_atts = array(
		'class'    => '',
		'id'       => sprintf( 'a13-video-%d-%d', $post_id, $instances ),
		'width'    => absint( $atts['width'] ),
		'height'   => absint( $atts['height'] ),
		'poster'   => esc_url( $atts['poster'] ),
		'loop'     => wp_validate_boolean( $atts['loop'] ),
		'autoplay' => wp_validate_boolean( $atts['autoplay'] ),
		'preload'  => $atts['preload'],
	);

	// These ones should just be omitted altogether if they are blank
	foreach ( array( 'poster', 'loop', 'autoplay', 'preload' ) as $a ) {
		if ( empty( $html_atts[ $a ] ) ) {
			unset( $html_atts[ $a ] );
		}
	}

	$attr_strings = array();
	foreach ( $html_atts as $k => $v ) {
		$attr_strings[] = $k . '="' . esc_attr( $v ) . '"';
	}

	$html = sprintf( '<video %s controls="controls">', join( ' ', $attr_strings ) );

	$fileurl = '';
	$source  = '<source type="%s" src="%s" />';
	foreach ( $default_types as $fallback ) {
		if ( ! empty( $atts[ $fallback ] ) ) {
			if ( empty( $fileurl ) ) {
				$fileurl = $atts[ $fallback ];
			}
			if ( 'src' === $fallback && preg_match( $yt_pattern, $atts['src'] ) ) {
				$type = array( 'type' => 'video/youtube' );
			} else {
				$type = wp_check_filetype( $atts[ $fallback ], wp_get_mime_types() );
			}
			$url = esc_url( add_query_arg( '_', $instances, $atts[ $fallback ] ) );
			$html .= sprintf( $source, $type['type'], esc_url( $url ) );
		}
	}

	if ( ! empty( $content ) ) {
		if ( false !== strpos( $content, "\n" ) ) {
			$content = str_replace( array( "\r\n", "\n", "\t" ), '', $content );
		}
		$html .= trim( $content );
	}

	$html .= $dont_load_video_library? '' : wp_mediaelement_fallback( $fileurl );
	$html .= '</video>';

	$output = sprintf( '<div class="wp-video">%s</div>', $html );

	return $output;
}


