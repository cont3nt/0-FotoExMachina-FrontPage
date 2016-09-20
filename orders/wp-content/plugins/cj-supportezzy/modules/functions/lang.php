<?php
global $wpdb, $current_user;
get_currentuserinfo();

$support_page = get_post(cjsupport_get_option('page_cjsupport_app'));
$homepage_content = wpautop($support_page->post_content);
if(is_user_logged_in() && current_user_can('manage_options')){
	$homepage_content .= '<p><a target="_blank" href="'.get_edit_post_link( $support_page->ID ).'">'.__('Edit content', 'cjsupport').'</a></p>';
}

$user_role = cjsupport_user_type($current_user->ID);

$lm = cjsupport_get_option('login_message');

$login_link = (cjsupport_get_option('login_url') == '') ? wp_login_url(get_permalink($support_page->ID)) : cjsupport_string(cjsupport_get_option('login_url')).'redirect='.get_permalink($support_page->ID);
$logout_link = (cjsupport_get_option('logout_url') == '') ? wp_logout_url(get_permalink($support_page->ID)) : cjsupport_string(cjsupport_get_option('logout_url')).'redirect='.get_permalink($support_page->ID);
$register_link = (cjsupport_get_option('register_url') == '') ? wp_registration_url() : cjsupport_string(cjsupport_get_option('register_url')).'redirect='.get_permalink($support_page->ID);

$login_link = sprintf('<a href="%s">Login</a>', $login_link);
$register_link = sprintf('<a href="%s">Register</a>', $register_link);

$login_message = str_replace('%%login_link%%', $login_link, $lm);
$login_message = str_replace('%%register_link%%', $register_link, $login_message);

if($user_role != 'client'){
	$sidebar_message = 	'<div class="sep"></div>'.cjsupport_get_option('sidebar_message_agent');
}
if($user_role == 'client'){
	$sidebar_message = 	'<div class="sep"></div>'.cjsupport_get_option('sidebar_message_client');
}

$logo_text = cjsupport_get_option('company_name');
$logo_image = cjsupport_get_option('company_logo');

if($logo_image != ''){
	$logo = '<span class="image-logo"><img src="'.$logo_image[0].'" alt="'.$logo_text.'" /></span>';
}else{
	$logo = '<span class="text-logo">'.$logo_text.'</span>';
}

$products_array = cjsupport_products_array('slug');
$verified_envato_products = cjsupport_get_verified_envato_products($current_user->ID);
$departments_array = cjsupport_departments_array('slug');
$random_product = array_rand($products_array);
$random_department = array_rand($departments_array);

$allowed_file_types = str_replace('|', ', ', cjsupport_get_option('allowed_file_types'));

$user_can_close_tickets = 0;
// agents can close tickets
if(($user_role == 'agent' && cjsupport_get_option('agent_can_close_ticket') == 'yes')){
	$user_can_close_tickets = 1;
}

// clients can close tickets
if(($user_role == 'client' && cjsupport_get_option('client_can_close_ticket') == 'yes')){
	$user_can_close_tickets = 1;
}

// admin can close tickets
if((current_user_can('manage_options'))){
	$user_can_close_tickets = 1;
}

// user can transfer ticket
$can_transfer_ticket = 0;
if(($user_role == 'agent' && cjsupport_get_option('agent_can_transfer_ticket') == 'yes')){
	$can_transfer_ticket = 1;
}
if(current_user_can('manage_options')){
	$can_transfer_ticket = 1;
}

// user can transfer ticket
$can_change_priority = 0;
if(($user_role == 'agent' && cjsupport_get_option('agent_can_change_priority') == 'yes')){
	$can_change_priority = 1;
}
if(current_user_can('manage_options')){
	$can_change_priority = 1;
}

$lang = array(
	'home_url' => cjsupport_get_option('home_url'),
	'logout_url' => cjsupport_get_option('logout_url'),
	'logo' => $logo,
	'item_url' => cjsupport_item_path('item_url'),
	'form_builder_url' => cjsupport_item_path('item_url').'/templates/partials/includes/form-builder.html',
	'custom_form_fields' => cjsupport_custom_form_fields(),
	'user_id' => $current_user->ID,
	'user_type' => $user_role,
	'user_role' => cjsupport_user_role($current_user->ID),
	'user_can_close_ticket' => $user_can_close_tickets,
	'agent_can_change_priority' => $can_change_priority,
	'are_you_sure' => __("Are you sure?\nThis cannot be undone.", 'cjsupport'),
	'products_array' => cjsupport_products_array('slug'),
	'verified_envato_products' => $verified_envato_products,
	'departments_array' => cjsupport_departments_array('slug'),
	'login_message' => $login_message,
	'employees_array' => cjsupport_employees_array(),
	'display_name' => $current_user->display_name,
	'user_login' => $current_user->user_login,
	'user_email' => $current_user->user_email,
	'user_avatar' => cjsupport_get_image_url(get_avatar($current_user->ID, '125')),
	'mod_documentation' => cjsupport_get_option('mod_documentation'),
	'mod_faqs' => cjsupport_get_option('mod_faqs'),
	'mod_public_tickets' => cjsupport_get_option('mod_public_tickets'),
	'cancel' => __('Cancel', 'cjsupport'),
	'save' => __('Save', 'cjsupport'),
	'search_placeholder' => __('Search Tickets by ID or Keywords', 'cjsupport'),
	'tickets' => __('My Tickets', 'cjsupport'),
	'ticket_id_label' => __('Ticket ID:', 'cjsupport'),
	'documentation' => __('Documentation', 'cjsupport'),
	'faqs' => __('FAQs', 'cjsupport'),
	'settings' => __('Settings', 'cjsupport'),
	'welcome_user' => (is_user_logged_in()) ? sprintf(__('Welcome, %s', 'cjsupport'), $current_user->display_name): __('Welcome, Guest', 'cjsupport'),
	'homepage_content' => $homepage_content,
	'all_open_tickets' => __('All Open Tickets', 'cjsupport'),
	'closed_tickets' => __('Closed Tickets', 'cjsupport'),
	'quick_links' => __('Quick Links', 'cjsupport'),
	'awaiting_response' => __('Awaiting Response', 'cjsupport'),
	'starred_tickets' => __('Starred Tickets', 'cjsupport'),
	'private_tickets' => __('Private Tickets', 'cjsupport'),
	'public_tickets' => __('Public Tickets', 'cjsupport'),
	'private_ticket' => __('Private Ticket', 'cjsupport'),
	'public_ticket' => __('Public Ticket', 'cjsupport'),
	'closed_tickets' => __('Closed Tickets', 'cjsupport'),
	'employees' => __('Employees', 'cjsupport'),
	'products' => __('Products', 'cjsupport'),
	'edit_profile' => __('Update Profile', 'cjsupport'),
	'edit_profile_url' => cjsupport_get_option('page_edit_profile'),
	'logout_url' => $logout_link,
	'change_password_url' => admin_url('profile.php').'#password',
	'change_password' => __('Change Password', 'cjsupport'),
	'edit_gravatar' => __('Update Profile Picture', 'cjsupport'),
	'label_find_client' => __('Search client account', 'cjsupport'),
	'label_client_email' => __('Client Email Address <span class="req">*</span>', 'cjsupport'),
	'info_client_email' => __('Search for a client or specify a valid email address to create a ticket on client\'s behalf.', 'cjsupport'),
	'submit_new_ticket' => __('Submit New Ticket', 'cjsupport'),
	'create_new_ticket' => __('Create New Ticket', 'cjsupport'),
	'ticket_product' => __('This ticket is for: <span class="req">*</span>', 'cjsupport'),
	'ticket_department' => __('Select Department: <span class="req">*</span>', 'cjsupport'),
	'ticket_subject' => __('Subject <span class="req">*</span>', 'cjsupport'),
	'ticket_url' => __('URL (optional)', 'cjsupport'),
	'ticket_message' => __('Your Question/Problem: <span class="req">*</span>', 'cjsupport'),
	'ticket_comment_message' => __('Your Reply: <span class="req">*</span>', 'cjsupport'),
	'attach_a_file' => __('Attach a file', 'cjsupport'),
	'attach_a_file_desc' => sprintf(__('Feel free to add a %s up to 5MB in size.', 'cjsupport'), $allowed_file_types),
	'submit_ticket' => __('Submit Ticket', 'cjsupport'),
	'http' => __('http://', 'cjsupport'),
	'open' => __('Open', 'cjsupport'),
	'closed' => __('Closed', 'cjsupport'),
	'status' => __('Status:', 'cjsupport'),
	'submitted_by' => __('Submitted By:', 'cjsupport'),
	'department' => __('Department:', 'cjsupport'),
	'product' => __('Product:', 'cjsupport'),
	'assigned_to' => __('Assigned to:', 'cjsupport'),
	'close_ticket' => __('Close Ticket', 'cjsupport'),
	'reopen_ticket' => __('Open Ticket', 'cjsupport'),
	'add_star' => __('Add to Starred', 'cjsupport'),
	'remove_star' => __('Remove from Starred', 'cjsupport'),
	'private_ticket' => __('Private Ticket', 'cjsupport'),
	'ticket_visibility' => __('Ticket Visibility', 'cjsupport'),
	'view_ticket' => __('View', 'cjsupport'),
	'no_tickets' => __('No tickets found.', 'cjsupport'),
	'ticket_information' => __('Ticket Information', 'cjsupport'),
	'ticket_information_more' => __('Additional Information', 'cjsupport'),
	'your_comment' => __('Your Reply/Answer <span class="req">*</span>', 'cjsupport'),
	'checkbox_close' => __('Mark this ticket as closed/resolved', 'cjsupport'),
	'post_response' => __('Post Response', 'cjsupport'),
	'show_previous_comments' => __('Show previous comments', 'cjsupport'),
	'change_agent' => __('Select support staff', 'cjsupport'),
	'change_department' => __('Select Department <span class="req">*</span>', 'cjsupport'),
	'change_product' => __('Select Product <span class="req">*</span>', 'cjsupport'),
	'transfer_ticket' => __('Transfer Ticket', 'cjsupport'),
	'can_transfer_ticket' => $can_transfer_ticket,
	'internal_notes' => __('Internal Notes <span class="req">*</span>', 'cjsupport'),
	'internal_note' => __(' ~ Internal Note', 'cjsupport'),
	'edit_comment' => __('Edit Comment', 'cjsupport'),
	'edit_comment_title' => __('Edit Comment', 'cjsupport'),
	'create_faq' => __('Create FAQ', 'cjsupport'),
	'question' => __('Question', 'cjsupport'),
	'answer' => __('Answer', 'cjsupport'),
	'submit' => __('Submit', 'cjsupport'),
	'choose_product' => __('Choose a product', 'cjsupport'),
	'faqs_title' => __('Frequently Asked Questions', 'cjsupport'),
	'faq_no_product' => __('Please select a product', 'cjsupport'),
	'view_all_faqs' => __('View all questions', 'cjsupport'),
	'label_first_name' => __('First Name', 'cjsupport'),
	'label_last_name' => __('Last Name', 'cjsupport'),
	'label_email_address' => __('Email address', 'cjsupport'),
	'refresh' => __('Refresh', 'cjsupport'),
	'sidebar_message' => $sidebar_message,
	'ticket_closed_msg' => __('Ticket is closed. You may reopen this ticket to add new comments.', 'cjsupport'),
	'search_tickets' => __('Search tickets...', 'cjsupport'),
	'envato_username' => __('Envato Username', 'cjsupport'),
	'envato_api_key' => __('Envato API Key', 'cjsupport'),
	'envato_verify_purchase' => __('Verify Purchase', 'cjsupport'),
	'envato_purchase_code' => __('Purchase Code', 'cjsupport'),
	'envato_find_purchase_code' => sprintf(__('<a target="_blank" href="%s">Click here</a> for instructions to find your purchase code.', 'cjsupport'), 'https://help.market.envato.com/hc/en-us/articles/202822600-Where-can-I-find-my-Purchase-Code-'),
	'hide_departments' => cjsupport_get_option('hide_departments'),
	'hide_products' => cjsupport_get_option('hide_products'),
	'default_product' => $products_array[$random_product]['id'],
	'default_department' => $departments_array[$random_department]['id'],
	'response_required' => __('Awaiting Response', 'cjsupport'),
	'textarea_type' => cjsupport_get_option('textarea_type'),
	'choose_file' => __('Choose files', 'cjsupport'),
	'attachments' => __('Attachments', 'cjsupport'),
	'home' => __('Home', 'cjsupport'),
	'mod_ticket_priority' => cjsupport_get_option('mod_ticket_priority'),
	'ticket_priority_label' => __('Priority', 'cjsupport'),
	'ticket_priority_options' => cjsupport_ticket_priority_options(),
	'update_ticket_priority_button' => __('Update Ticket Priority', 'cjsupport'),
	'mod_ticket_ratings' => cjsupport_get_option('mod_ticket_ratings'),
	'rate_this_response' => __('Rate this response: ', 'cjsupport'),
	'faq_setup' => cjsupport_get_option('faq_setup'),
);