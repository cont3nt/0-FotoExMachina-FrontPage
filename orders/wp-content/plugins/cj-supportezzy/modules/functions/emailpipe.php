<?php
global $attachments_dir;

$imap_mailboxes = cjsupport_get_option('cjsupport_email_routes');

if(is_array($imap_mailboxes)){
    $imap_class = cjsupport_item_path('item_dir').'/assets/helpers/emailpipe/src/ImapMailbox.php';
    require_once($imap_class);
}

$path = wp_upload_dir();
$attachments_dir = $path['basedir'].'/cjsupportezzy';

if(!is_dir($attachments_dir)){
    @mkdir($attachments_dir);
}


if(is_array($imap_mailboxes)){
    foreach ($imap_mailboxes as $key => $mailbox) {
        cjsupport_imap_process_mails($mailbox);
        sleep(0.5);
    }
}

function cjsupport_imap_process_mails($mailbox){
    global $wpdb, $attachments_dir;

    // IMAP must be enabled in Google Mail Settings
    $imap_email = $mailbox['imap_email'];
    $imap_password = $mailbox['imap_password'];
    $imap_server = $mailbox['server_string'];

    $imapResource = new ImapMailbox($imap_server, $imap_email, $imap_password, $attachments_dir, 'utf-8');
    $mails = array();
    // Get some mail
    $new_mail_ids = $imapResource->searchMailBox('ALL UNSEEN');

    //$new_mail_ids = $imapResource->searchMailBox('BODY "Not looking to do that at all"');
    if($new_mail_ids) {
        $count = 0;
        $saved_last_mail_id = get_option('cjsupportezzy_last_mail_id');
        foreach ($new_mail_ids as $key => $mailId) {
            $last_mail_id = ($saved_last_mail_id == '') ? 0 : $saved_last_mail_id;
            if($mailId != $last_mail_id){
                $count++;
                $mail = $imapResource->getMail($mailId);
                $attachments = $mail->getAttachments();

                $attachments = cjsupport_get_email_attachments($attachments);

                $sender_info = cjsupport_imap_user($mail);

                $ticket_uid = cjsupport_get_ticketuid($mail->subject, '[', ']');
                if(is_null($ticket_uid)){
                    if(!cjsupport_imap_check_existing_ticket($mailId)){
                        cjsupport_imap_create_ticket($mail, $attachments, $sender_info, $mailbox);
                        $imapResource->deleteMail($mailId);
                    }
                }else{
                    $ticket_post_id = cjsupport_get_ticket_by_uid($ticket_uid);
                    $ticket_info = cjsupport_ticket_info($ticket_post_id);
                    $email_body = cjsupport_get_email_message($mail);
                    $check_comment_meta = $wpdb->get_row("SELECT * FROM $wpdb->commentmeta WHERE meta_key = '_imap_id' AND meta_value = '{$mailId}'");
                    if(is_null($check_comment_meta)){
                        cjsupport_add_ticket_comment($ticket_info, $email_body, $sender_info, $attachments, $mailId);
                        $imapResource->deleteMail($mailId);
                    }
                }
            }
        }
    }
}


function cjsupport_get_email_message($mail){
    $string = preg_replace('/(^\w.+:\n)?(^>.*(\n|$))+/mi', '', $mail->textPlain);
    $paragraphs = '';
    $str_array = array_unique(explode("\n", $string));
    foreach ($str_array as $line) {
        if (trim($line) && !empty($line)) {
            $paragraphs .= '<p>' . $line . '</p>';
        }
    }
    $paragraphs = strip_tags($paragraphs, '<p><b><i><strong><a><img><ul><li><ol>');
    $paragraphs = explode("<p>", $paragraphs);
    $paragraphs = str_replace('wrote:', '', $paragraphs);
    return implode('<p>', $paragraphs);
}

/*function cjsupport_get_email_message($mail){
    $string = preg_replace('/(^\w.+:\n)?(^>.*(\n|$))+/mi', '', $mail->textHtml);
    return strip_tags($string, '<p><b><strong><i><a><div><table><tr><td><th>');
}*/

function cjsupport_get_ticketuid($src, $start, $end){
    $txt = explode($start,$src);
    if(isset($txt[1])){
        $txt2 = explode($end, $txt[1]);
        return trim($txt2[0]);
    }else{
        return null;
    }
}

function cjsupport_get_ticket_by_uid($uid){
    global $wpdb;
    $query = $wpdb->get_row("SELECT * FROM $wpdb->postmeta WHERE meta_key = '_uid' AND meta_value = '{$uid}'");
    if($query){
        return $query->post_id;
    }else{
        return null;
    }
}


function cjsupport_add_ticket_comment($ticket_info, $email_body, $sender_info, $attachments, $mailId){

    $time = current_time('mysql');

    $comment_data = array(
        'comment_post_ID' => $ticket_info['ID'],
        'comment_author' => $sender_info['user_login'],
        'comment_author_email' => $sender_info['user_email'],
        'comment_content' => $email_body,
        'comment_type' => 'comment',
        'comment_parent' => 0,
        'user_id' => $sender_info['ID'],
        'comment_date' => $time,
        'comment_approved' => 1,
    );

    update_post_meta($ticket_info['ID'], '_awaiting_response_from', $sender_info['ID']);

    $comment_id = wp_insert_comment($comment_data);

    update_comment_meta( $comment_id, '_via_email', 1 );
    update_comment_meta( $comment_id, '_imap_id', $mailId );

    if(!is_null($attachments)){
        update_comment_meta( $comment_id, '_attachments', $attachments);
    }



    // Email piping attachments
    $comment_attachment_link = null;
    $communication_setup = cjsupport_get_option('cjsupport_communication_setup');
    if($communication_setup == 'imap'){
        $comment_attachment_url = get_comment_meta($comment_id, '_attachments', true);
        if($comment_attachment_url){
            if( $comment_attachment_url && is_serialized($comment_attachment_url) ){
                if(is_array($comment_attachment_url)){
                    $comment_attachment_link = '<p class="attachments-panel"><b>'.__('Attachments:', 'cjsupport').'</b><br>';
                    foreach (unserialize($comment_attachment_url) as $key => $link) {
                        $comment_attachment_link .= '<a href="'.cjsupport_item_path('item_url').'/download.php?download='.urlencode($link).'">'.basename($link).'</a><br>';
                    }
                    $comment_attachment_link .= '</p>';
                }
            }else{
                $comment_attachment_link = '<p class="attachments-panel"><b>'.__('Attachments:', 'cjsupport').'</b><br><a href="'.cjsupport_item_path('item_url').'/download.php?download='.urlencode($comment_attachment_url).'">'.basename($comment_attachment_url).'</a></p>';
            }
        }
    }

    $user_type = $sender_info['user_type'];
    if($user_type == 'client'){
        // send email to agent about new comment
        $comment_info = cjsupport_comment_info($comment_id);
        $ticket_info = cjsupport_ticket_info($comment_info['post_ID']);
        $email_data = array(
            'to' => $ticket_info['agent_info']['user_email'],
            'from_name' => cjsupport_noreply('name', $ticket_info, cjsupport_override_noreply_agents()),
            'from_email' => cjsupport_noreply('email', $ticket_info, cjsupport_override_noreply_agents()),
            'subject' => cjsupport_parse_email_subject('new_comment_subject_to_agent', $ticket_info['ID'], $comment_id),
            'message' => cjsupport_parse_email_message('new_comment_to_agent', $ticket_info['ID'], $comment_id).$comment_attachment_link,
        );
        cjsupport_email($email_data);
    }

    if($user_type == 'agent'){
        // send email to agent about new comment
        $comment_info = cjsupport_comment_info($comment_id);
        $ticket_info = cjsupport_ticket_info($comment_info['post_ID']);
        $email_data = array(
            'to' => $ticket_info['client_info']['user_email'],
            'from_name' => cjsupport_noreply('name', $ticket_info),
            'from_email' => cjsupport_noreply('email', $ticket_info),
            'subject' => cjsupport_parse_email_subject('new_comment_subject_to_client', $ticket_info['ID'], $comment_id),
            'message' => cjsupport_parse_email_message('new_comment_to_client', $ticket_info['ID'], $comment_id).$comment_attachment_link,
        );
        cjsupport_email($email_data);

    }
}

function cjsupport_imap_create_ticket($mail, $attachments, $sender_info, $mailbox){
    global $wpdb;

    $post_data = array(
        'post_content'   => cjsupport_get_email_message($mail),
        'post_name'      => sanitize_title( $mail->subject ),
        'post_title'     => $mail->subject,
        'post_status'    => 'publish',
        'post_type'      => 'cjsupport',
        'post_author'    => $sender_info['ID'],
        'ping_status'    => 'closed',
        'post_excerpt'   => cjsupport_trim_text(strip_tags($mail->textHtml), 160, '...'),
        'comment_status' => 'open'
    );

    $post = wp_insert_post( $post_data );
    $post = get_post($post);

    update_post_meta($post->ID, '_imap_id', $mail->id);

    $rand_product = array_rand($mailbox['products']);
    $product = $mailbox['products'][$rand_product];
    if($product == 'all'){
        $all_products = array_keys(cjsupport_products_array());
        $rand_product = array_rand($all_products);
        $product = $all_products[$rand_product];
    }

    $department = $mailbox['departments'];

    if($department == 'all'){
        $all_departments = cjsupport_departments_array();
        $department = array_rand($all_departments);
    }

    wp_set_object_terms( $post->ID, $product, 'cjsupport_products', false );
    wp_set_object_terms( $post->ID, $department, 'cjsupport_departments', false );


    $default_visibility = cjsupport_get_option('ticket_visibility');
    update_post_meta($post->ID, '_post_visibility', $default_visibility);


    $assgined_to = cjsupport_assign_ticket($post->ID, $department, $product);
    update_post_meta($post->ID, '_awaiting_response_from', $sender_info['ID']);
    update_post_meta($post->ID, 'ticket_status', 'publish');
    update_post_meta($post->ID, '_starred_by', 'none');

    $ticket_id = cjsupport_unique_string().$post->ID;
    update_post_meta($post->ID, '_uid', $ticket_id);

    if(!is_null($attachments)){
        update_post_meta($post->ID, '_attachments', $attachments);
    }


    // Send email to client
    $attachment_link = null;
    $attachment_url = get_post_meta($post->ID, '_attachments', true);
    if($attachment_url){
        if( $attachment_url && is_serialized($attachment_url) ){
            if(is_array($attachment_url)){
                $attachment_link = '<p class="attachments-panel"><b>'.__('Attachments:', 'cjsupport').'</b><br>';
                foreach (unserialize($attachment_url) as $key => $link) {
                    $attachment_link .= '<a href="'.cjsupport_item_path('item_url').'/download.php?download='.urlencode($link).'">'.basename($link).'</a><br>';
                }
                $attachment_link .= '</p>';
            }
        }else{
            $attachment_link = '<p class="attachments-panel"><b>'.__('Attachments:', 'cjsupport').'</b><br><a href="'.cjsupport_item_path('item_url').'/download.php?download='.urlencode($attachment_url).'">'.basename($attachment_url).'</a></p>';
        }
    }


    $ticket_info = cjsupport_ticket_info($post->ID);

    // Send email to client
    $email_data = array(
        'to' => $ticket_info['client_info']['user_email'],
        'from_name' => cjsupport_noreply('name', $ticket_info),
        'from_email' => cjsupport_noreply('email', $ticket_info),
        'subject' => cjsupport_parse_email_subject('new_ticket_subject_to_client', $ticket_info['ID']),
        'message' => cjsupport_parse_email_message('new_ticket_to_client', $ticket_info['ID']).$attachment_link,
    );
    cjsupport_email($email_data);

    // Send email to agent
    $email_data = array(
        'to' => $ticket_info['agent_info']['user_email'],
        'from_name' => cjsupport_noreply('name', $ticket_info, cjsupport_override_noreply_agents()),
        'from_email' => cjsupport_noreply('email', $ticket_info, cjsupport_override_noreply_agents()),
        'subject' => cjsupport_parse_email_subject('new_ticket_subject_to_agent', $ticket_info['ID']),
        'message' => cjsupport_parse_email_message('new_ticket_to_agent', $ticket_info['ID']).$attachment_link,
    );
    cjsupport_email($email_data);

    return $post->ID;

}



function cjsupport_imap_check_existing_ticket($mailId){
    global $wpdb;
    $args = array(
        'posts_per_page' => -1,
        'post_type' => 'cjsupport',
        'meta_query' => array(
            array(
                'key' => '_imap_id',
                'value' => $mailId,
                'compare' => '=',
            ),
        ),
    );
    $existing_ticket = get_posts($args);
    if(!empty($existing_ticket)){
        return true;
    }else{
        return false;
    }

}



function cjsupport_imap_user($mail){
    global $wpdb;
    $user_info = cjsupport_user_info($mail->fromAddress);

    if(is_null($user_info)){

        $user_login = cjsupport_create_unique_username($mail->fromAddress);
        $user_pass = wp_generate_password( 10, false, false );
        $user_email = $mail->fromAddress;
        $new_user_id = wp_create_user( $user_login, $user_pass, $user_email );
        $user_info = cjsupport_user_info($new_user_id);

        $new_user_subject = cjsupport_get_option('new_user_subject_to_client');
        $new_user_subject = str_replace('%%site_name%%', get_bloginfo('name'), $new_user_subject);
        $support_page = get_post(cjsupport_get_option('page_cjsupport_app'));
        $login_link = (cjsupport_get_option('login_url') == '') ? wp_login_url(get_permalink($support_page->ID)) : cjsupport_get_option('login_url');
        $new_user_message = cjsupport_get_option('new_user_message_to_client');
        $new_user_message = str_replace('%%site_name%%', get_bloginfo('name'), $new_user_message);
        $new_user_message = str_replace('%%user_login%%', $user_info['user_login'], $new_user_message);
        $new_user_message = str_replace('%%user_pass%%', $user_pass, $new_user_message);
        $new_user_message = str_replace('%%login_url%%', $login_link, $new_user_message);

        $new_user_email_data = array(
            'to' => $user_info['user_email'],
            'from_name' => cjsupport_noreply('name', null, 'force-no-reply'),
            'from_email' => cjsupport_noreply('email', null, 'force-no-reply'),
            'subject' => $new_user_subject,
            'message' => $new_user_message,
        );
        cjsupport_email($new_user_email_data);

        if(cjsupport_get_option('new_user_admin_notification') == 'yes'){
            $new_user_admin_message = __('<p>Dear Admin,</p>', 'cjsupport');
            $new_user_admin_message .= __('<p>A new user has created a support ticket via email.</p>', 'cjsupport');
            $new_user_admin_message .= sprintf(__('<p><a href="%s">Click here</a> to view tickets.</p>', 'cjsupport'), cjsupport_generate_url('page_cjsupport_app').'#/tickets/user/'.$user_info['ID']);
            $new_user_admin_message .= sprintf(__('<p><a href="%s">Disable</a> these notifications.</p>', 'cjsupport'), admin_url('admin.php?page=cjsupport&callback=cjsupport_app_settings'));

            $new_user_admin_email_data = array(
                'to' => get_option('admin_email'),
                'from_name' => get_bloginfo('name'),
                'from_email' => get_option('admin_email'),
                'subject' => sprintf(__('New User Registration: %s', 'cjsupport'), $user_info['user_login']),
                'message' => $new_user_admin_message,
            );
            cjsupport_email($new_user_admin_email_data);
        }

    }

    $user_info['user_type'] = cjsupport_user_type($user_info['ID']);
    return $user_info;
}



function cjsupport_get_email_attachments($attachments){
    global $wpdb;
    $wp_upload_dir = wp_upload_dir();
    $targetPath = $wp_upload_dir['path'] . '/';

    $attached_files = null;

    if(isset($attachments)){

        foreach ($attachments as $key => $file) {
            $newFileName = wp_unique_filename( $targetPath, $file->name );
            $targetFile = str_replace('//', '/', $targetPath) . $newFileName;
            rename($file->filePath, $targetFile);

            $filename = $targetFile;
            $wp_filetype = wp_check_filetype(basename($filename), null );
            $attachment = array(
                'guid' => $wp_upload_dir['baseurl'] . '/' . _wp_relative_upload_path( $filename ),
                'post_mime_type' => $wp_filetype['type'],
                'post_title' => preg_replace('/\.[^.]+$/', '', basename($filename)),
                'post_content' => '',
                'post_status' => 'inherit'
            );
            $attach_id = wp_insert_attachment( $attachment, $filename);
            require_once(ABSPATH . 'wp-admin/includes/image.php');
            $attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
            wp_update_attachment_metadata( $attach_id, $attach_data );
            $guid = $wpdb->get_row("SELECT * FROM $wpdb->posts WHERE ID = '{$attach_id}'");

            $attached_files[] =  $guid->guid;

        }

        if(!is_null($attached_files)  && count($attached_files) == 1){
            return $attached_files[0];
        }else{
            return serialize($attached_files);
        }

    }else{
        return null;
    }

}