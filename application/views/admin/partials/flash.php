<?php if ($this->session->flashdata('admin_success')): ?>
	<div class="alert alert-success" role="alert"><?php echo $this->session->flashdata('admin_success'); ?></div>
<?php endif; ?>
<?php if ($this->session->flashdata('admin_error')): ?>
	<div class="alert alert-danger" role="alert"><?php echo $this->session->flashdata('admin_error'); ?></div>
<?php endif; ?>