<?php
get_header();
while ( have_posts() ) : the_post();
?>
<main class="ct-page ct-section">
	<div class="ct-container ct-page__inner">
		<header class="ct-page__header"><h1><?php the_title(); ?></h1></header>
		<div class="ct-article-prose"><?php the_content(); ?></div>
	</div>
</main>
<?php
endwhile;
get_footer();
?>