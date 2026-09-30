<?php
get_header();
?>
<main class="ct-search-page ct-section">
	<div class="ct-container">
		<header class="ct-search-page__head">
			<span class="ct-overline">SEARCH</span>
			<h1><?php printf( esc_html__( 'Results for “%s”', 'creed-times' ), esc_html( get_search_query() ) ); ?></h1>
			<form class="ct-search-filters" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<input type="search" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="Search Creed Times…">
				<select name="ct_type"><option value="">All content</option><option value="post">Articles</option><?php if ( post_type_exists( 'ct_video' ) ) : ?><option value="ct_video">Videos</option><?php endif; ?><?php if ( post_type_exists( 'ct_podcast' ) ) : ?><option value="ct_podcast">Podcasts</option><?php endif; ?></select>
				<?php if ( taxonomy_exists( 'ct_language' ) ) : wp_dropdown_categories( array( 'taxonomy' => 'ct_language', 'name' => 'ct_language', 'show_option_all' => 'All languages', 'value_field' => 'slug', 'hide_empty' => false ) ); endif; ?>
				<?php if ( taxonomy_exists( 'ct_region' ) ) : wp_dropdown_categories( array( 'taxonomy' => 'ct_region', 'name' => 'ct_region', 'show_option_all' => 'All regions', 'value_field' => 'slug', 'hide_empty' => false ) ); endif; ?>
				<button type="submit">Search</button>
			</form>
		</header>

		<?php if ( have_posts() ) : ?>
			<div class="ct-story-grid ct-story-grid--archive">
				<?php while ( have_posts() ) : the_post(); get_template_part( 'template-parts/card', 'story' ); endwhile; ?>
			</div>
			<div class="ct-pagination"><?php the_posts_pagination( array( 'mid_size' => 2, 'prev_text' => '←', 'next_text' => '→' ) ); ?></div>
		<?php else : ?>
			<div class="ct-empty-state"><h2>No results found.</h2><p>Try a broader topic, different spelling, or another filter.</p></div>
		<?php endif; ?>
	</div>
</main>
<?php get_footer(); ?>
