<?php
/*
Template Name: Creed Times Contribute
*/
get_header();
$ct_contact = ct_contact_details();
?>
<main class="ctv-page ctv-contribute">
	<section class="ctv-page-hero ctv-page-hero--compact">
		<div class="ct-container ctv-page-hero__inner">
			<span class="ctv-kicker">WRITE FOR CREED TIMES</span>
			<h1>Have a perspective?<br><em>Bring the story with it.</em></h1>
			<p>Creed Times welcomes thoughtful pitches, reported pieces, explainers, analysis, interviews and long-form ideas from writers and contributors.</p>
		</div>
	</section>

	<section class="ctv-page-section">
		<div class="ct-container ctv-contribute-grid">
			<article><span>01</span><h3>Pitch clearly</h3><p>Tell us what the story is, why it matters and what makes your angle useful to readers.</p></article>
			<article><span>02</span><h3>Show your sources</h3><p>For reported or analytical work, explain the evidence, documents, interviews or references you plan to use.</p></article>
			<article><span>03</span><h3>Choose the format</h3><p>News, analysis, opinion, explainer, interview, long read, documentary or visual story.</p></article>
			<article><span>04</span><h3>English or Urdu</h3><p>Submissions can be proposed in English or Urdu. Final editorial decisions remain with the Creed Times team.</p></article>
		</div>
	</section>

	<section class="ctv-page-section ctv-page-section--soft">
		<div class="ct-container ctv-contribute-form-layout">
			<div>
				<span class="ctv-kicker">SUBMIT A PITCH</span>
				<h2>Tell us what you want to write.</h2>
				<p class="ctv-page-copy">You can also email your pitch directly to <a href="mailto:<?php echo esc_attr( $ct_contact['email'] ); ?>"><?php echo esc_html( $ct_contact['email'] ); ?></a>.</p>
			</div>
			<div>
				<?php if ( shortcode_exists( 'ct_contribute_form' ) ) : echo do_shortcode( '[ct_contribute_form]' ); endif; ?>
			</div>
		</div>
	</section>
</main>
<?php get_footer(); ?>