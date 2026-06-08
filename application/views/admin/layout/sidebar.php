<aside class="admin-sidebar">
	<div class="admin-sidebar-brand">
		<img src="<?php echo asset_url('images/logo.svg'); ?>" alt="GICP Talaga logo">
		<div>
			<h1 class="admin-sidebar-title">GICP Talaga</h1>
			<p class="admin-sidebar-copy">Church Website CMS</p>
		</div>
	</div>

	<nav aria-label="Admin navigation">
		<?php foreach ($admin_menu as $item): ?>
			<a class="admin-nav-link <?php echo strpos(uri_string(), trim($item['url'], '/')) === 0 ? 'active' : ''; ?>" href="<?php echo site_url($item['url']); ?>">
				<i class="bi <?php echo html_escape($item['icon']); ?>"></i>
				<span><?php echo html_escape($item['label']); ?></span>
			</a>
		<?php endforeach; ?>
	</nav>
</aside>

<div class="admin-main">
	<header class="admin-topbar d-flex justify-content-between align-items-center gap-3">
		<div class="d-flex align-items-center gap-3">
			<button class="btn btn-outline-secondary d-lg-none" type="button" data-sidebar-toggle aria-label="Toggle sidebar">
				<i class="bi bi-list"></i>
			</button>
			<div>
				<div class="text-muted small">Administration</div>
				<div class="fw-bold"><?php echo html_escape(isset($page_title) ? $page_title : 'Dashboard'); ?></div>
			</div>
		</div>
		<div class="text-end">
			<div class="fw-bold"><?php echo html_escape($admin_user ? trim($admin_user->first_name.' '.$admin_user->last_name) : 'Administrator'); ?></div>
			<div class="small text-muted"><?php echo html_escape($admin_user && isset($admin_user->role_name) ? $admin_user->role_name : 'User'); ?></div>
		</div>
	</header>

	<main class="admin-page">
		<?php $this->load->view('admin/partials/flash'); ?>