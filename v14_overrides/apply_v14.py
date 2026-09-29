from pathlib import Path
import sys

root = Path(sys.argv[1])

def must_replace(path, old, new, count=1):
    p = root / path
    s = p.read_text()
    if old not in s:
        raise SystemExit(f"Missing pattern in {path}: {old[:120]}")
    p.write_text(s.replace(old, new, count))

p = root/'app/src/main/assets/app.js'
s = p.read_text()

s = s.replace("nav:'home', tab:'latest',", "nav:'home', tab:'updates',", 1)

s = s.replace("const tabs = [\n  ['latest','latest','Latest'],",
"""const tabs = [
  ['updates','latest','News Updates'],
  ['latest','latest','Latest'],""", 1)

s = s.replace("function empty(heading, description, action='') {",
"""function updatesBanner() {
  if (state.tab !== 'updates') return '';
  return `<section class="updates-banner">
    <div class="updates-live"><span class="live-dot"></span><span>LIVE NEWS</span></div>
    <div class="updates-copy"><strong>Creed Times News Updates</strong><small>Auto-refreshes while the app is open</small></div>
    <a class="updates-channel" href="https://whatsapp.com/channel/0029Vac8cT7AInPgxXePRw2K" aria-label="Open Creed Times WhatsApp Channel">${icon('whatsapp')}<span>Channel</span></a>
  </section>`;
}

function empty(heading, description, action='') {""", 1)

s = s.replace("return ({latest:'Latest stories',articles:'Articles & analysis',video:'Watch now',audio:'Listen now',art:'Artworks & visual stories'})[state.tab] || 'Latest stories';",
"return ({updates:'News updates',latest:'Latest stories',articles:'Articles & analysis',video:'Watch now',audio:'Listen now',art:'Artworks & visual stories'})[state.tab] || 'Latest stories';", 1)

s = s.replace("return ({latest:'Fresh from Creed Times',articles:'Long reads, opinion and analysis',video:'Video reports and conversations',audio:'Podcasts and audio programmes',art:'Visual work from the newsroom'})[state.tab] || 'Fresh from Creed Times';",
"return ({updates:'Breaking, developing and latest headlines',latest:'Fresh from Creed Times',articles:'Long reads, opinion and analysis',video:'Video reports and conversations',audio:'Podcasts and audio programmes',art:'Visual work from the newsroom'})[state.tab] || 'Fresh from Creed Times';", 1)

s = s.replace("html = `${topTabs()}<main class=\"content home-content\"><section class=\"section-title\">",
"html = `${topTabs()}<main class=\"content home-content\">${updatesBanner()}<section class=\"section-title\">", 1)

s = s.replace("state.error?'Check the connection and try again. Your saved stories are still available offline.':`Publish a post in the matching ${state.tab==='art'?'Art / Artwork':state.tab==='audio'?'Podcast / Audio':state.tab==='video'?'Video':'article'} category or tag on CreedTimes.com.`",
"state.error?'Check the connection and try again. Your saved stories are still available offline.':`Publish a post in the matching ${state.tab==='updates'?'News / News Update / Breaking News':state.tab==='art'?'Art / Artwork':state.tab==='audio'?'Podcast / Audio':state.tab==='video'?'Video':'article'} category or tag on CreedTimes.com.`", 1)

s = s.replace("const rules = {\n    video:", """const rules = {
    updates: /(^|[-\\s_])(news|breaking|breaking-news|headline|headlines|update|updates|news-update|news-updates|latest-news|live)([-\\s_]|$)|خبر|خبریں|تازہ|بریکنگ/i,
    video:""", 1)

s = s.replace("if (['video','audio','art'].includes(state.tab)) {",
"""if (state.tab === 'updates') {
    const ids = matchingIds('updates');
    // Graceful fallback: if the site has not yet created a dedicated news/update taxonomy,
    // the live tab still behaves as a fast-refresh latest-news feed.
    if (ids.cats.length) q.set('categories', ids.cats.join(','));
    if (ids.tags.length) q.set('tags', ids.tags.join(','));
    if (ids.cats.length && ids.tags.length) q.set('tax_relation', 'OR');
  } else if (['video','audio','art'].includes(state.tab)) {""", 1)

s = s.replace("setInterval(() => { if(!document.hidden&&!state.article&&state.nav==='home'&&!state.loading&&scrollY<200) load(); }, 300000);",
"""setInterval(() => {
  if (document.hidden || state.article || state.nav !== 'home' || state.loading || scrollY >= 200) return;
  const interval = state.tab === 'updates' ? 60000 : 300000;
  if (!state.sync || Date.now() - state.sync >= interval) load();
}, 30000);""", 1)

s = s.replace("document.addEventListener('visibilitychange', () => { if(document.hidden) stopSpeech(); else if(!state.article&&state.nav==='home'&&(!state.sync||Date.now()-state.sync>300000)) load(); });",
"""document.addEventListener('visibilitychange', () => {
  if (document.hidden) { stopSpeech(); return; }
  if (!state.article && state.nav === 'home') {
    const interval = state.tab === 'updates' ? 60000 : 300000;
    if (!state.sync || Date.now() - state.sync > interval) load();
  }
});""", 1)

s = s.replace("Creed Times Android · v1.3", "Creed Times Android · v1.4")
p.write_text(s)

p = root/'app/src/main/assets/style.css'
s = p.read_text()
s += r'''
/* v1.4 — live news updates */
.updates-banner{display:grid;grid-template-columns:auto minmax(0,1fr) auto;align-items:center;gap:10px;margin:0 0 17px;padding:12px 13px;border:1px solid color-mix(in srgb,var(--orange) 18%,var(--line));border-radius:18px;background:linear-gradient(135deg,color-mix(in srgb,var(--orange-soft) 72%,var(--surface)),var(--surface));box-shadow:0 7px 22px rgba(8,47,79,.05)}
.updates-live{display:flex;align-items:center;gap:6px;padding:6px 8px;border-radius:999px;background:var(--surface);border:1px solid color-mix(in srgb,var(--orange) 24%,var(--line));font-size:8px;font-weight:900;letter-spacing:1px;color:var(--orange);white-space:nowrap}
.live-dot{width:7px;height:7px;border-radius:50%;background:var(--orange);box-shadow:0 0 0 0 rgba(255,102,22,.35);animation:livePulse 1.8s infinite}
.updates-copy{min-width:0}.updates-copy strong,.updates-copy small{display:block}.updates-copy strong{font-size:12px;color:var(--ink)}.updates-copy small{margin-top:2px;font-size:9.5px;color:var(--muted)}
.updates-channel{display:flex;align-items:center;gap:5px;min-height:36px;padding:0 10px;border:1px solid var(--line);border-radius:12px;background:var(--surface);color:var(--ink);font-size:9.5px;font-weight:800;text-decoration:none}.updates-channel svg{width:16px;height:16px;color:#25D366}
@keyframes livePulse{0%{box-shadow:0 0 0 0 rgba(255,102,22,.35)}70%{box-shadow:0 0 0 7px rgba(255,102,22,0)}100%{box-shadow:0 0 0 0 rgba(255,102,22,0)}}
@media(max-width:420px){.updates-banner{grid-template-columns:auto minmax(0,1fr);gap:8px}.updates-channel{grid-column:1/-1;justify-content:center}.updates-copy strong{font-size:11.5px}}
'''
p.write_text(s)

must_replace('app/build.gradle', "versionCode 4\n        versionName '1.3.0'", "versionCode 5\n        versionName '1.4.0'")
