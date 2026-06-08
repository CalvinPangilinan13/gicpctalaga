<?php
	$church_name = isset($church_profile->short_name) ? $church_profile->short_name : 'GICP Talaga';
	$current_uri = trim(uri_string(), '/');
	$grouped_menu = array();

	foreach ($admin_menu as $item)
	{
		$section = isset($item['section']) ? $item['section'] : 'Navigation';

		if (!isset($grouped_menu[$section]))
		{
			$grouped_menu[$section] = array();
		}

		$grouped_menu[$section][] = $item;
	}

	$display_name = $admin_user ? trim($admin_user->first_name.' '.$admin_user->last_name) : 'Administrator';
	$initials_source = preg_split('/\s+/', trim($display_name));
	$initials = '';

	foreach ($initials_source as $part)
	{
		if ($part === '')
		{
			continue;
		}

		$initials .= strtoupper(substr($part, 0, 1));

		if (strlen($initials) === 2)
		{
			break;
		}
	}

	if ($initials === '')
	{
		$initials = 'AD';
	}
?>

<aside class="admin-sidebar">
	<div class="admin-sidebar-shell">
		<div class="admin-sidebar-brand-card">
			<div class="admin-sidebar-brand">
				<img src="<?php echo media_url(isset($church_profile->logo) ? $church_profile->logo : '', 'images/logo.svg'); ?>" alt="<?php echo html_escape($church_name); ?> logo">
				<div>
					<div class="admin-sidebar-eyebrow">CMS Workspace</div>
					<h1 class="admin-sidebar-title"><?php echo html_escape($church_name); ?></h1>
					<p class="admin-sidebar-copy">Manage worship content, engagement, and site updates.</p>
				</div>
			</div>

			<a class="admin-sidebar-site-link" href="<?php echo site_url(); ?>" target="_blank" rel="noopener noreferrer">
				<span><i class="bi bi-box-arrow-up-right"></i> View public site</span>
				<i class="bi bi-arrow-right-short"></i>
			</a>
		</div>

		<nav class="admin-sidebar-nav" aria-label="Admin navigation">
			<?php foreach ($grouped_menu as $section => $items): ?>
				<div class="admin-nav-section">
					<div class="admin-nav-section-title"><?php echo html_escape($section); ?></div>
					<div class="admin-nav-section-links">
						<?php foreach ($items as $item): ?>
							<?php $is_active = $current_uri === trim($item['url'], '/') || strpos($current_uri, trim($item['url'], '/').'/') === 0; ?>
							<a class="admin-nav-link <?php echo $is_active ? 'active' : ''; ?>" href="<?php echo site_url($item['url']); ?>">
								<span class="admin-nav-link-icon"><i class="bi <?php echo html_escape($item['icon']); ?>"></i></span>
								<span class="admin-nav-link-copy"><?php echo html_escape($item['label']); ?></span>
								<span class="admin-nav-link-arrow"><i class="bi bi-chevron-right"></i></span>
							</a>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endforeach; ?>
		</nav>

		<div class="admin-sidebar-footer">
			<div class="admin-sidebar-user">
				<div class="admin-sidebar-user-avatar"><?php echo html_escape($initials); ?></div>
				<div>
					<div class="admin-sidebar-user-name"><?php echo html_escape($display_name); ?></div>
					<div class="admin-sidebar-user-role"><?php echo html_escape($admin_user && isset($admin_user->role_name) ? $admin_user->role_name : 'Administrator'); ?></div>
				</div>
			</div>
			<div class="admin-sidebar-footer-actions">
				<a class="admin-sidebar-footer-link" href="<?php echo site_url('admin/content/settings'); ?>">
					<i class="bi bi-sliders"></i>
					<span>Preferences</span>
				</a>
				<a class="admin-sidebar-footer-link" href="<?php echo site_url('admin/logout'); ?>">
					<i class="bi bi-box-arrow-right"></i>
					<span>Sign out</span>
				</a>
			</div>
		</div>
	</div>
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