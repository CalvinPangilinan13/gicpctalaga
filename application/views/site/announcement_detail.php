<?php
$title = $announcement->title;
$lead = $announcement->summary;
$eyebrow = 'Announcement';
$this->load->view('site/partials/page_header', compact('title', 'lead', 'eyebrow'));
?>

<section class="section-spacing">
	<div class="container">
		<div class="detail-card">
			<div class="detail-meta">
				<span class="content-badge"><i class="bi bi-calendar2-week"></i><?php echo formatted_date($announcement->publish_date); ?></span>
				<?php if ((int) $announcement->is_featured === 1): ?><span class="content-badge"><i class="bi bi-star-fill"></i>Featured</span><?php endif; ?>
			</div>
			<?php if (!empty($announcement->image)): ?><img class="card-media" src="<?php echo media_url($announcement->image, 'images/placeholders/news.svg'); ?>" alt="<?php echo html_escape($announcement->title); ?>"><?php endif; ?>
			<p class="detail-copy mb-0"><?php echo nl2br(html_escape($announcement->content)); ?></p>
		</div>
	</div>
</section>