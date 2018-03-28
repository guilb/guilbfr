<?php
if(!function_exists('a13_footer_msg')){
	function a13_footer_msg() {
		global $a13_apollo13;

		$msg_on     = $a13_apollo13->get_option( 'appearance', 'footer_msg' ) === 'on';
		$msg_txt    = do_shortcode($a13_apollo13->get_option( 'appearance', 'footer_msg_text' ));

		if($msg_on): ?>
			<div class="msg footer-msg">
				<?php echo '<div class="msg_text">'.$msg_txt.'</div>'; ?>
			</div>
		<?php endif;
	}
}


if(!function_exists('a13_footer_widgets')) {
	function a13_footer_widgets() {
		global $a13_apollo13;

		$msg_on = $a13_apollo13->get_option( 'appearance', 'footer_msg' ) === 'on';
		$columns = 0;
		for ( $i = 1; $i <= 5; $i ++ ) {
			if ( is_active_sidebar( 'footer-widget-area-' . $i ) ) {
				$columns ++;
			}
		}
		//is there any widgets
		if ( $columns > 0 || $msg_on ) {
			//class for widgets
			$_class = '';
			if ( $columns === 1 ) {
				$_class = ' one-col';
			} elseif ( $columns === 2 ) {
				$_class = ' two-col';
			} elseif ( $columns === 3 ) {
				$_class = ' three-col';
			} elseif ( $columns === 4 ) {
				$_class = ' four-col';
			} elseif ( $columns === 5 ) {
				$_class = ' five-col';
			}

			//color of sidebar
			$_class .= ' '.$a13_apollo13->get_option( 'appearance', 'footer_widgets_color' );

			echo '<div class="foot-widgets' . $_class . '">';
            echo '<div class="foot-content clearfix">';
			a13_footer_msg();

			for ( $i = 1, $j = 0; $i <= 5; $i ++ ) {
				if ( is_active_sidebar( 'footer-widget-area-' . $i ) ) {
					$j ++;
					echo '<div class="col col-' . $j . '">';
					dynamic_sidebar( 'footer-widget-area-' . $i );
					echo '</div>';
				}
			}

			echo '</div>
		                    </div>';
		}
	}
}


if(!function_exists('a13_footer_items')) {
	function a13_footer_items() {
		global $a13_apollo13; ?>
			<div class="foot-items clearfix">
                <?php
                echo '<div class="f-texts clearfix">';
                //footer text
                $ft = $a13_apollo13->get_option( 'appearance', 'footer_text' );
                if(!empty($ft)){
                    echo '<div class="foot-text">'.nl2br($ft).'</div>';
                }

                //footer menu
                echo '<div class="f-links">';


                $music = $a13_apollo13->get_option( 'settings', 'music' ) === 'on';
                if($music){
	                $ids = array();
	                $current_id = '';
	                for( $i = 1; $i <= 5; $i++ ){
		                $temp = $a13_apollo13->get_option( 'settings', 'song_'.$i );
		                if(strlen($temp)){
			                $current_id = pippin_get_attachment_id($temp);
			                if($current_id !== 0){
			                    $ids[] = pippin_get_attachment_id($temp);
			                }
		                }
	                }

	                $tracks_no = sizeof($ids);
	                if($tracks_no){
		                $rand = (bool)$a13_apollo13->get_option( 'settings', 'music_random' );
		                if($rand){ shuffle($ids); }

		                echo '<div class="f-audio'.($tracks_no === 1? ' one-track' : '').'">'. a13_playlist( $ids ).'</div>';
	                }
                }


                echo $a13_apollo13->get_option( 'appearance', 'footer_socials' ) === 'on'? a13_social_icons($a13_apollo13->get_option( 'appearance', 'footer_socials_color' )) : '';
                echo '</div>';

                echo '<span class="icon-switch" id="f-switch"></span>';
                echo '</div>';
                ?>
			</div>
		<?php
	}
}
