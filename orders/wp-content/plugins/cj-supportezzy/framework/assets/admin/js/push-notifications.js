/**
 * @package CSSJockey WordPress Framework
 * @author Mohit Aneja (cssjockey.com)
 * @version FW-20150208
 */
jQuery(document).ready(function($) {

    $.post(
        ajaxurl, {
            'action': 'cjsupport_get_notifications',
            //'data': 'foobarid'
        },
        function(response) {
            console.log(response);
        }
    );

});