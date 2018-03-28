<?php

class A13_Customizer_Font_Control extends WP_Customize_Control {

	public $type = 'font';


	public function render_content() {
		$value = $this->value();
		$font_parts = explode(':', $value);
		$font_name = $font_parts[0];
		$selected = $font_name;
		$selected_prop = ' selected="selected"';
		$checked_prop = ' checked="checked"';
		//link to generate: https://www.googleapis.com/webfonts/v1/webfonts?
		$google_fonts = json_decode(file_get_contents( A13_TPL_ADV_DIR . '/inc/google-font-json' ));
		$sample_text = 'Sample text with <strong>some bold words</strong> and numbers 1 2 3 4 5 6 7 8 9 69 ;-)';
		$options = '';
		$variants = array();
		$variants_html = '';
		$subsets = array();
		$subsets_html = '';

		//prepare select with fonts
		//Normal fonts
		$options .= '<optgroup label="'. a13__be('Classic fonts').'">';
		foreach( $this->choices as $html_value => $html_option ) {
			$options .= '<option class="classic-font" value="' . esc_attr($html_value) . '"' . ($html_value == $selected? $selected_prop : '') . '>' . $html_option . '</option>';
		}
		$options .= '</optgroup>';

		//Google fonts
		$options .= '<optgroup label="'. a13__be('Google fonts').'">';
		foreach( $google_fonts->items as $font ) {
			$options .= '<option value="' . esc_attr($font->family) . '"' . ($font->family == $selected? $selected_prop : '') . '>' . $font->family . '</option>';
			//save params of current font
			if($font->family == $font_name){
				$variants = $font->variants;
				$subsets = $font->subsets;
			}
		}
		$options .= '</optgroup>';

		//prepare variants of selected font
		if(sizeof($variants) > 0){
			//make array of selected variants
			$used_variants = isset($font_parts[1])? explode(',', $font_parts[1]) : array();

			foreach( $variants as $v ) {
				$variants_html .= '<label><input type="checkbox" name="variant" value="'.$v.'"' . (in_array($v, $used_variants)? $checked_prop : '') . ' />'.$v.'</label>'."\n";
			}
		}

		//prepare subsets of selected font
		if(sizeof($subsets) > 0){
			//make array of selected subsets
			$used_subsets = isset($font_parts[2])? explode(',', $font_parts[2]) : array();

			foreach( $subsets as $s ) {
				$subsets_html .= '<label><input type="checkbox" name="subset" value="'.$s.'"' . (in_array($s, $used_subsets)? $checked_prop : '') . ' />'.$s.'</label>'."\n";
			}
		}

		?>
		<div class="font-input">
			<label for="<?php echo esc_attr( $this->id ); ?>">

				<?php if ( ! empty( $this->label ) ) : ?>
					<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<?php endif;
				if ( ! empty( $this->description ) ) : ?>
					<span class="description customize-control-description"><?php echo esc_attr($this->description); ?></span>
				<?php endif; ?>

			</label>
			<div class="input-desc">

				<input id="<?php echo esc_attr( $this->id ); ?>" class="font-request" type="text" value="<?php echo esc_attr($value); ?>" <?php $this->link(); ?> />
				<input class="sample-text" type="text" value="<?php echo esc_attr($sample_text); ?>" />
				<span class="sample-view" style="font-family: <?php echo esc_attr($font_name); ?>;"><?php echo $sample_text; ?></span>
				<p class="desc"><?php a13_be('Double click on sample text to edit it. After edit double click on input to see preview again.'); ?></p>

				<div class="input-tip">
					<span class="hover">?</span>
					<p class="tip"><?php a13_be( 'If you choose <strong>classic font</strong> then remember that this setting depends on fonts installed on client device.<br />'.
					                             'If you choose <strong>google font</strong> then remember to choose needed variants and subsets. Read more in documentation.<br />'.
					                             'For preview google font is loaded with variants regular and 700, and all available subsets.'); ?></p>
				</div>
				<select class="fonts-choose first-load">
					<?php echo $options; ?>
				</select>

				<div class="font-info clearfix">
					<div>
						<h4><?php a13_be( 'Variants' ) ?></h4>
						<div class="variants">
							<?php echo $variants_html; ?>
						</div>
					</div>
					<div>
						<h4><?php a13_be( 'Subsets' ) ?></h4>
						<div class="subsets">
							<?php echo $subsets_html; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
}
