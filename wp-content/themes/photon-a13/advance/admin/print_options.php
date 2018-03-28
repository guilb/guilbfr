<?php
/**
 * Generates form of settings
 *
 * @param $options  : current page settings
 * @param $opt_name : name of settings group
 */
function a13_print_options( &$options, $opt_name ) {
	global $a13_apollo13;
	$a13_prefix = A13_INPUT_PREFIX;
	?>
	<form method="post" action="">
		<?php
		$fieldset_open = false;
		$params        = array( 'opt_name' => $opt_name );
		$no_save       = false;
		$save_button   = '
			<div class="text-input input-parent clearfix">
                <div class="input-desc">
                    <div class="save-opts"><input type="submit" name="theme_updated" class="button-primary autowidth" value="' . esc_attr( a13__be( 'Save Changes' ) ) . '" /></div>
                </div>
            </div>';

		foreach ( $options as $option ) {
			if ( $option['type'] == 'fieldset' ) {
				if ( $fieldset_open ) {
					if ( ! isset( $option['no_save_button'] ) || $option['no_save_button'] !== true ) {
						$no_save = false;
						echo $save_button;
					} else {
						$no_save = true;
					}
					?>
            </div>
        </div>
                <?php
				}

				$closed_class = '';
				$input_value  = '0';
				if ( isset( $option['id'] ) ) {
					//value that holds info if fieldset is closed or open
					$hidden_val = $a13_apollo13->get_option( $opt_name, $option['id'] );
					if ( $hidden_val == 0 ) {
						$closed_class = ' closed';
					}
					$input_value = $hidden_val;
				}

				//after each filed set print save button
				echo '<div class="postbox' . $closed_class . '"' . ( isset( $option['id'] ) ? ( ' id="' . $a13_prefix . $option['id'] . '"' ) : '' ) . '>
                            <div class="fieldset-name sidebar-name">
                                <div class="sidebar-name-arrow"><br></div>
                                <h3><span>' . $option['name'] . '</span></h3>
                                ' . ( isset( $option['id'] ) ? ( '<input type="hidden" name="' . $a13_prefix . $option['id'] . '" value="' . $input_value . '" />' ) : '' ) . '
                            </div>
                            <div class="inside">';
				//help info
				if ( isset( $option['help'] ) ) {
					printf( '<strong class="help-info">' . a13__be( 'If you need help with these settings <a href="%s">check this topic</a> in documentation' ) . '</strong>', A13_DOCS_LINK . $option['help'] );
				}

				$fieldset_open = true;
			} //checks for all normal options
			elseif ( a13_print_form_controls( $option, $params ) ) {
				continue;
			}

		}

		/* Close last options div */
		if ( $fieldset_open ) {
			if ( $no_save === false ) {
				echo $save_button;
			}
			?>
        </div>
    </div>
        <?php
		}
		?>
	</form>
<?php
}


/**
 * Generates input, selects and other form controls
 *
 * @param $option  : currently processed option with all attributes
 * @param $params  : params for meta type or option type
 * @param $is_meta : meta or option
 *
 * @return bool true if some field was used, false other way
 */
function a13_print_form_controls( $option, &$params, $is_meta = false ) {
	global $a13_apollo13;
	$a13_prefix = A13_INPUT_PREFIX;

	static $switches = array();


	/* SPECIAL CASE TYPES. NEED TO BE BEFORE VALUE GETTING */
	if ( $option['type'] == 'switch-group' ) {
		$style_group  = ' style="display: none;"';
		$switch_value = end( $switches ); //get last added switch

		//check if current group should be visible
		if ( strlen( $switch_value && $switch_value == $option['name'] ) ) {
			$style_group = '';
		}

		echo '<div class="switch-group" data-switch="' . $option['name'] . '"' . $style_group . '>';

		return true;
	} elseif ( $option['type'] == 'switch-group-end' ) {
		echo '</div>';

		return true;
	} elseif ( $option['type'] == 'end-switch' ) {
		//remove last added switch
		array_pop( $switches );
		echo '</div>';

		return true;
	}


	/* Extract some variables */
	$style  = '';
	$switch = isset( $option['switch'] ) ? ' switch-control' : '';

	$description = isset( $option['description'] ) ? $option['description'] : '';

	if ( $is_meta ) {
		$value = $params['value'];
		$style = $params['style'];
	} //if run for theme options
	else {
		$value = $a13_apollo13->get_option( $params['opt_name'], $option['id'] );
	}

	//check if this option is switch
	if ( isset( $option['switch'] ) && $option['switch'] == true ) {
		echo '<div class="switch">';
		//add to switches array
		array_push( $switches, $value );
	}


	/* NORMAL TYPES */
	if ( $option['type'] == 'upload' ) {
		$upload_button_text = ! empty( $option['button_text'] ) ? $option['button_text'] : a13__be( 'Upload' );
		$data_attr          = '';
		if ( isset( $option['attachment_field'] ) && strlen( $option['attachment_field'] ) ) {
			$data_attr = ' data-attachment="'.esc_attr($option['attachment_field']).'"';
		}

		$media_button_text = '';
		if ( isset( $option['media_button_text'] ) && strlen( $option['media_button_text'] ) ) {
			$media_button_text = ' data-media-button-name="' . esc_attr($option['media_button_text']) . '"';
		}

		$media_type = '';
		if ( isset( $option['media_type'] ) && strlen( $option['media_type'] ) ) {
			$media_type = ' data-media-type="' . esc_attr($option['media_type']) . '"';
		}
		?>

		<div class="upload-input input-parent"<?php echo $style; ?>>
			<label for="<?php echo esc_attr($a13_prefix . $option['id']); ?>"><?php echo esc_html($option['name']); ?>&nbsp;</label>

			<div class="input-desc">
				<input id="<?php echo esc_attr($a13_prefix . $option['id']); ?>"<?php echo $data_attr; ?> type="text" size="36" name="<?php echo esc_attr($a13_prefix . $option['id']); ?>" value="<?php echo stripslashes( esc_attr( $value ) ); ?>" />
				<input id="upload_<?php echo esc_attr($a13_prefix . $option['id']); ?>" class="upload-image-button" type="button" value="<?php echo esc_attr($upload_button_text) ?>"<?php echo $media_button_text; ?><?php echo $media_type; ?> />
				<input id="clear_<?php echo esc_attr($a13_prefix . $option['id']); ?>" class="clear-image-button" type="button" value="<?php echo esc_attr(a13__be('Clear field')) ?>" />

				<p class="desc"><?php echo $description; ?></p>
			</div>
		</div>
		<?php
		return true;
	} elseif ( $option['type'] == 'text' ) {
		$inp_class   = isset( $option['input_class'] ) ? ( ' class="' . esc_attr($option['input_class']) . '"' ) : '';
		$placeholder = isset( $option['placeholder'] ) ? ( ' placeholder="' . $option['placeholder'] . '"' ) : '';
		?>
		<div class="text-input input-parent"<?php echo $style; ?>>
			<label for="<?php echo esc_attr($a13_prefix . $option['id']); ?>"><?php echo esc_html($option['name']); ?>&nbsp;</label>

			<div class="input-desc">
				<input id="<?php echo esc_attr($a13_prefix . $option['id']); ?>"<?php echo $inp_class . $placeholder; ?> type="text" size="36" name="<?php echo esc_attr($a13_prefix . $option['id']); ?>" value="<?php echo stripslashes( esc_attr( $value ) ); ?>" />

				<p class="desc"><?php echo $description; ?></p>
			</div>
		</div>
		<?php
		return true;
	} elseif ( $option['type'] == 'hidden' ) {
		?>
		<div class="hidden-input input-parent"<?php echo $style; ?>>
			<input id="<?php echo esc_attr($a13_prefix . $option['id']); ?>" type="hidden" name="<?php echo esc_attr($a13_prefix . $option['id']); ?>" value="<?php echo esc_attr( $value ); ?>" />
		</div>
		<?php
		return true;
	} elseif ( $option['type'] == 'textarea' ) {
		?>
		<div class="textarea-input input-parent"<?php echo $style; ?>>
			<label for="<?php echo esc_attr($a13_prefix . $option['id']); ?>"><?php echo esc_html($option['name']); ?>&nbsp;</label>

			<div class="input-desc">
				<textarea rows="10" cols="20" class="large-text" id="<?php echo esc_attr($a13_prefix . $option['id']); ?>" name="<?php echo esc_attr($a13_prefix . $option['id']); ?>"><?php echo stripslashes( esc_textarea( $value ) ); ?></textarea>

				<p class="desc"><?php echo $description; ?></p>
			</div>
		</div>
		<?php
		return true;
	} elseif ( $option['type'] == 'import_textarea' ) {
		?>
		<div class="textarea-input input-parent"<?php echo $style; ?>>
			<label for="<?php echo esc_attr($a13_prefix . $option['id']); ?>"><?php echo esc_html($option['name']); ?>&nbsp;</label>

			<div class="input-desc">
				<textarea rows="10" cols="20" class="large-text" id="<?php echo esc_attr($a13_prefix . $option['id']); ?>" name="<?php echo esc_attr($a13_prefix . $option['id']); ?>"></textarea>

				<p class="desc"><?php echo $description; ?></p>
				<input type="submit" name="import_options" class="button-primary autowidth" value="<?php echo esc_attr( a13__be( 'Import settings' ) ); ?>" />
			</div>
		</div>
		<?php
		return true;
	} elseif ( $option['type'] == 'export_textarea' ) {

		$value = base64_encode(serialize( $a13_apollo13->get_options_array() ));
		?>
		<div class="textarea-input input-parent"<?php echo $style; ?>>
			<label for="<?php echo esc_attr($a13_prefix . $option['id']); ?>"><?php echo esc_html($option['name']); ?>&nbsp;</label>

			<div class="input-desc">
				<textarea rows="10" cols="20" class="large-text" id="<?php echo esc_attr($a13_prefix . $option['id']); ?>" name="<?php echo esc_attr($a13_prefix . $option['id']); ?>"><?php echo stripslashes( esc_textarea( $value ) ); ?></textarea>

				<p class="desc"><?php echo $description; ?></p>
			</div>
		</div>
		<?php
		return true;
	} elseif ( $option['type'] == 'export_site_config' ) {
		$export = array();

		//export widgets
		global $wp_registered_widgets;
		$widgets_types = array();


		//we collect all registered widgets and check if we can get their id_base
		foreach ( $wp_registered_widgets as $widget ) {
			$temp_callback = $widget['callback'];
			if ( is_array( $temp_callback ) ) {
				$widgets_types[] = 'widget_' . $temp_callback[0]->id_base;
			}
		}

		//remove duplicates
		$widgets_types = array_unique( $widgets_types );

		//collect export info only
		$export_widgets = array();
		foreach ( $widgets_types as $type ) {
			$temp_type = get_option( $type );
			if ( $temp_type !== false ) {
				$export_widgets[ $type ] = $temp_type;
			}
		}

		//our export value
		$export['widgets'] = serialize( $export_widgets );


		//export sidebars
		$export['sidebars'] = serialize( get_option( 'sidebars_widgets' ) );


		//export frontpage
		$fp_options = array(
			'show_on_front'  => get_option( 'show_on_front' ),
			'page_on_front'  => get_option( 'page_on_front' ),
			'page_for_posts' => get_option( 'page_for_posts' )
		);

		//our export value
		$export['frontpage'] = serialize( $fp_options );


		//export menus
		$menu_locations = get_nav_menu_locations();
		foreach ( $menu_locations as $key => $id ) {
			if ( $id === 0 ) {
				continue;
			}
			$obj = get_term( $id, 'nav_menu' );
			//instead of id save slug of menu
			$menu_locations[ $key ] = $obj->slug;
		}

		$export['menus'] = serialize( $menu_locations );


		//export plugins settings
		//AddToAny
		$plugins_settings = array();
		if( function_exists('A2A_SHARE_SAVE_init')){
			$plugins_settings['addtoany_options'] = get_option( 'addtoany_options' );
		}
		$export['plugins_configs'] = serialize($plugins_settings);


		//Woocommerce
		if(a13_is_woocommerce_activated()){

			$options_to_export = array(
				'woocommerce_shop_page_id',
				'woocommerce_cart_page_id',
				'woocommerce_checkout_page_id',
				'woocommerce_myaccount_page_id',
				'shop_thumbnail_image_size',
				'shop_catalog_image_size',
				'shop_single_image_size',
			);

			$wc_options = array();

			foreach($options_to_export as $name){
				$wc_options[$name] = get_option($name);
			}

			//wishlist settings
			if(class_exists( 'YITH_WCWL' )){
				$wc_options['yith_wcwl_wishlist_page_id'] = get_option( 'yith_wcwl_wishlist_page_id' );
			}

			//our export value
			$export['woocommerce'] = serialize($wc_options);
		}


		//final value
		$value = base64_encode(serialize( $export ));
		?>
		<div class="textarea-input input-parent"<?php echo $style; ?>>
			<label for="<?php echo esc_attr($a13_prefix . $option['id']); ?>"><?php echo esc_html($option['name']); ?>&nbsp;</label>

			<div class="input-desc">
				<textarea rows="10" cols="20" class="large-text" id="<?php echo esc_attr($a13_prefix . $option['id']); ?>" name="<?php echo esc_attr($a13_prefix . $option['id']); ?>"><?php echo stripslashes( esc_textarea( $value ) ); ?></textarea>

				<p class="desc"><?php echo $description; ?></p>
			</div>
		</div>
		<?php
		return true;
	} elseif ( $option['type'] == 'import_demo_data' ) {
		?>
		<div class="demo-data-input input-parent"<?php echo $style; ?>>
			<label for="<?php echo esc_attr($a13_prefix . $option['id']); ?>"><?php echo esc_html($option['name']); ?>&nbsp;</label>

			<div class="input-desc">
				<div id="demo_data_import_progress"></div>
				<a href="#" id="<?php echo esc_attr( $a13_prefix . $option['id'] ); ?>" class="button-primary autowidth" data-confirm="<?php a13_be( 'Are you sure? It will clean all your current content.' ); ?>"><?php a13_be( 'Import demo data content' ); ?></a>

				<p class="desc"><?php echo $description; ?></p>
				<a href="#" id="<?php echo esc_attr( $a13_prefix . $option['id'] ); ?>_log_link"><?php a13_be( 'Show/hide log.' ); ?></a>

				<p class="desc"><?php a13_be( 'Warnings are normal things here, so don\'t panic and don\'t interpret this on you own;-) ' ); ?></p>

				<div id="demo_data_import_log"></div>

			</div>
		</div>
		<?php
		return true;
	} elseif ( $option['type'] == 'import_set_select' ) {
		$selected      = $value;
		$selected_prop = ' selected="selected"';
		?>
		<div class="select-input input-parent<?php echo esc_attr($switch); ?>"<?php echo $style; ?>>
			<label for="<?php echo esc_attr($a13_prefix . $option['id']); ?>"><?php echo esc_html($option['name']); ?></label>

			<div class="input-desc">
				<select id="<?php echo esc_attr($a13_prefix . $option['id']); ?>" name="<?php echo esc_attr($a13_prefix . $option['id']); ?>">
					<?php
					foreach ( $option['options'] as $html_value => $html_option ) {
						echo '<option value="' . esc_attr( $html_value ) . '"' . ( (string) $html_value == (string) $selected ? $selected_prop : '' ) . '>' . $html_option . '</option>';
					}
					?>
				</select>

				<p class="desc"><?php echo $description; ?></p>
				<input type="submit" name="import_options" class="button-primary autowidth" value="<?php echo esc_attr( a13__be( 'Import settings' ) ); ?>" />
			</div>
		</div>
		<?php
		return true;
	} elseif ( $option['type'] == 'import_radio_reset' ) {
		$selected = $value;
		?>
		<div class="radio-input input-parent<?php echo esc_attr($switch); ?>"<?php echo $style; ?>>
			<span class="label-like"><?php echo esc_html($option['name']); ?></span>

			<div class="input-desc">
				<?php
				foreach ( $option['options'] as $html_value => $html_option ) {
					$selected_attr = '';
					if ( (string) $html_value == (string) $selected ) {
						$selected_attr = ' checked="checked"';
					}
					echo '<label><input type="radio" name="' . $a13_prefix . $option['id'] . '" value="' . esc_attr( $html_value ) . '"' . $selected_attr . ' />' . $html_option . '</label>';
				}
				?>
				<p class="desc"><?php echo $description; ?></p>
				<input type="submit" name="theme_updated" class="button-primary autowidth" value="<?php echo esc_attr( a13__be( 'Reset' ) ); ?>" />
			</div>
		</div>
		<?php
		return true;
	} elseif ( $option['type'] == 'select' ) {
		$selected      = $value;
		$selected_prop = ' selected="selected"';
		?>
		<div class="select-input input-parent<?php echo esc_attr($switch); ?>"<?php echo $style; ?>>
			<label for="<?php echo esc_attr($a13_prefix . $option['id']); ?>"><?php echo esc_html($option['name']); ?></label>

			<div class="input-desc">
				<select id="<?php echo esc_attr($a13_prefix . $option['id']); ?>" name="<?php echo esc_attr($a13_prefix . $option['id']); ?>">
					<?php
					foreach ( $option['options'] as $html_value => $html_option ) {
						echo '<option value="' . esc_attr( $html_value ) . '"' . ( (string) $html_value == (string) $selected ? $selected_prop : '' ) . '>' . $html_option . '</option>';
					}
					?>
				</select>

				<p class="desc"><?php echo $description; ?></p>
			</div>
		</div>
		<?php
		return true;
	} elseif ( $option['type'] == 'radio' ) {
		$selected = $value;
		?>
		<div class="radio-input input-parent<?php echo esc_attr($switch); ?>"<?php echo $style; ?>>
			<span class="label-like"><?php echo esc_html($option['name']); ?></span>

			<div class="input-desc">
				<?php
				foreach ( $option['options'] as $html_value => $html_option ) {
					$selected_attr = '';
					if ( (string) $html_value == (string) $selected ) {
						$selected_attr = ' checked="checked"';
					}
					echo '<label><input type="radio" name="' . $a13_prefix . $option['id'] . '" value="' . esc_attr( $html_value ) . '"' . $selected_attr . ' />' . $html_option . '</label>';
				}
				?>
				<p class="desc"><?php echo $description; ?></p>
			</div>
		</div>
		<?php
		return true;
	} elseif ( $option['type'] == 'color' ) {
		?>
		<div class="color-input input-parent"<?php echo $style; ?>>
			<label for="<?php echo esc_attr($a13_prefix . $option['id']); ?>"><?php echo esc_html($option['name']); ?></label>

			<div class="input-desc">
				<div class="input-tip">
					<span class="hover">?</span>

					<p class="tip"><?php a13_be( 'Use valid CSS <code>color</code> property values( <code>green, #33FF99, rgb(255,128,0)</code> ), or get your color with color picker tool.<br />Use <code>Transparent</code> button to insert transparent value.<br />Left empty to use default theme value.' ); ?></p>
				</div>
				<input id="<?php echo esc_attr($a13_prefix . $option['id']); ?>" type="text" class="with-color" name="<?php echo esc_attr($a13_prefix . $option['id']); ?>" value="<?php echo stripslashes( esc_attr( $value ) ); ?>" />
				<button class="transparent-value button-secondary"><?php a13_be( 'Transparent' ); ?></button>
				<p class="desc"><?php echo $description; ?></p>
			</div>
		</div>
		<?php
		return true;
	} elseif ( $option['type'] == 'slider' ) {
		$min = isset( $option['min'] ) ? $option['min'] : '';
		$max = isset( $option['max'] ) ? $option['max'] : '';
		?>
		<div class="slider-input input-parent"<?php echo $style; ?>>
			<label for="<?php echo esc_attr($a13_prefix . $option['id']); ?>"><?php echo esc_html($option['name']); ?></label>

			<div class="input-desc">
				<div class="input-tip">
					<span class="hover">?</span>

					<p class="tip"><?php a13_be( 'Use slider to set proper value. You can click on slider handle and then use arrows keys(on keyboard) to adjust value precisely. You can also type in input value that is in/out of range of slider, and it will be used.' ); ?></p>
				</div>
				<input class="slider-dump" id="<?php echo esc_attr($a13_prefix . $option['id']); ?>" type="text" name="<?php echo esc_attr($a13_prefix . $option['id']); ?>" value="<?php echo stripslashes( esc_textarea( $value ) ); ?>" />

				<div class="slider-place" data-min="<?php echo esc_attr($min); ?>" data-max="<?php echo esc_attr($max); ?>" data-unit="<?php echo esc_attr($option['unit']); ?>"></div>
				<p class="desc"><?php echo $description; ?></p>
			</div>
		</div>
		<?php
		return true;
	} elseif ( $option['type'] == 'wp_dropdown_pages' ) {
		$selected = $value;
		?>
		<div class="select-input input-parent"<?php echo $style; ?>>
			<label for="<?php echo esc_attr($a13_prefix . $option['id']); ?>"><?php echo esc_html($option['name']); ?></label>

			<div class="input-desc">
				<?php
				$wp_pages = wp_dropdown_pages( array(
					'selected'          => $selected,
					'name'              => $a13_prefix . $option['id'],
					'show_option_none'  => a13__be( 'Select page' ),
					'option_none_value' => '0',
					'echo'              => 0
				) );
				if ( strlen( $wp_pages ) ) {
					echo $wp_pages;
				} else {
					a13_be( '<span class="empty-type">There is no pages yet!</span>' );
				}
				?>
				<p class="desc"><?php echo $description; ?></p>
			</div>
		</div>
		<?php
		return true;
	}
	elseif ( $option['type'] == 'wp_dropdown_products' ) {
		?>
		<div class="select-input input-parent"<?php echo $style; ?>>
			<label for="<?php echo esc_attr($a13_prefix . $option['id']); ?>"><?php echo esc_html($option['name']); ?></label>

			<div class="input-desc">
				<?php
				$args = array(
					'post_type'				=> 'product',
					'post_status'			=> 'publish',
					'ignore_sticky_posts'	=> 1,
					'posts_per_page' 		=> -1,
					'orderby' => 'title'
				);

				$products = new WP_Query( apply_filters( 'woocommerce_shortcode_products_query', $args ) );

				if ( $products->have_posts() ){
					$wp_products = '<select name="'.$a13_prefix.$option['id'].'" id="'.$a13_prefix.$option['id'].'">';
					$wp_products .= '<option value="0">'.a13__be('None').'</option>';

					while ( $products->have_posts() ) {
						$products->the_post();
						$id = get_the_ID();
						$wp_products .= '<option value="'.$id.'" '.selected( $value, 1, false ).'>'.get_the_title().'</option>';
					}

					$wp_products .= '</select>';
				}
				else{
					$wp_products = a13__be('<span class="empty-type">There is no products yet!</span>');
				}

				wp_reset_postdata();

				echo $wp_products;

				?>
				<p class="desc"><?php echo $description; ?></p>
			</div>
		</div>
		<?php
		return true;
	}
	elseif ( $option['type'] == 'sidebars' ) {
		$placeholder = isset( $option['placeholder'] ) ? ( ' placeholder="' . esc_attr($option['placeholder']) . '"' ) : '';
		?>
		<div class="text-input input-parent"<?php echo $style; ?>>
			<label for="<?php echo esc_attr($a13_prefix . $option['id']); ?>"><?php echo esc_html($option['name']); ?>&nbsp;</label>

			<div class="input-desc">
				<input id="<?php echo esc_attr($a13_prefix . $option['id']); ?>"<?php echo $placeholder; ?> type="text" size="36" name="<?php echo esc_attr($a13_prefix . $option['id']); ?>" value="" />

				<p class="desc"><?php echo $description; ?></p>
			</div>
			<?php
			$custom_sidebars = unserialize( $value );
			$sidebars_count  = count( $custom_sidebars );
			if ( is_array( $custom_sidebars ) && $sidebars_count > 0 ) {
				echo '<h3>' . a13__be( 'Your current custom sidebars:' ) . '</h3>';
				echo '<ol id="a13-custom-sidebars-list">';
				foreach ( $custom_sidebars as $sidebar ) {
					echo '<li><b>' . $sidebar['name'] . '</b> <a href="#" id="' . $sidebar['id'] . '">' . a13__be( 'Remove sidebar' ) . '</a></li>';
				}
				echo '</ol>';
			}
			?>
		</div>
		<?php
		return true;
	} elseif ( $option['type'] == 'wp_dropdown_revosliders' ) {
		//check if we have class of Revolution Sliders
		if ( ! class_exists( 'RevSlider' ) ) {
			return true;
		}

		$slider        = new RevSlider();
		$arrSliders    = $slider->getArrSliders();
		$selected      = $value;
		$selected_prop = ' selected="selected"';
		?>
		<div class="select-input input-parent"<?php echo $style; ?>>
			<label for="<?php echo esc_attr($a13_prefix . $option['id']); ?>"><?php echo esc_html($option['name']); ?></label>

			<div class="input-desc">
				<?php


				if ( sizeof( $arrSliders ) ) :
					echo '<select name="' . $a13_prefix . $option['id'] . '" id="' . $a13_prefix . $option['id'] . '">';

					foreach ( $arrSliders as $slider ) {
						$title = $slider->getTitle();
						$alias = $slider->getAlias();

						echo '<option value="' . $alias . '"' . ( ( (string) $alias == (string) $selected ) ? $selected_prop : '' ) . '>' . $title . '</option>';
					}

					echo '</select>';


				else:
					a13_be( '<span class="empty-type">There is no sliders yet!</span>' );
				endif;
				?>
				<p class="desc"><?php echo $description; ?></p>
			</div>
		</div>
		<?php
		return true;
	} elseif ( $option['type'] == 'wp_dropdown_layersliders' ) {
		//check if we have class of Revolution Sliders
		if ( ! function_exists( 'lsSliders' ) ) {
			return true;
		}

		$arrSliders    = lsSliders( 200, true, false );
		$selected      = $value;
		$selected_prop = ' selected="selected"';
		?>
		<div class="select-input input-parent"<?php echo $style; ?>>
			<label for="<?php echo esc_attr($a13_prefix . $option['id']); ?>"><?php echo esc_html($option['name']); ?></label>

			<div class="input-desc">
				<?php


				if ( sizeof( $arrSliders ) ) :
					echo '<select name="' . $a13_prefix . $option['id'] . '" id="' . $a13_prefix . $option['id'] . '">';

					foreach ( $arrSliders as $slider ) {
						$title = $slider['name'];
						$id    = $slider['id'];

						echo '<option value="' . $id . '"' . ( ( (string) $id === (string) $selected ) ? $selected_prop : '' ) . '>' . $title . '</option>';
					}

					echo '</select>';


				else:
					a13_be( '<span class="empty-type">There is no sliders yet!</span>' );
				endif;
				?>
				<p class="desc"><?php echo $description; ?></p>
			</div>
		</div>
		<?php
		return true;
	}

	return false;
}