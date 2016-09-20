<?php
global $current_user, $wpdb;

$args = array(
	'include' => cjsupport_get_option('support_staff'),
);

$staff_members = get_users($args);

$departments = get_terms('cjsupport_departments', array('hide_empty' => 0, 'order_by' => 'name', 'order' => 'asc') );
$products = get_terms('cjsupport_products', array('hide_empty' => 0, 'order_by' => 'name', 'order' => 'asc') );


$department_options['all'] = __('All departments', 'cjsupport');
if(!empty($departments)){
	foreach ($departments as $key => $department) {
		$department_options[$department->slug] = $department->name;
	}
}

$product_options['all'] = __('All products', 'cjsupport');
if(!empty($products)){
	foreach ($products as $key => $product) {
		$product_options[$product->slug] = $product->name;
	}
}

if(isset($_POST['update_staff'])){

	$user_id = $_POST['user_id'];

	if(isset($_POST['user_departments'])){
		if(in_array('all', $_POST['user_departments'])){
			update_user_meta($user_id, 'cjsupport_departments', array('all'));
		}else{
			update_user_meta($user_id, 'cjsupport_departments', $_POST['user_departments']);
		}
	}

	if(isset($_POST['user_products'])){
		if(in_array('all', $_POST['user_products'])){
			update_user_meta($user_id, 'cjsupport_products', array('all'));
		}else{
			update_user_meta($user_id, 'cjsupport_products', $_POST['user_products']);
		}
	}

	foreach ($staff_members as $key => $user) {
		$uds = get_user_meta($user->ID, 'cjsupport_departments', true);
		$ups =get_user_meta($user->ID, 'cjsupport_products', true);
		if(empty($uds)){
			update_user_meta($user->ID, 'cjsupport_departments', array('all'));
		}
		if(empty($ups)){
			update_user_meta($user->ID, 'cjsupport_products', array('all'));
		}
	}

}

?>

<table class="enable-search" cellspacing="0" cellpadding="0" width="100%">
	<thead>
		<tr>
			<th colspan="5">
				<h2 class="main-heading"><?php _e('Staff Members', 'cjsupport'); ?>
					<a href="<?php echo cjsupport_callback_url('cjsupport_choose_staff'); ?>" class="btn btn-small btn-inverse margin-10-left"><?php _e('&leftarrow; Go back', 'cjsupport'); ?></a>
					<span class="settings-search-box">
						<input id="settings-search-box" name="settings-search-box" type="text" placeholder="<?php _e('Quick Search', 'cjsupport') ?>">
						<i class="cj-icon icon-search"></i>
					</span>
				</h2>
			</th>
		</tr>
		<tr>
			<th width="15%"><b><?php _e('Staff Member', 'cjsupport'); ?></b></th>
			<th width="25%"><b><?php _e('Departments', 'cjsupport'); ?></b></th>
			<th width="25%"><b><?php _e('Products', 'cjsupport'); ?></b></th>
			<th width="15%" class="textcenter"><b><?php _e('Actions', 'cjsupport'); ?></b></th>
		</tr>
	</thead>
	<tbody>
		<?php if(!empty($staff_members)): ?>
		<?php
			foreach ($staff_members as $key => $user):
			$user_last_login = get_user_meta($user->ID, 'cjsupport_last_login', true);
			$last_login = ($user_last_login == '') ? __('Never', 'cjsupport') : date('M d, Y h:i A', $user_last_login);
			$user_departments = get_user_meta($user->ID, 'cjsupport_departments', true);
			$user_products = get_user_meta($user->ID, 'cjsupport_products', true);
			if($user->ID != cjsupport_get_option('fallback_support_staff')):
		?>
		<tr class="searchable">
			<form action="<?php echo cjsupport_callback_url('cjsupport_manage_staff'); ?>" method="post">
			<td>
				<p class="margin-0 margin-5-bottom"><b><?php echo $user->display_name; ?></b> (<?php echo $user->user_login; ?>)</p>
				<i><?php echo sprintf('Last login: %s', $last_login) ?></i>
			</td>
			<td>
				<input name="user_id" type="hidden" value="<?php echo $user->ID; ?>">
				<select name="user_departments[]" class="chzn-select-no-results" multiple style="width:80%;">
					<?php
					foreach ($department_options as $key => $value) {
						if(in_array($key, $user_departments)){
							echo '<option selected value="'.$key.'">'.$value.'</option>';
						}else{
							echo '<option value="'.$key.'">'.$value.'</option>';
						}

					}
					?>
				</select>
			</td>
			<td>
				<select name="user_products[]" class="chzn-select-no-results" multiple style="width:80%;">
					<?php
					foreach ($product_options as $key => $value) {
						if(in_array($key, $user_products)){
							echo '<option selected value="'.$key.'">'.$value.'</option>';
						}else{
							echo '<option value="'.$key.'">'.$value.'</option>';
						}

					}
					?>
				</select>
			</td>
			<td  class="textcenter">
				<button name="update_staff" type="submit" class="btn btn-info"><?php _e('Update', 'cjsupport') ?></button>
			</td>
			</form>
		</tr>
		<?php else: ?>
			<tr>
				<td>
					<p class="margin-0 margin-5-bottom"><b><?php echo $user->display_name; ?></b> (<?php echo $user->user_login; ?>)</p>
					<i><?php echo sprintf('Last login: %s', $last_login) ?></i>
					<p class="red italic"><?php _e('Fallback Support Staff', 'cjsupport'); ?></p>
				</td>
				<td>
					<?php _e('All Departments', 'cjsupport'); ?>
				</td>
				<td>
					<?php _e('All Products', 'cjsupport'); ?>
				</td>
				<td>
					<?php _e('--', 'cjsupport'); ?>
				</td>
			</tr>
		<?php endif; ?>
		<?php endforeach; ?>

		<?php else: ?>

		<tr>
			<td colspan="5" class="red">
				<?php _e('No staff members found.', 'cjsupport'); ?>
			</td>
		</tr>

		<?php endif; ?>
	</tbody>
</table>















