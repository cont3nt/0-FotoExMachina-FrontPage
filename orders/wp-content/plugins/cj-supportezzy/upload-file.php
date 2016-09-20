<?php
$allowed_file_types = cjsupport_get_option('allowed_file_types');
if(isset($_GET['upload_files'])){
	if(!empty($_FILES)){
		$file = cjsupport_file_upload('file', null, null, $allowed_file_types, 'guid');
		if(is_array($file)){
			$return['status'] = 'error';
			$return['response'] = implode('<br>', $file);
		}else{
			$return['status'] = 'success';
			$return['response'] = array(
				'fname' => basename($file),
				'furl' => $file,
			);
		}
	}else{
		$return['status'] = 'error';
		$return['response'] = __('No files found', 'cjsupport');
	}
	echo json_encode($return);
	die();
}
