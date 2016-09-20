<?php
/**
 * @package CSSJockey WordPress Framework
 * @author Mohit Aneja (cssjockey.com)
 * @version FW-20150208
*/
global $cjsupport_item_vars, $cjsupport_file_opts, $wpdb;

$cjsupport_options_table = cjsupport_item_info('options_table');
foreach ($cjsupport_item_vars['db_tables'] as $key => $sql) {
	$query = $wpdb->query($sql);
	if(!$query){
		wp_die( $wpdb->print_error(), __('Database Error!', 'cjsupport') );
	}
	if(is_wp_error( $query )){
		wp_die($query->get_error_message(), __('Database Error!', 'cjsupport'));
	}
}

$cjsupport_item_options = cjsupport_item_options();
foreach ($cjsupport_item_options as $key => $value) {
	foreach ($value as $okey => $ovalue) {
		$check = $wpdb->get_row("SELECT * FROM $cjsupport_options_table WHERE option_name = '{$ovalue['id']}'");
		if(empty($check)){

			if(is_array($ovalue['default'])){
				$save_value = serialize($ovalue['default']);
			}else{
				$save_value = $ovalue['default'];
			}

			$wpdb->query("INSERT INTO $cjsupport_options_table (option_name, option_value) VALUES ('{$ovalue['id']}', '{$save_value}')");
		}
		$cjsupport_file_opts[] = $ovalue['id'];
	}
}

$cjsupport_options_sync = $wpdb->get_results("SELECT * FROM $cjsupport_options_table ORDER BY option_id");

foreach ($cjsupport_options_sync as $key => $result) {
	$cjsupport_table_opts[] = $result->option_name;
}

$cjsupport_opts_diff = array_diff($cjsupport_table_opts, $cjsupport_file_opts);

if(!empty($cjsupport_opts_diff)){
	foreach ($cjsupport_opts_diff as $key => $diff_opt) {
		$wpdb->query("DELETE FROM $cjsupport_options_table WHERE option_name = '{$diff_opt}'");
	}
}


function cjsupport_duplicate_options(){
	global $cjsupport_file_opts;
	$duplicates = implode('<br />', array_unique( array_diff_assoc( $cjsupport_file_opts, array_unique( $cjsupport_file_opts ) ) ));

	if(!empty($duplicates)){
		$display[] = '<div class="error">';
		$display[] = sprintf(__('<p><strong>ERROR</strong>: Duplicate options found!  <br /><b>%s <br />(%s)</b></p>', 'cjsupport'), cjsupport_item_info('item_name'), cjsupport_item_path('item_dir'));
		$display[] = '<p>'.$duplicates.'</p>';
		$display[] = '</div>';

		echo implode('', $display);
	}
}

add_action('admin_notices', 'cjsupport_duplicate_options');

do_action('cjsupport_db_setup_hook');

