<?php if ($this->session->flashdata('site_success')): ?>
	<div class="alert alert-success mb-4" role="alert"><?php echo $this->session->flashdata('site_success'); ?></div>
<?php endif; ?>
<?php if ($this->session->flashdata('site_error')): ?>
	<div class="alert alert-danger mb-4" role="alert"><?php echo $this->session->flashdata('site_error'); ?></div>
<?php endif; ?>