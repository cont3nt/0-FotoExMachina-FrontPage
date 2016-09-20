<?php

global $wp_query;

$support_page = cjsupport_get_option('page_cjsupport_app');

if(is_page($support_page)){
	require_once('app.php');
    die();
}
if(is_post_type_archive() && get_query_var('post_type') == 'cjsupport'){
	require_once('app.php');
    die();
}
if(is_tax('cjsupport_departments') || is_tax('cjsupport_products')){
	require_once('app.php');
    die();
}
if(is_single() && get_query_var('post_type') == 'cjsupport'){
	require_once('app.php');
    die();
}