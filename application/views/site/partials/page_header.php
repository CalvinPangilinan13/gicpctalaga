<section class="page-hero">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-lg-10 text-center">
				<span class="eyebrow"><?php echo isset($eyebrow) ? html_escape($eyebrow) : 'Grace in Christ Presbyterian Church'; ?></span>
				<h1 class="section-title"><?php echo html_escape($title); ?></h1>
				<?php if (!empty($lead)): ?>
					<p class="section-lead mx-auto"><?php echo html_escape($lead); ?></p>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>