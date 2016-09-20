<?php
global $cjsupport_item_options;

$login_message = __('<h2>Login or Register</h2>', 'cjsupport');
$login_message .= __('<p>You must %%login_link%% or %%register_link%% to submit a support ticket.</p>', 'cjsupport');

$enable_disable_array = array(
	'enable' => __('Enable', 'cjsupport'),
	'disable' => __('Disable', 'cjsupport'),
);

$yes_no_array = array(
	'yes' => __('Yes', 'cjsupport'),
	'no' => __('No', 'cjsupport'),
);

$public_private_array = array(
	'public' => __('Public', 'cjsupport'),
	'private' => __('Private', 'cjsupport'),
);

$layout_opitons = array(
	'default' => cjsupport_item_path('item_url').'/options/images/default-layout.png',
	'no-header' => cjsupport_item_path('item_url').'/options/images/sidebar-layout.png',
	'no-logo' => cjsupport_item_path('item_url').'/options/images/blank-layout.png',
);

$communication_array = array(
	'web' => __('<strong>Web Replies</strong>: Enable new ticket creation and replies via web only.', 'cjsupport'),
	'imap' => __('<strong>Email Piping</strong>: Enable new ticket creation and replies via both web and emails.', 'cjsupport'),
);


$cjsupport_item_options['cjsupport_app_sidebar'] = array(
	array(
		'type' => 'heading',
		'id' => 'cjsupport_sidebar_heading',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => __('Sidebar Content', 'cjsupport'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'wysiwyg',
		'id' => 'login_message',
		'label' => __('Login Message or Shortcodes', 'cjsupport'),
		'info' => __('You can specify any content, links or shortcodes to configure the login message displayed.', 'cjsupport'),
		'suffix' => '',
		'prefix' => '',
		'default' => $login_message,
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'wysiwyg',
		'id' => 'sidebar_message_agent',
		'label' => __('For Agents & Admins', 'cjsupport'),
		'info' => __('This content will be displyed to logged in Agents & Admins in the left sidebar.', 'cjsupport'),
		'suffix' => '',
		'prefix' => '',
		'default' => '',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'wysiwyg',
		'id' => 'sidebar_message_client',
		'label' => __('For Clients', 'cjsupport'),
		'info' => __('This content will be displyed to logged in Clients in the left sidebar.', 'cjsupport'),
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
);