<?php
$title = $sermon->title;
$lead = $sermon->excerpt;
$eyebrow = 'Sermon Detail';
$this->load->view('site/partials/page_header', compact('title', 'lead', 'eyebrow'));
?>

<section class="section-spacing">
	<div class="container">
		<div class="row g-4">
			<div class="col-lg-8">
				<div class="detail-card h-100">
					<div class="detail-meta">
						<span class="content-badge"><i class="bi bi-person"></i><?php echo html_escape($sermon->speaker); ?></span>
						<span class="content-badge"><i class="bi bi-calendar-event"></i><?php echo formatted_date($sermon->sermon_date); ?></span>
					</div>
					<img class="card-media" src="<?php echo media_url($sermon->featured_image, 'images/placeholders/sermon.svg'); ?>" alt="<?php echo html_escape($sermon->title); ?>">
					<p class="detail-copy"><?php echo nl2br(html_escape($sermon->content)); ?></p>
					<?php if (!empty($sermon->youtube_url)): ?>
						<div class="ratio ratio-16x9 mt-4">
							<iframe src="<?php echo str_replace('watch?v=', 'embed/', html_escape($sermon->youtube_url)); ?>" title="<?php echo html_escape($sermon->title); ?>" allowfullscreen loading="lazy"></iframe>
						</div>
					<?php endif; ?>
				</div>
			</div>
			<div class="col-lg-4">
				<div class="detail-card mb-4">
					<h2 class="h4 mb-3">Resources</h2>
					<ul class="detail-list">
						<?php foreach ($sermon_files as $file): ?>
							<?php if (file_exists(FCPATH.$file->file_path)): ?>
								<li><a href="<?php echo base_url($file->file_path); ?>" target="_blank" rel="noreferrer"><?php echo html_escape(ucfirst($file->file_type)); ?> Download</a></li>
							<?php endif; ?>
						<?php endforeach; ?>
						<?php if (empty($sermon_files)): ?>
							<li>No downloadable files uploaded for this sermon yet.</li>
						<?php endif; ?>
					</ul>
				</div>
				<div class="detail-card">
					<h2 class="h4 mb-3">Explore More</h2>
					<p class="mb-3">Browse the full sermon archive, search messages by speaker, or return to the main sermon library.</p>
					<a class="btn btn-church w-100" href="<?php echo site_url('sermons'); ?>">Back to Sermons</a>
				</div>
			</div>
		</div>
	</div>
</section>