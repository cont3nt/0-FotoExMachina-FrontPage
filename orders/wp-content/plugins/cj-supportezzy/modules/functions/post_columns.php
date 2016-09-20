<?php
/*function cjsupport_ticket_rating($post_id){
	return __('--', 'cjsupport');
}

// Add extra columns to edit menu screen
add_filter( 'manage_edit-cjsupport_columns', 'my_edit_tickets_columns' ) ;

function my_edit_tickets_columns( $columns ) {
	$return = array(
		'cb' => '<input type="checkbox" />',
	    'title' => __('Title', 'cjsupport'),
	    'taxonomy-cjsupport_products' => __('Products', 'cjsupport'),
	    'taxonomy-cjsupport_departments' => __('Departments', 'cjsupport'),
	    'assigned_to' => __('Assigned To', 'cjsupport'),
	    'comments' => __('Replies', 'cjsupport'),
	    'date' => __('Submitted', 'cjsupport'),
	);
	return $return;
}

add_action( 'manage_cjsupport_posts_custom_column', 'my_manage_tickets_columns', 10, 2 );

function my_manage_tickets_columns( $column, $post_id ) {
    switch ( $column ) {

        case 'featured_image' :
        	$featured_image = cjrm_post_featured_image($post_id, 'thumbnail', true);
            echo '<img src="'.$featured_image.'" width="50px" />';
            break;

        case 'assigned_to' :
        	$assigned_to = get_post_meta($post_id, '_assigned_to', true);
            echo '<a target="_blank" href="'.admin_url('user-edit.php?user_id='.$assigned_to).'"><b>'.cjsupport_user_info($assigned_to, 'display_name').'</b></a>';
            echo '<br>'.cjsupport_user_info($assigned_to, 'user_email');
            break;
    }
}*/