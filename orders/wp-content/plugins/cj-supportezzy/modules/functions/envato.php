<?php

function cjsupport_sync_envato_products(){
	$api_key = cjsupport_get_option('envato_apikey');
	$api_username = cjsupport_get_option('envato_username');
	$envato_sites = array(
		'themeforest' => 'themeforest',
		'codecanyon' => 'codecanyon',
		'videohive' => 'videohive',
		'audiojungle' => 'audiojungle',
		'graphicriver' => 'graphicriver',
		'photodune' => 'photodune',
		'3docean' => '3docean',
		'activeden' => 'activeden',
	);

	if($api_key == '' || $api_username == ''){
		return false;
	}else{

		$now = time();
		$next_sync = get_option('cjsupport_sync_envato');
		add_option('cjsupport_sync_envato', $now);
		if($now > $next_sync){
			foreach ($envato_sites as $key => $envato_site) {
				$url = "http://marketplace.envato.com/api/v3/new-files-from-user:{$api_username},{$envato_site}.json";
				$products = wp_remote_get($url);
				$data = json_decode($products['body']);
				foreach ($data as $key => $value) {
					$user_products[] = $value;
				}
			}
			if(is_array($user_products)){
				foreach ($user_products as $key => $envato_terms) {
					if(is_array($envato_terms)){
						foreach ($envato_terms as $key => $value) {
							$term_name = 'envato-'.$value->id;
							$term_title = $value->item;

							if(is_null(term_exists( $term_name, 'cjsupport_products'))){
								$term_data = wp_insert_term(
								  	$term_title, // the term
								  	'cjsupport_products', // the taxonomy
								  	array(
								    	'description'=> '',
								    	'slug' => $term_name,
								    	'parent'=> ''
								  	)
								);
							}
						}
					}
				}

			}
			update_option('cjsupport_sync_envato', strtotime('1 week'));
		}
	}
}

function cjsupport_manual_envato_import(){
	update_option('cjsupport_sync_envato', strtotime('-1 week'));
	cjsupport_sync_envato_products();
}

if(isset($_GET['cjsupport_action']) && $_GET['cjsupport_action'] == 'import-envato-products'){
	add_action('init', 'cjsupport_manual_envato_import');
}

if(cjsupport_get_option('import_envato_products') == 'yes'){
	add_action('init', 'cjsupport_sync_envato_products');
}

