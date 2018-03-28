<?php

//globals used by these functions
global $a13_customizer_js, 
       $a13_customizer_dependencies_js, 
       $a13_customizer_dependencies_php;

//here we will collect all JS for customizer controls updated by postMessage
$a13_customizer_js = '';
//here we will collect all dependencies to know where to show hide each control by JS
$a13_customizer_dependencies_js = array(
	'switches'      => array(),
	'dependencies'  => array()
);
//here we will collect all dependencies to know where to show hide each control by PHP
$a13_customizer_dependencies_php = array();



/**
 * Generates input, selects and other form controls
 * @param $option : currently processed option with all attributes
 * @param $args : pre-completed array of params for current control
 * @param $wp_customize : reference to $wp_customize
 * @return mixed : true if only array were completed, object for custom control, false if not found control
 */
function a13_customizer_controls($option, &$args, &$wp_customize){
	$return = false;

	switch( $option['type'] ) {
		case 'textarea':
			$args['default']    = $option['default'];
			$args['type']       = 'textarea';

			$return = true;
			break;

		case 'radio':
			$args['default']    = $option['default'];
			$args['type']       = 'radio';
			$args['mode']       = 'buttonset';
			$args['choices']    = $option['options'];

//			$return = true;
			$return = new Kirki_Customize_Radio_Control( $wp_customize, $args['setting'], $args);

			break;

		case 'select':
			$args['default']    = $option['default'];
			$args['type']       = 'select';
			$args['choices']    = $option['options'];

			$return = true;
			break;

		case 'wp_dropdown_pages':
			$args['default']    = $option['default'];
			$args['type']       = 'dropdown-pages';

			$return = true;
			break;

		case 'wp_dropdown_albums':
			$args['default']    = $option['default'];
			$args['type']       = 'select';

			$wp_query_params = array(
				'posts_per_page' => -1,
				'no_found_rows' => true,
				'post_type' => A13_CUSTOM_POST_TYPE_ALBUM,
				'post_status' => 'publish',
				'ignore_sticky_posts' => true,
				'orderby' => 'date'
			);

			$r = new WP_Query($wp_query_params);

			if ($r->have_posts()) :
				while ($r->have_posts()) : $r->the_post();
					$args['choices'][get_the_ID()] = get_the_title();
				endwhile;

				// Reset the global $the_post as this query will have stomped on it
				wp_reset_postdata();

			else:
				$args['choices'][0] = a13__be('There is no albums yet!');

			endif;

			$return = true;

			break;

		case 'slider':
			$args['default'] = $option['default'];
			$args['min']    = isset($option['min'])? $option['min'] : '';
			$args['max']    = isset($option['max'])? $option['max'] : '';
			$args['unit']   = isset($option['unit'])? $option['unit'] : '';

			$return = new A13_Customizer_Sliderui_Control( $wp_customize, $args['setting'], $args);
			break;

		case 'color':
			$args['default'] = $option['default'];

			$return = new A13_Customizer_Color_Control( $wp_customize, $args['setting'], $args);
//			$return = new WP_Customize_Color_Control( $wp_customize, $args['setting'], $args);
			break;

		case 'image':
			$args['default'] = $option['default'];

			$return = new WP_Customize_Image_Control( $wp_customize, $args['setting'], $args);
			break;

		case 'upload':
			$args['default'] = $option['default'];

			$return = new WP_Customize_Upload_Control( $wp_customize, $args['setting'], $args);
			break;

		case 'font':
			$args['default'] = $option['default'];
			$args['choices'] = $option['options'];

			$return = new A13_Customizer_Font_Control( $wp_customize, $args['setting'], $args);
			break;

		case 'reset_cookie':
			$args['default'] = $option['default'];

			$return = new A13_Customizer_Reset_Cookie_Control( $wp_customize, $args['setting'], $args);
			break;

		case 'socials':
			$args['default'] = $option['default'];
			$args['choices'] = $option['options'];

			$return = new A13_Customizer_Socials_Control( $wp_customize, $args['setting'], $args);
			break;

		default:
			$args['default'] = $option['default'];
			$args['type'] = $option['type'];
			if(isset($option['input_attrs'])){
				$args['input_attrs'] = $option['input_attrs'];
			}

			$return = true;
			break;
	}

	return $return;
}


function a13_sanitize_setting($value, $setting){
	global $a13_apollo13;

	if(isset($setting)){
		//get setting parts
		$pattern = '/^[^_]+_([^\[]+)\[([^\]]+)]/';
		preg_match($pattern, $setting->id, $matches);
		$section = $matches[1];
		$option = $matches[2];
		$function_to_call = 'apollo13_' . $section . '_options';
		$current_opt = false;

		if( function_exists($function_to_call) ){
			$options = $function_to_call();

			//we search for our option details
			foreach ( $options['opt'] as $opt ){
				if($opt['id'] === $option){
					$current_opt = $opt;
					break;
				}
			}
			if($current_opt !== false){
				//pure sanitization starts here

				$type = $current_opt['type'];

				switch( $type ) {
					case 'textarea':
						if($current_opt['id'] !== 'custom_css'){
							$value = wp_kses_post( force_balance_tags( $value ) );
						}

						return $value;

					case 'radio':
					case 'select':
//                  These are not used cause we don't have options before hand
//                  and with current sanitization bug https://core.trac.wordpress.org/ticket/32103 it would be overkill
//					case 'wp_dropdown_pages':
//					case 'wp_dropdown_albums':
						if( array_key_exists( $value, $current_opt['options'] ) ){
							return $value;
						}

						return '';
					case 'image':
					case 'upload':
						if( filter_var( $value, FILTER_VALIDATE_URL )){
							return $value;
						}
						return '';

					case 'slider':
						//we want to allow values out of range
						//so we only strip tags
					case 'socials':
					case 'font':
					case 'color':
						return strip_tags($value);

					case 'reset_cookie':
						if(preg_match('/^[a-zA-Z0-9._]+$/', $value)) {
							return $value;
						}
						return '';

					default:
						return $value;
				}

			}
			else{
				//nothing we can do
				return '';
			}
		}
		else{
			//nothing we can do
			return '';
		}
	}
	//nothing we can do
	return '';
}

function a13_customizer_settings( $wp_customize ) {
	global $a13_apollo13, $a13_customizer_dependencies_php, $a13_customizer_dependencies_js;

	//include all custom controls
	foreach (glob(A13_TPL_ADV_DIR."/customizer/custom_controls/*.php") as $filename) {
		require_once ($filename);
	}


	$option_func = $a13_apollo13->customizer_groups;

	$panel_priority = 2;//below theme selector
	foreach($option_func as $function){
		$function_to_call = 'apollo13_' . $function . '_options';
		$options = $function_to_call();
		$section_priority = 0;

		//we group sections in panels
		$wp_customize->add_panel(
			$function_to_call,
			array(
				'title'         => $options['title'],
				'description'   => isset($options['description'])? $options['description'] : '',
				'priority'      => $panel_priority++,
			)
		);

		//clear section for each new panel
		$section = '';
		$control_priority = 0;
		foreach( $options['opt'] as $option) {
			//register section
			if($option['type'] === 'fieldset'){
				$section = $option['id'];

				$wp_customize->add_section(
					$section,
					array(
						'panel'         => $function_to_call,
						'title'         => $option['name'],
						'description'   => isset($option['description'])? $option['description'] : '',
						'priority'      => $section_priority++,
					)
				);

				//reset counter
				$control_priority = 0;

				//move to next option
				continue;
			}

			//register control
			if(isset($option['id']) && isset($option['default']) ){
				$setting_name = A13_TPL_SLUG.'_'.$function.'['.$option['id'].']';

				//do we know how to refresh this setting instantly?
				if(isset($option['js'])){
					a13_add_customizer_js($setting_name, $option['js']);
				}

				//register setting
				$wp_customize->add_setting( $setting_name, array(
					'default'        => $option['default'],
					'type'           => 'option',
					'sanitize_callback' => 'a13_sanitize_setting',
					'transport' => isset($option['js'])? 'postMessage' : 'refresh'
				) );

				$control_args = array(
					'label'      => $option['name'],
					'description'=> isset($option['description'])? $option['description'] : '',
					'section'    => $section,
					'setting'    => $setting_name,
					'priority'   => $control_priority++,
				);

				//control needs other controls on particular values?
				if(isset($option['required'])){
					$control_args['active_callback'] = 'a13_customizer_activate_callback';
					$a13_customizer_dependencies_php[$function][$option['id']] = $option['required'];
					foreach($option['required'] as $id => $val ){
						$id = A13_TPL_SLUG.'_'.$function.'['.$id.']';
						$a13_customizer_dependencies_js['switches'][$id][] = $setting_name;
						$a13_customizer_dependencies_js['dependencies'][$setting_name][$id] = $val;
					}

				}



				$control = a13_customizer_controls($option, $control_args, $wp_customize);

				if($control === true){
					$wp_customize->add_control( $setting_name, $control_args );
				}
				elseif(is_object($control)){
					$wp_customize->add_control( $control );
				}
			}

		}
	}

	//register js for customizer
	if ( $wp_customize->is_preview() && ! is_admin() ){
		add_action( 'wp_footer', 'a13_customizer_preview_js_css', 21);
	}
}
add_action( 'customize_register', 'a13_customizer_settings' );

//function checks is control should be visible on page load
function a13_customizer_activate_callback($control){
	global $a13_apollo13, $a13_customizer_dependencies_php;

	//we break id of control to settings group and setting id
	$parts = explode('[', $control->id);
	$panel_name = explode('_', $parts[0]);
	$panel = $panel_name[1];
	$id = substr($parts[1],0,-1);

	//get requirements from global table
	$requirement = $a13_customizer_dependencies_php[$panel][$id];

	//check if control met all requirements
	$show = true;
	foreach($requirement as $key => $val){
		if($a13_apollo13->get_option($panel, $key) !== $val){
			$show = false; //we shouldn't display this control
			break;
		}
	}

	return $show;
};

//function to update current JS for customizer
function a13_add_customizer_js( $setting_name, $js ){
	global $a13_customizer_js;

	$a13_customizer_js .= "wp.customize('$setting_name',function( value ) {
        value.bind(function(to) {
            $js
        });
    });";
}

//function to display JS & CSS for customizer
//CSS to reflect changes
//JS to show changes instantly
function a13_customizer_preview_js_css(){
	global $wp_styles;
	//CSS
	$css = include( A13_TPL_ADV_DIR . '/user-css.php' );
	echo '<style type="text/css" media="all">'.$css.'</style>';

	//print inline styles
	$wp_styles->print_inline_style('user-css');


	//JS
	global $a13_customizer_js;

	?>
	<script type="text/javascript">
		( function( $ ){
			<?php echo $a13_customizer_js; ?>
		} )( jQuery );
	</script>
<?php
}

//function to print controls JS
function a13_customizer_admin_js(){
	wp_enqueue_script('a13-admin-customizer', A13_TPL_JS . '/admin-customizer.js',
		array(
			'jquery',
			'customize-controls', 'iris', 'underscore', 'wp-util'
		),
		A13_THEME_VER
	);
}
//add_action( 'customize_controls_init', 'a13_customizer_admin_js');
add_action( 'customize_controls_enqueue_scripts', 'a13_customizer_admin_js');


//function to print controls JS dependencies
function a13_customizer_admin_js_dependencies(){
	global  $a13_customizer_dependencies_js;

	//prepare JS constant
	$a13_customizer_dependencies_js = 'A13_CUSTOMIZER_DEPENDENCIES = '.json_encode($a13_customizer_dependencies_js);
	?>
	<script type="text/javascript">
		<?php echo $a13_customizer_dependencies_js; ?>
	</script>
<?php
}
add_action( 'customize_controls_print_scripts', 'a13_customizer_admin_js_dependencies');


//function to print icons selector
function a13_customizer_footer() {
	echo '<div id="a13-fa-icons">';

	define('A13_FA_GENERATOR_DIR', A13_TPL_ADV_DIR . '/inc/font-awesome-classes-generator/');

	$classes = require_once(A13_FA_GENERATOR_DIR.'/index.php');
	foreach($classes as $name){
		echo '<span class="a13-font-icon fa fa-'.$name.'" title="'.$name.'"></span>';
	}
	echo '</div>';
}
add_action( 'customize_controls_print_footer_scripts', 'a13_customizer_footer' );