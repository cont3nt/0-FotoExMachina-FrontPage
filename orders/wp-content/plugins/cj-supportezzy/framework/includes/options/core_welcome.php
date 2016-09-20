<?php
/**
 * @package CSSJockey WordPress Framework
 * @author Mohit Aneja (cssjockey.com)
 * @version FW-20150208
*/
$help_support_value[] = '<a href="'.cjsupport_item_info('quick_start_guide_url').'" target="_blank">'.__('Quick Start Guide', 'cjsupport').'</a>';
$help_support_value[] = '<a href="'.cjsupport_item_info('documentation_url').'" target="_blank">'.__('Documentation', 'cjsupport').'</a>';
$help_support_value[] = '<a href="'.cjsupport_item_info('support_forum_url').'" target="_blank">'.__('Support Fourm', 'cjsupport').'</a>';

if(isset($_REQUEST['cjmsg']) && $_REQUEST['cjmsg'] == 'pc-no-match'){
	echo cjsupport_show_message('error', sprintf(__('Could not reset your license for this installation. <br><a target="_blank" href="%s">Click here</a> to create a support ticket.', 'cjsupport'), cjsupport_item_info('support_forum_url')));
}

$cjsupport_purchase_code = '';

$eon = sha1('cjsupport_verify_epc'.site_url());
$eov = get_option($eon);
if($eov != ''){
	$cjsupport_purchase_code = array(
		'type' => 'info',
		'id' => 'cjsupport_purchase_code',
		'label' => __('Purchase code', 'cjsupport'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => '<span class="btn btn-success btn-sm" style=" margin-right: 10px;"><i class="fa fa-check-circle"></i>&nbsp;&nbsp;'.__('Verified & Active', 'cjsupport').'</span>'.
					  sprintf('<a href="%s" class="btn btn-danger btn-sm cj-confirm" data-confirm="%s">Reset License</a>', cjsupport_string(cjsupport_callback_url('core_welcome')).'cjsupport_action=reset-license', __("Are you sure?\nThis will reset your license for this installation.\nYou may use this license again on any installation.", 'cjsupport')),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	);
}else{
	$cjsupport_purchase_code = array(
		'type' => 'info',
		'id' => 'cjsupport_purchase_code',
		'label' => __('Purchase code', 'cjsupport'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => '<span class="label label-danger" style="padding:5px 10px; font-size:14px; font-weight:normal;"><i class="fa fa-times-circle"></i>&nbsp;&nbsp;'.__('Not Verified', 'cjsupport').'</span>',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	);
}

if(cjsupport_is_local()){
	$cjsupport_purchase_code = array(
		'type' => 'info',
		'id' => 'cjsupport_purchase_code',
		'label' => __('Purchase code', 'cjsupport'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => '<span class="label label-default" style="padding:5px 10px; font-size:14px; font-weight:normal;"><i class="fa fa-info-circle"></i>&nbsp;&nbsp;'.__('Local Server', 'cjsupport').'</span><span class="margin-10-left italic red">'.__('You will be asked to validate your purchase code to use this product on live server.', 'cjsupport').'</span>',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	);
}

if(cjsupport_item_info('item_id') == 'NA'){
	$cjsupport_purchase_code = null;
	$contribute = null;
}else{
	$contribute = array(
		'type' => 'info',
		'id' => 'contribute',
		'label' => __('Contribute', 'cjsupport'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => sprintf(__('<p>You can contribute to support further development and new features for this product.</p> <p><a target="_blank" class="btn btn-danger" href="%s">Contribute</a></p>', 'cjsupport'), 'http://cssjockey.com/contribute'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	);
}

$localization_string = sprintf(__('You can download <a class="bold" target="_blank" href="%s">Loco Translate WordPress Plugin (FREE)</a> to easily create language files within your WordPress dashboard without using Poedit software.', 'cjsupport'), 'https://wordpress.org/plugins/loco-translate/');

$cjsupport_form_options['welcome'] = array(
	array(
		'type' => 'sub-heading',
		'id' => 'welcome_heading',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => sprintf(__('%s Information', 'cjsupport'), ucwords(cjsupport_item_info('item_type'))),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'info',
		'id' => 'product_type',
		'label' => __('Product Type', 'cjsupport'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => sprintf(__('WordPress %s', 'cjsupport'), ucwords(cjsupport_item_info('item_type'))),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'info',
		'id' => 'product_name',
		'label' => __('Product Name', 'cjsupport'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => cjsupport_item_info('item_name'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'info',
		'id' => 'product_id',
		'label' => __('Product ID', 'cjsupport'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => cjsupport_item_info('item_id'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'info',
		'id' => 'version',
		'label' => __('Installed Version', 'cjsupport'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => cjsupport_item_info('item_version'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	$cjsupport_purchase_code,
	array(
		'type' => 'info',
		'id' => 'license',
		'label' => __('License & Terms of use', 'cjsupport'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => sprintf(__('<a target="_blank" href="%s">Click here</a> to view license and terms of use.', 'cjsupport'), cjsupport_item_info('license_url')),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'info',
		'id' => 'help_support',
		'label' => __('Help & Support', 'cjsupport'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => implode(' &nbsp; &bull; &nbsp; ', $help_support_value),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'info',
		'id' => 'translate',
		'label' => __('Localization', 'cjsupport'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => $localization_string,
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	$contribute,
);
cjsupport_admin_form_raw($cjsupport_form_options['welcome']);


do_action('cjsupport_after_welcome_panel');
