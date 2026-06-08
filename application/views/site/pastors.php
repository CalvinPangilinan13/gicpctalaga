<?php
$title = 'Pastors & Leaders';
$lead = 'Meet the pastors, elders, and church leaders serving GICP Talaga with faithful shepherding and Gospel care.';
$eyebrow = 'Church Leadership';
$this->load->view('site/partials/page_header', compact('title', 'lead', 'eyebrow'));
?>

<section class="section-spacing">
	<div class="container">
		<div class="row g-4">
			<?php foreach ($pastors as $pastor): ?>
				<div class="col-md-6 col-xl-3">
					<div class="content-card h-100">
						<img class="card-media" src="<?php echo media_url($pastor->photo, 'images/placeholders/pastor.svg'); ?>" alt="<?php echo html_escape($pastor->name); ?>">
						<div class="card-overline"><?php echo html_escape($pastor->position); ?></div>
						<h2 class="card-title"><?php echo html_escape($pastor->name); ?></h2>
						<p class="card-text"><?php echo nl2br(html_escape($pastor->bio)); ?></p>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>