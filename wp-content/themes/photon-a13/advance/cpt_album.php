<?php


/*
 * Prepares attachments so they can be used in admin and front end to show all media from album
 */
function a13_prepare_album_attachments( $value, $for_admin = false ){
	$attachments = array();
	if ( ! empty( $value ) ) {
		$images_videos_array = json_decode( $value, true );
		$media_count         = count( $images_videos_array );

		if ( $media_count ) {
			//collect all ids
			//and filter out external media(video links, audio links)
			$ids       = array();
			$externals = array();
			for ( $i = 0; $i < $media_count; $i ++ ) {
				$id = $images_videos_array[ $i ]['id'];
				if ( $id === 'external' ) {
					$externals[] = $images_videos_array[ $i ];
				}
				$ids[] = $images_videos_array[ $i ]['id'];
			}

			//process items from media library
			$args = array(
				'post_type'      => 'attachment',
				'posts_per_page' => -1,
				'post_status'    => 'any',
				'post_parent'    => null,
				'post__in'       => $ids,
				'orderby'        => 'post__in'
			);
			$attachments = get_posts( $args );
			$attachments = array_map( 'wp_prepare_attachment_for_js', $attachments );
			//remove any empty, false elements
			$attachments = array_filter( $attachments );
			wp_reset_postdata();

			//process items from external links
			a13_prepare_external_media( $externals );

			//combine internal and external media back again
			//also check for deleted items
			for ( $i = 0; $i < $media_count; $i ++ ) {
				$id = $images_videos_array[ $i ]['id'];
				//wpml? get proper ID
				if(defined( 'ICL_SITEPRESS_VERSION') && !$for_admin ){
					$id = apply_filters( 'wpml_object_id', $id, 'post', true );
				}

				if ( $id === 'external' ) {
					//first we push around to make space for us
					array_splice( $attachments, $i, 0, 'whatever' );
					//and now we push our thing
					$attachments[ $i ] = array_shift( $externals );
				} elseif ( ! isset( $attachments[ $i ] ) || ( (int) $id !== (int) $attachments[ $i ]['id'] ) ) {
					//there is something wrong, probably media was deleted
					array_splice( $attachments, $i, 0, 'deleted' );
				} else{
					//we push additional info to real attachments
					//These are options from theme
					$type = $images_videos_array[ $i ][ 'type' ];
					if( $type === 'image' ){
						$attachments[ $i ][ 'bg_color' ] = $images_videos_array[ $i ][ 'image_bg_color' ];
						$attachments[ $i ][ 'ratio_x' ] = $images_videos_array[ $i ][ 'image_ratio_x' ];
						$attachments[ $i ][ 'alt_link' ] = $images_videos_array[ $i ][ 'image_link' ];
						$attachments[ $i ][ 'product_id' ] = isset($images_videos_array[ $i ][ 'image_product_id' ])? $images_videos_array[ $i ][ 'image_product_id' ] : '';
					} elseif( $type === 'video' ){
						$attachments[ $i ][ 'autoplay' ] = $images_videos_array[ $i ][ 'video_autoplay' ];
						$attachments[ $i ][ 'ratio_x' ] = $images_videos_array[ $i ][ 'video_ratio_x' ];
					}
				}
			}
		}
	}

	return $attachments;
}


/*
 * Prepares external attachments
 */
function a13_prepare_external_media(&$items){
	$audio_icon = wp_mime_type_icon('audio');
	$video_icon = wp_mime_type_icon('video');

	foreach($items as &$item){
		$type   = $item['type'];
		$mime   = substr($type, 0, -4); //-'link', result in "video" or "audio"
		$title  = $item[$type.'_title'];
		$link   = $item[$type.'_link'];
		$id     = $item[$type.'_attachment_id'];

		//prepare args that will be used to generate gallery HTML
		$item['filename']   = (empty($title)? $link : $title); //title is more favorable
		//CAUTION! overwrite of type here!
		$item['type']       = $mime; //type and subtype are switched kind of in compare to default WP Media library
		$item['subtype']    = $type;
		$item['icon']       = ${$mime.'_icon'};

		//thumb of item
		if(!empty($id)){
			list( $src, $width, $height ) = wp_get_attachment_image_src( $id, 'thumbnail' );
			$item['thumb'] = compact( 'src', 'width', 'height' );
		}
		else{
			$width = 48;
			$height = 64;
			$src = $item['icon'];
			$item['thumb'] = compact( 'src', 'width', 'height' );
		}
	}
	unset($item);
}


/*
 * Prepares admin gallery ready to display
 */
function a13_prepare_admin_gallery_html($attachments){

	ob_start();
	if ( $attachments ) {
		foreach ( $attachments as $item ) {
			if( !is_array($item) && $item === 'deleted' ){
				$file_name = 'File deleted?';
				$item_class = 'attachment-preview image deleted';
				$src = A13_TPL_GFX.'/holders/deleted.png';
				$img_class = 'thumbnail';
			}
			else{
				//thumbnail src
				$src = '';
				if(isset($item['thumb'])){
					$src = $item['thumb']['src'];
				}
				else{
					if(isset($item['sizes']['thumbnail'])){
						$src = $item['sizes']['thumbnail']['url'];
					}
					//image is very small or just don't have thumbnail yet
					else{
						$src = $item['sizes']['full']['url'];
					}
				}

				//classes of item
				$item_class = 'attachment-preview'
				              .' type-'.$item['type']
				              .' subtype-'.$item['subtype']
				              .( isset($item['orientation'])? ' '.$item['orientation'] : '' )
				;

				//icon & filename for no image types
				$img_class = "thumbnail";
				$file_name = false;
				if($item['type'] !== 'image'){
					if( $item['thumb']['src'] === $item['icon'] ){
						$img_class = 'icon';
					}
					$file_name = $item['filename'];
				}
			}

			a13_admin_gallery_item_html($item_class, $img_class, $src, $file_name );
		}
	}
	$output = ob_get_contents();
	ob_end_clean();

	return $output;
}


/*
 * Helper to prepare each album item to display in admin
 */
function a13_admin_gallery_item_html($item_class, $img_class, $src, $file_name = false ){
	?>
	<li class="mu-item attachment">
	<div class="<?php echo esc_attr($item_class); ?>">
		<div class="thumbnail">
			<div class="centered">
				<img class="<?php echo esc_attr($img_class); ?>" src="<?php echo esc_url($src); ?>">
			</div>

			<?php if($file_name !== false): ?>
				<div class="filename">
					<div><?php echo esc_html($file_name); ?></div>
				</div>
			<?php endif; ?>
		</div>
		<span class="mu-item-edit fa fa-pencil" title="<?php a13_be('Edit'); ?>"></span>
		<span class="mu-item-remove fa fa-times" title="<?php a13_be('Remove item'); ?>"></span>
		<div class="mu-item-drag"></div>
	</div>
	</li>
<?php
}


/*
 * For printing categories(taxonomies) of album
 */
if(!function_exists('a13_album_posted_in')){
	function a13_album_posted_in( $separator = '<span>/</span>' ) {
		$term_list = wp_get_post_terms(get_the_ID(), A13_CPT_ALBUM_TAXONOMY, array("fields" => "all"));;
		$count_terms = count( $term_list );
		$html = '';
		$iter = 1;
		if( $count_terms ){
			foreach($term_list as $term) {
				$html .= '<a href="' . esc_url(get_term_link($term)) . '">' . $term->name . '</a>';
				if( $count_terms != $iter ){
					$html .= $separator;
				}
				$iter++;
			}
		}

		return $html;
	}
}


/*
 * Prints internet address and custom fields of album
 */
if(!function_exists('a13_album_meta_data')){
    function a13_album_meta_data(){
        $fields = '';
        for($i = 0; $i < 6; $i++){
	        if( $i === 0 ) {
		        //website link
		        $temp = get_post_meta( get_the_ID(), '_www', true );
		        if ( strlen( $temp ) ) {
			        $temp = __( 'Website', 'photon' ).':'.$temp;
		        }
	        }
	        else{
		        //custom fields
                $temp = get_post_meta(get_the_ID(), '_custom_'.$i, true);
	        }

            if(strlen($temp)){
                $pieces = explode(':', $temp, 2);
                if(sizeof($pieces) == 1){
                    $fields .= '<span>'.make_clickable($temp).'</span>';
                }
                else{
                    $fields .= '<span><em>'.$pieces[0].'</em>'.make_clickable($pieces[1]).'</span>';
                }
            }
        }

        if(strlen($fields)){
            echo ' <div class="meta-data">'.$fields.'</div>';
        }
    }
}


/*
 * Making cover for works in Albums list
 */
if(!function_exists('a13_make_album_image')){
    function a13_make_album_image( $album_id, $sizes = '' ){
        global  $a13_apollo13;

        if(empty($album_id)){
            $album_id = get_the_ID();
        }

        if( !is_array($sizes) ){
            $brick_size         = $a13_apollo13->get_meta('_brick_ratio_x', $album_id);
            $columns            = (int)$a13_apollo13->get_option( 'album', 'brick_columns' );
            $bricks_max_width   = (int)$a13_apollo13->get_option( 'album', 'bricks_max_width' );
            $brick_margin       = (int)$a13_apollo13->get_option( 'album', 'brick_margin' );

            /* brick_size can't be bigger then columns for calculations */
            $brick_size         = strlen($brick_size)? min((int)$brick_size, $columns) : 1;
            $ratio              = $brick_size/$columns;

            //many possible sizes, but one RULE to rule them all
            $image_width =  ceil($ratio * $bricks_max_width - (1-$ratio) * $brick_margin);
            $sizes = array($image_width, 0);
        }


        $src = a13_make_post_image( $album_id, $sizes, true );
        if ( $src === false ) {
            $src = A13_TPL_GFX . '/holders/photo.png';
        }

	    $image_alt = '';
	    $image_title = '';
	    $image_id = get_post_thumbnail_id( $album_id );
	    if($image_id){
		    $image_alt = get_post_meta( $image_id, '_wp_attachment_image_alt', true);
		    $image_title = get_the_title( $image_id );
	    }

	    return '<img src="'.esc_url($src).'" alt="'.esc_attr($image_alt).'"'.($image_title? ' title="'.esc_attr($image_title).'"' : '').' />';
    }
}


/*
 * Making images in bricks album
 */
if(!function_exists('a13_make_in_album_thumb')){
    function a13_make_in_album_thumb( $attr, $columns, $bricks_max_width, $brick_margin  ){
        global  $a13_apollo13;


        /* brick_size can't be bigger then columns for calculations */
        $brick_size         = $attr[ 'ratio_x' ];
        $brick_size         = strlen($brick_size)? min((int)$brick_size, $columns) : 1;
        $ratio              = $brick_size/$columns;

        //many possible sizes, but one RULE to rule them all
        $image_width = ceil($ratio * $bricks_max_width - (1-$ratio) * $brick_margin);
        $image_height = 0;
		$size = array( $image_width, $image_height, 'bfi_thumb' => true );

	    $id     = $attr[ 'id' ];
	    $thumb  = $attr[ 'thumb' ];
	    $type   = $attr[ 'type' ];
	    $src    = false;


	    //look out for thumb

	    //media from media library
	    if( $attr[ 'attachment_type' ] === 'internal' ){
			//for images we get attachment image
			if( $type === 'image' ){
				$attachment = wp_get_attachment_image_src( $id, $size );

				//only if we have attachment
				if( is_array($attachment) ){
					$src = $attachment[0];
				}
			}
			//for video/audio from media library we have to get image same way like for regular post
			elseif( $type === 'video' || $type === 'audio' ){
				$attachment = wp_get_attachment_image_src( get_post_thumbnail_id( $id ), $size );

				//only if we have attachment
				if( is_array($attachment) ){
					$src = $attachment[0];
				}
				else{
					$src = A13_TPL_GFX . '/holders/video.png';
				}
			}
	    }

	    //external media
	    else{
		    if( $type === 'video' ){
			    $attachment = '';

			    if( strlen($id) ) {
				    $attachment = wp_get_attachment_image_src( $id, $size );
			    }

			    //only if we have attachment
			    if( is_array($attachment) ){
				    $src = $attachment[0];
			    }

			    $src = a13_get_movie_thumb_src( array( 'type' => $attr[ 'video_type'], 'id' => $attr[ 'video_id'] ), $src );
		    }
	    }

	    //everything failed?
        if ( $src === false ) {
            $src = A13_TPL_GFX . '/holders/photo.png';
        }

        return $src;
    }
}


function a13_get_album_item_images($item, $collector, $columns, $bricks_max_width, $brick_margin){
	$poster = $brick = $thumb = $src = '';
	$type           = $item['type'];
	$is_external    = $collector['attachment_type'] === 'external';

	//prepare vars
	if( $is_external ){
		//video link
		$attachment_id  = $item['videolink_attachment_id'];
		$image          = $item['videolink_poster'];
	}
	//internal
	else{
		if( $type === 'image' ){
			$attachment_id  = $item['id'];
			$image          = $item['url'];
		}
		//video
		else{
			$attachment_id  = get_post_thumbnail_id( $item['id'] );
			$image          = '';
		}
	}


	/* POSTER */
	//try getting attachment
	if( $attachment_id ){
		$attachment = wp_get_attachment_image_src( $attachment_id, 'full' );

		//only if we have attachment
		if( is_array($attachment) ){
			$src = $attachment[0];
		}
	}
	//attachment failed
	if(!strlen($src)){
		if(strlen($image)){
			$poster = $image;
		}
		//$image failed
		else{
			if( $is_external ){
				if( $collector[ 'video_type' ] === 'youtube' ){
					$poster = 'http://img.youtube.com/vi/'.$collector[ 'video_id' ].'/maxresdefault.jpg';
				}
				//vimeo
				elseif( $collector[ 'video_type' ] === 'vimeo' ){
					$poster = A13_TPL_GFX . '/holders/vimeo.png';
				}
				//something else?
				else{
					$poster = A13_TPL_GFX . '/holders/video.png';
				}
			}
			else{
				if( $type === 'image'){
					$poster = A13_TPL_GFX . '/holders/photo.png';
				}
				//video
				else{
					$poster = A13_TPL_GFX . '/holders/video.png';
				}
			}

		}
	}
	//use attachment
	else{
		$poster = $src;
	}


	/* THUMB */
	//try getting attachment
	if( $attachment_id ){
		$attachment = wp_get_attachment_image_src( $attachment_id, 'thumbnail' );

		//only if we have attachment
		if( is_array($attachment) ){
			$src = $attachment[0];
		}
	}
	//attachment failed
	if(!strlen($src)){
		if(strlen($image)){
			$thumb = $image;
		}
		//$image failed
		else{
			if( $is_external ){
				if( $collector[ 'video_type' ] === 'youtube' ){
					$thumb = 'http://img.youtube.com/vi/'.$collector[ 'video_id' ].'/default.jpg';
				}
				//vimeo
				elseif( $collector[ 'video_type' ] === 'vimeo' ){
					$thumb = A13_TPL_GFX . '/holders/vimeo_150x100.png';
				}
				//something else?
				else{
					$thumb = A13_TPL_GFX . '/holders/video_150x100.png';
				}
			}
			else{
				if( $type === 'image'){
					$thumb = A13_TPL_GFX . '/holders/photo_150x100.png';
				}
				//video
				else{
					$thumb = A13_TPL_GFX . '/holders/video_150x100.png';
				}
			}
		}
	}
	//use attachment
	else{
		$thumb = $src;
	}

	//reset $src
	$src = '';


	/* BRICK */
	/* brick_size can't be bigger then columns for calculations */
	$brick_size         = $collector['ratio_x'];
	$brick_size         = strlen($brick_size)? min((int)$brick_size, $columns) : 1;
	$ratio              = $brick_size/$columns;

	//many possible sizes, but one RULE to rule them all
	$image_width = ceil($ratio * $bricks_max_width - (1-$ratio) * $brick_margin);
	$image_height = 0;
	$size = array( $image_width, $image_height, 'bfi_thumb' => true );
	
	//try getting attachment
	if( $attachment_id ){
		$attachment = wp_get_attachment_image_src( $attachment_id, $size );

		//only if we have attachment
		if( is_array($attachment) ){
			$src = $attachment[0];
		}
	}
	//attachment failed
	if(!strlen($src)){
		if(strlen($image)){
			$brick = $image;
		}
		//$image failed
		else{
			if( $is_external ){
				if( $collector[ 'video_type' ] === 'youtube' ){
					$brick = 'http://img.youtube.com/vi/'.$collector[ 'video_id' ].'/hqdefault.jpg';
				}
				//vimeo
				elseif( $collector[ 'video_type' ] === 'vimeo' ){
					$brick = A13_TPL_GFX . '/holders/vimeo_640x360.png';
				}
				//something else?
				else{
					$brick = A13_TPL_GFX . '/holders/video_640x360.png';
				}
			}
			else{
				if( $type === 'image'){
					$brick = A13_TPL_GFX . '/holders/photo.png';
				}
				//video
				else{
					$brick = A13_TPL_GFX . '/holders/video_640x360.png';
				}
			}
		}
	}
	//use attachment
	else{
		$brick = $src;
	}

	//reset $src
	$src = '';


	return array($poster, $brick, $thumb);
}


/*
 * Making thumb for lightbox
 */
if(!function_exists('a13_make_lightbox_album_thumb')){
    function a13_make_lightbox_album_thumb( $attr, $current_thumb ){
        global  $a13_apollo13;

	    //thumbnail src
	    $src = '';
	    if(isset($attr['thumb'])){
		    $src = $attr['thumb']['src'];
	    }
	    else{
		    if(isset($attr['sizes']['thumbnail'])){
			    $src = $attr['sizes']['thumbnail']['url'];
		    }
		    //image is very small or just don't have thumbnail yet
		    else{
			    $src = $attr['sizes']['full']['url'];
		    }
	    }

	    if( $src === $attr['icon'] ){
		    $src = $current_thumb;
	    }

        return $src;
    }
}


/*
 * Collection of album items. Used in single album, to print all media used in album
 * JS can feed on it to create custom layouts
 */
if(!function_exists('a13_make_media_collection')){
    function a13_make_media_collection(){
	    $id = get_the_ID();
	    $value = get_post_meta( $id, '_images_n_videos' , true);
	    $order = get_post_meta( $id, '_order', true);
	    ?>
	    <ul id="album-media-collection" class="screen-reader-text">
			<?php echo a13_prepare_frontend_gallery_html( a13_prepare_album_attachments( $value ), $order, $id ); ?>
		</ul>
	    <?php
    }
}


/*
 * Prepares front-end gallery ready to display
 */
function a13_prepare_frontend_gallery_html( $attachments, $order, $id ){
	if( $order === 'DESC' ){
		$attachments = array_reverse( $attachments );
	}
	elseif( $order === 'random' ){
		shuffle( $attachments );
	}

	ob_start();
	if ( $attachments ) {
		$columns            = get_post_meta( $id, '_brick_columns', true);
		$bricks_max_width   = get_post_meta( $id, '_bricks_max_width', true);
		$brick_margin       = get_post_meta( $id, '_brick_margin', true);
		$show_desc          = get_post_meta( $id, '_enable_desc', true) === 'on';

		foreach ( $attachments as $item ) {
			//skip deleted items
			if( !is_array($item) && $item === 'deleted' ){
				continue;
			}
			//audio type currently not fully supported, so we skip
			elseif( is_array($item) && $item['type'] === 'audio' ){
				continue;
			}
			else{
				$type           = $item['type'];
				$is_link        = false;

				$collector['type']  = $item[ 'type' ];
/*
 * full_image -> main-image
 * thumb -> bricks
 * lbthumb -> thumb
 * */
				// external video like YT, Vimeo
				if ( $item['id'] === 'external' ) {
					$collector[ 'attachment_type' ]= 'external';
					$collector[ 'id' ]             = $item['videolink_attachment_id'];
					$collector[ 'src' ]            = $item['videolink_link'];           //link to video that will open normaly in browser
					$collector[ 'title' ]          = $item['videolink_title'];          //easy
					$collector[ 'description' ]    = $item['videolink_desc'];           //easy
					$collector[ 'ratio_x' ]        = $item['videolink_ratio_x'];        //for bricks theme
					$collector[ 'autoplay' ]       = $item['videolink_autoplay'];       //easy

					//video details
					$temp =  a13_detect_movie( $item['videolink_link'] );
					$collector[ 'video_type' ]     = $temp[ 'type' ];                   //vimeo/youtube
					$collector[ 'video_id' ]       = $temp[ 'id' ];                     //id of movie. Number of vimeo and alpha-num string for YouTube
					$collector[ 'video_player' ]   = a13_get_movie_link( $temp );     //iframe address

					//prepare images
					list(
						$collector[ 'main-image' ],
						$collector[ 'brick_image' ],
						$collector[ 'thumb' ]
					) = a13_get_album_item_images($item, $collector, $columns, $bricks_max_width, $brick_margin);
				}

				//from media library
				else{
					$collector[ 'attachment_type' ]= 'internal';
					$collector[ 'id' ]             = $item['id']; //attachment id
					$collector[ 'src' ]            = $item['url'];
					$collector[ 'title' ]          = $item['title'];
					$collector[ 'description' ]    = $item['description'];
					$collector[ 'ratio_x' ]        = $item['ratio_x'];

					//get type sensitive values
					if( $type === 'image' ){
						$collector['bg_color']   = $item['bg_color'];
						$collector['product_id'] = $item['product_id'];
						$collector['alt_attr']   = esc_attr( get_post_meta( $item['id'], '_wp_attachment_image_alt', true ) );


						//prepare images
						list(
							$collector[ 'main-image' ],
							$collector[ 'brick_image' ],
							$collector[ 'thumb' ]
							) = a13_get_album_item_images($item, $collector, $columns, $bricks_max_width, $brick_margin);

						//if there is alternative link
						if( strlen( $item['alt_link'] ) ){
							$collector[ 'src' ]     = $item['alt_link'];
							$is_link                = true;
						}

					}
					elseif( $type === 'video' ){
						$collector[ 'autoplay' ] = $item['autoplay'];
						$collector[ 'video_type' ]     = 'html5';
						$collector[ 'video_id' ]       = $collector[ 'src' ];
						$collector[ 'video_player' ]   = $collector[ 'src' ];

						//prepare images
						list(
							$collector[ 'main-image' ],
							$collector[ 'brick_image' ],
							$collector[ 'thumb' ]
							) = a13_get_album_item_images($item, $collector, $columns, $bricks_max_width, $brick_margin);
					}
				}

//				$collector[ 'thumb' ]   = a13_make_in_album_thumb( $collector, $columns, $bricks_max_width, $brick_margin );
//				$collector[ 'lbthumb' ] = a13_make_lightbox_album_thumb( $item, $collector[ 'thumb' ] );

				//classes of item
				$collector[ 'item_class' ] = 'album-item'
	              .' type-'.$type
	              .' subtype-'.$item['subtype']
	              .($is_link? ' link' : '')
				;

			}

			a13_frontend_gallery_item_html( $collector, $show_desc );
		}
	}

	$output = ob_get_contents();
	ob_end_clean();

	return $output;
}


/*
 * Helper to prepare each album item to display in front-end
 */
function a13_frontend_gallery_item_html( $collector, $show_desc ){
	global $a13_apollo13;
	static $id = 0;
	$id++;
	$not_needed = array( 'src', 'title', 'description', 'id', 'attachment_type', 'item_class', 'type', 'video_id' );
	//no need for some attributes in case of html
	if( $collector[ 'type' ] === 'image' ){
		$not_needed[] = 'autoplay';
	}

	$data_attr_list = array_diff_key($collector, array_flip($not_needed) );

	//construct data attributes
	$data_attr = '';
	foreach( $data_attr_list as $attr => $val ){
		$data_attr .= ' data-'.$attr.'="'.esc_attr($val).'"';
	}
	?>
	<li class="<?php echo esc_attr($collector[ 'item_class' ]); ?>"<?php echo $data_attr; ?>>
		<a href="<?php echo esc_url($collector[ 'src' ]); ?>"><?php echo esc_html($collector[ 'title' ]); ?></a>
		<?php

		if( $show_desc ){ ?>
			<div id="album-desc-<?php echo esc_attr($id); ?>" class="album-desc">
				<?php echo $collector[ 'description' ]; ?>
			</div>
		<?php
		}

		//print internal video so lightbox can use it
		if( $collector[ 'attachment_type' ] === 'internal' && $collector[ 'type' ] === 'video' ){
			$video_attr = array(
				'src'      => $collector[ 'src' ],
				'loop'     => false,
				//we don't use it now, but good to know how easily do it
				//'autoplay' => (bool)$collector[ 'autoplay' ],
				'poster'   => $collector[ 'main-image' ],
				'width'    => 480,//ratio 16:9
				'height'   => 270
			);
			echo '<div class="album-video" id="album-video-'.$id.'">'.a13_video( $video_attr ).'</div>';
		}

		if ( function_exists( 'ADDTOANY_SHARE_SAVE_KIT' ) ) {
			//check if social icons are enabled
			$sharing_enabled =  get_post_meta( get_the_ID(), 'sharing_disabled', true) !== '1';
			if ( $a13_apollo13->get_option( 'album', 'album_social_icons' ) === 'on' && $sharing_enabled ){
				$back_to_album = $a13_apollo13->get_option( 'album', 'album_bricks_share_type' ) === 'album';
				//share link will link to album and open image in lightbox
				if($back_to_album){
					$album_url = a13_current_url();
					$link_url = add_query_arg( 'gallery_item', basename( $collector['src'] ), $album_url );
					ADDTOANY_SHARE_SAVE_KIT( array( 'linkname' => $collector['title'], 'linkurl' => $link_url ) );
				}
				//share link will link to attachment page
				else{
					ADDTOANY_SHARE_SAVE_KIT( array( 'linkname' => $collector['title'], 'linkurl' => get_attachment_link($collector['id']) ) );
				}
			}
		}

		//add to cart button
		if( a13_is_woocommerce_activated() && isset( $collector['product_id'] ) && $collector['product_id']){
			echo WC_Shortcodes::product_add_to_cart(array('id'=> $collector[ 'product_id' ], 'style' => ''));
		}
		?>
	</li>
<?php
}



/*
 * Prepares CSS specially for each album
 */
if(!function_exists('a13_album_individual_look')){
	function a13_album_individual_look(){
		global $a13_apollo13;

		//checks if page can have meta fields
		if(!a13_is_no_property_page()){
			$css = '';
			$page_type = a13_what_page_type_is_it();
			$album = $page_type['album'];
			
			if($album){
				$id = get_the_ID();
				$bricks_max_width   = 'max-width:'.get_post_meta( $id, '_bricks_max_width', true).';';
				$brick_margin       = get_post_meta( $id, '_brick_margin', true);
			

			$css .= '
.single-album .bricks-frame{
	'.$bricks_max_width.'
}
#only-album-items-here{
	margin-right: -'.$brick_margin.';
}

/* 4 columns */
.bricks-columns-4 .album-content,
.single-album .bricks-columns-4 .archive-item,
.single-album .bricks-columns-4 .grid-master{
	width: -webkit-calc(25% - '.$brick_margin.');
	width: calc(25% - '.$brick_margin.');
}
.bricks-columns-4 .album-content.w2,
.single-album .bricks-columns-4 .archive-item.w2{
	width: -webkit-calc(50% - '.$brick_margin.');
	width: calc(50% - '.$brick_margin.');
}
.bricks-columns-4 .album-content.w3,
.single-album .bricks-columns-4 .archive-item.w3{
	width: -webkit-calc(75% - '.$brick_margin.');
	width: calc(75% - '.$brick_margin.');
}

/* 3 columns */
.bricks-columns-3 .album-content,
.single-album .bricks-columns-3 .archive-item,
.single-album .bricks-columns-3 .grid-master{
	width: -webkit-calc(33.3% - '.$brick_margin.');
	width: calc(33.3% - '.$brick_margin.');
}
.bricks-columns-3 .album-content.w2,
.single-album .bricks-columns-3 .archive-item.w2{
	width: -webkit-calc(66.6% - '.$brick_margin.');
	width: calc(66.6% - '.$brick_margin.');
}

/* 2 columns */
.bricks-columns-2 .album-content,
.single-album .bricks-columns-2 .archive-item,
.single-album .bricks-columns-2 .grid-master{
	width: -webkit-calc(50% - '.$brick_margin.');
	width: calc(50% - '.$brick_margin.');
}

/* 100% width content */
.bricks-columns-1 .album-content,
.bricks-columns-2 .album-content.w2,
.bricks-columns-2 .album-content.w3,
.bricks-columns-2 .album-content.w4,
.bricks-columns-3 .album-content.w3,
.bricks-columns-3 .album-content.w4,
.bricks-columns-4 .album-content.w4,
/* 100% width bricks */
.single-album .bricks-columns-1 .archive-item,
.single-album .bricks-columns-2 .archive-item.w2,
.single-album .bricks-columns-2 .archive-item.w3,
.single-album .bricks-columns-2 .archive-item.w4,
.single-album .bricks-columns-3 .archive-item.w3,
.single-album .bricks-columns-3 .archive-item.w4,
.single-album .bricks-columns-4 .archive-item.w4{
	width: -webkit-calc(100% - '.$brick_margin.');
	width: calc(100% - '.$brick_margin.');
}

/* responsive rules */
@media only screen and (max-width: 1350px) {
	/* 3 columns */
	.bricks-columns-4 .album-content,
	.single-album .bricks-columns-4 .archive-item,
	.single-album .bricks-columns-4 .grid-master{
		width: -webkit-calc(33.3% - '.$brick_margin.');
		width:         calc(33.3% - '.$brick_margin.');
	}
	.bricks-columns-4 .album-content.w2,
	.single-album .bricks-columns-4 .archive-item.w2{
		width: -webkit-calc(66.6% - '.$brick_margin.');
		width:         calc(66.6% - '.$brick_margin.');
	}
	.bricks-columns-4 .album-content.w3,
	.single-album .bricks-columns-4 .archive-item.w3{
		width: -webkit-calc(100% - '.$brick_margin.');
		width:         calc(100% - '.$brick_margin.');
	}
}
@media only screen and (max-width: 1024px) {
	/* 2 columns */
	.bricks-columns-4 .album-content,
	.bricks-columns-4 .album-content.w2,
	.bricks-columns-3 .album-content,
	.single-album .bricks-columns-4 .archive-item,
	.single-album .bricks-columns-4 .grid-master,
	.single-album .bricks-columns-4 .archive-item.w2,
	.single-album .bricks-columns-3 .archive-item,
	.single-album .bricks-columns-3 .grid-master{
		width: -webkit-calc(50% - '.$brick_margin.');
		width:         calc(50% - '.$brick_margin.');
	}
	.bricks-columns-4 .album-content.w3,
	.bricks-columns-3 .album-content.w2,
	.single-album .bricks-columns-4 .archive-item.w3,
	.single-album .bricks-columns-3 .archive-item.w2{
		width: -webkit-calc(100% - '.$brick_margin.');
		width:         calc(100% - '.$brick_margin.');
	}
}
@media only screen and (max-width: 768px) {
	/* 1 column */
	.bricks-frame .album-content{
		width: 100% !important; /* we unify all possible options of bricks width  for content */
	}
}
@media only screen and (max-width: 600px) {
	/* 1 column */
	.single-album .bricks-frame .archive-item{
		width: 100% !important; /* we unify all possible options of bricks width */
	}
}
';
			}

			//if we have some CSS then add it
			if(strlen($css)){
				wp_add_inline_style( 'user-css', $css );
			}
		}
	}
}
add_action( 'wp_enqueue_scripts', 'a13_album_individual_look', 28 );