<?php
$title = 'Search';
$lead = 'Search across sermons, events, announcements, and church updates.';
$eyebrow = 'Search Results';
$this->load->view('site/partials/page_header', compact('title', 'lead', 'eyebrow'));
?>

<section class="section-spacing">
	<div class="container">
		<form class="content-card search-form mb-4" method="get">
			<div class="row g-3 align-items-end">
				<div class="col-lg-10">
					<label class="form-label" for="globalSearch">Search the website</label>
					<input id="globalSearch" class="form-control" type="search" name="q" value="<?php echo html_escape($query); ?>" placeholder="Search sermons, events, announcements, and updates">
				</div>
				<div class="col-lg-2 d-grid">
					<button class="btn btn-church" type="submit">Search</button>
				</div>
			</div>
		</form>

		<?php if ($query !== ''): ?>
			<div class="search-summary">
				Showing results for <strong><?php echo html_escape($query); ?></strong>.
			</div>
		<?php endif; ?>

		<div class="row g-4">
			<div class="col-lg-6">
				<div class="content-card h-100">
					<div class="card-overline">Sermons</div>
					<ul class="archive-list mt-3">
						<?php foreach ($results['sermons'] as $item): ?>
							<li><a href="<?php echo site_url('sermons/'.$item->slug); ?>"><?php echo html_escape($item->title); ?></a></li>
						<?php endforeach; ?>
						<?php if (empty($results['sermons'])): ?><li>No sermon results found.</li><?php endif; ?>
					</ul>
				</div>
			</div>
			<div class="col-lg-6">
				<div class="content-card h-100">
					<div class="card-overline">Events</div>
					<ul class="archive-list mt-3">
						<?php foreach ($results['events'] as $item): ?>
							<li><a href="<?php echo site_url('events/'.$item->slug); ?>"><?php echo html_escape($item->title); ?></a></li>
						<?php endforeach; ?>
						<?php if (empty($results['events'])): ?><li>No event results found.</li><?php endif; ?>
					</ul>
				</div>
			</div>
			<div class="col-lg-6">
				<div class="content-card h-100">
					<div class="card-overline">Announcements</div>
					<ul class="archive-list mt-3">
						<?php foreach ($results['announcements'] as $item): ?>
							<li><a href="<?php echo site_url('announcements/'.$item->slug); ?>"><?php echo html_escape($item->title); ?></a></li>
						<?php endforeach; ?>
						<?php if (empty($results['announcements'])): ?><li>No announcement results found.</li><?php endif; ?>
					</ul>
				</div>
			</div>
			<div class="col-lg-6">
				<div class="content-card h-100">
					<div class="card-overline">News & Updates</div>
					<ul class="archive-list mt-3">
						<?php foreach ($results['news_items'] as $item): ?>
							<li><a href="<?php echo site_url('news-updates/'.$item->slug); ?>"><?php echo html_escape($item->title); ?></a></li>
						<?php endforeach; ?>
						<?php if (empty($results['news_items'])): ?><li>No news results found.</li><?php endif; ?>
					</ul>
				</div>
			</div>
		</div>
	</div>
</section>