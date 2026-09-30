<?php
$ct_social = ct_get_social_links();
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="theme-color" content="#ffffff">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div class="ct-reading-progress" data-reading-progress aria-hidden="true"></div>

<div class="ctn-topbar">
	<div class="ct-container ctn-topbar__inner">
		<div class="ctn-topbar__left">
			<span><?php echo esc_html( wp_date( 'D, d M Y' ) ); ?></span>
			<i></i>
			<span><?php esc_html_e( 'Stay informed. Stay aware.', 'creed-times' ); ?></span>
		</div>
		<div class="ctn-topbar__right">
			<a href="<?php echo esc_url( home_url( '/about-us/' ) ); ?>">About</a>
			<a href="<?php echo esc_url( home_url( '/authors/' ) ); ?>">Authors</a>
			<a href="<?php echo esc_url( home_url( '/contribute/' ) ); ?>">Contribute</a>
			<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Contact</a>
			<a href="<?php echo esc_url( ct_section_url( 'urdu' ) ); ?>" lang="ur" dir="rtl">اردو</a>
		</div>
	</div>
</div>

<header class="ctn-header" data-site-header>
	<div class="ct-container ctn-header__inner">
		<div class="ctn-brand-wrap">
			<button class="ctn-mobile-btn" type="button" data-mobile-menu-toggle aria-expanded="false" aria-label="<?php esc_attr_e( 'Open menu', 'creed-times' ); ?>">
				<?php echo ct_icon( 'menu' ); ?>
			</button>
			<a class="ctn-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
				<?php if ( has_custom_logo() ) : ?>
					<?php echo wp_get_attachment_image( get_theme_mod( 'custom_logo' ), 'full', false, array( 'class' => 'custom-logo', 'alt' => get_bloginfo( 'name' ) ) ); ?>
				<?php else : ?>
					<img class="ctn-brand__default" src="<?php echo esc_url( ct_brand_wordmark_url() ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
				<?php endif; ?>
			</a>
		</div>

		<nav class="ctn-nav" aria-label="<?php esc_attr_e( 'Primary navigation', 'creed-times' ); ?>">
			<a class="<?php echo is_front_page() ? 'is-active' : ''; ?>" href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a>
			<a href="<?php echo esc_url( ct_section_url( 'latest' ) ); ?>">Latest</a>
			<a href="<?php echo esc_url( ct_section_url( 'west-asia' ) ); ?>">West Asia</a>
			<a href="<?php echo esc_url( ct_section_url( 'pakistan' ) ); ?>">Pakistan</a>
			<a href="<?php echo esc_url( ct_section_url( 'world' ) ); ?>">World</a>
			<a href="<?php echo esc_url( ct_section_url( 'analysis' ) ); ?>">Analysis</a>
			<?php if ( post_type_exists( 'ct_video' ) ) : ?><a href="<?php echo esc_url( get_post_type_archive_link( 'ct_video' ) ); ?>">Videos</a><?php endif; ?>
			<a href="<?php echo esc_url( home_url( '/authors/' ) ); ?>">Authors</a>
		</nav>

		<div class="ctn-actions">
			<div class="ctn-social">
				<?php if ( $ct_social['facebook'] ) : ?><a href="<?php echo esc_url( $ct_social['facebook'] ); ?>" target="_blank" rel="noopener" aria-label="Facebook"><?php echo ct_brand_icon( 'facebook' ); ?></a><?php endif; ?>
				<?php if ( $ct_social['instagram'] ) : ?><a href="<?php echo esc_url( $ct_social['instagram'] ); ?>" target="_blank" rel="noopener" aria-label="Instagram"><?php echo ct_brand_icon( 'instagram' ); ?></a><?php endif; ?>
				<?php if ( $ct_social['youtube'] ) : ?><a href="<?php echo esc_url( $ct_social['youtube'] ); ?>" target="_blank" rel="noopener" aria-label="YouTube"><?php echo ct_brand_icon( 'youtube' ); ?></a><?php endif; ?>
			</div>
			<button class="ctn-action-btn" type="button" data-search-overlay-toggle aria-label="<?php esc_attr_e( 'Search', 'creed-times' ); ?>"><?php echo ct_icon( 'search' ); ?></button>
			<button class="ctn-action-btn ctn-theme" type="button" data-theme-toggle aria-label="<?php esc_attr_e( 'Toggle dark mode', 'creed-times' ); ?>"><?php echo ct_icon( 'moon', 'ct-theme-icon ct-theme-icon--moon' ); ?><?php echo ct_icon( 'sun', 'ct-theme-icon ct-theme-icon--sun' ); ?></button>
			<a class="ctn-wa" href="<?php echo esc_url( $ct_social['whatsapp'] ); ?>" target="_blank" rel="noopener"><?php echo ct_brand_icon( 'whatsapp' ); ?><span>Join Channel</span></a>
		</div>
	</div>

	<div class="ctn-mobile-panel" data-mobile-panel hidden>
		<div class="ct-container">
			<form class="ctn-mobile-search" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<input type="search" name="s" placeholder="Search Creed Times…">
				<button type="submit"><?php echo ct_icon( 'search' ); ?></button>
			</form>
			<nav>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a>
				<a href="<?php echo esc_url( ct_section_url( 'latest' ) ); ?>">Latest</a>
				<a href="<?php echo esc_url( ct_section_url( 'west-asia' ) ); ?>">West Asia</a>
				<a href="<?php echo esc_url( ct_section_url( 'pakistan' ) ); ?>">Pakistan</a>
				<a href="<?php echo esc_url( ct_section_url( 'world' ) ); ?>">World</a>
				<a href="<?php echo esc_url( ct_section_url( 'analysis' ) ); ?>">Analysis</a>
				<a href="<?php echo esc_url( ct_section_url( 'urdu' ) ); ?>" lang="ur">اردو</a>
				<?php if ( post_type_exists( 'ct_video' ) ) : ?><a href="<?php echo esc_url( get_post_type_archive_link( 'ct_video' ) ); ?>">Videos</a><?php endif; ?>
				<?php if ( post_type_exists( 'ct_short' ) ) : ?><a href="<?php echo esc_url( get_post_type_archive_link( 'ct_short' ) ); ?>">Shorts</a><?php endif; ?>
				<?php if ( post_type_exists( 'ct_podcast' ) ) : ?><a href="<?php echo esc_url( get_post_type_archive_link( 'ct_podcast' ) ); ?>">Podcasts</a><?php endif; ?>
				<a href="<?php echo esc_url( home_url( '/about-us/' ) ); ?>">About</a>
			</nav>
			<div class="ctn-mobile-cta">
				<a href="<?php echo esc_url( $ct_social['whatsapp'] ); ?>" target="_blank" rel="noopener"><?php echo ct_brand_icon( 'whatsapp' ); ?> Join Channel</a>
				<a href="<?php echo esc_url( $ct_social['group'] ); ?>" target="_blank" rel="noopener"><?php echo ct_brand_icon( 'whatsapp' ); ?> Group</a>
			</div>
		</div>
	</div>
</header>

<div class="ct-search-overlay" data-search-overlay hidden>
	<button class="ct-search-overlay__close" type="button" data-search-overlay-toggle aria-label="<?php esc_attr_e( 'Close search', 'creed-times' ); ?>">×</button>
	<div class="ct-search-overlay__inner">
		<span class="ct-overline">SEARCH CREED TIMES</span>
		<form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
			<input type="search" name="s" placeholder="Search topics, authors, articles…" autofocus>
			<button type="submit">Search</button>
		</form>
	</div>
</div>

<div id="content" class="ct-site-content">
