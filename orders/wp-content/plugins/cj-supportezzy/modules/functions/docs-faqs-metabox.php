<?php
function cjsupport_slides_metabox( $meta_boxes ) {
    $prefix = '_cmb_'; // Prefix for all fields

    /*
    $show_on = '';
    if(isset($_GET['post']) && @$_GET['action'] == 'edit'){
        $page_template = get_post_meta($_GET['post'], '_wp_page_template', true);
        if(is_admin() && $page_template == 'page-templates/page-nav-menu.php'){
            $show_on = array('page');
        }
    }
    */

    $meta_boxes[] = array(
        'id' => 'test_metabox',
        'title' => __('Slide Options', 'cjsupport'),
        'pages' => array('cjsupport_docs'), // post type
        'context' => 'advanced',
        'priority' => 'high',
        'show_names' => false, // Show field names on the left
        'fields' => array(
            array(
                'name' => __('Content', 'cjsupport'),
                'desc' => __('Feel free to use any shortcodes', 'cjsupport'),
                'id' => $prefix . 'content',
                'type' => 'wysiwyg'
            ),
        ),
    );

    return $meta_boxes;
}
// add_filter( 'cmb_meta_boxes', 'cjsupport_slides_metabox' );