document.addEventListener('DOMContentLoaded', function () {
	var shareButtons = document.querySelectorAll('[data-share-verse]');

	shareButtons.forEach(function (button) {
		button.addEventListener('click', function () {
			var text = button.getAttribute('data-share-verse');
			var title = button.getAttribute('data-share-title') || document.title;

			if (navigator.share) {
				navigator.share({ title: title, text: text, url: window.location.href }).catch(function () {});
				return;
			}

			if (navigator.clipboard) {
				navigator.clipboard.writeText(text + ' ' + window.location.href).then(function () {
					button.textContent = 'Copied';
					window.setTimeout(function () {
						button.textContent = 'Share Verse';
					}, 1800);
				});
			}
		});
	});

	var modalElement = document.getElementById('lightboxModal');

	if (!modalElement || typeof bootstrap === 'undefined') {
		return;
	}

	var modal = new bootstrap.Modal(modalElement);
	var image = modalElement.querySelector('[data-lightbox-image]');
	var caption = modalElement.querySelector('[data-lightbox-caption]');

	document.querySelectorAll('[data-lightbox]').forEach(function (trigger) {
		trigger.addEventListener('click', function (event) {
			event.preventDefault();
			image.src = trigger.getAttribute('href');
			image.alt = trigger.getAttribute('data-caption') || 'Gallery image';
			caption.textContent = trigger.getAttribute('data-caption') || '';
			modal.show();
		});
	});
});