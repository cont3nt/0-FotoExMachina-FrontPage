<?php
/*
Plugin Name: CSSJockey SupportEzzy
Plugin URI: http://www.cssjockey.com/shop/wordpress-plugin/supportezzy/
Description: SupportEzzy is an elegant support tickets system and faqs portal for WordPress. This is a stand-alone AngularJS app which runs on a single WordPress page of your website. This app does not interfere with your existing theme and plugins and will work with any kind of WordPress website.
Author: Mohit Aneja (CSSJockey)
Version: 1.4.2
Author URI: http://CSSJockey.com/
Text Domain: cjsupport
*/
ob_start();

define('cjsupport_version', '1.4.2');

function cjsupport_load_textdomain() {
	$lang_path = dirname( plugin_basename( __FILE__ ) ) . '/languages/';
	load_plugin_textdomain( 'cjsupport', false, $lang_path );
}
add_action( 'init', 'cjsupport_load_textdomain');

require_once('item_setup.php');
require_once(sprintf('%s/framework/framework.php', dirname(__FILE__)));

do_action('cjsupport_functions');

function upload_files(){
	require_once('upload-file.php');
}
add_action('init', 'upload_files');
