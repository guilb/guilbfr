<?php

/*
 * Scripts and styles added in admin area
 */
if(!function_exists('a13_admin_head')){
    function a13_admin_head(){
        // color picker
        wp_register_script('jquery-wheelcolorpicker', A13_TPL_JS . '/jquery-wheelcolorpicker/jquery.wheelcolorpicker.js', array('jquery'), '2.0.3' );

        //main admin scripts
        wp_register_script('apollo13-admin', A13_TPL_JS . '/admin-script.js',
            array(
                'jquery',   //dom operation
	            'jquery-wheelcolorpicker', //color picker
                'jquery-ui-slider', //slider for font-size setting
                'jquery-ui-sortable' //sortable meta
            ),
            A13_THEME_VER
        );

	    //color picker with rgba for customizer
	    wp_register_script( 'a13-alphacolor-admin' ,A13_TPL_JS . '/alphacolor-admin.js', array( 'jquery','wp-color-picker' ), NULL, true );
	    wp_register_style( 'a13-alphacolor-admin', A13_TPL_CSS . '/alphacolor-admin.css', array( 'wp-color-picker' ), NULL, 'all' );

        wp_enqueue_script('apollo13-admin');


        //styles for uploading window
        wp_enqueue_style('thickbox');

        //some styling for admin options
	    wp_enqueue_style( 'font-awesome', A13_TPL_CSS.'/font-awesome.min.css', false, '4.6.3');
        wp_enqueue_style( 'jquery-wheelcolorpicker', A13_TPL_JS . '/jquery-wheelcolorpicker/css/wheelcolorpicker.css', false, A13_THEME_VER, 'all' );
        wp_enqueue_style( 'apollo-jquery-ui', A13_TPL_CSS . '/ui-lightness/jquery-ui-1.10.4.custom.css', false, A13_THEME_VER, 'all'  );
        wp_enqueue_style( 'admin-css', A13_TPL_CSS . '/admin-css.css', false, A13_THEME_VER, 'all' );

    }
}


/*
 * Scripts in admin_enqueue_scripts hook
 */
if(!function_exists('a13_admin_scripts')){
    function a13_admin_scripts(){
        wp_enqueue_media();
    }
}


/**
 * Adds menu with settings for theme
 */
if(!function_exists('a13_admin_pages')){
    function a13_admin_pages() {
        add_theme_page( sprintf( a13__be( '%s Sidebars' ), A13_TPL_NAME ), sprintf( a13__be( '%s Sidebars' ), A13_TPL_NAME ), 'manage_options', 'apollo13_sidebars', 'a13_show_settings_page');
        add_theme_page( sprintf( a13__be( '%s Advanced' ), A13_TPL_NAME ), sprintf( a13__be( '%s Advanced' ), A13_TPL_NAME ), 'manage_options', 'apollo13_advanced', 'a13_show_settings_page');
        add_theme_page( sprintf( a13__be( '%s Import &amp; export' ), A13_TPL_NAME ), sprintf( a13__be( '%s Import &amp; export' ), A13_TPL_NAME ), 'manage_options', 'apollo13_import', 'a13_show_settings_page');
    }
}




/**
 * Settings page template
 */
if(!function_exists('a13_show_settings_page')){
    function a13_show_settings_page() {
        if (!current_user_can('manage_options')){
            wp_die(  a13__be( 'You do not have sufficient permissions to access this page.' ) );
        }
        global $title;  //get the title of page from <title> tag
        //get options list for current settings page
        $func = $_GET['page'] . '_options';
        $option_list = $func();
	    $option_list = $option_list['opt'];
        //get name of options we will change
        $options_name = str_replace( 'apollo13_', '', $_GET['page']);

        ?>
    <div class="wrap apollo13-settings apollo13-options metabox-holder" id="apollo13-settings">
        <h2><img id="a13-logo" src="<?php echo esc_url(A13_TPL_GFX .'/admin/icon_big.png'); ?>" /><?php echo esc_html($title); ?></h2>
        <div class="apollo-help">
            <p><span>!</span><?php printf(  a13__be( 'If you need any help check <a href="%s" target="_blank">documentation</a> or <a href="%s" target="_blank">visit our support forum</a>' ), esc_url(A13_DOCS_LINK), 'http://support.apollo13.eu/' ); ?></p>
        </div>
        <?php
        if ( isset( $_POST[ 'theme_updated' ] ) ) {
            ?>
            <div id="message" class="updated">
                <p><?php printf(  a13__be( 'Template updated. <a href="%s">Visit your site</a> to see how it looks.' ), esc_url(home_url( '/' )) ); ?></p>
            </div>
            <?php
        }
        a13_print_options( $option_list, $options_name );
        ?>

    </div>
    <?php
    }
}

function a13_admin_footer() {
    echo '<div id="a13-fa-icons">';

    define('A13_FA_GENERATOR_DIR', A13_TPL_ADV_DIR . '/inc/font-awesome-classes-generator/');

    $classes = require_once(A13_FA_GENERATOR_DIR.'/index.php');
    foreach($classes as $name){
        echo '<span class="a13-font-icon fa fa-'.$name.'" title="'.$name.'"></span>';
    }
    echo '</div>';
}

//flush if settings has changed (important for CUSTOM POST TYPES and their slugs)
function a13_flush_for_cpt(){
    if ( defined( 'A13_SETTINGS_CHANGED' ) && A13_SETTINGS_CHANGED ) {
        flush_rewrite_rules();
    }
}

function a13_update_theme_notice(){
	$state = get_option('external_theme_updates-'.A13_TPL_SLUG);
	$update = $state->update;

	if ( is_string($state->update) ) {
		$update = ThemeUpdate::fromJson($state->update);
	}
	if(!version_compare(A13_THEME_VER,$update->version,"<")){
		return; //other check cause update plugin is failing sometimes
	}
	echo '<div class="updated"><p>';

	printf(  a13__be( 'There is new version <em>%1$s</em> of <strong>%2$s theme</strong> available. Please go to <a href="%3$s" target="_blank">ThemeForest</a> and get new version of it. Next follow <a href="%4$s" target="_blank">update instructions from documentation</a>. Good luck ;-) <br /><a href="%5$s" target="_blank">Check changes in Change log</a>'),
		$update->version,
		A13_TPL_NAME,
		$update->details_url,
		A13_DOCS_LINK.'#!/installation_update_update_theme',
		'http://www.apollo13.eu/themes_update/changelog.php?t='.A13_TPL_SLUG
	);
	echo '</p></div>';
}


add_action( 'init', 'a13_flush_for_cpt', 20 ); /* run after register of CPT's */
add_action( 'admin_menu', 'a13_admin_pages' );
add_action( 'admin_init', 'a13_admin_head' );
add_action( 'admin_enqueue_scripts', 'a13_admin_scripts');
add_action( 'admin_footer', 'a13_admin_footer');