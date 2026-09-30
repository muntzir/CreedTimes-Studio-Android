<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

function ct_core_login_branding() {
	?>
	<style>
	body.login{background:#f5f7f8;font-family:Inter,system-ui,-apple-system,sans-serif}
	body.login:before{content:"CREED TIMES";display:block;text-align:center;margin:54px 0 -22px;color:#0b2a4a;font-size:30px;font-weight:900;letter-spacing:-1.5px}
	body.login:after{content:"NEWS · ANALYSIS · PERSPECTIVE";display:block;position:absolute;top:92px;left:0;right:0;text-align:center;color:#ff6b3d;font-size:9px;font-weight:800;letter-spacing:2.5px}
	.login h1{display:none}
	.login form{border:1px solid #e1e7eb;border-radius:22px;box-shadow:0 18px 55px rgba(11,42,74,.08);padding:26px;background:#fff}
	.login label{color:#0b1e30;font-weight:700}
	.login input[type=text],.login input[type=password],.login input[type=email]{border:1px solid #dfe5e9;border-radius:12px;min-height:46px;box-shadow:none}
	.wp-core-ui .button-primary{background:#ff6b3d;border-color:#ff6b3d;border-radius:999px;min-height:42px;padding:0 18px;font-weight:800}
	.login #nav,.login #backtoblog{text-align:center}
	.login #nav a,.login #backtoblog a{color:#0b2a4a}
	</style>
	<?php
}
add_action( 'login_enqueue_scripts', 'ct_core_login_branding' );

function ct_core_login_logo_url() {
	return home_url( '/' );
}
add_filter( 'login_headerurl', 'ct_core_login_logo_url' );

function ct_core_login_logo_title() {
	return 'Creed Times';
}
add_filter( 'login_headertext', 'ct_core_login_logo_title' );
