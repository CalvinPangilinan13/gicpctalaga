<?php
	$church_name = isset($church_profile->short_name) ? $church_profile->short_name : 'GICP Talaga';
	$current_uri = trim(uri_string(), '/');
	$navigation_groups = array();

	foreach ($admin_menu as $item)
	{
		$section = isset($item['section']) ? $item['section'] : 'Navigation';
		$section_key = strtolower(trim(preg_replace('/[^a-z0-9]+/i', '-', $section), '-'));
		$item_uri = trim($item['url'], '/');
		$is_active = $current_uri === $item_uri || strpos($current_uri, $item_uri.'/') === 0;

		if (!isset($navigation_groups[$section_key]))
		{
			$navigation_groups[$section_key] = array(
				'label' => $section,
				'key' => $section_key,
				'items' => array(),
				'is_active' => FALSE,
			);
		}

		$item['is_active'] = $is_active;
		$item['search_label'] = strtolower($item['label'].' '.$section);
		$navigation_groups[$section_key]['items'][] = $item;

		if ($is_active)
		{
			$navigation_groups[$section_key]['is_active'] = TRUE;
		}
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

<button class="admin-sidebar-backdrop" type="button" data-sidebar-dismiss aria-label="Close sidebar"></button>

<aside class="admin-sidebar" data-admin-sidebar>
	<div class="admin-sidebar-shell">
		<div class="admin-sidebar-brand-card">
			<div class="admin-sidebar-toolbar">
				<span class="admin-sidebar-pill"><i class="bi bi-stars"></i> Control Center</span>
				<button class="admin-sidebar-close d-lg-none" type="button" data-sidebar-dismiss aria-label="Close sidebar">
					<i class="bi bi-x-lg"></i>
				</button>
			</div>

			<div class="admin-sidebar-brand">
				<img src="<?php echo media_url(isset($church_profile->logo) ? $church_profile->logo : '', 'images/logo.svg'); ?>" alt="<?php echo html_escape($church_name); ?> logo">
				<div>
					<div class="admin-sidebar-eyebrow">CMS Workspace</div>
					<h1 class="admin-sidebar-title"><?php echo html_escape($church_name); ?></h1>
					<p class="admin-sidebar-copy">A calmer control panel for content, engagement, and publishing.</p>
				</div>
			</div>

			<div class="admin-sidebar-stats">
				<div class="admin-sidebar-stat-card">
					<div class="admin-sidebar-stat-value"><?php echo count($admin_menu); ?></div>
					<div class="admin-sidebar-stat-label">Modules</div>
				</div>
				<div class="admin-sidebar-stat-card">
					<div class="admin-sidebar-stat-value"><?php echo count($navigation_groups); ?></div>
					<div class="admin-sidebar-stat-label">Sections</div>
				</div>
			</div>

			<a class="admin-sidebar-site-link" href="<?php echo site_url(); ?>" target="_blank" rel="noopener noreferrer">
				<span><i class="bi bi-box-arrow-up-right"></i> View public site</span>
				<i class="bi bi-arrow-right-short"></i>
			</a>
		</div>

		<nav class="admin-sidebar-nav" aria-label="Admin navigation">
			<div class="admin-sidebar-search" role="search">
				<i class="bi bi-search"></i>
				<input type="search" placeholder="Search navigation" aria-label="Search navigation" data-sidebar-search>
				<button class="admin-sidebar-search-clear" type="button" data-sidebar-search-clear aria-label="Clear search" hidden>
					<i class="bi bi-x-circle"></i>
				</button>
			</div>

			<div class="admin-sidebar-nav-groups" data-sidebar-groups>
				<?php foreach ($navigation_groups as $group): ?>
					<?php $section_body_id = 'admin-nav-group-'.$group['key']; ?>
					<div class="admin-nav-section <?php echo $group['is_active'] ? 'is-active' : 'is-collapsed'; ?>" data-nav-section data-section-key="<?php echo html_escape($group['key']); ?>">
						<button class="admin-nav-section-toggle" type="button" data-nav-section-toggle aria-expanded="<?php echo $group['is_active'] ? 'true' : 'false'; ?>" aria-controls="<?php echo html_escape($section_body_id); ?>">
							<span>
								<span class="admin-nav-section-title"><?php echo html_escape($group['label']); ?></span>
								<span class="admin-nav-section-meta"><?php echo count($group['items']); ?> modules</span>
							</span>
							<span class="admin-nav-section-state">
								<span class="admin-nav-section-count"><?php echo count($group['items']); ?></span>
								<i class="bi bi-chevron-down"></i>
							</span>
						</button>

						<div class="admin-nav-section-body" id="<?php echo html_escape($section_body_id); ?>">
							<div class="admin-nav-section-links">
								<?php foreach ($group['items'] as $item): ?>
									<a class="admin-nav-link <?php echo $item['is_active'] ? 'active' : ''; ?>" href="<?php echo site_url($item['url']); ?>" data-nav-item data-nav-label="<?php echo html_escape($item['search_label']); ?>">
										<span class="admin-nav-link-icon"><i class="bi <?php echo html_escape($item['icon']); ?>"></i></span>
										<span class="admin-nav-link-copy"><?php echo html_escape($item['label']); ?></span>
										<span class="admin-nav-link-arrow"><i class="bi bi-chevron-right"></i></span>
									</a>
								<?php endforeach; ?>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>

			<div class="admin-sidebar-empty" data-sidebar-empty hidden>
				<div class="admin-sidebar-empty-icon"><i class="bi bi-search-heart"></i></div>
				<div class="admin-sidebar-empty-title">No matching navigation</div>
				<p>Try a different keyword or clear the search field.</p>
			</div>
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