<?php
$ct_social  = ct_get_social_links();
$ct_contact = ct_contact_details();
$ct_app_url = ct_app_download_url();
?>
</div>

<section class="ct-community-cta">
	<div class="ct-container ct-community-cta__card">
		<div>
			<span class="ct-overline">CREED NETWORK</span>
			<h2>Stay informed. Stay aware.</h2>
			<p>Get Creed Times directly on the platforms you already use.</p>
		</div>
		<div class="ct-community-links">
			<a href="<?php echo esc_url( $ct_social['whatsapp'] ); ?>" target="_blank" rel="noopener"><strong><?php echo ct_brand_icon( 'whatsapp' ); ?> Join Channel</strong><span>Join ↗</span></a>
			<a href="<?php echo esc_url( $ct_social['group'] ); ?>" target="_blank" rel="noopener"><strong><?php echo ct_brand_icon( 'whatsapp' ); ?> WhatsApp Group</strong><span>Join ↗</span></a>
			<a href="<?php echo esc_url( $ct_social['instagram'] ); ?>" target="_blank" rel="noopener"><strong><?php echo ct_brand_icon( 'instagram' ); ?> Instagram</strong><span>Follow ↗</span></a>
			<a href="<?php echo esc_url( $ct_social['youtube'] ); ?>" target="_blank" rel="noopener"><strong><?php echo ct_brand_icon( 'youtube' ); ?> YouTube</strong><span>Watch ↗</span></a>
		</div>
	</div>
</section>

<footer class="ct-footer">
	<div class="ct-container">
		<div class="ct-footer__top">
			<a class="ct-footer-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<?php echo ct_logo_markup( 'ct-footer-logo' ); ?>
			</a>
			<p>News · Analysis · Perspective</p>
		</div>

		<div class="ct-footer__grid">
			<div>
				<h3>Explore</h3>
				<a href="<?php echo esc_url( ct_section_url( 'latest' ) ); ?>">Latest</a>
				<a href="<?php echo esc_url( ct_section_url( 'analysis' ) ); ?>">Analysis</a>
				<a href="<?php echo esc_url( ct_section_url( 'west-asia' ) ); ?>">West Asia</a>
				<a href="<?php echo esc_url( ct_section_url( 'world' ) ); ?>">World</a>
				<?php if ( ct_has_urdu_content() ) : ?><a href="<?php echo esc_url( ct_section_url( 'urdu' ) ); ?>" lang="ur">اردو</a><?php endif; ?>
			</div>

			<div>
				<h3>Media</h3>
				<a href="<?php echo esc_url( post_type_exists( 'ct_video' ) ? get_post_type_archive_link( 'ct_video' ) : home_url( '/videos/' ) ); ?>">Videos</a>
				<a href="<?php echo esc_url( post_type_exists( 'ct_short' ) ? get_post_type_archive_link( 'ct_short' ) : home_url( '/shorts/' ) ); ?>">Reels / Shorts</a>
				<a href="<?php echo esc_url( post_type_exists( 'ct_podcast' ) ? get_post_type_archive_link( 'ct_podcast' ) : home_url( '/podcasts/' ) ); ?>">Podcasts</a>
				<a href="<?php echo esc_url( post_type_exists( 'ct_artwork' ) ? get_post_type_archive_link( 'ct_artwork' ) : home_url( '/visuals/' ) ); ?>">Visual Stories</a>
				<a href="<?php echo esc_url( $ct_social['youtube'] ); ?>" target="_blank" rel="noopener">YouTube Channel ↗</a>
				<a href="<?php echo esc_url( $ct_social['instagram'] ); ?>" target="_blank" rel="noopener">Instagram ↗</a>
			</div>

			<div>
				<h3>People</h3>
				<a href="<?php echo esc_url( home_url( '/authors/' ) ); ?>">Authors</a>
				<a href="<?php echo esc_url( home_url( '/about-us/' ) ); ?>">About Creed Times</a>
				<a href="<?php echo esc_url( home_url( '/contribute/' ) ); ?>">Write for Us</a>
				<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Contact</a>
			</div>

			<div>
				<h3>Account</h3>
				<a href="<?php echo esc_url( home_url( '/profile/' ) ); ?>">My Profile</a>
				<a href="<?php echo esc_url( home_url( '/creed-pro/' ) ); ?>">Creed Pro</a>
				<?php if ( $ct_app_url ) : ?><a href="<?php echo esc_url( $ct_app_url ); ?>" target="_blank" rel="noopener">Download App v<?php echo esc_html( ct_app_version() ); ?> ↗</a><?php endif; ?>
				<a href="mailto:<?php echo esc_attr( $ct_contact['email'] ); ?>"><?php echo esc_html( $ct_contact['email'] ); ?></a>
			</div>
		</div>

		<div class="ct-footer__social-row">
			<span>Follow Creed Times</span>
			<div>
				<?php if ( $ct_social['facebook'] ) : ?><a href="<?php echo esc_url( $ct_social['facebook'] ); ?>" target="_blank" rel="noopener" aria-label="Facebook"><?php echo ct_brand_icon( 'facebook' ); ?></a><?php endif; ?>
				<?php if ( $ct_social['instagram'] ) : ?><a href="<?php echo esc_url( $ct_social['instagram'] ); ?>" target="_blank" rel="noopener" aria-label="Instagram"><?php echo ct_brand_icon( 'instagram' ); ?></a><?php endif; ?>
				<?php if ( $ct_social['youtube'] ) : ?><a href="<?php echo esc_url( $ct_social['youtube'] ); ?>" target="_blank" rel="noopener" aria-label="YouTube"><?php echo ct_brand_icon( 'youtube' ); ?></a><?php endif; ?>
				<?php if ( $ct_social['x'] ) : ?><a href="<?php echo esc_url( $ct_social['x'] ); ?>" target="_blank" rel="noopener" aria-label="X"><?php echo ct_brand_icon( 'x' ); ?></a><?php endif; ?>
				<a href="<?php echo esc_url( $ct_social['whatsapp'] ); ?>" target="_blank" rel="noopener" aria-label="WhatsApp"><?php echo ct_brand_icon( 'whatsapp' ); ?></a>
			</div>
		</div>

		<div class="ct-footer__bottom">
			<span>© <?php echo esc_html( gmdate( 'Y' ) ); ?> Creed Times</span>
			<span>creedtimes.com · <?php echo esc_html( $ct_contact['phone'] ); ?></span>
		</div>
	</div>
</footer>

<nav class="ct-mobile-bottom" aria-label="Mobile navigation">
	<a class="<?php echo is_front_page() ? 'is-active' : ''; ?>" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo ct_icon( 'home' ); ?><span>Home</span></a>
	<a href="<?php echo esc_url( ct_section_url( 'latest' ) ); ?>"><?php echo ct_icon( 'search' ); ?><span>Latest</span></a>
	<a href="<?php echo esc_url( post_type_exists( 'ct_video' ) ? get_post_type_archive_link( 'ct_video' ) : home_url( '/videos/' ) ); ?>"><?php echo ct_icon( 'video' ); ?><span>Videos</span></a>
	<a href="<?php echo esc_url( home_url( '/profile/?tab=saved' ) ); ?>"><?php echo ct_icon( 'bookmark' ); ?><span>Saved</span></a>
	<a href="<?php echo esc_url( home_url( '/profile/' ) ); ?>"><?php echo ct_icon( 'user' ); ?><span>Profile</span></a>
</nav>

<?php wp_footer(); ?>
</body>
</html>
