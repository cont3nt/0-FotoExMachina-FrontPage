<?php

$errors = null;

$yes_no_array = array(
	'yes' => __('Yes', 'cjsupport'),
	'no' => __('No', 'cjsupport'),
);

$departments = get_terms('cjsupport_departments', array('hide_empty' => 0, 'order_by' => 'name', 'order' => 'asc') );
$products = get_terms('cjsupport_products', array('hide_empty' => 0, 'order_by' => 'name', 'order' => 'asc') );


$department_options['all'] = __('All departments', 'cjsupport');
if(!empty($departments)){
	foreach ($departments as $key => $department) {
		$department_options[$department->slug] = $department->name;
	}
}

$product_options['all'] = __('All products', 'cjsupport');
if(!empty($products)){
	foreach ($products as $key => $product) {
		$product_options[$product->slug] = $product->name;
	}
}

$email_routes = cjsupport_get_option('cjsupport_email_routes');

if(isset($_POST['add_route'])){

	$errors = null;

	if(!cjsupport_is_email_valid($_POST['imap_email']) || $_POST['imap_email'] == ''){
		$errors['email'] = __('Invalid email address.', 'cjsupport');
	}

	$check_existing = email_exists($_POST['imap_email']);
	if($check_existing){
		$errors['mailbox'] = __('Mailbox email should not be used for any user account on this website.', 'cjsupport');
	}

	if($_POST['imap_password'] == ''){
		$errors[] = __('Password field is missing.', 'cjsupport');
	}

	if($_POST['imap_server'] == ''){
		$errors[] = __('Server field is missing.', 'cjsupport');
	}

	if($_POST['imap_port'] == ''){
		$errors[] = __('Server port field is missing.', 'cjsupport');
	}

	if(!isset($_POST['departments'])){
		$errors[] = __('Please assign departments for this route.', 'cjsupport');
	}

	if(!isset($_POST['products'])){
		$errors[] = __('Please assign products for this route.', 'cjsupport');
	}

	if(isset($email_routes) && is_array($email_routes)){
		foreach ($email_routes as $key => $route) {
			if($key != $_POST['imap_email'] && $_POST['departments'] == $route['departments']){
				$errors['departments'] = __('Selected department is already assigned to another mailbox.', 'cjsupport');
			}
		}
	}

	if(is_null($errors)){
		$ssl = ($_POST['imap_ssl'] == 'yes') ? '/ssl' : '';
		$server_string = '{'.$_POST['imap_server'].':'.$_POST['imap_port'].'/novalidate-cert/imap'.$ssl.'}INBOX';
		$mbox = @imap_open($server_string, $_POST['imap_email'], $_POST['imap_password']);

		$imap_errors = imap_errors();
		$imap_alerts = imap_alerts();

		if(is_array($imap_errors) || is_array($imap_alerts)){
			$errors['imap'] = sprintf(__('Could not connect to IMAP server, please check and try again. <p>%s</p> <p>%s</p>', 'cjsupport'), @implode('<br>', $imap_errors), @implode('<br>', $imap_alerts));
		}else{
			imap_close($mbox);
		}
	}

	if(!is_null($errors)){
		echo cjsupport_show_message('error', implode('<br>', $errors));
	}


	if(is_null($errors)){

		$ssl = ($_POST['imap_ssl'] == 'yes') ? '/ssl' : '';
		$server_string = '{'.$_POST['imap_server'].':'.$_POST['imap_port'].'/novalidate-cert/imap'.$ssl.'}INBOX';

		$post_products = (in_array('all', $_POST['products'])) ? array('all') : $_POST['products'];

		$email_route_data[$_POST['imap_email']] = array(
			'server_string' => $server_string,
			'imap_email' => $_POST['imap_email'],
			'imap_password' => $_POST['imap_password'],
			'imap_server' => $_POST['imap_server'],
			'imap_port' => $_POST['imap_port'],
			'imap_ssl' => $_POST['imap_ssl'],
			'departments' => $_POST['departments'],
			'products' => $post_products,
		);

		if(!is_array($email_routes)){
			cjsupport_update_option('cjsupport_email_routes', $email_route_data);
			$location = cjsupport_callback_url('cjsupport_email_routes');
			wp_redirect($location);
			exit;
		}else{
			unset($email_routes[$_POST['imap_email']]);
			$new_routes = array_merge($email_routes, $email_route_data);
			cjsupport_update_option('cjsupport_email_routes', $new_routes);
			$location = cjsupport_callback_url('cjsupport_email_routes');
			wp_redirect($location);
			exit;
		}
	}

}



$configuration_form['form_fields'] = array(
	array(
	    'type' => 'sub-heading',
	    'id' => 'none',
	    'label' => '',
	    'info' => '',
	    'suffix' => '',
	    'prefix' => '',
	    'default' => __('Add new email (IMAP configuration)', 'cjsupport'),
	    'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
	    'type' => 'text',
	    'id' => 'imap_email',
	    'label' => __('Email address <span class="red">*</span>', 'cjsupport'),
		'info' => __('<p>Specify email address.</p> <span class="red">NOTE: All unseen emails to this email address will be processed as tickets or comments. <br> If you are using an existing email address, please make sure the inbox is empty otherwise existing emails will also be converted to new tickets.</span>', 'cjsupport'),
	    'suffix' => '',
	    'prefix' => '',
	    'default' => cjsupport_post_default('imap_email', ''),
	    'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
	    'type' => 'password',
	    'id' => 'imap_password',
	    'label' => __('Password <span class="red">*</span>', 'cjsupport'),
	    'info' => __('Please enter your email password', 'cjsupport'),
	    'suffix' => '',
	    'prefix' => '',
	    'default' => cjsupport_post_default('imap_password', ''),
	    'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
	    'type' => 'text',
	    'id' => 'imap_server',
	    'label' => __('Incoming server name <span class="red">*</span>', 'cjsupport'),
	    'info' => __('Please specify imap incoming server name. e.g. imap.gmail.com', 'cjsupport'),
	    'suffix' => '',
	    'prefix' => '',
	    'default' => cjsupport_post_default('imap_server', ''),
	    'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
	    'type' => 'text',
	    'id' => 'imap_port',
	    'label' => __('Incoming server port <span class="red">*</span>', 'cjsupport'),
	    'info' => __('Please specify imap incoming server port for SSL/TLS. e.g. 993', 'cjsupport'),
	    'suffix' => '',
	    'prefix' => '',
	    'default' => cjsupport_post_default('imap_port', ''),
	    'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
	    'type' => 'radio',
	    'id' => 'imap_ssl',
	    'label' => __('Enable SSL/TLS <span class="red">*</span>', 'cjsupport'),
	    'info' => '',
	    'suffix' => '',
	    'prefix' => '',
	    'default' => cjsupport_post_default('imap_ssl', 'no'),
	    'options' => $yes_no_array, // array in case of dropdown, checkbox and radio buttons
	),
	array(
	    'type' => 'select',
	    'id' => 'departments',
	    'label' => __('Department <span class="red">*</span>', 'cjsupport'),
	    'info' => '',
	    'suffix' => '',
	    'prefix' => '',
	    'default' => cjsupport_post_default('departments', ''),
	    'options' => $department_options, // array in case of dropdown, checkbox and radio buttons
	),
	array(
	    'type' => 'multiselect',
	    'id' => 'products',
	    'label' => __('Products <span class="red">*</span>', 'cjsupport'),
	    'info' => '',
	    'suffix' => '',
	    'prefix' => '',
	    'default' => cjsupport_post_default('products', ''),
	    'options' => $product_options, // array in case of dropdown, checkbox and radio buttons
	),
	array(
	    'type' => 'submit',
	    'id' => 'add_route',
	    'label' => __('Test Connection & Save', 'cjsupport'),
	    'info' => '',
	    'suffix' => ' <a href="'.cjsupport_callback_url('cjsupport_email_routes').'" class="margin-10-left button-secondary">'.__('Go Back', 'cjsupport').'</a>',
	    'prefix' => '',
	    'default' => '',
	    'options' => '', // array in case of dropdown, checkbox and radio buttons
	),

);


echo '<form action="" method="post">';
echo cjsupport_admin_form_raw($configuration_form['form_fields']);
echo '</form>';