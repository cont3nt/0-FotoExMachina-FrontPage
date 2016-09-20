<?php
function cjsupport_test_shortcode( $atts, $content) {
	global $wpdb, $current_user, $post;
	$defaults = array(
		'return' => null,
	);
	$atts = extract( shortcode_atts( $defaults ,$atts ) );

	$options = array(
		'stype' => 'closed', // single or closed
		'description' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa cum sociis natoque penatibus et magnis.',
		/*'default_content' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa cum sociis natoque penatibus et magnis.',
		'heading' => array(__('Heading Text', 'cjsupport'), 'heading', 'Default Value'),
		'paragraph' => array(__('Peragraph Text here: Lorem ipsum Magna ut est anim velit voluptate aliqua in commodo cillum sunt nostrud labore consectetur.', 'cjsupport'), 'paragraph', 'Default Value'),
		'textbox' => array(__('Textbox Input', 'cjsupport'), 'text', 'Default Value', 'information'),
		'textarea' => array(__('Textarea Input', 'cjsupport'), 'textarea', 'default value', 'information'),
		'dropdown' => array(__('Select Options', 'cjsupport'), 'dropdown', array('option 1' => 'option 1','option 2' => 'option 2'), 'information'),
		'color' => array(__('Pick Color', 'cjsupport'), 'color', null, 'information'),*/
		'number' => array(__('Number', 'cjsupport'), 'number', '101', 'information'),
	);

	if(!is_null($return) && $return == 'options'){ return serialize($options); } if(!is_null($return) && $return == 'defaults'){ return serialize($defaults); } foreach ($defaults as $key => $value) { if($$key == ''){ $$key = $defaults[$key]; }}
	$display[] = '';

	if($return == null){
	    return implode('', $display);
	}else{
	    return serialize($options);
	}

}
add_shortcode( 'cjsupport_test_shortcode', 'cjsupport_test_shortcode' );



function cjsupport_test_shortcode_two( $atts, $content) {
	global $wpdb, $current_user, $post;
	$defaults = array(
		'return' => null,
		'number' => 10,
		'textarea' => 'default texarea value',
	);
	$atts = extract( shortcode_atts( $defaults ,$atts ) );

	$options = array(
		'stype' => 'single', // single or closed
		'description' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa cum sociis natoque penatibus et magnis.',
		'default_content' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa cum sociis natoque penatibus et magnis.',
		'heading' => array(__('Heading Text', 'cjsupport'), 'heading', 'Default Value'),
		'paragraph' => array(__('Peragraph Text here: Lorem ipsum Magna ut est anim velit voluptate aliqua in commodo cillum sunt nostrud labore consectetur.', 'cjsupport'), 'paragraph', 'Default Value'),
		'textbox' => array(__('Textbox Input', 'cjsupport'), 'text', 'Default Value', 'information'),
		'textarea' => array(__('Textarea Input', 'cjsupport'), 'textarea', 'default value', 'information'),
		'dropdown' => array(__('Select Options', 'cjsupport'), 'dropdown', array('option 1' => 'option 1','option 2' => 'option 2'), 'information'),
		'number' => array(__('Number', 'cjsupport'), 'number', '101', 'information'),
		'color' => array(__('Pick Color', 'cjsupport'), 'color', null, 'information'),
	);

	if(!is_null($return) && $return == 'options'){ return serialize($options); } if(!is_null($return) && $return == 'defaults'){ return serialize($defaults); } foreach ($defaults as $key => $value) { if($$key == ''){ $$key = $defaults[$key]; }}



	$display[] = $number;
	$display[] = $textarea;

	if($return == null){
	    return implode('', $display);
	}else{
	    return serialize($options);
	}

}
add_shortcode( 'cjsupport_test_shortcode_two', 'cjsupport_test_shortcode_two' );