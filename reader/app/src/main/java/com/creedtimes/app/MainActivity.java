package com.creedtimes.app;

import android.app.Activity;
import android.content.*;
import android.net.Uri;
import android.os.*;
import android.speech.tts.*;
import android.webkit.*;
import android.view.*;
import android.widget.Toast;
import org.json.JSONObject;
import java.net.*;
import java.io.*;
import java.nio.charset.StandardCharsets;
import java.util.*;
import java.util.concurrent.*;

public class MainActivity extends Activity {
    private WebView web;
    private TextToSpeech tts;
    private boolean ttsReady = false;
    private final ExecutorService pool = Executors.newFixedThreadPool(3);
    private static final String ORIGIN = "https://app.creedtimes.local/";
    @Override public void onCreate(Bundle state) {
        super.onCreate(state);
        getWindow().setStatusBarColor(0xff082d4b);
        getWindow().setNavigationBarColor(0xff082d4b);
        web = new WebView(this);
        web.setBackgroundColor(0xfff5f6f8);
        web.setOnApplyWindowInsetsListener((v, insets) -> {
            v.setPadding(insets.getSystemWindowInsetLeft(), insets.getSystemWindowInsetTop(), insets.getSystemWindowInsetRight(), insets.getSystemWindowInsetBottom());
            return insets.consumeSystemWindowInsets();
        });
        setContentView(web);
        web.getSettings().setJavaScriptEnabled(true);
        web.getSettings().setDomStorageEnabled(true);
        web.getSettings().setAllowFileAccess(false);
        web.getSettings().setAllowContentAccess(false);
        web.getSettings().setMixedContentMode(WebSettings.MIXED_CONTENT_NEVER_ALLOW);
        web.addJavascriptInterface(new Bridge(), "Native");
        web.setWebViewClient(new WebViewClient() {
            @Override public WebResourceResponse shouldInterceptRequest(WebView w, WebResourceRequest r) {
                String url = r.getUrl().toString();
                if (!url.startsWith(ORIGIN)) return null;
                String name = r.getUrl().getPath().substring(1);
                if (!Arrays.asList("index.html", "app.js", "style.css", "monogram.png", "wordmark.png").contains(name)) return new WebResourceResponse("text/plain", "UTF-8", new ByteArrayInputStream(new byte[0]));
                try {
                    String mime = name.endsWith(".js") ? "application/javascript" : name.endsWith(".css") ? "text/css" : name.endsWith(".png") ? "image/png" : "text/html";
                    return new WebResourceResponse(mime, "UTF-8", getAssets().open(name));
                } catch (IOException e) { return new WebResourceResponse("text/plain", "UTF-8", new ByteArrayInputStream(new byte[0])); }
            }
            @Override public boolean shouldOverrideUrlLoading(WebView w, WebResourceRequest r) {
                if (r.getUrl().toString().equals(ORIGIN + "index.html")) return false;
                openExternal(r.getUrl().toString()); return true;
            }
        });
        tts = new TextToSpeech(this, status -> ttsReady = status == TextToSpeech.SUCCESS);
        tts.setOnUtteranceProgressListener(new UtteranceProgressListener() {
            public void onStart(String id) {}
            public void onDone(String id) { if (id.endsWith("-last")) js("window.speechEnded && window.speechEnded()"); }
            public void onError(String id) { js("window.speechEnded && window.speechEnded()"); }
        });
        web.loadUrl(ORIGIN + "index.html");
    }
    private void js(String code) { runOnUiThread(() -> { if (!isFinishing() && web != null) web.evaluateJavascript(code, null); }); }
    private void toast(String msg) { runOnUiThread(() -> Toast.makeText(this, msg, Toast.LENGTH_LONG).show()); }
    private void openExternal(String url) {
        Uri uri = Uri.parse(url);
        if (!Arrays.asList("https", "mailto").contains(uri.getScheme())) return;
        runOnUiThread(() -> { try { startActivity(new Intent(Intent.ACTION_VIEW, uri)); } catch (ActivityNotFoundException e) { toast("No app available to open this link."); } });
    }
    public class Bridge {
        @JavascriptInterface public void request(String id, String route) {
            if (!id.matches("[0-9]+") || !route.matches("(posts|categories|tags|users|post_template)(\\?[a-zA-Z0-9_=&%.*,+\\-]*)?")) return;
            pool.execute(() -> {
                HttpURLConnection c = null;
                try {
                    URL url = new URL("https://creedtimes.com/wp-json/wp/v2/" + route);
                    c = (HttpURLConnection) url.openConnection();
                    c.setConnectTimeout(15000); c.setReadTimeout(20000); c.setInstanceFollowRedirects(false);
                    c.setRequestProperty("Accept", "application/json"); c.setRequestProperty("User-Agent", "CreedTimesAndroid/1.0");
                    int status = c.getResponseCode();
                    for (int redirects = 0; redirects < 3 && status >= 300 && status < 400; redirects++) {
                        String location = c.getHeaderField("Location");
                        if (location == null) break;
                        URL next = new URL(url, location);
                        if (!"https".equals(next.getProtocol()) || !("creedtimes.com".equals(next.getHost()) || "www.creedtimes.com".equals(next.getHost()))) throw new IOException("Unexpected website redirect");
                        c.disconnect(); url = next; c = (HttpURLConnection) url.openConnection();
                        c.setConnectTimeout(15000); c.setReadTimeout(20000); c.setInstanceFollowRedirects(false);
                        c.setRequestProperty("Accept", "application/json");
                        status = c.getResponseCode();
                    }
                    if (status != 200) throw new IOException("Website returned HTTP " + status);
                    ByteArrayOutputStream out = new ByteArrayOutputStream();
                    try (InputStream in = c.getInputStream()) {
                        byte[] buffer = new byte[8192]; int n;
                        while ((n = in.read(buffer)) != -1) { out.write(buffer, 0, n); if(out.size() > 12000000) throw new IOException("Response too large"); }
                    }
                    String body = out.toString("UTF-8");
                    js("window.nativeReply(" + JSONObject.quote(id) + "," + JSONObject.quote(body) + ",null)");
                } catch (Exception e) { js("window.nativeReply(" + JSONObject.quote(id) + ",null," + JSONObject.quote(e.getMessage() == null ? "Connection failed" : e.getMessage()) + ")"); }
                finally { if (c != null) c.disconnect(); }
            });
        }
        @JavascriptInterface public void open(String url) { openExternal(url); }
        @JavascriptInterface public void share(String title, String url) {
            if (!url.startsWith("https://")) return;
            runOnUiThread(() -> { Intent i = new Intent(Intent.ACTION_SEND); i.setType("text/plain"); i.putExtra(Intent.EXTRA_TEXT, title + "\n" + url); startActivity(Intent.createChooser(i, "Share story")); });
        }
        @JavascriptInterface public void media(String url) {
            if (!url.startsWith("https://")) return;
            runOnUiThread(() -> { tts.stop(); startActivity(new Intent(MainActivity.this, MediaActivity.class).putExtra("url", url)); });
        }
        @JavascriptInterface public void speak(String text, String language, float rate) {
            runOnUiThread(() -> {
                if (!ttsReady) { toast("Speech engine is not ready. Check Android text-to-speech settings."); js("window.speechEnded()"); return; }
                int support = tts.setLanguage(Locale.forLanguageTag(language));
                if (support < 0) { toast("This voice is unavailable. Install a compatible Urdu or English voice in Android settings."); js("window.speechEnded()"); return; }
                tts.stop(); tts.setSpeechRate(Math.max(.5f, Math.min(2f, rate)));
                int chunkSize = Math.min(3000, TextToSpeech.getMaxSpeechInputLength() - 1);
                for(int i = 0; i < text.length(); i += chunkSize) {
                    int end = Math.min(text.length(), i + chunkSize);
                    tts.speak(text.substring(i, end), TextToSpeech.QUEUE_ADD, null, "ct-" + i + (end == text.length() ? "-last" : ""));
                }
            });
        }
        @JavascriptInterface public void close() { runOnUiThread(() -> finish()); }
        @JavascriptInterface public void stop() { runOnUiThread(() -> tts.stop()); }
    }
    @Override public void onBackPressed() { js("window.goBack()"); }
    @Override protected void onPause() { super.onPause(); if (tts != null) tts.stop(); js("window.speechEnded && window.speechEnded()"); }
    @Override protected void onDestroy() { pool.shutdownNow(); if(tts != null) { tts.stop(); tts.shutdown(); } if(web != null) { web.removeJavascriptInterface("Native"); web.destroy(); web = null; } super.onDestroy(); }
}
