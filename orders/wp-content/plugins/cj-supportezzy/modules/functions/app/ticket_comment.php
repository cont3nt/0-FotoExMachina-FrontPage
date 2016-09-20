<?php
global $wpdb, $current_user;

$time = current_time('mysql');

$user_info = cjsupport_user_info($_POST['user_id']);
$user_type = cjsupport_user_type($current_user->ID);

$errors = null;

if($_POST['content'] == ''){
    $errors[] = __('Please specify your response.', 'cjsupport');
}

if(is_null($errors)){

    $data = array(
        'comment_post_ID' => $_POST['ticket_id'],
        'comment_author' => $user_info['user_login'],
        'comment_author_email' => $user_info['user_email'],
        'comment_author_url' => '',
        'comment_content' => $_POST['content'],
        'comment_type' => 'comment',
        'comment_parent' => 0,
        'user_id' => $user_info['ID'],
        'comment_date' => $time,
        'comment_approved' => 1,
    );

    update_post_meta($_POST['ticket_id'], '_awaiting_response_from', $_POST['user_id']);

    if(isset($_POST['close']) && $_POST['close'] == 'close'){
        $update_post_data = array(
            'ID' => $_POST['ticket_id'],
            'post_status' => 'closed'
        );
        wp_update_post($update_post_data );
        update_post_meta($_POST['ticket_id'], '_ticket_status', 'closed');
        update_post_meta($_POST['ticket_id'], '_closed_by', $_POST['user_id']);
        $redirect_close = 'closed';
    }else{
        $update_post_data = array(
            'ID' => $_POST['ticket_id'],
            'post_status' => 'publish'
        );
        wp_update_post($update_post_data );
        update_post_meta($_POST['ticket_id'], '_ticket_status', 'publish');
        delete_post_meta($_POST['ticket_id'], '_closed_by');
        $redirect_close = 'no';
    }


    $comment_id = wp_insert_comment($data);

    $comment_attachment_link = null;

    if(isset($_POST['attachments'])){
        $attachments = explode(',', $_POST['attachments']);
        update_comment_meta( $comment_id, '_attachments', $attachments );
    }



    // Email piping attachments
    // Email piping attachments
    $communication_setup = cjsupport_get_option('cjsupport_communication_setup');
    if($communication_setup == 'imap'){
        $comment_attachment_url = get_comment_meta($comment_id, '_attachments', true);
        $comment_attachment_url = is_serialized($comment_attachment_url) ? unserialize($comment_attachment_url) : $comment_attachment_url;
        if( is_array($comment_attachment_url) && $comment_attachment_url[0] != '' ){
            if(is_array($comment_attachment_url)){
                $comment_attachment_link = '<p class="attachments-panel"><b>'.__('Attachments:', 'cjsupport').'</b><br>';
                foreach ($comment_attachment_url as $key => $link) {
                    $comment_attachment_link .= '<a href="'.cjsupport_item_path('item_url').'/download.php?download='.urlencode($link).'">'.basename($link).'</a><br>';
                }
                $comment_attachment_link .= '</p>';
            }
        }else{
            $comment_attachment_link = '';
        }
    }

    $comment_info = cjsupport_comment_info($comment_id);
    $ticket_info = cjsupport_ticket_info($comment_info['post_ID']);

    if($user_type == 'agent' || $user_type == 'admin'){
        // send email to agent about new comment
        $client_email_data = array(
            'to' => $ticket_info['client_info']['user_email'],
            'from_name' => cjsupport_noreply('name', $ticket_info),
            'from_email' => cjsupport_noreply('email', $ticket_info),
            'subject' => cjsupport_parse_email_subject('new_comment_subject_to_client', $ticket_info['ID'], $comment_id),
            'message' => cjsupport_parse_email_message('new_comment_to_client', $ticket_info['ID'], $comment_id).$comment_attachment_link,
        );
        cjsupport_email($client_email_data);
    }

    if($user_type == 'client'){
        // send email to agent about new comment
        $agent_email_data = array(
            'to' => $ticket_info['agent_info']['user_email'],
            'from_name' => cjsupport_noreply('name', $ticket_info, cjsupport_override_noreply_agents()),
            'from_email' => cjsupport_noreply('email', $ticket_info, cjsupport_override_noreply_agents()),
            'subject' => cjsupport_parse_email_subject('new_comment_subject_to_agent', $ticket_info['ID'], $comment_id),
            'message' => cjsupport_parse_email_message('new_comment_to_agent', $ticket_info['ID'], $comment_id).$comment_attachment_link,
        );
        cjsupport_email($agent_email_data);

    }


    $return['status'] = 'success';
    $return['response'] = $redirect_close;
}else{
    $return['status'] = 'errors';
    $return['response'] = implode('<br>', $errors);
}