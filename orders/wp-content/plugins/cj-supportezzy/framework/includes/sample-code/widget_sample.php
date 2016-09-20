<?php
global $cjsupport_sample_widget_args;
$cjsupport_sample_widget_args = array(
	'title' => __('Social Media (cjsupport)', 'cjsupport'),
	'description' => __('Display social media links with or without icons', 'cjsupport'),
	'classname' => 'social-media',
	'width' => '250',
	'height' => '350',
	'form_options' => array(
		array(
			'type' => 'text',
			'id' => 'title',
			'label' => __('Title', 'cjsupport'),
			'info' => __('Specify widget title here.', 'cjsupport'),
			'suffix' => '',
			'prefix' => '',
			'default' => '',
			'options' => '', // array in case of dropdown, checkbox and radio buttons
		),
		array(
			'type' => 'textgroup',
			'id' => 'facebook',
			'label' => __('facebook', 'cjsupport'),
			'info' => __('Specify widget facebook here.', 'cjsupport'),
			'suffix' => '',
			'prefix' => '',
			'default' => '',
			'options' => array(
				'link_text', 'link_url', 'link_image'
			), // array in case of dropdown, checkbox and radio buttons
		),
		array(
			'type' => 'text',
			'id' => 'sub_title',
			'label' => __('sub_title', 'cjsupport'),
			'info' => __('Specify widget title here.', 'cjsupport'),
			'suffix' => '',
			'prefix' => '',
			'default' => '',
			'options' => '', // array in case of dropdown, checkbox and radio buttons
		),
	) // form array
);

class cjsupport_sample_widget_widget extends WP_Widget {

    /** constructor */
    function cjsupport_sample_widget_widget() {
    	global $cjsupport_sample_widget_args;
		$widget_ops = array('classname' => $cjsupport_sample_widget_args['classname'], 'description' => $cjsupport_sample_widget_args['description']);
		$control_ops = array('width' => $cjsupport_sample_widget_args['width'], 'height' => $cjsupport_sample_widget_args['height']);
		$this->WP_Widget($cjsupport_sample_widget_args['classname'], $cjsupport_sample_widget_args['title'] , $widget_ops, $control_ops);
    }

	/** @see WP_Widget::widget */
	function widget($args, $instance) {
		global $cjsupport_sample_widget_args;
		extract( $args );

		foreach ($cjsupport_sample_widget_args['form_options'] as $key => $option) {
			$vars[$option['id']] = apply_filters('title', $instance[$option['id']]);
		}

		$display[] = $before_widget;
		$display[] = ($vars['title'] != '') ? $before_title.$vars['title'].$after_title : '';
		$display[] = cjsupport_sample_widget_display($vars);
		$display[] = $after_widget;

		echo implode("\n", $display);
	}

	/** @see WP_Widget::update */
	function update($new_instance, $old_instance) {
		global $cjsupport_sample_widget_args;
		$instance = $old_instance;
		foreach ($cjsupport_sample_widget_args['form_options'] as $key => $option) {
			$id = $option['id'];
			if(is_array($new_instance[$id])){
				$instance[$id] = implode('~~~~~', $new_instance[$id]);
			}else{
				$instance[$id] = strip_tags($new_instance[$id]);
			}
		}
	    return $instance;
	}

	/** @see WP_Widget::form */
	function form($instance) {
		global $cjsupport_sample_widget_args;
		foreach ($cjsupport_sample_widget_args['form_options'] as $key => $value) {
			$id = $value['id'];
			$form_instance[$id]['id'] = $this->get_field_id($id);
			$form_instance[$id]['name'] = $this->get_field_name($id);
			$form_instance[$id]['value'] = @esc_attr( $instance[$id] );
		}

	    echo cjsupport_widget_form($cjsupport_sample_widget_args['form_options'], $form_instance);
	}

}
add_action('widgets_init', create_function('', 'return register_widget("cjsupport_sample_widget_widget");'));


function cjsupport_sample_widget_display($vars){
	if(is_array($vars)){
		foreach ($vars as $key => $var) {
			if(strpos($var, '~~~~~') > 0){
				$$key = explode('~~~~~', $var);
			}else{
				$$key = $var;
			}
		}
	}

	// Start display function here...
	$display[] = '';


	return  implode("\n", $display);
}
