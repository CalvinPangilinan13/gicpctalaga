<?php
$title = 'About Us';
$lead = $church_profile->brief_intro;
$eyebrow = 'Grace in Christ Presbyterian Church';
$this->load->view('site/partials/page_header', compact('title', 'lead', 'eyebrow'));
$values = preg_split('/\r\n|\r|\n/', (string) $church_profile->core_values);
?>

<section class="section-spacing">
	<div class="container">
		<div class="row g-4">
			<div class="col-lg-8">
				<div class="content-card h-100">
					<div class="card-overline">Church History</div>
					<h2 class="card-title">How the Lord has guided our church.</h2>
					<p class="card-text mb-0"><?php echo nl2br(html_escape($church_profile->history)); ?></p>
				</div>
			</div>
			<div class="col-lg-4">
				<div class="content-card h-100">
					<div class="card-overline">Core Values</div>
					<h2 class="card-title">What shapes our life together.</h2>
					<ul class="value-list mt-3">
						<?php foreach ($values as $value): ?>
							<?php if (trim($value) !== ''): ?>
								<li><?php echo html_escape(trim($value)); ?></li>
							<?php endif; ?>
						<?php endforeach; ?>
					</ul>
				</div>
			</div>
		</div>

		<div class="row g-4 mt-1">
			<div class="col-md-6">
				<div class="content-card h-100">
					<div class="card-overline">Mission</div>
					<h2 class="card-title">Our Mission</h2>
					<p class="card-text mb-0"><?php echo nl2br(html_escape($church_profile->mission)); ?></p>
				</div>
			</div>
			<div class="col-md-6">
				<div class="content-card h-100">
					<div class="card-overline">Vision</div>
					<h2 class="card-title">Our Vision</h2>
					<p class="card-text mb-0"><?php echo nl2br(html_escape($church_profile->vision)); ?></p>
				</div>
			</div>
		</div>

		<div class="row g-4 mt-1">
			<div class="col-12">
				<div class="content-card">
					<div class="card-overline">Statement of Faith</div>
					<h2 class="card-title">Confessing Christ with Biblical conviction.</h2>
					<p class="card-text mb-0"><?php echo nl2br(html_escape($church_profile->statement_of_faith)); ?></p>
				</div>
			</div>
		</div>
	</div>
</section>