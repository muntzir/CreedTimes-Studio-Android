<?php
$ct_social = ct_get_social_links();
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="theme-color" content="#0B2A4A">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div class="ct-reading-progress" data-reading-progress aria-hidden="true"></div>

<div class="ct-utility">
	<div class="ct-container ct-utility__inner">
		<div class="ct-utility__left">
			<span><?php echo esc_html( wp_date( 'D, d M Y' ) ); ?></span>
			<span class="ct-dot-sep">•</span>
			<span><?php esc_html_e( 'Stay informed. Stay aware.', 'creed-times' ); ?></span>
		</div>
		<nav class="ct-utility__nav" aria-label="<?php esc_attr_e( 'Utility navigation', 'creed-times' ); ?>">
			<a href="<?php echo esc_url( home_url( '/about/' ) ); ?>"><?php esc_html_e( 'About', 'creed-times' ); ?></a>
			<a href="<?php echo esc_url( home_url( '/authors/' ) ); ?>"><?php esc_html_e( 'Our Team', 'creed-times' ); ?></a>
			<a href="<?php echo esc_url( home_url( '/contribute/' ) ); ?>"><?php esc_html_e( 'Contribute', 'creed-times' ); ?></a>
			<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Contact', 'creed-times' ); ?></a>
			<button class="ct-icon-btn ct-icon-btn--plain" type="button" data-theme-toggle aria-label="<?php esc_attr_e( 'Toggle dark mode', 'creed-times' ); ?>">
				<?php echo ct_icon( 'moon', 'ct-theme-icon ct-theme-icon--moon' ); ?>
				<?php echo ct_icon( 'sun', 'ct-theme-icon ct-theme-icon--sun' ); ?>
			</button>
			<a class="ct-language-switch" href="<?php echo esc_url( home_url( '/category/urdu/' ) ); ?>" lang="ur" dir="rtl">اردو</a>
		</nav>
	</div>
</div>

<header class="ct-header" data-site-header>
	<div class="ct-container ct-header__main">
		<div class="ct-brand-wrap">
			<button class="ct-mobile-menu-btn" type="button" data-mobile-menu-toggle aria-expanded="false" aria-label="<?php esc_attr_e( 'Open menu', 'creed-times' ); ?>">
				<?php echo ct_icon( 'menu' ); ?>
			</button>
			<a class="ct-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
				<?php if ( has_custom_logo() ) : ?>
					<?php the_custom_logo(); ?>
				<?php else : ?>
					<span class="ct-brand__text"><strong>CREED</strong><em>TIMES</em><small>NEWS · ANALYSIS · PERSPECTIVE</small></span>
				<?php endif; ?>
			</a>
		</div>

		<form class="ct-header-search" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
			<label class="screen-reader-text" for="ct-header-search"><?php esc_html_e( 'Search', 'creed-times' ); ?></label>
			<input id="ct-header-search" type="search" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php esc_attr_e( 'Search news, analysis, videos…', 'creed-times' ); ?>">
			<button type="submit" aria-label="<?php esc_attr_e( 'Submit search', 'creed-times' ); ?>"><?php echo ct_icon( 'search' ); ?></button>
		</form>

		<div class="ct-header__actions">
			<div class="ct-social-icons">
				<?php if ( $ct_social['facebook'] ) : ?><a href="<?php echo esc_url( $ct_social['facebook'] ); ?>" target="_blank" rel="noopener" aria-label="Facebook">f</a><?php endif; ?>
				<?php if ( $ct_social['instagram'] ) : ?><a href="<?php echo esc_url( $ct_social['instagram'] ); ?>" target="_blank" rel="noopener" aria-label="Instagram">◎</a><?php endif; ?>
				<?php if ( $ct_social['youtube'] ) : ?><a href="<?php echo esc_url( $ct_social['youtube'] ); ?>" target="_blank" rel="noopener" aria-label="YouTube">▶</a><?php endif; ?>
				<?php if ( $ct_social['x'] ) : ?><a href="<?php echo esc_url( $ct_social['x'] ); ?>" target="_blank" rel="noopener" aria-label="X">X</a><?php endif; ?>
			</div>
			<a class="ct-wa-btn" href="<?php echo esc_url( $ct_social['whatsapp'] ); ?>" target="_blank" rel="noopener"><span class="ct-wa-dot">●</span><?php esc_html_e( 'WhatsApp Channel', 'creed-times' ); ?></a>
			<a class="ct-wa-btn ct-wa-btn--group" href="<?php echo esc_url( $ct_social['group'] ); ?>" target="_blank" rel="noopener"><span class="ct-wa-dot">●</span><?php esc_html_e( 'WhatsApp Group', 'creed-times' ); ?></a>
			<button class="ct-mobile-search-btn" type="button" data-search-overlay-toggle aria-label="<?php esc_attr_e( 'Search', 'creed-times' ); ?>"><?php echo ct_icon( 'search' ); ?></button>
		</div>
	</div>

	<div class="ct-nav-row">
		<div class="ct-container ct-nav-row__inner">
			<nav class="ct-primary-nav" aria-label="<?php esc_attr_e( 'Primary navigation', 'creed-times' ); ?>">
				<?php if ( has_nav_menu( 'primary' ) ) : ?>
					<?php wp_nav_menu( array( 'theme_location' => 'primary', 'container' => false, 'menu_class' => 'ct-primary-menu', 'depth' => 1 ) ); ?>
				<?php else : ?>
					<ul class="ct-primary-menu">
						<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a></li>
						<li><a href="<?php echo esc_url( home_url( '/category/news/' ) ); ?>">Latest</a></li>
						<li><a href="<?php echo esc_url( ct_tax_url( 'ct_region', 'west-asia', '/category/west_asia/' ) ); ?>">West Asia</a></li>
						<li><a href="<?php echo esc_url( ct_tax_url( 'ct_region', 'pakistan', '/region/pakistan/' ) ); ?>">Pakistan</a></li>
						<li><a href="<?php echo esc_url( ct_tax_url( 'ct_region', 'world', '/category/world/' ) ); ?>">World</a></li>
						<li><a href="<?php echo esc_url( home_url( '/category/analysis/' ) ); ?>">Analysis</a></li>
						<li><a href="<?php echo esc_url( home_url( '/category/urdu/' ) ); ?>" lang="ur">Urdu</a></li>
						<?php if ( post_type_exists( 'ct_video' ) ) : ?><li><a href="<?php echo esc_url( get_post_type_archive_link( 'ct_video' ) ); ?>">Videos</a></li><?php endif; ?>
						<?php if ( post_type_exists( 'ct_short' ) ) : ?><li><a href="<?php echo esc_url( get_post_type_archive_link( 'ct_short' ) ); ?>">Shorts</a></li><?php endif; ?>
						<?php if ( post_type_exists( 'ct_podcast' ) ) : ?><li><a href="<?php echo esc_url( get_post_type_archive_link( 'ct_podcast' ) ); ?>">Podcasts</a></li><?php endif; ?>
						<li><a href="<?php echo esc_url( home_url( '/authors/' ) ); ?>">Authors</a></li>
					</ul>
				<?php endif; ?>
			</nav>
			<a class="ct-pro-pill" href="<?php echo esc_url( home_url( '/creed-pro/' ) ); ?>"><?php echo ct_icon( 'lock' ); ?> CREED PRO</a>
		</div>
	</div>

	<div class="ct-mobile-panel" data-mobile-panel hidden>
		<div class="ct-container">
			<form class="ct-mobile-panel__search" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<input type="search" name="s" placeholder="<?php esc_attr_e( 'Search Creed Times…', 'creed-times' ); ?>">
				<button type="submit"><?php echo ct_icon( 'search' ); ?></button>
			</form>
			<nav class="ct-mobile-panel__nav">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a>
				<a href="<?php echo esc_url( home_url( '/category/news/' ) ); ?>">Latest</a>
				<a href="<?php echo esc_url( home_url( '/category/analysis/' ) ); ?>">Analysis</a>
				<a href="<?php echo esc_url( ct_tax_url( 'ct_region', 'west-asia', '/category/west_asia/' ) ); ?>">West Asia</a>
				<a href="<?php echo esc_url( ct_tax_url( 'ct_region', 'world', '/category/world/' ) ); ?>">World</a>
				<a href="<?php echo esc_url( home_url( '/category/urdu/' ) ); ?>" lang="ur">اردو</a>
			</nav>
			<div class="ct-mobile-panel__social">
				<a href="<?php echo esc_url( $ct_social['whatsapp'] ); ?>" target="_blank" rel="noopener">WhatsApp Channel</a>
				<a href="<?php echo esc_url( $ct_social['group'] ); ?>" target="_blank" rel="noopener">WhatsApp Group</a>
			</div>
		</div>
	</div>
</header>

<div class="ct-search-overlay" data-search-overlay hidden>
	<button class="ct-search-overlay__close" type="button" data-search-overlay-toggle aria-label="<?php esc_attr_e( 'Close search', 'creed-times' ); ?>">×</button>
	<div class="ct-search-overlay__inner">
		<span class="ct-overline">SEARCH CREED TIMES</span>
		<form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
			<input type="search" name="s" placeholder="<?php esc_attr_e( 'Type a topic, author or story…', 'creed-times' ); ?>" autofocus>
			<button type="submit"><?php esc_html_e( 'Search', 'creed-times' ); ?></button>
		</form>
	</div>
</div>

<div id="content" class="ct-site-content">
