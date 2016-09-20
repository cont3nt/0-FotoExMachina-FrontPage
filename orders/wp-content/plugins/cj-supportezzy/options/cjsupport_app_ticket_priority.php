<?php
global $cjsupport_item_options;

$enable_disable_array = array(
	'enable' => __('Enable', 'cjsupport'),
	'disable' => __('Disable', 'cjsupport'),
);

$cjsupport_item_options['cjsupport_app_ticket_priority'] = array(
	array(
		'type' => 'heading',
		'id' => 'cjsupport_app_ticket_priority_heading',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => __('Ticket Priority Settings', 'cjsupport'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'dropdown',
		'id' => 'mod_ticket_priority',
		'label' => __('Ticket Priority', 'cjsupport'),
		'info' => __('You can choose to enable or disable ticket priority system for this app.', 'cjsupport'),
		'suffix' => '',
		'prefix' => '',
		'default' => 'enable',
		'options' => $enable_disable_array, // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'info-full',
		'id' => 'cjsupport_app_ticket_priority_info',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => __('Here you can manage ticket priority labels and response times based on ticket priority.', 'cjsupport'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'multitextboxes',
		'id' => 'ticket_priority_low',
		'label' => __('Low Priority', 'cjsupport'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => array('Low', '48'),
		'options' => array(
			__('Label (will be used at different locations)', 'cjsupport'),
			__('Response Time (Hours)', 'cjsupport'),
		), // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'multitextboxes',
		'id' => 'ticket_priority_normal',
		'label' => __('Normal Priority', 'cjsupport'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => array('Normal', '24'),
		'options' => array(
			__('Label (will be used at different locations)', 'cjsupport'),
			__('Response Time (Hours)', 'cjsupport'),
		), // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'multitextboxes',
		'id' => 'ticket_priority_high',
		'label' => __('High Priority', 'cjsupport'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => array('High', '12'),
		'options' => array(
			__('Label (will be used at different locations)', 'cjsupport'),
			__('Response Time (Hours)', 'cjsupport'),
		), // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'multitextboxes',
		'id' => 'ticket_priority_urgent',
		'label' => __('Urgent Priority', 'cjsupport'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => array('Urgent', '6'),
		'options' => array(
			__('Label (will be used at different locations)', 'cjsupport'),
			__('Response Time (Hours)', 'cjsupport'),
		), // array in case of dropdown, checkbox and radio buttons
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
);