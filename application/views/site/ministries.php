<?php
$title = 'Ministries';
$lead = 'Every ministry exists to help people worship Christ, grow in discipleship, and serve one another in grace.';
$eyebrow = 'Ministry Life';
$this->load->view('site/partials/page_header', compact('title', 'lead', 'eyebrow'));
?>

<section class="section-spacing">
	<div class="container">
		<div class="row g-4">
			<?php foreach ($ministries as $ministry): ?>
				<div class="col-md-6 col-xl-4">
					<div class="content-card h-100">
						<img class="card-media" src="<?php echo media_url($ministry->image, 'images/placeholders/ministry.svg'); ?>" alt="<?php echo html_escape($ministry->name); ?>">
						<div class="card-overline"><?php echo html_escape($ministry->meeting_schedule); ?></div>
						<h2 class="card-title"><?php echo html_escape($ministry->name); ?></h2>
						<p class="card-text"><?php echo nl2br(html_escape($ministry->description)); ?></p>
						<?php if (!empty($ministry->leader_name)): ?>
							<p class="mb-0 text-muted-strong"><strong>Leader:</strong> <?php echo html_escape($ministry->leader_name); ?></p>
						<?php endif; ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>