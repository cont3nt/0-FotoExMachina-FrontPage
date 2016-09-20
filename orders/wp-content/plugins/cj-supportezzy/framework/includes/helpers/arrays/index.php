<?php
function cjsupport_arrays($return){
	$arrays['yes_no'] = array(
		'yes' => __('Yes', 'cjsupport'),
		'no' => __('No', 'cjsupport'),
	);
	$arrays['enable_disable'] = array(
		'enable' => __('Enable', 'cjsupport'),
		'disable' => __('Disable', 'cjsupport'),
	);
	$arrays['on_off'] = array(
		'on' => __('On', 'cjsupport'),
		'off' => __('Off', 'cjsupport'),
	);

	$categories = get_categories();
	if(!empty($categories)){
		foreach ($categories as $key => $cat) {
			$arrays['categories'][$cat->term_id] = $cat->name;
		}
	}else{
		$arrays['categories']['none'] = __('No categories found', 'cjsupport');
	}


	return $arrays[$return];
}