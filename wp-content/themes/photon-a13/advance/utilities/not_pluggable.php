<?php
/**
 functions that shouldn't be overwritten but it is good to keep them grouped here
 */

/* Register your custom function to override some LayerSlider data */
function a13_layerslider_overrides() {
    // Disable auto-updates
    $GLOBALS['lsAutoUpdateBox'] = false;
}
add_action('layerslider_ready', 'a13_layerslider_overrides');




/* Checking if we are on demo or dev server */
function a13_is_home_server(){
	return strpos($_SERVER['SERVER_NAME'], 'apollo13.kinsta.com') !== false;
}


/* Generates user css based on settings in admin panel */
function a13_make_css_rule($property, $value, $format = false){
	if ( $value !== '' &&  $value !== 'default' ){
		//format for some properties
		if( $format !== false ){
			return $property . ': ' . sprintf($format, $value) . ';';
		}

		return $property . ': ' . $value . ";";
	}
	else{
		if( $value === '' && $property === 'background-image' ){
			return $property.': none;';
		}
		return '';
	}
}

add_action('get_header', 'a13_remove_admin_head_bump');

function a13_remove_admin_head_bump() {
	remove_action('wp_head', '_admin_bar_bump_cb');
}


function a13_rgba2hex( $r, $g, $b, $a ){
	return sprintf( '#%02s%02s%02s%02s', dechex( 255 * $a ), dechex( $r ), dechex( $g ), dechex( $b ) );
}

function a13_hex2rgba( $hex, $opacity = 1 ) {
	list( $r, $g, $b ) = sscanf( $hex, "#%02x%02x%02x" );

	return 'rgba('.$r.','.$g.','.$b.','.$opacity.')';
}

function a13_break_rgba( $rgba ){
	$chunks = array();
	preg_match("/\(\s*(\d+),\s*(\d+),\s*(\d+),\s*(\d+\.?\d*)\s*\)/", $rgba, $chunks);
	return $chunks;
}