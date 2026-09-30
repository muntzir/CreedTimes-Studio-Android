<?php
get_header();

$paged = max( 1, get_query_var( 'paged' ), get_query_var( 'page' ) );
$per_page = 12;

$native_ids = get_posts( array(
	'post_type' => 'ct_video',
	'post_status' => 'publish',
	'posts_per_page' => -1,
	'fields' => 'ids',
) );
$legacy_ids = get_posts( array(
	'post_type' => 'post',
	'post_status' => 'publish',
	'posts_per_page' => -1,
	'fields' => 'ids',
	'meta_query' => array(
		array( 'key' => '_ct_content_kind', 'value' => 'video' ),
	),
) );
$video_ids = array_values( array_unique( array_merge( $native_ids, $legacy_ids ) ) );

usort( $video_ids, function( $a, $b ) {
	return get_post_time( 'U', true, $b ) <=> get_post_time( 'U', true, $a );
} );

$total = count( $video_ids );
$page_ids = array_slice( $video_ids, ( $paged - 1 ) * $per_page, $per_page );
$video_q = new WP_Query( array(
	'post_type' => array( 'ct_video', 'post' ),
	'post_status' => 'publish',
	'post__in' => $page_ids ?: array( 0 ),
	'orderby' => 'post__in',
	'posts_per_page' => $per_page,
) );
?>
<main class="ct-media-archive ct-section">
	<div class="ct-container">
		<header class="ct-archive-head">
			<span class="ct-overline">WATCH</span>
			<h1>Videos</h1>
			<p class="ct-archive-description">Creed Times interviews, explainers, documentaries and videos — including new uploads synced automatically from YouTube.</p>
		</header>

		<div class="ct-media-toolbar">
			<span><?php echo esc_html( $total ); ?> videos</span>
			<a href="<?php echo esc_url( ct_get_social_links()['youtube'] ); ?>" target="_blank" rel="noopener"><?php echo ct_brand_icon( 'youtube' ); ?> YouTube Channel ↗</a>
		</div>

		<?php if ( $video_q->have_posts() ) : ?>
			<div class="ct-media-grid">
			<?php while ( $video_q->have_posts() ) : $video_q->the_post(); ?>
				<article class="ct-media-card">
					<a class="ct-media-card__image" href="<?php the_permalink(); ?>">
						<?php if ( has_post_thumbnail() ) : the_post_thumbnail( 'full', array( 'loading'=>'lazy', 'decoding'=>'async', 'sizes'=>'(max-width:700px) 100vw, (max-width:1100px) 50vw, 420px' ) ); else : ?><div class="ct-media-fallback"><span>CT</span></div><?php endif; ?>
						<span class="ct-play-button"><?php echo ct_icon( 'play' ); ?></span>
						<?php if ( get_post_meta( get_the_ID(), '_ct_duration', true ) ) : ?><small><?php echo esc_html( get_post_meta( get_the_ID(), '_ct_duration', true ) ); ?></small><?php endif; ?>
					</a>
					<div>
						<span class="ct-overline"><?php echo esc_html( get_post_meta( get_the_ID(), '_ct_show_name', true ) ?: ( get_post_meta( get_the_ID(), '_ct_source', true ) === 'youtube' ? 'YOUTUBE' : 'VIDEO' ) ); ?></span>
						<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						<p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 18 ) ); ?></p>
						<div class="ct-media-card__meta"><span><?php the_author(); ?></span><span><?php echo esc_html( get_the_date( 'd M Y' ) ); ?></span></div>
					</div>
				</article>
			<?php endwhile; wp_reset_postdata(); ?>
			</div>

			<?php
			$total_pages = max( 1, (int) ceil( $total / $per_page ) );
			if ( $total_pages > 1 ) :
			?>
				<div class="ct-pagination">
					<?php echo wp_kses_post( paginate_links( array(
						'total' => $total_pages,
						'current' => $paged,
						'mid_size' => 2,
						'prev_text' => '←',
						'next_text' => '→',
						'type' => 'list',
					) ) ); ?>
				</div>
			<?php endif; ?>
		<?php else : ?>
			<div class="ct-empty-state"><h2>No videos yet.</h2><p>Use Creed Times → Dashboard → Sync YouTube Now, or publish a Video from the newsroom.</p></div>
		<?php endif; ?>
	</div>
</main>
<?php get_footer(); ?>