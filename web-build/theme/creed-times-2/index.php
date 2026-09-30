<?php
get_header();
?>
<main class="ct-archive-page ct-section">
	<div class="ct-container">
		<div class="ct-archive-head">
			<span class="ct-overline"><?php esc_html_e( 'CREED TIMES', 'creed-times' ); ?></span>
			<h1><?php echo esc_html( is_home() ? get_the_title( get_option( 'page_for_posts' ) ) : get_the_archive_title() ); ?></h1>
			<?php the_archive_description( '<div class="ct-archive-description">', '</div>' ); ?>
		</div>
		<?php if ( have_posts() ) : ?>
			<div class="ct-story-grid ct-story-grid--archive">
				<?php while ( have_posts() ) : the_post(); get_template_part( 'template-parts/card', 'story' ); endwhile; ?>
			</div>
			<div class="ct-pagination"><?php the_posts_pagination( array( 'mid_size' => 2, 'prev_text' => '←', 'next_text' => '→' ) ); ?></div>
		<?php else : ?>
			<div class="ct-empty-state"><h2><?php esc_html_e( 'Nothing here yet.', 'creed-times' ); ?></h2><p><?php esc_html_e( 'Try another section or search Creed Times.', 'creed-times' ); ?></p></div>
		<?php endif; ?>
	</div>
</main>
<?php get_footer(); ?>
