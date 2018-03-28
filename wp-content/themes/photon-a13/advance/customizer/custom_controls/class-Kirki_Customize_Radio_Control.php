<?php

class Kirki_Customize_Radio_Control extends WP_Customize_Control {

	public $type = 'radio';
	public $mode = 'radio';

	public function enqueue() {

		if ( 'buttonset' == $this->mode || 'image' == $this->mode ) {
			wp_enqueue_script( 'jquery-ui-button' );
		}

	}

	public function render_content() {

		if ( empty( $this->choices ) ) {
			return;
		}

		$name = '_customize-radio-' . $this->id;

		?>
		<label for="<?php echo esc_attr( $this->id ); ?>">

			<?php if ( ! empty( $this->label ) ) : ?>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<?php endif;
			if ( ! empty( $this->description ) ) : ?>
				<span class="description customize-control-description"><?php echo $this->description; ?></span>
			<?php endif; ?>

		</label>

		<div id="input_<?php echo esc_attr($this->id); ?>" class="<?php echo esc_attr($this->mode); ?>">
			<?php

			// JqueryUI Button Sets
			if ( 'buttonset' == $this->mode ) {

				foreach ( $this->choices as $value => $label ) : ?>
					<input type="radio" value="<?php echo esc_attr( $value ); ?>" name="<?php echo esc_attr( $name ); ?>" id="<?php echo esc_attr($this->id . $value); ?>" <?php $this->link(); checked( $this->value(), $value ); ?>>
						<label for="<?php echo esc_attr($this->id . $value); ?>">
							<?php echo esc_html( $label ); ?>
						</label>
					</input>
					<?php
				endforeach;

			// Image radios.
			} elseif ( 'image' == $this->mode ) {

				foreach ( $this->choices as $value => $label ) : ?>
					<input class="image-select" type="radio" value="<?php echo esc_attr( $value ); ?>" name="<?php echo esc_attr( $name ); ?>" id="<?php echo esc_attr($this->id . $value); ?>" <?php $this->link(); checked( $this->value(), $value ); ?>>
						<label for="<?php echo esc_attr($this->id . $value); ?>">
							<img src="<?php echo esc_html( $label ); ?>">
						</label>
					</input>
					<?php
				endforeach;

			// Normal radios
			} else {

				foreach ( $this->choices as $value => $label ) :
					?>
					<label class="customizer-radio">
						<input class="kirki-radio" type="radio" value="<?php echo esc_attr( $value ); ?>" name="<?php echo esc_attr( $name ); ?>" <?php $this->link(); checked( $this->value(), $value ); ?> />
						<?php echo esc_html( $label ); ?><br/>
					</label>
					<?php
				endforeach;

			}
			?>
		</div>
		<?php if ( 'buttonset' == $this->mode || 'image' == $this->mode ) { ?>
			<script>
			jQuery(document).ready(function($) {
				$( '[id="input_<?php echo esc_attr($this->id); ?>"]' ).buttonset();
			});
			</script>
		<?php }

	}
}
