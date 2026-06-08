<?php
$title = 'Events';
$lead = 'Plan your participation in upcoming worship gatherings, fellowships, training events, and outreach opportunities.';
$eyebrow = 'Church Calendar';
$this->load->view('site/partials/page_header', compact('title', 'lead', 'eyebrow'));
?>

<section class="section-spacing">
	<div class="container">
		<form class="content-card search-form mb-4" method="get">
			<div class="row g-3 align-items-end">
				<div class="col-lg-10">
					<label class="form-label" for="eventSearch">Search events</label>
					<input id="eventSearch" class="form-control" type="search" name="q" value="<?php echo html_escape($search_term); ?>" placeholder="Search by title, location, or description">
				</div>
				<div class="col-lg-2 d-grid">
					<button class="btn btn-church" type="submit">Search</button>
				</div>
			</div>
		</form>

		<div class="row g-4">
			<?php foreach ($events as $event): ?>
				<div class="col-md-6 col-xl-4">
					<div class="content-card h-100">
						<img class="card-media" src="<?php echo media_url($event->image, 'images/placeholders/event.svg'); ?>" alt="<?php echo html_escape($event->title); ?>">
						<div class="detail-meta">
							<span class="content-badge"><i class="bi bi-calendar-event"></i><?php echo formatted_date($event->start_datetime, 'F j, Y g:i A'); ?></span>
							<span class="content-badge"><i class="bi bi-geo-alt"></i><?php echo html_escape($event->location); ?></span>
						</div>
						<h2 class="card-title"><?php echo html_escape($event->title); ?></h2>
						<p class="card-text"><?php echo excerpt($event->summary, 150); ?></p>
						<a class="btn btn-outline-church" href="<?php echo site_url('events/'.$event->slug); ?>">View Event</a>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>