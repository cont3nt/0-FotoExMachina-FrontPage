<?php
/*add_action('cjsupport_departments_edit_form_fields','cjsupport_departments_edit_form_fields');
add_action('cjsupport_departments_add_form_fields','cjsupport_departments_edit_form_fields');
add_action('edited_cjsupport_departments', 'cjsupport_departments_save_form_fields', 10, 2);
add_action('created_cjsupport_departments', 'cjsupport_departments_save_form_fields', 10, 2);

function cjsupport_departments_save_form_fields($term_id) {
    $meta_name = 'department_email';
    if ( isset( $_POST[$meta_name] ) ) {
        $meta_value = $_POST[$meta_name];
        // This is an associative array with keys and values:
        // $term_metas = Array($meta_name => $meta_value, ...)
        $term_metas = get_option("taxonomy_{$term_id}_metas");
        if (!is_array($term_metas)) {
            $term_metas = Array();
        }
        // Save the meta value
        $term_metas[$meta_name] = $meta_value;
        update_option( "taxonomy_{$term_id}_metas", $term_metas );
    }
}

function cjsupport_departments_edit_form_fields ($term_obj) {
    // Read in the order from the options db
    $term_id = @$term_obj->term_id;
    $term_metas = get_option("taxonomy_{$term_id}_metas");
    if ( isset($term_metas['department_email']) ) {
        $department_email = $term_metas['department_email'];
    } else {
        $department_email = cjsupport_get_option('company_email');
    }
    if ( isset($term_metas['department_email']) ) {
        $department_email = $term_metas['department_email'];
    } else {
        $department_email = cjsupport_get_option('company_email');
    }
?>
	<tr class="form-field">
		<th>
			<label for="department_email"><?php _e('Department Email', 'cjsupport') ?></label>
		</th>
		<td>
			<input name="department_email" id="department_email" type="text" value="<?php echo $department_email; ?>" size="40" style="width:95%;" placeholder="" />
			<p><?php _e('Specify department email, company email will be used if left blank.', 'cjsupport') ?></p>
		</td>
	</tr>
	<tr><td>&nbsp;</td></tr>
<?php
}


add_filter("manage_edit-cjsupport_departments_columns", 'cjsupport_cjsupport_departments_taxonomy_columns');
function cjsupport_cjsupport_departments_taxonomy_columns($cjsupport_cjsupport_departments_taxonomy_columns) {

    $new_columns = array(
        'cb' => '<input type="checkbox" />',
        'name' => __('Name', 'cjsupport'),
        'department_email' => __('Department Email', 'cjsupport'),
      	'description' => __('Description'),
        'slug' => __('Slug'),
        'posts' => __('Posts'),
    );
    return $new_columns;
}


add_filter("manage_cjsupport_departments_custom_column", 'manage_cjsupport_cjsupport_departments_taxonomy_columns', 10, 3);
function manage_cjsupport_cjsupport_departments_taxonomy_columns($out, $column_name, $term_id) {
    $term = get_term($term_id, 'cjsupport_departments');
    $term_metas = get_option("taxonomy_{$term_id}_metas");
    switch ($column_name) {
        case 'ID':
            echo $term_id;
            break;
        case 'department_email':
            if(isset($term_metas['department_email']) && $term_metas['department_email'] != ''){
            	echo $term_metas['department_email'];
            }else{
            	echo cjsupport_get_option('company_email').' '.__(' - Default', 'cjsupport');
            }
            break;

        default:
            break;
    }
}*/