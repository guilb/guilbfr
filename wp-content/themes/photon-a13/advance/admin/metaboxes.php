<?php

/*
 * Metaboxes in different post types
 */
function a13_admin_meta_boxes(){
    add_meta_box(
        'apollo13_theme_options',
         a13__be( 'Blog post details' ),
        'a13_meta_main_opts',
        'post',
        'normal',
        'default',
        array('func' => 'apollo13_metaboxes_post')//callback
    );
    add_meta_box(
        'apollo13_theme_options_layout',
         a13__be( 'Post layout' ),
        'a13_meta_main_opts',
        'post',
        'normal',
        'default',
        array('func' => 'apollo13_metaboxes_post_layout')//callback
    );
    add_meta_box(
        'apollo13_theme_options',
         a13__be( 'Page details' ),
        'a13_meta_main_opts',
        'page',
        'normal',
        'default',
        array('func' => 'apollo13_metaboxes_page')//callback
    );
    add_meta_box(
        'apollo13_theme_options_layout',
         a13__be( 'Page layout' ),
        'a13_meta_main_opts',
        'page',
        'normal',
        'default',
        array('func' => 'apollo13_metaboxes_page_layout')//callback
    );
    add_meta_box(
        'apollo13_theme_options_2',
         a13__be( 'Album list options' ),
        'a13_meta_main_opts',
        A13_CUSTOM_POST_TYPE_ALBUM,
        'normal',
        'default',
        array('func' => 'apollo13_metaboxes_album_list')//callback
    );
    add_meta_box(
        'apollo13_theme_options',
         a13__be( 'Album details' ),
        'a13_meta_main_opts',
        A13_CUSTOM_POST_TYPE_ALBUM,
        'normal',
        'default',
        array('func' => 'apollo13_metaboxes_album')//callback
    );
    add_meta_box(
        'apollo13_theme_options_1',
         a13__be( 'Album media - Add images/videos' ),
        'a13_meta_main_opts',
        A13_CUSTOM_POST_TYPE_ALBUM,
        'normal',
        'high',
        array('func' => 'apollo13_metaboxes_cpt_images')//callback
    );
}


/*
 * Generates inputs in metaboxes
 */
function a13_meta_main_opts( $post, $metabox ){

    // Use nonce for verification
    wp_nonce_field( 'apollo13_customization' , 'apollo13_noncename' );

    $a13_prefix = A13_INPUT_PREFIX;

    require_once (A13_TPL_ADV_DIR . '/meta.php');
    $metaboxes = $metabox['args']['func']();

    $fieldset_open = false;

    echo '<div class="apollo13-settings apollo13-metas">';

    foreach( $metaboxes as &$meta ){
        //ASSIGNING VALUE
        $value = '';
        if ( isset( $meta['id'] ) ){
	        //get value
            $value = get_post_meta($post->ID, '_'.$meta['id'] , true);

            //use default if no value
            if( !strlen($value) ){
                $value = ( isset( $meta['default'] )? $meta['default'] : '' );
            }
        }

        $params = array(
            'style' => '',
            'value' => $value
        );

        /*
        * print tag according to type
        */

        if ( $meta['type'] === 'fieldset' ) {
            if ( $fieldset_open ) {
                a13_close_meta_fieldset();
            }

            $class = 'fieldset static';
	        if( isset( $meta['is_prototype'] ) ){
                $class .= ' prototype';
	        }

	        $id = '';
	        if( isset($meta['id'] ) ){
		        $id = ' id="'.$meta['id'].'"';
	        }

            echo '<div class="'.$class.'"'.$id.'>';
            $fieldset_open = true;
        }

        //checks for all normal options
        elseif( a13_print_form_controls($meta, $params, true ) ){
            continue;
        }

        /***********************************************
         * SPECIAL field types
         ************************************************/
        elseif ( $meta['type'] === 'multi-upload' ) {
	        $media_type = '';
	        if ( isset( $meta['media_type'] ) && strlen( $meta['media_type'] ) ) {
		        $media_type = ' data-media-type="' . esc_attr($meta['media_type']) . '"';
	        }
	        ?>


	        <div class="a13-mu-container">
		        <input id="a13-multi-upload" class="button button-large button-primary" type="button" value="<?php echo esc_attr( a13__be( 'Select/Upload images and videos' ) ); ?>" <?php echo $media_type; ?> />
		        <span class="button button-large add-link-media"><?php a13_be( 'Add Video from Youtube/Vimeo' ); ?></span>
		        <label class="button button-large"><input type="checkbox" id="mu-prepend" value="1" /><?php a13_be( 'Add items at beginning of list' ); ?>
		        </label>
		        <input id="a13-multi-remove" class="button button-large" type="button" value="<?php echo esc_attr( a13__be( 'Remove selected' ) ); ?>" disabled="disabled" />

		        <div class="input-tip">
			        <span class="hover">?</span>

			        <p class="tip"><?php a13_be( 'To mark more items in Media Library and in below list, you can use <code>Ctrl</code>(<code>Cmd</code>) or <code>Shift</code> key while selecting them with mouse.' ); ?></p>
		        </div>
		        <div id="a13-mu-notice"></div>
	        </div>


	        <?php
	        //hidden textarea with JSON of all images
	        echo '<textarea id="' . $a13_prefix . $meta['id'] . '" name="' . $a13_prefix . $meta['id'] . '">' . $value . '</textarea>';
	        //prototype of single linked item
	        echo '<div id="mu-single-item" class="fieldset prototype">'; //hide item
	        a13_admin_gallery_item_html( 'attachment-preview image', 'thumbnail', A13_TPL_GFX . '/holders/video_150x100.png' );
	        echo '</div>';
	        ?>
			<ul id="mu-media" class="media-frame-content" data-columns="5">
				<?php echo a13_prepare_admin_gallery_html( a13_prepare_album_attachments( $value, true ) ); ?>
			</ul><?php
        }
    } //end foreach

    unset($meta);// be safe, don't loose your hair :-)

    //close fieldset
    if ( $fieldset_open ) {
        a13_close_meta_fieldset();
    }

    echo '</div>';//.apollo13-settings .apollo13-metas
}


function a13_close_meta_fieldset(){
    echo '</div>';
}


/*
 * Saving metas in post
 */
function a13_save_post($post_id){
    static $done = 0;
    $done++;
    if( $done > 1 ){
        return;//no double saving same things
    }

    $a13_prefix = A13_INPUT_PREFIX;

    // verify if this is an auto save routine.
    // If it is our form has not been submitted, so we dont want to do anything
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
        return;

    // verify this came from the our screen and with proper authorization,
    // because save_post can be triggered at other times
    if( ! isset( $_POST['apollo13_noncename'] ) )
        return;

    if ( !wp_verify_nonce( $_POST['apollo13_noncename'], 'apollo13_customization' ) )
        return;

	//lets get all fields that need to be saved
    require_once (A13_TPL_ADV_DIR . '/meta.php');

    $metaboxes = array();

    switch( $_POST['post_type'] ){
        case 'post':
            $metaboxes = array_merge(apollo13_metaboxes_post(), apollo13_metaboxes_post_layout());
            break;
        case 'page':
            $metaboxes = array_merge(apollo13_metaboxes_page(), apollo13_metaboxes_page_layout());
            break;
        case A13_CUSTOM_POST_TYPE_ALBUM:
            $metaboxes = array_merge( apollo13_metaboxes_album(), apollo13_metaboxes_album_list(), apollo13_metaboxes_cpt_images() );
            break;
    }

    //saving meta
	$is_prototype = false;
    foreach( $metaboxes as &$meta ){
	    //check is it prototype
	    if ( $meta['type'] === 'fieldset' ) {
		    if( isset( $meta['is_prototype'] ) ){
			    $is_prototype = true;
		    }
		    else{
			    $is_prototype = false;
		    }
		    continue;
	    }

        //don't save fields of prototype
        if($is_prototype){
            continue;
        }


        if( isset( $meta['id'] ) && isset( $_POST[ $a13_prefix.$meta['id'] ] ) ){
            $val = $_POST[ $a13_prefix.$meta['id'] ];
            update_post_meta( $post_id, '_'.$meta['id'] , $val );
        }
    }
}



add_action( 'add_meta_boxes', 'a13_admin_meta_boxes');
//Do something with the data entered
add_action( 'save_post', 'a13_save_post' );

