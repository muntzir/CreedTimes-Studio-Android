<?php
/*
Template Name: Creed Pro
*/
get_header();
?>
<main class="ct-pro-page">
	<section class="ct-pro-hero">
		<div class="ct-container ct-pro-hero__inner">
			<div><span class="ct-overline">CREED PRO</span><h1>Read deeper.<br>Keep what matters.</h1><p>Premium journalism plus a focused personal reading workspace for Creed Times members.</p><a class="ct-primary-btn" href="<?php echo esc_url( function_exists( 'pmpro_url' ) ? pmpro_url( 'levels' ) : home_url( '/membership-levels/' ) ); ?>">Join Creed Pro →</a></div>
			<div class="ct-pro-feature-grid"><article><strong>Premium Analysis</strong><p>Access selected member-only analysis and long reads.</p></article><article><strong>Save & Read Later</strong><p>Build a personal library across articles and media.</p></article><article><strong>Private Notes</strong><p>Add private notes while researching and reading.</p></article><article><strong>Premium Media</strong><p>Unlock selected video and podcast content.</p></article></div>
		</div>
	</section>
</main>
<?php get_footer(); ?>
