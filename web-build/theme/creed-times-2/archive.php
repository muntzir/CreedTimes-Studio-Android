<?php
get_header();

$queried = get_queried_object();
$title = '';
$description = '';
$kicker = 'SECTION';

if ( is_category() || is_tag() || is_tax() ) {
	$title = single_term_title( '', false );
	$description = term_description();
	if ( is_tag() ) {
		$kicker = 'TOPIC';
	} elseif ( is_tax( 'ct_region' ) ) {
		$kicker = 'REGION';
	} elseif ( is_tax( 'ct_format' ) ) {
		$kicker = 'FORMAT';
	} elseif ( is_tax( 'ct_topic' ) ) {
		$kicker = 'TOPIC';
	}
} elseif ( is_post_type_archive() ) {
	$title = post_type_archive_title( '', false );
	$kicker = 'MEDIA';
} elseif ( is_author() ) {
	$title = get_the_author();
	$kicker = 'AUTHOR';
} else {
	$title = wp_strip_all_tags( get_the_archive_title() );
	$description = get_the_archive_description();
}

$title = $title ?: 'Stories';
$archive_posts = $GLOBALS['wp_query']->posts ?? array();
$lead = $archive_posts[0] ?? null;
$remaining = array_slice( $archive_posts, 1 );
?>
<main class="ctv-archive">
	<section class="ctv-archive-hero">
		<div class="ct-container">
			<div class="ctv-archive-hero__top">
				<div>
					<span class="ctv-kicker"><?php echo esc_html( $kicker ); ?></span>
					<h1><?php echo esc_html( $title ); ?></h1>
					<?php if ( $description ) : ?><div class="ctv-archive-description"><?php echo wp_kses_post( $description ); ?></div><?php endif; ?>
				</div>
				<div class="ctv-archive-hero__meta">
					<span><?php echo esc_html( (int) $GLOBALS['wp_query']->found_posts ); ?></span>
					<small><?php esc_html_e( 'published stories', 'creed-times' ); ?></small>
				</div>
			</div>

			<div class="ctv-topic-nav">
				<a href="<?php echo esc_url( ct_section_url( 'latest' ) ); ?>">Latest</a>
				<a href="<?php echo esc_url( ct_section_url( 'west-asia' ) ); ?>">West Asia</a>
				<a href="<?php echo esc_url( ct_section_url( 'pakistan' ) ); ?>">Pakistan</a>
				<a href="<?php echo esc_url( ct_section_url( 'world' ) ); ?>">World</a>
				<a href="<?php echo esc_url( ct_section_url( 'analysis' ) ); ?>">Analysis</a>
				<a href="<?php echo esc_url( ct_section_url( 'urdu' ) ); ?>" lang="ur">اردو</a>
			</div>
		</div>
	</section>

	<?php if ( $lead ) : setup_postdata( $lead ); ?>
	<section class="ctv-archive-lead">
		<div class="ct-container">
			<article class="ctv-lead-story">
				<a class="ctv-lead-story__media" href="<?php the_permalink(); ?>">
					<?php if ( has_post_thumbnail() ) : ?>
						<?php the_post_thumbnail( 'full', array( 'fetchpriority' => 'high', 'decoding' => 'async', 'sizes' => '(max-width: 900px) 100vw, 62vw' ) ); ?>
					<?php else : ?>
						<div class="ctn-fallback"><span>CT</span></div>
					<?php endif; ?>
					<span class="ctv-image-label"><?php echo esc_html( ct_primary_category() ? ct_primary_category()->name : $title ); ?></span>
				</a>
				<div class="ctv-lead-story__copy">
					<span class="ctv-kicker">FEATURED IN <?php echo esc_html( strtoupper( $title ) ); ?></span>
					<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
					<p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 28 ) ); ?></p>
					<div class="ctv-lead-story__footer">
						<div><?php echo get_avatar( get_the_author_meta( 'ID' ), 40, '', esc_attr( get_the_author() ) ); ?><span><strong><?php the_author(); ?></strong><small><?php echo esc_html( get_the_date( 'd M Y' ) ); ?> · <?php echo esc_html( ct_reading_time() ); ?> min</small></span></div>
						<a href="<?php the_permalink(); ?>">Read story ↗</a>
					</div>
				</div>
			</article>
		</div>
	</section>
	<?php wp_reset_postdata(); endif; ?>

	<section class="ctv-archive-feed">
		<div class="ct-container">
			<div class="ctv-feed-head">
				<div><span class="ctv-kicker">MORE FROM <?php echo esc_html( strtoupper( $title ) ); ?></span><h2>Latest stories</h2></div>
			</div>

			<?php if ( $remaining ) : ?>
				<div class="ctv-story-grid">
					<?php foreach ( $remaining as $post ) : setup_postdata( $post ); ?>
						<?php get_template_part( 'template-parts/card', 'story' ); ?>
					<?php endforeach; wp_reset_postdata(); ?>
				</div>
			<?php else : ?>
				<div class="ct-empty-state"><h2>No more stories yet.</h2><p>Explore another Creed Times section from the navigation above.</p></div>
			<?php endif; ?>

			<div class="ct-pagination"><?php the_posts_pagination( array( 'mid_size' => 2, 'prev_text' => '←', 'next_text' => '→' ) ); ?></div>
		</div>
	</section>
</main>
<?php get_footer(); ?>
