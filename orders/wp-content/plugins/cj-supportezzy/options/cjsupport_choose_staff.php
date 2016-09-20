<?php
global $cjsupport_item_options;
$admin_email = get_option('admin_email');
$admin_id = cjsupport_user_info($admin_email, 'ID');

$cjsupport_item_options['cjsupport_choose_staff'] = array(
	array(
		'type' => 'heading',
		'id' => 'cjsupport_staff_heading',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => __('Choose Support Staff', 'cjsupport'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'users',
		'id' => 'support_staff',
		'label' => __('Support Staff', 'cjsupport'),
		'info' => sprintf(__('<p>Select users who will act as a support agents.</p>', 'cjsupport'), cjsupport_callback_url('cjsupport_manage_staff')),
		'suffix' => '',
		'prefix' => '',
		'default' => '',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'user',
		'id' => 'fallback_support_staff',
		'label' => __('Fallback Support User', 'cjsupport'),
		'info' => __('Select fallback user to assign a tickt where system does not find agent for products and departments.', 'cjsupport'),
		'suffix' => '',
		'prefix' => '',
		'default' => '',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'submit',
		'id' => '',
		'label' => __('Save Settings', 'cjsupport'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => '',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'info',
		'id' => 'support_staff_manage_link',
		'label' => __('Manage Staff', 'cjsupport'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => sprintf(__('<a class="btn btn-success" href="%s">Assign Departments & Products &rightarrow;</a>', 'cjsupport'), cjsupport_callback_url('cjsupport_manage_staff')),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
);