<?php
$title = $news_item->title;
$lead = $news_item->summary;
$eyebrow = 'Church Update';
$this->load->view('site/partials/page_header', compact('title', 'lead', 'eyebrow'));
?>

<section class="section-spacing">
	<div class="container">
		<div class="detail-card">
			<div class="detail-meta">
				<span class="content-badge"><i class="bi bi-tag"></i><?php echo html_escape($news_item->category); ?></span>
				<span class="content-badge"><i class="bi bi-calendar2"></i><?php echo formatted_date($news_item->publish_date); ?></span>
			</div>
			<img class="card-media" src="<?php echo media_url($news_item->image, 'images/placeholders/news.svg'); ?>" alt="<?php echo html_escape($news_item->title); ?>">
			<p class="detail-copy mb-0"><?php echo nl2br(html_escape($news_item->content)); ?></p>
		</div>
	</div>
</section>