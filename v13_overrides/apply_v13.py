from pathlib import Path
import sys
root=Path(sys.argv[1])

def rep(path, old, new, count=1):
    p=root/path; s=p.read_text()
    if old not in s: raise SystemExit(f'missing pattern in {path}: {old[:80]}')
    p.write_text(s.replace(old,new,count))

# JS: sync native system-bar theme, add author ribbon, show it on Home, bump version.
p=root/'app/src/main/assets/app.js'; s=p.read_text()
s=s.replace("function applyTheme() {\n  document.body.classList.toggle('dark', prefs.theme === 'dark' || (prefs.theme === 'system' && matchMedia('(prefers-color-scheme: dark)').matches));\n  document.documentElement.style.setProperty('--reader-font', prefs.font + 'px');\n}","function applyTheme() {\n  const dark = prefs.theme === 'dark' || (prefs.theme === 'system' && matchMedia('(prefers-color-scheme: dark)').matches);\n  document.body.classList.toggle('dark', dark);\n  document.documentElement.style.setProperty('--reader-font', prefs.font + 'px');\n  if (window.Native?.systemTheme) Native.systemTheme(dark);\n}",1)
anchor="function topTabs() {"
ribbon="""function authorRibbon() {
  const writers = state.authors.filter(a => a && a.name).slice(0, 8);
  if (!writers.length) return `<section class="author-ribbon author-ribbon-loading" aria-label="Creed Times writers"><div class="voices-label"><span>Voices</span><small>Writers & contributors</small></div><div class="author-scroll"><span class="author-chip ghost"></span><span class="author-chip ghost"></span><span class="author-chip ghost"></span></div></section>`;
  return `<section class="author-ribbon" aria-label="Creed Times writers"><div class="voices-label"><span>Voices</span><small>Writers & contributors</small></div><div class="author-scroll">${writers.map(a => `<button class="author-chip" data-filter="author" data-id="${a.id}" aria-label="View articles by ${esc(a.name)}">${safeURL(a.avatar_urls?.['96'])?`<img src="${esc(safeURL(a.avatar_urls['96']))}" alt="">`:`<span class="author-chip-initial">${esc(a.name.slice(0,1).toUpperCase())}</span>`}<span>${esc(a.name)}</span></button>`).join('')}</div></section>`;
}

"""
if anchor not in s: raise SystemExit('missing topTabs anchor')
s=s.replace(anchor,ribbon+anchor,1)
s=s.replace("$('#app').innerHTML = `<div class=\"shell\">${header()}${html}${bottomNav()}</div>`;","$('#app').innerHTML = `<div class=\"shell\">${header()}${state.nav==='home'?authorRibbon():''}${html}${bottomNav()}</div>`;",1)
s=s.replace('Creed Times Android · v1.2','Creed Times Android · v1.3')
p.write_text(s)

# CSS: dedicated safe zone and editorial author carousel.
p=root/'app/src/main/assets/style.css'; s=p.read_text()
s=s.replace(':root{',':root{--system-top:30px;',1)
s=s.replace('.shell{max-width:760px;margin:auto;min-height:100dvh;padding-bottom:96px}', '.shell{max-width:760px;margin:auto;min-height:100dvh;padding-top:var(--system-top);padding-bottom:96px}',1)
s=s.replace('.header{position:sticky;top:0;','.header{position:sticky;top:var(--system-top);',1)
s=s.replace('.top-tabs-wrap{position:sticky;top:69px;','.top-tabs-wrap{position:sticky;top:calc(var(--system-top) + 69px);',1)
s=s.replace('.top-tabs-wrap{top:61px;','.top-tabs-wrap{top:calc(var(--system-top) + 61px);',1)
s=s.replace('.reader-top{position:sticky;top:0;','.reader-top{position:sticky;top:var(--system-top);',1)
s=s.replace('.top-tabs-wrap{top:57px}', '.top-tabs-wrap{top:calc(var(--system-top) + 57px)}',1)
marker='.top-tabs button.active::after{background:var(--orange);transform:scaleX(1)}'
extra='''.author-ribbon{display:grid;grid-template-columns:auto minmax(0,1fr);align-items:center;gap:14px;padding:10px 18px 11px;background:var(--surface);border-bottom:1px solid color-mix(in srgb,var(--line) 78%,transparent)}
.voices-label{display:flex;flex-direction:column;gap:1px;min-width:58px}.voices-label span{font-size:9px;line-height:1.1;font-weight:900;letter-spacing:1.45px;text-transform:uppercase;color:var(--orange)}.voices-label small{font-size:8.5px;line-height:1.25;color:var(--muted);white-space:nowrap}
.author-scroll{display:flex;gap:9px;overflow-x:auto;scrollbar-width:none;padding:1px 1px 2px}.author-scroll::-webkit-scrollbar{display:none}
.author-chip{display:flex;align-items:center;gap:7px;min-width:max-content;max-width:150px;padding:5px 10px 5px 5px;border:1px solid color-mix(in srgb,var(--line) 85%,transparent);border-radius:999px;background:var(--surface-2);color:var(--ink);box-shadow:0 4px 12px rgba(8,47,79,.035);font-size:10.5px;font-weight:760;transition:.16s ease}.author-chip:active{transform:scale(.98)}.author-chip img,.author-chip-initial{width:28px;height:28px;flex:0 0 28px;border-radius:50%;object-fit:cover}.author-chip-initial{display:flex;align-items:center;justify-content:center;background:linear-gradient(145deg,var(--navy-2),var(--navy));color:#fff;font-size:11px;font-weight:850}.author-chip>span:last-child{overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.author-chip.ghost{width:98px;height:38px;display:block;background:linear-gradient(110deg,var(--surface-2),color-mix(in srgb,var(--surface) 88%,var(--surface-2)),var(--surface-2));background-size:200% 100%;animation:pulse 1.5s infinite;border-color:transparent}
'''
if marker not in s: raise SystemExit('missing tabs css marker')
s=s.replace(marker,marker+extra,1)
s+='''\n/* v1.3 — Android status-bar safe zone + editorial writer strip */\n@media(max-width:420px){.author-ribbon{grid-template-columns:52px minmax(0,1fr);gap:8px;padding-inline:14px}.voices-label small{display:none}.author-chip{max-width:132px;padding-right:9px;font-size:10px}.author-chip img,.author-chip-initial{width:27px;height:27px;flex-basis:27px}}\n'''
p.write_text(s)

# Android: use real system insets but convert physical px to CSS px; theme system bars with app theme.
p=root/'app/src/main/java/com/creedtimes/app/MainActivity.java'; s=p.read_text()
s=s.replace('private final ExecutorService pool = Executors.newFixedThreadPool(3);','private final ExecutorService pool = Executors.newFixedThreadPool(3);\n    private int systemTopInsetPx = 30;',1)
s=s.replace('getWindow().setStatusBarColor(0xff08385f);','getWindow().setStatusBarColor(0xfff5f7fa);',1)
s=s.replace('            v.setPadding(left, top, right, bottom);\n            return insets;','            systemTopInsetPx = Math.max(top, dp(24));\n            v.setPadding(left, 0, right, bottom);\n            applySystemInset();\n            return insets;',1)
s=s.replace('            @Override public boolean shouldOverrideUrlLoading(WebView w, WebResourceRequest r) {','            @Override public void onPageFinished(WebView w, String url) {\n                super.onPageFinished(w, url);\n                applySystemInset();\n            }\n            @Override public boolean shouldOverrideUrlLoading(WebView w, WebResourceRequest r) {',1)
s=s.replace('CreedTimesAndroid/1.1','CreedTimesAndroid/1.3')
s=s.replace('    private void js(String code) { runOnUiThread(() -> { if (!isFinishing() && web != null) web.evaluateJavascript(code, null); }); }\n    private void toast(String msg) {','    private void js(String code) { runOnUiThread(() -> { if (!isFinishing() && web != null) web.evaluateJavascript(code, null); }); }\n    private int dp(float value) { return Math.round(value * getResources().getDisplayMetrics().density); }\n    private void applySystemInset() {\n        float density = getResources().getDisplayMetrics().density;\n        final int topCssPx = Math.max(24, Math.round(systemTopInsetPx / Math.max(1f, density)));\n        js("document.documentElement.style.setProperty(\\\'--system-top\\\',\\\'" + topCssPx + "px\\\')");\n    }\n    private void toast(String msg) {',1)
bridge='''        @JavascriptInterface public void systemTheme(boolean dark) {
            runOnUiThread(() -> {
                int bg = dark ? 0xff061522 : 0xfff5f7fa;
                getWindow().setStatusBarColor(bg); getWindow().setNavigationBarColor(bg);
                if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.R) {
                    WindowInsetsController controller = getWindow().getInsetsController();
                    if (controller != null) {
                        int mask = WindowInsetsController.APPEARANCE_LIGHT_STATUS_BARS | WindowInsetsController.APPEARANCE_LIGHT_NAVIGATION_BARS;
                        controller.setSystemBarsAppearance(dark ? 0 : mask, mask);
                    }
                } else if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.O) {
                    getWindow().getDecorView().setSystemUiVisibility(dark ? 0 : (View.SYSTEM_UI_FLAG_LIGHT_STATUS_BAR | View.SYSTEM_UI_FLAG_LIGHT_NAVIGATION_BAR));
                } else if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.M) {
                    getWindow().getDecorView().setSystemUiVisibility(dark ? 0 : View.SYSTEM_UI_FLAG_LIGHT_STATUS_BAR);
                }
            });
        }
'''
s=s.replace('        @JavascriptInterface public void open(String url) { openExternal(url); }',bridge+'        @JavascriptInterface public void open(String url) { openExternal(url); }',1)
p.write_text(s)

# Version and theme defaults.
rep('app/build.gradle', "versionCode 3\n        versionName '1.2.0'", "versionCode 4\n        versionName '1.3.0'")
p=root/'app/src/main/res/values/styles.xml'; s=p.read_text().replace('#08385F</item>','#F5F7FA</item>').replace('<item name="android:windowLightStatusBar">false</item>','<item name="android:windowLightStatusBar">true</item>').replace('<item name="android:windowLightNavigationBar">false</item>','<item name="android:windowLightNavigationBar">true</item>'); p.write_text(s)
