<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Change Password | GICP Talaga CMS</title>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Source+Sans+3:wght@400;600;700&display=swap" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	<link rel="stylesheet" href="<?php echo asset_url('css/admin.css'); ?>">
</head>
<body>
<div class="auth-shell">
	<div class="auth-card">
		<h1 class="auth-title mb-3">Update Password</h1>
		<?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>
		<form method="post">
			<?php echo csrf_input(); ?>
			<div class="mb-3">
				<label class="form-label" for="password">New Password</label>
				<input id="password" class="form-control" type="password" name="password" required>
			</div>
			<div class="mb-3">
				<label class="form-label" for="password_confirm">Confirm Password</label>
				<input id="password_confirm" class="form-control" type="password" name="password_confirm" required>
			</div>
			<button class="btn btn-admin w-100" type="submit">Update Password</button>
		</form>
	</div>
</div>
</body>
</html>