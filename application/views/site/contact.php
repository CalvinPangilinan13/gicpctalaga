<?php
$title = 'Contact';
$lead = 'Get in touch with GICP Talaga, send a prayer request, or plan your first Sunday visit.';
$eyebrow = 'Contact Us';
$this->load->view('site/partials/page_header', compact('title', 'lead', 'eyebrow'));
?>

<section class="section-spacing">
	<div class="container">
		<?php $this->load->view('site/partials/flash'); ?>
		<div class="row g-4">
			<div class="col-lg-6">
				<div class="contact-card h-100">
					<div class="card-overline">Church Address</div>
					<h2 class="card-title">We would love to welcome you.</h2>
					<ul class="detail-list mb-4">
						<li><strong>Address:</strong> <?php echo html_escape($church_profile->address); ?></li>
						<li><strong>Email:</strong> <a href="mailto:<?php echo html_escape($church_profile->email); ?>"><?php echo html_escape($church_profile->email); ?></a></li>
						<li><strong>Mobile Number:</strong> <a href="tel:<?php echo html_escape($church_profile->mobile_number); ?>"><?php echo html_escape($church_profile->mobile_number); ?></a></li>
						<li><strong>Service Schedule:</strong> <?php echo !empty($service_schedules) ? html_escape($service_schedules[0]->title.' - '.$service_schedules[0]->day_name.' '.$service_schedules[0]->time_range) : 'Please contact the church office.'; ?></li>
					</ul>
					<div class="ratio ratio-4x3 rounded-4 overflow-hidden"><?php echo $church_profile->google_map_embed; ?></div>
				</div>
			</div>
			<div class="col-lg-6">
				<div class="contact-card mb-4">
					<div class="card-overline">Send a Message</div>
					<h2 class="card-title">Contact Form</h2>
					<form method="post" action="<?php echo site_url('contact'); ?>">
						<?php echo csrf_input(); ?>
						<div class="row g-3">
							<div class="col-md-6">
								<label class="form-label" for="contactName">Full Name</label>
								<input id="contactName" class="form-control" type="text" name="full_name" required>
							</div>
							<div class="col-md-6">
								<label class="form-label" for="contactEmail">Email</label>
								<input id="contactEmail" class="form-control" type="email" name="email" required>
							</div>
							<div class="col-md-6">
								<label class="form-label" for="contactMobile">Mobile Number</label>
								<input id="contactMobile" class="form-control" type="text" name="mobile_number">
							</div>
							<div class="col-md-6">
								<label class="form-label" for="contactSubject">Subject</label>
								<input id="contactSubject" class="form-control" type="text" name="subject" required>
							</div>
							<div class="col-12">
								<label class="form-label" for="contactMessage">Message</label>
								<textarea id="contactMessage" class="form-control" name="message" rows="5" required></textarea>
							</div>
							<div class="col-12">
								<button class="btn btn-church" type="submit">Send Message</button>
							</div>
						</div>
					</form>
				</div>

				<div class="contact-card" id="prayer-request">
					<div class="card-overline">Prayer Request</div>
					<h2 class="card-title">Let us pray for you.</h2>
					<form method="post" action="<?php echo site_url('prayer-request'); ?>">
						<?php echo csrf_input(); ?>
						<input type="hidden" name="redirect_url" value="<?php echo current_url(); ?>#prayer-request">
						<div class="row g-3">
							<div class="col-md-6">
								<label class="form-label" for="prayerName">Full Name</label>
								<input id="prayerName" class="form-control" type="text" name="full_name" required>
							</div>
							<div class="col-md-6">
								<label class="form-label" for="prayerEmail">Email</label>
								<input id="prayerEmail" class="form-control" type="email" name="email" required>
							</div>
							<div class="col-md-6">
								<label class="form-label" for="prayerMobile">Mobile Number</label>
								<input id="prayerMobile" class="form-control" type="text" name="mobile_number">
							</div>
							<div class="col-md-6 d-flex align-items-end">
								<div class="form-check">
									<input id="prayerPrivate" class="form-check-input" type="checkbox" name="is_private" value="1" checked>
									<label class="form-check-label" for="prayerPrivate">Keep my request private</label>
								</div>
							</div>
							<div class="col-12">
								<label class="form-label" for="prayerText">Prayer Request</label>
								<textarea id="prayerText" class="form-control" name="request_text" rows="5" required></textarea>
							</div>
							<div class="col-12">
								<button class="btn btn-church" type="submit">Submit Prayer Request</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</section>