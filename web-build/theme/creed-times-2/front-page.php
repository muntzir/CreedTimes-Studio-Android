<?php
get_header();

$hero_q = new WP_Query( array(
	'post_type'           => 'post',
	'post_status'         => 'publish',
	'posts_per_page'      => 1,
	'ignore_sticky_posts' => false,
) );
$hero = $hero_q->have_posts() ? $hero_q->posts[0] : null;
$hero_id = $hero ? $hero->ID : 0;

$trending_q = new WP_Query( array(
	'post_type'           => 'post',
	'post_status'         => 'publish',
	'posts_per_page'      => 5,
	'post__not_in'        => $hero_id ? array( $hero_id ) : array(),
	'meta_key'            => '_ct_trending',
	'meta_value'          => '1',
	'ignore_sticky_posts' => true,
) );
if ( ! $trending_q->have_posts() ) {
	$trending_q = new WP_Query( array(
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'posts_per_page'      => 5,
		'post__not_in'        => $hero_id ? array( $hero_id ) : array(),
		'ignore_sticky_posts' => true,
	) );
}

$spike_q = new WP_Query( array(
	'post_type'           => 'post',
	'post_status'         => 'publish',
	'posts_per_page'      => 6,
	'offset'              => 5,
	'post__not_in'        => $hero_id ? array( $hero_id ) : array(),
	'ignore_sticky_posts' => true,
) );

$latest_q = new WP_Query( array(
	'post_type'           => 'post',
	'post_status'         => 'publish',
	'posts_per_page'      => 6,
	'offset'              => 11,
	'post__not_in'        => $hero_id ? array( $hero_id ) : array(),
	'ignore_sticky_posts' => true,
) );

$analysis_q = new WP_Query( array(
	'post_type'           => 'post',
	'post_status'         => 'publish',
	'posts_per_page'      => 4,
	'category_name'       => 'analysis',
	'ignore_sticky_posts' => true,
) );

$urdu_q = new WP_Query( array(
	'post_type'           => 'post',
	'post_status'         => 'publish',
	'posts_per_page'      => 4,
	'category_name'       => 'urdu',
	'ignore_sticky_posts' => true,
) );
?>

<main class="ct-home">
	<section class="ct-home-top">
		<div class="ct-container ct-home-top__grid">
			<?php if ( $hero ) : setup_postdata( $hero ); ?>
				<article class="ct-hero-story">
					<a class="ct-hero-story__media" href="<?php the_permalink(); ?>">
						<?php if ( has_post_thumbnail() ) : the_post_thumbnail( 'ct-hero', array( 'fetchpriority' => 'high', 'decoding' => 'async' ) ); else : ?><div class="ct-media-fallback"><span>CT</span></div><?php endif; ?>
						<div class="ct-hero-story__shade"></div>
						<div class="ct-hero-story__content">
							<div class="ct-hero-story__badges">
								<span class="ct-badge ct-badge--accent"><?php esc_html_e( 'FEATURED', 'creed-times' ); ?></span>
								<?php if ( ct_is_pro_post() ) : ?><span class="ct-badge ct-badge--pro"><?php echo ct_icon( 'lock' ); ?> PRO</span><?php endif; ?>
							</div>
							<h1><?php the_title(); ?></h1>
							<p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 28 ) ); ?></p>
							<div class="ct-hero-story__meta">
								<?php echo get_avatar( get_the_author_meta( 'ID' ), 36, '', esc_attr( get_the_author() ) ); ?>
								<span><?php the_author(); ?></span>
								<span>•</span>
								<span><?php echo esc_html( ct_reading_time() ); ?> min read</span>
								<span>•</span>
								<span><?php echo esc_html( get_the_date( 'd M Y' ) ); ?></span>
							</div>
						</div>
					</a>
				</article>
				<?php wp_reset_postdata(); ?>
			<?php endif; ?>

			<aside class="ct-trending-panel">
				<div class="ct-panel-title">
					<div><span class="ct-flame">●</span><h2><?php esc_html_e( 'Trending Now', 'creed-times' ); ?></h2></div>
					<a href="<?php echo esc_url( home_url( '/category/news/' ) ); ?>"><?php esc_html_e( 'View All', 'creed-times' ); ?> →</a>
				</div>
				<div class="ct-trending-list">
					<?php $rank = 1; while ( $trending_q->have_posts() ) : $trending_q->the_post(); ?>
						<a class="ct-trending-item" href="<?php the_permalink(); ?>">
							<span class="ct-trending-item__rank"><?php echo esc_html( sprintf( '%02d', $rank++ ) ); ?></span>
							<span class="ct-trending-item__thumb"><?php if ( has_post_thumbnail() ) : the_post_thumbnail( 'thumbnail', array( 'loading' => 'lazy' ) ); else : ?><span class="ct-mini-fallback">CT</span><?php endif; ?></span>
							<span class="ct-trending-item__copy">
								<strong><?php the_title(); ?></strong>
								<small><?php echo esc_html( human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ); ?> ago</small>
							</span>
						</a>
					<?php endwhile; wp_reset_postdata(); ?>
				</div>
			</aside>
		</div>
	</section>

	<section class="ct-spike-section ct-section">
		<div class="ct-container">
			<div class="ct-section-heading">
				<div>
					<span class="ct-section-heading__icon">ϟ</span>
					<div><h2><?php esc_html_e( 'The Spike', 'creed-times' ); ?></h2><p><?php esc_html_e( 'Key stories you shouldn’t miss', 'creed-times' ); ?></p></div>
				</div>
				<div class="ct-carousel-controls"><button type="button" data-scroll-prev aria-label="Previous">←</button><button type="button" data-scroll-next aria-label="Next">→</button></div>
			</div>
			<div class="ct-spike-row" data-horizontal-scroll>
				<?php while ( $spike_q->have_posts() ) : $spike_q->the_post(); ?>
					<article class="ct-spike-card">
						<a href="<?php the_permalink(); ?>">
							<div class="ct-spike-card__media"><?php if ( has_post_thumbnail() ) : the_post_thumbnail( 'ct-card', array( 'loading' => 'lazy' ) ); else : ?><div class="ct-media-fallback"><span>CT</span></div><?php endif; ?></div>
							<span class="ct-spike-card__category"><?php echo esc_html( ct_primary_category() ? ct_primary_category()->name : 'Latest' ); ?></span>
							<h3><?php the_title(); ?></h3>
						</a>
					</article>
				<?php endwhile; wp_reset_postdata(); ?>
			</div>
		</div>
	</section>

	<section class="ct-latest-section ct-section">
		<div class="ct-container">
			<div class="ct-section-heading ct-section-heading--line">
				<div><span class="ct-section-heading__bar"></span><div><h2><?php esc_html_e( 'Latest Stories', 'creed-times' ); ?></h2></div></div>
				<a class="ct-view-all" href="<?php echo esc_url( home_url( '/category/news/' ) ); ?>">View All →</a>
			</div>
			<div class="ct-latest-layout">
				<div class="ct-story-grid">
					<?php while ( $latest_q->have_posts() ) : $latest_q->the_post(); get_template_part( 'template-parts/card', 'story' ); endwhile; wp_reset_postdata(); ?>
				</div>

				<aside class="ct-featured-video">
					<div class="ct-featured-video__title"><span><?php echo ct_icon( 'video' ); ?></span><h2><?php esc_html_e( 'Featured Video', 'creed-times' ); ?></h2></div>
					<?php
					$video_q = post_type_exists( 'ct_video' ) ? new WP_Query( array( 'post_type' => 'ct_video', 'posts_per_page' => 1, 'post_status' => 'publish' ) ) : new WP_Query( array(
						'post_type'      => 'post',
						'posts_per_page' => 1,
						'post_status'    => 'publish',
						'tax_query'      => array( array( 'taxonomy' => 'post_format', 'field' => 'slug', 'terms' => array( 'post-format-video' ) ) ),
					) );
					if ( $video_q->have_posts() ) : $video_q->the_post();
					?>
						<a class="ct-featured-video__media" href="<?php the_permalink(); ?>">
							<?php if ( has_post_thumbnail() ) : the_post_thumbnail( 'ct-card', array( 'loading' => 'lazy' ) ); else : ?><div class="ct-media-fallback"><span>CT</span></div><?php endif; ?>
							<span class="ct-play-button"><?php echo ct_icon( 'play' ); ?></span>
						</a>
						<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
						<div class="ct-featured-video__meta"><span><?php the_author(); ?></span><span>·</span><span><?php echo esc_html( get_the_date( 'd M Y' ) ); ?></span></div>
						<?php wp_reset_postdata(); ?>
					<?php else : ?>
						<div class="ct-empty-card"><?php esc_html_e( 'Publish a video to feature it here.', 'creed-times' ); ?></div>
					<?php endif; ?>
				</aside>
			</div>
		</div>
	</section>

	<section class="ct-authors-section ct-section">
		<div class="ct-container">
			<div class="ct-section-heading ct-section-heading--line">
				<div><span class="ct-section-heading__bar"></span><div><h2><?php esc_html_e( 'Authors & Contributors', 'creed-times' ); ?></h2></div></div>
				<a class="ct-view-all" href="<?php echo esc_url( home_url( '/authors/' ) ); ?>">View All Authors →</a>
			</div>
			<div class="ct-authors-layout">
				<div class="ct-authors-row">
					<?php
					$authors = get_users( array( 'orderby' => 'post_count', 'order' => 'DESC', 'number' => 6, 'has_published_posts' => true ) );
					foreach ( $authors as $author ) :
					?>
						<article class="ct-author-card">
							<a class="ct-author-card__avatar" href="<?php echo esc_url( get_author_posts_url( $author->ID ) ); ?>"><?php echo get_avatar( $author->ID, 120, '', esc_attr( $author->display_name ) ); ?></a>
							<h3><a href="<?php echo esc_url( get_author_posts_url( $author->ID ) ); ?>"><?php echo esc_html( $author->display_name ); ?></a></h3>
							<p><?php echo esc_html( ct_author_designation( $author->ID ) ); ?></p>
							<span><?php echo esc_html( count_user_posts( $author->ID, 'post', true ) ); ?> <?php esc_html_e( 'Articles', 'creed-times' ); ?></span>
						</article>
					<?php endforeach; ?>
				</div>
				<aside class="ct-write-card">
					<span class="ct-write-card__icon">✎</span>
					<h3><?php esc_html_e( 'Write for Creed Times', 'creed-times' ); ?></h3>
					<p><?php esc_html_e( 'Join our network of writers and contributors.', 'creed-times' ); ?></p>
					<a href="<?php echo esc_url( home_url( '/contribute/' ) ); ?>"><?php esc_html_e( 'Get Started', 'creed-times' ); ?> →</a>
				</aside>
			</div>
		</div>
	</section>

	<section class="ct-editorial-grid-section ct-section">
		<div class="ct-container ct-editorial-grid">
			<section class="ct-focus-card">
				<div class="ct-mini-section-title"><span>◎</span><h2><?php esc_html_e( 'In Focus', 'creed-times' ); ?></h2></div>
				<?php
				$focus_q = new WP_Query( array( 'post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => 1, 'meta_key' => '_ct_editor_pick', 'meta_value' => '1' ) );
				if ( ! $focus_q->have_posts() ) { $focus_q = new WP_Query( array( 'post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => 1, 'offset' => 17 ) ); }
				if ( $focus_q->have_posts() ) : $focus_q->the_post();
				?>
					<a class="ct-focus-card__media" href="<?php the_permalink(); ?>"><?php if ( has_post_thumbnail() ) : the_post_thumbnail( 'ct-card', array( 'loading' => 'lazy' ) ); else : ?><div class="ct-media-fallback"><span>CT</span></div><?php endif; ?></a>
					<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
					<div class="ct-story-card__meta"><span><?php echo esc_html( get_the_date( 'd M' ) ); ?></span><span><?php echo esc_html( ct_reading_time() ); ?> min read</span></div>
					<?php wp_reset_postdata(); ?>
				<?php endif; ?>
			</section>

			<section class="ct-analysis-list">
				<div class="ct-mini-section-title"><span>▤</span><h2><?php esc_html_e( 'Analysis', 'creed-times' ); ?></h2></div>
				<?php while ( $analysis_q->have_posts() ) : $analysis_q->the_post(); ?>
					<a class="ct-compact-story" href="<?php the_permalink(); ?>">
						<span class="ct-compact-story__thumb"><?php if ( has_post_thumbnail() ) : the_post_thumbnail( 'thumbnail', array( 'loading' => 'lazy' ) ); else : ?><span class="ct-mini-fallback">CT</span><?php endif; ?></span>
						<span><strong><?php the_title(); ?></strong><small><?php echo esc_html( human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ); ?> ago</small></span>
					</a>
				<?php endwhile; wp_reset_postdata(); ?>
			</section>

			<section class="ct-urdu-desk" lang="ur" dir="rtl">
				<div class="ct-mini-section-title"><span>ن</span><h2>اردو ڈیسک</h2></div>
				<?php if ( $urdu_q->have_posts() ) : $urdu_q->the_post(); ?>
					<a class="ct-urdu-desk__lead" href="<?php the_permalink(); ?>"><?php if ( has_post_thumbnail() ) : the_post_thumbnail( 'ct-card', array( 'loading' => 'lazy' ) ); else : ?><div class="ct-media-fallback"><span>CT</span></div><?php endif; ?></a>
					<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
					<small><?php echo esc_html( get_the_date( 'd M' ) ); ?> · <?php echo esc_html( ct_reading_time() ); ?> منٹ</small>
					<?php wp_reset_postdata(); ?>
				<?php else : ?>
					<p>اردو مضامین یہاں دکھائی دیں گے۔</p>
				<?php endif; ?>
			</section>

			<section class="ct-podcast-box">
				<div class="ct-mini-section-title"><span>◉</span><h2><?php esc_html_e( 'Podcasts', 'creed-times' ); ?></h2></div>
				<?php
				$pod_q = post_type_exists( 'ct_podcast' ) ? new WP_Query( array( 'post_type' => 'ct_podcast', 'post_status' => 'publish', 'posts_per_page' => 2 ) ) : null;
				if ( $pod_q && $pod_q->have_posts() ) :
					while ( $pod_q->have_posts() ) : $pod_q->the_post();
				?>
					<a class="ct-podcast-row" href="<?php the_permalink(); ?>">
						<span class="ct-podcast-row__thumb"><?php if ( has_post_thumbnail() ) : the_post_thumbnail( 'thumbnail', array( 'loading' => 'lazy' ) ); else : ?><span class="ct-mini-fallback">CT</span><?php endif; ?></span>
						<span><small><?php echo esc_html( get_post_meta( get_the_ID(), '_ct_show_name', true ) ?: 'Creed Times' ); ?></small><strong><?php the_title(); ?></strong></span>
						<span class="ct-podcast-row__play">▶</span>
					</a>
				<?php endwhile; wp_reset_postdata(); else : ?>
					<div class="ct-empty-card"><?php esc_html_e( 'Podcast episodes will appear here.', 'creed-times' ); ?></div>
				<?php endif; ?>
			</section>
		</div>
	</section>

	<section class="ct-pro-section ct-section">
		<div class="ct-container ct-pro-section__card">
			<div><span class="ct-overline">CREED PRO</span><h2><?php esc_html_e( 'Go deeper with Creed Pro.', 'creed-times' ); ?></h2><p><?php esc_html_e( 'Premium analysis, exclusive stories and powerful reading tools in one focused membership.', 'creed-times' ); ?></p></div>
			<ul><li>Premium analysis</li><li>Exclusive articles & videos</li><li>Save & organize research</li><li>Private article notes</li></ul>
			<a href="<?php echo esc_url( home_url( '/creed-pro/' ) ); ?>"><?php esc_html_e( 'Explore Creed Pro', 'creed-times' ); ?> →</a>
		</div>
	</section>
</main>

<?php get_footer(); ?>
