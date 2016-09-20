<?php $email_routes = cjsupport_get_option('cjsupport_email_routes'); ?>
<table width="100%" cellspacing="0" cellpadding="0">
	<thead>
		<tr>
			<th colspan="5">
				<h2 class="main-heading">
					<?php _e('Email Routes', 'cjsupport') ?>
					<a href="<?php echo cjsupport_callback_url('cjsupport_email_routes').'&do=add-route' ?>" class="btn btn-success btn-small margin-5-left"><?php _e('Add new route', 'cjsupport') ?></a>
				</h2>
			</th>
		</tr>
		<tr>
			<td width="20%"><strong><?php _e('Email Address', 'cjsupport') ?></strong></td>
			<td width="20%"><strong><?php _e('IMAP Server', 'cjsupport') ?></strong></td>
			<td width="25%"><strong><?php _e('Departments', 'cjsupport') ?></strong></td>
			<td width="25%"><strong><?php _e('Products', 'cjsupport') ?></strong></td>
			<td width="10%"><strong><?php _e('Actions', 'cjsupport') ?></strong></td>
		</tr>
	</thead>
	<tbody>
		<?php if(empty($email_routes) || $email_routes == ''): ?>
		<tr>
			<td colspan="5" class="red"><?php _e('No routes found, please create a new route.', 'cjsupport') ?></td>
		</tr>
		<?php else: ?>

		<?php
			foreach ($email_routes as $key => $route):
				//$actions = '<a href="'.cjsupport_string(cjsupport_callback_url('cjsupport_email_routes')).'do=edit-route&id='.urlencode($route['imap_email']).'">'.__('Edit', 'cjsupport').'</a>&nbsp;|&nbsp;';
				$actions = '<a href="'.cjsupport_string(cjsupport_callback_url('cjsupport_email_routes')).'do=delete-route&id='.urlencode($route['imap_email']).'" class="red cj-confirm" data-confirm="'.__("Are you sure?\nThis cannot be undone.", 'cjsupport').'">'.__('Delete', 'cjsupport').'</a>';

				if($route['departments'] == 'all'){
					$department_name = __('All Departments', 'cjsupport');
				}else{
					$department = get_term_by('slug', $route['departments'], 'cjsupport_departments');
					$department_name = $department->name;
				}
				$product_names = '';
				foreach ($route['products'] as $key => $product) {
					if($product == 'all'){
						$product_names['all'] = __('All Products', 'cjsupport');
					}else{
						$product = get_term_by('slug', $product, 'cjsupport_products' );
						$product_names[$product->slug] = $product->name;
					}
				}
		?>
		<tr>
			<td><?php echo $route['imap_email']; ?></td>
			<td><?php echo $route['imap_server'].':'.$route['imap_port']; ?></td>
			<td><?php echo $department_name; ?></td>
			<td><?php echo implode('<br>', $product_names); ?></td>
			<td><?php echo $actions; ?></td>
		</tr>
		<?php endforeach; ?>

		<?php endif; ?>
	</tbody>
</table>