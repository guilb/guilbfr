<?php

class A13_Customizer_Socials_Control extends WP_Customize_Control {

	public $type = 'socials';

	public function enqueue() {
		wp_enqueue_script( 'jquery-ui-sortable' );
	}

	public function render_content() {
		$value = $this->value();
		$original_names = $this->choices;

		$current_settings = json_decode($value, true);

		//if we add new social services to options, then this way they will be available to user
		if(sizeof($original_names) > sizeof($current_settings)){
			$to_append = $original_names; //new copy!

			//checking which one are new services
			foreach($current_settings as $set){
				unset($to_append[$set['id']]);
			}

			//adding new services at end of list
			foreach($to_append as $new_id => $new_val){
				$current_settings[] = array('id' => $new_id, 'link' => '');
			}
		}
		?>

		<label>

			<?php if ( ! empty( $this->label ) ) : ?>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<?php endif;
			if ( ! empty( $this->description ) ) : ?>
				<span class="description customize-control-description"><?php echo $this->description; ?></span>
			<?php endif; ?>

		</label>

		<?php
		//hidden textarea with JSON of all social links
		echo '<textarea class="a13_ss_textarea" rows="10" cols="25" '.$this->get_link().'>'.esc_textarea($value).'</textarea>';
		?>

		<div id="a13_sortable-socials">
            <?php foreach($current_settings as $service): ?>
                <div class="service" data-a13_ss_id="<?php echo esc_attr($service['id']); ?>">
	                <div>
	                    <label for="a13_ss_<?php echo esc_attr($service['id']); ?>" class="social_icon a13_soc-<?php echo esc_attr($service['id']); ?>" title="<?php echo esc_attr($original_names[$service['id']]); ?>"></label>
	                    <input id="a13_ss_<?php echo esc_attr($service['id']); ?>" type="text" size="36" value="<?php echo esc_attr($service['link']); ?>" />
	                </div>
                </div>
            <?php endforeach; ?>
		</div>

		<?php
	}
}
