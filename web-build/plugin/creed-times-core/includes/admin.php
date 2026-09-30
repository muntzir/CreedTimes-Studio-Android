<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

function ct_core_admin_menu() {
	add_menu_page(
		'Creed Times',
		'Creed Times',
		'edit_posts',
		'creed-times-core',
		'ct_core_admin_page',
		'dashicons-admin-site-alt3',
		3
	);

	add_submenu_page( 'creed-times-core', 'Creed Times Dashboard', 'Dashboard', 'edit_posts', 'creed-times-core', 'ct_core_admin_page' );
	add_submenu_page( 'creed-times-core', 'Editorial Structure', 'Editorial Structure', 'manage_categories', 'ct-editorial-structure', 'ct_core_structure_page' );
	add_submenu_page( 'creed-times-core', 'Creed Times Settings', 'Settings', 'manage_options', 'ct-settings', 'ct_core_settings_page' );

	// Native WordPress screens grouped under the Creed Times newsroom menu.
	global $submenu;
	$submenu['creed-times-core'][] = array( 'Articles', 'edit_posts', 'edit.php' );
	$submenu['creed-times-core'][] = array( 'Authors', 'list_users', 'users.php' );
	$submenu['creed-times-core'][] = array( 'Media Library', 'upload_files', 'upload.php' );
	if ( function_exists( 'pmpro_url' ) ) {
		$submenu['creed-times-core'][] = array( 'Members / Creed Pro', 'manage_options', admin_url( 'admin.php?page=pmpro-dashboard' ) );
	}
}
add_action( 'admin_menu', 'ct_core_admin_menu', 20 );

function ct_core_admin_counts() {
	return array(
		'articles' => (int) wp_count_posts( 'post' )->publish,
		'videos'   => post_type_exists( 'ct_video' ) ? (int) wp_count_posts( 'ct_video' )->publish : 0,
		'shorts'   => post_type_exists( 'ct_short' ) ? (int) wp_count_posts( 'ct_short' )->publish : 0,
		'podcasts' => post_type_exists( 'ct_podcast' ) ? (int) wp_count_posts( 'ct_podcast' )->publish : 0,
		'visuals'  => post_type_exists( 'ct_artwork' ) ? (int) wp_count_posts( 'ct_artwork' )->publish : 0,
		'authors'  => count_users()['total_users'] ?? 0,
	);
}

function ct_core_admin_page() {
	if ( ! current_user_can( 'edit_posts' ) ) { return; }
	$counts = ct_core_admin_counts();
	$last_sync = get_option( 'ct_youtube_last_sync', array() );
	$last_migration = get_option( 'ct_core_last_migration', array() );
	?>
	<div class="wrap ct-admin-wrap">
		<h1>Creed Times Newsroom</h1>
		<p class="description">Articles, video, reels, podcasts, authors, topics and publishing settings in one place.</p>

		<div class="ct-admin-cards">
			<a href="<?php echo esc_url( admin_url( 'edit.php' ) ); ?>"><span>Articles</span><strong><?php echo esc_html( $counts['articles'] ); ?></strong><small>Manage written stories</small></a>
			<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=ct_video' ) ); ?>"><span>Videos</span><strong><?php echo esc_html( $counts['videos'] ); ?></strong><small>YouTube + native video</small></a>
			<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=ct_short' ) ); ?>"><span>Reels / Shorts</span><strong><?php echo esc_html( $counts['shorts'] ); ?></strong><small>Vertical video</small></a>
			<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=ct_podcast' ) ); ?>"><span>Podcasts</span><strong><?php echo esc_html( $counts['podcasts'] ); ?></strong><small>Audio & conversations</small></a>
			<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=ct_artwork' ) ); ?>"><span>Visual Stories</span><strong><?php echo esc_html( $counts['visuals'] ); ?></strong><small>Artwork & infographics</small></a>
			<a href="<?php echo esc_url( admin_url( 'users.php' ) ); ?>"><span>Authors</span><strong><?php echo esc_html( $counts['authors'] ); ?></strong><small>Profiles & designations</small></a>
		</div>

		<div class="ct-admin-grid">
			<section class="ct-admin-panel">
				<h2>Editorial Structure</h2>
				<p><strong>Content:</strong> Articles · Videos · Reels / Shorts · Podcasts · Visual Stories</p>
				<p><strong>Formats:</strong> News · Analysis · Opinion · Explainer · Interview · Long Read · Documentary</p>
				<p><strong>Languages:</strong> English · Urdu</p>
				<p><strong>Regions:</strong> Pakistan · West Asia · World</p>
				<p><strong>Access:</strong> Free · Creed Pro</p>
				<p><a class="button button-primary" href="<?php echo esc_url( admin_url( 'admin.php?page=ct-editorial-structure' ) ); ?>">Review & Clean Structure</a></p>
			</section>

			<section class="ct-admin-panel">
				<h2>YouTube Sync</h2>
				<p>New videos from the Creed Times YouTube channel are imported automatically into the Videos section.</p>
				<?php if ( $last_sync && empty( $last_sync['error'] ) ) : ?><p><small>Last sync: <?php echo esc_html( wp_date( 'd M Y H:i', $last_sync['time'] ?? time() ) ); ?> · <?php echo esc_html( $last_sync['created'] ?? 0 ); ?> new · <?php echo esc_html( $last_sync['updated'] ?? 0 ); ?> updated</small></p><?php endif; ?>
				<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
					<input type="hidden" name="action" value="ct_youtube_sync_now">
					<?php wp_nonce_field( 'ct_youtube_sync_now' ); ?>
					<button class="button button-secondary" type="submit">Sync YouTube Now</button>
				</form>
			</section>

			<section class="ct-admin-panel">
				<h2>Required Pages</h2>
				<p>About, Authors, Contribute, Contact, Profile, Creed Pro and App pages are maintained automatically by Creed Times Core.</p>
				<p><a class="button" href="<?php echo esc_url( home_url( '/authors/' ) ); ?>" target="_blank" rel="noopener">Open Authors ↗</a> <a class="button" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" target="_blank" rel="noopener">Open Contact ↗</a></p>
			</section>

			<section class="ct-admin-panel">
				<h2>Migration Status</h2>
				<?php if ( $last_migration ) : ?><p>Last cleanup: <?php echo esc_html( wp_date( 'd M Y H:i', $last_migration['time'] ?? time() ) ); ?></p><?php else : ?><p>The editorial cleanup has not been run on this install yet.</p><?php endif; ?>
				<p><a class="button button-primary" href="<?php echo esc_url( admin_url( 'admin.php?page=ct-editorial-structure' ) ); ?>">Open Safe Cleanup</a></p>
			</section>
		</div>
	</div>
	<?php
}

function ct_core_structure_page() {
	if ( ! current_user_can( 'manage_categories' ) ) { return; }
	$notice = '';
	$report = null;
	if ( isset( $_POST['ct_core_run_migration'] ) ) {
		check_admin_referer( 'ct_core_migrate' );
		$report = ct_core_run_safe_migration();
		$notice = 'Creed Times editorial cleanup completed.';
	}
	?>
	<div class="wrap ct-admin-wrap">
		<h1>Editorial Structure & Cleanup</h1>
		<?php if ( $notice ) : ?><div class="notice notice-success is-dismissible"><p><?php echo esc_html( $notice ); ?></p></div><?php endif; ?>

		<section class="ct-admin-panel ct-admin-panel--wide">
			<h2>Safe Creed Times Migration</h2>
			<p>This keeps existing posts, media, authors, dates, slugs and URLs. It adds the new Language / Region / Editorial Format / Topic structure, maps legacy Video/Podcast templates, removes Uncategorized only where a real category already exists, maps subscriber posts to Creed Pro, and marks known Glossier/demo tags so the new front-end hides them.</p>
			<form method="post">
				<?php wp_nonce_field( 'ct_core_migrate' ); ?>
				<button class="button button-primary button-hero" name="ct_core_run_migration" value="1">Run Safe Editorial Cleanup</button>
			</form>
			<?php if ( $report ) : ?>
				<ul class="ct-migration-report">
					<li><strong><?php echo esc_html( $report['posts_scanned'] ); ?></strong> posts scanned</li>
					<li><strong><?php echo esc_html( $report['terms_mapped'] ); ?></strong> classifications mapped</li>
					<li><strong><?php echo esc_html( $report['topics_created'] ); ?></strong> useful topics created from repeated tags</li>
					<li><strong><?php echo esc_html( $report['video_mapped'] ); ?></strong> legacy videos identified</li>
					<li><strong><?php echo esc_html( $report['podcast_mapped'] ); ?></strong> legacy podcasts identified</li>
					<li><strong><?php echo esc_html( $report['pro_mapped'] ); ?></strong> subscriber posts mapped to Creed Pro</li>
					<li><strong><?php echo esc_html( $report['demo_tags_marked'] ); ?></strong> demo tags hidden</li>
					<li><strong><?php echo esc_html( $report['uncategorized_cleaned'] ); ?></strong> unnecessary Uncategorized assignments removed</li>
				</ul>
			<?php endif; ?>
		</section>

		<div class="ct-admin-grid">
			<section class="ct-admin-panel"><h2>Languages</h2><p><a href="<?php echo esc_url( admin_url( 'edit-tags.php?taxonomy=ct_language&post_type=post' ) ); ?>">Manage English / Urdu →</a></p></section>
			<section class="ct-admin-panel"><h2>Editorial Formats</h2><p><a href="<?php echo esc_url( admin_url( 'edit-tags.php?taxonomy=ct_format&post_type=post' ) ); ?>">Manage formats →</a></p></section>
			<section class="ct-admin-panel"><h2>Regions</h2><p><a href="<?php echo esc_url( admin_url( 'edit-tags.php?taxonomy=ct_region&post_type=post' ) ); ?>">Manage regions →</a></p></section>
			<section class="ct-admin-panel"><h2>Topics</h2><p><a href="<?php echo esc_url( admin_url( 'edit-tags.php?taxonomy=ct_topic&post_type=post' ) ); ?>">Manage topics →</a></p></section>
		</div>
	</div>
	<?php
}

function ct_core_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) { return; }
	$notice = '';

	if ( isset( $_POST['ct_core_save_settings'] ) ) {
		check_admin_referer( 'ct_core_settings' );
		$settings = array(
			'ct_pro_level_ids' => sanitize_text_field( wp_unslash( $_POST['ct_pro_level_ids'] ?? '' ) ),
			'ct_contact_email' => sanitize_email( wp_unslash( $_POST['ct_contact_email'] ?? 'info@creedtimes.com' ) ),
			'ct_contact_phone' => sanitize_text_field( wp_unslash( $_POST['ct_contact_phone'] ?? '+92 308 3829035' ) ),
			'ct_contact_whatsapp' => preg_replace( '/\D+/', '', wp_unslash( $_POST['ct_contact_whatsapp'] ?? '923083829035' ) ),
			'ct_youtube_handle_url' => esc_url_raw( wp_unslash( $_POST['ct_youtube_handle_url'] ?? 'https://www.youtube.com/@creedtimes' ) ),
			'ct_youtube_channel_id' => sanitize_text_field( wp_unslash( $_POST['ct_youtube_channel_id'] ?? '' ) ),
			'ct_app_download_url' => esc_url_raw( wp_unslash( $_POST['ct_app_download_url'] ?? '' ) ),
			'ct_app_version' => sanitize_text_field( wp_unslash( $_POST['ct_app_version'] ?? '1.7.0' ) ),
		);
		foreach ( $settings as $key => $value ) { update_option( $key, $value ); }
		delete_transient( 'ct_youtube_channel_id' );
		$notice = 'Settings saved.';
	}

	$fields = array(
		'ct_contact_email' => array( 'Contact email', get_option( 'ct_contact_email', 'info@creedtimes.com' ), 'email' ),
		'ct_contact_phone' => array( 'Display phone', get_option( 'ct_contact_phone', '+92 308 3829035' ), 'text' ),
		'ct_contact_whatsapp' => array( 'WhatsApp number (digits)', get_option( 'ct_contact_whatsapp', '923083829035' ), 'text' ),
		'ct_youtube_handle_url' => array( 'YouTube channel URL', get_option( 'ct_youtube_handle_url', 'https://www.youtube.com/@creedtimes' ), 'url' ),
		'ct_youtube_channel_id' => array( 'YouTube Channel ID (optional)', get_option( 'ct_youtube_channel_id', '' ), 'text' ),
		'ct_app_download_url' => array( 'Android APK download URL', get_option( 'ct_app_download_url', '' ), 'url' ),
		'ct_app_version' => array( 'App version', get_option( 'ct_app_version', '1.7.0' ), 'text' ),
		'ct_pro_level_ids' => array( 'PMPro Creed Pro Level IDs', get_option( 'ct_pro_level_ids', '' ), 'text' ),
	);
	?>
	<div class="wrap ct-admin-wrap">
		<h1>Creed Times Settings</h1>
		<?php if ( $notice ) : ?><div class="notice notice-success is-dismissible"><p><?php echo esc_html( $notice ); ?></p></div><?php endif; ?>
		<form method="post" class="ct-admin-panel ct-admin-panel--wide">
			<?php wp_nonce_field( 'ct_core_settings' ); ?>
			<table class="form-table">
				<?php foreach ( $fields as $key => $field ) : ?>
					<tr><th><label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $field[0] ); ?></label></th><td><input class="regular-text" id="<?php echo esc_attr( $key ); ?>" type="<?php echo esc_attr( $field[2] ); ?>" name="<?php echo esc_attr( $key ); ?>" value="<?php echo esc_attr( $field[1] ); ?>"></td></tr>
				<?php endforeach; ?>
			</table>
			<p class="description">Upload the latest APK to Media Library first; Creed Times Core can auto-detect a CreedTimes .apk attachment, or paste its media URL above.</p>
			<p><button class="button button-primary" name="ct_core_save_settings" value="1">Save Settings</button></p>
		</form>
	</div>
	<?php
}

function ct_core_youtube_sync_now() {
	if ( ! current_user_can( 'edit_posts' ) ) { wp_die( 'Permission denied.' ); }
	check_admin_referer( 'ct_youtube_sync_now' );
	$result = ct_core_sync_youtube();
	if ( isset( $result['error'] ) ) {
		update_option( 'ct_youtube_last_sync', array( 'error' => $result['error'], 'time' => time() ) );
	}
	wp_safe_redirect( admin_url( 'admin.php?page=creed-times-core' ) );
	exit;
}
add_action( 'admin_post_ct_youtube_sync_now', 'ct_core_youtube_sync_now' );

function ct_core_user_profile_fields( $user ) {
	if ( ! current_user_can( 'edit_user', $user->ID ) ) { return; }
	?>
	<h2>Creed Times Author Profile</h2>
	<table class="form-table">
		<tr><th><label for="ct_designation">Designation</label></th><td><input type="text" class="regular-text" id="ct_designation" name="ct_designation" value="<?php echo esc_attr( get_user_meta( $user->ID, 'ct_designation', true ) ); ?>" placeholder="Author"></td></tr>
	</table>
	<?php
}
add_action( 'show_user_profile', 'ct_core_user_profile_fields' );
add_action( 'edit_user_profile', 'ct_core_user_profile_fields' );

function ct_core_save_user_profile_fields( $user_id ) {
	if ( ! current_user_can( 'edit_user', $user_id ) ) { return; }
	if ( isset( $_POST['ct_designation'] ) ) {
		update_user_meta( $user_id, 'ct_designation', sanitize_text_field( wp_unslash( $_POST['ct_designation'] ) ) );
	}
}
add_action( 'personal_options_update', 'ct_core_save_user_profile_fields' );
add_action( 'edit_user_profile_update', 'ct_core_save_user_profile_fields' );

function ct_core_admin_columns( $columns ) {
	$columns['ct_kind'] = 'Type';
	$columns['ct_access'] = 'Access';
	$columns['ct_format'] = 'Format';
	$columns['ct_language'] = 'Language';
	$columns['ct_region'] = 'Region';
	return $columns;
}
add_filter( 'manage_posts_columns', 'ct_core_admin_columns' );

function ct_core_admin_column_content( $column, $post_id ) {
	if ( 'ct_kind' === $column ) {
		echo esc_html( ucfirst( get_post_meta( $post_id, '_ct_content_kind', true ) ?: 'article' ) );
	}
	if ( 'ct_access' === $column ) {
		echo esc_html( 'pro' === get_post_meta( $post_id, '_ct_access_level', true ) ? 'Creed Pro' : 'Free' );
	}
	foreach ( array( 'ct_format' => 'ct_format', 'ct_language' => 'ct_language', 'ct_region' => 'ct_region' ) as $col => $tax ) {
		if ( $column === $col ) {
			$terms = get_the_terms( $post_id, $tax );
			if ( $terms && ! is_wp_error( $terms ) ) { echo esc_html( implode( ', ', wp_list_pluck( $terms, 'name' ) ) ); }
		}
	}
}
add_action( 'manage_posts_custom_column', 'ct_core_admin_column_content', 10, 2 );

function ct_core_admin_styles() {
	$screen = get_current_screen();
	if ( ! $screen || false === strpos( $screen->id, 'creed-times' ) ) { return; }
	echo '<style>
	.ct-admin-wrap{max-width:1180px}.ct-admin-cards{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin:22px 0}.ct-admin-cards>a,.ct-admin-panel{background:#fff;border:1px solid #dcdcde;border-radius:14px;padding:20px;text-decoration:none;color:#1d2327}.ct-admin-cards>a{display:flex;flex-direction:column}.ct-admin-cards span{font-weight:700}.ct-admin-cards strong{font-size:30px;margin:8px 0}.ct-admin-cards small{color:#646970}.ct-admin-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px}.ct-admin-panel h2{margin-top:0}.ct-admin-panel--wide{max-width:none;margin-top:18px}.ct-migration-report{display:grid;grid-template-columns:repeat(4,1fr);gap:8px;padding:0;margin-top:20px}.ct-migration-report li{list-style:none;background:#f6f7f7;border-radius:10px;padding:13px}.ct-migration-report strong{display:block;font-size:22px}@media(max-width:800px){.ct-admin-cards{grid-template-columns:1fr 1fr}.ct-admin-grid{grid-template-columns:1fr}.ct-migration-report{grid-template-columns:1fr 1fr}}</style>';
}
add_action( 'admin_head', 'ct_core_admin_styles' );
