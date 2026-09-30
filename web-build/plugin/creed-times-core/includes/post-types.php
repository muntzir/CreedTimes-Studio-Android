<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

function ct_core_register_content_types() {
	$content_types = array(
		'ct_video' => array(
			'singular' => 'Video',
			'plural'   => 'Videos',
			'slug'     => 'videos',
			'icon'     => 'dashicons-video-alt3',
			'supports' => array( 'title', 'editor', 'excerpt', 'thumbnail', 'author', 'comments', 'revisions' ),
		),
		'ct_short' => array(
			'singular' => 'Short',
			'plural'   => 'Reels / Shorts',
			'slug'     => 'shorts',
			'icon'     => 'dashicons-format-video',
			'supports' => array( 'title', 'editor', 'excerpt', 'thumbnail', 'author', 'comments', 'revisions' ),
		),
		'ct_podcast' => array(
			'singular' => 'Podcast',
			'plural'   => 'Podcasts',
			'slug'     => 'podcasts',
			'icon'     => 'dashicons-microphone',
			'supports' => array( 'title', 'editor', 'excerpt', 'thumbnail', 'author', 'comments', 'revisions' ),
		),
		'ct_artwork' => array(
			'singular' => 'Visual Story',
			'plural'   => 'Visual Stories',
			'slug'     => 'visuals',
			'icon'     => 'dashicons-format-image',
			'supports' => array( 'title', 'editor', 'excerpt', 'thumbnail', 'author', 'comments', 'revisions' ),
		),
	);

	foreach ( $content_types as $type => $config ) {
		register_post_type( $type, array(
			'labels' => array(
				'name'          => $config['plural'],
				'singular_name' => $config['singular'],
				'add_new_item'  => 'Add New ' . $config['singular'],
				'edit_item'     => 'Edit ' . $config['singular'],
			),
			'public'       => true,
			'show_in_rest' => true,
			'has_archive'  => true,
			'rewrite'      => array( 'slug' => $config['slug'], 'with_front' => false ),
			'menu_icon'    => $config['icon'],
			'show_in_menu' => 'creed-times-core',
			'supports'     => $config['supports'],
			'taxonomies'   => array( 'post_tag' ),
		) );
	}

	$object_types = array( 'post', 'ct_video', 'ct_short', 'ct_podcast', 'ct_artwork' );
	$taxonomies = array(
		'ct_language' => array(
			'singular' => 'Language',
			'plural'   => 'Languages',
			'slug'     => 'language',
			'hierarchical' => true,
		),
		'ct_format' => array(
			'singular' => 'Editorial Format',
			'plural'   => 'Editorial Formats',
			'slug'     => 'format',
			'hierarchical' => true,
		),
		'ct_region' => array(
			'singular' => 'Region',
			'plural'   => 'Regions',
			'slug'     => 'region',
			'hierarchical' => true,
		),
		'ct_topic' => array(
			'singular' => 'Topic',
			'plural'   => 'Topics',
			'slug'     => 'topic',
			'hierarchical' => false,
		),
	);

	foreach ( $taxonomies as $taxonomy => $config ) {
		register_taxonomy( $taxonomy, $object_types, array(
			'labels' => array(
				'name'          => $config['plural'],
				'singular_name' => $config['singular'],
			),
			'public'            => true,
			'show_in_rest'      => true,
			'hierarchical'      => $config['hierarchical'],
			'rewrite'           => array( 'slug' => $config['slug'], 'with_front' => false ),
			'show_admin_column' => true,
		) );
	}
}
add_action( 'init', 'ct_core_register_content_types', 5 );

function ct_core_seed_terms() {
	$seed = array(
		'ct_language' => array(
			'english' => 'English',
			'urdu'    => 'Urdu',
		),
		'ct_format' => array(
			'news'        => 'News',
			'analysis'    => 'Analysis',
			'opinion'     => 'Opinion',
			'explainer'   => 'Explainer',
			'interview'   => 'Interview',
			'long-read'   => 'Long Read',
			'documentary' => 'Documentary',
		),
		'ct_region' => array(
			'pakistan'  => 'Pakistan',
			'west-asia' => 'West Asia',
			'world'     => 'World',
		),
		'ct_topic' => array(
			'iran'                    => 'Iran',
			'palestine-gaza'          => 'Palestine / Gaza',
			'yemen'                   => 'Yemen',
			'pakistan-politics'       => 'Pakistan Politics',
			'geopolitics'             => 'Geopolitics',
			'economy'                 => 'Economy',
			'technology'              => 'Technology',
			'religion-society'        => 'Religion & Society',
			'international-relations' => 'International Relations',
			'defence'                 => 'Defence',
			'media'                   => 'Media',
		),
	);
	foreach ( $seed as $taxonomy => $terms ) {
		if ( ! taxonomy_exists( $taxonomy ) ) { continue; }
		foreach ( $terms as $slug => $name ) {
			if ( ! term_exists( $slug, $taxonomy ) ) {
				wp_insert_term( $name, $taxonomy, array( 'slug' => $slug ) );
			}
		}
	}
}
add_action( 'init', 'ct_core_seed_terms', 20 );


/**
 * Keep a normalized content-kind meta field on all Creed Times content.
 * This also lets legacy articles that were built as video/podcast posts join the new archives.
 */
function ct_core_set_content_kind_meta( $post_id, $post, $update ) {
	if ( wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id ) ) { return; }

	$kind_map = array(
		'ct_video'   => 'video',
		'ct_short'   => 'short',
		'ct_podcast' => 'podcast',
		'ct_artwork' => 'visual',
		'post'       => get_post_meta( $post_id, '_ct_legacy_content_kind', true ) ?: 'article',
	);
	if ( isset( $kind_map[ $post->post_type ] ) ) {
		update_post_meta( $post_id, '_ct_content_kind', $kind_map[ $post->post_type ] );
	}
}
add_action( 'save_post', 'ct_core_set_content_kind_meta', 20, 3 );
