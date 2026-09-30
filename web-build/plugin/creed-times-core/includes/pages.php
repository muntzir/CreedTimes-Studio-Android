<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

function ct_core_required_pages() {
	return array(
		'about-us' => array( 'title' => 'About Us', 'template' => 'page-about-us.php' ),
		'authors' => array( 'title' => 'Authors', 'template' => 'page-authors.php' ),
		'contribute' => array( 'title' => 'Contribute', 'template' => 'page-contribute.php' ),
		'contact' => array( 'title' => 'Contact', 'template' => 'page-contact.php' ),
		'profile' => array( 'title' => 'Profile', 'template' => 'page-profile.php' ),
		'creed-pro' => array( 'title' => 'Creed Pro', 'template' => 'page-creed-pro.php' ),
		'app' => array( 'title' => 'Creed Times App', 'template' => 'page-app.php' ),
	);
}

function ct_core_ensure_required_pages() {
	foreach ( ct_core_required_pages() as $slug => $config ) {
		$page = get_page_by_path( $slug );
		if ( ! $page ) {
			$page_id = wp_insert_post( array(
				'post_type' => 'page',
				'post_status' => 'publish',
				'post_title' => $config['title'],
				'post_name' => $slug,
				'post_content' => '',
			) );
		} else {
			$page_id = $page->ID;
			if ( 'publish' !== $page->post_status ) {
				wp_update_post( array( 'ID' => $page_id, 'post_status' => 'publish' ) );
			}
		}

		if ( $page_id && ! is_wp_error( $page_id ) ) {
			update_post_meta( $page_id, '_wp_page_template', $config['template'] );
		}
	}
}
add_action( 'init', 'ct_core_ensure_required_pages', 90 );

function ct_core_force_page_templates( $template ) {
	if ( ! is_page() ) { return $template; }

	$slug = get_post_field( 'post_name', get_queried_object_id() );
	$pages = ct_core_required_pages();
	if ( isset( $pages[ $slug ] ) ) {
		$theme_template = locate_template( $pages[ $slug ]['template'] );
		if ( $theme_template ) {
			return $theme_template;
		}
	}
	return $template;
}
add_filter( 'template_include', 'ct_core_force_page_templates', 99 );

function ct_core_allow_apk_uploads( $mimes ) {
	$mimes['apk'] = 'application/vnd.android.package-archive';
	return $mimes;
}
add_filter( 'upload_mimes', 'ct_core_allow_apk_uploads' );

function ct_core_detect_app_attachment() {
	if ( get_option( 'ct_app_download_url' ) ) { return; }

	$attachments = get_posts( array(
		'post_type' => 'attachment',
		'post_status' => 'inherit',
		'posts_per_page' => 20,
		'orderby' => 'date',
		'order' => 'DESC',
		's' => 'CreedTimes',
	) );

	foreach ( $attachments as $attachment ) {
		$file = get_attached_file( $attachment->ID );
		if ( $file && preg_match( '/\.apk$/i', $file ) ) {
			$url = wp_get_attachment_url( $attachment->ID );
			if ( $url ) {
				update_option( 'ct_app_download_url', esc_url_raw( $url ) );
				if ( preg_match( '/v([0-9]+(?:\.[0-9]+)+)/i', basename( $file ), $m ) ) {
					update_option( 'ct_app_version', sanitize_text_field( $m[1] ) );
				}
				break;
			}
		}
	}
}
add_action( 'init', 'ct_core_detect_app_attachment', 95 );
