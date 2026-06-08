<?php
$title = 'Sermons';
$lead = 'Search, listen, and revisit biblical preaching from the pulpit ministry of GICP Talaga.';
$eyebrow = 'Sermon Library';
$this->load->view('site/partials/page_header', compact('title', 'lead', 'eyebrow'));
?>

<section class="section-spacing">
	<div class="container">
		<form class="content-card search-form mb-4" method="get">
			<div class="row g-3 align-items-end">
				<div class="col-lg-10">
					<label class="form-label" for="sermonSearch">Search sermons</label>
					<input id="sermonSearch" class="form-control" type="search" name="q" value="<?php echo html_escape($search_term); ?>" placeholder="Search by title, speaker, or content">
				</div>
				<div class="col-lg-2 d-grid">
					<button class="btn btn-church" type="submit">Search</button>
				</div>
			</div>
		</form>

		<div class="row g-4">
			<?php foreach ($sermons as $sermon): ?>
				<div class="col-md-6 col-xl-4">
					<div class="content-card h-100">
						<img class="card-media" src="<?php echo media_url($sermon->featured_image, 'images/placeholders/sermon.svg'); ?>" alt="<?php echo html_escape($sermon->title); ?>">
						<div class="detail-meta">
							<span class="content-badge"><i class="bi bi-mic"></i><?php echo html_escape($sermon->speaker); ?></span>
							<span class="content-badge"><i class="bi bi-calendar2"></i><?php echo formatted_date($sermon->sermon_date); ?></span>
						</div>
						<h2 class="card-title"><?php echo html_escape($sermon->title); ?></h2>
						<p class="card-text"><?php echo excerpt($sermon->excerpt, 150); ?></p>
						<a class="btn btn-outline-church" href="<?php echo site_url('sermons/'.$sermon->slug); ?>">Watch or Download</a>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>