# Creed Times Reader 1.1

Android reader app for CreedTimes.com, application ID `com.creedtimes.app`.
The Studio editor app remains a separate application.

Tabs: Latest, Articles, Videos, Podcasts and Artworks. Text-only editorial tab bar; Artworks uses a visual gallery. Categories/tags named Article(s) feed Articles. If absent, Articles excludes known Video, Podcast and Art taxonomies. Art/Artwork(s)/Gallery taxonomies feed Artworks. Other features: authors, search, saved offline text, favorites, TTS, share, contact, theme and reading settings.

The site must expose the standard WordPress public REST endpoints. Normal published posts are supported. Custom post types require mapping. App refreshes at launch, resume after 5 minutes, manually, and periodically while the latest screen is active. No background push service. Media and images require internet; Urdu TTS depends on the device voice engine.

Build: Java 17, Android SDK 35, Gradle 8.9. `gradle :app:assembleDebug :app:lintDebug`.
CI: repository root `.github/workflows/reader-apk.yml`. Artifacts include a signed debug APK and test screenshots. A debug APK is for direct installation/testing, not a Play Store release. Retain a dedicated release signing key before public release. Do not commit secret keys.

Tests: `node tests/logic.cjs`; `npm install --no-save playwright@1.56.1 && npx playwright install chromium && node tests/ui.cjs`. UI tests use explicitly synthetic fixtures. `tests/probe-site.py` performs read-only public API diagnostics.
