<?php
/*
Template Name: 代码高亮
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* 代码块样式库 template-code.css 与交互脚本 template-code.js 托管在 GitHub，
   通过 JSDMirror（jsDelivr 镜像，域名 cdn.jsdmirror.com）以 tag 版本号对外引用。
   地址结构：https://cdn.jsdmirror.com/gh/<用户名>/<仓库>@<tag>/<文件名>
   以后更新库文件时：先 push 到仓库，再打一个新 tag，然后只改下面的 $scr_lib_tag ——
   链接结构保持不变，不用改文章里已经粘贴的 HTML。 */
$scr_lib_repo = 'z069/CodeHighlighting';   /* ← 改成你的 GitHub「用户名/仓库名」 */
$scr_lib_tag  = 'v1.0.0';               /* ← 每次更新库后改这里的版本号 */
$scr_lib_base = 'https://cdn.jsdmirror.com/gh/' . $scr_lib_repo . '@' . $scr_lib_tag;
?>
<?php get_header(); ?>

<style type="text/css">
#primary { width: 100%; }

#scr-app {
	--scr-border: #e5e8ee;
	--scr-soft: #f5f7fb;
	--scr-text: #2b2f36;
	--scr-muted: #9398a3;
	--scr-primary: #4078f2;
	margin: 6px 0 26px;
	color: var(--scr-text);
	font-size: 14px;
	line-height: 1.6;
}

#scr-app .scr-lead {
	margin: 0 0 18px;
	padding: 14px 18px;
	border-left: 3px solid var(--scr-primary);
	border-radius: 0 10px 10px 0;
	background: var(--scr-soft);
	color: #5a6270;
	font-size: 13px;
	line-height: 1.75;
}

#scr-app .scr-panel {
	margin: 0 0 16px;
	padding: 18px 20px 20px;
	border: 1px solid var(--scr-border);
	border-radius: 14px;
	background: #fff;
	box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04), 0 12px 32px rgba(15, 23, 42, 0.06);
}

#scr-app .scr-panel h3 {
	display: flex;
	align-items: center;
	gap: 8px;
	margin: 0 0 12px;
	padding: 0;
	border: 0;
	color: #1f2329;
	font-size: 15px;
	font-weight: 700;
	line-height: 1.4;
}

#scr-app .scr-panel h3::before {
	display: inline-block;
	width: 3px;
	height: 16px;
	border-radius: 2px;
	background: linear-gradient(180deg, #5b8cff 0%, #4078f2 100%);
	content: "";
}

#scr-app .scr-hint {
	margin-left: auto;
	color: var(--scr-muted);
	font-size: 12px;
	font-weight: 400;
}

#scr-app textarea,
#scr-app input[type="text"],
#scr-app input[type="number"],
#scr-app select {
	box-sizing: border-box;
	border: 1px solid #dbe0e8;
	border-radius: 8px;
	background: #fcfdff;
	color: #24292f;
	font-size: 13px;
	outline: none;
	transition: border-color 0.16s ease, box-shadow 0.16s ease, background-color 0.16s ease;
}

#scr-app textarea {
	width: 100%;
	padding: 12px 14px;
	background: #fcfdff;
	font-family: ui-monospace, SFMono-Regular, "SF Mono", Menlo, Consolas, "Liberation Mono", monospace;
	line-height: 1.65;
	resize: vertical;
	tab-size: 4;
	-moz-tab-size: 4;
}

#scr-app input[type="text"],
#scr-app input[type="number"],
#scr-app select {
	height: 34px;
	padding: 0 10px;
}

#scr-app input[type="number"] { width: 88px; }

#scr-app input[type="text"] { width: 232px; }

#scr-app textarea:focus,
#scr-app input:focus,
#scr-app select:focus {
	border-color: var(--scr-primary);
	background: #fff;
	box-shadow: 0 0 0 3px rgba(64, 120, 242, 0.14);
}

#scr-app .scr-settings {
	display: flex;
	flex-wrap: wrap;
	align-items: flex-end;
	gap: 12px 20px;
	margin-bottom: 14px;
}

#scr-app .scr-field {
	display: flex;
	flex-direction: column;
	gap: 5px;
}

#scr-app .scr-field > span {
	color: #5c6670;
	font-size: 12px;
	font-weight: 600;
}

#scr-app .scr-check {
	display: inline-flex;
	align-items: center;
	gap: 6px;
	height: 34px;
	color: #3c434a;
	font-size: 13px;
	cursor: pointer;
	-webkit-user-select: none;
	user-select: none;
}

#scr-app .scr-check input {
	-webkit-appearance: none;
	appearance: none;
	margin: 0;
	flex: 0 0 15px;
	width: 15px;
	height: 15px;
	border: 1.5px solid #c3cad6;
	border-radius: 4px;
	background: #fff center/11px 11px no-repeat;
	cursor: pointer;
	transition: background-color .16s ease, border-color .16s ease;
}

#scr-app .scr-check input:hover { border-color: #4078f2; }

#scr-app .scr-check input:checked {
	border-color: #4078f2;
	background-color: #4078f2;
	background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12'%3E%3Cpath d='M2.3 6.2l2.3 2.3 5-5.2' fill='none' stroke='%23fff' stroke-width='1.9' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
}

#scr-app .scr-check input:focus-visible { outline: 2px solid rgba(64,120,242,.35); outline-offset: 2px; }

#scr-app .scr-settings-sub {
	align-items: flex-end;
	gap: 10px 14px;
	margin-top: -2px;
	padding-top: 12px;
	border-top: 1px dashed #e2e6ee;
}

#scr-app .scr-sub-t {
	flex: 0 0 100%;
	font-size: 12px;
	font-weight: 600;
	letter-spacing: .2px;
	color: #5b6472;
	margin-bottom: 2px;
}

#scr-app .scr-css-hint {
	flex: 1 1 260px;
	min-width: 220px;
	align-self: center;
	line-height: 1.6;
}

#scr-app .scr-actions {
	display: flex;
	flex-wrap: wrap;
	align-items: center;
	gap: 10px;
}

#scr-app .scr-btn {
	height: 36px;
	padding: 0 16px;
	border: 1px solid #dbe0e8;
	border-radius: 8px;
	background: #fff;
	color: #3c434a;
	font-size: 13px;
	font-weight: 500;
	line-height: 34px;
	cursor: pointer;
	transition: background-color 0.18s ease, border-color 0.18s ease, color 0.18s ease,
		box-shadow 0.18s ease, transform 0.12s ease;
}

#scr-app .scr-btn:hover {
	border-color: var(--scr-primary);
	color: var(--scr-primary);
	box-shadow: 0 4px 14px rgba(64, 120, 242, 0.12);
}

#scr-app .scr-btn:active { transform: translateY(1px); }

#scr-app .scr-btn-primary {
	border-color: var(--scr-primary);
	background: var(--scr-primary);
	color: #fff;
	box-shadow: 0 4px 14px rgba(64, 120, 242, 0.28);
}

#scr-app .scr-btn-primary:hover {
	background: #2f63d8;
	border-color: #2f63d8;
	color: #fff;
	box-shadow: 0 6px 18px rgba(64, 120, 242, 0.34);
}

#scr-app .scr-btn-ghost {
	border-color: transparent;
	background: transparent;
	color: var(--scr-muted);
}

#scr-app .scr-status {
	min-height: 1em;
	color: var(--scr-primary);
	font-size: 12px;
}

#scr-app .scr-output {
	background: var(--scr-soft);
	color: #3c434a;
}

#scr-app .scr-preview {
	min-height: 90px;
	padding: 2px 0;
}

#scr-app .scr-empty {
	margin: 0;
	padding: 34px 0;
	color: var(--scr-muted);
	font-size: 13px;
	text-align: center;
}

#scr-app .scr-foot {
	margin: 14px 0 0;
	color: var(--scr-muted);
	font-size: 12px;
	line-height: 1.7;
}

#scr-app .scr-foot code {
	padding: 1px 5px;
	border-radius: 3px;
	background: var(--scr-soft);
	color: #5c6670;
	font-size: 12px;
}

body.night #scr-app,
body.dark #scr-app {
	--scr-border: #383838;
	--scr-soft: #2b2b2b;
	--scr-text: #c9c9c9;
	--scr-muted: #8a8a8a;
}

body.night #scr-app .scr-panel,
body.dark #scr-app .scr-panel { background: #232323; }

body.night #scr-app .scr-panel h3,
body.dark #scr-app .scr-panel h3 { color: #ededed; }

body.night #scr-app .scr-lead,
body.dark #scr-app .scr-lead {
	background: var(--scr-soft);
	color: #b4b9c2;
}

body.night #scr-app .scr-field > span,
body.night #scr-app .scr-check,
body.dark #scr-app .scr-field > span,
body.dark #scr-app .scr-check { color: #b6b6b6; }

body.night #scr-app .scr-check input,
body.dark #scr-app .scr-check input {
	border-color: #454d5c;
	background-color: #1b2028;
}

body.night #scr-app .scr-check input:hover,
body.dark #scr-app .scr-check input:hover { border-color: #5b8cff; }

body.night #scr-app .scr-check input:checked,
body.dark #scr-app .scr-check input:checked {
	border-color: #4078f2;
	background-color: #4078f2;
	background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12'%3E%3Cpath d='M2.3 6.2l2.3 2.3 5-5.2' fill='none' stroke='%23fff' stroke-width='1.9' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
}

body.night #scr-app .scr-btn:hover,
body.dark #scr-app .scr-btn:hover { color: #5b8cff; }

body.night #scr-app .scr-btn.scr-btn-primary,
body.dark #scr-app .scr-btn.scr-btn-primary {
	border-color: #4078f2;
	background: #4078f2;
	color: #fff;
}

body.night #scr-app .scr-btn.scr-btn-ghost,
body.dark #scr-app .scr-btn.scr-btn-ghost { color: #9a9a9a; }

body.night #scr-app textarea,
body.night #scr-app input,
body.night #scr-app select,
body.dark #scr-app textarea,
body.dark #scr-app input,
body.dark #scr-app select {
	border-color: #3d3d3d;
	background: #2b2b2b;
	color: #dedede;
}

body.night #scr-app .scr-btn,
body.dark #scr-app .scr-btn {
	border-color: #454545;
	background: #2d2d2d;
	color: #d0d0d0;
}

@media (max-width: 640px) {
	#scr-app .scr-settings { gap: 10px 14px; }
	#scr-app .scr-field,
	#scr-app input[type="number"] { width: 100%; }
	#scr-app .scr-hint { display: none; }
}
</style>

<div id="content" class="site-content site-roll">
<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
	<?php while ( have_posts() ) : the_post(); ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'bk da' ); ?>>
			<header class="entry-header">
				<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
			</header><!-- .entry-header -->

			<div class="entry-content">
				<div class="single-content">
					<?php the_content(); ?>

					<div id="scr-app">
						<p class="scr-lead">
							在本页把代码转换成<strong>可直接粘贴到 WordPress 文章里的高亮 HTML</strong>。模板自带语法着色与行号，代码块采用仿 macOS 窗口样式，右上角为复制图标按钮（悬停显示提示），可切换「默认折叠」或「名片模式」（名片显示代码名称，点击后展开代码），「配色模式」可选自动跟随系统、跟随主题（按页面背景亮度判断）、固定浅色或固定深色，不依赖主题的 <code>js/highlight.js</code>，也不需要安装插件。
						</p>

						<section class="scr-panel">
							<h3>输入源代码 <span class="scr-hint">支持 17 种常用语言</span></h3>
							<textarea id="scr-source" rows="10" spellcheck="false" placeholder="在此粘贴需要高亮的代码…"></textarea>
						</section>

						<section class="scr-panel">
							<h3>转换设置</h3>
							<div class="scr-settings">
								<label class="scr-field">
									<span>选择语言</span>
									<select id="scr-lang">
										<option value="php" selected>PHP</option>
										<option value="java">Java</option>
										<option value="xml">HTML / XML</option>
										<option value="sql">SQL</option>
										<option value="javascript">JavaScript</option>
										<option value="groovy">Groovy</option>
										<option value="css">CSS</option>
										<option value="cpp">C / C++</option>
										<option value="csharp">C#</option>
										<option value="python">Python</option>
										<option value="vb">VB / VBScript</option>
										<option value="perl">Perl</option>
										<option value="ruby">Ruby</option>
										<option value="delphi">Delphi</option>
										<option value="json">JSON</option>
										<option value="markdown">Markdown</option>
										<option value="plaintext">纯文本</option>
									</select>
								</label>
								<label class="scr-field">
									<span>起始行号</span>
									<input type="number" id="scr-start" min="1" step="1" value="1">
								</label>
								<label class="scr-field">
									<span>代码名称</span>
									<input type="text" id="scr-name" placeholder="如：UserService 登录逻辑" spellcheck="false">
								</label>
								<label class="scr-field">
									<span>配色模式</span>
									<select id="scr-scheme">
										<option value="auto" selected>自动（跟随系统）</option>
										<option value="theme">跟随主题</option>
										<option value="light">固定浅色</option>
										<option value="dark">固定深色</option>
									</select>
								</label>
								<label class="scr-check"><input type="checkbox" id="scr-ln" checked> 显示行号</label>
								<label class="scr-check"><input type="checkbox" id="scr-wrap" checked> 自动换行</label>
								<label class="scr-check"><input type="checkbox" id="scr-fold"> 默认折叠</label>
								<label class="scr-check"><input type="checkbox" id="scr-card"> 名片模式</label>

							</div>
							<div class="scr-settings scr-settings-sub">
								<span class="scr-sub-t">样式库引用（统一引用 GitHub + JSDMirror CDN 上的 template-code.css / template-code.js，地址与版本号写在本模板文件头）</span>
								<label class="scr-check"><input type="checkbox" id="scr-lib-first" checked> 本文第一次使用代码高亮（输出 CSS 与 JS 引用）</label>
								<label class="scr-check"><input type="checkbox" id="scr-lib-multi"> 本文多次使用代码高亮（不再引用 CSS 与 JS）</label>
								<span class="scr-hint scr-css-hint">两项互斥：一篇文章只需第一次勾第一项，其余代码块改勾第二项——后续块只输出代码块本身，样式与交互沿用第一次的引用，避免重复加载。</span>
							</div>
							<div class="scr-actions">
								<button type="button" class="scr-btn scr-btn-primary" id="scr-generate">生成 HTML</button>
								<button type="button" class="scr-btn" id="scr-copy">复制 HTML</button>
								<button type="button" class="scr-btn scr-btn-ghost" id="scr-clear">清空</button>
								<span class="scr-status" id="scr-status" role="status" aria-live="polite"></span>
							</div>
						</section>

						<section class="scr-panel">
							<h3>HTML 代码 <span class="scr-hint">在 WordPress 文本模式下粘贴</span></h3>
							<textarea id="scr-output" class="scr-output" rows="7" readonly spellcheck="false" placeholder="生成的 HTML 会显示在这里…"></textarea>
							<p class="scr-foot">粘贴后请保持在<strong>文本模式</strong>，不要切换到可视化模式；配色按上面的「配色模式」锁定（跟随主题时由脚本读页面背景亮度判定），粘贴到任意主题都能正常显示。</p>
						</section>

						<section class="scr-panel">
							<h3>HTML 预览 <span class="scr-hint">文章中的实际效果</span></h3>
							<div id="scr-preview" class="scr-preview">
								<p class="scr-empty">输入代码后，这里会实时显示最终效果。</p>
							</div>
						</section>
					</div><!-- #scr-app -->
				</div><!-- .single-content -->
			</div><!-- .entry-content -->
		</article><!-- #page -->
		<?php endwhile; ?>
	</main><!-- .site-main -->
</div><!-- .content-area -->
</div><!-- #content -->
<div class="clear"></div>

<script type="text/javascript">
(function () {
	'use strict';
	var FONT = "ui-monospace,SFMono-Regular,'SF Mono',Menlo,Consolas,'Liberation Mono',monospace";
	var FONTUI = "-apple-system,BlinkMacSystemFont,'SF Pro Text','Helvetica Neue','PingFang SC',sans-serif";

	/* ================= 样式库地址（部署时要改的就是这一行） =================
	   template-code.css 与 template-code.js 必须放在本模板文件所在的同一目录、同名（只差扩展名），
	   这里默认取本模板文件所在目录下的 template-code.css，JS 地址由 .css 自动换成 .js。
	   若文件被放到别处（插件目录、子主题等），把下面这一行改成完整 URL 即可；留空则只用内联样式。 */
	var CSS_LIB_URL = '<?php echo esc_url( $scr_lib_base . "/template-code.css" ); ?>';

	/* 名片模式里展示的语言名称，与下拉框文案保持一致 */
	var LANG_NAMES = {
		java: 'Java', xml: 'HTML / XML', sql: 'SQL', javascript: 'JavaScript',
		groovy: 'Groovy', css: 'CSS', cpp: 'C / C++', csharp: 'C#',
		python: 'Python', vb: 'VB / VBScript', perl: 'Perl', php: 'PHP',
		ruby: 'Ruby', delphi: 'Delphi', json: 'JSON', markdown: 'Markdown',
		plaintext: '纯文本'
	};

	/* 配色参考 VS Code / JetBrains 一类高对比度编辑器：
	   语义词深色饱和、注释深绿；深色模式整体提亮，保证暗底上依然清晰 */
	var PALETTES = {
		dark: {
			bg: '#1e222a', border: '#333a46', gutterBg: '#191c23', headBg: '#1b2028',
			text: '#e9eef7', gutter: '#7b8494', icon: '#98a3b6',
			shadow: '0 2px 6px rgba(0,0,0,.38), 0 24px 56px rgba(0,0,0,.5)',
			keyword: '#c792ea', string: '#f7a072', comment: '#a3adbb',
			number: '#f2c26b', func: '#ffffff', type: '#ffd479',
			variable: '#ff9db0', tag: '#ff8fab', attr: '#ffffff',
			meta: '#ffffff', literal: '#f2c26b', builtin: '#ffffff',
			lineHover: 'rgba(255,255,255,.055)',
			sel: 'rgba(130,170,255,.28)',
			actHover: 'rgba(255,255,255,.08)',
			tipBg: '#e9eef7', tipText: '#1b2028', ok: '#3fb950',
			accent: '#5b8cff', accentSoft: 'rgba(91,140,255,.14)'
		},
		light: {
			bg: '#ffffff', border: '#dfe4ec', gutterBg: '#f0f3f9', headBg: '#f4f6fa',
			text: '#15181d', gutter: '#8b95a5', icon: '#697386',
			shadow: '0 1px 2px rgba(16,24,40,.06), 0 14px 34px rgba(16,24,40,.11)',
			keyword: '#8e24aa', string: '#a31515', comment: '#57a35e',
			number: '#b45309', func: '#1a56db', type: '#16181d',
			variable: '#16181d', tag: '#8e24aa', attr: '#1a56db',
			meta: '#16181d', literal: '#b45309', builtin: '#16181d',
			lineHover: 'rgba(26,86,219,.06)',
			sel: 'rgba(26,86,219,.16)',
			actHover: 'rgba(16,24,40,.06)',
			tipBg: '#1f2328', tipText: '#ffffff', ok: '#1f883d',
			accent: '#4078f2', accentSoft: 'rgba(64,120,242,.10)'
		}
	};

	var TOKEN_SHORT = {
		keyword: 'kw', string: 'str', comment: 'cmt', number: 'num', func: 'fn',
			type: 'type', variable: 'var', tag: 'tag', attr: 'attr',
		meta: 'meta', literal: 'lit', builtin: 'bi'
	};

	var CB_VARS = [
		['bg', 'bg'], ['border', 'border'], ['gutterBg', 'gutter-bg'], ['headBg', 'head-bg'],
		['text', 'text'], ['gutter', 'gutter'], ['icon', 'icon'], ['shadow', 'shadow'],
		['keyword', 'keyword'], ['string', 'string'], ['comment', 'comment'],
		['number', 'number'], ['func', 'func'], ['type', 'type'],
		['variable', 'variable'], ['tag', 'tag'], ['attr', 'attr'],
		['meta', 'meta'], ['literal', 'literal'], ['builtin', 'builtin'],
		['lineHover', 'line-hover'], ['sel', 'sel'],
		['actHover', 'act-hover'], ['tipBg', 'tip-bg'], ['tipText', 'tip-text'], ['ok', 'ok'],
		['accent', 'accent'], ['accentSoft', 'accent-soft']
	];

	function varsCss(p) {
		var out = [];
		for (var i = 0; i < CB_VARS.length; i++) {
			out.push('--cb-' + CB_VARS[i][1] + ':' + p[CB_VARS[i][0]] + ';');
		}
		return out.join('');
	}

	/* ============ 代码块样式库（唯一一份，内联与引用两种模式共用） ============
	   原则：颜色只在 .scr-cb 上用 CSS 变量定义一次，其余规则只引用 var(--cb-*)，
	   不再逐个属性重复兜底色，CSS 体积可以少三分之一；
	   每条规则单独一行，导出的 template-code.css 也便于阅读和手改 */
	var CSS_PARTS = (function () {
		var base = [], ln = [], fold = [], card = [], scheme = [];
		var i, tk;

		/* 颜色只在这里定义一次，其余规则统一写 var(--cb-*)，不再逐个属性重复兜底色 */
		base.push('.scr-cb{' + varsCss(PALETTES.light)
			+ 'position:relative;margin:28px 0;border:1px solid var(--cb-border);'
			+ 'border-radius:12px;background:var(--cb-bg);box-shadow:var(--cb-shadow);'
			+ 'overflow:hidden;text-align:left;font-family:' + FONT + ';'
			+ 'font-size:13.5px;line-height:1.6}');
		base.push('@media (prefers-color-scheme:dark){.scr-cb{' + varsCss(PALETTES.dark) + '}}');
		base.push('body.night .scr-cb,body.dark .scr-cb{' + varsCss(PALETTES.dark) + '}');

		/* 显式配色：data-scheme=light / dark 直接锁定一套变量，优先级高于上面的 @media 与 body.night；
		   data-scheme=theme 由脚本按页面背景亮度改写为 light 或 dark */
		scheme.push('.scr-cb[data-scheme="light"]{' + varsCss(PALETTES.light) + '}');
		scheme.push('.scr-cb[data-scheme="dark"]{' + varsCss(PALETTES.dark) + '}');
		scheme.push('body.night .scr-cb[data-scheme="light"],body.dark .scr-cb[data-scheme="light"]{'
			+ varsCss(PALETTES.light) + '}');

		/* 屏蔽主题对 pre 的装饰与自带的浮动复制按钮 */
		base.push('.scr-cb pre:before,.scr-cb pre:after{display:none!important;content:none!important}');
		base.push('.scr-cb .btn-clipboard{display:none!important}');
		base.push('.scr-cb ::selection{background:var(--cb-sel);color:inherit}');

		/* 语法着色：短类名 + 变量 */
		var tokens = [
			['keyword', 'kw', 1], ['string', 'str', 0], ['comment', 'cmt', 0],
			['number', 'num', 0], ['func', 'fn', 0], ['type', 'type', 1],
			['variable', 'var', 0], ['tag', 'tag', 1], ['attr', 'attr', 0],
			['meta', 'meta', 0], ['literal', 'lit', 0], ['builtin', 'bi', 0]
		];
		for (i = 0; i < tokens.length; i++) {
			tk = tokens[i];
			base.push('.scr-cb .t-' + tk[1] + '{color:var(--cb-' + tk[0] + ')!important'
				+ (tk[2] ? ';font-weight:600' : '')
				+ (tk[0] === 'comment' ? ';font-style:italic' : '') + '}');
		}

		/* 标题条 + macOS 三色圆点 */
		base.push('.scr-cb .scr-h{display:flex;align-items:center;justify-content:space-between;'
			+ 'gap:12px;padding:6px 8px 6px 12px;border-bottom:1px solid var(--cb-border);'
			+ 'background:var(--cb-head-bg)}');
		base.push('.scr-cb .scr-dots{display:flex;align-items:center;gap:7px;flex:0 0 auto}');
		base.push('.scr-cb .scr-ds{display:block;width:11px;height:11px;border-radius:50%;'
			+ 'box-shadow:inset 0 0 0 1px rgba(0,0,0,.10),inset 0 1px 0 rgba(255,255,255,.35)}');
		base.push('.scr-cb .scr-ds:nth-child(1){background:#ff5f57}');
		base.push('.scr-cb .scr-ds:nth-child(2){background:#febc2e}');
		base.push('.scr-cb .scr-ds:nth-child(3){background:#28c840}');

		/* 右上角图标按钮 + 悬停气泡 */
		base.push('.scr-cb .scr-hb{display:flex;align-items:center;gap:4px;flex:0 0 auto}');
		base.push('.scr-cb .scr-ib{display:inline-flex;align-items:center;justify-content:center;'
			+ 'width:26px;height:26px;margin:0;padding:0;box-sizing:border-box;border:0;'
			+ 'border-radius:7px;background:transparent;box-shadow:none;position:relative;'
			+ 'overflow:visible;color:var(--cb-icon)!important;line-height:1;cursor:pointer}');
		base.push('.scr-cb [data-act]{-webkit-tap-highlight-color:transparent;'
			+ '-webkit-appearance:none;appearance:none;'
			+ 'transition:background-color .16s ease,color .16s ease,transform .1s ease}');
		base.push('.scr-cb [data-act]:hover{background:var(--cb-act-hover)!important;'
			+ 'color:var(--cb-text)!important}');
		base.push('.scr-cb [data-act]:active{transform:translateY(1px)}');
		base.push('.scr-cb [data-act]:focus-visible{outline:2px solid var(--cb-func);outline-offset:2px}');
		base.push('.scr-cb [data-act][data-done]{color:var(--cb-ok)!important}');
		base.push('.scr-cb [data-act][data-tip]::after{content:attr(data-tip);position:absolute;'
			+ 'top:calc(100% + 8px);right:0;padding:6px 9px;border-radius:6px;'
			+ 'background:var(--cb-tip-bg);color:var(--cb-tip-text);'
			+ 'font-family:' + FONTUI + ';font-size:11px;font-weight:500;line-height:1.2;'
			+ 'letter-spacing:0;white-space:nowrap;box-shadow:0 8px 22px rgba(0,0,0,.18);'
			+ 'opacity:0;transform:translateY(-4px);'
			+ 'transition:opacity .16s ease,transform .16s ease;pointer-events:none;z-index:50}');
		base.push('.scr-cb [data-act][data-tip]:hover::after{opacity:1;transform:translateY(0)}');

		/* 代码区与行结构 */
		base.push('.scr-cb .scr-body{overflow-x:auto;padding:16px 0;background:var(--cb-bg);'
			+ 'scrollbar-width:thin;scrollbar-color:rgba(121,132,148,.42) transparent}');
		base.push('.scr-cb .scr-body::-webkit-scrollbar{height:10px;width:10px}');
		base.push('.scr-cb .scr-body::-webkit-scrollbar-thumb{background:rgba(121,132,148,.42);'
			+ 'border:2px solid transparent;border-radius:10px;background-clip:content-box}');
		base.push('.scr-cb .scr-body::-webkit-scrollbar-thumb:hover{background:rgba(121,132,148,.68);'
			+ 'background-clip:content-box}');
		base.push('.scr-cb .scr-body::-webkit-scrollbar-track{background:transparent}');
		base.push('.scr-cb .scr-r{display:flex;align-items:stretch;transition:background-color .15s ease}');
		base.push('.scr-cb .scr-r:hover{background:var(--cb-line-hover)}');
		base.push('.scr-cb[data-wrap="0"] .scr-r{width:max-content;min-width:100%}');
		base.push('.scr-cb .scr-c{flex:1 1 auto;min-width:0;box-sizing:border-box;min-height:24px;'
			+ 'padding:0 26px 0 18px;color:var(--cb-text)!important;'
			+ 'font-size:13.5px;line-height:24px;text-align:left;letter-spacing:0;'
			+ 'tab-size:4;-moz-tab-size:4;white-space:pre;'
			+ '-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale}');
		base.push('.scr-cb[data-wrap="1"] .scr-c{white-space:pre-wrap;word-break:break-word;'
			+ 'overflow-wrap:anywhere}');

		/* 行号列：只在显示行号时输出 */
		ln.push('.scr-cb .scr-n{flex:0 0 auto;box-sizing:border-box;min-width:54px;'
			+ 'padding:0 14px 0 20px;text-align:right;white-space:nowrap;'
			+ 'font-variant-numeric:tabular-nums;font-feature-settings:"tnum" 1;'
			+ 'color:var(--cb-gutter)!important;background:var(--cb-gutter-bg);'
			+ 'border-right:1px solid var(--cb-border);font-size:12px;line-height:24px;'
			+ 'opacity:.9;-webkit-user-select:none;user-select:none}');
		ln.push('.scr-cb[data-wrap="0"] .scr-n{position:sticky;left:0;z-index:1}');

		/* 折叠：高度写在样式里，HTML 不再带内联 style；8 行 × 24px = 192px */
		fold.push('.scr-cb[data-folded="1"] .scr-body{max-height:192px;overflow-y:hidden}');
		fold.push('.scr-cb .scr-fb{position:relative;z-index:2;display:flex;justify-content:center;'
			+ 'margin-top:-40px;padding:26px 16px 12px;pointer-events:none;'
			+ 'background:linear-gradient(to bottom,rgba(0,0,0,0) 0%,var(--cb-bg) 55%);'
			+ 'transition:margin-top .22s ease,padding .22s ease,background .22s ease,border-color .22s ease}');
		fold.push('.scr-cb .scr-fb [data-act]{pointer-events:auto}');
		fold.push('.scr-cb[data-folded="0"] .scr-fb{margin-top:0;padding:8px 16px 10px;'
			+ 'background:transparent;border-top:1px solid var(--cb-border)}');
		fold.push('.scr-cb .scr-fbtn{display:inline-flex;align-items:center;gap:6px;height:30px;'
			+ 'margin:0;padding:0 14px;box-sizing:border-box;border:1px solid var(--cb-border);'
			+ 'border-radius:999px;background:var(--cb-head-bg);color:var(--cb-text)!important;'
			+ 'font-family:' + FONTUI + ';font-size:12px;font-weight:600;line-height:1;'
			+ 'letter-spacing:.01em;cursor:pointer;box-shadow:0 4px 14px rgba(16,24,40,.10);'
			+ 'white-space:nowrap}');
		fold.push('.scr-cb[data-folded="0"] .scr-fbtn svg{transform:rotate(180deg)}');

		/* 名片：只在开启名片模式时才输出这一大段 */
		card.push('.scr-cb .scr-card{display:block;padding:38px 26px 34px;text-align:center;'
			+ 'box-sizing:border-box;background:var(--cb-bg);cursor:pointer;'
			+ '-webkit-user-select:none;user-select:none}');
		card.push('.scr-cb .scr-cg{display:inline-flex;align-items:center;justify-content:center;'
			+ 'width:50px;height:50px;border-radius:16px;background:var(--cb-accent-soft);'
			+ 'color:var(--cb-accent)!important;transition:transform .18s ease}');
		card.push('.scr-cb .scr-card:hover .scr-cg{transform:translateY(-1px)}');
		card.push('.scr-cb .scr-ct{margin:16px auto 0;max-width:520px;font-family:' + FONTUI + ';'
			+ 'font-size:16px;font-weight:600;line-height:1.45;letter-spacing:.005em;'
			+ 'color:var(--cb-text)!important;word-break:break-word}');
		card.push('.scr-cb .scr-cm{margin-top:11px;display:flex;justify-content:center;'
			+ 'align-items:center;flex-wrap:wrap;gap:6px}');
		card.push('.scr-cb .scr-chip{display:inline-flex;align-items:center;height:20px;padding:0 9px;'
			+ 'box-sizing:border-box;border-radius:999px;border:1px solid var(--cb-border);'
			+ 'background:var(--cb-head-bg);color:var(--cb-gutter)!important;'
			+ 'font-family:' + FONTUI + ';font-size:11px;font-weight:500;letter-spacing:.02em}');
		card.push('.scr-cb .scr-ca{margin-top:24px;display:flex;justify-content:center}');
		card.push('.scr-cb .scr-cta{display:inline-flex;align-items:center;gap:6px;height:34px;'
			+ 'padding:0 18px;box-sizing:border-box;border-radius:999px;'
			+ 'background:var(--cb-accent);color:#fff!important;font-family:' + FONTUI + ';'
			+ 'font-size:12.5px;font-weight:600;line-height:1;letter-spacing:.02em;'
			+ 'box-shadow:0 8px 20px rgba(64,120,242,.28);'
			+ 'transition:box-shadow .18s ease,transform .18s ease}');
		card.push('.scr-cb .scr-card:hover .scr-cta{box-shadow:0 12px 26px rgba(64,120,242,.34);'
			+ 'transform:translateY(-2px)}');
		card.push('.scr-cb .scr-card:focus-visible{outline:2px solid var(--cb-func);'
			+ 'outline-offset:-3px;border-radius:12px}');
		card.push('.scr-cb[data-card="1"] [data-cb-acts],'
			+ '.scr-cb[data-card="1"] [data-hl-view],'
			+ '.scr-cb[data-card="1"] [data-fold-bar]{display:none!important}');
		card.push('.scr-cb[data-card="0"] [data-card-view]{display:none}');

		return { base: base, ln: ln, fold: fold, card: card, scheme: scheme };
	})();

	/* 内联模式只拼装当前代码块真正用到的规则，引用模式直接读整份样式库 */
	function cssFor(flags) {
		var out = CSS_PARTS.base.slice();
		if (flags.ln) { out = out.concat(CSS_PARTS.ln); }
		if (flags.fold) { out = out.concat(CSS_PARTS.fold); }
		if (flags.card) { out = out.concat(CSS_PARTS.card); }
		if (flags.scheme) { out = out.concat(CSS_PARTS.scheme); }
		return out.join('\n');
	}

	/* 样式库里始终带上显式配色规则（引用模式下多块共用一个文件） */
	var CSS_FULL = cssFor({ ln: 1, fold: 1, card: 1, scheme: 1 });

	/* 供导出样式库使用（页面里静默存在，不参与渲染） */
	window.__scrCssLib = CSS_FULL;

	function words(str) { return str.split(' '); }
	function toSet(list, lower) {
		var set = {};
		if (!list) { return set; }
		for (var i = 0; i < list.length; i++) {
			set[lower ? list[i].toLowerCase() : list[i]] = 1;
		}
		return set;
	}

	var SPECS = {
		java: {
			line: ['//'], blocks: [['/*', '*/']], strings: ['"', "'"], escape: 'backslash',
			kw: words('abstract assert boolean break byte case catch char class const continue default do double else enum extends final finally float for goto if implements import instanceof int interface long native new package private protected public return short static strictfp super switch synchronized this throw throws transient try void volatile while'),
			literal: words('true false null'), type: true, func: true,
			builtins: words('String System Math Object Integer Double Boolean Long List Map Set ArrayList HashMap Scanner')
		},
		groovy: {
			line: ['//'], blocks: [['/*', '*/']], strings: ['"""', "'''", '"', "'"], escape: 'backslash',
			kw: words('abstract as assert boolean break byte case catch char class const continue def default do double else enum extends final finally float for goto if implements import in instanceof int interface long native new package private protected public return short static strictfp super switch synchronized this throw throws trait transient try void volatile while'),
			literal: words('true false null'), type: true, func: true,
			builtins: words('String GString List Map Set Closure println each collect')
		},
		cpp: {
			line: ['//'], blocks: [['/*', '*/']], strings: ['"', "'"], escape: 'backslash', preprocessor: true,
			kw: words('alignas alignof asm auto bool break case catch char class const constexpr const_cast continue decltype default delete do double dynamic_cast else enum explicit extern false float for friend goto if inline int long mutable namespace new noexcept nullptr operator private protected public register reinterpret_cast return short signed sizeof static static_assert static_cast struct switch template this throw true try typedef typeid typename union unsigned using virtual void volatile wchar_t while'),
			literal: words('true false nullptr'), type: true, func: true,
			builtins: words('std cout cin cerr endl string vector map set size_t int8_t uint8_t printf malloc')
		},
		csharp: {
			line: ['//'], blocks: [['/*', '*/']], strings: ['"', "'"], escape: 'backslash', preprocessor: true,
			kw: words('abstract as base bool break byte case catch char checked class const continue decimal default delegate do double else enum event explicit extern finally fixed float for foreach goto if implicit in int interface internal is lock long namespace new null object operator out override params private protected public readonly ref return sbyte sealed short sizeof stackalloc static string struct switch this throw try typeof uint ulong unchecked unsafe ushort using virtual void volatile while var async await yield record init'),
			literal: words('true false null'), type: true, func: true,
			builtins: words('Console Math String Object List Dictionary IEnumerable Task Exception')
		},
		javascript: {
			line: ['//'], blocks: [['/*', '*/']], strings: ['"', "'", '`'], escape: 'backslash',
			kw: words('break case catch class const continue debugger default delete do else export extends finally for function if import in instanceof let new return super switch this throw try typeof var void while with yield async await static get set of from as'),
			literal: words('true false null undefined NaN Infinity'), func: true,
			builtins: words('console document window navigator location localStorage sessionStorage Math JSON Object Array String Number Boolean Promise Map Set Date RegExp Error Symbol Intl')
		},
		sql: {
			line: ['--', '#'], blocks: [['/*', '*/']], strings: ["'", '"'], escape: 'double', ci: true, backtick: true,
			kw: words('select from where insert into values update set delete create table drop alter add index view join inner left right outer on as and or not null is in exists between like case when then else end group by order having limit offset union all distinct primary key foreign references default auto_increment engine charset varchar int bigint smallint text datetime timestamp decimal float double char blob procedure function begin declare while return returns if'),
			literal: words('null true false'), func: true,
			builtins: words('count sum avg min max now concat substring length')
		},
		python: {
			line: ['#'], blocks: [], strings: ['"""', "'''", '"', "'"], escape: 'backslash',
			kw: words('and as assert async await break class continue def del elif else except finally for from global if import in is lambda nonlocal not or pass raise return try while with yield match case'),
			literal: words('True False None'), builtins: words('print len range str int float list dict set tuple bool type isinstance enumerate zip map filter sum min max abs sorted open input super self iter next repr format'), func: true, decorator: true, type: true
		},
		php: {
			line: ['//', '#'], blocks: [['/*', '*/']], strings: ['"', "'"], escape: 'backslash', ci: true, vars: true, phpTags: true,
			kw: words('abstract and array as break callable case catch class clone const continue declare default do echo else elseif empty enddeclare endfor endforeach endif endswitch endwhile eval exit extends final finally fn for foreach function global goto if implements include include_once instanceof insteadof interface isset list match namespace new or print private protected public readonly require require_once return static switch throw trait try unset use var while xor yield'),
			literal: words('true false null'), func: true,
			builtins: words('array strlen count explode implode str_replace sprintf printf htmlspecialchars json_encode json_decode print_r var_dump isset empty date')
		},
		ruby: {
			line: ['#'], blocks: [], strings: ['"', "'"], escape: 'backslash', vars: true, symbols: true, func: true, type: true,
			kw: words('alias and begin break case class def defined do else elsif end ensure for if in module next not or redo rescue retry return self super then undef unless until when while yield require require_relative attr_accessor attr_reader attr_writer include extend prepend'),
			literal: words('true false nil'), builtins: words('puts print p gets loop lambda proc raise new')
		},
		perl: {
			line: ['#'], blocks: [], strings: ['"', "'", '`'], escape: 'backslash', vars: true, func: true,
			kw: words('my our local sub use require package if elsif else unless while until for foreach do last next redo return and or not xor eq ne lt gt le ge cmp'),
			literal: words('undef'), builtins: words('print say open close die exit chomp chop split join push pop shift unshift keys values each sort map grep')
		},
		vb: {
			line: ["'"], blocks: [], strings: ['"'], escape: 'double', ci: true, func: true,
			kw: words('addhandler and as boolean by byref byte call case catch class const continue date decimal declare default delegate dim do double each else elseif end enum erase error event exit finally for friend function get global handles if implements imports in inherits integer interface is let like long loop me mod module namespace new next not nothing object on operator optional or private property protected public raiseevent readonly redim removehandler resume return select set shared short single static step stop string structure sub then throw to true try type using variant wend when while with xor'),
			literal: words('true false nothing'),
			builtins: words('Console Math String Integer Double Boolean MsgBox')
		},
		delphi: {
			line: ['//'], blocks: [['{', '}'], ['(*', '*)']], strings: ["'"], escape: 'double', ci: true, func: true,
			kw: words('and array as asm begin case class const constructor destructor div do downto else end except exports file finalization finally for function goto if implementation in inherited initialization inline interface is label library mod not object of or out packed procedure program property raise record repeat resourcestring set shl shr string then threadvar to try type unit until uses var while with xor'),
			literal: words('true false nil'),
			builtins: words('integer string boolean char real double single byte word longint cardinal tobject')
		},
		json: {
			line: [], blocks: [], strings: ['"'], escape: 'backslash',
			kw: [], literal: words('true false null'), func: false
		},
		xml: { custom: 'xml' },
		css: { custom: 'css' },
		markdown: { line: [], blocks: [], strings: [], custom: 'markdown' },
		plaintext: { line: [], blocks: [], strings: [] }
	};

	for (var specKey in SPECS) {
		if (SPECS.hasOwnProperty(specKey)) {
			var spec = SPECS[specKey];
			spec.keywordSet = toSet(spec.kw, spec.ci);
			spec.literalSet = toSet(spec.literal, spec.ci);
			spec.builtinSet = toSet(spec.builtins, spec.ci);
			if (spec.strings && spec.strings.length) {
				spec.strings.sort(function (a, b) { return b.length - a.length; });
			}
		}
	}

	function esc(str) {
		return String(str)
			.replace(/&/g, '&amp;')
			.replace(/</g, '&lt;')
			.replace(/>/g, '&gt;')
			.replace(/"/g, '&quot;');
	}
	/* 颜色同时给出内联兜底值 + !important：
	   这样即使 WordPress 把 <style> 剥离、或主题用 !important 覆盖颜色，
	   高亮依然生效，不会再退化成纯黑文字 */
	function span(type, text) {
		if (!text) { return ''; }
		/* 着色统一交给样式库里的短类名：内联、引用两种模式共用同一套标记 */
		return '<span class="t-' + (TOKEN_SHORT[type] || type) + '">' + esc(text) + '</span>';
	}

	function isSpace(ch) {
		return ch === ' ' || ch === '\t' || ch === '\n' || ch === '\r';
	}

	function spaceEnd(code, i) {
		var n = code.length;
		while (i < n && isSpace(code.charAt(i))) { i++; }
		return i;
	}

	function readString(code, i, quote, escape) {
		var n = code.length;
		var j = i + quote.length;
		while (j < n) {
			if (escape === 'double') {
				if (code.substr(j, quote.length) === quote) {
					if (code.substr(j + quote.length, quote.length) === quote) {
						j += quote.length * 2;
						continue;
					}
					return j + quote.length;
				}
			} else {
				if (code.charAt(j) === '\\') {
					j += 2;
					continue;
				}
				if (code.substr(j, quote.length) === quote) {
					return j + quote.length;
				}
			}
			j++;
		}
		return n;
	}

	function isFuncCall(code, from) {
		var n = code.length;
		var i = from;
		var limit = 0;
		while (i < n && limit < 40) {
			var ch = code.charAt(i);
			if (ch === '(') { return true; }
			if (!isSpace(ch)) { return false; }
			i++;
			limit++;
		}
		return false;
	}

	var NUMBER_RE = /0x[0-9a-fA-F]+|0b[01]+|\d+(?:\.\d+)?(?:[eE][+-]?\d+)?/y;
	var IDENT_RE = /[A-Za-z_$][A-Za-z0-9_$]*/y;

	function highlightCode(code, lang) {
		var spec = SPECS[lang] || SPECS.plaintext;
		if (spec.custom === 'xml') { return highlightXml(code); }
		if (spec.custom === 'css') { return highlightCss(code); }
		if (spec.custom === 'markdown') { return highlightMarkdown(code); }

		var out = '';
		var i = 0;
		var n = code.length;
		var blocks = spec.blocks || [];
		var lines = spec.line || [];

		while (i < n) {
			var ch = code.charAt(i);

			if (isSpace(ch)) {
				var wsEnd = spaceEnd(code, i);
				out += esc(code.slice(i, wsEnd));
				i = wsEnd;
				continue;
			}

			var matched = false;

			for (var b = 0; b < blocks.length; b++) {
				if (code.substr(i, blocks[b][0].length) === blocks[b][0]) {
					var close = code.indexOf(blocks[b][1], i + blocks[b][0].length);
					var blockEnd = close === -1 ? n : close + blocks[b][1].length;
					out += span('comment', code.slice(i, blockEnd));
					i = blockEnd;
					matched = true;
					break;
				}
			}
			if (matched) { continue; }

			for (var l = 0; l < lines.length; l++) {
				if (code.substr(i, lines[l].length) === lines[l]) {
					var nl = code.indexOf('\n', i);
					var lineEnd = nl === -1 ? n : nl;
					out += span('comment', code.slice(i, lineEnd));
					i = lineEnd;
					matched = true;
					break;
				}
			}
			if (matched) { continue; }

			if (spec.preprocessor && ch === '#' && (i === 0 || code.charAt(i - 1) === '\n')) {
				var pnl = code.indexOf('\n', i);
				var preEnd = pnl === -1 ? n : pnl;
				out += span('meta', code.slice(i, preEnd));
				i = preEnd;
				continue;
			}

			/* PHP 起始标签：仅当同一行内出现结束标记时，才把整段按标签吞掉；
			   找不到结束标记时只吃掉「起始标签本身」，其后的 PHP 代码
			   继续按注释、字符串、关键字正常着色，不会被整块吞成灰色。 */
			if (spec.phpTags && code.charAt(i) === '<' && code.charAt(i + 1) === '?') {
				var nlTag = code.indexOf('\n', i);
				var lineEnd = nlTag === -1 ? n : nlTag;
				var closeTag = code.indexOf('?>', i);
				var tagEnd;
				if (closeTag !== -1 && closeTag < lineEnd) {
					tagEnd = closeTag + 2;
				} else {
					var openTag = /^<[?](php|=)?/i.exec(code.slice(i, lineEnd));
					tagEnd = openTag ? i + openTag[0].length : lineEnd;
				}
				if (tagEnd <= i) { tagEnd = i + 2; }
				out += span('meta', code.slice(i, tagEnd));
				i = tagEnd;
				continue;
			}

			if (spec.phpTags && code.charAt(i) === '?' && code.charAt(i + 1) === '>') {
				out += span('meta', '?>');
				i += 2;
				continue;
			}

			var strings = spec.strings || [];
			for (var s = 0; s < strings.length; s++) {
				if (code.substr(i, strings[s].length) === strings[s]) {
					var strEnd = readString(code, i, strings[s], spec.escape);
					out += span('string', code.slice(i, strEnd));
					i = strEnd;
					matched = true;
					break;
				}
			}
			if (matched) { continue; }

			if (spec.backtick && ch === '`') {
				var tickEnd = code.indexOf('`', i + 1);
				tickEnd = tickEnd === -1 ? n : tickEnd + 1;
				out += span('attr', code.slice(i, tickEnd));
				i = tickEnd;
				continue;
			}

			if (spec.decorator && ch === '@') {
				IDENT_RE.lastIndex = i + 1;
				var deco = IDENT_RE.exec(code);
				if (deco) {
					out += span('attr', code.slice(i, i + deco[0].length + 1));
					i += deco[0].length + 1;
					continue;
				}
			}

			if (spec.symbols && ch === ':' && code.charAt(i + 1) !== ':') {
				IDENT_RE.lastIndex = i + 1;
				var sym = IDENT_RE.exec(code);
				if (sym) {
					out += span('attr', code.slice(i, i + sym[0].length + 1));
					i += sym[0].length + 1;
					continue;
				}
			}

			if (spec.vars && (ch === '$' || ch === '@' || ch === '%')) {
				var sigEnd = i + 1;
				if (ch === '@' && code.charAt(sigEnd) === '@') { sigEnd++; }
				IDENT_RE.lastIndex = sigEnd;
				var vari = IDENT_RE.exec(code);
				if (vari) {
					out += span('variable', code.slice(i, sigEnd + vari[0].length));
					i = sigEnd + vari[0].length;
					continue;
				}
			}

			NUMBER_RE.lastIndex = i;
			var num = NUMBER_RE.exec(code);
			if (num && num.index === i) {
				out += span('number', num[0]);
				i += num[0].length;
				continue;
			}

			IDENT_RE.lastIndex = i;
			var ident = IDENT_RE.exec(code);
			if (ident && ident.index === i) {
				var word = ident[0];
				var key = spec.ci ? word.toLowerCase() : word;

				if (spec.keywordSet[key]) {
					out += span('keyword', word);
				} else if (spec.literalSet[key]) {
					out += span('literal', word);
				} else if (spec.builtinSet[key]) {
					out += span('builtin', word);
				} else if (spec.type && /^[A-Z]/.test(word)) {
					out += span('type', word);
				} else if (spec.func && isFuncCall(code, i + word.length)) {
					out += span('func', word);
				} else {
					out += esc(word);
				}
				i += word.length;
				continue;
			}

			out += esc(ch);
			i++;
		}

		return out;
	}
	var XML_NAME_RE = /[A-Za-z_][A-Za-z0-9._:-]*/y;

	function highlightXml(code) {
		var out = '';
		var i = 0;
		var n = code.length;

		while (i < n) {
			if (code.substr(i, 4) === '<!--') {
				var cEnd = code.indexOf('-->', i + 4);
				cEnd = cEnd === -1 ? n : cEnd + 3;
				out += span('comment', code.slice(i, cEnd));
				i = cEnd;
				continue;
			}

			if (code.substr(i, 9) === '<![CDATA[') {
				var dEnd = code.indexOf(']]>', i);
				dEnd = dEnd === -1 ? n : dEnd + 3;
				out += span('string', code.slice(i, dEnd));
				i = dEnd;
				continue;
			}

			if (code.charAt(i) === '<' && code.charAt(i + 1) === '!') {
				var mEnd = code.indexOf('>', i);
				mEnd = mEnd === -1 ? n : mEnd + 1;
				out += span('meta', code.slice(i, mEnd));
				i = mEnd;
				continue;
			}

			if (code.charAt(i) === '<') {
				out += esc('<');
				i++;

				if (code.charAt(i) === '/') {
					out += esc('/');
					i++;
				}

				XML_NAME_RE.lastIndex = i;
				var tag = XML_NAME_RE.exec(code);
				if (tag) {
					out += span('tag', tag[0]);
					i += tag[0].length;
				}

				while (i < n && code.charAt(i) !== '>') {
					var ch = code.charAt(i);

					if (isSpace(ch)) {
						var wEnd = spaceEnd(code, i);
						out += esc(code.slice(i, wEnd));
						i = wEnd;
						continue;
					}

					if (ch === '/' && code.charAt(i + 1) === '>') {
						out += esc('/>');
						i += 2;
						break;
					}

					XML_NAME_RE.lastIndex = i;
					var attr = XML_NAME_RE.exec(code);
					if (attr) {
						out += span('attr', attr[0]);
						i += attr[0].length;
						continue;
					}

					if (ch === '"' || ch === "'") {
						var qEnd = code.indexOf(ch, i + 1);
						qEnd = qEnd === -1 ? n : qEnd + 1;
						out += span('string', code.slice(i, qEnd));
						i = qEnd;
						continue;
					}

					out += esc(ch);
					i++;
				}

				if (code.charAt(i) === '>') {
					out += esc('>');
					i++;
				}
				continue;
			}

			var next = code.indexOf('<', i);
			if (next === -1) { next = n; }
			out += esc(code.slice(i, next));
			i = next;
		}

		return out;
	}

	var CSS_WORD_RE = /-?[A-Za-z_][A-Za-z0-9_-]*/y;
	var CSS_HEX_RE = /#[0-9a-fA-F]{3,8}/y;
	var CSS_NUM_RE = /\d+(?:\.\d+)?(?:px|em|rem|%|vh|vw|vmin|vmax|s|ms|deg|fr|ch)?/y;

	function highlightCss(code) {
		var out = '';
		var i = 0;
		var n = code.length;
		var depth = 0;

		while (i < n) {
			var ch = code.charAt(i);

			if (isSpace(ch)) {
				var wEnd = spaceEnd(code, i);
				out += esc(code.slice(i, wEnd));
				i = wEnd;
				continue;
			}

			if (code.substr(i, 2) === '/*') {
				var cEnd = code.indexOf('*/', i + 2);
				cEnd = cEnd === -1 ? n : cEnd + 2;
				out += span('comment', code.slice(i, cEnd));
				i = cEnd;
				continue;
			}

			if (ch === '"' || ch === "'") {
				var qEnd = code.indexOf(ch, i + 1);
				qEnd = qEnd === -1 ? n : qEnd + 1;
				out += span('string', code.slice(i, qEnd));
				i = qEnd;
				continue;
			}

			if (ch === '@') {
				CSS_WORD_RE.lastIndex = i + 1;
				var atRule = CSS_WORD_RE.exec(code);
				if (atRule) {
					out += span('keyword', code.slice(i, i + atRule[0].length + 1));
					i += atRule[0].length + 1;
					continue;
				}
			}

			CSS_HEX_RE.lastIndex = i;
			var hex = CSS_HEX_RE.exec(code);
			if (hex) {
				out += span('number', hex[0]);
				i += hex[0].length;
				continue;
			}

			CSS_NUM_RE.lastIndex = i;
			var num = CSS_NUM_RE.exec(code);
			if (num) {
				out += span('number', num[0]);
				i += num[0].length;
				continue;
			}

			CSS_WORD_RE.lastIndex = i;
			var word = CSS_WORD_RE.exec(code);
			if (word) {
				var after = spaceEnd(code, i + word[0].length);
				var isProp = code.charAt(after) === ':';
				if (isProp) {
					out += span('attr', word[0]);
				} else if (word[0] === 'important') {
					out += span('keyword', word[0]);
				} else if (depth === 0) {
					out += span('tag', word[0]);
				} else {
					out += esc(word[0]);
				}
				i += word[0].length;
				continue;
			}

			if (ch === '{') { depth++; }
			if (ch === '}') { depth = Math.max(0, depth - 1); }
			out += esc(ch);
			i++;
		}

		return out;
	}

	function highlightMarkdown(code) {
		var inlineRe = /(`[^`\n]*`|\*\*[^*\n]+\*\*|__[^_\n]+__|\[[^\]\n]*\]\([^)\n]*\))/g;
		var lines = code.split('\n');
		var html = [];

		for (var i = 0; i < lines.length; i++) {
			var line = lines[i];
			var out = '';
			var heading = /^(#{1,6})\s+(.*)$/.exec(line);
			var fence = /^\s*```/.test(line);
			var quote = /^\s*>\s?/.test(line);
			var bullet = /^\s*(?:[-*+]|\d+\.)\s+/.exec(line);

			if (heading) {
				out = span('tag', heading[1]) + esc(line.slice(heading[1].length));
			} else if (fence) {
				out = span('meta', line);
			} else if (quote) {
				out = span('comment', line);
			} else if (bullet) {
				out = span('keyword', bullet[0].replace(/\s+$/, '')) + esc(line.slice(bullet[0].length));
			} else {
				var parts = line.split(inlineRe);
				for (var p = 0; p < parts.length; p++) {
					var part = parts[p];
					if (!part) { continue; }
					if (/^\*\*[\s\S]+\*\*$/.test(part) || /^__[\s\S]+__$/.test(part)) {
						out += span('literal', part.slice(2, -2));
					} else if (/^`[\s\S]+`$/.test(part)) {
						out += span('string', part);
					} else if (/^\[[\s\S]+\]\([\s\S]+\)$/.test(part)) {
						out += span('func', part);
					} else {
						out += esc(part);
					}
				}
			}

			html.push(out);
		}

		return html.join('\n');
	}

	function normalizeCode(value) {
		var code = String(value == null ? '' : value).replace(/\r\n?/g, '\n');
		code = code.replace(/^\n/, '');
		return code.replace(/\n+$/, '');
	}

	/* 把整段高亮结果按行拆成独立 HTML；
	   跨行的注释/字符串会在断行处先闭合、下一行再重开，
	   保证每行都是合法片段，可以各自放进一个 flex 行里与行号严格对齐 */
	function splitLines(html) {
		var parts = html.match(/<\/?span[^>]*>|[^<]+/g) || [];
		var lines = [];
		var open = [];
		var cur = '';
		var i, p, segs, s, c;

		for (i = 0; i < parts.length; i++) {
			p = parts[i];
			if (p.charAt(0) === '<') {
				if (p.charAt(1) === '/') { open.pop(); } else { open.push(p); }
				cur += p;
				continue;
			}
			segs = p.split('\n');
			for (s = 0; s < segs.length; s++) {
				if (s > 0) {
					for (c = 0; c < open.length; c++) { cur += '</span>'; }
					lines.push(cur);
					cur = open.join('');
				}
				cur += segs[s];
			}
		}

		lines.push(cur);
		return lines;
	}

	/* 代码块内按钮的点击逻辑。整段会被序列化进生成结果，
	   所以函数体内不能引用外部变量；同一页面多次粘贴也只绑定一次 */
	function blockText(block) {
		var view = block.querySelector('[data-hl-view]');
		if (!view) { return ''; }
		var rows = view.querySelectorAll('.scr-r');
		var out = [];
		for (var i = 0; i < rows.length; i++) {
			var cell = rows[i].querySelector('.scr-c');
			out.push(cell ? cell.textContent : '');
		}
		return out.join('\n');
	}

	function codeBlockClick(event) {
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
	}

	/* 生成结果自带的绑定脚本：文章页里没有模板的 JS，靠它让按钮可用 */
	/* 判断页面（不是操作系统）是深色还是浅色：取 body 实际背景色折算亮度 */
	function pageIsDark() {
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
	}

	/* 把 data-scheme-mode="theme" 的代码块改写成 data-scheme="light|dark" */
	function applySchemeIn(scope) {
		var root = scope || document;
		var els = root.querySelectorAll('.scr-cb[data-scheme-mode="theme"]');
		if (!els.length) { return; }
		var dark = pageIsDark(), i;
		for (i = 0; i < els.length; i++) {
			els[i].setAttribute('data-scheme', dark ? 'dark' : 'light');
		}
	}

	/* 主题切换（夜间插件改 body 的 class / data-theme）时重新判定一次 */
	function watchScheme() {
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
	}

	function bindScript(withScheme) {
		return '(function(){if(window.__scrCbBound){return;}window.__scrCbBound=1;'
			+ 'var blockText=' + String(blockText) + ';'
			+ 'var codeBlockClick=' + String(codeBlockClick) + ';'
			+ 'document.addEventListener("click",codeBlockClick);'
			+ (withScheme
				? 'var pageIsDark=' + String(pageIsDark) + ';'
					+ 'var applySchemeIn=' + String(applySchemeIn) + ';'
					+ 'var watchScheme=' + String(watchScheme) + ';'
					+ 'watchScheme();'
				: '')
			+ '})();';
	}

	/* 线性图标取 currentColor，跟随按钮文字色；不换行，避免被 wpautop 插入 <br> */
	var ICON_COPY = '<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="flex:0 0 auto;display:block;"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>';
	var ICON_CODE = '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="flex:0 0 auto;display:block;"><polyline points="16 18 22 12 16 6"></polyline><polyline points="8 6 2 12 8 18"></polyline></svg>';
	var ICON_CHEVRON = '<svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="flex:0 0 auto;display:block;transition:transform .18s ease;"><polyline points="6 9 12 15 18 9"></polyline></svg>';

	/* 把单行的 SVG 图标拆成多行：<svg ...> 一行、内部图形各自一行、</svg> 一行 */
	function iconLines(svg, pad) {
		var parts = svg.split(/(?=<(?:rect|path|polyline|circle|ellipse)\b|<\/svg>)/);
		var out = [];
		for (var i = 0; i < parts.length; i++) {
			var t = parts[i].replace(/^\s+|\s+$/g, '');
			if (!t) { continue; }
			var isClose = t.indexOf('</svg>') === 0;
			out.push((isClose ? pad : (out.length === 0 ? pad : pad + '  ')) + t);
		}
		return out;
	}

	/* 生成的 HTML 按最终渲染的结构逐行输出（每个标签一行、按层级缩进），
	   既方便直接在文章里手改，也不会在代码行内插入多余空白 */
	function buildHtml(code, opt) {
		var lines = splitLines(highlightCode(code, opt.lang));
		var wrap = !!opt.wrap;
		var ln = !!opt.ln;
		/* 样式统一走引用样式库，没有内联分支；libFirst 只由勾选框决定 */
		var libFirst = opt.lib !== 'next';
		var scheme = opt.scheme || 'auto';
		var schemeAttr = scheme === 'theme'
			? ' data-scheme-mode="theme"'
			: (scheme === 'light' || scheme === 'dark' ? ' data-scheme="' + scheme + '"' : '');
		var canFold = !!opt.fold && lines.length > 9;
		var canCard = !!opt.card;
		var cardName = (opt.name || '').replace(/^\s+|\s+$/g, '') || '代码片段';
		var langName = LANG_NAMES[opt.lang] || 'Text';
		var i;

		var rows = [];
		for (i = 0; i < lines.length; i++) {
			rows.push('    <div class="scr-r">'
				+ (ln ? '<span class="scr-n">' + (opt.start + i) + '</span>' : '')
				+ '<span class="scr-c">' + lines[i] + '</span></div>');
		}

		var out = [];

		/* 样式只在本文第一块输出一条 link，后续块完全省略，避免重复引用；
		   样式库地址未配置时不输出空 href（避免坏链） */
		if (libFirst && opt.cssUrl) {
			out.push('<link rel="stylesheet" href="' + esc(opt.cssUrl) + '">');
		}

		out.push('<div class="scr-cb" data-cb="1" data-wrap="' + (wrap ? '1' : '0') + '"'
			+ (canFold ? ' data-folded="1"' : '')
			+ (canCard ? ' data-card="1"' : '') + schemeAttr + '>');

		/* 标题条：三色圆点 + 只有一枚复制按钮 */
		out.push('  <div class="scr-h">');
		out.push('    <span class="scr-dots"><i class="scr-ds"></i><i class="scr-ds"></i><i class="scr-ds"></i></span>');
		out.push('    <span class="scr-hb" data-cb-acts="1">');
		out.push('      <button type="button" class="scr-ib" data-act="copy" data-tip="复制代码" aria-label="复制代码">');
		out = out.concat(iconLines(ICON_COPY, '        '));
		out.push('      </button>');
		out.push('    </span>');
		out.push('  </div>');

		/* 名片：名称 + 语言/行数 + 展开提示，点击后整块展开 */
		if (canCard) {
			out.push('  <div class="scr-card" data-card-view="1" data-act="card" role="button" tabindex="0" aria-label="展开代码">');
			out.push('    <span class="scr-cg">');
			out = out.concat(iconLines(ICON_CODE, '      '));
			out.push('    </span>');
			out.push('    <div class="scr-ct">' + esc(cardName) + '</div>');
			out.push('    <div class="scr-cm">');
			out.push('      <span class="scr-chip">' + esc(langName) + '</span>');
			out.push('      <span class="scr-chip">' + lines.length + ' 行</span>');
			out.push('    </div>');
			out.push('    <div class="scr-ca">');
			out.push('      <span class="scr-cta" data-card-pill="1">' + ICON_CHEVRON + '<span>展开代码</span></span>');
			out.push('    </div>');
			out.push('  </div>');
		}

		/* 代码主体：一行一个标签，行内不加任何空白 */
		out.push('  <div class="scr-body" data-hl-view="1">');
		out.push(rows.join('\n'));
		out.push('  </div>');

		if (canFold) {
			out.push('  <div class="scr-fb" data-fold-bar="1">');
			out.push('    <button type="button" class="scr-fbtn" data-act="fold" aria-expanded="false">'
				+ ICON_CHEVRON + '<span data-label="1">展开全部</span></button>');
			out.push('  </div>');
		}

		out.push('</div>');

		/* 脚本外链（地址由样式库地址推导：xxx.css -> xxx.js）；
		   后续块沿用第一块已经加载的脚本，不再重复引用 */
		var jsUrl = opt.cssUrl.indexOf('.css') >= 0 ? opt.cssUrl.replace('.css', '.js') : '';
		if (libFirst && jsUrl) {
			out.push('<script src="' + esc(jsUrl) + '" defer><\/script>');
		}

		return out.join('\n');
	}

	var srcEl = document.getElementById('scr-source');
	var langEl = document.getElementById('scr-lang');
	var startEl = document.getElementById('scr-start');
	var lnEl = document.getElementById('scr-ln');
	var wrapEl = document.getElementById('scr-wrap');
	var foldEl = document.getElementById('scr-fold');
	var cardEl = document.getElementById('scr-card');
	var nameEl = document.getElementById('scr-name');
	var libFirstEl = document.getElementById('scr-lib-first');
	var libMultiEl = document.getElementById('scr-lib-multi');
	var schemeEl = document.getElementById('scr-scheme');
	var outputEl = document.getElementById('scr-output');
	var previewEl = document.getElementById('scr-preview');
	var statusEl = document.getElementById('scr-status');

	if (!srcEl || !outputEl || !previewEl) { return; }

	var emptyHtml = '<p class="scr-empty">输入代码后，这里会实时显示最终效果。</p>';
	var statusTimer = null;

	function setStatus(text) {
		if (!statusEl) { return; }
		statusEl.textContent = text;
		window.clearTimeout(statusTimer);
		if (text) {
			statusTimer = window.setTimeout(function () { statusEl.textContent = ''; }, 2600);
		}
	}

	function readOptions() {
		var start = parseInt(startEl.value, 10);
		if (isNaN(start) || start < 1) { start = 1; }
		return {
			lang: langEl.value,
			start: start,
			ln: lnEl.checked,
			wrap: wrapEl.checked,
			fold: !!(foldEl && foldEl.checked),
			card: !!(cardEl && cardEl.checked),
			name: nameEl ? (nameEl.value || '').replace(/^\s+|\s+$/g, '') : '',
			cssMode: 'link',
			lib: (libMultiEl && libMultiEl.checked) ? 'next' : 'first',
			scheme: schemeEl ? schemeEl.value : 'auto',
			cssUrl: (CSS_LIB_URL || '').replace(/^\s+|\s+$/g, '')
		};
	}

	function refresh() {
		var code = normalizeCode(srcEl.value);
		var opt = readOptions();

		if (code === '') {
			outputEl.value = '';
			previewEl.innerHTML = emptyHtml;
			return;
		}

		var html = buildHtml(code, opt);
		outputEl.value = html;
		var preview = html;
		/* 「后续代码块」模式把 link 省掉了，预览区自己补一条，保证本页样式正常 */
		if (opt.lib === 'next' && opt.cssUrl) {
			preview = '<link rel="stylesheet" href="' + opt.cssUrl.replace(/"/g, '&quot;') + '">' + preview;
		}
		previewEl.innerHTML = preview;
		applySchemeIn(previewEl);
	}

	var refreshTimer = null;
	function scheduleRefresh() {
		window.clearTimeout(refreshTimer);
		refreshTimer = window.setTimeout(refresh, 300);
	}

	function legacyCopy(text) {
		outputEl.focus();
		outputEl.select();
		var ok = false;
		try { ok = document.execCommand('copy'); } catch (e) { ok = false; }
		return ok;
	}

	function copyOutput() {
		var text = outputEl.value;
		if (!text) {
			setStatus('请先输入代码并生成 HTML');
			return;
		}

		if (legacyCopy(text)) {
			setStatus('已复制到剪贴板');
			return;
		}

		if (navigator.clipboard && window.isSecureContext) {
			setStatus('正在复制…');
			navigator.clipboard.writeText(text).then(function () {
				setStatus('已复制到剪贴板');
			}).catch(function () {
				setStatus('复制失败，请手动全选复制');
			});
		} else {
			setStatus('复制失败，请手动全选复制');
		}
	}

	document.getElementById('scr-generate').addEventListener('click', function () {
		refresh();
		setStatus(srcEl.value.replace(/^\s+|\s+$/g, '') ? '已生成，可复制 HTML' : '请先输入代码');
	});

	document.getElementById('scr-copy').addEventListener('click', copyOutput);

	document.getElementById('scr-clear').addEventListener('click', function () {
		srcEl.value = '';
		startEl.value = 1;
		refresh();
		setStatus('已清空');
		srcEl.focus();
	});

	srcEl.addEventListener('input', scheduleRefresh);

	if (nameEl) { nameEl.addEventListener('input', scheduleRefresh); }

	/* 两个样式库勾选框互斥：勾一个自动取消另一个，并刷新生成的 HTML */
	if (libFirstEl && libMultiEl) {
		libFirstEl.addEventListener('change', function () {
			if (libFirstEl.checked) { libMultiEl.checked = false; }
			if (!libFirstEl.checked && !libMultiEl.checked) { libFirstEl.checked = true; }
			refresh();
		});
		libMultiEl.addEventListener('change', function () {
			if (libMultiEl.checked) { libFirstEl.checked = false; }
			if (!libFirstEl.checked && !libMultiEl.checked) { libFirstEl.checked = true; }
			refresh();
		});
	}

	/* 预览区里的代码块按钮同样走这套逻辑（预览是 innerHTML 注入，脚本不会自动执行） */
	document.addEventListener('click', codeBlockClick);

	startEl.addEventListener('input', scheduleRefresh);

	[langEl, lnEl, wrapEl, foldEl, cardEl].forEach(function (el) {
		if (!el) { return; }
		el.addEventListener('change', refresh);
	});

	srcEl.addEventListener('keydown', function (event) {
		if (event.key === 'Tab' && !event.shiftKey && !event.ctrlKey && !event.metaKey) {
			event.preventDefault();
			if (typeof srcEl.setRangeText === 'function') {
				srcEl.setRangeText('\t', srcEl.selectionStart, srcEl.selectionEnd, 'end');
			} else {
				srcEl.value += '\t';
			}
			scheduleRefresh();
		}
		if ((event.ctrlKey || event.metaKey) && event.key === 'Enter') {
			event.preventDefault();
			refresh();
			setStatus('已生成，可复制 HTML');
		}
	});

	refresh();

})();
</script>

<?php get_footer(); ?>

