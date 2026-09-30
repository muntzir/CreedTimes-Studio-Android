<?php
/*
Template Name: Creed Times Profile
*/
get_header();
?>
<main class="ct-profile-page ct-section">
	<div class="ct-container">
		<?php if ( ! is_user_logged_in() ) : ?>
			<section class="ct-login-card">
				<span class="ct-overline">YOUR CREED TIMES</span>
				<h1>Save stories. Build your reading space.</h1>
				<p>Sign in to use bookmarks, private notes, reading history, followed authors and Creed Pro features.</p>
				<div class="ct-login-card__actions"><a href="<?php echo esc_url( wp_login_url( get_permalink() ) ); ?>">Sign in</a><a class="is-secondary" href="<?php echo esc_url( wp_registration_url() ); ?>">Create account</a></div>
			</section>
		<?php elseif ( shortcode_exists( 'ct_member_dashboard' ) ) : ?>
			<?php echo do_shortcode( '[ct_member_dashboard]' ); ?>
		<?php else : ?>
			<div class="ct-empty-state"><h2>Profile tools need Creed Times Core.</h2><p>Activate the Creed Times Core plugin to enable bookmarks, notes, follows and membership tools.</p></div>
		<?php endif; ?>
	</div>
</main>
<?php get_footer(); ?>
