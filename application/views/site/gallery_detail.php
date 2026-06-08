<?php
$title = $gallery->title;
$lead = $gallery->description;
$eyebrow = 'Album';
$this->load->view('site/partials/page_header', compact('title', 'lead', 'eyebrow'));
?>

<section class="section-spacing">
	<div class="container">
		<div class="detail-card mb-4">
			<div class="detail-meta">
				<span class="content-badge"><i class="bi bi-images"></i><?php echo html_escape($gallery->category); ?></span>
				<?php if (!empty($gallery->album_date)): ?><span class="content-badge"><i class="bi bi-calendar2-week"></i><?php echo formatted_date($gallery->album_date); ?></span><?php endif; ?>
			</div>
			<p class="detail-copy mb-0"><?php echo nl2br(html_escape($gallery->description)); ?></p>
		</div>

		<div class="gallery-grid">
			<?php foreach ($images as $image): ?>
				<a href="<?php echo media_url($image->image_path, 'images/placeholders/gallery.svg'); ?>" data-lightbox data-caption="<?php echo html_escape($image->caption); ?>">
					<img src="<?php echo media_url($image->image_path, 'images/placeholders/gallery.svg'); ?>" alt="<?php echo html_escape($image->caption); ?>">
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>