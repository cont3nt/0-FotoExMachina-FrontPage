<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $cjsupport_item_vars, $wpdb;

/*** Do not change anything in this file unless you know what you are doing ***/

# Item info
####################################################################################################
$cjsupport_item_vars['item_info'] = array(
	'item_type' => 'plugin', // plugin or theme
	'item_id' => 'R4U5IFSA34', // Unique ID of the item
	'item_name' => 'SupportEzzy',
	'item_version' => cjsupport_version,
	'text_domain' => 'cjsupport',
	'options_table' => $wpdb->prefix.'cjsupport_options',
	'addon_tables' => null,
	'page_title' => 'SupportEzzy',
	'menu_title' => 'SupportEzzy',
	'page_slug' => 'cjsupport',

	'license_url' => 'http://cssjockey.com/terms-of-use',
	'api_url' => 'http://api.cssjockey.com',

	'quick_start_guide_url' => 'http://docs.cssjockey.com/cjsupport/quick-start-guide/',
	'documentation_url' => 'http://docs.cssjockey.com/cjsupport',
	'support_forum_url' => 'http://support.cssjockey.com',
	'feature_request_url' => 'http://support.cssjockey.com',
	'report_bugs_url' => 'http://support.cssjockey.com',
);


# Dropdown items
####################################################################################################
$cjsupport_item_vars['dropdown'] = array(
	'app_settings' => array(
		'cjsupport_app_configuration' => __('Basic Configuration', 'cjsupport'),
		'cjsupport_app_styles' => __('Color Scheme & Styles', 'cjsupport'),
		'cjsupport_app_sidebar' => __('Sidebar Settings', 'cjsupport'),
		'cjsupport_app_ticket_priority' => __('Ticket Priority', 'cjsupport'),
		'cjsupport_custom_css' => __('Custom CSS Code', 'cjsupport'),
	),
	'cjsupport_form_builder' => __('Customize Form', 'cjsupport'),
	'cjsupport_choose_staff' => __('Manage Support Staff', 'cjsupport'),
	'customize_email_messages' => array(
		'cjsupport_emails_variables' => __('Dynamic Variables', 'cjsupport'),
		'cjsupport_emails_new_ticket' => __('New Ticket', 'cjsupport'),
		'cjsupport_emails_new_comment' => __('New Comment', 'cjsupport'),
		'cjsupport_emails_update_comment' => __('Update Comment', 'cjsupport'),
		'cjsupport_emails_ticket_status' => __('Ticket Status Change', 'cjsupport'),
		'cjsupport_emails_ticket_transfer' => __('Ticket Transfered', 'cjsupport'),
		'cjsupport_emails_auto_close' => __('Ticket Auto Close', 'cjsupport'),
		'cjsupport_emails_unattended' => __('Unattended Tickets', 'cjsupport'),
		'cjsupport_emails_new_faq' => __('New FAQ', 'cjsupport'),
		'cjsupport_emails_new_user' => __('New User', 'cjsupport'),
	),
	'cjsupport_email_routes' => __('IMAP Configuration', 'cjsupport'),
	'integration' => array(
		'cjsupport_woocommerce' => __('Woocommerce', 'cjsupport'),
		'cjsupport_envato' => __('Envato Marketplace', 'cjsupport'),
	),
);

$cjsupport_item_vars["localize_variables"] = array();

# Option Files
####################################################################################################
$cjsupport_item_vars['option_files'] = array(
	'plugin_addon_options',

	'cjsupport_app_configuration',
	'cjsupport_custom_css',
	'cjsupport_app_styles',
	'cjsupport_app_sidebar',
	'cjsupport_choose_staff',
	'cjsupport_emails_variables',
	'cjsupport_emails_new_ticket',
	'cjsupport_emails_new_comment',
	'cjsupport_emails_update_comment',
	'cjsupport_emails_ticket_status',
	'cjsupport_emails_ticket_transfer',
	'cjsupport_emails_new_faq',
	'cjsupport_emails_new_user',
	'cjsupport_emails_auto_close',
	'cjsupport_emails_unattended',
	'cjsupport_woocommerce',
	'cjsupport_envato',
	'cjsupport_app_ticket_priority',
);

# Load Modules
####################################################################################################
$cjsupport_item_vars['modules'] = array(
	'functions/global',
	'shortcodes/global',
	'widgets/global',
	'helpers/global',
	'functions/item-assistant',

	'functions/ajax',
	'functions/post_columns',
	'functions/docs-faqs-metabox',
	'functions/department-emails',
	'functions/canned-responses',
	'functions/autoclose_tickets',

	'shortcodes/supportezzy-app',
	'shortcodes/supportezzy-faqs',

	// Integrations
	'functions/woocommerce',
	'functions/envato',

);


# Load Extras
####################################################################################################
$cjsupport_item_vars['load_extras'] = array();


# Sidebar Vars
####################################################################################################
$cjsupport_item_vars['sidebar_vars'] = array(
	'before_widget' => '<div id="%1$s" class="widget %2$s">',
	'after_widget' => '</div>',
	'before_title' => '<h3 class="title">',
	'after_title' => '</h3>',
);


# Theme Nav Menus
####################################################################################################
//$cjsupport_item_vars['nav_menus'] = array();
$cjsupport_item_vars['nav_menus'] = array(
	'cjsupport_agents_menu' => __('Agents Only Menu (SupportEzzy)', 'cjsupport'),
	'cjsupport_clients_menu' => __('Clients Only Menu (SupportEzzy)', 'cjsupport'),
	'cjsupport_visitors_menu' => __('Visitors Only Menu (SupportEzzy)', 'cjsupport'),
);


# Database Tables
####################################################################################################
$cjtheme_charset_collate = $wpdb->get_charset_collate();
$options_table = $cjsupport_item_vars['item_info']['options_table'];
$cjsupport_item_vars['db_tables']['sql'] = "
	CREATE TABLE IF NOT EXISTS `{$options_table}` (
        `option_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
        `option_name` varchar(64) NOT NULL DEFAULT '',
        `option_value` longtext NOT NULL,
        PRIMARY KEY (`option_id`),
        UNIQUE KEY `option_name` (`option_name`)
    ) $cjtheme_charset_collate ;
";

$form_fields = $wpdb->prefix.'cjsupport_form_fields';
$cjsupport_item_vars['db_tables']['cjsupport_form_fields'] = "
	CREATE TABLE IF NOT EXISTS `{$form_fields}` (
        `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
        `field_type` VARCHAR(100) NOT NULL DEFAULT '',
        `field_id` VARCHAR(100) NOT NULL DEFAULT '',
        `field_label` VARCHAR(200) NOT NULL DEFAULT '',
        `field_info` TEXT NOT NULL DEFAULT '',
        `field_options` TEXT NOT NULL DEFAULT '',
        `field_order` INT(11) NOT NULL DEFAULT 0,
        `field_required` INT(1) NOT NULL DEFAULT 0,
        PRIMARY KEY (`id`),
        UNIQUE KEY `field_id` (`field_id`)
    ) $cjtheme_charset_collate ;
";

$canned_responses = $wpdb->prefix.'cjsupport_canned_responses';
$cjsupport_item_vars['db_tables']['cjsupport_canned_responses'] = "
	CREATE TABLE IF NOT EXISTS `{$canned_responses}` (
        `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
        `user_id` bigint(20) NOT NULL,
        `response_name` varchar(64) NOT NULL DEFAULT '',
        `response_text` LONGTEXT NOT NULL,
        `dated` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        UNIQUE KEY `response_name` (`response_name`)
    ) $cjtheme_charset_collate ;
";

$ticket_ratings = $wpdb->prefix.'cjsupport_ticket_ratings';
$cjsupport_item_vars['db_tables']['cjsupport_ticket_ratings'] = "
	CREATE TABLE IF NOT EXISTS `{$ticket_ratings}` (
        `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
        `user_id` bigint(20) NOT NULL,
        `ticket_id` bigint(20) NOT NULL,
        `comment_id` bigint(20) NOT NULL,
        `rating` INT(10) NOT NULL,
        `total` INT(10) NOT NULL,
        `dated` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`)
    ) $cjtheme_charset_collate ;
";

# Recommended or Required Plugins
####################################################################################################
$cjsupport_item_vars['install_plugins'] = array();


# Custom Post Types
####################################################################################################
//$cjsupport_item_vars['custom_post_types'] = array();
$cjsupport_item_vars['custom_post_types']['cjsupport'] = array(
	'labels' => array(
		'name' => __('Tickets', 'cjsupport'),
		'singular_name' => __('Ticket', 'cjsupport'),
		'add_new' => __('Add New', 'cjsupport'),
		'add_new_item' => __('Add New Ticket', 'cjsupport'),
		'edit_item' => __('Edit Ticket', 'cjsupport'),
		'new_item' => __('New Ticket', 'cjsupport'),
		'view_item' => __('View Ticket', 'cjsupport'),
		'search_items' => __('Search Ticket', 'cjsupport'),
		'not_found' => __('No Ticket found', 'cjsupport'),
		'not_found_in_trash' => __('No Ticket found in Trash', 'cjsupport'),
		'parent_item_colon' => ''
	),
	'args' => array(
		'exclude_from_search' => true,
		'public' => false,
		'publicly_queryable' => false,
		'show_ui' => true,
		'show_in_menu' => true,
		'has_archive' => true,
		'query_var' => true,
		'rewrite' => array( 'slug' => 'tickets' ),
		'capability_type' => 'post',
		'taxonomies' => array(),
		'hierarchical' => false,
		'menu_position' => 4,
		'menu_icon' => null,
		'supports' => array('title', 'editor', 'custom-fields', 'excerpt', 'comments')
	)
);

$cjsupport_item_vars['custom_post_types']['cjsupport_faqs'] = array(
	'labels' => array(
		'name' => __('FAQs', 'cjsupport'),
		'singular_name' => __('FAQ', 'cjsupport'),
		'add_new' => __('Add New', 'cjsupport'),
		'add_new_item' => __('Add New FAQ', 'cjsupport'),
		'edit_item' => __('Edit FAQ', 'cjsupport'),
		'new_item' => __('New FAQ', 'cjsupport'),
		'view_item' => __('View FAQ', 'cjsupport'),
		'search_items' => __('Search FAQ', 'cjsupport'),
		'not_found' => __('No FAQ found', 'cjsupport'),
		'not_found_in_trash' => __('No FAQ found in Trash', 'cjsupport'),
		'parent_item_colon' => ''
	),
	'args' => array(
		'exclude_from_search' => true,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'has_archive' => true,
		'query_var' => true,
		'rewrite' => array( 'slug' => 'faqs' ),
		'capability_type' => 'page',
		'can_export' => true,
		'taxonomies' => array(),
		'hierarchical' => false,
		'menu_position' => 4,
		'menu_icon' => null,
		'supports' => array('title', 'editor', 'page-attributes')
	)
);


# Custom Taxonomies
####################################################################################################
//$cjsupport_item_vars['custom_taxonomies'] = array();
$cjsupport_item_vars['custom_taxonomies']['cjsupport_departments'] = array(
	'labels' => array(
		'name' => __('Departments', 'cjsupport'),
	    'singular_name' => __('Department', 'cjsupport'),
	    'search_items' => __('Search Department', 'cjsupport'),
	    'all_items' => __('All Departments', 'cjsupport'),
	    'parent_item' => __('Parent Department', 'cjsupport'),
	    'parent_item_colon' => __('Parent Department:', 'cjsupport'),
	    'edit_item' => __('Edit Department', 'cjsupport'),
	    'update_item' => __('Update Department', 'cjsupport'),
	    'add_new_item' => __('Add New Department', 'cjsupport'),
	    'new_item_name' => __('New Department', 'cjsupport'),
	),
    'args' => array(
    	'hierarchical'      => true,
    	'show_ui'           => true,
    	'show_admin_column' => true,
    	'query_var'         => true,
    	'rewrite'           => array( 'slug' => 'departments' , 'with_front' => false ),
    ),
    'post_types' => array('cjsupport')
);

$cjsupport_item_vars['custom_taxonomies']['cjsupport_products'] = array(
	'labels' => array(
		'name' => __('Products', 'cjsupport'),
	    'singular_name' => __('Products', 'cjsupport'),
	    'search_items' => __('Search Products', 'cjsupport'),
	    'all_items' => __('All Products', 'cjsupport'),
	    'parent_item' => __('Parent Products', 'cjsupport'),
	    'parent_item_colon' => __('Parent Products:', 'cjsupport'),
	    'edit_item' => __('Edit Products', 'cjsupport'),
	    'update_item' => __('Update Products', 'cjsupport'),
	    'add_new_item' => __('Add New Products', 'cjsupport'),
	    'new_item_name' => __('New Products', 'cjsupport'),
	),
    'args' => array(
    	'hierarchical'      => true,
    	'show_ui'           => true,
    	'show_admin_column' => true,
    	'query_var'         => true,
    	'rewrite'           => array( 'slug' => 'products' , 'with_front' => false ),
    ),
    'post_types' => array('cjsupport', 'cjsupport_docs', 'cjsupport_faqs')
);