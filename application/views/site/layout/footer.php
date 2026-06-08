</main>

<footer class="site-footer">
	<div class="container">
		<div class="row g-4 align-items-start">
			<div class="col-lg-4">
				<div class="d-flex align-items-center gap-3 mb-3">
					<img class="brand-mark" src="<?php echo media_url($church_profile->logo, 'images/logo.svg'); ?>" alt="<?php echo html_escape($church_profile->short_name); ?> logo">
					<div>
						<h2 class="footer-title mb-1"><?php echo html_escape($church_profile->short_name); ?></h2>
						<p class="mb-0"><?php echo html_escape($church_profile->tagline); ?></p>
					</div>
				</div>
				<p><?php echo html_escape($church_profile->brief_intro); ?></p>
				<p class="mb-0"><strong>Address:</strong> <?php echo html_escape($church_profile->address); ?></p>
				<p class="mb-0"><strong>Email:</strong> <a href="mailto:<?php echo html_escape($church_profile->email); ?>"><?php echo html_escape($church_profile->email); ?></a></p>
				<p><strong>Mobile:</strong> <a href="tel:<?php echo html_escape($church_profile->mobile_number); ?>"><?php echo html_escape($church_profile->mobile_number); ?></a></p>
			</div>
			<div class="col-lg-4">
				<h3 class="h5 text-white mb-3">Worship & Community</h3>
				<ul class="schedule-list">
					<?php foreach ($service_schedules as $schedule): ?>
						<li>
							<strong><?php echo html_escape($schedule->title); ?></strong><br>
							<span><?php echo html_escape($schedule->day_name.' | '.$schedule->time_range); ?></span><br>
							<small><?php echo html_escape($schedule->description); ?></small>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
			<div class="col-lg-4">
				<h3 class="h5 text-white mb-3">Stay Connected</h3>
				<div class="footer-newsletter mb-3">
					<form action="<?php echo site_url('newsletter-subscribe'); ?>" method="post" class="row g-2">
						<?php echo csrf_input(); ?>
						<input type="hidden" name="redirect_url" value="<?php echo current_url(); ?>">
						<div class="col-12">
							<label class="form-label visually-hidden" for="footerEmail">Email</label>
							<input id="footerEmail" class="form-control" type="email" name="email" placeholder="Subscribe to church updates" required>
						</div>
						<div class="col-12">
							<button class="btn btn-outline-light w-100" type="submit">Subscribe</button>
						</div>
					</form>
				</div>
				<div class="d-flex flex-wrap gap-2 mb-3">
					<?php if (!empty($church_profile->facebook_url)): ?><a class="social-chip" href="<?php echo html_escape($church_profile->facebook_url); ?>" target="_blank" rel="noreferrer" aria-label="Facebook"><i class="bi bi-facebook"></i></a><?php endif; ?>
					<?php if (!empty($church_profile->youtube_url)): ?><a class="social-chip" href="<?php echo html_escape($church_profile->youtube_url); ?>" target="_blank" rel="noreferrer" aria-label="YouTube"><i class="bi bi-youtube"></i></a><?php endif; ?>
					<?php if (!empty($church_profile->instagram_url)): ?><a class="social-chip" href="<?php echo html_escape($church_profile->instagram_url); ?>" target="_blank" rel="noreferrer" aria-label="Instagram"><i class="bi bi-instagram"></i></a><?php endif; ?>
				</div>
				<p class="mb-1"><?php echo html_escape(setting_value($settings, 'footer_text', 'Grace in Christ Presbyterian Church - Talaga.')); ?></p>
				<p class="mb-0 small"><?php echo date('Y'); ?>. Crafted for ministry, worship, and witness.</p>
			</div>
		</div>
	</div>
</footer>

<div class="modal fade lightbox-modal" id="lightboxModal" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-xl modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-body text-center">
				<button class="btn btn-light rounded-pill mb-3" type="button" data-bs-dismiss="modal">Close</button>
				<img src="" alt="" data-lightbox-image>
				<p class="text-white mt-3 mb-0" data-lightbox-caption></p>
			</div>
		</div>
	</div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="<?php echo asset_url('js/site.js'); ?>"></script>
</body>
</html>