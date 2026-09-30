<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

function ct_core_pro_level_ids() {
	$raw = (string) get_option( 'ct_pro_level_ids', '' );
	$ids = array_filter( array_map( 'absint', preg_split( '/[,s]+/', $raw ) ) );
	return array_values( $ids );
}

function ct_core_user_has_pro( $user_id = 0 ) {
	$user_id = $user_id ?: get_current_user_id();
	if ( ! $user_id ) { return false; }
	if ( user_can( $user_id, 'manage_options' ) ) { return true; }

	if ( function_exists( 'pmpro_hasMembershipLevel' ) ) {
		$levels = ct_core_pro_level_ids();
		return $levels ? (bool) pmpro_hasMembershipLevel( $levels, $user_id ) : (bool) pmpro_hasMembershipLevel( null, $user_id );
	}

	return (bool) get_user_meta( $user_id, '_ct_pro_member', true );
}

function ct_core_can_view_post( $post_id = 0, $user_id = 0 ) {
	$post_id = $post_id ?: get_the_ID();
	$access = get_post_meta( $post_id, '_ct_access_level', true );
	if ( ! $access || 'free' === $access ) { return true; }
	if ( current_user_can( 'edit_post', $post_id ) ) { return true; }
	return ct_core_user_has_pro( $user_id );
}

function ct_core_pro_body_class( $classes ) {
	if ( is_user_logged_in() ) {
		$classes[] = ct_core_user_has_pro() ? 'ct-user-pro' : 'ct-user-free';
	}
	return $classes;
}
add_filter( 'body_class', 'ct_core_pro_body_class' );
