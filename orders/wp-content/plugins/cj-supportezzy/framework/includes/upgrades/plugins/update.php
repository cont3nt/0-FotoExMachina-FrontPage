<?php
require_once ('class-plugin-update.php');
$item_info = cjsupport_item_info();
$cjsupport_eon = sha1('cjsupport_verify_epc'.site_url());
$cjsupport_eov = get_option($cjsupport_eon);
$cjsupport_upgrade_url = 'http://api.cssjockey.com/?cj_action=upgrades&item_id='.$item_info['item_id'].'&item_type='.$item_info['item_type'].'&purchase_code='.$cjsupport_eov.'&slug='.basename(cjsupport_item_path('item_dir')).'&site_url='.site_url();
$cjsupport_plugin_upgrades = new PluginUpdateChecker(
	$cjsupport_upgrade_url,
	cjsupport_item_path('item_dir').'/index.php',
	basename(cjsupport_item_path('item_dir'))
);