<?php
$title = 'News & Updates';
$lead = 'Read recent stories, mission updates, outreach highlights, and church activity recaps from GICP Talaga.';
$eyebrow = 'Newsroom';
$this->load->view('site/partials/page_header', compact('title', 'lead', 'eyebrow'));
?>

<section class="section-spacing">
	<div class="container">
		<form class="content-card search-form mb-4" method="get">
			<div class="row g-3 align-items-end">
				<div class="col-lg-10">
					<label class="form-label" for="newsSearch">Search news and updates</label>
					<input id="newsSearch" class="form-control" type="search" name="q" value="<?php echo html_escape($search_term); ?>" placeholder="Search by category, title, or content">
				</div>
				<div class="col-lg-2 d-grid">
					<button class="btn btn-church" type="submit">Search</button>
				</div>
			</div>
		</form>

		<div class="row g-4">
			<div class="col-lg-5">
				<div class="section-heading">
					<span class="eyebrow">Featured Posts</span>
					<h2 class="section-title">Latest highlights from church life.</h2>
				</div>
				<div class="row g-4">
					<?php foreach ($featured_news as $item): ?>
						<div class="col-12">
							<div class="content-card h-100">
								<div class="card-overline"><?php echo html_escape($item->category); ?></div>
								<h2 class="card-title"><?php echo html_escape($item->title); ?></h2>
								<p class="card-text"><?php echo excerpt($item->summary, 150); ?></p>
								<a class="btn btn-outline-church" href="<?php echo site_url('news-updates/'.$item->slug); ?>">Read Story</a>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
			<div class="col-lg-7">
				<div class="row g-4">
					<?php foreach ($news_items as $item): ?>
						<div class="col-md-6">
							<div class="content-card h-100">
								<img class="card-media" src="<?php echo media_url($item->image, 'images/placeholders/news.svg'); ?>" alt="<?php echo html_escape($item->title); ?>">
								<div class="detail-meta">
									<span class="content-badge"><i class="bi bi-tag"></i><?php echo html_escape($item->category); ?></span>
									<span class="content-badge"><i class="bi bi-calendar2"></i><?php echo formatted_date($item->publish_date); ?></span>
								</div>
								<h2 class="card-title"><?php echo html_escape($item->title); ?></h2>
								<p class="card-text"><?php echo excerpt($item->summary, 125); ?></p>
								<a class="btn btn-outline-church" href="<?php echo site_url('news-updates/'.$item->slug); ?>">Read More</a>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
</section>