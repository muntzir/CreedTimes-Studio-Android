<?php
/*
Template Name: Creed Times Profile
*/
get_header();
$user = is_user_logged_in() ? wp_get_current_user() : null;
?>
<main class="ctv-page ctv-profile-page">
	<section class="ctv-page-hero ctv-page-hero--profile">
		<div class="ct-container ctv-profile-hero">
			<div>
				<span class="ctv-kicker">YOUR CREED TIMES</span>
				<h1><?php echo $user ? 'Your reading space.' : 'Save what matters.'; ?></h1>
				<p><?php echo $user ? 'Bookmarks, private notes, reading history and followed authors in one focused place.' : 'Create a free Creed Times account to save stories, add private notes and build your personal reading library.'; ?></p>
			</div>
			<?php if ( $user ) : ?>
				<div class="ctv-profile-user"><?php echo get_avatar( $user->ID, 80 ); ?><div><strong><?php echo esc_html( $user->display_name ); ?></strong><span><?php echo function_exists( 'ct_core_user_has_pro' ) && ct_core_user_has_pro( $user->ID ) ? 'Creed Pro' : 'Free account'; ?></span></div></div>
			<?php endif; ?>
		</div>
	</section>

	<section class="ctv-page-section">
		<div class="ct-container">
			<?php if ( ! is_user_logged_in() ) : ?>
				<section class="ct-login-card">
					<h2>Sign in to continue.</h2>
					<p>Your saved stories and notes stay connected to your account.</p>
					<div class="ct-login-card__actions">
						<a href="<?php echo esc_url( wp_login_url( get_permalink() ) ); ?>">Sign in</a>
						<?php if ( get_option( 'users_can_register' ) ) : ?><a class="is-secondary" href="<?php echo esc_url( wp_registration_url() ); ?>">Create account</a><?php endif; ?>
					</div>
				</section>
			<?php elseif ( shortcode_exists( 'ct_member_dashboard' ) ) : ?>
				<?php echo do_shortcode( '[ct_member_dashboard]' ); ?>
			<?php else : ?>
				<div class="ct-empty-state"><h2>Creed Times Core is required.</h2><p>Activate the Creed Times Core plugin to enable bookmarks, notes, follows and membership tools.</p></div>
			<?php endif; ?>
		</div>
	</section>
</main>
<?php get_footer(); ?>