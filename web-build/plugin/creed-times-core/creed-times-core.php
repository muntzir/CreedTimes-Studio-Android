<?php
/**
 * Plugin Name: Creed Times Core
 * Plugin URI: https://creedtimes.com/
 * Description: Editorial content types, taxonomy cleanup, Creed Pro access, bookmarks, notes, follows and profile tools for Creed Times.
 * Version: 2.3.1
 * Author: Creed Times
 * Requires at least: 6.6
 * Requires PHP: 8.0
 * Text Domain: creed-times-core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'CT_CORE_VERSION', '2.3.1' );
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
	// Keep activation deliberately minimal so third-party plugins cannot break
	// the WordPress activation sandbox while Creed Times is being enabled.
	ct_core_register_content_types();
	ct_core_seed_terms();
	update_option( 'ct_core_pending_setup', '1' );
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'ct_core_activate' );

function ct_core_deactivate() {
	$timestamp = wp_next_scheduled( 'ct_core_youtube_sync_event' );
	if ( $timestamp ) {
		wp_unschedule_event( $timestamp, 'ct_core_youtube_sync_event' );
	}
	$initial = wp_next_scheduled( 'ct_core_youtube_initial_sync_event' );
	if ( $initial ) {
		wp_unschedule_event( $initial, 'ct_core_youtube_initial_sync_event' );
	}
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'ct_core_deactivate' );


/**
 * Deferred setup runs only after the plugin has activated successfully.
 * No taxonomy migration is run automatically; the editor can review it first.
 */
function ct_core_deferred_setup() {
	if ( '1' !== get_option( 'ct_core_pending_setup' ) && get_option( 'ct_core_schema_version' ) === CT_CORE_VERSION ) {
		return;
	}

	if ( function_exists( 'ct_core_register_content_types' ) ) {
		ct_core_register_content_types();
	}
	if ( function_exists( 'ct_core_seed_terms' ) ) {
		ct_core_seed_terms();
	}
	if ( function_exists( 'ct_core_ensure_required_pages' ) ) {
		ct_core_ensure_required_pages();
	}

	if ( ! wp_next_scheduled( 'ct_core_youtube_sync_event' ) ) {
		wp_schedule_event( time() + 300, 'hourly', 'ct_core_youtube_sync_event' );
	}

	// Detect exact brand assets only if they already exist in WordPress Media.
	$logo_candidates = get_posts( array(
		'post_type'      => 'attachment',
		'post_status'    => 'inherit',
		'posts_per_page' => 20,
		's'              => 'Creed Times',
	) );
	foreach ( $logo_candidates as $attachment ) {
		$title = strtolower( (string) $attachment->post_title );
		if ( false !== strpos( $title, 'geometric news logo' ) || false !== strpos( $title, 'creed times logo' ) ) {
			if ( ! get_theme_mod( 'custom_logo' ) ) {
				set_theme_mod( 'custom_logo', $attachment->ID );
			}
		}
		if ( false !== strpos( $title, 'monogram' ) && ! has_site_icon() ) {
			update_option( 'site_icon', $attachment->ID );
		}
	}

	delete_option( 'ct_core_pending_setup' );
	update_option( 'ct_core_schema_version', CT_CORE_VERSION );
}
add_action( 'admin_init', 'ct_core_deferred_setup', 20 );

function ct_core_activation_notice() {
	if ( get_option( 'ct_core_schema_version' ) !== CT_CORE_VERSION && '1' === get_option( 'ct_core_pending_setup' ) ) {
		echo '<div class="notice notice-info"><p><strong>Creed Times Core:</strong> activation succeeded. Finishing safe newsroom setup now…</p></div>';
	}
}
add_action( 'admin_notices', 'ct_core_activation_notice' );


function ct_core_initial_youtube_sync() {
	if ( function_exists( 'ct_core_sync_youtube' ) ) {
		ct_core_sync_youtube();
	}
}
add_action( 'ct_core_youtube_initial_sync_event', 'ct_core_initial_youtube_sync' );
