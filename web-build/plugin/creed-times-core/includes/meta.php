<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

function ct_core_meta_fields() {
	return array(
		'_ct_access_level' => array( 'label' => 'Access', 'type' => 'select', 'options' => array( 'free' => 'Free', 'pro' => 'Creed Pro' ), 'default' => 'free' ),
		'_ct_editor_pick'   => array( 'label' => 'Editor’s Pick', 'type' => 'checkbox' ),
		'_ct_trending'      => array( 'label' => 'Trending / The Spike', 'type' => 'checkbox' ),
		'_ct_developing'    => array( 'label' => 'Developing Story', 'type' => 'checkbox' ),
		'_ct_audio_url'     => array( 'label' => 'Audio Article URL', 'type' => 'url' ),
		'_ct_show_name'     => array( 'label' => 'Show / Program Name', 'type' => 'text' ),
		'_ct_duration'      => array( 'label' => 'Duration', 'type' => 'text' ),
		'_ct_video_url'     => array( 'label' => 'Video URL', 'type' => 'url' ),
		'_ct_key_points'    => array( 'label' => 'Key Points', 'type' => 'textarea' ),
		'_ct_sources'       => array( 'label' => 'Sources & References', 'type' => 'textarea' ),
	);
}

function ct_core_add_meta_boxes() {
	foreach ( array( 'post', 'ct_video', 'ct_short', 'ct_podcast', 'ct_artwork' ) as $type ) {
		add_meta_box( 'ct-editorial-meta', 'Creed Times Editorial', 'ct_core_meta_box', $type, 'normal', 'high' );
	}
}
add_action( 'add_meta_boxes', 'ct_core_add_meta_boxes' );

function ct_core_meta_box( $post ) {
	wp_nonce_field( 'ct_core_save_meta', 'ct_core_meta_nonce' );
	$fields = ct_core_meta_fields();
	echo '<div class="ct-admin-fields">';
	foreach ( $fields as $key => $field ) {
		$value = get_post_meta( $post->ID, $key, true );
		if ( '' === $value && isset( $field['default'] ) ) { $value = $field['default']; }
		echo '<p><label for="' . esc_attr( $key ) . '"><strong>' . esc_html( $field['label'] ) . '</strong></label><br>';
		if ( 'select' === $field['type'] ) {
			echo '<select id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '">';
			foreach ( $field['options'] as $option_value => $option_label ) {
				echo '<option value="' . esc_attr( $option_value ) . '" ' . selected( $value, $option_value, false ) . '>' . esc_html( $option_label ) . '</option>';
			}
			echo '</select>';
		} elseif ( 'checkbox' === $field['type'] ) {
			echo '<label><input id="' . esc_attr( $key ) . '" type="checkbox" name="' . esc_attr( $key ) . '" value="1" ' . checked( $value, '1', false ) . '> Enabled</label>';
		} elseif ( 'textarea' === $field['type'] ) {
			echo '<textarea id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" rows="5" style="width:100%">' . esc_textarea( $value ) . '</textarea>';
		} else {
			echo '<input id="' . esc_attr( $key ) . '" type="' . esc_attr( $field['type'] ) . '" name="' . esc_attr( $key ) . '" value="' . esc_attr( $value ) . '" style="width:100%">';
		}
		echo '</p>';
	}
	echo '</div>';
}

function ct_core_save_meta( $post_id ) {
	if ( ! isset( $_POST['ct_core_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ct_core_meta_nonce'] ) ), 'ct_core_save_meta' ) ) { return; }
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) { return; }
	if ( ! current_user_can( 'edit_post', $post_id ) ) { return; }

	foreach ( ct_core_meta_fields() as $key => $field ) {
		if ( 'checkbox' === $field['type'] ) {
			update_post_meta( $post_id, $key, isset( $_POST[ $key ] ) ? '1' : '0' );
			continue;
		}
		if ( ! isset( $_POST[ $key ] ) ) { continue; }
		$raw = wp_unslash( $_POST[ $key ] );
		$value = 'textarea' === $field['type'] ? wp_kses_post( $raw ) : ( 'url' === $field['type'] ? esc_url_raw( $raw ) : sanitize_text_field( $raw ) );
		update_post_meta( $post_id, $key, $value );
	}
}
add_action( 'save_post', 'ct_core_save_meta' );
