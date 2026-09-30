<?php
/**
 * Plugin Name: Creed Times Core
 * Plugin URI: https://creedtimes.com/
 * Description: Editorial content types, taxonomy cleanup, Creed Pro access, bookmarks, notes, follows and profile tools for Creed Times.
 * Version: 2.0.0
 * Author: Creed Times
 * Requires at least: 6.6
 * Requires PHP: 8.0
 * Text Domain: creed-times-core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'CT_CORE_VERSION', '2.0.0' );
define( 'CT_CORE_DIR', plugin_dir_path( __FILE__ ) );
define( 'CT_CORE_URI', plugin_dir_url( __FILE__ ) );

require_once CT_CORE_DIR . 'includes/post-types.php';
require_once CT_CORE_DIR . 'includes/meta.php';
require_once CT_CORE_DIR . 'includes/membership.php';
require_once CT_CORE_DIR . 'includes/user-tools.php';
require_once CT_CORE_DIR . 'includes/migration.php';
require_once CT_CORE_DIR . 'includes/admin.php';

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

	$pages = array(
		'profile'   => 'Profile',
		'creed-pro' => 'Creed Pro',
		'authors'   => 'Authors',
	);
	foreach ( $pages as $slug => $title ) {
		if ( ! get_page_by_path( $slug ) ) {
			wp_insert_post( array(
				'post_type'   => 'page',
				'post_status' => 'publish',
				'post_title'  => $title,
				'post_name'   => $slug,
			) );
		}
	}
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'ct_core_activate' );

function ct_core_deactivate() {
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'ct_core_deactivate' );
