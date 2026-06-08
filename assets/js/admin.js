document.addEventListener('DOMContentLoaded', function () {
	var body = document.body;
	var toggle = document.querySelector('[data-sidebar-toggle]');
	var dismissButtons = document.querySelectorAll('[data-sidebar-dismiss]');
	var navSections = document.querySelectorAll('[data-nav-section]');
	var navItems = document.querySelectorAll('[data-nav-item]');
	var searchInput = document.querySelector('[data-sidebar-search]');
	var clearSearch = document.querySelector('[data-sidebar-search-clear]');
	var emptyState = document.querySelector('[data-sidebar-empty]');

	function setSectionCollapsed(section, collapsed) {
		var toggleButton = section.querySelector('[data-nav-section-toggle]');

		section.classList.toggle('is-collapsed', collapsed);

		if (toggleButton) {
			toggleButton.setAttribute('aria-expanded', collapsed ? 'false' : 'true');
		}
	}

	function closeSidebar() {
		body.classList.remove('sidebar-open');
	}

	function filterNavigation() {
		if (!searchInput) {
			return;
		}

		var query = searchInput.value.trim().toLowerCase();
		var hasVisibleItems = false;

		navSections.forEach(function (section) {
			var sectionItems = section.querySelectorAll('[data-nav-item]');
			var visibleCount = 0;
			var totalCount = sectionItems.length;

			sectionItems.forEach(function (item) {
				var label = (item.getAttribute('data-nav-label') || '').toLowerCase();
				var matches = query === '' || label.indexOf(query) !== -1;

				item.hidden = !matches;

				if (matches) {
					visibleCount++;
					hasVisibleItems = true;
				}
			});

			section.hidden = visibleCount === 0;

			if (query !== '' && visibleCount > 0) {
				setSectionCollapsed(section, false);
			} else if (query === '') {
				setSectionCollapsed(section, section.getAttribute('data-collapsed') === '1');
			}

			var count = section.querySelector('.admin-nav-section-count');

			if (count) {
				count.textContent = query === '' ? totalCount : visibleCount;
			}
		});

		if (clearSearch) {
			clearSearch.hidden = query === '';
		}

		if (emptyState) {
			emptyState.hidden = hasVisibleItems;
		}
	}

	navSections.forEach(function (section) {
		section.setAttribute('data-collapsed', section.classList.contains('is-collapsed') ? '1' : '0');

		var toggleButton = section.querySelector('[data-nav-section-toggle]');

		if (toggleButton) {
			toggleButton.addEventListener('click', function () {
				var collapsed = section.getAttribute('data-collapsed') !== '1';

				section.setAttribute('data-collapsed', collapsed ? '1' : '0');
				setSectionCollapsed(section, collapsed);
			});
		}
	});

	if (toggle) {
		toggle.addEventListener('click', function () {
			body.classList.toggle('sidebar-open');
		});
	}

	dismissButtons.forEach(function (button) {
		button.addEventListener('click', closeSidebar);
	});

	document.addEventListener('keydown', function (event) {
		if (event.key === 'Escape') {
			closeSidebar();
		}
	});

	navItems.forEach(function (item) {
		item.addEventListener('click', function () {
			if (window.innerWidth < 992) {
				closeSidebar();
			}
		});
	});

	if (searchInput) {
		searchInput.addEventListener('input', filterNavigation);
	}

	if (clearSearch && searchInput) {
		clearSearch.addEventListener('click', function () {
			searchInput.value = '';
			searchInput.focus();
			filterNavigation();
		});
	}

	filterNavigation();
});