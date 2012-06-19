<?php 
/*-------------------------------------------------------------------
Plugin Name: Translator Box
Plugin URI: http://www.translatorbox.com/
Description: This plugin adds translation functionality to posts and pages. For information how to use it please check the <a href="options-general.php?page=translation_box_options" title="Translation Box">Help page</a>
Author: Ivan Kazandzhiev
Version: 0.1
Author URI: http://www.kazandjiev.com/
--------------------------------------------------------------------*/

require_once('includes.php');

add_action('wp_enqueue_scripts', 'my_scripts');
add_shortcode('translation_box','add_translation');
add_action('admin_menu','translation_box_options');
add_action( 'wp_ajax_nopriv_tr-box-request', 'myajax_submit' );
add_action( 'wp_ajax_tr-box-request', 'myajax_submit' );
//if you want only logged in users to access this function use this hook
// add_action('wp_ajax_tr_box', 'my_AJAX_processing_function');
// add_action('wp_ajax_nopriv_tr_box', 'my_AJAX_processing_function');

/* EOF */