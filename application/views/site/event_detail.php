<?php
$title = $event->title;
$lead = $event->summary;
$eyebrow = 'Event Details';
$this->load->view('site/partials/page_header', compact('title', 'lead', 'eyebrow'));
?>

<section class="section-spacing">
	<div class="container">
		<?php $this->load->view('site/partials/flash'); ?>
		<div class="row g-4">
			<div class="col-lg-8">
				<div class="detail-card h-100">
					<img class="card-media" src="<?php echo media_url($event->image, 'images/placeholders/event.svg'); ?>" alt="<?php echo html_escape($event->title); ?>">
					<div class="detail-meta">
						<span class="content-badge"><i class="bi bi-calendar-event"></i><?php echo formatted_date($event->start_datetime, 'F j, Y g:i A'); ?></span>
						<?php if (!empty($event->end_datetime)): ?><span class="content-badge"><i class="bi bi-clock"></i>Ends <?php echo formatted_date($event->end_datetime, 'F j, Y g:i A'); ?></span><?php endif; ?>
						<span class="content-badge"><i class="bi bi-geo-alt"></i><?php echo html_escape($event->location); ?></span>
					</div>
					<p class="detail-copy"><?php echo nl2br(html_escape($event->description)); ?></p>
				</div>
			</div>
			<div class="col-lg-4">
				<div class="detail-card mb-4">
					<h2 class="h4 mb-3">Event Snapshot</h2>
					<ul class="detail-list">
						<li><strong>Registration:</strong> <?php echo (int) $event->registration_enabled === 1 ? 'Open' : 'Not required'; ?></li>
						<?php if (!empty($event->registration_limit)): ?><li><strong>Capacity:</strong> <?php echo html_escape($event->registration_limit); ?> seats</li><?php endif; ?>
						<li><strong>Current Registrations:</strong> <?php echo html_escape($registration_count); ?></li>
					</ul>
				</div>
				<?php if ((int) $event->registration_enabled === 1): ?>
					<div class="detail-card" id="registration">
						<h2 class="h4 mb-3">Register for this event</h2>
						<form method="post" action="<?php echo site_url('event-register/'.$event->id); ?>">
							<?php echo csrf_input(); ?>
							<div class="mb-3">
								<label class="form-label" for="eventName">Full Name</label>
								<input id="eventName" class="form-control" type="text" name="full_name" required>
							</div>
							<div class="mb-3">
								<label class="form-label" for="eventEmail">Email</label>
								<input id="eventEmail" class="form-control" type="email" name="email" required>
							</div>
							<div class="mb-3">
								<label class="form-label" for="eventMobile">Mobile Number</label>
								<input id="eventMobile" class="form-control" type="text" name="mobile_number">
							</div>
							<div class="mb-3">
								<label class="form-label" for="eventNotes">Notes</label>
								<textarea id="eventNotes" class="form-control" name="notes" rows="4"></textarea>
							</div>
							<button class="btn btn-church w-100" type="submit">Submit Registration</button>
						</form>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>