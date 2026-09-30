<?php
get_header();
$author = get_queried_object();
$author_id = $author ? (int) $author->ID : 0;
$description = get_the_author_meta( 'description', $author_id );
?>
<main class="ct-author-page ct-section">
	<div class="ct-container">
		<section class="ct-author-profile">
			<div class="ct-author-profile__avatar"><?php echo get_avatar( $author_id, 180, '', esc_attr( $author->display_name ?? '' ) ); ?></div>
			<div class="ct-author-profile__copy">
				<span class="ct-overline">AUTHOR</span>
				<h1><?php echo esc_html( $author->display_name ?? '' ); ?></h1>
				<p class="ct-author-profile__role"><?php echo esc_html( ct_author_designation( $author_id ) ); ?></p>
				<?php if ( $description ) : ?><div class="ct-author-profile__bio"><?php echo wp_kses_post( wpautop( $description ) ); ?></div><?php endif; ?>
				<div class="ct-author-profile__stats">
					<div><strong><?php echo esc_html( count_user_posts( $author_id, 'post', true ) ); ?></strong><span>Articles</span></div>
					<?php if ( post_type_exists( 'ct_video' ) ) : ?><div><strong><?php echo esc_html( count_user_posts( $author_id, 'ct_video', true ) ); ?></strong><span>Videos</span></div><?php endif; ?>
				</div>
			</div>
			<div class="ct-author-profile__action">
				<?php if ( shortcode_exists( 'ct_follow_author_button' ) ) : echo do_shortcode( '[ct_follow_author_button author="' . esc_attr( $author_id ) . '"]' ); endif; ?>
			</div>
		</section>

		<div class="ct-author-tabs">
			<a class="is-active" href="<?php echo esc_url( get_author_posts_url( $author_id ) ); ?>">Articles</a>
			<?php if ( post_type_exists( 'ct_video' ) ) : ?><a href="<?php echo esc_url( add_query_arg( 'content', 'video', get_author_posts_url( $author_id ) ) ); ?>">Videos</a><?php endif; ?>
			<?php if ( post_type_exists( 'ct_podcast' ) ) : ?><a href="<?php echo esc_url( add_query_arg( 'content', 'podcast', get_author_posts_url( $author_id ) ) ); ?>">Podcasts</a><?php endif; ?>
		</div>

		<?php if ( have_posts() ) : ?>
			<div class="ct-story-grid ct-story-grid--archive">
				<?php while ( have_posts() ) : the_post(); get_template_part( 'template-parts/card', 'story' ); endwhile; ?>
			</div>
			<div class="ct-pagination"><?php the_posts_pagination( array( 'mid_size' => 2, 'prev_text' => '←', 'next_text' => '→' ) ); ?></div>
		<?php endif; ?>
	</div>
</main>
<?php get_footer(); ?>
