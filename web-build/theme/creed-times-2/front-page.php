<?php
get_header();

$all_q = new WP_Query( array(
	'post_type'           => 'post',
	'post_status'         => 'publish',
	'posts_per_page'      => 28,
	'ignore_sticky_posts' => false,
) );
$ct_posts = $all_q->posts;

$lead = $ct_posts[0] ?? null;
$pulse = array_slice( $ct_posts, 1, 4 );
$spike = array_slice( $ct_posts, 5, 5 );
$latest = array_slice( $ct_posts, 10, 6 );
$popular = array_slice( $ct_posts, 16, 5 );

$analysis_q = new WP_Query( array(
	'post_type' => 'post',
	'post_status' => 'publish',
	'posts_per_page' => 3,
	'category_name' => 'analysis',
	'ignore_sticky_posts' => true,
) );

$urdu_q = new WP_Query( array(
	'post_type' => 'post',
	'post_status' => 'publish',
	'posts_per_page' => 4,
	'category_name' => 'urdu',
	'ignore_sticky_posts' => true,
) );

function ctn_home_image( $post_id, $size = 'ct-card', $class = '' ) {
	if ( has_post_thumbnail( $post_id ) ) {
		echo get_the_post_thumbnail( $post_id, 'full', array( 'class' => $class, 'loading' => 'lazy', 'decoding' => 'async', 'sizes' => '(max-width: 700px) 100vw, (max-width: 1100px) 50vw, 760px' ) );
	} else {
		echo '<div class="ctn-fallback ' . esc_attr( $class ) . '"><span>CT</span></div>';
	}
}
function ctn_home_category( $post_id ) {
	$cat = ct_primary_category( $post_id );
	return $cat ? $cat->name : 'Latest';
}
function ctn_home_read( $post_id ) {
	return max( 1, (int) ceil( str_word_count( wp_strip_all_tags( get_post_field( 'post_content', $post_id ) ) ) / 220 ) );
}
?>

<main class="ctn-home">
	<section class="ctn-briefbar">
		<div class="ct-container ctn-briefbar__inner">
			<div class="ctn-briefbar__label"><i></i><strong>NEWSROOM</strong></div>
			<div class="ctn-briefbar__stories">
				<?php foreach ( array_slice( $ct_posts, 0, 3 ) as $brief ) : ?>
					<a href="<?php echo esc_url( get_permalink( $brief ) ); ?>"><span><?php echo esc_html( ctn_home_category( $brief->ID ) ); ?></span><?php echo esc_html( get_the_title( $brief ) ); ?></a>
				<?php endforeach; ?>
			</div>
			<a class="ctn-briefbar__all" href="<?php echo esc_url( home_url( '/category/news/' ) ); ?>">All stories →</a>
		</div>
	</section>

	<section class="ctn-hero-section">
		<div class="ct-container">
			<div class="ctn-hero-heading">
				<div><span class="ctn-eyebrow">TODAY ON CREED TIMES</span><h1>Read the story.<br><em>Understand the context.</em></h1></div>
				<p>Independent reporting, analysis and visual storytelling from Pakistan, West Asia and the wider world.</p>
			</div>

			<div class="ctn-lead-grid">
				<?php if ( $lead ) : ?>
					<article class="ctn-lead-card">
						<a class="ctn-lead-card__media" href="<?php echo esc_url( get_permalink( $lead ) ); ?>">
							<?php ctn_home_image( $lead->ID, 'ct-hero', 'ctn-cover' ); ?>
							<div class="ctn-lead-card__badge"><?php echo esc_html( ctn_home_category( $lead->ID ) ); ?></div>
						</a>
						<div class="ctn-lead-card__copy">
							<div class="ctn-story-topline"><span>FEATURED STORY</span><small><?php echo esc_html( get_the_date( 'd M Y', $lead ) ); ?></small></div>
							<h2><a href="<?php echo esc_url( get_permalink( $lead ) ); ?>"><?php echo esc_html( get_the_title( $lead ) ); ?></a></h2>
							<p><?php echo esc_html( wp_trim_words( get_the_excerpt( $lead ), 28 ) ); ?></p>
							<div class="ctn-author-line">
								<?php echo get_avatar( $lead->post_author, 40, '', esc_attr( get_the_author_meta( 'display_name', $lead->post_author ) ) ); ?>
								<div><strong><?php echo esc_html( get_the_author_meta( 'display_name', $lead->post_author ) ); ?></strong><span><?php echo esc_html( ctn_home_read( $lead->ID ) ); ?> min read</span></div>
								<a href="<?php echo esc_url( get_permalink( $lead ) ); ?>">Read story ↗</a>
							</div>
						</div>
					</article>
				<?php endif; ?>

				<aside class="ctn-pulse">
					<div class="ctn-pulse__head"><div><i></i><span>Latest Pulse</span></div><a href="<?php echo esc_url( home_url( '/category/news/' ) ); ?>">View all</a></div>
					<div class="ctn-pulse__list">
						<?php foreach ( $pulse as $i => $p ) : ?>
							<a class="ctn-pulse-item" href="<?php echo esc_url( get_permalink( $p ) ); ?>">
								<div class="ctn-pulse-item__img"><?php ctn_home_image( $p->ID, 'thumbnail' ); ?></div>
								<div class="ctn-pulse-item__copy"><span><?php echo esc_html( ctn_home_category( $p->ID ) ); ?></span><strong><?php echo esc_html( get_the_title( $p ) ); ?></strong><small><?php echo esc_html( human_time_diff( get_the_time( 'U', $p ), current_time( 'timestamp' ) ) ); ?> ago</small></div>
								<b><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></b>
							</a>
						<?php endforeach; ?>
					</div>
				</aside>
			</div>
		</div>
	</section>

	<section class="ctn-section ctn-spike">
		<div class="ct-container">
			<div class="ctn-section-head">
				<div><span class="ctn-kicker">THE SPIKE</span><h2>Key stories to catch up on.</h2></div>
				<div class="ctn-section-actions"><button type="button" data-scroll-prev>←</button><button type="button" data-scroll-next>→</button></div>
			</div>
			<div class="ctn-spike-row" data-horizontal-scroll>
				<?php foreach ( $spike as $i => $p ) : ?>
					<article class="ctn-spike-card">
						<a class="ctn-spike-card__media" href="<?php echo esc_url( get_permalink( $p ) ); ?>"><?php ctn_home_image( $p->ID, 'ct-card' ); ?><span><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span></a>
						<div class="ctn-spike-card__copy"><small><?php echo esc_html( ctn_home_category( $p->ID ) ); ?></small><h3><a href="<?php echo esc_url( get_permalink( $p ) ); ?>"><?php echo esc_html( get_the_title( $p ) ); ?></a></h3><div><span><?php echo esc_html( get_the_date( 'd M', $p ) ); ?></span><span><?php echo esc_html( ctn_home_read( $p->ID ) ); ?> min</span></div></div>
					</article>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<section class="ctn-section ctn-latest">
		<div class="ct-container">
			<div class="ctn-section-head">
				<div><span class="ctn-kicker">LATEST</span><h2>Fresh from the newsroom.</h2></div>
				<a class="ctn-text-link" href="<?php echo esc_url( home_url( '/category/news/' ) ); ?>">Browse all stories →</a>
			</div>
			<div class="ctn-latest-layout">
				<div class="ctn-story-grid">
					<?php foreach ( $latest as $p ) : ?>
						<article class="ctn-story-card">
							<a class="ctn-story-card__media" href="<?php echo esc_url( get_permalink( $p ) ); ?>"><?php ctn_home_image( $p->ID, 'ct-card' ); ?><span><?php echo esc_html( ctn_home_category( $p->ID ) ); ?></span></a>
							<div class="ctn-story-card__copy"><h3><a href="<?php echo esc_url( get_permalink( $p ) ); ?>"><?php echo esc_html( get_the_title( $p ) ); ?></a></h3><p><?php echo esc_html( wp_trim_words( get_the_excerpt( $p ), 15 ) ); ?></p><div><span><?php echo esc_html( get_the_author_meta( 'display_name', $p->post_author ) ); ?></span><span><?php echo esc_html( ctn_home_read( $p->ID ) ); ?> min</span></div></div>
						</article>
					<?php endforeach; ?>
				</div>

				<aside class="ctn-popular">
					<div class="ctn-popular__head"><span>POPULAR NOW</span><i></i></div>
					<?php foreach ( $popular as $i => $p ) : ?>
						<a href="<?php echo esc_url( get_permalink( $p ) ); ?>"><b><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></b><div><span><?php echo esc_html( ctn_home_category( $p->ID ) ); ?></span><strong><?php echo esc_html( get_the_title( $p ) ); ?></strong><small><?php echo esc_html( get_the_date( 'd M', $p ) ); ?></small></div></a>
					<?php endforeach; ?>
				</aside>
			</div>
		</div>
	</section>

	<section class="ctn-section ctn-authors">
		<div class="ct-container">
			<div class="ctn-section-head">
				<div><span class="ctn-kicker">VOICES</span><h2>Authors & contributors.</h2></div>
				<a class="ctn-text-link" href="<?php echo esc_url( home_url( '/authors/' ) ); ?>">Meet the team →</a>
			</div>
			<div class="ctn-author-layout">
				<div class="ctn-author-row">
					<?php $authors = get_users( array( 'orderby' => 'post_count', 'order' => 'DESC', 'number' => 6, 'has_published_posts' => true ) ); foreach ( $authors as $author ) : ?>
						<article class="ctn-author-card">
							<a class="ctn-author-card__avatar" href="<?php echo esc_url( get_author_posts_url( $author->ID ) ); ?>"><?php echo get_avatar( $author->ID, 110, '', esc_attr( $author->display_name ) ); ?><span>↗</span></a>
							<h3><a href="<?php echo esc_url( get_author_posts_url( $author->ID ) ); ?>"><?php echo esc_html( $author->display_name ); ?></a></h3>
							<p><?php echo esc_html( ct_author_designation( $author->ID ) ); ?></p>
							<small><?php echo esc_html( count_user_posts( $author->ID, 'post', true ) ); ?> stories</small>
						</article>
					<?php endforeach; ?>
				</div>
				<aside class="ctn-contribute">
					<span class="ctn-contribute__mark">CT</span><div><small>CONTRIBUTE</small><h3>Have a story or perspective?</h3><p>Join the Creed Times contributor network.</p></div><a href="<?php echo esc_url( home_url( '/contribute/' ) ); ?>">Write for us ↗</a>
				</aside>
			</div>
		</div>
	</section>

	<section class="ctn-section ctn-analysis">
		<div class="ct-container">
			<div class="ctn-section-head">
				<div><span class="ctn-kicker">DEEPER READS</span><h2>Analysis & perspective.</h2></div>
				<a class="ctn-text-link" href="<?php echo esc_url( home_url( '/category/analysis/' ) ); ?>">All analysis →</a>
			</div>
			<div class="ctn-analysis-grid">
				<?php $i = 0; while ( $analysis_q->have_posts() ) : $analysis_q->the_post(); ?>
					<article class="ctn-analysis-card <?php echo 0 === $i ? 'ctn-analysis-card--lead' : ''; ?>">
						<a class="ctn-analysis-card__media" href="<?php the_permalink(); ?>"><?php if ( has_post_thumbnail() ) : the_post_thumbnail( 0 === $i ? 'ct-hero' : 'ct-card', array( 'loading' => 'lazy' ) ); else : ?><div class="ctn-fallback"><span>CT</span></div><?php endif; ?></a>
						<div class="ctn-analysis-card__copy"><span>ANALYSIS</span><h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3><p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 0 === $i ? 24 : 15 ) ); ?></p><small><?php the_author(); ?> · <?php echo esc_html( ct_reading_time() ); ?> min read</small></div>
					</article>
				<?php $i++; endwhile; wp_reset_postdata(); ?>
			</div>
		</div>
	</section>

	<section class="ctn-section ctn-media">
		<div class="ct-container">
			<div class="ctn-section-head">
				<div><span class="ctn-kicker">WATCH · LISTEN</span><h2>Creed Times media.</h2></div>
			</div>
			<div class="ctn-media-grid">
				<?php
				$video_q = post_type_exists( 'ct_video' ) ? new WP_Query( array( 'post_type' => 'ct_video', 'post_status' => 'publish', 'posts_per_page' => 1 ) ) : null;
				if ( $video_q && $video_q->have_posts() ) : $video_q->the_post();
				?>
					<article class="ctn-media-feature">
						<a href="<?php the_permalink(); ?>" class="ctn-media-feature__media"><?php if ( has_post_thumbnail() ) : the_post_thumbnail( 'ct-hero', array( 'loading' => 'lazy' ) ); else : ?><div class="ctn-fallback"><span>CT</span></div><?php endif; ?><span class="ctn-play"><?php echo ct_icon( 'play' ); ?></span></a>
						<div><span>FEATURED VIDEO</span><h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3><p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 20 ) ); ?></p></div>
					</article>
					<?php wp_reset_postdata(); ?>
				<?php else : ?>
					<article class="ctn-media-feature ctn-media-feature--empty"><div class="ctn-media-feature__media"><div class="ctn-fallback"><span>CT</span></div><span class="ctn-play"><?php echo ct_icon( 'play' ); ?></span></div><div><span>FEATURED VIDEO</span><h3>Your latest Creed Times video will appear here.</h3><p>Publish video content using Creed Times Core.</p></div></article>
				<?php endif; ?>

				<div class="ctn-media-side">
					<a href="<?php echo esc_url( post_type_exists( 'ct_short' ) ? get_post_type_archive_link( 'ct_short' ) : home_url( '/shorts/' ) ); ?>"><span>SHORTS</span><strong>Quick visual stories</strong><b>↗</b></a>
					<a href="<?php echo esc_url( post_type_exists( 'ct_podcast' ) ? get_post_type_archive_link( 'ct_podcast' ) : home_url( '/podcasts/' ) ); ?>"><span>PODCASTS</span><strong>Conversations & ideas</strong><b>↗</b></a>
					<a href="<?php echo esc_url( post_type_exists( 'ct_artwork' ) ? get_post_type_archive_link( 'ct_artwork' ) : home_url( '/visuals/' ) ); ?>"><span>VISUALS</span><strong>Infographics & artworks</strong><b>↗</b></a>
				</div>
			</div>
		</div>
	</section>

	<section class="ctn-section ctn-urdu" lang="ur" dir="rtl">
		<div class="ct-container">
			<div class="ctn-section-head ctn-section-head--urdu">
				<div><span class="ctn-kicker">اردو ڈیسک</span><h2>اردو میں خبریں، تجزیے اور رائے</h2></div>
				<a class="ctn-text-link" href="<?php echo esc_url( home_url( '/category/urdu/' ) ); ?>">تمام مضامین ←</a>
			</div>
			<div class="ctn-urdu-grid">
				<?php while ( $urdu_q->have_posts() ) : $urdu_q->the_post(); ?>
					<article><a class="ctn-urdu-card__media" href="<?php the_permalink(); ?>"><?php if ( has_post_thumbnail() ) : the_post_thumbnail( 'ct-card', array( 'loading' => 'lazy' ) ); else : ?><div class="ctn-fallback"><span>CT</span></div><?php endif; ?></a><span><?php echo esc_html( ctn_home_category( get_the_ID() ) ); ?></span><h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3><small><?php echo esc_html( get_the_date( 'd M Y' ) ); ?></small></article>
				<?php endwhile; wp_reset_postdata(); ?>
			</div>
		</div>
	</section>

	<section class="ctn-pro">
		<div class="ct-container ctn-pro__card">
			<div><span class="ctn-kicker">CREED PRO</span><h2>More context. Fewer distractions.</h2><p>Premium analysis, exclusive stories, bookmarks and private reading notes.</p></div>
			<a href="<?php echo esc_url( home_url( '/creed-pro/' ) ); ?>">Explore Creed Pro ↗</a>
		</div>
	</section>
</main>

<?php get_footer(); ?>
