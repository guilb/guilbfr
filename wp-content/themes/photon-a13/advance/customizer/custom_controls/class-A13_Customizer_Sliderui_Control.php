<?php

class A13_Customizer_Sliderui_Control extends WP_Customize_Control {

	public $type = 'slider';
	public $min = '';
	public $max = '';
	public $unit = '';

	public function enqueue() {
		wp_enqueue_script( 'jquery-ui-slider' );
	}

	public function render_content() { ?>
		<label for="<?php echo esc_attr( $this->id ); ?>">

			<?php if ( ! empty( $this->label ) ) : ?>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<?php endif;
			if ( ! empty( $this->description ) ) : ?>
				<span class="description customize-control-description"><?php echo $this->description; ?></span>
			<?php endif; ?>

		</label>

		<div class="input-tip">
			<span class="hover">?</span>
			<p class="tip"><?php a13_be( 'Use slider to set proper value. You can click on slider handle and then use arrows keys(on keyboard) to adjust value precisely. You can also type in input value that is in/out of range of slider, and it will be used.' ); ?></p>
		</div>
		<input id="<?php echo esc_attr( $this->id ); ?>" class="slider-dump" type="text" value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->link(); ?> />
		<div class="slider-place" data-min="<?php echo esc_attr($this->min); ?>" data-max="<?php echo esc_attr($this->max); ?>" data-unit="<?php echo esc_attr($this->unit); ?>"></div>


		<?php

	}
}
