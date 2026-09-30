<?php
/*
Template Name: Creed Times About
*/
get_header();
$authors = get_users( array(
	'orderby' => 'post_count',
	'order' => 'DESC',
	'number' => 6,
	'has_published_posts' => true,
) );
$ct_social = ct_get_social_links();
?>
<main class="ctv-page ctv-about">
	<section class="ctv-page-hero ctv-page-hero--about">
		<div class="ct-container ctv-about-hero-grid">
			<div>
				<span class="ctv-kicker">ABOUT CREED TIMES</span>
				<h1>Meaningful writing.<br><em>Thoughtful ideas.</em></h1>
				<p>Creed Times is a digital platform focused on meaningful writing, thoughtful ideas and impactful content across social, political and intellectual topics.</p>
				<div class="ctv-about-actions">
					<a class="ctv-primary-action" href="<?php echo esc_url( ct_section_url( 'latest' ) ); ?>">Explore Latest Stories ↗</a>
					<a class="ctv-secondary-action" href="<?php echo esc_url( home_url( '/authors/' ) ); ?>">Meet the Authors</a>
				</div>
			</div>
			<div class="ctv-about-brand-card">
				<div class="ctv-about-brand-card__logo"><?php echo ct_logo_markup( 'ctv-about-logo' ); ?></div>
				<p>News · Analysis · Perspective</p>
			</div>
		</div>
	</section>

	<section class="ctv-page-section">
		<div class="ct-container ctv-about-story">
			<div>
				<span class="ctv-kicker">WHY WE EXIST</span>
				<h2>A space for work that goes beyond the surface.</h2>
			</div>
			<div>
				<p>Creed Times is built by a small, dedicated team of independent journalism learners and writers. The platform creates space for deep thinkers, analysts and writers to publish comprehensive work that goes beyond the surface of mainstream media.</p>
				<p>Our aim is simple: make serious ideas easier to discover, read and understand — through articles, analysis, interviews, documentaries, podcasts, video and visual storytelling.</p>
			</div>
		</div>
	</section>

	<section class="ctv-page-section ctv-page-section--soft">
		<div class="ct-container">
			<div class="ctv-page-head">
				<div><span class="ctv-kicker">EDITORIAL SYSTEM</span><h2>Different formats. One clear newsroom.</h2></div>
			</div>
			<div class="ctv-format-grid">
				<article><span>01</span><b>News</b><p>Clear, concise reporting around current developments.</p></article>
				<article><span>02</span><b>Analysis</b><p>Context, background and deeper interpretation.</p></article>
				<article><span>03</span><b>Opinion</b><p>Clearly identified perspectives from named authors.</p></article>
				<article><span>04</span><b>Explainers</b><p>Complex developments broken into understandable parts.</p></article>
				<article><span>05</span><b>Interviews</b><p>Direct conversations with guests and specialists.</p></article>
				<article><span>06</span><b>Documentaries</b><p>Longer-form research and visual storytelling.</p></article>
			</div>
		</div>
	</section>

	<section class="ctv-page-section">
		<div class="ct-container">
			<div class="ctv-page-head">
				<div><span class="ctv-kicker">OUR AUTHORS</span><h2>Writers behind the stories.</h2></div>
				<a href="<?php echo esc_url( home_url( '/authors/' ) ); ?>">View all authors →</a>
			</div>
			<div class="ctv-about-authors">
				<?php foreach ( $authors as $author ) : ?>
					<article>
						<a href="<?php echo esc_url( get_author_posts_url( $author->ID ) ); ?>"><?php echo get_avatar( $author->ID, 120, '', esc_attr( $author->display_name ) ); ?></a>
						<h3><a href="<?php echo esc_url( get_author_posts_url( $author->ID ) ); ?>"><?php echo esc_html( $author->display_name ); ?></a></h3>
						<p><?php echo esc_html( ct_author_designation( $author->ID ) ); ?></p>
					</article>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<section class="ctv-about-manifesto">
		<div class="ct-container ctv-about-manifesto__inner">
			<span class="ctv-kicker">OUR APPROACH</span>
			<blockquote>Journalism that serves the people, not the powerful.</blockquote>
			<p>Creed Times is designed around clear authorship, readable pages, transparent sources and consistent editorial presentation across desktop and mobile.</p>
		</div>
	</section>

	<section class="ctv-page-section">
		<div class="ct-container ctv-about-connect">
			<div><span class="ctv-kicker">STAY CONNECTED</span><h2>Follow the newsroom directly.</h2></div>
			<div>
				<a href="<?php echo esc_url( $ct_social['whatsapp'] ); ?>" target="_blank" rel="noopener"><?php echo ct_brand_icon( 'whatsapp' ); ?> Join Channel <span>↗</span></a>
				<a href="<?php echo esc_url( $ct_social['instagram'] ); ?>" target="_blank" rel="noopener"><?php echo ct_brand_icon( 'instagram' ); ?> Instagram <span>↗</span></a>
				<a href="<?php echo esc_url( $ct_social['youtube'] ); ?>" target="_blank" rel="noopener"><?php echo ct_brand_icon( 'youtube' ); ?> YouTube <span>↗</span></a>
				<a href="<?php echo esc_url( $ct_social['facebook'] ); ?>" target="_blank" rel="noopener"><?php echo ct_brand_icon( 'facebook' ); ?> Facebook <span>↗</span></a>
			</div>
		</div>
	</section>
</main>
<?php get_footer(); ?>