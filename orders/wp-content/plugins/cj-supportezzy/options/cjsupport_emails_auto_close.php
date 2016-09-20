<?php
global $cjsupport_item_options, $cjsupport_email_messages;

$email_messages_file = cjsupport_item_path('item_dir').'/modules/functions/email-messages.php';
require_once($email_messages_file);

$enable_disable_array = array(
	'enable' => __('Enable', 'cjsupport'),
	'disable' => __('Disable', 'cjsupport'),
);

$cjsupport_item_options['cjsupport_emails_auto_close'] = array(
	array(
		'type' => 'heading',
		'id' => 'cjsupport_emails_auto_close_heading',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => __('Auto Close Notification Message', 'cjsupport'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'dropdown',
		'id' => 'auto_close_emails',
		'label' => __('Auto close notifications', 'cjsupport'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => 'disable',
		'options' => $enable_disable_array, // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'text',
		'id' => 'auto_close_subject_to_client',
		'label' => __('Subject', 'cjsupport'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => $cjsupport_email_messages['auto_close_subject_to_client'],
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'wysiwyg',
		'id' => 'auto_close_message_to_client',
		'label' => __('Message to client', 'cjsupport'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => $cjsupport_email_messages['auto_close_message_to_client'],
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
);