<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

function ct_core_form_notice() {
	if ( empty( $_GET['ct_message'] ) ) { return ''; }
	$status = sanitize_key( wp_unslash( $_GET['ct_message'] ) );
	if ( 'sent' === $status ) {
		return '<div class="ct-form-notice is-success">Thank you. Your message has been sent to Creed Times.</div>';
	}
	if ( 'error' === $status ) {
		return '<div class="ct-form-notice is-error">We could not send the message. Please email info@creedtimes.com directly.</div>';
	}
	return '';
}

function ct_core_contact_form_shortcode() {
	ob_start();
	echo ct_core_form_notice();
	?>
	<form class="ct-public-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
		<input type="hidden" name="action" value="ct_contact_submit">
		<?php wp_nonce_field( 'ct_contact_submit', 'ct_contact_nonce' ); ?>
		<input class="ct-honeypot" type="text" name="website" tabindex="-1" autocomplete="off" aria-hidden="true">
		<div class="ct-public-form__row"><label>Name<input type="text" name="name" required></label><label>Email<input type="email" name="email" required></label></div>
		<label>Subject<input type="text" name="subject" required></label>
		<label>Message<textarea name="message" rows="7" required></textarea></label>
		<button type="submit">Send Message →</button>
	</form>
	<?php
	return ob_get_clean();
}
add_shortcode( 'ct_contact_form', 'ct_core_contact_form_shortcode' );

function ct_core_contribute_form_shortcode() {
	ob_start();
	echo ct_core_form_notice();
	?>
	<form class="ct-public-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
		<input type="hidden" name="action" value="ct_contribute_submit">
		<?php wp_nonce_field( 'ct_contribute_submit', 'ct_contribute_nonce' ); ?>
		<input class="ct-honeypot" type="text" name="website" tabindex="-1" autocomplete="off" aria-hidden="true">
		<div class="ct-public-form__row"><label>Name<input type="text" name="name" required></label><label>Email<input type="email" name="email" required></label></div>
		<div class="ct-public-form__row">
			<label>Proposed format<select name="format"><option>Article</option><option>Analysis</option><option>Opinion</option><option>Explainer</option><option>Interview</option><option>Documentary</option><option>Visual Story</option></select></label>
			<label>Language<select name="language"><option>English</option><option>Urdu</option></select></label>
		</div>
		<label>Working title / topic<input type="text" name="subject" required></label>
		<label>Your pitch<textarea name="message" rows="8" placeholder="What is the story, why does it matter, and what sources or experience will you bring?" required></textarea></label>
		<button type="submit">Submit Pitch →</button>
	</form>
	<?php
	return ob_get_clean();
}
add_shortcode( 'ct_contribute_form', 'ct_core_contribute_form_shortcode' );

function ct_core_handle_public_form( $type ) {
	$nonce_key = 'contact' === $type ? 'ct_contact_nonce' : 'ct_contribute_nonce';
	$nonce_action = 'contact' === $type ? 'ct_contact_submit' : 'ct_contribute_submit';

	if ( empty( $_POST[ $nonce_key ] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST[ $nonce_key ] ) ), $nonce_action ) ) {
		wp_die( 'Invalid request.' );
	}
	if ( ! empty( $_POST['website'] ) ) {
		wp_safe_redirect( home_url( '/' ) );
		exit;
	}

	$name = sanitize_text_field( wp_unslash( $_POST['name'] ?? '' ) );
	$email = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );
	$subject = sanitize_text_field( wp_unslash( $_POST['subject'] ?? '' ) );
	$message = sanitize_textarea_field( wp_unslash( $_POST['message'] ?? '' ) );
	$format = sanitize_text_field( wp_unslash( $_POST['format'] ?? '' ) );
	$language = sanitize_text_field( wp_unslash( $_POST['language'] ?? '' ) );

	$to = sanitize_email( get_option( 'ct_contact_email', 'info@creedtimes.com' ) );
	$mail_subject = ( 'contribute' === $type ? '[Creed Times Pitch] ' : '[Creed Times Contact] ' ) . $subject;
	$body = "Name: {$name}\nEmail: {$email}\n";
	if ( 'contribute' === $type ) {
		$body .= "Format: {$format}\nLanguage: {$language}\n";
	}
	$body .= "\n{$message}";
	$headers = $email ? array( 'Reply-To: ' . $name . ' <' . $email . '>' ) : array();

	$sent = $name && $email && $subject && $message && wp_mail( $to, $mail_subject, $body, $headers );
	$fallback = 'contribute' === $type ? home_url( '/contribute/' ) : home_url( '/contact/' );
	$redirect = wp_get_referer() ?: $fallback;
	$redirect = add_query_arg( 'ct_message', $sent ? 'sent' : 'error', $redirect );
	wp_safe_redirect( $redirect );
	exit;
}

function ct_core_contact_submit() { ct_core_handle_public_form( 'contact' ); }
function ct_core_contribute_submit() { ct_core_handle_public_form( 'contribute' ); }
add_action( 'admin_post_ct_contact_submit', 'ct_core_contact_submit' );
add_action( 'admin_post_nopriv_ct_contact_submit', 'ct_core_contact_submit' );
add_action( 'admin_post_ct_contribute_submit', 'ct_core_contribute_submit' );
add_action( 'admin_post_nopriv_ct_contribute_submit', 'ct_core_contribute_submit' );
