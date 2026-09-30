<?php
/*
Template Name: Creed Times App
*/
get_header();
$app_url = ct_app_download_url();
?>
<main class="ctv-page ctv-app-page">
	<section class="ctv-page-hero">
		<div class="ct-container ctv-app-hero">
			<div>
				<span class="ctv-kicker">CREED TIMES APP</span>
				<h1>Creed Times,<br>on your phone.</h1>
				<p>Read the latest stories, watch Creed Times media and keep the newsroom close on Android.</p>
				<?php if ( $app_url ) : ?>
					<a class="ctv-primary-action" href="<?php echo esc_url( $app_url ); ?>" target="_blank" rel="noopener">Download Android App v<?php echo esc_html( ct_app_version() ); ?> ↓</a>
				<?php else : ?>
					<span class="ctv-app-pending">App download will appear here as soon as the APK is added in Creed Times settings.</span>
				<?php endif; ?>
			</div>
			<div class="ctv-app-device"><div><span>CT</span><strong>CREED TIMES</strong><small>News · Analysis · Perspective</small></div></div>
		</div>
	</section>
</main>
<?php get_footer(); ?>