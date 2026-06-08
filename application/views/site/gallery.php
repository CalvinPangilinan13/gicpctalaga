<?php
$title = 'Gallery';
$lead = 'Browse albums from worship gatherings, outreach programs, fellowships, and ministry events.';
$eyebrow = 'Photo Gallery';
$this->load->view('site/partials/page_header', compact('title', 'lead', 'eyebrow'));
?>

<section class="section-spacing">
	<div class="container">
		<div class="row g-4">
			<?php foreach ($galleries as $gallery): ?>
				<div class="col-md-6 col-xl-4">
					<div class="content-card h-100">
						<img class="card-media" src="<?php echo media_url($gallery->cover_image, 'images/placeholders/gallery.svg'); ?>" alt="<?php echo html_escape($gallery->title); ?>">
						<div class="detail-meta">
							<span class="content-badge"><i class="bi bi-camera"></i><?php echo html_escape($gallery->category); ?></span>
							<span class="content-badge"><i class="bi bi-calendar2-week"></i><?php echo formatted_date($gallery->album_date); ?></span>
						</div>
						<h2 class="card-title"><?php echo html_escape($gallery->title); ?></h2>
						<p class="card-text"><?php echo excerpt($gallery->description, 145); ?></p>
						<a class="btn btn-outline-church" href="<?php echo site_url('gallery/'.$gallery->slug); ?>">Open Album</a>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>