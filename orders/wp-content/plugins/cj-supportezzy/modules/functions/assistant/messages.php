<?php

global $current_user;
get_currentuserinfo();
$user_info = cjsupport_user_info($current_user->ID);
$item_name = cjsupport_item_info('item_name');
$item_type = cjsupport_item_info('item_type');

$quick_setup_quide_link = '<a href="'.cjsupport_item_info('quick_start_guide_url').'" target="_blank">Quick Start Guide</a>';
$menu_icon = '<img src="'.cjsupport_item_path('framework_url').'/assets/admin/img/menu-icon.png" width="16" />';

$welcome_msg = <<<EOF
<p>
<b>Hello {$user_info['display_name']}</b>,<br>
Thank you for using our {$item_name} WordPress plugin.
</p>
<p>
I am here to assist you setting up this plugin on your website and I'll also show you some awesome features that comes with this plugin.
</p>
<p>
If you like, you can also check out our {$quick_setup_quide_link} to setup this pugin and learn more about the features.
</p>
EOF;

$end_tour_msg = <<<EOF
<p>
<b>Thank You, {$user_info['display_name']}</b>,<br>
</p>
<p>
Its been nice interacting with you. In case you need me again,<br> you can call me from <b>Help & Support</b> menu from the navigation on plugin settigns page.
</p>
<p>
Here are a few useful links for further help and contact with CSSJockey team.
</p>
<ul>
<li><a href="http://demo.cssjockey.com/cjsupport/quick-start-guide/" target="_blank">Quick Start Guide</a></li>
<li><a href="http://demo.cssjockey.com/cjsupport" target="_blank">Documentation</a></li>
<li><a href="http://cssjockey.com/support" target="_blank">Support Fourm</a></li>
<li><a href="http://cssjockey.com/support" target="_blank">Feature Requests</a></li>
<li><a href="http://cssjockey.com/support" target="_blank">Report Bugs</a></li>
</ul>
<p>
<h4>Good luck with your project.</h4>
</p>
EOF;

$app_settings = <<<EOF
<h3>Application Configuration</h3>
<p>
Please go through each option and configure these as per your requirements.
</p>
<p>
<strong class="red">Important:</strong> You must select a page for the Support App to run. <br>If you wish to use support app as your homepage, you can set the selected page as Front page under Settings >> Reading.
<br><br>
</p>
EOF;

$choose_staff = <<<EOF
<h3>Choose Support Staff</h3>
<p>
You can create as many users as you want, select them under Support Staff and click Save Settings button.
</p>
<p>
Once you are done selecting the users as support staff, click Assign Departments and Products button.
<br><br>
</p>
EOF;

$manage_staff = <<<EOF
<h3>Manage Support Staff</h3>
<p>
Here you can assign different departments and products for each user. Once a client creates a ticket, under specific department and product, tickets will be assigned to these users as per their support department and products.
</p>
<p>
If you are the only one to handle the support, select All Departments and All Products for yourself.
<br><br>
</p>
EOF;

$email_variables = <<<EOF
<h3>Custom Email Variables</h3>
<p>
Here's a list of dynamic variables that you can use when you customize email messages.
</p>
<p>
To cusomize each email, Check out the options available under Customize Email Messages dropdown menu.
</p>
<p>There are various events when this app sends email messages to the client, agent and admin. I've created separate pages to manage these to avoid any confusion.</p>
<p>
Go ahead and customize the email messages with dynamic variables as per your requirements.
<br><br>
</p>
EOF;


$cjsupport_assistant_steps[] = array(
	'id' => 'welcome',
	'text' => $welcome_msg,
	'button_text' => __('Ok, lets get started', 'cjsupport'),
	'callback' => cjsupport_callback_url('cjsupport_app_settings'),
	'cancel_text' => 'No, I will check out my self.',
	'cancel_link' => cjsupport_assistant_url('end-tour', cjsupport_callback_url('core_welcome')),
);
$cjsupport_assistant_steps[] = array(
	'id' => 'app_settings',
	'text' => $app_settings,
	'button_text' => 'All set, Let\'s go ahead',
	'callback' => cjsupport_callback_url('cjsupport_choose_staff'),
	'cancel_text' => 'End Tour',
	'cancel_link' => cjsupport_assistant_url('end-tour', cjsupport_callback_url('core_welcome')),
);
$cjsupport_assistant_steps[] = array(
	'id' => 'choose_staff',
	'text' => $choose_staff,
	'button_text' => 'Ok, lets assign departments & products',
	'callback' => cjsupport_callback_url('cjsupport_manage_staff'),
	'cancel_text' => 'End Tour',
	'cancel_link' => cjsupport_assistant_url('end-tour', cjsupport_callback_url('core_welcome')),
);
$cjsupport_assistant_steps[] = array(
	'id' => 'manage_staff',
	'text' => $manage_staff,
	'button_text' => 'Customize Email Messages',
	'callback' => cjsupport_callback_url('cjsupport_emails_variables'),
	'cancel_text' => 'End Tour',
	'cancel_link' => cjsupport_assistant_url('end-tour', cjsupport_callback_url('core_welcome')),
);
$cjsupport_assistant_steps[] = array(
	'id' => 'email_variables',
	'text' => $email_variables,
	'button_text' => 'All done, what next?',
	'callback' => cjsupport_callback_url('core_welcome'),
	'cancel_text' => 'End Tour',
	'cancel_link' => cjsupport_assistant_url('end-tour', cjsupport_callback_url('core_welcome')),
);
$cjsupport_assistant_steps[] = array(
	'id' => 'end_tour',
	'text' => $end_tour_msg,
	'button_text' => 'See Ya!',
	'callback' => cjsupport_assistant_url('end-tour', cjsupport_callback_url('core_welcome')),
	'cancel_text' => 'End Tour',
	'cancel_link' => cjsupport_assistant_url('end-tour', cjsupport_callback_url('core_welcome')),
);
