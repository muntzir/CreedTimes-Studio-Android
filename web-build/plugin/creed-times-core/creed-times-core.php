<?php
/**
 * Plugin Name: Creed Times Core
 * Plugin URI: https://creedtimes.com/
 * Description: Editorial content types, taxonomy cleanup, Creed Pro access, bookmarks, notes, follows and profile tools for Creed Times.
 * Version: 2.3.0
 * Author: Creed Times
 * Requires at least: 6.6
 * Requires PHP: 8.0
 * Text Domain: creed-times-core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'CT_CORE_VERSION', '2.3.0' );
define( 'CT_CORE_DIR', plugin_dir_path( __FILE__ ) );
define( 'CT_CORE_URI', plugin_dir_url( __FILE__ ) );

require_once CT_CORE_DIR . 'includes/post-types.php';
require_once CT_CORE_DIR . 'includes/meta.php';
require_once CT_CORE_DIR . 'includes/membership.php';
require_once CT_CORE_DIR . 'includes/user-tools.php';
require_once CT_CORE_DIR . 'includes/migration.php';
require_once CT_CORE_DIR . 'includes/admin.php';
require_once CT_CORE_DIR . 'includes/login-branding.php';
require_once CT_CORE_DIR . 'includes/pages.php';
require_once CT_CORE_DIR . 'includes/contact-forms.php';
require_once CT_CORE_DIR . 'includes/youtube-sync.php';

function ct_core_assets() {
	wp_enqueue_script(
		'ct-core-front',
		CT_CORE_URI . 'assets/js/front.js',
		array(),
		CT_CORE_VERSION,
		true
	);
	wp_localize_script( 'ct-core-front', 'ctCore', array(
		'ajaxUrl' => admin_url( 'admin-ajax.php' ),
		'nonce'   => wp_create_nonce( 'ct_core_front' ),
		'postId'  => is_singular() ? get_queried_object_id() : 0,
		'loggedIn'=> is_user_logged_in(),
	) );
}
add_action( 'wp_enqueue_scripts', 'ct_core_assets' );

function ct_core_activate() {
	ct_core_register_content_types();
	ct_core_seed_terms();

	ct_core_ensure_required_pages();
	if ( ! wp_next_scheduled( 'ct_core_youtube_sync_event' ) ) {
		wp_schedule_event( time() + 300, 'hourly', 'ct_core_youtube_sync_event' );
	}
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'ct_core_activate' );

function ct_core_deactivate() {
	$timestamp = wp_next_scheduled( 'ct_core_youtube_sync_event' );
	if ( $timestamp ) {
		wp_unschedule_event( $timestamp, 'ct_core_youtube_sync_event' );
	}
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'ct_core_deactivate' );


/**
 * One-time upgrade routine for routes, default pages and optional brand asset discovery.
 */
function ct_core_maybe_upgrade() {
	if ( get_option( 'ct_core_schema_version' ) === CT_CORE_VERSION ) {
		return;
	}

	ct_core_register_content_types();
	ct_core_seed_terms();

	ct_core_ensure_required_pages();

	// Use exact Creed Times brand uploads automatically if they already exist in Media Library.
	$logo_candidates = get_posts( array(
		'post_type'      => 'attachment',
		'post_status'    => 'inherit',
		'posts_per_page' => 20,
		's'              => 'Creed Times',
	) );
	foreach ( $logo_candidates as $attachment ) {
		$title = strtolower( $attachment->post_title );
		if ( false !== strpos( $title, 'geometric news logo' ) || false !== strpos( $title, 'creed times logo' ) ) {
			if ( ! get_theme_mod( 'custom_logo' ) ) {
				set_theme_mod( 'custom_logo', $attachment->ID );
			}
		}
		if ( false !== strpos( $title, 'monogram' ) ) {
			if ( ! has_site_icon() ) {
				update_option( 'site_icon', $attachment->ID );
			}
		}
	}

	flush_rewrite_rules( false );
	update_option( 'ct_core_schema_version', CT_CORE_VERSION );
}
add_action( 'init', 'ct_core_maybe_upgrade', 98 );
