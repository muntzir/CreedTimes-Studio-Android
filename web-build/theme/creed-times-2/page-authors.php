<?php
/*
Template Name: Creed Times Authors
*/
get_header();
$authors = get_users( array( 'orderby' => 'post_count', 'order' => 'DESC', 'has_published_posts' => true ) );
?>
<main class="ct-authors-page ct-section">
	<div class="ct-container">
		<header class="ct-archive-head">
			<span class="ct-overline">PEOPLE</span>
			<h1>Authors & Contributors</h1>
			<p class="ct-archive-description">Meet the writers, analysts and contributors behind Creed Times.</p>
		</header>
		<div class="ct-authors-directory">
			<?php foreach ( $authors as $author ) : ?>
				<article class="ct-author-directory-card">
					<a href="<?php echo esc_url( get_author_posts_url( $author->ID ) ); ?>" class="ct-author-directory-card__avatar"><?php echo get_avatar( $author->ID, 140, '', esc_attr( $author->display_name ) ); ?></a>
					<div><h2><a href="<?php echo esc_url( get_author_posts_url( $author->ID ) ); ?>"><?php echo esc_html( $author->display_name ); ?></a></h2><p class="ct-author-directory-card__role"><?php echo esc_html( ct_author_designation( $author->ID ) ); ?></p><?php if ( $author->description ) : ?><p><?php echo esc_html( wp_trim_words( $author->description, 24 ) ); ?></p><?php endif; ?><a class="ct-author-directory-card__link" href="<?php echo esc_url( get_author_posts_url( $author->ID ) ); ?>">View profile →</a></div>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</main>
<?php get_footer(); ?>
