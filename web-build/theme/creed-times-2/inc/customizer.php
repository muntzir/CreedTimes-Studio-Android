<?php
/**
 * Creed Times theme Customizer.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function ct_customize_register( $wp_customize ) {
	$wp_customize->add_section( 'ct_brand', array(
		'title'    => __( 'Creed Times Brand & Social', 'creed-times' ),
		'priority' => 30,
	) );

	$settings = array(
		'ct_facebook_url' => array( 'Facebook URL', 'https://facebook.com/creedtimes' ),
		'ct_instagram_url' => array( 'Instagram URL', 'https://instagram.com/creed.times' ),
		'ct_youtube_url' => array( 'YouTube URL', 'https://youtube.com/@creedtimes' ),
		'ct_x_url' => array( 'X / Twitter URL', '' ),
		'ct_whatsapp_channel_url' => array( 'WhatsApp Channel URL', 'https://whatsapp.com/channel/0029VbDZdu7AInPo7csvQi0Z' ),
		'ct_whatsapp_group_url' => array( 'WhatsApp Group URL', 'https://chat.whatsapp.com/C2IysQwYjuJ36ImEWG7Md2' ),
		'ct_contribute_url' => array( 'Contribute URL', home_url( '/contribute/' ) ),
	);

	foreach ( $settings as $id => $data ) {
		$wp_customize->add_setting( $id, array(
			'default'           => $data[1],
			'sanitize_callback' => 'esc_url_raw',
		) );
		$wp_customize->add_control( $id, array(
			'label'   => __( $data[0], 'creed-times' ),
			'section' => 'ct_brand',
			'type'    => 'url',
		) );
	}
}
add_action( 'customize_register', 'ct_customize_register' );
