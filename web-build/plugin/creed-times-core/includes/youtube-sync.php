<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

function ct_core_youtube_handle_url() {
	return trim( (string) get_option( 'ct_youtube_handle_url', 'https://www.youtube.com/@creedtimes' ) );
}

function ct_core_resolve_youtube_channel_id() {
	$stored = trim( (string) get_option( 'ct_youtube_channel_id', '' ) );
	if ( $stored ) { return $stored; }

	$cached = get_transient( 'ct_youtube_channel_id' );
	if ( $cached ) { return $cached; }

	$response = wp_remote_get( ct_core_youtube_handle_url(), array(
		'timeout' => 15,
		'redirection' => 5,
		'user-agent' => 'Mozilla/5.0 CreedTimes/2.3',
	) );
	if ( is_wp_error( $response ) ) { return ''; }

	$html = wp_remote_retrieve_body( $response );
	$patterns = array(
		'/"channelId":"(UC[a-zA-Z0-9_-]+)"/',
		'/"externalId":"(UC[a-zA-Z0-9_-]+)"/',
		'#youtube\.com/channel/(UC[a-zA-Z0-9_-]+)#',
	);
	foreach ( $patterns as $pattern ) {
		if ( preg_match( $pattern, $html, $m ) ) {
			$id = sanitize_text_field( $m[1] );
			update_option( 'ct_youtube_channel_id', $id );
			set_transient( 'ct_youtube_channel_id', $id, DAY_IN_SECONDS * 7 );
			return $id;
		}
	}
	return '';
}

function ct_core_find_youtube_video( $video_id ) {
	$ids = get_posts( array(
		'post_type' => 'ct_video',
		'post_status' => 'any',
		'posts_per_page' => 1,
		'fields' => 'ids',
		'meta_key' => '_ct_youtube_video_id',
		'meta_value' => $video_id,
	) );
	return $ids ? (int) $ids[0] : 0;
}

function ct_core_sideload_youtube_thumbnail( $post_id, $video_id, $feed_url = '' ) {
	if ( has_post_thumbnail( $post_id ) || ! $video_id ) { return; }
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$candidates = array_filter( array(
		'https://i.ytimg.com/vi/' . rawurlencode( $video_id ) . '/maxresdefault.jpg',
		'https://i.ytimg.com/vi/' . rawurlencode( $video_id ) . '/sddefault.jpg',
		'https://i.ytimg.com/vi/' . rawurlencode( $video_id ) . '/hqdefault.jpg',
		$feed_url,
	) );

	foreach ( $candidates as $url ) {
		$head = wp_remote_head( $url, array( 'timeout' => 8, 'redirection' => 3 ) );
		if ( is_wp_error( $head ) || 200 !== (int) wp_remote_retrieve_response_code( $head ) ) {
			continue;
		}

		$tmp = download_url( $url, 15 );
		if ( is_wp_error( $tmp ) ) { continue; }

		$file = array(
			'name' => 'youtube-' . sanitize_file_name( $video_id ) . '.jpg',
			'tmp_name' => $tmp,
		);
		$attachment_id = media_handle_sideload( $file, $post_id, get_the_title( $post_id ) );
		if ( is_wp_error( $attachment_id ) ) {
			@unlink( $tmp );
			continue;
		}

		set_post_thumbnail( $post_id, $attachment_id );
		update_post_meta( $post_id, '_ct_youtube_thumbnail_source', esc_url_raw( $url ) );
		break;
	}
}

function ct_core_sync_youtube() {
	if ( ! post_type_exists( 'ct_video' ) ) { return array( 'error' => 'Video post type is unavailable.' ); }

	$channel_id = ct_core_resolve_youtube_channel_id();
	if ( ! $channel_id ) { return array( 'error' => 'Could not resolve the YouTube channel ID. Add it manually in Creed Times settings.' ); }

	$feed_url = 'https://www.youtube.com/feeds/videos.xml?channel_id=' . rawurlencode( $channel_id );
	$response = wp_remote_get( $feed_url, array( 'timeout' => 15, 'user-agent' => 'CreedTimes/2.3' ) );
	if ( is_wp_error( $response ) ) { return array( 'error' => $response->get_error_message() ); }

	$body = wp_remote_retrieve_body( $response );
	if ( ! $body ) { return array( 'error' => 'YouTube returned an empty feed.' ); }

	libxml_use_internal_errors( true );
	$xml = simplexml_load_string( $body );
	if ( ! $xml ) { return array( 'error' => 'YouTube feed could not be parsed.' ); }

	$created = 0;
	$updated = 0;
	foreach ( $xml->entry as $entry ) {
		$yt = $entry->children( 'http://www.youtube.com/xml/schemas/2015' );
		$media = $entry->children( 'http://search.yahoo.com/mrss/' );
		$video_id = sanitize_text_field( (string) $yt->videoId );
		if ( ! $video_id ) { continue; }

		$title = sanitize_text_field( (string) $entry->title );
		$link = 'https://www.youtube.com/watch?v=' . rawurlencode( $video_id );
		$published = strtotime( (string) $entry->published );
		$description = '';
		$thumbnail = '';

		if ( isset( $media->group ) ) {
			$description = sanitize_textarea_field( (string) $media->group->description );
			if ( isset( $media->group->thumbnail ) ) {
				$attrs = $media->group->thumbnail->attributes();
				$thumbnail = isset( $attrs['url'] ) ? esc_url_raw( (string) $attrs['url'] ) : '';
			}
		}

		$post_id = ct_core_find_youtube_video( $video_id );
		$data = array(
			'post_type' => 'ct_video',
			'post_status' => 'publish',
			'post_title' => $title,
			'post_excerpt' => wp_trim_words( $description, 34 ),
		);
		if ( $published ) {
			$data['post_date'] = wp_date( 'Y-m-d H:i:s', $published );
			$data['post_date_gmt'] = gmdate( 'Y-m-d H:i:s', $published );
		}

		if ( $post_id ) {
			$data['ID'] = $post_id;
			wp_update_post( wp_slash( $data ) );
			$updated++;
		} else {
			$post_id = wp_insert_post( wp_slash( $data ) );
			if ( ! $post_id || is_wp_error( $post_id ) ) { continue; }
			$created++;
		}

		update_post_meta( $post_id, '_ct_youtube_video_id', $video_id );
		update_post_meta( $post_id, '_ct_video_url', $link );
		update_post_meta( $post_id, '_ct_show_name', 'Creed Times YouTube' );
		update_post_meta( $post_id, '_ct_content_kind', 'video' );
		update_post_meta( $post_id, '_ct_source', 'youtube' );
		if ( taxonomy_exists( 'ct_format' ) ) {
			wp_set_object_terms( $post_id, 'news', 'ct_format', true );
		}
		ct_core_sideload_youtube_thumbnail( $post_id, $video_id, $thumbnail );
	}

	$result = array(
		'created' => $created,
		'updated' => $updated,
		'channel_id' => $channel_id,
		'time' => time(),
	);
	update_option( 'ct_youtube_last_sync', $result );
	return $result;
}

function ct_core_youtube_cron() {
	ct_core_sync_youtube();
}
add_action( 'ct_core_youtube_sync_event', 'ct_core_youtube_cron' );

function ct_core_ensure_youtube_schedule() {
	if ( ! wp_next_scheduled( 'ct_core_youtube_sync_event' ) ) {
		wp_schedule_event( time() + 300, 'hourly', 'ct_core_youtube_sync_event' );
	}
}
add_action( 'init', 'ct_core_ensure_youtube_schedule', 100 );
