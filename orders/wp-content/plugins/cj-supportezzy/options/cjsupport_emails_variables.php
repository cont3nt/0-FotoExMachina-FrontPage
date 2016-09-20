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

$variables_info = <<<EOD
<p><b>Ticket Variables</b></p>
<p><code>%%agent_name%%</code> : Will be replaced by agent name for the assigned ticket.</p>
<p><code>%%client_name%%</code> : Will be replaced by client name who submitted the ticket.</p>
<p><code>%%product_name%%</code> : Will be replaced by product name for the ticket.</p>
<p><code>%%department_name%%</code> : Will be replaced by department name for the ticket.</p>
<p><code>%%ticket_uid%%</code> : Will be replaced by unique ticket number. </p>
<p><code>%%ticket_subject%%</code> : Will be replaced by subject line of the ticket.</p>
<p><code>%%ticket_comment%%</code> : Will be replaced by content of the ticket posted by client.</p>
<p><code>%%ticket_url%%</code> : Will be replaced by full Url of the ticket.</p>

<p><b>Comment Variables</b></p>
<p><code>%%comment_content%%</code> : Will be replaced by comment/message posted by the client or agent.</p>

<p><b>FAQs Variables</b></p>
<p><code>%%faq_title%%</code> : Will be replaced by title of the FAQ.</p>
<p><code>%%faq_content%%</code> : Will be replaced by content of the FAQ.</p>
<p><code>%%faq_edit_link%%</code> : Will be replaced by edit link for the faq post.</p>
EOD;

$cjsupport_item_options['cjsupport_emails_variables'] = array(
	array(
		'type' => 'heading',
		'id' => 'cjsupport_emails_variables_heading',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => __('Dynamic Variables', 'cjsupport'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'info-full',
		'id' => 'cjsupport_emails_variables_info',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => __('You can use below variables in your email messages', 'cjsupport'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'info',
		'id' => 'cjsupport_emails_variable_1',
		'label' => __('Variables', 'cjsupport'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => $variables_info,
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
);