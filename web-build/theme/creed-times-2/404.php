<?php get_header(); ?>
<main class="ct-section">
	<div class="ct-container">
		<div class="ct-empty-state">
			<span class="ct-overline">404</span>
			<h1>That page isn’t here.</h1>
			<p>Try searching Creed Times or return to the newsroom.</p>
			<p><a class="ct-primary-btn" href="<?php echo esc_url( home_url( '/' ) ); ?>">Back to Home</a></p>
		</div>
	</div>
</main>
<?php get_footer(); ?>