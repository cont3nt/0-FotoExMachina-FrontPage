<?php 
wp_trash_post( $_POST['ticket_id'] );
$return['status'] = 'success';
$return['response'] = 'deleted';