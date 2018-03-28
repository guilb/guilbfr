<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

?><!DOCTYPE html>
<!--[if lt IE 9]> <html class="no-js lt-ie10 lt-ie9" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 9]>    <html class="no-js lt-ie10" <?php language_attributes(); ?>> <![endif]-->
<!--[if gt IE 9]><!--> <html class="no-js" <?php language_attributes(); ?>> <!--<![endif]-->
<head>

<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php a13_favicon(); ?>

<?php
    global $a13_apollo13;
    a13_theme_head();
    wp_head();
?>
</head>

<body id="top" <?php body_class(); ?>>
    <?php
    a13_page_preloader();
    a13_page_background();
    a13_theme_header();
    ?>
    <div id="mid" class="to-move <?php echo a13_get_mid_classes(); ?>">