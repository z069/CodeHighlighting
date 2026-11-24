/* template-code.js —— 代码块交互（复制 / 折叠 / 名片展开 / 跟随主题配色） */
(function(){if(window.__scrCbBound){return;}window.__scrCbBound=1;var blockText=function blockText(block) {
		var view = block.querySelector('[data-hl-view]');
		if (!view) { return ''; }
		var rows = view.querySelectorAll('.scr-r');
		var out = [];
		for (var i = 0; i < rows.length; i++) {
			var cell = rows[i].querySelector('.scr-c');
			out.push(cell ? cell.textContent : '');
		}
		return out.join('\n');
	};var codeBlockClick=function codeBlockClick(event) {
		var node = event.target;
		var btn = null;
		while (node && node.nodeType === 1) {
			if (node.getAttribute && node.getAttribute('data-act')) { btn = node; break; }
			node = node.parentNode;
		}
		if (!btn) { return; }

		var block = btn;
		while (block && block.nodeType === 1 && !(block.getAttribute && block.getAttribute('data-cb'))) {
			block = block.parentNode;
		}
		if (!block) { return; }

		var act = btn.getAttribute('data-act');

		/* 名片：只翻转状态属性，显隐全交给样式库 */
		if (act === 'card') {
			if (block.getAttribute('data-card') !== '1') { return; }
			block.setAttribute('data-card', '0');
			return;
		}

		/* 折叠：同样只改状态属性，高度由 .scr-cb[data-folded] 决定 */
		if (act === 'fold') {
			var folded = block.getAttribute('data-folded') === '1';
			block.setAttribute('data-folded', folded ? '0' : '1');
			btn.setAttribute('aria-expanded', folded ? 'true' : 'false');
			var label = btn.querySelector('[data-label]');
			if (label) { label.textContent = folded ? '收起' : '展开全部'; }
			return;
		}

		if (act === 'copy') {
			var text = blockText(block);
			if (!text) { return; }
			var ta = document.createElement('textarea');
			ta.value = text;
			ta.setAttribute('readonly', 'readonly');
			ta.style.position = 'fixed';
			ta.style.top = '-1000px';
			ta.style.opacity = '0';
			document.body.appendChild(ta);
			ta.select();
			var ok = false;
			try { ok = document.execCommand('copy'); } catch (e) { ok = false; }
			document.body.removeChild(ta);
			if (!ok && navigator.clipboard && navigator.clipboard.writeText) {
				navigator.clipboard.writeText(text);
			}
			/* 复制成功时图标短暂变绿，气泡同步显示「已复制」 */
			btn.setAttribute('data-done', '1');
			btn.setAttribute('data-tip', '已复制');
			window.setTimeout(function () {
				btn.removeAttribute('data-done');
				btn.setAttribute('data-tip', '复制代码');
			}, 1500);
		}
	};document.addEventListener("click",codeBlockClick);var pageIsDark=function pageIsDark() {
		var el = document.body, bg = '', guard = 0;
		while (el && guard < 8) {
			bg = window.getComputedStyle(el).backgroundColor;
			if (bg && bg !== 'transparent' && bg !== 'rgba(0, 0, 0, 0)') { break; }
			el = el.parentElement;
			guard++;
		}
		var m = /rgba?\(([^)]+)\)/.exec(bg || '');
		if (!m) { return false; }
		var p = m[1].split(',');
		if (p.length > 3 && parseFloat(p[3]) < 0.2) { return false; }
		var lum = 0.2126 * parseFloat(p[0]) + 0.7152 * parseFloat(p[1]) + 0.0722 * parseFloat(p[2]);
		return lum < 140;
	};var applySchemeIn=function applySchemeIn(scope) {
		var root = scope || document;
		var els = root.querySelectorAll('.scr-cb[data-scheme-mode="theme"]');
		if (!els.length) { return; }
		var dark = pageIsDark(), i;
		for (i = 0; i < els.length; i++) {
			els[i].setAttribute('data-scheme', dark ? 'dark' : 'light');
		}
	};var watchScheme=function watchScheme() {
		if (window.__scrSchemeOn) { return; }
		window.__scrSchemeOn = 1;
		applySchemeIn();
		window.addEventListener('load', function () {
			window.setTimeout(function () { applySchemeIn(); }, 300);
		});
		if (window.MutationObserver && document.body) {
			new MutationObserver(function () { applySchemeIn(); })
				.observe(document.body, { attributes: true, attributeFilter: ['class', 'data-theme', 'data-mode'] });
		}
	};watchScheme();})();
