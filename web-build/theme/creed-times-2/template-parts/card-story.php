<?php
$post_id = get_the_ID();
$cat = ct_primary_category( $post_id );
$is_pro = ct_is_pro_post( $post_id );
?>
<article <?php post_class( 'ct-story-card' ); ?>>
	<a class="ct-story-card__media" href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr( get_the_title() ); ?>">
		<?php if ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( 'full', array( 'loading' => 'lazy', 'decoding' => 'async', 'sizes' => '(max-width: 700px) 100vw, (max-width: 1100px) 50vw, 430px' ) ); ?>
		<?php else : ?>
			<div class="ct-media-fallback"><span>CT</span></div>
		<?php endif; ?>
		<div class="ct-story-card__badges">
			<?php echo ct_term_badge( $post_id ); ?>
			<?php if ( $is_pro ) : ?><span class="ct-badge ct-badge--pro"><?php echo ct_icon( 'lock' ); ?> PRO</span><?php endif; ?>
		</div>
	</a>
	<div class="ct-story-card__body">
		<h3 class="ct-story-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
		<p class="ct-story-card__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 18 ) ); ?></p>
		<div class="ct-story-card__meta">
			<span><?php echo esc_html( get_the_author() ); ?></span>
			<span><?php echo esc_html( get_the_date( 'd M' ) ); ?> · <?php echo esc_html( ct_reading_time() ); ?> min</span>
		</div>
	</div>
</article>
