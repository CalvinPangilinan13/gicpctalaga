<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo html_escape(isset($page_title) ? $page_title.' | '.$church_profile->church_name : $church_profile->church_name); ?></title>
	<meta name="description" content="<?php echo html_escape(isset($meta_description) ? $meta_description : setting_value($settings, 'meta_description', $church_profile->brief_intro)); ?>">
	<meta name="keywords" content="<?php echo html_escape(setting_value($settings, 'site_keywords', 'church, presbyterian, grace, sermons, ministries')); ?>">
	<meta name="theme-color" content="<?php echo html_escape(setting_value($settings, 'primary_color', '#123d73')); ?>">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Source+Sans+3:wght@400;600;700&display=swap" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
	<link rel="stylesheet" href="<?php echo asset_url('css/site.css'); ?>">
</head>
<body>
<a class="skip-link" href="#main-content">Skip to main content</a>

<div class="site-topbar py-2">
	<div class="container d-flex flex-column flex-lg-row justify-content-between gap-2 align-items-lg-center">
		<div class="d-flex flex-wrap gap-3">
			<span><i class="bi bi-geo-alt-fill me-1"></i><?php echo html_escape($church_profile->address); ?></span>
			<span><i class="bi bi-telephone-fill me-1"></i><?php echo html_escape($church_profile->mobile_number); ?></span>
		</div>
		<div class="d-flex flex-wrap gap-3 align-items-center">
			<?php foreach ($service_schedules as $schedule): ?>
				<span><strong><?php echo html_escape($schedule->title); ?>:</strong> <?php echo html_escape($schedule->day_name.' '.$schedule->time_range); ?></span>
			<?php endforeach; ?>
		</div>
	</div>
</div>

<nav class="navbar navbar-expand-xl sticky-top">
	<div class="container">
		<?php
		$dropdown_urls = array('about-us', 'pastors', 'ministries', 'sermons', 'events', 'gallery', 'contact');
		$dropdown_items = array();
		$dropdown_active = FALSE;

		foreach ($menu_items as $menu_item)
		{
			if (in_array(trim($menu_item->url, '/'), $dropdown_urls, TRUE))
			{
				$dropdown_items[] = $menu_item;
				if (is_active_menu($menu_item->url) === 'active')
				{
					$dropdown_active = TRUE;
				}
			}
		}
		?>
		<a class="navbar-brand" href="<?php echo base_url(); ?>">
			<img class="brand-mark" src="<?php echo media_url($church_profile->logo, 'images/logo.svg'); ?>" alt="<?php echo html_escape($church_profile->short_name); ?> logo">
			<span>
				<span class="brand-title d-block"><?php echo html_escape($church_profile->short_name); ?></span>
				<span class="brand-subtitle d-block"><?php echo html_escape($church_profile->tagline); ?></span>
			</span>
		</a>
		<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#siteNav" aria-controls="siteNav" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="siteNav">
			<ul class="navbar-nav ms-auto align-items-xl-center gap-xl-1 mb-3 mb-xl-0">
				<?php $dropdown_rendered = FALSE; ?>
				<?php foreach ($menu_items as $menu_item): ?>
					<?php if (in_array(trim($menu_item->url, '/'), $dropdown_urls, TRUE)): ?>
						<?php if (!$dropdown_rendered && !empty($dropdown_items)): ?>
							<li class="nav-item dropdown">
								<a class="nav-link dropdown-toggle <?php echo $dropdown_active ? 'active' : ''; ?>" href="#" id="headerExploreDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
									Explore
								</a>
								<ul class="dropdown-menu dropdown-menu-xl-end" aria-labelledby="headerExploreDropdown">
									<?php foreach ($dropdown_items as $dropdown_item): ?>
										<li>
											<a class="dropdown-item <?php echo is_active_menu($dropdown_item->url); ?>" target="<?php echo html_escape($dropdown_item->target); ?>" href="<?php echo base_url(ltrim($dropdown_item->url, '/')); ?>">
												<?php if (!empty($dropdown_item->icon)): ?><i class="bi <?php echo html_escape($dropdown_item->icon); ?> me-2"></i><?php endif; ?><?php echo html_escape($dropdown_item->label); ?>
											</a>
										</li>
									<?php endforeach; ?>
								</ul>
							</li>
							<?php $dropdown_rendered = TRUE; ?>
						<?php endif; ?>
						<?php continue; ?>
					<?php endif; ?>
					<li class="nav-item">
						<a class="nav-link <?php echo is_active_menu($menu_item->url); ?>" target="<?php echo html_escape($menu_item->target); ?>" href="<?php echo base_url(ltrim($menu_item->url, '/')); ?>"><?php echo html_escape($menu_item->label); ?></a>
					</li>
				<?php endforeach; ?>
			</ul>
			<div class="nav-actions d-flex flex-column flex-xl-row gap-2 align-items-xl-center ms-xl-3">
				<form class="search-form navbar-search" action="<?php echo site_url('search'); ?>" method="get" role="search">
					<div class="search-shell">
						<label class="visually-hidden" for="navSearch">Search website</label>
						<input id="navSearch" class="form-control" type="search" name="q" placeholder="Search sermons, events, news" aria-label="Search website">
						<button class="search-submit" type="submit" aria-label="Submit search">
							<i class="bi bi-search"></i>
						</button>
					</div>
				</form>
				<a class="btn btn-church" href="<?php echo site_url('contact'); ?>">Prayer & Contact</a>
			</div>
		</div>
	</div>
</nav>

<main id="main-content">