<?php $hero_image_url = media_url(isset($church_profile->hero_image) ? $church_profile->hero_image : '', 'images/hero-pattern.svg'); ?>

<section class="hero-section" style="--hero-image: url('<?php echo html_escape($hero_image_url); ?>');">
	<div class="container position-relative">
		<div class="row align-items-center g-5">
			<div class="col-lg-7">
				<span class="eyebrow text-white bg-transparent border border-light border-opacity-25">Grace in Christ Presbyterian Church</span>
				<h1 class="hero-title"><?php echo html_escape($hero_title); ?></h1>
				<p class="hero-copy"><?php echo html_escape($hero_subtitle); ?></p>
				<div class="hero-actions">
					<a class="btn btn-church" href="<?php echo site_url($hero_button_link); ?>"><?php echo html_escape($hero_button_text); ?></a>
					<a class="btn btn-outline-light" href="<?php echo site_url('sermons'); ?>">Listen to Sermons</a>
				</div>
				<div class="row g-3 mt-4">
					<div class="col-sm-6">
						<div class="hero-highlight">
							<div class="card-overline text-white-50">Full Name</div>
							<h2 class="h3 mb-2"><?php echo html_escape($church_profile->church_name); ?></h2>
							<p class="mb-0 text-white-50"><?php echo html_escape($church_profile->welcome_message); ?></p>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="hero-highlight">
							<div class="card-overline text-white-50">Prayer Request</div>
							<p class="mb-3 text-white-50"><?php echo html_escape($church_profile->prayer_cta_text); ?></p>
							<a class="btn btn-outline-light btn-sm" href="<?php echo site_url('contact#prayer-request'); ?>">Share a Prayer Request</a>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-5">
				<div class="hero-glass mb-4">
					<div class="card-overline text-white-50">Daily Bible Verse</div>
					<?php if (!empty($daily_verse)): ?>
						<div class="verse-text text-white"><?php echo html_escape($daily_verse->verse_text); ?></div>
						<div class="verse-reference"><?php echo html_escape($daily_verse->reference); ?></div>
						<button class="btn btn-outline-light rounded-pill mt-3" type="button" data-share-verse="<?php echo html_escape($daily_verse->verse_text.' - '.$daily_verse->reference); ?>" data-share-title="Daily Bible Verse">Share Verse</button>
					<?php else: ?>
						<p class="mb-0 text-white-50">Daily verse will appear here when published from the CMS.</p>
					<?php endif; ?>
				</div>
				<div class="hero-glass">
					<div class="card-overline text-white-50">Service Schedule</div>
					<ul class="schedule-list">
						<?php foreach ($service_schedules as $schedule): ?>
							<li class="bg-transparent border border-light border-opacity-10 text-white">
								<strong><?php echo html_escape($schedule->title); ?></strong><br>
								<span class="text-white-50"><?php echo html_escape($schedule->day_name.' | '.$schedule->time_range); ?></span>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="section-spacing">
	<div class="container">
		<?php $this->load->view('site/partials/flash'); ?>
		<div class="row g-4 align-items-stretch">
			<div class="col-lg-7">
				<div class="section-heading">
					<span class="eyebrow">About GICP Talaga</span>
					<h2 class="section-title">A congregation grounded in Scripture, grace, and Gospel witness.</h2>
					<p class="section-lead"><?php echo html_escape($church_profile->brief_intro); ?></p>
				</div>
			</div>
			<div class="col-lg-5">
				<div class="metric-card h-100">
					<div class="metric-value"><?php echo html_escape($visitor_count); ?></div>
					<div class="metric-label">Recorded site visits and returning community touchpoints</div>
				</div>
			</div>
		</div>

		<div class="row g-4 mt-1">
			<div class="col-lg-6">
				<div class="content-card h-100">
					<div class="card-overline">Mission</div>
					<h3 class="card-title">Our Mission</h3>
					<p class="card-text mb-0"><?php echo nl2br(html_escape($church_profile->mission)); ?></p>
				</div>
			</div>
			<div class="col-lg-6">
				<div class="content-card h-100">
					<div class="card-overline">Vision</div>
					<h3 class="card-title">Our Vision</h3>
					<p class="card-text mb-0"><?php echo nl2br(html_escape($church_profile->vision)); ?></p>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="section-spacing bg-white">
	<div class="container">
		<div class="section-heading text-center mx-auto">
			<span class="eyebrow">Meet Our Pastors</span>
			<h2 class="section-title">Faithful shepherds serving the church with prayer, preaching, and care.</h2>
		</div>
		<div class="row g-4">
			<?php foreach ($featured_pastors as $pastor): ?>
				<div class="col-md-6 col-xl-3">
					<div class="content-card h-100">
						<img class="card-media" src="<?php echo media_url($pastor->photo, 'images/placeholders/pastor.svg'); ?>" alt="<?php echo html_escape($pastor->name); ?>">
						<div class="card-overline"><?php echo html_escape($pastor->position); ?></div>
						<h3 class="card-title"><?php echo html_escape($pastor->name); ?></h3>
						<p class="card-text"><?php echo excerpt($pastor->bio, 160); ?></p>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section class="section-spacing">
	<div class="container">
		<div class="section-heading text-center mx-auto">
			<span class="eyebrow">Ministries</span>
			<h2 class="section-title">Serving every generation with intentional, Christ-centered ministry.</h2>
		</div>
		<div class="row g-4">
			<?php foreach ($featured_ministries as $ministry): ?>
				<div class="col-md-6 col-xl-4">
					<div class="content-card h-100">
						<img class="card-media" src="<?php echo media_url($ministry->image, 'images/placeholders/ministry.svg'); ?>" alt="<?php echo html_escape($ministry->name); ?>">
						<div class="card-overline"><?php echo html_escape($ministry->meeting_schedule); ?></div>
						<h3 class="card-title"><?php echo html_escape($ministry->name); ?></h3>
						<p class="card-text"><?php echo excerpt($ministry->description, 150); ?></p>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section class="section-spacing bg-white">
	<div class="container">
		<div class="row g-4 align-items-start">
			<div class="col-lg-6">
				<div class="section-heading">
					<span class="eyebrow">Upcoming Events</span>
					<h2 class="section-title">Gather, grow, and serve together.</h2>
				</div>
				<div class="list-divider">
					<?php foreach ($upcoming_events as $event): ?>
						<div class="content-card">
							<div class="detail-meta">
								<span class="content-badge"><i class="bi bi-calendar-event"></i><?php echo formatted_date($event->start_datetime, 'F j, Y g:i A'); ?></span>
								<span class="content-badge"><i class="bi bi-geo-alt"></i><?php echo html_escape($event->location); ?></span>
							</div>
							<h3 class="card-title"><?php echo html_escape($event->title); ?></h3>
							<p class="card-text"><?php echo excerpt($event->summary, 150); ?></p>
							<a class="btn btn-outline-church" href="<?php echo site_url('events/'.$event->slug); ?>">View Event Details</a>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
			<div class="col-lg-6">
				<div class="section-heading">
					<span class="eyebrow">Latest Announcements</span>
					<h2 class="section-title">Church notices, updates, and congregational reminders.</h2>
				</div>
				<div class="row g-4">
					<?php foreach ($featured_announcements as $announcement): ?>
						<div class="col-12">
							<div class="content-card">
								<div class="detail-meta">
									<span class="content-badge"><i class="bi bi-pin-angle"></i><?php echo $announcement->is_featured ? 'Featured' : 'Announcement'; ?></span>
									<span class="content-badge"><i class="bi bi-calendar3"></i><?php echo formatted_date($announcement->publish_date); ?></span>
								</div>
								<h3 class="card-title"><?php echo html_escape($announcement->title); ?></h3>
								<p class="card-text"><?php echo excerpt($announcement->summary, 150); ?></p>
								<a class="btn btn-outline-church" href="<?php echo site_url('announcements/'.$announcement->slug); ?>">Read Announcement</a>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="section-spacing">
	<div class="container">
		<div class="row g-4">
			<div class="col-lg-6">
				<div class="section-heading">
					<span class="eyebrow">Church Updates</span>
					<h2 class="section-title">Stories of ministry, outreach, and Gospel work.</h2>
				</div>
				<div class="row g-4">
					<?php foreach ($latest_news as $item): ?>
						<div class="col-12">
							<div class="content-card">
								<div class="card-overline"><?php echo html_escape($item->category); ?></div>
								<h3 class="card-title"><?php echo html_escape($item->title); ?></h3>
								<p class="card-text"><?php echo excerpt($item->summary, 145); ?></p>
								<a class="btn btn-outline-church" href="<?php echo site_url('news-updates/'.$item->slug); ?>">Read Update</a>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
			<div class="col-lg-6">
				<div class="section-heading">
					<span class="eyebrow">Latest Sermons</span>
					<h2 class="section-title">Listen again to Christ-centered preaching.</h2>
				</div>
				<div class="row g-4">
					<?php foreach ($latest_sermons as $sermon): ?>
						<div class="col-12">
							<div class="content-card">
								<div class="detail-meta">
									<span class="content-badge"><i class="bi bi-mic"></i><?php echo html_escape($sermon->speaker); ?></span>
									<span class="content-badge"><i class="bi bi-calendar2-week"></i><?php echo formatted_date($sermon->sermon_date); ?></span>
								</div>
								<h3 class="card-title"><?php echo html_escape($sermon->title); ?></h3>
								<p class="card-text"><?php echo excerpt($sermon->excerpt, 150); ?></p>
								<a class="btn btn-outline-church" href="<?php echo site_url('sermons/'.$sermon->slug); ?>">Watch or Download</a>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="section-spacing bg-white">
	<div class="container">
		<div class="row g-4 align-items-stretch">
			<div class="col-lg-7">
				<div class="content-card h-100">
					<div class="section-heading mb-4">
						<span class="eyebrow">Prayer Request CTA</span>
						<h2 class="section-title">We would be honored to pray with you.</h2>
						<p class="section-lead"><?php echo html_escape($church_profile->prayer_cta_text); ?></p>
					</div>
					<form class="row g-3" action="<?php echo site_url('prayer-request'); ?>" method="post">
						<?php echo csrf_input(); ?>
						<input type="hidden" name="redirect_url" value="<?php echo current_url(); ?>">
						<div class="col-md-6">
							<label class="form-label" for="homePrayerName">Full Name</label>
							<input id="homePrayerName" class="form-control" type="text" name="full_name" required>
						</div>
						<div class="col-md-6">
							<label class="form-label" for="homePrayerEmail">Email</label>
							<input id="homePrayerEmail" class="form-control" type="email" name="email" required>
						</div>
						<div class="col-md-6">
							<label class="form-label" for="homePrayerMobile">Mobile Number</label>
							<input id="homePrayerMobile" class="form-control" type="text" name="mobile_number">
						</div>
						<div class="col-md-6 d-flex align-items-end">
							<div class="form-check">
								<input id="homePrayerPrivate" class="form-check-input" type="checkbox" name="is_private" value="1" checked>
								<label class="form-check-label" for="homePrayerPrivate">Keep my request private</label>
							</div>
						</div>
						<div class="col-12">
							<label class="form-label" for="homePrayerText">Prayer Request</label>
							<textarea id="homePrayerText" class="form-control" name="request_text" rows="5" required></textarea>
						</div>
						<div class="col-12">
							<button class="btn btn-church" type="submit">Submit Prayer Request</button>
						</div>
					</form>
				</div>
			</div>
			<div class="col-lg-5 d-flex flex-column gap-4">
				<div class="content-card">
					<div class="card-overline">Contact Information</div>
					<h3 class="card-title">Visit and Worship With Us</h3>
					<p class="card-text"><?php echo html_escape($church_profile->address); ?></p>
					<ul class="detail-list">
						<li><strong>Email:</strong> <a href="mailto:<?php echo html_escape($church_profile->email); ?>"><?php echo html_escape($church_profile->email); ?></a></li>
						<li><strong>Mobile:</strong> <a href="tel:<?php echo html_escape($church_profile->mobile_number); ?>"><?php echo html_escape($church_profile->mobile_number); ?></a></li>
						<li><strong>Livestream:</strong> <a href="<?php echo html_escape(setting_value($settings, 'livestream_url', $church_profile->youtube_url)); ?>" target="_blank" rel="noreferrer">Join online</a></li>
					</ul>
				</div>
				<div class="content-card">
					<div class="card-overline">Photo Highlights</div>
					<div class="gallery-grid">
						<?php foreach ($featured_albums as $album): ?>
							<a href="<?php echo site_url('gallery/'.$album->slug); ?>">
								<img src="<?php echo media_url($album->cover_image, 'images/placeholders/gallery.svg'); ?>" alt="<?php echo html_escape($album->title); ?>">
							</a>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>