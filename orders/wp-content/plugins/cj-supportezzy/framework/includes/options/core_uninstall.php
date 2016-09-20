<?php
/**
 * @package CSSJockey WordPress Framework
 * @author Mohit Aneja (cssjockey.com)
 * @version FW-20150208
*/
global $wpdb;
$installed_table_names = cjsupport_item_info('options_table').'<br>';

if(!is_null(cjsupport_item_info('addon_tables'))){
	$installed_addon_tables = explode(',', cjsupport_item_info('addon_tables'));
	foreach ($installed_addon_tables as $key => $value) {
		$installed_table_names .= $wpdb->prefix.$value.'<br />';
	}
}

$cjsupport_form_options['ncuninstall'] = array(
	array(
		'type' => 'sub-heading',
		'id' => 'ncuninstall_heading',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => sprintf(__('Uninstall %s', 'cjsupport'), ucwords(cjsupport_item_info('item_type'))),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'info-full',
		'id' => 'nctrial_info',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => __('<p>To uninstall this plugin, you can deactivate this plugin and then remove the following tables from your databse.</p>', 'cjsupport').
					$installed_table_names,
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),

);


cjsupport_admin_form_raw($cjsupport_form_options['ncuninstall']);