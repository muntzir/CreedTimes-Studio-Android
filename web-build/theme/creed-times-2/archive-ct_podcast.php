<?php
get_header();
?>
<main class="ct-media-archive ct-section">
	<div class="ct-container">
		<header class="ct-archive-head"><span class="ct-overline">LISTEN</span><h1>Podcasts</h1><p class="ct-archive-description">Conversations, interviews and ideas from Creed Times programs and podcasts.</p></header>
		<?php if ( have_posts() ) : ?><div class="ct-podcast-archive-grid">
		<?php while ( have_posts() ) : the_post(); ?>
			<article class="ct-podcast-archive-card">
				<a class="ct-podcast-archive-card__image" href="<?php the_permalink(); ?>"><?php if ( has_post_thumbnail() ) : the_post_thumbnail( 'ct-square', array( 'loading'=>'lazy' ) ); else : ?><div class="ct-media-fallback"><span>CT</span></div><?php endif; ?><span class="ct-podcast-row__play">▶</span></a>
				<div><span class="ct-overline"><?php echo esc_html( get_post_meta( get_the_ID(), '_ct_show_name', true ) ?: 'PODCAST' ); ?></span><h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2><p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 20 ) ); ?></p><small><?php echo esc_html( get_post_meta( get_the_ID(), '_ct_duration', true ) ); ?></small></div>
			</article>
		<?php endwhile; ?></div><div class="ct-pagination"><?php the_posts_pagination( array( 'mid_size'=>2,'prev_text'=>'←','next_text'=>'→' ) ); ?></div><?php endif; ?>
	</div>
</main>
<?php get_footer(); ?>