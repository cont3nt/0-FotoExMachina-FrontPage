<?php
$email_routes = cjsupport_get_option('cjsupport_email_routes');
$route_to_delete = urldecode($_GET['id']);
unset($email_routes[$route_to_delete]);
cjsupport_update_option('cjsupport_email_routes', $email_routes);
$location = cjsupport_callback_url('cjsupport_email_routes');
wp_redirect($location);
exit;