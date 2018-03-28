<?php

class A13_Customizer_Reset_Cookie_Control extends WP_Customize_Control {

	public $type = 'reset_cookie';


	public function render_content() { ?>
		<label for="<?php echo esc_attr( $this->id ); ?>">

			<?php if ( ! empty( $this->label ) ) : ?>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<?php endif;
			if ( ! empty( $this->description ) ) : ?>
				<span class="description customize-control-description"><?php echo $this->description; ?></span>
			<?php endif; ?>

		</label>

		<div class="reset_cookie">
			<button class="a13_reset_cookie"><?php a13_be('Reset cookie'); ?></button>
			<input id="<?php echo esc_attr( $this->id ); ?>" type="text" readonly="readonly" value="<?php echo esc_attr($this->value()); ?>" <?php $this->link(); ?> />
		</div>
	<?php
	}
}
