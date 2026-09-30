<?php
$ct_social = ct_get_social_links();
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
			<a href="<?php echo esc_url( $ct_social['whatsapp'] ); ?>" target="_blank" rel="noopener"><strong>WhatsApp Channel</strong><span>Join ↗</span></a>
			<a href="<?php echo esc_url( $ct_social['group'] ); ?>" target="_blank" rel="noopener"><strong>WhatsApp Group</strong><span>Join ↗</span></a>
			<a href="<?php echo esc_url( $ct_social['instagram'] ); ?>" target="_blank" rel="noopener"><strong>Instagram</strong><span>Follow ↗</span></a>
			<a href="<?php echo esc_url( $ct_social['youtube'] ); ?>" target="_blank" rel="noopener"><strong>YouTube</strong><span>Watch ↗</span></a>
		</div>
	</div>
</section>

<footer class="ct-footer">
	<div class="ct-container">
		<div class="ct-footer__top">
			<a class="ct-footer-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<?php if ( has_custom_logo() ) : echo wp_get_attachment_image( get_theme_mod( 'custom_logo' ), 'full', false, array( 'class' => 'custom-logo', 'alt' => get_bloginfo( 'name' ) ) ); else : ?><img class="ct-footer-default-logo" src="<?php echo esc_url( ct_brand_wordmark_url() ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"><?php endif; ?>
			</a>
			<p>News · Analysis · Perspective</p>
		</div>
		<div class="ct-footer__grid">
			<div><h3>Explore</h3><a href="<?php echo esc_url( home_url( '/category/news/' ) ); ?>">Latest</a><a href="<?php echo esc_url( home_url( '/category/analysis/' ) ); ?>">Analysis</a><a href="<?php echo esc_url( ct_section_url( 'west-asia' ) ); ?>">West Asia</a><a href="<?php echo esc_url( ct_section_url( 'world' ) ); ?>">World</a><a href="<?php echo esc_url( home_url( '/category/urdu/' ) ); ?>" lang="ur">اردو</a></div>
			<div><h3>Media</h3><?php if ( post_type_exists( 'ct_video' ) ) : ?><a href="<?php echo esc_url( get_post_type_archive_link( 'ct_video' ) ); ?>">Videos</a><?php endif; ?><?php if ( post_type_exists( 'ct_short' ) ) : ?><a href="<?php echo esc_url( get_post_type_archive_link( 'ct_short' ) ); ?>">Shorts</a><?php endif; ?><?php if ( post_type_exists( 'ct_podcast' ) ) : ?><a href="<?php echo esc_url( get_post_type_archive_link( 'ct_podcast' ) ); ?>">Podcasts</a><?php endif; ?><?php if ( post_type_exists( 'ct_artwork' ) ) : ?><a href="<?php echo esc_url( get_post_type_archive_link( 'ct_artwork' ) ); ?>">Visuals</a><?php endif; ?></div>
			<div><h3>Account</h3><a href="<?php echo esc_url( home_url( '/profile/' ) ); ?>">My Profile</a><a href="<?php echo esc_url( home_url( '/creed-pro/' ) ); ?>">Creed Pro</a><a href="<?php echo esc_url( home_url( '/authors/' ) ); ?>">Authors</a><a href="<?php echo esc_url( home_url( '/contribute/' ) ); ?>">Contribute</a></div>
			<div><h3>Network</h3><a href="<?php echo esc_url( $ct_social['whatsapp'] ); ?>" target="_blank" rel="noopener">WhatsApp</a><a href="<?php echo esc_url( $ct_social['instagram'] ); ?>" target="_blank" rel="noopener">Instagram</a><a href="<?php echo esc_url( $ct_social['facebook'] ); ?>" target="_blank" rel="noopener">Facebook</a><a href="<?php echo esc_url( $ct_social['youtube'] ); ?>" target="_blank" rel="noopener">YouTube</a></div>
		</div>
		<div class="ct-footer__bottom"><span>© <?php echo esc_html( gmdate( 'Y' ) ); ?> Creed Times</span><span>creedtimes.com</span></div>
	</div>
</footer>

<nav class="ct-mobile-bottom" aria-label="Mobile navigation">
	<a class="<?php echo is_front_page() ? 'is-active' : ''; ?>" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo ct_icon( 'home' ); ?><span>Home</span></a>
	<a href="<?php echo esc_url( home_url( '/category/news/' ) ); ?>"><?php echo ct_icon( 'search' ); ?><span>Latest</span></a>
	<a href="<?php echo esc_url( post_type_exists( 'ct_video' ) ? get_post_type_archive_link( 'ct_video' ) : home_url( '/videos/' ) ); ?>"><?php echo ct_icon( 'video' ); ?><span>Videos</span></a>
	<a href="<?php echo esc_url( home_url( '/profile/?tab=saved' ) ); ?>"><?php echo ct_icon( 'bookmark' ); ?><span>Saved</span></a>
	<a href="<?php echo esc_url( home_url( '/profile/' ) ); ?>"><?php echo ct_icon( 'user' ); ?><span>Profile</span></a>
</nav>

<?php wp_footer(); ?>
</body>
</html>
