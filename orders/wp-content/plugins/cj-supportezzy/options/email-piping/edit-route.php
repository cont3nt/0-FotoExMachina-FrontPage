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


if(isset($_POST['edit_route'])){

	if(!cjsupport_is_email_valid($_POST['imap_email']) || $_POST['imap_email'] == ''){
		$errors['email'] = __('Invalid email address.', 'cjsupport');
	}

	$check_existing = email_exists($_POST['imap_email']);
	if($check_existing && $_POST['imap_email'] != urldecode($_GET['id'])){
		$errors['mailbox'] = __('Mailbox email should not be used for any user account on this website.', 'cjsupport');
	}

	if($_POST['imap_password'] == ''){
		$errors['password'] = __('Password field is missing.', 'cjsupport');
	}

	if($_POST['imap_server'] == ''){
		$errors['server'] = __('Server field is missing.', 'cjsupport');
	}

	if($_POST['imap_port'] == ''){
		$errors['port'] = __('Server port field is missing.', 'cjsupport');
	}

	if(!isset($_POST['departments'])){
		$errors['departments'] = __('Please assign departments for this route.', 'cjsupport');
	}

	foreach ($email_routes as $key => $route) {
		if($key != $_POST['imap_email'] && $_POST['departments'] == $route['departments']){
			$errors['departments'] = __('Selected department is already assigned to another mailbox.', 'cjsupport');
		}
	}


	if(!isset($_POST['products'])){
		$errors[] = __('Please assign products for this route.', 'cjsupport');
	}

	if(is_null($errors)){
		$ssl = ($_POST['imap_ssl'] == 'yes') ? '/ssl' : '';
		$server_string = '{'.$_POST['imap_server'].':'.$_POST['imap_port'].'/novalidate-cert/imap'.$ssl.'}INBOX';
		$mbox = @imap_open($server_string, $_POST['imap_email'], $_POST['imap_password']);

		if(is_array(imap_errors()) || is_array(imap_alerts())){
			$errors['imap'] = __('Could not connect to IMAP server, please check and try again.', 'cjsupport');
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
		}else{
			unset($email_routes[$_POST['imap_email']]);
			$new_routes = array_merge($email_routes, $email_route_data);
			cjsupport_update_option('cjsupport_email_routes', $new_routes);
			echo cjsupport_show_message('success', __('Connection to IMAP server was established and route is saved successfully.', 'cjsupport'));
		}
	}

}

$edit_for = urldecode($_GET['id']);
$email_routes = cjsupport_get_option('cjsupport_email_routes');
$form_info = $email_routes[$edit_for];

$configuration_form['form_fields'] = array(
	array(
	    'type' => 'sub-heading',
	    'id' => 'none',
	    'label' => '',
	    'info' => '',
	    'suffix' => '',
	    'prefix' => '',
	    'default' => __('Edit Route (IMAP configuration)', 'cjsupport'),
	    'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
	    'type' => 'text',
	    'id' => 'imap_email',
	    'label' => __('Email address <span class="red">*</span>', 'cjsupport'),
	    'info' => __('<p>Specify email address.</p> <span class="red">NOTE: All unseen emails to this email address will be processed as tickets or comments. <br> If you are using an existing email address, please make sure the inbox is empty otherwise existing emails will also be converted to new tickets.</span>', 'cjsupport'),
	    'suffix' => '',
	    'prefix' => '',
	    'default' => $form_info['imap_email'],
	    'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
	    'type' => 'password',
	    'id' => 'imap_password',
	    'label' => __('Password <span class="red">*</span>', 'cjsupport'),
	    'info' => __('Please enter your email password', 'cjsupport'),
	    'suffix' => '',
	    'prefix' => '',
	    'default' => $form_info['imap_password'],
	    'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
	    'type' => 'text',
	    'id' => 'imap_server',
	    'label' => __('Incoming server name <span class="red">*</span>', 'cjsupport'),
	    'info' => __('Please specify imap incoming server name. e.g. imap.gmail.com', 'cjsupport'),
	    'suffix' => '',
	    'prefix' => '',
	    'default' => $form_info['imap_server'],
	    'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
	    'type' => 'text',
	    'id' => 'imap_port',
	    'label' => __('Incoming server port <span class="red">*</span>', 'cjsupport'),
	    'info' => __('Please specify imap incoming server port for SSL/TLS. e.g. 993', 'cjsupport'),
	    'suffix' => '',
	    'prefix' => '',
	    'default' => $form_info['imap_port'],
	    'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
	    'type' => 'radio',
	    'id' => 'imap_ssl',
	    'label' => __('Enable SSL/TLS <span class="red">*</span>', 'cjsupport'),
	    'info' => '',
	    'suffix' => '',
	    'prefix' => '',
	    'default' => $form_info['imap_ssl'],
	    'options' => $yes_no_array, // array in case of dropdown, checkbox and radio buttons
	),
	array(
	    'type' => 'select',
	    'id' => 'departments',
	    'label' => __('Department <span class="red">*</span>', 'cjsupport'),
	    'info' => '',
	    'suffix' => '',
	    'prefix' => '',
	    'default' => $form_info['departments'],
	    'options' => $department_options, // array in case of dropdown, checkbox and radio buttons
	),
	array(
	    'type' => 'multiselect',
	    'id' => 'products',
	    'label' => __('Products <span class="red">*</span>', 'cjsupport'),
	    'info' => '',
	    'suffix' => '',
	    'prefix' => '',
	    'default' => $form_info['products'],
	    'options' => $product_options, // array in case of dropdown, checkbox and radio buttons
	),
	array(
	    'type' => 'submit',
	    'id' => 'edit_route',
	    'label' => __('Test Connection & Update', 'cjsupport'),
	    'info' => '',
	    'suffix' => ' <a href="'.cjsupport_callback_url('cjsupport_email_routes').'" class="margin-10-left button-secondary">'.__('Go Back', 'cjsupport').'</a>',
	    'prefix' => '',
	    'default' => '',
	    'options' => '', // array in case of dropdown, checkbox and radio buttons
	),

);


echo '<form action="'.cjsupport_callback_url('cjsupport_email_routes').'&do=edit-route&id='.urlencode($_GET['id']).'" method="post">';
echo cjsupport_admin_form_raw($configuration_form['form_fields']);
echo '</form>';