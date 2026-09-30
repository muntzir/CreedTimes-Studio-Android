(() => {
	'use strict';

	const root = document.documentElement;
	const storageKey = 'ct-theme';

	function setTheme(theme) {
		root.dataset.theme = theme;
		try { localStorage.setItem(storageKey, theme); } catch (e) {}
	}

	function initTheme() {
		let saved = null;
		try { saved = localStorage.getItem(storageKey); } catch (e) {}
		if (saved === 'dark' || saved === 'light') {
			setTheme(saved);
			return;
		}
		setTheme(window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
	}
	initTheme();

	document.addEventListener('click', (event) => {
		const themeButton = event.target.closest('[data-theme-toggle]');
		if (themeButton) {
			setTheme(root.dataset.theme === 'dark' ? 'light' : 'dark');
			return;
		}

		const menuButton = event.target.closest('[data-mobile-menu-toggle]');
		if (menuButton) {
			const panel = document.querySelector('[data-mobile-panel]');
			if (!panel) return;
			const open = !panel.hidden;
			panel.hidden = open;
			menuButton.setAttribute('aria-expanded', String(!open));
			return;
		}

		const searchToggle = event.target.closest('[data-search-overlay-toggle]');
		if (searchToggle) {
			const overlay = document.querySelector('[data-search-overlay]');
			if (!overlay) return;
			const open = !overlay.hidden;
			overlay.hidden = open;
			document.body.classList.toggle('ct-lock-scroll', !open);
			if (!open) setTimeout(() => overlay.querySelector('input[type="search"]')?.focus(), 40);
			return;
		}

		const shareToggle = event.target.closest('[data-share-toggle]');
		if (shareToggle) {
			const menu = shareToggle.closest('.ct-share-menu')?.querySelector('[data-share-menu]');
			if (menu) menu.hidden = !menu.hidden;
			return;
		}

		const nativeShare = event.target.closest('[data-native-share]');
		if (nativeShare) {
			const shareData = {
				title: nativeShare.dataset.shareTitle || document.title,
				text: nativeShare.dataset.shareTitle || document.title,
				url: window.location.href
			};
			if (navigator.share) {
				navigator.share(shareData).catch(() => {});
			} else {
				navigator.clipboard?.writeText(window.location.href).then(() => {
					const old = nativeShare.textContent;
					nativeShare.textContent = 'Link copied';
					setTimeout(() => { nativeShare.textContent = old; }, 1200);
				});
			}
			return;
		}

		const copy = event.target.closest('[data-copy-link]');
		if (copy) {
			navigator.clipboard?.writeText(window.location.href).then(() => {
				const old = copy.textContent;
				copy.textContent = copy.dataset.copiedLabel || 'Copied';
				setTimeout(() => { copy.textContent = old; }, 1200);
			});
		}
	});

	const progress = document.querySelector('[data-reading-progress]');
	const article = document.querySelector('[data-article]');
	function updateReadingProgress() {
		if (!progress || !article) return;
		const top = article.offsetTop;
		const max = Math.max(1, article.offsetHeight - window.innerHeight * 0.55);
		const ratio = Math.min(1, Math.max(0, (window.scrollY - top + 80) / max));
		progress.style.transform = 'scaleX(' + ratio + ')';
	}
	if (progress && article) {
		updateReadingProgress();
		window.addEventListener('scroll', updateReadingProgress, { passive: true });
		window.addEventListener('resize', updateReadingProgress);
	}

	document.querySelectorAll('[data-horizontal-scroll]').forEach((row) => {
		const parent = row.parentElement;
		const prev = parent?.querySelector('[data-scroll-prev]');
		const next = parent?.querySelector('[data-scroll-next]');
		prev?.addEventListener('click', () => row.scrollBy({ left: -row.clientWidth * 0.75, behavior: 'smooth' }));
		next?.addEventListener('click', () => row.scrollBy({ left: row.clientWidth * 0.75, behavior: 'smooth' }));
	});
})();
