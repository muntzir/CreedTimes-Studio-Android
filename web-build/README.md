# Creed Times 2.3 — WordPress Rebuild

Creed Times 2.3 is a custom WordPress theme + newsroom core plugin built around the supplied Creed Times WordPress/WXR structure and the agreed 2026 editorial design system.

## Installable files

- `creed-times-2.3.0.zip` — Creed Times theme
- `creed-times-core-2.3.0.zip` — editorial/backend plugin
- `creed-times-2.3-complete-package.zip` — both packages + this guide

## What 2.3 fixes

### Front-end
- Clean Blue + Orange Creed Times identity
- Modern light newsroom interface + dark mode
- Header WhatsApp button renamed to **Join Channel**
- Professional WhatsApp / Facebook / Instagram / YouTube icons
- Urdu navigation appears only when Urdu content exists
- Modern About Us page; old standalone HTML/CSS no longer controls the design
- Working Authors directory
- Working Contribute / Write for Us page + pitch form
- Working Contact page with:
  - info@creedtimes.com
  - +92 308 3829035
  - WhatsApp Channel
  - WhatsApp Group
  - Facebook
  - Instagram
  - YouTube
- Working Profile page and member dashboard
- Mobile App page with safe APK-download support
- Footer Media / People / Account / social sections rebuilt
- Author fallback role is **Author**, not Contributor
- Professional article share rail and bottom share panel
- WhatsApp, Facebook, X, LinkedIn, Telegram, Copy Link, and native mobile share (Instagram / other apps)
- Old full-document article HTML is normalized so legacy <head>, <style>, <script> and page chrome cannot override the new article design
- Large responsive source images used in story cards and media archives

### Backend / editorial structure
Creed Times Core creates one newsroom menu with:
- Articles
- Videos
- Reels / Shorts
- Podcasts
- Visual Stories
- Authors
- Editorial Structure
- Settings

Editorial taxonomies:
- Language: English / Urdu
- Editorial Format: News / Analysis / Opinion / Explainer / Interview / Long Read / Documentary
- Region: Pakistan / West Asia / World
- Topic: flexible editorial topics
- Access: Free / Creed Pro

Editorial fields:
- Free / Creed Pro
- Editor's Pick
- Trending / The Spike
- Developing Story
- Audio Article URL
- Video URL
- Show / Program Name
- Duration
- Key Points
- Sources & References

### Safe editorial cleanup
The cleanup is non-destructive. It keeps:
- posts
- pages
- authors
- images/media
- publish dates
- slugs
- URLs
- original legacy taxonomy data

It additionally:
- maps Urdu / Roman Urdu → Language: Urdu
- maps West Asia / World / Pakistan → Region
- maps News / Analysis / Documentary / Opinion / Interview / Explainer → Editorial Format
- maps Politics → Pakistan Politics
- maps Religion → Religion & Society
- maps Art → Media
- maps old Video / Podcast post-template records into normalized content kinds
- maps old For Subscribers items → Creed Pro
- turns meaningful repeated tags into Topics
- marks known Glossier/demo tags as legacy so the new front-end hides them
- removes Uncategorized from a post only when a real category is already assigned

### YouTube auto-sync
Creed Times Core:
- uses https://www.youtube.com/@creedtimes
- resolves the channel ID automatically when possible
- reads the official YouTube RSS feed
- creates / updates Video cards in WordPress
- stores the YouTube URL
- prefers max-resolution YouTube thumbnails, with SD/HQ fallbacks
- runs hourly via WP-Cron
- also provides **Creed Times → Dashboard → Sync YouTube Now**

If automatic channel-ID discovery is blocked by the host, paste the YouTube Channel ID under Creed Times → Settings.

## Exact logo + favicon

The theme does not deliberately redraw the official mark.

Set:
- **Custom Logo** = the exact Creed Times full wordmark PNG
- **Site Icon / favicon** = the exact CT monogram

The same custom logo is used throughout the theme. In dark mode it is automatically rendered as a white logo treatment.

If those exact files already exist in the WordPress Media Library with Creed Times / monogram names, Creed Times Core attempts to detect them automatically.

## Android app

The latest available Creed Times app is v1.7.0.

To publish the app on the website:
1. With Creed Times Core 2.3 active, open WordPress Media → Add New.
2. Upload `CreedTimes-v1.7.apk`.
3. The plugin allows APK MIME uploads and attempts to detect CreedTimes APK files automatically.
4. Or copy the uploaded Media URL into **Creed Times → Settings → Android APK download URL**.
5. The site App page and footer download link will then use the real URL.

No fake or temporary ChatGPT URL is hardcoded into the website.

## Installation / update order

1. Take a fresh full Hostinger backup.
2. WordPress → Plugins → Add New → Upload Plugin.
3. Upload `creed-times-core-2.3.0.zip`.
4. If WordPress asks, choose **Replace current with uploaded**.
5. Activate / keep Creed Times Core active.
6. Go to **Creed Times → Editorial Structure**.
7. Click **Run Safe Editorial Cleanup** once.
8. Go to **Creed Times → Settings** and confirm:
   - email
   - WhatsApp / phone
   - YouTube URL
   - app URL/version when uploaded
   - PMPro Creed Pro level ID if required
9. Go to **Creed Times → Dashboard → Sync YouTube Now** once.
10. Appearance → Themes → Add New → Upload Theme.
11. Upload `creed-times-2.3.0.zip`.
12. Choose **Replace current with uploaded**, then activate/keep active.
13. Appearance / Site Identity:
    - set exact Creed Times full logo
    - set CT monogram as Site Icon
14. Settings → Permalinks → click **Save Changes** once.
15. LiteSpeed Cache → Toolbox → Purge → **Purge All**.
16. Browser hard refresh: Ctrl + F5.

## Pages maintained automatically

Creed Times Core makes sure these pages exist and use the new theme templates:
- /about-us/
- /authors/
- /contribute/
- /contact/
- /profile/
- /creed-pro/
- /app/

This is intentional: old Elementor or standalone HTML on those pages should not override the new Creed Times interface.

## Existing content preservation

Do not delete/import the old site again after installing 2.3. The new theme and plugin use the current WordPress database and preserve the existing content in place.
