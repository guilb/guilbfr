<?php
/*
 * Page preloader
 */
if(!function_exists('a13_page_preloader')){
    function a13_page_preloader(){
        global $a13_apollo13;

        if($a13_apollo13->get_option( 'appearance', 'preloader' ) === 'on'){
	        $class_attr = '';
	        if($a13_apollo13->get_option( 'appearance', 'preloader_hide_event' )==='ready'){
		        $class_attr = ' class="onReady"';
	        }
        ?>
<div id="preloader"<?php echo $class_attr; ?>>
    <div class="preload-content">
        <div class="preloader-animation"><?php a13_preloader_animation_html($a13_apollo13->get_option( 'appearance' , 'preloader_type' )); ?></div>
        <div class="preloader-text"><?php a13_header_logo(); ?></div>
        <a class="skip-preloader icon-cross" href="#"></a>
    </div>
</div>
        <?php
        }
    }
}



if(!function_exists('a13_preloader_animation_html')){
	function a13_preloader_animation_html($animation) {
		switch($animation){
			case $animation === 'circle_illusion':
				?>
				<div class='blob-wrap'>
					<div class='translate'>
						<div class='scale'></div>
					</div>
				</div>
				<div class='blob-wrap'>
					<div class='translate'>
						<div class='scale'></div>
					</div>
				</div>
				<div class='blob-wrap'>
					<div class='translate'>
						<div class='scale'></div>
					</div>
				</div>
				<div class='blob-wrap'>
					<div class='translate'>
						<div class='scale'></div>
					</div>
				</div>
				<div class='blob-wrap'>
					<div class='translate'>
						<div class='scale'></div>
					</div>
				</div>
				<div class='blob-wrap'>
					<div class='translate'>
						<div class='scale'></div>
					</div>
				</div>
				<div class='blob-wrap'>
					<div class='translate'>
						<div class='scale'></div>
					</div>
				</div>
				<div class='blob-wrap'>
					<div class='translate'>
						<div class='scale'></div>
					</div>
				</div>
				<div class='blob-wrap'>
					<div class='translate'>
						<div class='scale'></div>
					</div>
				</div>
				<?php
				break;

			case $animation === 'square_of_squares':
				?>
				<div class="sos-load">
					<div class="blockcont">
						<div class="sos-block"></div>
						<div class="sos-block"></div>
						<div class="sos-block"></div>

						<div class="sos-block"></div>
						<div class="sos-block"></div>
						<div class="sos-block"></div>

						<div class="sos-block"></div>
						<div class="sos-block"></div>
						<div class="sos-block"></div>

					</div>
				</div>
				<?php
				break;

			case $animation === 'plus_minus':
				?>
				<div class="pm-top">
					<div class="square">
						<div class="square">
							<div class="square">
								<div class="square">
									<div class="square"><div class="square">

										</div></div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="pm-bottom">
					<div class="square">
						<div class="square">
							<div class="square">
								<div class="square">
									<div class="square"><div class="square">
										</div></div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="pm-left">
					<div class="square">
						<div class="square">
							<div class="square">
								<div class="square">
									<div class="square"><div class="square">
										</div></div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="pm-right">
					<div class="square">
						<div class="square">
							<div class="square">
								<div class="square">
									<div class="square"><div class="square">
										</div></div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php
				break;

			case $animation === 'hand':
				?>
				<div class="hand-loading">
					<div class="finger finger-1">
						<div class="finger-item">
							<span></span><i></i>
						</div>
					</div>
					<div class="finger finger-2">
						<div class="finger-item">
							<span></span><i></i>
						</div>
					</div>
					<div class="finger finger-3">
						<div class="finger-item">
							<span></span><i></i>
						</div>
					</div>
					<div class="finger finger-4">
						<div class="finger-item">
							<span></span><i></i>
						</div>
					</div>
					<div class="last-finger">
						<div class="last-finger-item"><i></i></div>
					</div>
				</div>
				<?php
				break;

			case $animation === 'blurry':
				?>
				<div class="blurry-box"></div>
				<?php
				break;

			case $animation === 'arcs':
				?>
				<div class="arc">
					<div class="arc-cube"></div>
				</div>
				<?php
				break;

			case $animation === 'tetromino':
				?>
				<div class='tetrominos'>
					<div class='tetromino box1'></div>
					<div class='tetromino box2'></div>
					<div class='tetromino box3'></div>
					<div class='tetromino box4'></div>
				</div>
				<?php
				break;

			case $animation === 'infinity':
				?>
				<div class='infinity-container'>
					<div class='inf-lt'></div>
					<div class='inf-rt'></div>
					<div class='inf-lb'></div>
					<div class='inf-rb'></div>
				</div>
				<?php
				break;

			case $animation === 'cloud_circle':
				?>
				<div class='cloud-circle-container'>
					<div class='cloud-circle'>
						<div class='inner'></div>
					</div>
					<div class='cloud-circle'>
						<div class='inner'></div>
					</div>
					<div class='cloud-circle'>
						<div class='inner'></div>
					</div>
					<div class='cloud-circle'>
						<div class='inner'></div>
					</div>
					<div class='cloud-circle'>
						<div class='inner'></div>
					</div>
				</div>
				<?php
				break;

			case $animation === 'dots':
				?>
				<div class='dots-loading'>
					<div class='bullet'></div>
					<div class='bullet'></div>
					<div class='bullet'></div>
					<div class='bullet'></div>
				</div>
				<?php
				break;

			case $animation === 'photon_man':
				?>
				<div class="photon-man-body">
				    <span>
				        <span></span>
				        <span></span>
				        <span></span>
				        <span></span>
				    </span>
					<div class="photon-man-base">
						<span></span>
						<div class="photon-man-face"></div>
					</div>
				</div>
				<div class="longfazers">
					<span></span>
					<span></span>
					<span></span>
					<span></span>
				</div>
				<?php
				break;

			case $animation === 'circle':
				?>
				<div class="circle-loader">Loading...</div>
				<?php
				break;

			default:
				?>
				<div class="pace-progress"><div class="pace-progress-inner"></div ></div>
		        <div class="pace-activity"></div>
				<?php
		}
	}
}



if(!function_exists('a13_page_background')){
    function a13_page_background(){
        ?>
        <div class="page-background to-move"></div>
        <?php
    }
}



if(!function_exists('a13_bg_fit_helper')){
    function a13_bg_fit_helper($option){
        static $options = array(
            'center'     => 'background-size: auto; background-repeat: no-repeat; background-position: 50% 50%;',
            'cover'      => 'background-size: cover; background-repeat: no-repeat; background-position: 0 0;',
            'contain'    => 'background-size: contain; background-repeat: no-repeat; background-position: 50% 50%;',
            'fitV'       => 'background-size: 100% auto; background-repeat: no-repeat; background-position: 50% 50%;',
            'fitH'       => 'background-size:  auto 100%; background-repeat: no-repeat; background-position: 50% 50%;',
            'repeat'     => 'background-repeat: repeat; background-size:auto; background-position: 0 0;',
            'repeat-x'   => 'background-repeat: repeat-x; background-size:auto; background-position: 0 0;',
            'repeat-y'   => 'background-repeat: repeat-y; background-size:auto; background-position: 0 0;',
        );

        return $options[$option];
    }
}



/*
 * Prints search form with custom id for each displayed form one one page
 */
if(!function_exists('a13_search_form')){
    function a13_search_form() {
        static $search_id = 1;
	    global $a13_apollo13;

	    $wpml_active = defined( 'ICL_SITEPRESS_VERSION');
	    $shop_search_option = $a13_apollo13->get_option( 'shop', 'shop_search' );
	    $shop_search = $shop_search_option === 'on';
        $helper_search = get_search_query() == '' ? true : false;
        $field_search = '<input' .
            ' placeholder="' . esc_attr( __('Search &hellip;', 'photon' )) . '" ' .
            'type="search" name="s" id="s' . $search_id . '" value="' .
            esc_attr( $helper_search ? '' : get_search_query() ) .
            '" />';

        $form = '
                <form class="search-form" role="search" method="get" action="' . home_url( '/' ) . '" >
                    <fieldset class="semantic">
                        ' . $field_search . '
                        <input type="submit" id="searchsubmit' . $search_id . '" title="'. esc_attr( __( 'Search', 'photon' ) ) .'" value="'. esc_attr( __( 'Search', 'photon' ) ) .'" />
                        '.($shop_search? '<input type="hidden" value="product" name="post_type">' : '').'
                        '.($wpml_active? ('<input type="hidden" name="lang" value="'.ICL_LANGUAGE_CODE.'"/>') : '').'
                    </fieldset>
                </form>';

        //next call will have different ID
        $search_id++;
        return $form;
    }
}
add_filter( 'get_search_form','a13_search_form' );


/*
 * Page title, Filter and others (ex. RSS for blog)
 */
if(!function_exists('a13_title_bar')){
    function a13_title_bar($outside = true, $title = '', $subtitle = '') {
        global $a13_apollo13;

        $page_type = a13_what_page_type_is_it();
        $home = $page_type['home'];

        $title_bar_option_global = $a13_apollo13->get_option( 'appearance', 'title_bar' ); //global value
        $position_global         = $a13_apollo13->get_option( 'appearance', 'title_bar_position' ); //global value
        $title_bar_option = $position =  $_subtitle = '';
	    $is_password_protected = post_password_required();


        //prepare variables
        //albums
        if($page_type['album'] || $page_type['albums_list']){
            $position = 'outside';
            $_subtitle = $a13_apollo13->get_option( 'album', 'subtitle' );

            if( $a13_apollo13->get_option( 'album', 'custom_title_bar' ) === 'off' ){
                //use settings from global layout
                $title_bar_option   = $title_bar_option_global;
            }
            else{
                //use settings from album layout
                $title_bar_option = $a13_apollo13->get_option( 'album', 'title_bar' );
            }
        }
        //shop
        elseif($page_type['shop']){
            $position = 'outside';
	        $title_bar_option =  $_subtitle = '';
	        $_subtitle = $a13_apollo13->get_option( 'shop', 'subtitle' );

	        if( $a13_apollo13->get_option( 'shop', 'custom_title_bar' ) === 'off' ){
		        //use settings from global layout
		        $title_bar_option   = $title_bar_option_global;
	        }
	        else{
		        //use settings from blog layout
		        $title_bar_option = $a13_apollo13->get_option( 'shop', 'title_bar' );
	        }
        }
        elseif($is_password_protected){
	        $position = 'outside';
        }
        //pages
        elseif($page_type['page']){
            $meta_id = get_the_ID();
            $_subtitle = $a13_apollo13->get_meta('_subtitle', $meta_id);
            $title_bar_option = $a13_apollo13->get_meta('_title_bar_settings', $meta_id);

            //three way check which options to apply to title bar
            if( $title_bar_option === 'global' ){
                if( $a13_apollo13->get_option( 'page', 'custom_title_bar' ) === 'off' ){
                    //use settings from global layout
                    $title_bar_option   = $title_bar_option_global;
                    $position           = $position_global;
                }
                else{
                    //use settings from pages layout
                    $title_bar_option = $a13_apollo13->get_option( 'page', 'title_bar' );
                    $position         = $a13_apollo13->get_option( 'page', 'title_bar_position' );
                }
            }
            else{
                //use settings from this page
                $position = $a13_apollo13->get_meta('_title_bar_position', $meta_id);
            }
        }
        //blog
        elseif(($page_type['blog_type'] || $page_type['post'])){
            $position = 'outside';
            $_subtitle = $a13_apollo13->get_option( 'blog', 'subtitle' );

            if( $a13_apollo13->get_option( 'blog', 'custom_title_bar' ) === 'off' ){
                //use settings from global layout
                $title_bar_option   = $title_bar_option_global;
            }
            else{
                //use settings from blog layout
                $title_bar_option = $a13_apollo13->get_option( 'blog', 'title_bar' );
            }
        }


        //is it OFF?
        if(!a13_is_no_property_page() && !$is_password_protected){ //checks if page can have meta fields
            if($title_bar_option === 'off'){
                return;
            }
        }
        //no outside title in post
        if($page_type['post'] && !$is_password_protected && $a13_apollo13->get_option( 'blog', 'post_outside_title' ) === 'off'){
            return;
        }



        //check in which place we called for title bar(inside/outside content)
        if($position === 'inside' && $outside || $position === 'outside' && !$outside){
            return;
        }


        //subtitle
        $subtitle    = empty($subtitle)? $_subtitle : $subtitle;
        $subtitle_on = strlen($subtitle);

        //title bar classes
		$tb_classes  = $subtitle_on? ' subtitle' : '';
		$tb_classes .= $outside? ' outside' : ' inside';

        ?>
    <header class="title-bar<?php echo esc_attr($tb_classes); ?>">
        <div class="in">
            <?php

            //use passed $title
            if(!empty( $title )){
                //change nothing
            }
            elseif($page_type['shop']){
	            if($page_type['product'] && $a13_apollo13->get_option( 'shop', 'product_outside_title' ) === 'on'){
	                $title = get_the_title();
	            }
	            else{
	                $title = get_the_title(wc_get_page_id( 'shop' ));
	            }
            }
            //album
            elseif ( $page_type['album'] ){
                $title = get_the_title();
            }
            //blog
            elseif ( $home ){
                if(get_option('page_for_posts') === '0'){
                    $title =  __( 'Blog', 'photon' );
                }
                else{
                    $title = get_the_title(get_option('page_for_posts'));
                }
            }
            //pages, blog post, etc.
            else{
                $title = get_the_title();
            }
            echo '<h1 class="page-title">';
            echo $title;//sometimes we add html here, so don't escape!
            echo '</h1>';

            //subtitle
            if($subtitle_on){
                echo '<h2>'.$subtitle.'</h2>';
            }
            ?>
        </div>
    </header>
    <?php
    }
}


/*
 * Prints CSS for title bar
 */
if(!function_exists('a13_page_individual_look')){
    function a13_page_individual_look(){
        global $a13_apollo13;

        //checks if page can have meta fields
        //if not page will use styles defined in user.css
        if(!a13_is_no_property_page()){
            $css = '';
            $page_type = a13_what_page_type_is_it();
            $post = $page_type['post'];
            $body_class = '.page';

            if($page_type['attachment'] || $page_type['blog_type'] || $page_type['album'] || $page_type['albums_list']){ //we use general styles for it
                return;
            }

            //id from where
            $meta_id = false;
            if(is_404() && $a13_apollo13->get_option( 'page', 'page_404_template_type' ) === 'custom' ) {
                $meta_id = $a13_apollo13->get_option( 'page', 'page_404_template' );
            }

            if($page_type['page']){ //404 is not page so it is ok that they are not in the same if-else
                $meta_id = get_the_ID();
            }
            elseif($post){ //404 is not page so it is ok that they are not in the same if-else
                $meta_id = get_the_ID();
                $body_class = '.single-post';
            }


            /***************************************/
            /* PAGE BACKGROUND */
            /***************************************/
            $page_bg_option = $a13_apollo13->get_meta('_page_bg_settings', $meta_id);

            if($page_bg_option === 'custom'){
                $bg_color       = get_post_meta($meta_id, '_page_bg_color', true);
                $bg_image       = get_post_meta($meta_id, '_page_image', true);
                $bg_image_fit   = a13_bg_fit_helper(get_post_meta($meta_id, '_page_image_fit', true));

                $css .= '
                    '.$body_class.' .page-background{
                        background-color:'.$bg_color.';
                        background-image: url('.$bg_image.');
                        '.$bg_image_fit.'
                    }
                ';
            }


            /***************************************/
            /* TOP SPACE */
            /***************************************/
            if(!$post){
                $top_space_option = $a13_apollo13->get_meta('_top_space_settings', $meta_id);

                if($top_space_option === 'custom'){
                    $space      = get_post_meta($meta_id, '_top_space_height', true);
                    $bg_color   = get_post_meta($meta_id, '_top_space_bg_color', true);

                    $css .= '
                        '.$body_class.' #mid:before{
                            height:'.$space.';
                            background-color:'.$bg_color.';
                        }
                    ';
                }
            }



            /***************************************/
            /* TITLE BAR */
            /***************************************/
            if(!$post) {
                $title_bar_option = $a13_apollo13->get_meta( '_title_bar_settings', $meta_id );

                if ( $title_bar_option === 'custom' ) {
                    //where title bar should be displayed
                    $position = $a13_apollo13->get_meta( '_title_bar_position', $meta_id );

                    //we don't style "inside" title bars
                    if ( $position !== 'inside' ) {
                        $bg_color    = get_post_meta( $meta_id, '_title_bar_bg_color', true );
                        $title_color = get_post_meta( $meta_id, '_title_bar_title_color', true );
                        $space       = get_post_meta( $meta_id, '_title_bar_space_width', true );

                        $css .= '
                            '.$body_class.' .title-bar.outside{
                                background-color:' . $bg_color . ';
                            }
                            '.$body_class.' .title-bar.outside .in{
                                padding-top:' . $space . ';
                                padding-bottom:' . $space . ';
                            }
                            '.$body_class.' .title-bar.outside .page-title,
                            '.$body_class.' .title-bar.outside .in h2{
                                color:' . $title_color . ';
                            }
                            '.$body_class.' .title-bar.outside.subtitle .page-title:after{
                                border-color:' . $title_color . ';
                            }
                        ';
                    }
                }
            }

            //if we have some CSS then add it
            if(strlen($css)){
               wp_add_inline_style( 'user-css', $css );
            }
        }
    }
}
add_action( 'wp_enqueue_scripts', 'a13_page_individual_look', 27 );



/*
 * Return HTML for social icons
 */
if(!function_exists('a13_social_icons')){
    function a13_social_icons($bg){
        global $a13_apollo13;


        $socials = json_decode($a13_apollo13->get_option( 'socials', 'social_services' ), true);
        $variant = ' '.$a13_apollo13->get_option( 'socials', 'socials_variant' );
//	    var_dump($socials);
        $soc_html = '';
        $has_active = false;
        $protocols = wp_allowed_protocols();
        $protocols[] = 'skype';

        foreach( $socials as $service ){
            if( ! empty($service['link']) ){
                $soc_html .= '<a target="_blank" href="' . esc_url($service['link'], $protocols) . '" class="a13_soc-'.$service['id'].'"></a>';
                $has_active = true;
            }
        }

        if($has_active){
            $soc_html = '<div class="socials '.$bg.$variant.'">'.$soc_html.'</div>';
        }

        return $soc_html;
    }
}


if(!function_exists('a13_demo_switcher')){
    function a13_demo_switcher(){
        global $a13_apollo13;

        if(0 && a13_is_home_server() && $a13_apollo13->get_option( 'advanced', 'demo_switcher' ) === 'on'){
            $dir = A13_TPL_ADV_DIR.'/demo_settings';
            $sets_html = array();
            $selected_set = isset($_COOKIE["a13_demo_set"])? $_COOKIE["a13_demo_set"] : 'default';
            $order = false;
            $title = esc_attr(sprintf( __( 'Use this switcher to see different possible combinations of settings. You can turn off this switcher in theme options: %s.', 'photon' ), A13_TPL_NAME . ' theme->Advanced->Demo switcher'));

            if( is_dir( $dir ) ) {
                foreach ( (array)glob($dir.'/*') as $file ){
                    $name = basename($file);
                    if($name === '_order'){
                        $order = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                        continue;
                    }
                    $img = A13_TPL_GFX.'/demos/'.$name.'.jpg';
                    $selected = ($name === $selected_set)? ' class="selected"' : '';
                    $sets_html[$name] = '<a href="?a13_demo_set='.urlencode($name).'" title="'.esc_attr($name).'"'.$selected.'><img width="90" height="60" src="'.esc_url($img).'" alt="" /></a>';
                }
            }

            //sort array
            $sets_html = array_merge(array_flip($order), $sets_html);
            //join to string
            $sets_html = implode('', $sets_html);

            if(strlen($sets_html)){
            ?>
            <div id="a13-demo-switcher">
                <span class="before-label"><?php _e( 'Style switcher', 'photon' ); ?><a href="#" class="fa fa-wrench" title="<?php echo esc_attr($title); ?>"></a></span>
                <div class="sets"><?php echo $sets_html; ?></div>
            </div>
            <?php
            }
        }
    }
}

if(!function_exists('a13_page_like_content')){
	function a13_page_like_content(){
	// almost copy of page.php
	the_post();

	get_header();

	a13_title_bar();
	?>

	<article id="content" class="clearfix">
		<div class="content-limiter">
			<div id="col-mask">

				<div id="post-<?php the_ID(); ?>" <?php post_class('content-box'); ?>>
					<?php
					a13_top_image_video();
					?>
					<div class="formatter">
						<?php a13_title_bar(false); ?>
						<div class="real-content">
							<?php the_content(); ?>
							<div class="clear"></div>

							<?php
							wp_link_pages( array(
									'before' => '<div id="page-links">'.__( 'Pages: ', 'photon' ),
									'after'  => '</div>')
							);
							?>
						</div>
					</div>
				</div>
				<?php get_sidebar(); ?>
			</div>
		</div>
	</article>

	<?php get_footer();
	}
}

