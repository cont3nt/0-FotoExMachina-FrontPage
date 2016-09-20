<?php
global $wpdb, $current_user;

$time = current_time('mysql');

$user_info = cjsupport_user_info($_POST['user_id']);

$errors = null;

if(!isset($_POST['internal_notes']) || $_POST['internal_notes'] == ''){
    $errors[] = __('Please specify your response.', 'cjsupport');
}
if(!isset($_POST['department']) || $_POST['department'] == ''){
    $errors[] = __('Please assign a department.', 'cjsupport');
}
if(!isset($_POST['product']) || $_POST['product'] == ''){
    $errors[] = __('Please assign a product.', 'cjsupport');
}

if(is_null($errors)){
    $data = array(
        'comment_post_ID' => $_POST['ticket_id'],
        'comment_author' => $user_info['user_login'],
        'comment_author_email' => $user_info['user_email'],
        'comment_author_url' => '',
        'comment_content' => $_POST['internal_notes'],
        'comment_type' => 'internal_note',
        'comment_parent' => 0,
        'user_id' => $user_info['ID'],
        'comment_date' => $time,
        'comment_approved' => 1,
    );

    if(isset($_POST['close'])){
        $update_post_data = array(
            'ID' => $_POST['ticket_id'],
            'post_status' => 'closed',
        );
        wp_update_post($update_post_data);
        update_post_meta($_POST['ticket_id'], '_closed_by', $user_info['ID']);
        update_post_meta($_POST['ticket_id'], 'ticket_status', $post_status);
    }

    $comment_id = wp_insert_comment($data);

    update_comment_meta( $comment_id, '_internal_note', 1 );

    $product = get_term_by('slug', $_POST['product'], 'cjsupport_products' );
    $department = get_term_by('slug', $_POST['department'], 'cjsupport_departments' );
    $client_notes = sprintf(__('Ticket transferred to %s for %s.', 'cjsupport'), $department->name, $product->name );
    update_comment_meta( $comment_id, '_client_notes', $client_notes );


    if(isset($_POST['attachments'])){
        update_comment_meta( $comment_id, '_attachments', $_POST['attachments'] );
    }

    wp_set_object_terms( $_POST['ticket_id'], $_POST['product'], 'cjsupport_products', false );
    wp_set_object_terms( $_POST['ticket_id'], $_POST['department'], 'cjsupport_departments', false );
    cjsupport_assign_ticket($_POST['ticket_id'], $_POST['department'], $_POST['product'], $_POST['agent']);


    $comment_info = cjsupport_comment_info($comment_id);
    $ticket_info = cjsupport_ticket_info($comment_info['post_ID']);

    // Send email to new agent
    $email_data = array(
        'to' => $ticket_info['agent_info']['user_email'],
        'from_name' => cjsupport_noreply('name', $ticket_info, cjsupport_override_noreply_agents()),
        'from_email' => cjsupport_noreply('email', $ticket_info, cjsupport_override_noreply_agents()),
        'subject' => cjsupport_parse_email_subject('transfer_info_subject_to_agent', $ticket_info['ID'], $comment_id),
        'message' => cjsupport_parse_email_message('transfer_info_to_agent', $ticket_info['ID'], $comment_id),
    );
    cjsupport_email($email_data);

    // Send email to client
    $email_data = array(
        'to' => $ticket_info['client_info']['user_email'],
        'from_name' => cjsupport_noreply('name', $ticket_info),
        'from_email' => cjsupport_noreply('email', $ticket_info),
        'subject' => cjsupport_parse_email_subject('transfer_info_subject_to_client', $ticket_info['ID'], $comment_id),
        'message' => cjsupport_parse_email_message('transfer_info_to_client', $ticket_info['ID'], $comment_id),
    );
    cjsupport_email($email_data);


    $return['status'] = 'success';
    $return['response'] = $comment_id;
}else{
    $return['status'] = 'errors';
    $return['response'] = implode('<br>', $errors);
}










