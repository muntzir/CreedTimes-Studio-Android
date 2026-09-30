<?php
/**
 * Creed Times 2.0 theme functions.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'CT_THEME_VERSION', '2.3.0' );
define( 'CT_THEME_DIR', get_template_directory() );
define( 'CT_THEME_URI', get_template_directory_uri() );

require_once CT_THEME_DIR . '/inc/template-tags.php';
require_once CT_THEME_DIR . '/inc/customizer.php';

function ct_theme_setup() {
	load_theme_textdomain( 'creed-times', CT_THEME_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'custom-logo', array(
		'height'      => 84,
		'width'       => 360,
		'flex-height' => true,
		'flex-width'  => true,
	) );
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
		'style',
		'script',
	) );

	register_nav_menus( array(
		'primary' => __( 'Primary Navigation', 'creed-times' ),
		'utility' => __( 'Utility Navigation', 'creed-times' ),
		'footer'  => __( 'Footer Navigation', 'creed-times' ),
	) );

	add_image_size( 'ct-hero', 1400, 820, true );
	add_image_size( 'ct-card', 760, 480, true );
	add_image_size( 'ct-card-hd', 1200, 675, true );
	add_image_size( 'ct-wide-hd', 1600, 900, true );
	add_image_size( 'ct-square', 480, 480, true );
	add_image_size( 'ct-portrait', 540, 900, true );
}
add_action( 'after_setup_theme', 'ct_theme_setup' );

function ct_theme_assets() {
	wp_enqueue_style(
		'ct-fonts',
		'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Manrope:wght@500;600;700;800&family=Newsreader:opsz,wght@6..72,500;6..72,600;6..72,700&family=Noto+Nastaliq+Urdu:wght@400;500;600;700&display=swap',
		array(),
		null
	);
	wp_enqueue_style(
		'ct-main',
		CT_THEME_URI . '/assets/css/main.css',
		array( 'ct-fonts' ),
		CT_THEME_VERSION
	);
	wp_enqueue_script(
		'ct-main',
		CT_THEME_URI . '/assets/js/main.js',
		array(),
		CT_THEME_VERSION,
		true
	);

	wp_localize_script( 'ct-main', 'ctTheme', array(
		'ajaxUrl'  => admin_url( 'admin-ajax.php' ),
		'nonce'    => wp_create_nonce( 'ct_frontend' ),
		'loggedIn' => is_user_logged_in(),
		'homeUrl'  => home_url( '/' ),
	) );
}
add_action( 'wp_enqueue_scripts', 'ct_theme_assets' );

function ct_theme_preconnect( $urls, $relation_type ) {
	if ( 'preconnect' === $relation_type ) {
		$urls[] = array( 'href' => 'https://fonts.googleapis.com', 'crossorigin' => 'anonymous' );
		$urls[] = array( 'href' => 'https://fonts.gstatic.com', 'crossorigin' => 'anonymous' );
	}
	return $urls;
}
add_filter( 'wp_resource_hints', 'ct_theme_preconnect', 10, 2 );

function ct_theme_widgets() {
	register_sidebar( array(
		'name'          => __( 'Article Sidebar', 'creed-times' ),
		'id'            => 'article-sidebar',
		'before_widget' => '<section class="ct-widget">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="ct-widget__title">',
		'after_title'   => '</h3>',
	) );
}
add_action( 'widgets_init', 'ct_theme_widgets' );

function ct_theme_excerpt_length( $length ) {
	return is_admin() ? $length : 24;
}
add_filter( 'excerpt_length', 'ct_theme_excerpt_length', 20 );

function ct_theme_body_classes( $classes ) {
	$classes[] = 'ct-site';
	if ( is_singular() ) {
		$classes[] = 'ct-singular';
	}
	if ( is_category( 'urdu' ) || ( is_singular() && has_term( 'urdu', 'ct_language' ) ) ) {
		$classes[] = 'ct-urdu-context';
	}
	return $classes;
}
add_filter( 'body_class', 'ct_theme_body_classes' );

function ct_theme_search_post_types( $query ) {
	if ( ! is_admin() && $query->is_main_query() && $query->is_search() ) {
		$types = array( 'post' );
		foreach ( array( 'ct_video', 'ct_short', 'ct_podcast', 'ct_artwork' ) as $type ) {
			if ( post_type_exists( $type ) ) {
				$types[] = $type;
			}
		}
		$query->set( 'post_type', $types );

		if ( ! empty( $_GET['ct_type'] ) ) {
			$type = sanitize_key( wp_unslash( $_GET['ct_type'] ) );
			if ( post_type_exists( $type ) || 'post' === $type ) {
				$query->set( 'post_type', $type );
			}
		}

		$tax_query = array();
		foreach ( array(
			'ct_language' => 'ct_language',
			'ct_region'   => 'ct_region',
			'ct_topic'    => 'ct_topic',
			'ct_format'   => 'ct_format',
		) as $param => $taxonomy ) {
			if ( ! empty( $_GET[ $param ] ) && taxonomy_exists( $taxonomy ) ) {
				$tax_query[] = array(
					'taxonomy' => $taxonomy,
					'field'    => 'slug',
					'terms'    => sanitize_title( wp_unslash( $_GET[ $param ] ) ),
				);
			}
		}
		if ( $tax_query ) {
			$query->set( 'tax_query', $tax_query );
		}
	}
}
add_action( 'pre_get_posts', 'ct_theme_search_post_types' );

function ct_theme_add_defer_attribute( $tag, $handle ) {
	if ( 'ct-main' === $handle && false === strpos( $tag, ' defer' ) ) {
		$tag = str_replace( ' src=', ' defer src=', $tag );
	}
	return $tag;
}
add_filter( 'script_loader_tag', 'ct_theme_add_defer_attribute', 10, 2 );


/**
 * Fallback favicon / app mark when no WordPress Site Icon has been set.
 */
function ct_theme_default_site_icon() {
	if ( has_site_icon() ) {
		return;
	}
	$icon = esc_url( ct_brand_mark_url() );
	echo '<link rel="icon" href="' . $icon . '" type="image/svg+xml">' . "\n";
	echo '<link rel="apple-touch-icon" href="' . $icon . '">' . "\n";
}
add_action( 'wp_head', 'ct_theme_default_site_icon', 2 );

/**
 * Keep newly introduced region/taxonomy routes from returning 404 after theme updates.
 * Runs only once per theme version.
 */
function ct_theme_maybe_flush_rewrites() {
	if ( get_option( 'ct_theme_rewrite_version' ) === CT_THEME_VERSION ) {
		return;
	}
	flush_rewrite_rules( false );
	update_option( 'ct_theme_rewrite_version', CT_THEME_VERSION );
}
add_action( 'init', 'ct_theme_maybe_flush_rewrites', 99 );

function ct_theme_image_quality() {
	return 90;
}
add_filter( 'jpeg_quality', 'ct_theme_image_quality' );
add_filter( 'wp_editor_set_quality', 'ct_theme_image_quality' );


/**
 * Normalize legacy posts that contain an entire standalone HTML document.
 * The new theme owns page chrome and typography; old <head>/<style>/<script>
 * blocks should never override the newsroom UI.
 */
function ct_theme_normalize_legacy_article_html( $content ) {
	if ( is_admin() || ! is_singular( 'post' ) ) {
		return $content;
	}
	if ( false === stripos( $content, '<!doctype' ) && false === stripos( $content, '<html' ) ) {
		return $content;
	}

	if ( preg_match( '#<body[^>]*>(.*)</body>#is', $content, $match ) ) {
		$content = $match[1];
	}
	$content = preg_replace( '#<(?:head|style|script)[^>]*>.*?</(?:head|style|script)>#is', '', $content );
	$content = preg_replace( '#</?(?:html|body|meta|link)[^>]*>#i', '', $content );
	return $content;
}
add_filter( 'the_content', 'ct_theme_normalize_legacy_article_html', 8 );

/**
 * Prefer large responsive image sources in editorial cards and archives.
 */
function ct_theme_attachment_image_attributes( $attr ) {
	if ( isset( $attr['loading'] ) && 'lazy' === $attr['loading'] ) {
		$attr['decoding'] = 'async';
	}
	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'ct_theme_attachment_image_attributes' );
