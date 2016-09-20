<?php

	$error_message = '';
	$success_message = '';

	$field_data = $wpdb->get_row("SELECT * FROM $table_form_fields WHERE id = '{$_GET['id']}'");
	if(is_null($field_data)){
		wp_redirect(cjsupport_callback_url($_GET['callback']));
		exit;
	}

	if(isset($_POST['edit_field'])){
		$errors = null;
 		$required_fields = array('field_type' ,'field_id' ,'field_label');
 		$options_required = array('dropdown', 'multidropdown', 'checkbox', 'radio');

 		$parsed_field_id = strtolower(str_replace('-', '_', sanitize_title($_POST['field_id'])));

 		foreach ($required_fields as $rk => $rv) {
 			if($_POST[$rv] == ''){
 				$errors['missing'] = __('Missing required fields', 'cjsupport');
 			}
 		}

 		if(in_array($_POST['field_type'], $options_required) && $_POST['field_options'] == ''){
 			$errors['options'] = __('You must specify options for this field.', 'cjsupport');
 		}

 		$check_existing = $wpdb->get_row("SELECT * FROM $table_form_fields WHERE field_id = '{$parsed_field_id}'");
 		if(!is_null($check_existing) && $check_existing->field_id != $_POST['field_id']){
 			$errors['existing'] = __('Field with same unique id already exists.', 'cjsupport');
 		}


 		if(!is_null($errors)){
 			$error_message = cjsupport_show_message('error', implode('<br>', $errors));
 		}

 		if(is_null($errors)){
 			$field_data = array(
 				'field_type' => $_POST['field_type'],
 				'field_id' => $parsed_field_id,
 				'field_label' => $_POST['field_label'],
 				'field_info' => $_POST['field_info'],
 				'field_options' => $_POST['field_options'],
 				'field_order' => $_POST['field_order'],
 				'field_required' => $_POST['field_required'],

 			);
 			cjsupport_update($table_form_fields, $field_data, 'id', $_GET['id']);
 			wp_redirect(cjsupport_callback_url($_GET['callback']));
 			exit;
 		}

	}


	$form_fields['edit'] = array(
		array(
		    'type' => 'sub-heading',
		    'id' => '',
		    'label' => '',
		    'info' => '',
		    'suffix' => '',
		    'prefix' => '',
		    'default' => __('Edit Field', 'cjsupport'),
		    'options' => '', // array in case of dropdown, checkbox and radio buttons
		),
		array(
		    'type' => 'dropdown',
		    'id' => 'field_type',
		    'label' => sprintf(__('Field Type %s', 'cjsupport'), $required_text),
		    'info' => '',
		    'suffix' => '',
		    'prefix' => '',
		    'params' => array('required' => 1),
		    'default' => cjsupport_post_default('field_type', $field_data->field_type),
		    'options' => $field_type_array, // array in case of dropdown, checkbox and radio buttons
		),
		array(
		    'type' => 'text',
		    'id' => 'field_id',
		    'label' => sprintf(__('Unique ID %s', 'cjsupport'), $required_text),
		    'info' => '<span class="red italic">'.__('Must not use spaces or any special character other than underscores.', 'cjsupport').'<span>',
		    'suffix' => '',
		    'prefix' => '',
		    'params' => array('required' => 1),
		    'default' => cjsupport_post_default('field_id', $field_data->field_id),
		    'options' => '', // array in case of dropdown, checkbox and radio buttons
		),
		array(
		    'type' => 'text',
		    'id' => 'field_label',
		    'label' => sprintf(__('Field Label %s', 'cjsupport'), $required_text),
		    'info' => '',
		    'suffix' => '',
		    'prefix' => '',
		    'params' => array('required' => 1),
		    'default' => cjsupport_post_default('field_label', $field_data->field_label),
		    'options' => '', // array in case of dropdown, checkbox and radio buttons
		),
		array(
		    'type' => 'textarea',
		    'id' => 'field_info',
		    'label' => __('Field Description', 'cjsupport'),
		    'info' => '',
		    'suffix' => '',
		    'prefix' => '',
		    'default' => cjsupport_post_default('field_info', $field_data->field_info),
		    'options' => '', // array in case of dropdown, checkbox and radio buttons
		),
		array(
		    'type' => 'textarea',
		    'id' => 'field_options',
		    'label' => __('Field Options', 'cjsupport'),
		    'info' => __('Specify each option per line.<br><span class="red italic">Required in case of dropdown, checkbox, radio buttons.</span>', 'cjsupport'),
		    'suffix' => '',
		    'params' => array('placeholder' => __("Option One\nOption Two\nand so on..", 'cjsupport')),
		    'prefix' => '',
		    'default' => cjsupport_post_default('field_options', $field_data->field_options),
		    'options' => '', // array in case of dropdown, checkbox and radio buttons
		),
		array(
		    'type' => 'number',
		    'id' => 'field_order',
		    'label' => __('Sort Order', 'cjsupport'),
		    'info' => '',
		    'suffix' => '',
		    'params' => array('min' => 0),
		    'prefix' => '',
		    'default' => cjsupport_post_default('field_order', $field_data->field_order),
		    'options' => '', // array in case of dropdown, checkbox and radio buttons
		),
		array(
		    'type' => 'dropdown',
		    'id' => 'field_required',
		    'label' => __('Required', 'cjsupport'),
		    'info' => '',
		    'suffix' => '',
		    'prefix' => '',
		    'default' => cjsupport_post_default('field_required', $field_data->field_required),
		    'options' => $required_array, // array in case of dropdown, checkbox and radio buttons
		),
		array(
		    'type' => 'submit',
		    'id' => 'edit_field',
		    'label' => __('Edit Field', 'cjsupport'),
		    'info' => '',
		    'suffix' => sprintf(__('<a href="%s" class="button-secondary margin-10-left">Cancel</a>', 'cjsupport'), cjsupport_callback_url($_GET['callback'])),
		    'prefix' => '',
		    'default' => '',
		    'options' => '', // array in case of dropdown, checkbox and radio buttons
		),
	);

	echo $error_message;
	echo $success_message;

	echo '<form action="" method="post">';
	echo cjsupport_admin_form_raw($form_fields['edit'], false);
	echo '</form>';

?>