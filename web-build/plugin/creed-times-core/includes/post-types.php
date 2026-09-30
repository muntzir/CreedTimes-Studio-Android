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
			'plural'   => 'Shorts',
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
