<?php
/**
 * @package CSSJockey WordPress Framework
 * @author Mohit Aneja (cssjockey.com)
 * @version FW-20150208
*/
$cjsupport_item_info = cjsupport_item_info();

require_once(sprintf('%s/db_setup.php', cjsupport_item_path('includes_dir')));
require_once(sprintf('%s/functions/'.$cjsupport_item_info['item_type'].'_setup.php', cjsupport_item_path('modules_dir')));

function cjsupport_framework_init(){
	require_once(sprintf('%s/widget_options_form.php', cjsupport_item_path('includes_dir')));
	require_once(sprintf('%s/dashboard-widget.php', cjsupport_item_path('includes_dir')));
	require_once(sprintf('%s/bootstrap-walker.php', cjsupport_item_path('includes_dir')));
	require_once(sprintf('%s/admin_ajax.php', cjsupport_item_path('includes_dir')));
	require_once(sprintf('%s/push_notifications.php', cjsupport_item_path('includes_dir')));
}

add_action('cjsupport_functions', 'cjsupport_install_plugins');
add_action('cjsupport_functions', 'cjsupport_load_modules');
add_action('cjsupport_functions', 'cjsupport_shortcode_generator');

add_action( 'init', 'cjsupport_framework_init' );
add_action( 'init', 'cjsupport_register_post_types' );
add_action( 'init', 'cjsupport_register_taxonomies');
add_action( 'init', 'cjsupport_meta_boxes', 9999 );

require_once(sprintf('%s/hooks.php', cjsupport_item_path('includes_dir')));

add_filter('widget_text', 'do_shortcode');
