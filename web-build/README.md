# Creed Times 2.1 — WordPress Rebuild

This build is based on the Creed Times 2.0/2.1 master brief and the WordPress/WXR structure supplied for creedtimes.com.

## Packages
- **Theme:** `creed-times-2`
- **Core plugin:** `creed-times-core`
- **HTML previews:** Homepage, Article, Profile

## Theme includes
- Brand-new responsive homepage
- Modern sticky header with search, social links and WhatsApp
- Blue + orange Creed Times design system
- Light / dark mode
- Homepage hero + Trending Now
- The Spike horizontal story section
- Latest Stories cards
- Featured Video panel
- Authors & Contributors
- In Focus / Analysis / Urdu Desk / Podcasts
- Creed Pro CTA
- Article reading layout with breadcrumbs, reading time, reading progress, share, save, audio, Key Points, Sources, author box, related stories and comments
- Author profile pages
- Search with filters
- User profile template
- Creed Pro page
- Video / Shorts / Podcast archives
- Mobile bottom navigation

## Creed Times Core includes
- Videos, Shorts, Podcasts and Visual Stories
- Language / Editorial Format / Region / Topic taxonomies
- Free / Creed Pro access
- Editor's Pick / Trending / Developing Story
- Audio / Video / Show / Duration fields
- Key Points and Sources
- Bookmarks / Save for Later
- Private Notes
- Reading History
- Follow Authors
- Member Dashboard
- Paid Memberships Pro integration
- Modern WordPress login branding
- Safe taxonomy migration tool

## Safe migration
The migration tool is deliberately non-destructive.

It does **not delete** existing posts, pages, authors, images, media, original categories, original tags, publish dates, slugs or URLs.

Current legacy mapping:
- Urdu → Language: Urdu
- Roman Urdu → Language: Urdu + Roman Urdu metadata
- West Asia → Region: West Asia
- World → Region: World
- News → Format: News
- Analysis → Format: Analysis
- Documentaries → Format: Documentary
- Politics → Topic: Pakistan Politics
- Religion → Topic: Religion & Society
- Art → Topic: Media
- For Subscribers → Creed Pro, when the legacy taxonomy exists

Obvious Glossier/demo tags such as beauty/vogue/tips/style are marked as legacy and hidden by the new front-end instead of being permanently deleted.

## Installation order
1. Take a fresh Hostinger full backup.
2. Plugins → Add New → Upload Plugin → upload `creed-times-core-2.0.0.zip`.
3. Activate it.
4. WordPress → Creed Times → run **Safe Creed Times Migration**.
5. If Paid Memberships Pro is being used, add the Creed Pro PMPro level ID in Creed Times settings.
6. Appearance → Themes → Add New → Upload Theme → upload `creed-times-2.1.0.zip`.
7. Activate the theme.
8. Set the official Blue + Orange Creed Times logo under Site Identity.
9. Set the official CT monogram as the WordPress Site Icon / favicon.
10. The 2.1 header uses its own clean Creed Times navigation so old Glossier/WooCommerce menu items cannot leak into the header.
11. Check homepage, article, author, profile, search and Urdu pages.
12. Clear LiteSpeed Cache and browser cache after replacing the theme.

## Logo note
The theme intentionally does not redraw your logo. WordPress uses the official logo uploaded under Site Identity, which avoids replacing your real mark with an approximation.

## Existing content preservation
The theme and plugin render existing WordPress content dynamically. The migration enriches existing content with new editorial taxonomies rather than rebuilding the database from scratch.

## Preview
Open the HTML files inside `web-build/preview/` before activation to review the visual direction.


## 2.1 homepage fix
Version 2.1 replaces the first homepage/header direction with a lighter, cleaner newsroom UI.

Key fixes:
- removes the old WordPress-assigned menu from the visual header, preventing Checkout / Membership / Shop pages from appearing as a bullet list
- one controlled modern navigation
- lighter white / soft-gray design
- compact Newsroom update strip
- split featured story card instead of an oversized dark hero
- Latest Pulse panel
- The Spike carousel
- latest story grid + Popular Now
- cleaner author cards
- Analysis & Perspective area
- media area
- Urdu desk
- refined responsive mobile header

When replacing an existing 2.0 theme, WordPress may show “Replace current with uploaded”. Use that option, then purge LiteSpeed Cache.
