<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

function ct_core_require_ajax_user() {
	check_ajax_referer( 'ct_core_front', 'nonce' );
	if ( ! is_user_logged_in() ) {
		wp_send_json_error( array( 'message' => 'Please sign in first.' ), 401 );
	}
	return get_current_user_id();
}

function ct_core_toggle_bookmark() {
	$user_id = ct_core_require_ajax_user();
	$post_id = isset( $_POST['postId'] ) ? absint( $_POST['postId'] ) : 0;
	if ( ! $post_id || ! get_post( $post_id ) ) { wp_send_json_error( array( 'message' => 'Invalid story.' ), 400 ); }

	$items = array_values( array_filter( array_map( 'absint', (array) get_user_meta( $user_id, '_ct_bookmarks', true ) ) ) );
	$saved = in_array( $post_id, $items, true );
	if ( $saved ) {
		$items = array_values( array_diff( $items, array( $post_id ) ) );
	} else {
		array_unshift( $items, $post_id );
		$items = array_slice( array_unique( $items ), 0, 250 );
	}
	update_user_meta( $user_id, '_ct_bookmarks', $items );
	wp_send_json_success( array( 'saved' => ! $saved, 'count' => count( $items ) ) );
}
add_action( 'wp_ajax_ct_toggle_bookmark', 'ct_core_toggle_bookmark' );

function ct_core_save_note() {
	$user_id = ct_core_require_ajax_user();
	$post_id = isset( $_POST['postId'] ) ? absint( $_POST['postId'] ) : 0;
	$note = isset( $_POST['note'] ) ? sanitize_textarea_field( wp_unslash( $_POST['note'] ) ) : '';
	if ( ! $post_id || ! get_post( $post_id ) ) { wp_send_json_error( array( 'message' => 'Invalid story.' ), 400 ); }

	$notes = (array) get_user_meta( $user_id, '_ct_private_notes', true );
	if ( '' === trim( $note ) ) {
		unset( $notes[ $post_id ] );
	} else {
		$notes[ $post_id ] = array( 'text' => $note, 'updated' => time() );
	}
	update_user_meta( $user_id, '_ct_private_notes', $notes );
	wp_send_json_success( array( 'saved' => true ) );
}
add_action( 'wp_ajax_ct_save_note', 'ct_core_save_note' );

function ct_core_toggle_author_follow() {
	$user_id = ct_core_require_ajax_user();
	$author_id = isset( $_POST['authorId'] ) ? absint( $_POST['authorId'] ) : 0;
	if ( ! $author_id || ! get_user_by( 'id', $author_id ) ) { wp_send_json_error( array( 'message' => 'Invalid author.' ), 400 ); }

	$items = array_values( array_filter( array_map( 'absint', (array) get_user_meta( $user_id, '_ct_followed_authors', true ) ) ) );
	$following = in_array( $author_id, $items, true );
	if ( $following ) {
		$items = array_values( array_diff( $items, array( $author_id ) ) );
	} else {
		array_unshift( $items, $author_id );
		$items = array_slice( array_unique( $items ), 0, 100 );
	}
	update_user_meta( $user_id, '_ct_followed_authors', $items );
	wp_send_json_success( array( 'following' => ! $following ) );
}
add_action( 'wp_ajax_ct_toggle_author_follow', 'ct_core_toggle_author_follow' );

function ct_core_log_history() {
	$user_id = ct_core_require_ajax_user();
	$post_id = isset( $_POST['postId'] ) ? absint( $_POST['postId'] ) : 0;
	if ( ! $post_id || ! get_post( $post_id ) ) { wp_send_json_error(); }
	$history = array_values( array_filter( array_map( 'absint', (array) get_user_meta( $user_id, '_ct_reading_history', true ) ) ) );
	$history = array_values( array_diff( $history, array( $post_id ) ) );
	array_unshift( $history, $post_id );
	update_user_meta( $user_id, '_ct_reading_history', array_slice( $history, 0, 100 ) );
	wp_send_json_success();
}
add_action( 'wp_ajax_ct_log_history', 'ct_core_log_history' );

function ct_core_save_button_shortcode() {
	$post_id = get_the_ID();
	if ( ! $post_id ) { return ''; }
	$saved = is_user_logged_in() && in_array( $post_id, (array) get_user_meta( get_current_user_id(), '_ct_bookmarks', true ), true );
	return '<button class="ct-save-button ' . ( $saved ? 'is-saved' : '' ) . '" type="button" data-ct-save data-post-id="' . esc_attr( $post_id ) . '" aria-pressed="' . ( $saved ? 'true' : 'false' ) . '"><span aria-hidden="true">♡</span><span data-save-label>' . ( $saved ? 'Saved' : 'Save' ) . '</span></button>';
}
add_shortcode( 'ct_save_button', 'ct_core_save_button_shortcode' );

function ct_core_private_notes_shortcode() {
	if ( ! is_user_logged_in() ) {
		return '<div class="ct-private-notes"><strong>Private Notes</strong><p><a href="' . esc_url( wp_login_url( get_permalink() ) ) . '">Sign in</a> to add a private note to this story.</p></div>';
	}
	$post_id = get_the_ID();
	$notes = (array) get_user_meta( get_current_user_id(), '_ct_private_notes', true );
	$value = isset( $notes[ $post_id ]['text'] ) ? $notes[ $post_id ]['text'] : '';
	ob_start();
	?>
	<div class="ct-private-notes" data-ct-note-wrap data-post-id="<?php echo esc_attr( $post_id ); ?>">
		<div><span class="ct-overline">PRIVATE</span><h3>My Notes</h3><p>Only you can see these notes.</p></div>
		<textarea data-ct-note placeholder="Add a private note…"><?php echo esc_textarea( $value ); ?></textarea>
		<div class="ct-private-notes__actions"><button type="button" data-ct-note-save>Save note</button><span data-ct-note-status aria-live="polite"></span></div>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'ct_private_notes', 'ct_core_private_notes_shortcode' );

function ct_core_follow_author_shortcode( $atts ) {
	$atts = shortcode_atts( array( 'author' => 0 ), $atts );
	$author_id = absint( $atts['author'] );
	if ( ! $author_id ) { return ''; }
	$following = is_user_logged_in() && in_array( $author_id, (array) get_user_meta( get_current_user_id(), '_ct_followed_authors', true ), true );
	return '<button class="ct-primary-btn" type="button" data-ct-follow-author data-author-id="' . esc_attr( $author_id ) . '" aria-pressed="' . ( $following ? 'true' : 'false' ) . '"><span data-follow-label>' . ( $following ? 'Following' : 'Follow Author' ) . '</span></button>';
}
add_shortcode( 'ct_follow_author_button', 'ct_core_follow_author_shortcode' );

function ct_core_dashboard_post_cards( $ids ) {
	$ids = array_values( array_filter( array_map( 'absint', (array) $ids ) ) );
	if ( ! $ids ) { return '<div class="ct-empty-state"><p>Nothing saved here yet.</p></div>'; }
	$q = new WP_Query( array( 'post_type' => 'any', 'post_status' => 'publish', 'post__in' => $ids, 'orderby' => 'post__in', 'posts_per_page' => 30 ) );
	ob_start();
	echo '<div class="ct-dashboard-list">';
	while ( $q->have_posts() ) {
		$q->the_post();
		echo '<article class="ct-dashboard-list__item"><a href="' . esc_url( get_permalink() ) . '">';
		if ( has_post_thumbnail() ) { echo get_the_post_thumbnail( get_the_ID(), 'thumbnail', array( 'loading' => 'lazy' ) ); }
		echo '<span><strong>' . esc_html( get_the_title() ) . '</strong><small>' . esc_html( get_the_date( 'd M Y' ) ) . '</small></span></a></article>';
	}
	wp_reset_postdata();
	echo '</div>';
	return ob_get_clean();
}

function ct_core_member_dashboard_shortcode() {
	if ( ! is_user_logged_in() ) { return ''; }
	$user = wp_get_current_user();
	$tab = isset( $_GET['tab'] ) ? sanitize_key( wp_unslash( $_GET['tab'] ) ) : 'overview';
	$bookmarks = (array) get_user_meta( $user->ID, '_ct_bookmarks', true );
	$history = (array) get_user_meta( $user->ID, '_ct_reading_history', true );
	$notes = (array) get_user_meta( $user->ID, '_ct_private_notes', true );
	$authors = (array) get_user_meta( $user->ID, '_ct_followed_authors', true );
	$base = get_permalink();
	ob_start();
	?>
	<div class="ct-member-dashboard">
		<aside class="ct-member-dashboard__sidebar">
			<div class="ct-member-dashboard__user"><?php echo get_avatar( $user->ID, 50 ); ?><div><strong><?php echo esc_html( $user->display_name ); ?></strong><span><?php echo ct_core_user_has_pro( $user->ID ) ? 'Creed Pro' : 'Free account'; ?></span></div></div>
			<nav class="ct-member-dashboard__nav">
				<?php foreach ( array( 'overview'=>'Overview', 'saved'=>'Saved Articles', 'notes'=>'Private Notes', 'history'=>'Reading History', 'authors'=>'Followed Authors', 'membership'=>'Membership' ) as $key => $label ) : ?>
					<a class="<?php echo $tab === $key ? 'is-active' : ''; ?>" href="<?php echo esc_url( add_query_arg( 'tab', $key, $base ) ); ?>"><?php echo esc_html( $label ); ?></a>
				<?php endforeach; ?>
			</nav>
		</aside>
		<section class="ct-member-dashboard__content">
			<?php if ( 'overview' === $tab ) : ?>
				<div class="ct-dashboard-grid"><article class="ct-dashboard-card"><strong><?php echo count( $bookmarks ); ?></strong><span>Saved stories</span></article><article class="ct-dashboard-card"><strong><?php echo count( $notes ); ?></strong><span>Private notes</span></article><article class="ct-dashboard-card"><strong><?php echo count( $authors ); ?></strong><span>Followed authors</span></article></div>
				<h2>Continue Reading</h2><?php echo ct_core_dashboard_post_cards( array_slice( $history, 0, 8 ) ); ?>
			<?php elseif ( 'saved' === $tab ) : ?><h1>Saved Articles</h1><?php echo ct_core_dashboard_post_cards( $bookmarks ); ?>
			<?php elseif ( 'history' === $tab ) : ?><h1>Reading History</h1><?php echo ct_core_dashboard_post_cards( $history ); ?>
			<?php elseif ( 'notes' === $tab ) : ?><h1>Private Notes</h1><div class="ct-dashboard-notes"><?php foreach ( $notes as $post_id => $note ) : if ( ! get_post( $post_id ) ) continue; ?><article><h3><a href="<?php echo esc_url( get_permalink( $post_id ) ); ?>"><?php echo esc_html( get_the_title( $post_id ) ); ?></a></h3><p><?php echo esc_html( $note['text'] ?? '' ); ?></p></article><?php endforeach; ?></div>
			<?php elseif ( 'authors' === $tab ) : ?><h1>Followed Authors</h1><div class="ct-dashboard-authors"><?php foreach ( $authors as $author_id ) : $a = get_user_by( 'id', $author_id ); if ( ! $a ) continue; ?><a href="<?php echo esc_url( get_author_posts_url( $a->ID ) ); ?>"><?php echo get_avatar( $a->ID, 54 ); ?><span><strong><?php echo esc_html( $a->display_name ); ?></strong><small><?php echo esc_html( get_user_meta( $a->ID, 'ct_designation', true ) ?: 'Author' ); ?></small></span></a><?php endforeach; ?></div>
			<?php elseif ( 'membership' === $tab ) : ?><h1>Membership</h1><div class="ct-dashboard-card"><strong><?php echo ct_core_user_has_pro( $user->ID ) ? 'Creed Pro' : 'Free'; ?></strong><span>Current plan</span><?php if ( ! ct_core_user_has_pro( $user->ID ) ) : ?><p><a href="<?php echo esc_url( home_url( '/creed-pro/' ) ); ?>">Explore Creed Pro →</a></p><?php endif; ?></div>
			<?php endif; ?>
		</section>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'ct_member_dashboard', 'ct_core_member_dashboard_shortcode' );
