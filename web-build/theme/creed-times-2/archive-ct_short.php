<?php
get_header();
?>
<main class="ct-media-archive ct-section">
	<div class="ct-container">
		<header class="ct-archive-head"><span class="ct-overline">VERTICAL VIDEO</span><h1>Shorts</h1><p class="ct-archive-description">Quick reactions, explainers and short-form stories.</p></header>
		<?php if ( have_posts() ) : ?><div class="ct-shorts-grid">
		<?php while ( have_posts() ) : the_post(); ?>
			<article class="ct-short-card">
				<a class="ct-short-card__image" href="<?php the_permalink(); ?>"><?php if ( has_post_thumbnail() ) : the_post_thumbnail( 'ct-portrait', array( 'loading'=>'lazy' ) ); else : ?><div class="ct-media-fallback"><span>CT</span></div><?php endif; ?><span class="ct-play-button"><?php echo ct_icon( 'play' ); ?></span><?php if ( get_post_meta( get_the_ID(), '_ct_duration', true ) ) : ?><small><?php echo esc_html( get_post_meta( get_the_ID(), '_ct_duration', true ) ); ?></small><?php endif; ?></a>
				<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
			</article>
		<?php endwhile; ?></div><div class="ct-pagination"><?php the_posts_pagination( array( 'mid_size'=>2,'prev_text'=>'←','next_text'=>'→' ) ); ?></div><?php endif; ?>
	</div>
</main>
<?php get_footer(); ?>