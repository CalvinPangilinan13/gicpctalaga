<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Login | GICP Talaga CMS</title>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Source+Sans+3:wght@400;600;700&display=swap" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
	<link rel="stylesheet" href="<?php echo asset_url('css/admin.css'); ?>">
</head>
<body>
<div class="auth-shell">
	<div class="auth-card">
		<div class="auth-brand">
			<img src="<?php echo asset_url('images/logo.svg'); ?>" alt="GICP Talaga logo">
			<div>
				<div class="auth-title">Grace in Christ</div>
				<div class="text-muted">Church Website CMS</div>
			</div>
		</div>
		<h1 class="h4 mb-2">Administrator Login</h1>
		<p class="text-muted mb-4">Sign in to manage church profile, ministries, sermons, events, announcements, and media.</p>

		<?php if ($this->session->flashdata('auth_success')): ?><div class="alert alert-success"><?php echo $this->session->flashdata('auth_success'); ?></div><?php endif; ?>
		<?php if ($this->session->flashdata('auth_error')): ?><div class="alert alert-danger"><?php echo $this->session->flashdata('auth_error'); ?></div><?php endif; ?>
		<?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>

		<form method="post">
			<?php echo csrf_input(); ?>
			<div class="mb-3">
				<label class="form-label" for="email">Email Address</label>
				<input id="email" class="form-control" type="email" name="email" value="<?php echo set_value('email'); ?>" required>
			</div>
			<div class="mb-3">
				<label class="form-label" for="password">Password</label>
				<input id="password" class="form-control" type="password" name="password" required>
			</div>
			<button class="btn btn-admin w-100 mb-3" type="submit">Sign In</button>
		</form>

		<div class="d-flex justify-content-between small">
			<a href="<?php echo site_url('admin/forgot-password'); ?>">Forgot password?</a>
			<a href="<?php echo base_url(); ?>">Return to website</a>
		</div>
	</div>
</div>
</body>
</html>