<?php
function cjsupport_run_app( $atts, $content) {
	global $wpdb, $current_user, $post;
	$defaults = array(
		'return' => null,
		'height' => '600px',
	);
	$atts = extract( shortcode_atts( $defaults ,$atts ) );

	$options = array(
		'stype' => 'single', // single or closed
		'description' => __('This shortcode will run the app within a page or post.', 'cjsupport'),
		/*'default_content' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa cum sociis natoque penatibus et magnis.',
		'heading' => array(__('Heading Text', 'cjsupport'), 'heading', 'Default Value'),
		'paragraph' => array(__('Peragraph Text here: Lorem ipsum Magna ut est anim velit voluptate aliqua in commodo cillum sunt nostrud labore consectetur.', 'cjsupport'), 'paragraph', 'Default Value'),
		'textbox' => array(__('Textbox Input', 'cjsupport'), 'text', 'Default Value', 'information'),
		'textarea' => array(__('Textarea Input', 'cjsupport'), 'textarea', 'default value', 'information'),
		'dropdown' => array(__('Select Options', 'cjsupport'), 'dropdown', array('option 1' => 'option 1','option 2' => 'option 2'), 'information'),
		'color' => array(__('Pick Color', 'cjsupport'), 'color', null, 'information'),*/
		'height' => array(__('Height', 'cjsupport'), 'text', '500px', __('Specify height in pixels. e.g. 500px', 'cjsupport')),
	);

	if(!is_null($return) && $return == 'options'){ return serialize($options); } if(!is_null($return) && $return == 'defaults'){ return serialize($defaults); } foreach ($defaults as $key => $value) { if($$key == ''){ $$key = $defaults[$key]; }}

	$display[] = cjsupport_embed_app($height);

	if($return == null){
	    return implode(null, $display);
	}else{
	    return serialize($options);
	}

}
add_shortcode( 'cjsupport_run_app', 'cjsupport_run_app' );