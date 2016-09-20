<?php
global $wpdb, $current_user;

if(isset($_POST['act']) && $_POST['act'] == 'find-client'){
	$support_page = get_post(cjsupport_get_option('page_cjsupport_app'));
	$user_role_type = cjsupport_user_type();
	$products_array = cjsupport_products_array('slug');
	$departments_array = cjsupport_departments_array('slug');

	$return['response']['departments'] = $departments_array;
	$return['response']['products'] = $products_array;

	$random_product = array_rand($products_array);
	$random_department = array_rand($departments_array);

	$return['hide_departments'] = cjsupport_get_option('hide_departments');
	$return['hide_products'] = cjsupport_get_option('hide_products');
	$return['default_product'] = $products_array[$random_product]['id'];
	$return['default_department'] = $departments_array[$random_department]['id'];
}


if(isset($_POST['act']) && $_POST['act'] == 'create-client-ticket'){

	extract($_POST);
	$errors = null;

	// Get client info (new account is created if not exists)
	$client_info = cjsupport_new_client_account($_POST['client_email']);

	$table_form_fields = $wpdb->prefix.'cjsupport_form_fields';
	$custom_fields = $wpdb->get_results("SELECT * FROM $table_form_fields WHERE field_required = '1' ORDER BY field_order DESC");
	if(!empty($custom_fields)){
		foreach ($custom_fields as $ck => $cv) {
			if(!isset($_POST[$cv->field_id])){
				$errors['missing'] = __('Missing required fields', 'cjsupport');
			}
		}
	}

	if(!isset($product) || !isset($department) || !isset($subject) || !isset($content)){
		$errors['missing'] = __('Missing required fields', 'cjsupport');
	}

	if(!is_null($errors)){
		$return['status'] = 'errors';
		$return['response'] = implode('<br>', $errors);
	}else{

		$post_data = array(
			'post_content'   => (cjsupport_get_option('textarea_type') == 'wysiwyg') ? $content : nl2br($content),
			'post_name'      => sanitize_title( $subject ),
			'post_title'     => $subject,
			'post_status'    => 'publish',
			'post_type'      => 'cjsupport',
			'post_author'    => $client_info['ID'],
			'ping_status'    => 'closed',
			'post_excerpt'   => cjsupport_trim_text(strip_tags($content), 160, '...'),
			'comment_status' => 'open'
		);

		$post = wp_insert_post( $post_data );
		$post = get_post($post);

		wp_set_object_terms( $post->ID, $product, 'cjsupport_products', false );
		wp_set_object_terms( $post->ID, $department, 'cjsupport_departments', false );

		// Compute agent id
		$current_user_type = cjsupport_user_type($current_user->ID);
		if($current_user_type == 'agent'){
			$assigned_to = cjsupport_assign_ticket($post->ID, $department, $product, $current_user->ID);
		}
		if($current_user_type == 'admin'){
			$assigned_to = cjsupport_assign_ticket($post->ID, $department, $product);
		}
		$agent_info = cjsupport_user_info($assigned_to);


		$exclude_manual_meta = array(
			'_url',
			'_post_visibility',
			'_attachments',
			'_awaiting_response_from',
			'_starred_by',
			'_envato_verified',
			'_uid',
		);

		foreach ($_POST as $mkey => $mvalue) {
			if(!in_array($mkey, $exclude_manual_meta)){
				update_post_meta($post->ID, '_'.$mkey, $mvalue);
			}
		}

		if(isset($url)){
			update_post_meta($post->ID, '_url', strip_tags(esc_url($url)));
		}

		$default_visibility = cjsupport_get_option('ticket_visibility');
		update_post_meta($post->ID, '_post_visibility', $default_visibility);

		if(isset($attachments)){
			$attachments = explode(',', $_POST['attachments']);
			update_post_meta($post->ID, '_attachments', $attachments);
		}

		update_post_meta($post->ID, '_awaiting_response_from', $agent_info['ID']);
		update_post_meta($post->ID, 'ticket_status', 'publish');
		update_post_meta($post->ID, '_starred_by', 'none');

		update_post_meta($post->ID, '_envato_verified', '');

		$ticket_id = cjsupport_unique_string().$post->ID;
		update_post_meta($post->ID, '_uid', $ticket_id);


		// Send email to client
		$ticket_info = cjsupport_ticket_info($post->ID);
		$email_data = array(
		    'to' => $client_info['user_email'],
		    'from_name' => cjsupport_noreply('name', $ticket_info),
		    'from_email' => cjsupport_noreply('email', $ticket_info),
		    'subject' => cjsupport_parse_email_subject('new_ticket_subject_to_client', $ticket_info['ID']),
		    'message' => cjsupport_parse_email_message('new_ticket_to_client', $ticket_info['ID']),
		);
		cjsupport_email($email_data);

		// Send email to agent
		$email_data = array(
		    'to' => $ticket_info['agent_info']['user_email'],
		    'from_name' => cjsupport_noreply('name', $ticket_info, cjsupport_override_noreply_agents()),
		    'from_email' => cjsupport_noreply('email', $ticket_info, cjsupport_override_noreply_agents()),
		    'subject' => cjsupport_parse_email_subject('new_ticket_subject_to_agent', $ticket_info['ID']),
		    'message' => cjsupport_parse_email_message('new_ticket_to_agent', $ticket_info['ID']),
		);
		cjsupport_email($email_data);



		$return['status'] = 'success';
		$return['response'] = $post;

	}

}





function cjsupport_new_client_account($email_address){
    global $wpdb;
    $user_info = cjsupport_user_info($email_address);

    if(is_null($user_info)){

        $user_login = cjsupport_create_unique_username($email_address);
        $user_pass = wp_generate_password( 10, false, false );
        $user_email = $email_address;
        $new_user_id = wp_create_user( $user_login, $user_pass, $user_email );
        $user_info = cjsupport_user_info($new_user_id);

        $new_user_subject = cjsupport_get_option('new_user_subject_to_client');
        $new_user_subject = str_replace('%%site_name%%', get_bloginfo('name'), $new_user_subject);
        $support_page = get_post(cjsupport_get_option('page_cjsupport_app'));
        $login_link = (cjsupport_get_option('login_url') == '') ? wp_login_url(get_permalink($support_page->ID)) : cjsupport_get_option('login_url');
        $new_user_message = cjsupport_get_option('new_user_message_to_client');
        $new_user_message = str_replace('%%site_name%%', get_bloginfo('name'), $new_user_message);
        $new_user_message = str_replace('%%user_login%%', $user_info['user_login'], $new_user_message);
        $new_user_message = str_replace('%%user_pass%%', $user_pass, $new_user_message);
        $new_user_message = str_replace('%%login_url%%', $login_link, $new_user_message);

        $new_user_email_data = array(
            'to' => $user_info['user_email'],
            'from_name' => cjsupport_noreply('name', null, 'force-no-reply'),
            'from_email' => cjsupport_noreply('email', null, 'force-no-reply'),
            'subject' => $new_user_subject,
            'message' => $new_user_message,
        );
        cjsupport_email($new_user_email_data);

        if(cjsupport_get_option('new_user_admin_notification') == 'yes'){
            $new_user_admin_message = __('<p>Dear Admin,</p>', 'cjsupport');
            $new_user_admin_message .= __('<p>A new user has created a support ticket via email.</p>', 'cjsupport');
            $new_user_admin_message .= sprintf(__('<p><a href="%s">Click here</a> to view tickets.</p>', 'cjsupport'), cjsupport_generate_url('page_cjsupport_app').'#/tickets/user/'.$user_info['ID']);
            $new_user_admin_message .= sprintf(__('<p><a href="%s">Disable</a> these notifications.</p>', 'cjsupport'), admin_url('admin.php?page=cjsupport&callback=cjsupport_app_settings'));

            $new_user_admin_email_data = array(
                'to' => get_option('admin_email'),
                'from_name' => get_bloginfo('name'),
                'from_email' => get_option('admin_email'),
                'subject' => sprintf(__('New User Registration: %s', 'cjsupport'), $user_info['user_login']),
                'message' => $new_user_admin_message,
            );
            cjsupport_email($new_user_admin_email_data);
        }

    }
    $user_info['user_type'] = cjsupport_user_type($user_info['ID']);
    return $user_info;
}