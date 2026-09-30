<?php
/**
 * Template helpers.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function ct_reading_time( $post_id = 0 ) {
	$post_id = $post_id ?: get_the_ID();
	$content = get_post_field( 'post_content', $post_id );
	$words   = str_word_count( wp_strip_all_tags( (string) $content ) );
	return max( 1, (int) ceil( $words / 220 ) );
}

function ct_primary_category( $post_id = 0 ) {
	$post_id = $post_id ?: get_the_ID();
	$cats = get_the_category( $post_id );
	return $cats ? $cats[0] : null;
}

function ct_term_badge( $post_id = 0 ) {
	$post_id = $post_id ?: get_the_ID();
	$cat = ct_primary_category( $post_id );
	if ( $cat ) {
		return '<a class="ct-badge" href="' . esc_url( get_category_link( $cat ) ) . '">' . esc_html( $cat->name ) . '</a>';
	}

	if ( taxonomy_exists( 'ct_format' ) ) {
		$terms = get_the_terms( $post_id, 'ct_format' );
		if ( $terms && ! is_wp_error( $terms ) ) {
			return '<span class="ct-badge">' . esc_html( $terms[0]->name ) . '</span>';
		}
	}

	return '<span class="ct-badge">' . esc_html__( 'Latest', 'creed-times' ) . '</span>';
}

function ct_post_type_label( $post_id = 0 ) {
	$post_id = $post_id ?: get_the_ID();
	$type = get_post_type( $post_id );

	$labels = array(
		'post'       => __( 'Article', 'creed-times' ),
		'ct_video'   => __( 'Video', 'creed-times' ),
		'ct_short'   => __( 'Short', 'creed-times' ),
		'ct_podcast' => __( 'Podcast', 'creed-times' ),
		'ct_artwork' => __( 'Visual', 'creed-times' ),
	);
	return $labels[ $type ] ?? __( 'Story', 'creed-times' );
}

function ct_get_social_links() {
	return array(
		'facebook'  => get_theme_mod( 'ct_facebook_url', 'https://facebook.com/creedtimes' ),
		'instagram' => get_theme_mod( 'ct_instagram_url', 'https://instagram.com/creed.times' ),
		'youtube'   => get_theme_mod( 'ct_youtube_url', 'https://youtube.com/@creedtimes' ),
		'x'         => get_theme_mod( 'ct_x_url', '' ),
		'whatsapp'  => get_theme_mod( 'ct_whatsapp_channel_url', 'https://whatsapp.com/channel/0029VbDZdu7AInPo7csvQi0Z' ),
		'group'     => get_theme_mod( 'ct_whatsapp_group_url', 'https://chat.whatsapp.com/C2IysQwYjuJ36ImEWG7Md2' ),
	);
}

function ct_icon( $name, $class = '' ) {
	$icons = array(
		'search' => '<circle cx="11" cy="11" r="6.5"></circle><path d="m16 16 4 4"></path>',
		'menu' => '<path d="M4 7h16M4 12h16M4 17h16"></path>',
		'sun' => '<path d="M12 3v2m0 14v2M3 12h2m14 0h2M5.6 5.6 7 7m10 10 1.4 1.4m0-12.8L17 7M7 17l-1.4 1.4"></path><circle cx="12" cy="12" r="4"></circle>',
		'moon' => '<path d="M20 15.3A8.4 8.4 0 1 1 8.7 4 7.2 7.2 0 0 0 20 15.3Z"></path>',
		'bookmark' => '<path d="M6 4h12v16l-6-4-6 4V4Z"></path>',
		'share' => '<circle cx="18" cy="5" r="2"></circle><circle cx="6" cy="12" r="2"></circle><circle cx="18" cy="19" r="2"></circle><path d="m8 11 8-5M8 13l8 5"></path>',
		'play' => '<circle cx="12" cy="12" r="9"></circle><path d="m10 8 6 4-6 4V8Z"></path>',
		'clock' => '<circle cx="12" cy="12" r="9"></circle><path d="M12 7v5l3 2"></path>',
		'user' => '<circle cx="12" cy="8" r="3"></circle><path d="M5 20c.6-4 3-6 7-6s6.4 2 7 6"></path>',
		'home' => '<path d="m3 11 9-7 9 7"></path><path d="M5 10v10h14V10"></path>',
		'video' => '<rect x="4" y="6" width="12" height="12" rx="2"></rect><path d="m16 10 4-2v8l-4-2"></path>',
		'copy' => '<rect x="8" y="8" width="10" height="11" rx="2"></rect><path d="M6 16H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v1"></path>',
		'lock' => '<rect x="5" y="10" width="14" height="10" rx="2"></rect><path d="M8 10V7a4 4 0 0 1 8 0v3"></path>',
	);

	if ( empty( $icons[ $name ] ) ) {
		return '';
	}

	return '<svg class="ct-icon ' . esc_attr( $class ) . '" viewBox="0 0 24 24" aria-hidden="true">' . $icons[ $name ] . '</svg>';
}

function ct_is_pro_post( $post_id = 0 ) {
	$post_id = $post_id ?: get_the_ID();
	return 'pro' === get_post_meta( $post_id, '_ct_access_level', true );
}

function ct_can_view_post( $post_id = 0 ) {
	$post_id = $post_id ?: get_the_ID();
	if ( function_exists( 'ct_core_can_view_post' ) ) {
		return ct_core_can_view_post( $post_id );
	}
	return ! ct_is_pro_post( $post_id ) || current_user_can( 'edit_post', $post_id );
}

function ct_featured_query( $count = 6, $args = array() ) {
	$defaults = array(
		'post_type'           => array_values( array_filter( array(
			'post',
			post_type_exists( 'ct_video' ) ? 'ct_video' : null,
			post_type_exists( 'ct_podcast' ) ? 'ct_podcast' : null,
		) ) ),
		'post_status'         => 'publish',
		'posts_per_page'      => $count,
		'ignore_sticky_posts' => false,
	);
	return new WP_Query( wp_parse_args( $args, $defaults ) );
}

function ct_author_designation( $user_id ) {
	$designation = get_user_meta( $user_id, 'ct_designation', true );
	if ( $designation ) {
		return $designation;
	}
	return __( 'Contributor', 'creed-times' );
}

function ct_legacy_tag_excluded( $term_id ) {
	return (bool) get_term_meta( $term_id, '_ct_legacy_demo', true );
}


function ct_tax_url( $taxonomy, $slug, $fallback = '' ) {
	if ( taxonomy_exists( $taxonomy ) ) {
		$term = get_term_by( 'slug', $slug, $taxonomy );
		if ( $term && ! is_wp_error( $term ) ) {
			$url = get_term_link( $term );
			if ( ! is_wp_error( $url ) ) {
				return $url;
			}
		}
	}
	return $fallback ? home_url( $fallback ) : home_url( '/' );
}
