package com.creedtimes.app;
import android.app.Activity;
import android.os.Bundle;
import android.webkit.*;
import android.content.*;
import android.net.Uri;
import android.widget.*;
import android.view.ViewGroup;

/** Isolated player: remote pages never receive the native JavaScript bridge. */
public class MediaActivity extends Activity {
 private WebView player;
 @Override public void onCreate(Bundle state) {
  super.onCreate(state);
  String url = getIntent().getStringExtra("url");
  if(url == null || !url.startsWith("https://")) { finish(); return; }
  LinearLayout layout = new LinearLayout(this); layout.setOrientation(LinearLayout.VERTICAL); layout.setOnApplyWindowInsetsListener((v, insets) -> {
   v.setPadding(insets.getSystemWindowInsetLeft(), insets.getSystemWindowInsetTop(), insets.getSystemWindowInsetRight(), insets.getSystemWindowInsetBottom());
   return insets.consumeSystemWindowInsets();
  });
  Button close = new Button(this); close.setText("← Back to Creed Times"); close.setOnClickListener(v -> finish()); layout.addView(close);
  player = new WebView(this); player.getSettings().setJavaScriptEnabled(true); player.getSettings().setDomStorageEnabled(true); player.getSettings().setAllowFileAccess(false); player.getSettings().setAllowContentAccess(false); player.getSettings().setMixedContentMode(WebSettings.MIXED_CONTENT_NEVER_ALLOW);
  player.setWebChromeClient(new WebChromeClient());
  player.setWebViewClient(new WebViewClient() {
   @Override public boolean shouldOverrideUrlLoading(WebView v, WebResourceRequest r) { return !"https".equals(r.getUrl().getScheme()); }
  });
  layout.addView(player,new LinearLayout.LayoutParams(ViewGroup.LayoutParams.MATCH_PARENT,0,1)); setContentView(layout);
  String path = Uri.parse(url).getPath();
  if (path != null && path.toLowerCase(java.util.Locale.ROOT).matches(".*\\.(mp3|m4a|ogg|wav|mp4|webm)$")) {
   String tag = path.toLowerCase(java.util.Locale.ROOT).matches(".*\\.(mp3|m4a|ogg|wav)$") ? "audio" : "video";
   String html = "<html><head><meta name='viewport' content='width=device-width,initial-scale=1'></head><body style='background:#082d4b;color:white;font-family:sans-serif;padding:20px'><h2>Creed Times</h2><" + tag + " controls style='width:100%' src='" + android.text.TextUtils.htmlEncode(url) + "'></" + tag + "></body></html>";
   player.loadDataWithBaseURL("https://creedtimes.com/", html, "text/html", "UTF-8", null);
  } else player.loadUrl(url);
 }
 @Override protected void onPause(){super.onPause();player.onPause();}
 @Override protected void onResume(){super.onResume();if(player!=null)player.onResume();}
 @Override protected void onDestroy(){player.destroy();super.onDestroy();}
}
