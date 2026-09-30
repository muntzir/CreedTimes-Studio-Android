<?php
/*
Template Name: Creed Times Contact
*/
get_header();
$ct_social  = ct_get_social_links();
$ct_contact = ct_contact_details();
?>
<main class="ctv-page ctv-contact">
	<section class="ctv-page-hero ctv-page-hero--contact">
		<div class="ct-container ctv-page-hero__inner">
			<span class="ctv-kicker">CONTACT CREED TIMES</span>
			<div class="ctv-contact-logo"><?php echo ct_logo_markup( 'ctv-contact-logo__image' ); ?></div>
			<h1>Talk to the newsroom.</h1>
			<p>For editorial queries, contributions, collaborations, corrections, interviews and general enquiries, use the details below.</p>
		</div>
	</section>

	<section class="ctv-page-section">
		<div class="ct-container ctv-contact-grid">
			<a class="ctv-contact-card" href="mailto:<?php echo esc_attr( $ct_contact['email'] ); ?>">
				<span>EMAIL</span><strong><?php echo esc_html( $ct_contact['email'] ); ?></strong><small>Editorial & general enquiries ↗</small>
			</a>
			<a class="ctv-contact-card" href="https://wa.me/<?php echo esc_attr( preg_replace( '/\D+/', '', $ct_contact['whatsapp'] ) ); ?>" target="_blank" rel="noopener">
				<span>WHATSAPP</span><strong><?php echo esc_html( $ct_contact['phone'] ); ?></strong><small>Direct contact ↗</small>
			</a>
			<a class="ctv-contact-card" href="<?php echo esc_url( $ct_social['whatsapp'] ); ?>" target="_blank" rel="noopener">
				<span>CHANNEL</span><strong>WhatsApp Channel</strong><small>Join for updates ↗</small>
			</a>
			<a class="ctv-contact-card" href="<?php echo esc_url( $ct_social['group'] ); ?>" target="_blank" rel="noopener">
				<span>COMMUNITY</span><strong>WhatsApp Group</strong><small>Join the community ↗</small>
			</a>
		</div>
	</section>

	<section class="ctv-page-section ctv-page-section--soft">
		<div class="ct-container ctv-contact-layout">
			<div>
				<span class="ctv-kicker">SEND A MESSAGE</span>
				<h2>Contact Creed Times directly.</h2>
				<p class="ctv-page-copy">Use this form for general messages. For article pitches or contributor applications, use the Write for Us page.</p>
				<?php if ( shortcode_exists( 'ct_contact_form' ) ) : echo do_shortcode( '[ct_contact_form]' ); endif; ?>
			</div>

			<aside class="ctv-social-panel">
				<span class="ctv-kicker">FOLLOW THE NEWSROOM</span>
				<h3>Creed Times across social media.</h3>
				<?php if ( $ct_social['facebook'] ) : ?><a href="<?php echo esc_url( $ct_social['facebook'] ); ?>" target="_blank" rel="noopener"><?php echo ct_brand_icon( 'facebook' ); ?><span><strong>Facebook</strong><small>facebook.com/creedtimes</small></span><b>↗</b></a><?php endif; ?>
				<?php if ( $ct_social['instagram'] ) : ?><a href="<?php echo esc_url( $ct_social['instagram'] ); ?>" target="_blank" rel="noopener"><?php echo ct_brand_icon( 'instagram' ); ?><span><strong>Instagram</strong><small>@creed.times</small></span><b>↗</b></a><?php endif; ?>
				<?php if ( $ct_social['youtube'] ) : ?><a href="<?php echo esc_url( $ct_social['youtube'] ); ?>" target="_blank" rel="noopener"><?php echo ct_brand_icon( 'youtube' ); ?><span><strong>YouTube</strong><small>@creedtimes</small></span><b>↗</b></a><?php endif; ?>
				<a href="<?php echo esc_url( $ct_social['whatsapp'] ); ?>" target="_blank" rel="noopener"><?php echo ct_brand_icon( 'whatsapp' ); ?><span><strong>WhatsApp Channel</strong><small>Join Channel</small></span><b>↗</b></a>
			</aside>
		</div>
	</section>
</main>
<?php get_footer(); ?>