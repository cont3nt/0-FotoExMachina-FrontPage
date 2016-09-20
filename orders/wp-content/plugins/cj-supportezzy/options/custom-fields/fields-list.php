<?php
	if(isset($_POST['bulk_update'])){
		foreach ($_POST as $bk => $bv) {
			if(is_array($bv)){
				foreach ($bv as $key => $value) {
					if(is_array($value)){
						$required = (isset($value['field_required'])) ? 1 : 0;
						$wpdb->query("UPDATE $table_form_fields SET field_required = '{$required}', field_order = '{$value['field_order']}' WHERE id = '{$key}'");
					}
				}
			}
		}
		wp_redirect(cjsupport_callback_url($_GET['callback']));
		exit;
	}
?>
<table class="cj-table" width="100%" cellspacing="0" cellpadding="0">
	<thead>
		<tr>
			<th colspan="7">
				<span class="pull-right">
					<a href="<?php echo $action_url.'add-new' ?>" class="btn btn-success btn-sm"><?php _e('Add New Field', 'cjsupport') ?></a>
				</span>
				<h2 class="main-heading"><?php _e('Create Ticket Form (Additional Fields)', 'cjsupport') ?></h2>
			</th>
		</tr>
		<tr>
			<td><?php _e('Field Type', 'cjsupport') ?></td>
			<td><?php _e('Unique Name', 'cjsupport') ?></td>
			<td><?php _e('Label', 'cjsupport') ?></td>
			<td width="25%"><?php _e('Description', 'cjsupport') ?></td>
			<td width="15%"><?php _e('Options', 'cjsupport') ?></td>
			<td width="10%" class="textcenter"><?php _e('Required', 'cjsupport') ?></td>
			<td width="10%" class="textcenter"><?php _e('Order', 'cjsupport') ?></td>
		</tr>
	</thead>
	<tbody>
		<?php if(empty($form_fields)): ?>
			<tr>
				<td colspan="8" class="red italic"><?php _e('No additional fields found.', 'cjsupport') ?></td>
			</tr>
		<?php else: ?>
			<form action="" method="post">
			<?php foreach ($form_fields as $key => $field): ?>
			<tr>
				<td>
				<?php echo $field->field_type; ?>
				<p>
					<a href="<?php echo $action_url.'edit&id='.$field->id; ?>"><?php _e('Update', 'cjsupport') ?></a> |
					<a class="red cj-confirm" data-confirm="<?php _e("Are you sure?\nThis cannot be undone.", 'cjsupport'); ?>" href="<?php echo $action_url.'delete&id='.$field->id; ?>"><?php _e('Delete', 'cjsupport') ?></a>
				</p>
				</td>
				<td><?php echo $field->field_id; ?></td>
				<td><?php echo stripcslashes($field->field_label); ?></td>
				<td><?php echo $field->field_info; ?></td>
				<td><?php echo ($field->field_options == '') ? __('--', 'cjsupport') : nl2br($field->field_options); ?></td>
				<td class="textcenter"><?php echo ($field->field_required == 1) ? '<input name="bulkupdate['.$field->id.'][field_required]" checked type="checkbox" value="1" />' : '<input name="bulkupdate['.$field->id.'][field_required]" type="checkbox" value="1" />'; ?></td>
				<td class="textcenter"><?php echo '<input name="bulkupdate['.$field->id.'][field_order]" type="text" value="'.$field->field_order.'" style="width:45px; text-align:center;" />'; ?></td>
			</tr>
			<?php endforeach; ?>
			<td colspan="7" class="textright">
				<button type="submit" name="bulk_update" class="button-primary"><?php _e('Update fields', 'cjsupport') ?></button>
			</td>
			</form>
		<?php endif; ?>
	</tbody>
</table>