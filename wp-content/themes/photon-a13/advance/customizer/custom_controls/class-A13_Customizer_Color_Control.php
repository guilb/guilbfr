<?php
class A13_Customizer_Color_Control extends WP_Customize_Control {
	public $type = 'alphacolor';
	//public $palette = '#3FADD7,#555555,#666666, #F5f5f5,#333333,#404040,#2B4267';
	public $palette = true;
	public $default = '#3FADD7';

	public function enqueue() {
		wp_enqueue_script( 'a13-alphacolor-admin' );
		wp_enqueue_style( 'a13-alphacolor-admin' );
	}

	protected function render() {
		$id = 'customize-control-' . str_replace( '[', '-', str_replace( ']', '', $this->id ) );
		$class = 'customize-control customize-control-' . $this->type; ?>
		<li id="<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( $class ); ?>">
			<?php $this->render_content(); ?>
		</li>
	<?php }

	public function render_content() { ?>
		<label>
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<input type="text" data-palette="<?php echo esc_attr($this->palette); ?>" data-default-color="<?php echo esc_attr($this->default); ?>" value="<?php echo intval( $this->value() ); ?>" class="pluto-color-control" <?php $this->link(); ?>  />
		</label>
	<?php }
}

