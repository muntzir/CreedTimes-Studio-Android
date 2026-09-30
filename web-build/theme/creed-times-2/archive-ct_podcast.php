<?php
get_header();

$paged = max( 1, get_query_var( 'paged' ), get_query_var( 'page' ) );
$per_page = 10;

$native_ids = get_posts( array( 'post_type'=>'ct_podcast', 'post_status'=>'publish', 'posts_per_page'=>-1, 'fields'=>'ids' ) );
$legacy_ids = get_posts( array(
	'post_type'=>'post',
	'post_status'=>'publish',
	'posts_per_page'=>-1,
	'fields'=>'ids',
	'meta_query'=>array( array( 'key'=>'_ct_content_kind', 'value'=>'podcast' ) ),
) );
$ids = array_values( array_unique( array_merge( $native_ids, $legacy_ids ) ) );
usort( $ids, function( $a, $b ) { return get_post_time( 'U', true, $b ) <=> get_post_time( 'U', true, $a ); } );
$total = count( $ids );
$page_ids = array_slice( $ids, ( $paged - 1 ) * $per_page, $per_page );
$q = new WP_Query( array(
	'post_type'=>array( 'ct_podcast','post' ),
	'post_status'=>'publish',
	'post__in'=>$page_ids ?: array(0),
	'orderby'=>'post__in',
	'posts_per_page'=>$per_page,
) );
?>
<main class="ct-media-archive ct-section">
	<div class="ct-container">
		<header class="ct-archive-head"><span class="ct-overline">LISTEN</span><h1>Podcasts</h1><p class="ct-archive-description">Conversations, interviews, ideas and long-form audio from Creed Times programs.</p></header>
		<?php if ( $q->have_posts() ) : ?><div class="ct-podcast-archive-grid">
		<?php while ( $q->have_posts() ) : $q->the_post(); ?>
			<article class="ct-podcast-archive-card">
				<a class="ct-podcast-archive-card__image" href="<?php the_permalink(); ?>"><?php if ( has_post_thumbnail() ) : the_post_thumbnail( 'full', array( 'loading'=>'lazy','decoding'=>'async','sizes'=>'(max-width:700px) 120px, 180px' ) ); else : ?><div class="ct-media-fallback"><span>CT</span></div><?php endif; ?><span class="ct-podcast-row__play">▶</span></a>
				<div><span class="ct-overline"><?php echo esc_html( get_post_meta( get_the_ID(), '_ct_show_name', true ) ?: 'PODCAST' ); ?></span><h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2><p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 20 ) ); ?></p><small><?php echo esc_html( get_post_meta( get_the_ID(), '_ct_duration', true ) ?: get_the_date( 'd M Y' ) ); ?></small></div>
			</article>
		<?php endwhile; wp_reset_postdata(); ?></div>
		<?php
		$total_pages=max(1,(int)ceil($total/$per_page));
		if($total_pages>1): ?><div class="ct-pagination"><?php echo wp_kses_post(paginate_links(array('total'=>$total_pages,'current'=>$paged,'mid_size'=>2,'prev_text'=>'←','next_text'=>'→','type'=>'list'))); ?></div><?php endif;
		?>
		<?php else : ?><div class="ct-empty-state"><h2>No podcast episodes yet.</h2><p>Publish Podcast content from the Creed Times newsroom.</p></div><?php endif; ?>
	</div>
</main>
<?php get_footer(); ?>