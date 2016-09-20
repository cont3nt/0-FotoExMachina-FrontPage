<?php
	global $wpdb;
	$table_form_fields = $wpdb->prefix.'cjsupport_form_fields';
	$form_fields = $wpdb->get_results("SELECT * FROM $table_form_fields ORDER BY field_order ASC");
	$action_url = cjsupport_string(cjsupport_callback_url($_GET['callback'])).'cjsupport_action=';

	$required_text = '<span class="red">'.__('*', 'cjsupport').'</span>';
	$next_order_query = $wpdb->get_row("SELECT * FROM $table_form_fields ORDER BY field_order DESC");
	if(!is_null($next_order_query)){
		$next_order = $next_order_query->field_order + 1;
	}else{
		$next_order = 1;
	}

	$required_array = array(
		'1' => __('Yes', 'cjsupport'),
		'0' => __('No', 'cjsupport'),
	);

	$field_type_array = array(
		'' => __('Please select a field type', 'cjsupport'),
		'text' => __('Single Line Text', 'cjsupport'),
		'textarea' => __('Multiline Line Text', 'cjsupport'),
		'dropdown' => __('Dropdown', 'cjsupport'),
		'multidropdown' => __('Multiple Dropdown', 'cjsupport'),
		// 'checkbox' => __('Checkbox', 'cjsupport'),
		'radio' => __('Radio', 'cjsupport'),
		'date' => __('Date', 'cjsupport'),
		'email' => __('Email', 'cjsupport'),
		'url' => __('Url', 'cjsupport'),
	);

	if(!isset($_GET['cjsupport_action'])){
		require_once('custom-fields/fields-list.php');
	}
	if(isset($_GET['cjsupport_action']) && $_GET['cjsupport_action'] == 'add-new'){
		require_once('custom-fields/fields-add-new.php');
	}
	if(isset($_GET['cjsupport_action']) && $_GET['cjsupport_action'] == 'edit' && $_GET['id'] != ''){
		require_once('custom-fields/fields-edit.php');
	}
	if(isset($_GET['cjsupport_action']) && $_GET['cjsupport_action'] == 'delete' && $_GET['id'] != ''){
		$wpdb->query("DELETE FROM $table_form_fields WHERE id = '{$_GET['id']}'");
		wp_redirect(cjsupport_callback_url($_GET['callback']));
		exit;
	}


?>

<script type="text/javascript">
	jQuery(document).ready(function($){
		$('#field_type').on('change', function(){
			var options_required = ['dropdown', 'multidropdown', 'checkbox', 'radio'];
			var field_type = $(this).val();
			if($.inArray(field_type, options_required) >= 0){
				$('#field_options').attr('required', 1);
			}else{
				$('#field_options').removeAttr('required');
			}

		});
	});
</script>