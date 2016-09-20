<?php
global $cjsupport_item_options;
require_once(ABSPATH.'/wp-includes/pluggable.php');
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


$cjsupport_item_options['cjsupport_app_configuration'] = array(
	array(
		'type' => 'heading',
		'id' => 'cjsupport_configuration_heading',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => __('Page Setup', 'cjsupport'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'page',
		'id' => 'page_cjsupport_app',
		'label' => __('Support App Page', 'cjsupport'),
		'info' => __('<p>Create a page for support and select the same in this dropdown.</p><p>To set support as your homepage, select this page as front page under Settings >> Reading.</p>', 'cjsupport'),
		'suffix' => '',
		'prefix' => '',
		'default' => '',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'screenshots',
		'id' => 'cjsupport_app_layout',
		'label' => __('Support App Layout', 'cjsupport'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => 'default',
		'options' => $layout_opitons, // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'text',
		'id' => 'home_url',
		'label' => __('Home Link Url', 'cjsupport'),
		'info' => __('You can specify a custom Url for the home link.', 'cjsupport'),
		'suffix' => '',
		'prefix' => '',
		'default' => site_url(),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'text',
		'id' => 'login_url',
		'label' => __('Login Url', 'cjsupport'),
		'info' => __('You can specify a custom Url if you use any plugin for login or registration.', 'cjsupport'),
		'suffix' => '',
		'prefix' => '',
		'default' => '',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'text',
		'id' => 'register_url',
		'label' => __('Register Url', 'cjsupport'),
		'info' => __('You can specify a custom Url if you use any plugin for login or registration.', 'cjsupport'),
		'suffix' => '',
		'prefix' => '',
		'default' => '',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'text',
		'id' => 'page_edit_profile',
		'label' => __('Edit Profile Url', 'cjsupport'),
		'info' => __('You can specify a custom Url for user edit profile page.', 'cjsupport'),
		'suffix' => '',
		'prefix' => '',
		'default' => admin_url('profile.php'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'text',
		'id' => 'logout_url',
		'label' => __('Logout Url', 'cjsupport'),
		'info' => __('You can specify a custom Logout Url that is displayed in the main menu.', 'cjsupport'),
		'suffix' => '',
		'prefix' => '',
		'default' => wp_logout_url(site_url()),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'sub-heading',
		'id' => 'general-settings',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => __('Company Settings', 'cjsupport'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'text',
		'id' => 'company_name',
		'label' => __('Company Name', 'cjsupport'),
		'info' => __('Your company name shows up in the header if logo image is not provided, notification emails and more. Be sure to put the right name here.', 'cjsupport'),
		'suffix' => '',
		'prefix' => '',
		'default' => get_bloginfo('name'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'text',
		'id' => 'company_email',
		'label' => __('Company email', 'cjsupport'),
		'info' => __('Domain name used in this email will be parsed to create <b><span class="red">no-reply</span>@DOMAIN.TLD</b>. <br><strong>Web Replies</strong>: All emails will be sent via no-reply@domain.com emails. <br><strong>Email Piping</strong>: All emails will be sent and received by this email address with additional information.', 'cjsupport'),
		'suffix' => '',
		'prefix' => '',
		'default' => get_option('admin_email'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'file',
		'id' => 'company_logo',
		'label' => __('Company logo', 'cjsupport'),
		'info' => __('Upload your company logo. If you do not upload any image file here, your company logo text will be displayed instead.', 'cjsupport'),
		'suffix' => '',
		'prefix' => '',
		'default' => '',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'sub-heading',
		'id' => 'mods-settings',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => __('Components Settings', 'cjsupport'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'radio',
		'id' => 'cjsupport_communication_setup',
		'label' => __('Communication', 'cjsupport'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => 'web',
		'options' => $communication_array, // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'radio-inline',
		'id' => 'mod_faqs',
		'label' => __('Frequently Asked Questions', 'cjsupport'),
		'info' => __('You can choose to enable or disable FAQs for the support portal.', 'cjsupport'),
		'suffix' => '',
		'prefix' => '',
		'default' => 'enable',
		'options' => $enable_disable_array, // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'radio-inline',
		'id' => 'faq_setup',
		'label' => __('Show FAQs on', 'cjsupport'),
		'info' => __('You can choose to enable or disable FAQs for the support portal.', 'cjsupport'),
		'suffix' => '',
		'prefix' => '',
		'default' => 'faq-page',
		'options' => array('faq-page' => __('FAQs page with shortcode', 'cjsupport'), 'app-page' => __('FAQs page within support app page.', 'cjsupport')), // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'radio-inline',
		'id' => 'mod_public_tickets',
		'label' => __('Public Tickets', 'cjsupport'),
		'info' => __('You can choose to enable or disable Public Tickets for the support portal.', 'cjsupport'),
		'suffix' => '',
		'prefix' => '',
		'default' => 'enable',
		'options' => $enable_disable_array, // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'radio-inline',
		'id' => 'mod_ticket_ratings',
		'label' => __('Ticket Ratings', 'cjsupport'),
		'info' => __('You can choose to enable or disable ticket ratings.', 'cjsupport'),
		'suffix' => '',
		'prefix' => '',
		'default' => 'enable',
		'options' => $enable_disable_array, // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'radio-inline',
		'id' => 'hide_departments',
		'label' => __('Hide Departments', 'cjsupport'),
		'info' => __('Choose Yes, If you do not want a client to choose a department. Tickets will be distributed on random basis.', 'cjsupport'),
		'suffix' => '',
		'prefix' => '',
		'default' => 'no',
		'options' => $yes_no_array, // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'radio-inline',
		'id' => 'hide_products',
		'label' => __('Hide products', 'cjsupport'),
		'info' => __('Choose Yes, If you do not want a client to choose a product. Tickets will be distributed on random basis.', 'cjsupport'),
		'suffix' => '',
		'prefix' => '',
		'default' => 'no',
		'options' => $yes_no_array, // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'text',
		'id' => 'allowed_file_types',
		'label' => __('Allowed file types', 'cjsupport'),
		'info' => __('Please specify file extensions for allowed file types.<br>example: jpg|png|gif|pdf|txt|zip', 'cjsupport'),
		'suffix' => '',
		'prefix' => '',
		'default' => 'jpg|png|gif|pdf|txt|zip',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'radio',
		'id' => 'textarea_type',
		'label' => __('Ticket & Comment Textbox', 'cjsupport'),
		'info' => __('You can choose to use WYSIWYG editor or normal textarea for user input on all screens.', 'cjsupport'),
		'suffix' => '',
		'prefix' => '',
		'default' => 'wysiwyg',
		'options' => array('wysiwyg' => 'WYSIWYG Text Editor', 'textarea' => 'Normal Multiline Textarea'), // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'sub-heading',
		'id' => 'ticket-settings',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => __('Ticket Options', 'cjsupport'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'radio-inline',
		'id' => 'ticket_visibility',
		'label' => __('Default Ticket Visibility', 'cjsupport'),
		'info' => __('Choose default ticket visibility for new tickets.', 'cjsupport'),
		'suffix' => '',
		'prefix' => '',
		'default' => 'private',
		'options' => $public_private_array, // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'radio-inline',
		'id' => 'admin_as_agent',
		'label' => __('Treat Admin as Agent', 'cjsupport'),
		'info' => __('You can restrict admin rights and treat all admins as agents if present in support staff.', 'cjsupport'),
		'suffix' => '',
		'prefix' => '',
		'default' => 'no',
		'options' => $yes_no_array, // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'number',
		'id' => 'auto_close_ticket_days',
		'label' => __('Auto close tickets after', 'cjsupport'),
		'info' => __('Specify number of days to auto close tickets if there is no response from the client.<br>Set this to 0 to disable auto close.', 'cjsupport'),
		'suffix' => '',
		'prefix' => '',
		'params' => array('min' => 0),
		'default' => 0,
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'number',
		'id' => 'maximum_response_time',
		'label' => __('Maximum response time', 'cjsupport'),
		'info' => __('Specify maximum number of hours for an agent to respond to a ticket.<br>Agent will be notified via email about the unattended tickets.<br>Admin will receive an alert if ticket stays unattended beyond maximum number of hours specified.<br>You can set this to 0 to disable these notifications.', 'cjsupport'),
		'suffix' => '',
		'prefix' => '',
		'params' => array('min' => 0),
		'default' => 48,
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'sub-heading',
		'id' => 'communication-settings',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => __('Extended Permissions', 'cjsupport'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'radio-inline',
		'id' => 'client_can_close_ticket',
		'label' => __('Can clients close their tickets?', 'cjsupport'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => 'yes',
		'options' => $yes_no_array, // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'radio-inline',
		'id' => 'client_can_edit_comment',
		'label' => __('Can clients edit their comments?', 'cjsupport'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => 'yes',
		'options' => $yes_no_array, // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'radio-inline',
		'id' => 'agent_can_close_ticket',
		'label' => __('Can agents close their tickets?', 'cjsupport'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => 'yes',
		'options' => $yes_no_array, // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'radio-inline',
		'id' => 'agent_can_edit_comment',
		'label' => __('Can agents edit their comments?', 'cjsupport'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => 'yes',
		'options' => $yes_no_array, // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'radio-inline',
		'id' => 'agent_can_create_faq',
		'label' => __('Can agents create FAQs?', 'cjsupport'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => 'yes',
		'options' => $yes_no_array, // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'radio-inline',
		'id' => 'agent_can_transfer_ticket',
		'label' => __('Can agents transfer tickets?', 'cjsupport'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => 'yes',
		'options' => $yes_no_array, // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'radio-inline',
		'id' => 'agent_can_change_priority',
		'label' => __('Can agents change ticket priority?', 'cjsupport'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => 'yes',
		'options' => $yes_no_array, // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'radio-inline',
		'id' => 'agent_can_reply_via_email',
		'label' => __('Allow agents to reply via email?', 'cjsupport'),
		'info' => '<span class="red">'.__('PHP Imap extension only works when someone visit the website.<br>If you are using the support app as a standalone site, you should not enable this as the emails will not be parsed automatically.', 'cjsupport').'</span>',
		'suffix' => '',
		'prefix' => '',
		'default' => 'no',
		'options' => $yes_no_array, // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'sub-heading',
		'id' => 'notification-settings',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => __('Admin Notifications', 'cjsupport'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'radio-inline',
		'id' => 'new_user_admin_notification',
		'label' => __('New User Notifications?', 'cjsupport'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => 'no',
		'options' => $yes_no_array, // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'sub-heading',
		'id' => 'debug-settings',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => __('Debugging', 'cjsupport'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'radio-inline',
		'id' => 'debugging',
		'label' => __('Enable Debugging', 'cjsupport'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => 'no',
		'options' => $yes_no_array, // array in case of dropdown, checkbox and radio buttons
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