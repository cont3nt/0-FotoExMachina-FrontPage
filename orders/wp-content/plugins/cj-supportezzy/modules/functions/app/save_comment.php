<?php
global $wpdb, $current_user;

$errors = null;

if($_POST['content'] == ''){
    $errors[] = __('Please specify your response.', 'cjsupport');
}

if(is_null($errors)){
    $comment_content = $_POST['content'];
    $wpdb->query("UPDATE $wpdb->comments SET comment_content = '{$comment_content}' WHERE comment_ID = '{$_POST['comment_ID']}'");

    $comment_id = $_POST['comment_ID'];

    $user_type = cjsupport_user_type($current_user->ID);

    if($user_type == 'client'){
        // send email to agent about new comment
        $comment_info = cjsupport_comment_info($comment_id);
        $ticket_info = cjsupport_ticket_info($comment_info['post_ID']);
        $email_data = array(
            'to' => $ticket_info['agent_info']['user_email'],
            'from_name' => cjsupport_noreply('name', $ticket_info, cjsupport_override_noreply_agents()),
            'from_email' => cjsupport_noreply('email', $ticket_info, cjsupport_override_noreply_agents()),
            'subject' => cjsupport_parse_email_subject('update_comment_subject_to_agent', $ticket_info['ID'], $comment_id),
            'message' => cjsupport_parse_email_message('update_comment_to_agent', $ticket_info['ID'], $comment_id),
        );
        cjsupport_email($email_data);
    }

    if($user_type == 'agent' || $user_type == 'admin'){
        // send email to client about new comment
        $comment_info = cjsupport_comment_info($comment_id);
        $ticket_info = cjsupport_ticket_info($comment_info['post_ID']);
        $email_data = array(
            'to' => $ticket_info['client_info']['user_email'],
            'from_name' => cjsupport_noreply('name', $ticket_info),
            'from_email' => cjsupport_noreply('email', $ticket_info),
            'subject' => cjsupport_parse_email_subject('update_comment_subject_to_client', $ticket_info['ID'], $comment_id),
            'message' => cjsupport_parse_email_message('update_comment_to_client', $ticket_info['ID'], $comment_id),
        );
        cjsupport_email($email_data);
    }


    $return['status'] = 'success';
    $return['response'] = $_POST;
}else{
    $return['status'] = 'errors';
    $return['response'] = implode('<br>', $errors);
}