<?php
get_header();
?>
<main class="ct-media-archive ct-section">
	<div class="ct-container">
		<header class="ct-archive-head"><span class="ct-overline">WATCH</span><h1>Videos</h1><p class="ct-archive-description">Interviews, explainers, documentaries and current-affairs video from Creed Times.</p></header>
		<?php if ( have_posts() ) : ?><div class="ct-media-grid">
		<?php while ( have_posts() ) : the_post(); ?>
			<article class="ct-media-card">
				<a class="ct-media-card__image" href="<?php the_permalink(); ?>"><?php if ( has_post_thumbnail() ) : the_post_thumbnail( 'ct-card', array( 'loading'=>'lazy' ) ); else : ?><div class="ct-media-fallback"><span>CT</span></div><?php endif; ?><span class="ct-play-button"><?php echo ct_icon( 'play' ); ?></span><?php if ( get_post_meta( get_the_ID(), '_ct_duration', true ) ) : ?><small><?php echo esc_html( get_post_meta( get_the_ID(), '_ct_duration', true ) ); ?></small><?php endif; ?></a>
				<div><span class="ct-overline"><?php echo esc_html( get_post_meta( get_the_ID(), '_ct_show_name', true ) ?: 'VIDEO' ); ?></span><h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2><p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 18 ) ); ?></p></div>
			</article>
		<?php endwhile; ?></div><div class="ct-pagination"><?php the_posts_pagination( array( 'mid_size'=>2,'prev_text'=>'←','next_text'=>'→' ) ); ?></div><?php endif; ?>
	</div>
</main>
<?php get_footer(); ?>