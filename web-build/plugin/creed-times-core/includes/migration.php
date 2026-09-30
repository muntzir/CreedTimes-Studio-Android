<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Safe migration based on Creed Times WXR structure.
 *
 * It does not delete posts, categories, tags, media, authors or change slugs.
 * It maps legacy classification into the new editorial taxonomies and marks
 * obvious demo/theme tags as legacy so the new theme can hide them.
 */
function ct_core_run_safe_migration() {
	$report = array(
		'posts_scanned'     => 0,
		'terms_mapped'      => 0,
		'pro_mapped'        => 0,
		'demo_tags_marked'  => 0,
	);

	ct_core_seed_terms();

	$legacy_demo_tags = array(
		'beauty','vogue','vouge','tips','today','culture','life','style','stylish',
		'gift','newface','trends','fancy','future'
	);
	foreach ( $legacy_demo_tags as $slug ) {
		$term = get_term_by( 'slug', $slug, 'post_tag' );
		if ( $term && ! is_wp_error( $term ) ) {
			update_term_meta( $term->term_id, '_ct_legacy_demo', '1' );
			$report['demo_tags_marked']++;
		}
	}

	$post_ids = get_posts( array(
		'post_type'      => 'post',
		'post_status'    => 'any',
		'posts_per_page' => -1,
		'fields'         => 'ids',
	) );

	foreach ( $post_ids as $post_id ) {
		$report['posts_scanned']++;

		$category_slugs = wp_get_post_terms( $post_id, 'category', array( 'fields' => 'slugs' ) );
		if ( is_wp_error( $category_slugs ) ) { $category_slugs = array(); }

		if ( in_array( 'urdu', $category_slugs, true ) ) {
			wp_set_object_terms( $post_id, 'urdu', 'ct_language', true );
			$report['terms_mapped']++;
		} elseif ( in_array( 'roman_urdu', $category_slugs, true ) || in_array( 'roman-urdu', $category_slugs, true ) ) {
			wp_set_object_terms( $post_id, 'urdu', 'ct_language', true );
			update_post_meta( $post_id, '_ct_roman_urdu', '1' );
			$report['terms_mapped']++;
		} else {
			wp_set_object_terms( $post_id, 'english', 'ct_language', true );
		}

		if ( in_array( 'west_asia', $category_slugs, true ) || in_array( 'west-asia', $category_slugs, true ) ) {
			wp_set_object_terms( $post_id, 'west-asia', 'ct_region', true );
			$report['terms_mapped']++;
		}
		if ( in_array( 'world', $category_slugs, true ) ) {
			wp_set_object_terms( $post_id, 'world', 'ct_region', true );
			$report['terms_mapped']++;
		}

		$format_map = array(
			'news'          => 'news',
			'analysis'      => 'analysis',
			'documentaries' => 'documentary',
		);
		foreach ( $format_map as $legacy => $new_term ) {
			if ( in_array( $legacy, $category_slugs, true ) ) {
				wp_set_object_terms( $post_id, $new_term, 'ct_format', true );
				$report['terms_mapped']++;
			}
		}

		$topic_map = array(
			'politics' => 'pakistan-politics',
			'religion' => 'religion-society',
			'art'      => 'media',
		);
		foreach ( $topic_map as $legacy => $new_term ) {
			if ( in_array( $legacy, $category_slugs, true ) ) {
				wp_set_object_terms( $post_id, $new_term, 'ct_topic', true );
				$report['terms_mapped']++;
			}
		}

		if ( taxonomy_exists( 'post_template' ) ) {
			$template_slugs = wp_get_post_terms( $post_id, 'post_template', array( 'fields' => 'slugs' ) );
			if ( ! is_wp_error( $template_slugs ) ) {
				if ( in_array( 'opinion', $template_slugs, true ) ) {
					wp_set_object_terms( $post_id, 'opinion', 'ct_format', true );
				}
				if ( in_array( 'video', $template_slugs, true ) ) {
					update_post_meta( $post_id, '_ct_legacy_content_kind', 'video' );
				}
				if ( in_array( 'podcast', $template_slugs, true ) || in_array( 'podcast-2', $template_slugs, true ) ) {
					update_post_meta( $post_id, '_ct_legacy_content_kind', 'podcast' );
				}
			}
		}

		if ( taxonomy_exists( 'top_category' ) ) {
			$top_slugs = wp_get_post_terms( $post_id, 'top_category', array( 'fields' => 'slugs' ) );
			if ( ! is_wp_error( $top_slugs ) && in_array( 'for-subscribers', $top_slugs, true ) ) {
				update_post_meta( $post_id, '_ct_access_level', 'pro' );
				$report['pro_mapped']++;
			}
		}

		if ( ! get_post_meta( $post_id, '_ct_access_level', true ) ) {
			update_post_meta( $post_id, '_ct_access_level', 'free' );
		}
	}

	update_option( 'ct_core_last_migration', array(
		'time'   => time(),
		'report' => $report,
	) );

	return $report;
}

function ct_core_hide_legacy_demo_tags( $args, $taxonomies ) {
	if ( is_admin() || ! in_array( 'post_tag', (array) $taxonomies, true ) ) { return $args; }
	$legacy = get_terms( array(
		'taxonomy'   => 'post_tag',
		'hide_empty' => false,
		'meta_key'   => '_ct_legacy_demo',
		'meta_value' => '1',
		'fields'     => 'ids',
	) );
	if ( $legacy && ! is_wp_error( $legacy ) ) {
		$args['exclude'] = array_unique( array_merge( (array) ( $args['exclude'] ?? array() ), array_map( 'absint', $legacy ) ) );
	}
	return $args;
}
add_filter( 'get_terms_args', 'ct_core_hide_legacy_demo_tags', 10, 2 );
