<div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
	<div>
		<h1 class="admin-page-title">Church CMS Dashboard</h1>
		<p class="text-muted mb-0">Manage church information, worship content, announcements, media, and member-facing updates from one place.</p>
	</div>
	<div class="d-flex flex-wrap gap-2">
		<a class="btn btn-admin" href="<?php echo base_url(); ?>" target="_blank">View Website</a>
		<a class="btn btn-outline-secondary" href="<?php echo site_url('admin/change-password'); ?>">Change Password</a>
		<a class="btn btn-outline-danger" href="<?php echo site_url('admin/logout'); ?>">Logout</a>
	</div>
</div>

<?php if (!empty($stats)): ?>
	<div class="row g-4 mb-4">
		<?php foreach ($stats as $stat): ?>
			<div class="col-sm-6 col-xl-3">
				<div class="admin-card h-100 mb-0">
					<div class="admin-stat">
						<div class="admin-stat-icon"><i class="bi <?php echo html_escape($stat['icon']); ?>"></i></div>
						<div>
							<div class="admin-stat-value"><?php echo (int) $stat['value']; ?></div>
							<div class="admin-stat-label"><?php echo html_escape($stat['label']); ?></div>
						</div>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
<?php endif; ?>

<div class="row g-4">
	<div class="col-xl-8">
		<?php if ($can_view_activity_logs): ?>
			<div class="admin-card">
				<div class="d-flex justify-content-between align-items-center mb-3">
					<h2 class="h3 mb-0">Latest Activities</h2>
					<a class="small" href="<?php echo site_url('admin/content/activity_logs'); ?>">View all logs</a>
				</div>
				<div class="table-responsive">
					<table class="table align-middle mb-0">
						<thead>
							<tr>
								<th>User</th>
								<th>Action</th>
								<th>Module</th>
								<th>Description</th>
								<th>Timestamp</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($latest_activities as $activity): ?>
								<tr>
									<td><?php echo html_escape(!empty($activity->user_name) ? $activity->user_name : 'System'); ?></td>
									<td><span class="badge-soft"><?php echo html_escape(module_label($activity->action)); ?></span></td>
									<td><?php echo html_escape(module_label($activity->module)); ?></td>
									<td><?php echo html_escape($activity->description); ?></td>
									<td><?php echo html_escape(formatted_date($activity->created_at, 'M d, Y h:i A')); ?></td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		<?php endif; ?>

		<div class="row g-4">
			<?php if ($can_view_events): ?>
				<div class="col-lg-6">
					<div class="admin-card mb-0 h-100">
						<div class="d-flex justify-content-between align-items-center mb-3">
							<h2 class="h3 mb-0">Upcoming Events</h2>
							<a class="small" href="<?php echo site_url('admin/content/events'); ?>">Manage events</a>
						</div>
						<div class="list-group list-group-flush">
							<?php foreach ($upcoming_events as $event): ?>
								<div class="list-group-item px-0 border-0 border-bottom pb-3 mb-3">
									<div class="fw-bold"><?php echo html_escape($event->title); ?></div>
									<div class="text-muted small mb-1"><?php echo html_escape(formatted_date($event->start_datetime, 'F d, Y h:i A')); ?></div>
									<div class="text-muted"><?php echo html_escape($event->location); ?></div>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
			<?php endif; ?>
			<div class="col-lg-6">
				<div class="admin-card mb-0 h-100">
					<h2 class="h3 mb-3">Quick Links</h2>
					<div class="row g-3">
						<?php foreach ($quick_links as $link): ?>
							<div class="col-sm-6">
								<a class="admin-card d-block h-100 text-decoration-none mb-0" href="<?php echo site_url($link['url']); ?>">
									<div class="d-flex align-items-center gap-3">
										<div class="admin-stat-icon"><i class="bi <?php echo html_escape($link['icon']); ?>"></i></div>
										<div>
											<div class="fw-bold text-dark"><?php echo html_escape($link['label']); ?></div>
											<div class="small text-muted">Open module</div>
										</div>
									</div>
								</a>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-xl-4">
		<?php if ($can_view_event_registrations || $can_view_newsletter_subscriptions): ?>
			<div class="admin-card">
				<h2 class="h3 mb-3">Ministry Inbox</h2>
				<div class="row g-3">
					<?php if ($can_view_event_registrations): ?>
						<div class="col-12">
							<div class="d-flex justify-content-between align-items-center p-3 rounded-4 border">
								<div>
									<div class="fw-bold">Event Registrations</div>
									<div class="small text-muted">New sign-ups for church events</div>
								</div>
								<div class="admin-stat-value fs-3"><?php echo (int) $registration_count; ?></div>
							</div>
						</div>
					<?php endif; ?>
					<?php if ($can_view_newsletter_subscriptions): ?>
						<div class="col-12">
							<div class="d-flex justify-content-between align-items-center p-3 rounded-4 border">
								<div>
									<div class="fw-bold">Newsletter Subscribers</div>
									<div class="small text-muted">Email addresses receiving updates</div>
								</div>
								<div class="admin-stat-value fs-3"><?php echo (int) $subscription_count; ?></div>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</div>
		<?php endif; ?>

		<?php if ($can_view_prayer_requests): ?>
			<div class="admin-card">
				<div class="d-flex justify-content-between align-items-center mb-3">
					<h2 class="h3 mb-0">Recent Prayer Requests</h2>
					<a class="small" href="<?php echo site_url('admin/content/prayer_requests'); ?>">Open module</a>
				</div>
				<div class="list-group list-group-flush">
					<?php foreach ($pending_requests as $request): ?>
						<div class="list-group-item px-0 border-0 border-bottom pb-3 mb-3">
							<div class="fw-bold"><?php echo html_escape($request->full_name); ?></div>
							<div class="small text-muted mb-1"><?php echo html_escape(formatted_date($request->created_at, 'M d, Y h:i A')); ?></div>
							<div><?php echo html_escape(excerpt($request->request_text, 100)); ?></div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		<?php endif; ?>

		<?php if ($can_view_contact_messages): ?>
			<div class="admin-card mb-0">
				<div class="d-flex justify-content-between align-items-center mb-3">
					<h2 class="h3 mb-0">New Contact Messages</h2>
					<a class="small" href="<?php echo site_url('admin/content/contact_messages'); ?>">Open module</a>
				</div>
				<div class="list-group list-group-flush">
					<?php foreach ($new_messages as $message): ?>
						<div class="list-group-item px-0 border-0 border-bottom pb-3 mb-3">
							<div class="fw-bold"><?php echo html_escape($message->subject); ?></div>
							<div class="small text-muted mb-1"><?php echo html_escape($message->full_name.' | '.$message->email); ?></div>
							<div><?php echo html_escape(excerpt($message->message, 110)); ?></div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		<?php endif; ?>
	</div>
</div>