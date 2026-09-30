(() => {
	'use strict';
	if (!window.ctCore) return;

	function post(action, data = {}) {
		const body = new URLSearchParams({ action, nonce: ctCore.nonce, ...data });
		return fetch(ctCore.ajaxUrl, {
			method: 'POST',
			headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
			credentials: 'same-origin',
			body
		}).then(r => r.json());
	}

	document.addEventListener('click', async (event) => {
		const save = event.target.closest('[data-ct-save]');
		if (save) {
			if (!ctCore.loggedIn) { window.location.href = '/wp-login.php?redirect_to=' + encodeURIComponent(window.location.href); return; }
			save.disabled = true;
			try {
				const result = await post('ct_toggle_bookmark', { postId: save.dataset.postId });
				if (result.success) {
					save.classList.toggle('is-saved', result.data.saved);
					save.setAttribute('aria-pressed', result.data.saved ? 'true' : 'false');
					const label = save.querySelector('[data-save-label]');
					if (label) label.textContent = result.data.saved ? 'Saved' : 'Save';
				}
			} finally { save.disabled = false; }
			return;
		}

		const noteSave = event.target.closest('[data-ct-note-save]');
		if (noteSave) {
			const wrap = noteSave.closest('[data-ct-note-wrap]');
			const textarea = wrap?.querySelector('[data-ct-note]');
			const status = wrap?.querySelector('[data-ct-note-status]');
			if (!wrap || !textarea) return;
			noteSave.disabled = true;
			if (status) status.textContent = 'Saving…';
			try {
				const result = await post('ct_save_note', { postId: wrap.dataset.postId, note: textarea.value });
				if (status) status.textContent = result.success ? 'Saved' : 'Could not save';
			} finally { noteSave.disabled = false; }
			return;
		}

		const follow = event.target.closest('[data-ct-follow-author]');
		if (follow) {
			if (!ctCore.loggedIn) { window.location.href = '/wp-login.php?redirect_to=' + encodeURIComponent(window.location.href); return; }
			follow.disabled = true;
			try {
				const result = await post('ct_toggle_author_follow', { authorId: follow.dataset.authorId });
				if (result.success) {
					follow.setAttribute('aria-pressed', result.data.following ? 'true' : 'false');
					const label = follow.querySelector('[data-follow-label]');
					if (label) label.textContent = result.data.following ? 'Following' : 'Follow Author';
				}
			} finally { follow.disabled = false; }
		}
	});

	if (ctCore.loggedIn && ctCore.postId && document.body.classList.contains('single')) {
		setTimeout(() => post('ct_log_history', { postId: ctCore.postId }).catch(() => {}), 2500);
	}
})();
