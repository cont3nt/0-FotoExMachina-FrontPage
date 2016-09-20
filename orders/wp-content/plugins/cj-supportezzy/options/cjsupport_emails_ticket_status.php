<?php
global $cjsupport_item_options, $cjsupport_email_messages;

$email_messages_file = cjsupport_item_path('item_dir').'/modules/functions/email-messages.php';
require_once($email_messages_file);

$enable_disable_array = array(
	'enable' => __('Enable', 'cjsupport'),
	'disable' => __('Disable', 'cjsupport'),
);

$cjsupport_item_options['cjsupport_emails_ticket_status'] = array(
	array(
		'type' => 'heading',
		'id' => 'cjsupport_emails_ticket_status_heading',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => __('Ticket Status Change Email Messages', 'cjsupport'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'info-full',
		'id' => 'cjsupport_emails_ticket_status_info',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => __('<p>Following emails will be sent to the agent and client when a comment is updated on a ticket.</p>', 'cjsupport'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'sub-heading',
		'id' => 'cjsupport_emails_ticket_status_closed_heading',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => __('When ticket is closed', 'cjsupport'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'textarea',
		'id' => 'ticket_closed_subject_to_agent',
		'label' => __('Subject for Agent', 'cjsupport'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => $cjsupport_email_messages['ticket_closed_subject_to_agent'],
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'wysiwyg',
		'id' => 'ticket_closed_to_agent',
		'label' => __('Message for Agent', 'cjsupport'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => $cjsupport_email_messages['ticket_closed_to_agent'],
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'textarea',
		'id' => 'ticket_closed_subject_to_client',
		'label' => __('Subject for Client', 'cjsupport'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => $cjsupport_email_messages['ticket_closed_subject_to_client'],
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'wysiwyg',
		'id' => 'ticket_closed_to_client',
		'label' => __('Message for Client', 'cjsupport'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => $cjsupport_email_messages['ticket_closed_to_client'],
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'sub-heading',
		'id' => 'cjsupport_emails_ticket_status_reopened_heading',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => __('When ticket is re-opened', 'cjsupport'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'textarea',
		'id' => 'ticket_reopened_subject_to_agent',
		'label' => __('Subject for Agent', 'cjsupport'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => $cjsupport_email_messages['ticket_reopened_subject_to_agent'],
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'wysiwyg',
		'id' => 'ticket_reopened_to_agent',
		'label' => __('Message for Agent', 'cjsupport'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => $cjsupport_email_messages['ticket_reopened_to_agent'],
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'textarea',
		'id' => 'ticket_reopened_subject_to_client',
		'label' => __('Subject for Client', 'cjsupport'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => $cjsupport_email_messages['ticket_reopened_subject_to_client'],
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'wysiwyg',
		'id' => 'ticket_reopened_to_client',
		'label' => __('Message for Client', 'cjsupport'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => $cjsupport_email_messages['ticket_reopened_to_client'],
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