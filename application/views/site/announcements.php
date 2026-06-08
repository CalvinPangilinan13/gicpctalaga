<?php
$title = 'Announcements';
$lead = 'Stay informed with featured congregational reminders, ministry notices, and church-wide communications.';
$eyebrow = 'Announcements';
$this->load->view('site/partials/page_header', compact('title', 'lead', 'eyebrow'));
?>

<section class="section-spacing">
	<div class="container">
		<div class="row g-4">
			<div class="col-lg-8">
				<div class="row g-4">
					<?php foreach ($announcements as $announcement): ?>
						<div class="col-12">
							<div class="content-card h-100">
								<div class="detail-meta">
									<span class="content-badge"><i class="bi bi-calendar2-week"></i><?php echo formatted_date($announcement->publish_date); ?></span>
									<?php if ((int) $announcement->is_featured === 1): ?><span class="content-badge"><i class="bi bi-star-fill"></i>Featured</span><?php endif; ?>
								</div>
								<h2 class="card-title"><?php echo html_escape($announcement->title); ?></h2>
								<p class="card-text"><?php echo excerpt($announcement->summary, 180); ?></p>
								<a class="btn btn-outline-church" href="<?php echo site_url('announcements/'.$announcement->slug); ?>">Read More</a>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
			<div class="col-lg-4">
				<div class="content-card h-100">
					<div class="card-overline">Archives</div>
					<h2 class="card-title">Announcement Archive</h2>
					<ul class="archive-list mt-3">
						<?php foreach ($archives as $label => $count): ?>
							<li class="d-flex justify-content-between align-items-center"><span><?php echo html_escape($label); ?></span><span class="content-badge"><?php echo html_escape($count); ?></span></li>
						<?php endforeach; ?>
					</ul>
				</div>
			</div>
		</div>
	</div>
</section>