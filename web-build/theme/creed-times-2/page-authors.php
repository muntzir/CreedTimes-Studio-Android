<?php
/*
Template Name: Creed Times Authors
*/
get_header();
$authors = get_users( array(
	'orderby' => 'post_count',
	'order' => 'DESC',
	'has_published_posts' => true,
) );
?>
<main class="ctv-page ctv-authors-page">
	<section class="ctv-page-hero ctv-page-hero--authors">
		<div class="ct-container ctv-page-hero__inner">
			<span class="ctv-kicker">THE PEOPLE BEHIND CREED TIMES</span>
			<h1>Authors, writers<br><em>& newsroom voices.</em></h1>
			<p>Meet the people writing, reporting, analysing and presenting stories for Creed Times.</p>
		</div>
	</section>

	<section class="ctv-page-section">
		<div class="ct-container">
			<div class="ctv-authors-directory">
				<?php foreach ( $authors as $author ) :
					$recent = get_posts( array(
						'author' => $author->ID,
						'post_type' => 'post',
						'post_status' => 'publish',
						'posts_per_page' => 1,
					) );
				?>
					<article class="ctv-author-profile-card">
						<div class="ctv-author-profile-card__top">
							<a class="ctv-author-profile-card__avatar" href="<?php echo esc_url( get_author_posts_url( $author->ID ) ); ?>"><?php echo get_avatar( $author->ID, 160, '', esc_attr( $author->display_name ) ); ?></a>
							<div>
								<span class="ctv-author-profile-card__role"><?php echo esc_html( ct_author_designation( $author->ID ) ); ?></span>
								<h2><a href="<?php echo esc_url( get_author_posts_url( $author->ID ) ); ?>"><?php echo esc_html( $author->display_name ); ?></a></h2>
								<p><?php echo esc_html( $author->description ? wp_trim_words( $author->description, 24 ) : 'Articles and editorial work published on Creed Times.' ); ?></p>
							</div>
						</div>
						<div class="ctv-author-profile-card__meta">
							<span><strong><?php echo esc_html( count_user_posts( $author->ID, 'post', true ) ); ?></strong> Articles</span>
							<?php if ( $recent ) : ?><a href="<?php echo esc_url( get_permalink( $recent[0] ) ); ?>">Latest: <?php echo esc_html( wp_trim_words( get_the_title( $recent[0] ), 7 ) ); ?> ↗</a><?php endif; ?>
						</div>
						<a class="ctv-author-profile-card__button" href="<?php echo esc_url( get_author_posts_url( $author->ID ) ); ?>">View Author Profile →</a>
					</article>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
</main>
<?php get_footer(); ?>