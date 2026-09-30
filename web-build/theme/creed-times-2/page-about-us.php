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
<main class="ctv-about">
	<section class="ctv-about-hero">
		<div class="ct-container ctv-about-hero__inner">
			<div class="ctv-about-hero__copy">
				<span class="ctv-kicker">ABOUT CREED TIMES</span>
				<h1>Context before noise.<br><em>Stories with perspective.</em></h1>
				<p>Creed Times is a digital news and analysis platform covering current affairs, Pakistan, West Asia and global developments through articles, interviews, documentaries, video and visual storytelling.</p>
				<div class="ctv-about-hero__actions">
					<a href="<?php echo esc_url( ct_section_url( 'latest' ) ); ?>">Explore latest stories ↗</a>
					<a class="is-secondary" href="<?php echo esc_url( home_url( '/authors/' ) ); ?>">Meet the authors</a>
				</div>
			</div>
			<div class="ctv-about-hero__brand">
				<img src="<?php echo esc_url( ct_brand_mark_url() ); ?>" alt="" aria-hidden="true">
				<span>NEWS · ANALYSIS · PERSPECTIVE</span>
			</div>
		</div>
	</section>

	<section class="ctv-about-stats">
		<div class="ct-container ctv-about-stats__grid">
			<article><span>01</span><strong>Newsroom</strong><p>Fast, structured coverage for important developments.</p></article>
			<article><span>02</span><strong>Analysis</strong><p>Longer reads designed to add context beyond the headline.</p></article>
			<article><span>03</span><strong>Visual Media</strong><p>Video, shorts, podcasts, explainers and documentary work.</p></article>
			<article><span>04</span><strong>English + Urdu</strong><p>A bilingual editorial experience built for readable publishing.</p></article>
		</div>
	</section>

	<section class="ctv-about-section">
		<div class="ct-container ctv-about-split">
			<div>
				<span class="ctv-kicker">WHAT WE DO</span>
				<h2>A modern digital newsroom, built around clarity.</h2>
			</div>
			<div class="ctv-about-copy">
				<p>Creed Times brings reporting, commentary and visual storytelling into one publishing system so readers can move from a quick update to a deeper explanation without losing context.</p>
				<p>The platform is designed around clear authorship, readable article pages, topic-based discovery, transparent source sections and a consistent experience across desktop and mobile.</p>
			</div>
		</div>
	</section>

	<section class="ctv-about-section ctv-about-section--soft">
		<div class="ct-container">
			<div class="ctv-about-head">
				<div><span class="ctv-kicker">EDITORIAL FORMATS</span><h2>One platform. Different ways to understand a story.</h2></div>
			</div>
			<div class="ctv-format-grid">
				<article><b>News</b><p>Concise coverage of developing and current stories.</p></article>
				<article><b>Analysis</b><p>Deeper context, background and interpretation.</p></article>
				<article><b>Opinion</b><p>Clearly identified perspectives from contributors.</p></article>
				<article><b>Explainers</b><p>Complex developments broken into accessible parts.</p></article>
				<article><b>Interviews</b><p>Direct conversations with guests and specialists.</p></article>
				<article><b>Documentaries</b><p>Longer-form visual storytelling and research.</p></article>
			</div>
		</div>
	</section>

	<section class="ctv-about-section">
		<div class="ct-container">
			<div class="ctv-about-head">
				<div><span class="ctv-kicker">PEOPLE</span><h2>Authors & contributors</h2></div>
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

	<section class="ctv-about-connect">
		<div class="ct-container ctv-about-connect__card">
			<div><span class="ctv-kicker">STAY CONNECTED</span><h2>Follow the newsroom directly.</h2><p>Get Creed Times updates through the channels you already use.</p></div>
			<div>
				<a href="<?php echo esc_url( $ct_social['whatsapp'] ); ?>" target="_blank" rel="noopener">WhatsApp Channel <span>↗</span></a>
				<a href="<?php echo esc_url( $ct_social['group'] ); ?>" target="_blank" rel="noopener">WhatsApp Group <span>↗</span></a>
				<a href="<?php echo esc_url( $ct_social['instagram'] ); ?>" target="_blank" rel="noopener">Instagram <span>↗</span></a>
				<a href="<?php echo esc_url( $ct_social['youtube'] ); ?>" target="_blank" rel="noopener">YouTube <span>↗</span></a>
			</div>
		</div>
	</section>
</main>
<?php get_footer(); ?>
