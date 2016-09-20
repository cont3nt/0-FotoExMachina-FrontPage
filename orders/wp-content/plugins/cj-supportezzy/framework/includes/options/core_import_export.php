<?php
/**
 * @package CSSJockey WordPress Framework
 * @author Mohit Aneja (cssjockey.com)
 * @version FW-20150208
*/

global $wpdb;
$options_table = cjsupport_item_info('options_table');
$settings = $wpdb->get_results("SELECT * FROM $options_table");
$export_settings = urlencode(serialize($settings));

$cjsupport_form_options['export_settings'] = array(
	array(
		'type' => 'sub-heading',
		'id' => 'export_heading',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => sprintf(__('Export %s Settings', 'cjsupport'), ucwords(cjsupport_item_info('item_type'))),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'textarea-readonly',
		'id' => 'export_settings',
		'label' => __('Export Settings', 'cjsupport'),
		'info' => __('Copy the contents and save it in a text file.', 'cjsupport'),
		'suffix' => '',
		'prefix' => '',
		'default' => cjsupport_post_default('export_settings', $export_settings),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
		'params' => ' onclick="this.focus();this.select()" ',
	),
);


cjsupport_admin_form_raw($cjsupport_form_options['export_settings']);




if(isset($_POST['do_import_settings'])){
	$import_settings = unserialize(urldecode($_POST['import_settings']));
	if(is_array($import_settings)){
		$wpdb->query("TRUNCATE TABLE {$options_table}");
		foreach ($import_settings as $key => $value) {
			$insert_data = array(
				'option_name' => $value->option_name,
				'option_value' => $value->option_value,
			);
			cjsupport_insert($options_table, $insert_data);
		}
		$location = cjsupport_callback_url();
		wp_redirect( $location, $status = 302 );
		exit;
	}
}


$cjsupport_form_options['import_settings'] = array(
	array(
		'type' => 'heading',
		'id' => 'import_heading',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => sprintf(__('Import %s Settings', 'cjsupport'), ucwords(cjsupport_item_info('item_type'))),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'textarea',
		'id' => 'import_settings',
		'label' => __('Import Settings', 'cjsupport'),
		'info' => __('Paste the previously copied settings and click Import Settings.', 'cjsupport'),
		'suffix' => '',
		'prefix' => '',
		'default' => '',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'submit',
		'id' => 'do_import_settings',
		'label' => __('Import Settings', 'cjsupport'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => '',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
);

echo '<form class="margin-30-top" action="'.admin_url('admin.php?page='.@$_GET['page'].'&callback='.@$_GET['callback'].'').'" method="post" enctype="multipart/form-data">';
cjsupport_admin_form_raw($cjsupport_form_options['import_settings']);
echo '</form>';