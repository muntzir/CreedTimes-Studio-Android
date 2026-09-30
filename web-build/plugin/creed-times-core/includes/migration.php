<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Non-destructive Creed Times cleanup.
 *
 * Existing posts, media, dates, slugs and authors stay untouched.
 * The routine adds normalized editorial metadata/taxonomies and hides only
 * known demo residue from the new front-end.
 */
function ct_core_run_safe_migration() {
	$report = array(
		'posts_scanned'        => 0,
		'terms_mapped'         => 0,
		'topics_created'       => 0,
		'pro_mapped'           => 0,
		'demo_tags_marked'     => 0,
		'uncategorized_cleaned'=> 0,
		'video_mapped'         => 0,
		'podcast_mapped'       => 0,
		'short_mapped'         => 0,
	);

	ct_core_seed_terms();

	$legacy_demo_tags = array(
		'beauty','vogue','vouge','tips','today','culture','life','style','stylish',
		'gift','newface','trends','fancy','future','fashion','clothes','accessories'
	);
	$legacy_demo_ids = array();

	foreach ( $legacy_demo_tags as $slug ) {
		$term = get_term_by( 'slug', $slug, 'post_tag' );
		if ( $term && ! is_wp_error( $term ) ) {
			update_term_meta( $term->term_id, '_ct_legacy_demo', '1' );
			$legacy_demo_ids[] = (int) $term->term_id;
			$report['demo_tags_marked']++;
		}
	}
	update_option( 'ct_legacy_demo_tag_ids', array_values( array_unique( $legacy_demo_ids ) ) );

	$post_ids = get_posts( array(
		'post_type'      => 'post',
		'post_status'    => 'any',
		'posts_per_page' => -1,
		'fields'         => 'ids',
	) );

	foreach ( $post_ids as $post_id ) {
		$report['posts_scanned']++;

		$category_terms = wp_get_post_terms( $post_id, 'category' );
		if ( is_wp_error( $category_terms ) ) { $category_terms = array(); }
		$category_slugs = wp_list_pluck( $category_terms, 'slug' );
		$category_names = wp_list_pluck( $category_terms, 'name' );

		// Language.
		if ( array_intersect( array( 'urdu', 'roman_urdu', 'roman-urdu' ), $category_slugs ) ) {
			wp_set_object_terms( $post_id, 'urdu', 'ct_language', true );
			$report['terms_mapped']++;
			if ( in_array( 'roman_urdu', $category_slugs, true ) || in_array( 'roman-urdu', $category_slugs, true ) ) {
				update_post_meta( $post_id, '_ct_roman_urdu', '1' );
			}
		} else {
			wp_set_object_terms( $post_id, 'english', 'ct_language', true );
		}

		// Region.
		if ( in_array( 'west_asia', $category_slugs, true ) || in_array( 'west-asia', $category_slugs, true ) ) {
			wp_set_object_terms( $post_id, 'west-asia', 'ct_region', true );
			$report['terms_mapped']++;
		}
		if ( in_array( 'world', $category_slugs, true ) ) {
			wp_set_object_terms( $post_id, 'world', 'ct_region', true );
			$report['terms_mapped']++;
		}
		if ( in_array( 'pakistan', $category_slugs, true ) || in_array( 'pakistan', array_map( 'strtolower', $category_names ), true ) ) {
			wp_set_object_terms( $post_id, 'pakistan', 'ct_region', true );
			$report['terms_mapped']++;
		}

		// Editorial format.
		$format_map = array(
			'news'          => 'news',
			'analysis'      => 'analysis',
			'documentaries' => 'documentary',
			'documentary'   => 'documentary',
			'opinion'       => 'opinion',
			'interview'     => 'interview',
			'explainer'     => 'explainer',
		);
		foreach ( $format_map as $legacy => $new_term ) {
			if ( in_array( $legacy, $category_slugs, true ) ) {
				wp_set_object_terms( $post_id, $new_term, 'ct_format', true );
				$report['terms_mapped']++;
			}
		}

		// Topic mapping from old categories.
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

		// Reuse meaningful tags as Topics only when the tag is used on 2+ posts.
		$tags = wp_get_post_terms( $post_id, 'post_tag' );
		if ( ! is_wp_error( $tags ) ) {
			foreach ( $tags as $tag ) {
				if ( in_array( (int) $tag->term_id, $legacy_demo_ids, true ) || (int) $tag->count < 2 ) {
					continue;
				}
				$topic_slug = sanitize_title( $tag->name );
				if ( ! $topic_slug ) { continue; }
				$existing = term_exists( $topic_slug, 'ct_topic' );
				if ( ! $existing ) {
					$created = wp_insert_term( $tag->name, 'ct_topic', array( 'slug' => $topic_slug ) );
					if ( ! is_wp_error( $created ) ) {
						$report['topics_created']++;
					}
				}
				wp_set_object_terms( $post_id, $topic_slug, 'ct_topic', true );
			}
		}

		// Legacy CMSMasters/Glossier post templates.
		$content_kind = 'article';
		if ( taxonomy_exists( 'post_template' ) ) {
			$template_slugs = wp_get_post_terms( $post_id, 'post_template', array( 'fields' => 'slugs' ) );
			if ( ! is_wp_error( $template_slugs ) ) {
				if ( in_array( 'opinion', $template_slugs, true ) ) {
					wp_set_object_terms( $post_id, 'opinion', 'ct_format', true );
				}
				if ( in_array( 'video', $template_slugs, true ) ) {
					$content_kind = 'video';
					update_post_meta( $post_id, '_ct_legacy_content_kind', 'video' );
					$report['video_mapped']++;
				}
				if ( in_array( 'podcast', $template_slugs, true ) || in_array( 'podcast-2', $template_slugs, true ) ) {
					$content_kind = 'podcast';
					update_post_meta( $post_id, '_ct_legacy_content_kind', 'podcast' );
					$report['podcast_mapped']++;
				}
			}
		}
		update_post_meta( $post_id, '_ct_content_kind', $content_kind );

		// Existing subscriber term → Creed Pro.
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

		// If a post has real categories, remove Uncategorized from that post only.
		$uncat = get_category_by_slug( 'uncategorized' );
		if ( $uncat && count( $category_terms ) > 1 && has_category( $uncat->term_id, $post_id ) ) {
			wp_remove_object_terms( $post_id, (int) $uncat->term_id, 'category' );
			$report['uncategorized_cleaned']++;
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
	$legacy = array_values( array_filter( array_map( 'absint', (array) get_option( 'ct_legacy_demo_tag_ids', array() ) ) ) );
	if ( $legacy ) {
		$args['exclude'] = array_unique( array_merge( (array) ( $args['exclude'] ?? array() ), $legacy ) );
	}
	return $args;
}
add_filter( 'get_terms_args', 'ct_core_hide_legacy_demo_tags', 10, 2 );
