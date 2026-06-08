<?php
$is_edit = $mode === 'edit';
$action_label = $is_edit ? 'Update' : 'Create';
?>

<div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
	<div>
		<h1 class="admin-page-title mb-1"><?php echo html_escape($page_title); ?></h1>
		<p class="text-muted mb-0">Maintain high-quality, church-centered content that is clear, pastoral, and welcoming.</p>
	</div>
	<div class="d-flex gap-2">
		<a class="btn btn-outline-secondary" href="<?php echo site_url('admin/content/'.$module); ?>">Back to List</a>
		<?php if ($module !== 'church_profile'): ?><a class="btn btn-outline-danger" href="<?php echo site_url('admin/logout'); ?>">Logout</a><?php endif; ?>
	</div>
</div>

<?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>

<form method="post" enctype="multipart/form-data">
	<?php echo csrf_input(); ?>
	<div class="admin-card">
		<div class="row g-4">
			<?php foreach ($fields as $field): ?>
				<?php
				$field_name = $field['name'];
				$current_value = set_value($field_name, form_value($item, $field_name));
				$field_type = $field['type'];
				if ($field_type === 'checkbox_group')
				{
					$current_value = isset($_POST[$field_name]) && is_array($_POST[$field_name]) ? $_POST[$field_name] : normalize_role_permissions($current_value, TRUE);
					if ($current_value === NULL)
					{
						$current_value = array();
					}
				}
				?>
				<div class="col-lg-<?php echo in_array($field_type, array('textarea', 'checkbox_group'), TRUE) ? '12' : '6'; ?>">
					<label class="form-label" for="<?php echo html_escape($field_name); ?>"><?php echo html_escape($field['label']); ?></label>
					<?php if ($field_type === 'textarea'): ?>
						<textarea class="form-control" id="<?php echo html_escape($field_name); ?>" name="<?php echo html_escape($field_name); ?>" rows="6"><?php echo html_escape($current_value); ?></textarea>
					<?php elseif ($field_type === 'select'): ?>
						<select class="form-select" id="<?php echo html_escape($field_name); ?>" name="<?php echo html_escape($field_name); ?>">
							<?php foreach ($field['options'] as $option_key => $option_label): ?>
								<option value="<?php echo html_escape($option_key); ?>" <?php echo (string) $current_value === (string) $option_key ? 'selected' : ''; ?>><?php echo html_escape($option_label); ?></option>
							<?php endforeach; ?>
						</select>
					<?php elseif ($field_type === 'checkbox'): ?>
						<div class="form-check form-switch mt-2">
							<input class="form-check-input" type="checkbox" id="<?php echo html_escape($field_name); ?>" name="<?php echo html_escape($field_name); ?>" value="1" <?php echo !empty($current_value) ? 'checked' : ''; ?>>
							<label class="form-check-label" for="<?php echo html_escape($field_name); ?>">Enable this option</label>
						</div>
					<?php elseif ($field_type === 'checkbox_group'): ?>
						<div class="row g-2 mt-1">
							<?php foreach ($field['options'] as $option_key => $option_label): ?>
								<div class="col-sm-6">
									<label class="form-check border rounded-4 px-3 py-2 h-100 d-flex align-items-start gap-2">
										<input class="form-check-input mt-1" type="checkbox" name="<?php echo html_escape($field_name); ?>[]" value="<?php echo html_escape($option_key); ?>" <?php echo in_array((string) $option_key, $current_value, TRUE) ? 'checked' : ''; ?>>
										<span>
											<span class="fw-semibold d-block"><?php echo html_escape($option_label); ?></span>
											<span class="small text-muted"><?php echo html_escape(module_label($option_key)); ?></span>
										</span>
									</label>
								</div>
							<?php endforeach; ?>
						</div>
					<?php elseif ($field_type === 'file'): ?>
						<input class="form-control" id="<?php echo html_escape($field_name); ?>" type="file" name="<?php echo html_escape($field_name); ?><?php echo $field_name === 'gallery_images' ? '[]' : ''; ?>" <?php echo $field_name === 'gallery_images' ? 'multiple' : ''; ?>>
						<?php if (!empty($current_value) && $field_name !== 'audio_upload' && $field_name !== 'pdf_upload' && $field_name !== 'gallery_images'): ?>
							<div class="mt-3">
								<img class="table-avatar" src="<?php echo media_url($current_value); ?>" alt="Current file preview">
							</div>
						<?php endif; ?>
					<?php else: ?>
						<input class="form-control" id="<?php echo html_escape($field_name); ?>" type="<?php echo $field_type === 'datetime' ? 'datetime-local' : html_escape($field_type); ?>" name="<?php echo html_escape($field_name); ?>" value="<?php echo html_escape($field_type === 'datetime' && !empty($current_value) ? date('Y-m-d\TH:i', strtotime($current_value)) : $current_value); ?>">
					<?php endif; ?>
					<?php if (!empty($field['help'])): ?>
						<div class="form-text"><?php echo html_escape($field['help']); ?></div>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>

		<?php if (!empty($sermon_files)): ?>
			<hr class="my-4">
			<h2 class="h4 mb-3">Attached Sermon Files</h2>
			<div class="row g-3">
				<?php foreach ($sermon_files as $file): ?>
					<div class="col-md-6">
						<div class="admin-card mb-0 p-3">
							<div class="fw-bold mb-1"><?php echo html_escape(strtoupper($file->file_type)); ?></div>
							<div class="small text-muted mb-2"><?php echo html_escape($file->original_name); ?></div>
							<?php if (file_exists(FCPATH.$file->file_path)): ?>
								<a class="btn btn-sm btn-outline-secondary" target="_blank" href="<?php echo base_url($file->file_path); ?>">Open File</a>
							<?php else: ?>
								<span class="badge rounded-pill text-bg-secondary">File placeholder only</span>
							<?php endif; ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<?php if (!empty($gallery_images)): ?>
			<hr class="my-4">
			<h2 class="h4 mb-3">Current Gallery Images</h2>
			<div class="image-preview-grid">
				<?php foreach ($gallery_images as $gallery_image): ?>
					<img src="<?php echo media_url($gallery_image->image_path); ?>" alt="<?php echo html_escape($gallery_image->caption); ?>">
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<div class="d-flex flex-wrap gap-2 mt-4">
			<button class="btn btn-admin" type="submit"><?php echo $action_label; ?> Record</button>
			<a class="btn btn-outline-secondary" href="<?php echo site_url('admin/content/'.$module); ?>">Cancel</a>
		</div>
	</div>
</form>