<?php
// New ticket emails
$cjsupport_email_messages['new_ticket_subject_to_agent'] = 'New Support Ticket from %%client_name%% on %%product_name%%';
$cjsupport_email_messages['new_ticket_to_agent'] = <<<EOF
<p>%%agent_name%%,</p>
<p>You have a new ticket form <strong>%%client_name%%</strong>.</p>
<hr style="border:0;height:1px;background:#ddd;">
<p>
For: <strong>%%product_name%%</strong><br>
Subject: <strong>%%ticket_subject%%</strong>
</p>
<p>Comment:</p>
%%ticket_comment%%
<hr style="border:0;height:1px;background:#ddd;">
<p>
<strong>Do not reply to this email.</strong><br>
<a href="%%ticket_url%%">Click here to log in and reply to this ticket.</a>
</p>
EOF;


$cjsupport_email_messages['new_ticket_subject_to_client'] = 'Your ticket has been received for %%product_name%%';
$cjsupport_email_messages['new_ticket_to_client'] = <<<EOF
<p>%%client_name%%,</p>
<p>We have received your ticket <strong>"%%ticket_subject%%"</strong> and your query will be answered as soon as possible.</p>
<hr style="border:0;height:1px;background:#ddd;">
<p>
<strong>Do not reply to this email.</strong><br>
<a href="%%ticket_url%%">Click here to log in and add comments to this ticket.</a>
</p>
EOF;


// Ticket status emails
$cjsupport_email_messages['ticket_closed_subject_to_agent'] = 'Ticket closed by %%client_name%%';
$cjsupport_email_messages['ticket_closed_to_agent'] = <<<EOF
<p>%%agent_name%%,</p>
<p>%%client_name%% has closed the ticket <strong>"%%ticket_subject%%"</strong>.</p>
<hr style="border:0;height:1px;background:#ddd;">
<p>
<strong>Do not reply to this email.</strong><br>
<a href="%%ticket_url%%">Click here to log in and view this ticket.</a>
</p>
EOF;


$cjsupport_email_messages['ticket_closed_subject_to_client'] = 'Ticket closed by %%agent_name%%';
$cjsupport_email_messages['ticket_closed_to_client'] = <<<EOF
<p>%%client_name%%,</p>
<p>%%agent_name%% has closed the ticket <strong>"%%ticket_subject%%"</strong>.</p>
<hr style="border:0;height:1px;background:#ddd;">
<p>
<strong>Do not reply to this email.</strong><br>
<a href="%%ticket_url%%">Click here to log in and view this ticket.</a>
</p>
EOF;


$cjsupport_email_messages['ticket_reopened_subject_to_agent'] = 'Ticket re-opened by %%client_name%%';
$cjsupport_email_messages['ticket_reopened_to_agent'] = <<<EOF
<p>%%agent_name%%,</p>
<p>%%client_name%% has reopened <strong>%%ticket_subject%%</strong>.</p>
<hr style="border:0;height:1px;background:#ddd;">
<p>
<strong>Do not reply to this email.</strong><br>
<a href="%%ticket_url%%">Click here to log in and view this ticket.</a>
</p>
EOF;


$cjsupport_email_messages['ticket_reopened_subject_to_client'] = 'Ticket re-opened by %%agent_name%%';
$cjsupport_email_messages['ticket_reopened_to_client'] = <<<EOF
<p>%%client_name%%,</p>
<p>%%agent_name%% has reopened <strong>%%ticket_subject%%</strong>.</p>
<hr style="border:0;height:1px;background:#ddd;">
<p>
<strong>Do not reply to this email.</strong><br>
<a href="%%ticket_url%%">Click here to log in and view this ticket.</a>
</p>
EOF;


// Ticket comment emails
$cjsupport_email_messages['new_comment_subject_to_agent'] = 'New comment from %%client_name%% on %%ticket_subject%%';
$cjsupport_email_messages['new_comment_to_agent'] = <<<EOF
<p>%%agent_name%%,</p>
<p>%%client_name%% added a new comment to <strong>%%ticket_subject%%</strong>.</p>
<hr style="border:0;height:1px;background:#ddd;">
%%comment_content%%
<hr style="border:0;height:1px;background:#ddd;">
<p>
<strong>Do not reply to this email.</strong><br>
<a href="%%ticket_url%%">Click here to log in and view this ticket.</a>
</p>
EOF;

$cjsupport_email_messages['new_comment_subject_to_client'] = 'New comment from %%agent_name%% on %%ticket_subject%%';
$cjsupport_email_messages['new_comment_to_client'] = <<<EOF
<p>%%client_name%%,</p>
<p>%%agent_name%% added a new comment to <strong>%%ticket_subject%%</strong>.</p>
<hr style="border:0;height:1px;background:#ddd;">
%%comment_content%%
<hr style="border:0;height:1px;background:#ddd;">
<p>
<strong>Do not reply to this email.</strong><br>
<a href="%%ticket_url%%">Click here to log in and view this ticket.</a>
</p>
EOF;


$cjsupport_email_messages['update_comment_subject_to_agent'] = '%%client_name%% has modified his comment on %%ticket_subject%%';
$cjsupport_email_messages['update_comment_to_agent'] = <<<EOF
<p>%%agent_name%%,</p>
<p>%%client_name%% updated his comment on <strong>%%ticket_subject%%</strong>.</p>
<hr style="border:0;height:1px;background:#ddd;">
%%comment_content%%
<hr style="border:0;height:1px;background:#ddd;">
<p>
<strong>Do not reply to this email.</strong><br>
<a href="%%ticket_url%%">Click here to log in and view this ticket.</a>
</p>
EOF;

$cjsupport_email_messages['update_comment_subject_to_client'] = '%%agent_name%% has modified his comment on %%ticket_subject%%';
$cjsupport_email_messages['update_comment_to_client'] = <<<EOF
<p>%%client_name%%,</p>
<p>%%agent_name%% updated his comment on <strong>%%ticket_subject%%</strong>.</p>
<hr style="border:0;height:1px;background:#ddd;">
%%comment_content%%
<hr style="border:0;height:1px;background:#ddd;">
<p>
<strong>Do not reply to this email.</strong><br>
<a href="%%ticket_url%%">Click here to log in and view this ticket.</a>
</p>
EOF;

// Transfer ticket emails
$cjsupport_email_messages['transfer_info_subject_to_agent'] = 'New ticket transfered for %%product_name%%';
$cjsupport_email_messages['transfer_info_to_agent'] = <<<EOF
<p>%%agent_name%%,</p>
<p>A new ticket has been transferred to you.</p>
<hr style="border:0;height:1px;background:#ddd;">
<p><strong>Subject: %%ticket_subject%%</strong></p>
Internal Notes:<br>
%%comment_content%%
<p>
<hr style="border:0;height:1px;background:#ddd;">
<strong>Do not reply to this email.</strong><br>
<a href="%%ticket_url%%">Click here to log in and view this ticket.</a>
</p>
EOF;

$cjsupport_email_messages['transfer_info_subject_to_client'] = 'Ticket transfered to %%department_name%%';
$cjsupport_email_messages['transfer_info_to_client'] = <<<EOF
<p>%%client_name%%,</p>
<p>Your ticket <strong>"%%ticket_subject%%"</strong> has been transferred to <strong>%%department_name%%</strong>.</p>
<hr style="border:0;height:1px;background:#ddd;">
<strong>Do not reply to this email.</strong><br>
<a href="%%ticket_url%%">Click here to log in and view this ticket.</a>
</p>
EOF;


// FAQ submitted email to admin
$cjsupport_email_messages['new_faq_subject_to_admin'] = 'New faq submitted by %%agent_name%%';
$cjsupport_email_messages['new_faq_to_admin'] = <<<EOF
<p>Dear Admin,</p>
<p>%%agent_name%% submitted a new FAQ for <strong>%%product_name%%</strong>.</p>
<p><strong>%%faq_title%%</strong></p>
<p>Review: %%faq_edit_link%%</p>
<strong>Do not reply to this email.</strong>
EOF;


# Email routing
####################################################################################################

// New user email to customer
$cjsupport_email_messages['new_user_subject_to_client'] = 'Your account details for %%site_name%%.';
$cjsupport_email_messages['new_user_message_to_client'] = <<<EOF
<p>Hello,</p>
<p>Your account has been created and you can login to our helpdesk by using following details:</p>
<p>
<strong>Username: </strong>%%user_login%%<br>
<strong>Password: </strong>%%user_pass%%<br>
<strong>Login Url: </strong>%%login_url%%<br>
</p>
EOF;

# Auto close ticket
####################################################################################################
// Auto close ticket email to customer
$cjsupport_email_messages['auto_close_subject_to_client'] = 'Ticket closed by system.';
$cjsupport_email_messages['auto_close_message_to_client'] = <<<EOF
<p>Hello,</p>
<p>Your ticket has been closed as we did not receive your reply.</p>
<p>
Feel free to create a new ticket if you need furhter help.
</p>
EOF;


# Unattended ticket emails
####################################################################################################
// Auto close ticket email to agent
$cjsupport_email_messages['unattended_subject_to_agent'] = 'Ticket awaiting response';
$cjsupport_email_messages['unattended_message_to_agent'] = <<<EOF
<p>The ticket is about to reach its maximum respond time, please respond quickly.</p>
<p><a href="%%ticket_url%%">Click here</a> to reply to this ticket.</p>
EOF;

// Auto close ticket email to admin
$cjsupport_email_messages['unattended_subject_to_admin'] = 'Unattended ticket notification';
$cjsupport_email_messages['unattended_message_to_admin'] = <<<EOF
<p>This ticket has not been responded to beyond its maximum response time.</p>
<p><a href="%%ticket_url%%">Click here</a> to view to this ticket.</p>
EOF;








