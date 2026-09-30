<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

function ct_core_admin_menu() {
	add_menu_page(
		'Creed Times',
		'Creed Times',
		'manage_options',
		'creed-times-core',
		'ct_core_admin_page',
		'dashicons-admin-site-alt3',
		3
	);
}
add_action( 'admin_menu', 'ct_core_admin_menu' );

function ct_core_admin_page() {
	if ( ! current_user_can( 'manage_options' ) ) { return; }

	$notice = '';
	if ( isset( $_POST['ct_core_save_settings'] ) ) {
		check_admin_referer( 'ct_core_settings' );
		update_option( 'ct_pro_level_ids', sanitize_text_field( wp_unslash( $_POST['ct_pro_level_ids'] ?? '' ) ) );
		$notice = 'Settings saved.';
	}

	if ( isset( $_POST['ct_core_run_migration'] ) ) {
		check_admin_referer( 'ct_core_migrate' );
		$report = ct_core_run_safe_migration();
		$notice = sprintf(
			'Migration complete: %d posts scanned, %d classifications mapped, %d subscriber posts mapped to Pro, %d demo tags marked.',
			$report['posts_scanned'],
			$report['terms_mapped'],
			$report['pro_mapped'],
			$report['demo_tags_marked']
		);
	}

	$last = get_option( 'ct_core_last_migration' );
	?>
	<div class="wrap">
		<h1>Creed Times 2.0</h1>
		<?php if ( $notice ) : ?><div class="notice notice-success is-dismissible"><p><?php echo esc_html( $notice ); ?></p></div><?php endif; ?>

		<div style="max-width:900px;background:#fff;border:1px solid #dcdcde;border-radius:10px;padding:24px;margin-top:20px">
			<h2>Editorial migration</h2>
			<p>This migration is intentionally non-destructive. It keeps existing posts, authors, dates, URLs, categories, tags and media. It maps the old Creed Times structure into the new Languages, Formats, Regions and Topics taxonomies; maps “For Subscribers” to Creed Pro; and marks obvious Glossier/demo tags so the new front-end hides them.</p>
			<form method="post">
				<?php wp_nonce_field( 'ct_core_migrate' ); ?>
				<p><button class="button button-primary" name="ct_core_run_migration" value="1">Run Safe Creed Times Migration</button></p>
			</form>
			<?php if ( $last && ! empty( $last['time'] ) ) : ?><p><small>Last run: <?php echo esc_html( wp_date( 'd M Y H:i', $last['time'] ) ); ?></small></p><?php endif; ?>
		</div>

		<div style="max-width:900px;background:#fff;border:1px solid #dcdcde;border-radius:10px;padding:24px;margin-top:20px">
			<h2>Creed Pro</h2>
			<p>If Paid Memberships Pro is active, enter the membership level IDs that should unlock Creed Pro. Separate multiple IDs with commas. Leave blank to treat any active PMPro membership as Pro.</p>
			<form method="post">
				<?php wp_nonce_field( 'ct_core_settings' ); ?>
				<table class="form-table"><tr><th><label for="ct_pro_level_ids">PMPro Level IDs</label></th><td><input id="ct_pro_level_ids" class="regular-text" type="text" name="ct_pro_level_ids" value="<?php echo esc_attr( get_option( 'ct_pro_level_ids', '' ) ); ?>" placeholder="e.g. 2"></td></tr></table>
				<p><button class="button button-primary" name="ct_core_save_settings" value="1">Save Settings</button></p>
			</form>
		</div>

		<div style="max-width:900px;background:#fff;border:1px solid #dcdcde;border-radius:10px;padding:24px;margin-top:20px">
			<h2>Publishing model</h2>
			<p><strong>Content types:</strong> Articles, Videos, Shorts, Podcasts, Visual Stories</p>
			<p><strong>Editorial formats:</strong> News, Analysis, Opinion, Explainer, Interview, Long Read, Documentary</p>
			<p><strong>Languages:</strong> English, Urdu</p>
			<p><strong>Regions:</strong> Pakistan, West Asia, World</p>
			<p><strong>Access:</strong> Free or Creed Pro</p>
		</div>
	</div>
	<?php
}

function ct_core_user_profile_fields( $user ) {
	if ( ! current_user_can( 'edit_user', $user->ID ) ) { return; }
	?>
	<h2>Creed Times Author Profile</h2>
	<table class="form-table">
		<tr><th><label for="ct_designation">Designation</label></th><td><input type="text" class="regular-text" id="ct_designation" name="ct_designation" value="<?php echo esc_attr( get_user_meta( $user->ID, 'ct_designation', true ) ); ?>" placeholder="e.g. Political Commentator"></td></tr>
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
	$columns['ct_access'] = 'Access';
	$columns['ct_format'] = 'Format';
	$columns['ct_language'] = 'Language';
	return $columns;
}
add_filter( 'manage_posts_columns', 'ct_core_admin_columns' );

function ct_core_admin_column_content( $column, $post_id ) {
	if ( 'ct_access' === $column ) {
		echo esc_html( 'pro' === get_post_meta( $post_id, '_ct_access_level', true ) ? 'Creed Pro' : 'Free' );
	}
	if ( 'ct_format' === $column ) {
		$terms = get_the_terms( $post_id, 'ct_format' );
		if ( $terms && ! is_wp_error( $terms ) ) { echo esc_html( implode( ', ', wp_list_pluck( $terms, 'name' ) ) ); }
	}
	if ( 'ct_language' === $column ) {
		$terms = get_the_terms( $post_id, 'ct_language' );
		if ( $terms && ! is_wp_error( $terms ) ) { echo esc_html( implode( ', ', wp_list_pluck( $terms, 'name' ) ) ); }
	}
}
add_action( 'manage_posts_custom_column', 'ct_core_admin_column_content', 10, 2 );
