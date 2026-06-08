<?php
$allow_create = !isset($config['allow_create']) || $config['allow_create'] !== FALSE;
$allow_edit = !isset($config['allow_edit']) || $config['allow_edit'] !== FALSE;
$allow_delete = !isset($config['allow_delete']) || $config['allow_delete'] !== FALSE;
$columns = isset($config['columns']) ? $config['columns'] : array();
?>

<div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
	<div>
		<h1 class="admin-page-title mb-1"><?php echo html_escape($config['title']); ?></h1>
		<p class="text-muted mb-0">Manage the records displayed on the public website and inside the church administration panel.</p>
	</div>
	<?php if ($allow_create && empty($config['single'])): ?>
		<a class="btn btn-admin" href="<?php echo site_url('admin/content/'.$module.'/create'); ?>"><i class="bi bi-plus-lg me-2"></i>Create New</a>
	<?php endif; ?>
</div>

<div class="admin-card">
	<div class="table-responsive">
		<table class="table align-middle mb-0">
			<thead>
				<tr>
					<?php foreach ($columns as $column): ?>
						<th><?php echo html_escape($column['label']); ?></th>
					<?php endforeach; ?>
					<th class="text-end">Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php if (empty($items)): ?>
					<tr>
						<td colspan="<?php echo count($columns) + 1; ?>" class="text-center py-5 text-muted">No records found in this module yet.</td>
					</tr>
				<?php endif; ?>
				<?php foreach ($items as $item): ?>
					<tr>
						<?php foreach ($columns as $column): ?>
							<td>
								<?php $value = isset($item->{$column['field']}) ? $item->{$column['field']} : ''; ?>
								<?php if (isset($column['type']) && $column['type'] === 'image'): ?>
									<img class="table-avatar" src="<?php echo media_url($value); ?>" alt="<?php echo html_escape($config['title']); ?> image">
								<?php elseif (isset($column['type']) && $column['type'] === 'boolean'): ?>
									<span class="badge rounded-pill text-bg-<?php echo !empty($value) ? 'success' : 'secondary'; ?>"><?php echo !empty($value) ? 'Yes' : 'No'; ?></span>
								<?php elseif (isset($column['type']) && $column['type'] === 'date'): ?>
									<?php echo html_escape(formatted_date($value, 'M d, Y')); ?>
								<?php elseif (isset($column['type']) && $column['type'] === 'datetime'): ?>
									<?php echo html_escape(formatted_date($value, 'M d, Y h:i A')); ?>
								<?php elseif (isset($column['type']) && $column['type'] === 'status'): ?>
									<span class="badge rounded-pill text-bg-<?php echo status_badge_class($value); ?>"><?php echo html_escape(module_label($value)); ?></span>
								<?php else: ?>
									<?php echo html_escape(excerpt($value, 80)); ?>
								<?php endif; ?>
							</td>
						<?php endforeach; ?>
						<td class="text-end">
							<div class="d-inline-flex gap-2">
								<?php if ($allow_edit && !empty($item->id)): ?>
									<a class="btn btn-sm btn-outline-secondary" href="<?php echo site_url('admin/content/'.$module.'/edit/'.$item->id); ?>">Edit</a>
								<?php endif; ?>
								<?php if ($allow_delete && !empty($item->id)): ?>
									<a class="btn btn-sm btn-outline-danger" href="<?php echo site_url('admin/content/'.$module.'/delete/'.$item->id); ?>" onclick="return confirm('Delete this record?');">Delete</a>
								<?php endif; ?>
							</div>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>