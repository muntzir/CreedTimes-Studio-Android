<?php
get_header();

while ( have_posts() ) :
	the_post();
	$post_id   = get_the_ID();
	$author_id = (int) get_the_author_meta( 'ID' );
	$cat       = ct_primary_category( $post_id );
	$is_pro    = ct_is_pro_post( $post_id );
	$can_view  = ct_can_view_post( $post_id );
	$sources   = get_post_meta( $post_id, '_ct_sources', true );
	$keypoints = get_post_meta( $post_id, '_ct_key_points', true );
	$audio_url = get_post_meta( $post_id, '_ct_audio_url', true );
	$updated   = get_the_modified_time( 'U' ) > get_the_time( 'U' ) + DAY_IN_SECONDS;
	$share_url = rawurlencode( get_permalink() );
	$share_txt = rawurlencode( get_the_title() );
?>
<main class="ct-article-page" data-article>
	<article <?php post_class( 'ct-article' ); ?>>
		<header class="ct-article-header">
			<div class="ct-container ct-article-header__inner">
				<nav class="ct-breadcrumbs" aria-label="Breadcrumb">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a>
					<span>›</span>
					<?php if ( $cat ) : ?><a href="<?php echo esc_url( get_category_link( $cat ) ); ?>"><?php echo esc_html( $cat->name ); ?></a><span>›</span><?php endif; ?>
					<span><?php echo esc_html( wp_trim_words( get_the_title(), 8 ) ); ?></span>
				</nav>

				<div class="ct-article-header__meta-top">
					<?php echo ct_term_badge( $post_id ); ?>
					<?php if ( $is_pro ) : ?><span class="ct-badge ct-badge--pro"><?php echo ct_icon( 'lock' ); ?> PRO</span><?php endif; ?>
					<?php if ( get_post_meta( $post_id, '_ct_developing', true ) ) : ?><span class="ct-badge ct-badge--live"><i></i> DEVELOPING</span><?php endif; ?>
					<?php if ( $updated ) : ?><span class="ct-update-label">Updated <?php echo esc_html( get_the_modified_date( 'd M Y' ) ); ?></span><?php endif; ?>
				</div>

				<h1><?php the_title(); ?></h1>

				<?php if ( has_excerpt() ) : ?><p class="ct-article-dek"><?php echo esc_html( get_the_excerpt() ); ?></p><?php endif; ?>

				<div class="ct-article-byline">
					<a href="<?php echo esc_url( get_author_posts_url( $author_id ) ); ?>" class="ct-article-byline__avatar"><?php echo get_avatar( $author_id, 54, '', esc_attr( get_the_author() ) ); ?></a>
					<div>
						<strong><a href="<?php echo esc_url( get_author_posts_url( $author_id ) ); ?>"><?php the_author(); ?></a></strong>
						<span><?php echo esc_html( ct_author_designation( $author_id ) ); ?></span>
					</div>
					<div class="ct-article-byline__details">
						<span><?php echo esc_html( get_the_date( 'd M Y' ) ); ?></span>
						<span>•</span>
						<span><?php echo esc_html( ct_reading_time() ); ?> min read</span>
					</div>
				</div>

				<div class="ct-article-actions">
					<?php if ( shortcode_exists( 'ct_save_button' ) ) : echo do_shortcode( '[ct_save_button]' ); endif; ?>
					<button class="ct-action-btn" type="button" data-share-toggle><?php echo ct_icon( 'share' ); ?> <span>Share</span></button>
					<div class="ct-share-menu">
						<button class="ct-action-btn" type="button" data-share-toggle><?php echo ct_icon( 'share' ); ?> <span>Share</span></button>
						<div class="ct-share-menu__panel" data-share-menu hidden>
							<a href="https://wa.me/?text=<?php echo esc_attr( $share_txt . '%20' . $share_url ); ?>" target="_blank" rel="noopener">WhatsApp</a>
							<a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo esc_attr( $share_url ); ?>" target="_blank" rel="noopener">Facebook</a>
							<a href="https://twitter.com/intent/tweet?url=<?php echo esc_attr( $share_url ); ?>&text=<?php echo esc_attr( $share_txt ); ?>" target="_blank" rel="noopener">X</a>
							<button type="button" data-copy-link data-copied-label="Copied">Copy link</button>
						</div>
					</div>
					<?php if ( $audio_url ) : ?><a class="ct-action-btn" href="#ct-audio-player">🔊 <span>Listen</span></a><?php endif; ?>
				</div>
			</div>
		</header>

		<?php if ( has_post_thumbnail() ) : ?>
			<div class="ct-container ct-article-hero">
				<?php the_post_thumbnail( 'ct-hero', array( 'class' => 'ct-article-hero__image', 'fetchpriority' => 'high', 'decoding' => 'async' ) ); ?>
				<?php if ( get_the_post_thumbnail_caption() ) : ?><p class="ct-article-caption"><?php echo wp_kses_post( get_the_post_thumbnail_caption() ); ?></p><?php endif; ?>
			</div>
		<?php endif; ?>

		<div class="ct-container ct-article-layout">
			<aside class="ct-article-share-rail" aria-label="Share article">
				<div class="ct-article-share-rail__sticky">
					<span>SHARE</span>
					<a href="https://wa.me/?text=<?php echo esc_attr( $share_txt . '%20' . $share_url ); ?>" target="_blank" rel="noopener">WA</a>
					<a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo esc_attr( $share_url ); ?>" target="_blank" rel="noopener">f</a>
					<a href="https://twitter.com/intent/tweet?url=<?php echo esc_attr( $share_url ); ?>&text=<?php echo esc_attr( $share_txt ); ?>" target="_blank" rel="noopener">X</a>
					<button type="button" data-copy-link data-copied-label="✓">↗</button>
				</div>
			</aside>

			<div class="ct-article-main">
				<?php if ( $audio_url ) : ?>
					<section id="ct-audio-player" class="ct-audio-card">
						<div><span>LISTEN</span><strong>Listen to this article</strong></div>
						<audio controls preload="none" src="<?php echo esc_url( $audio_url ); ?>"></audio>
					</section>
				<?php endif; ?>

				<?php if ( $keypoints ) : ?>
					<section class="ct-key-points">
						<h2>Key Points</h2>
						<?php echo wp_kses_post( wpautop( $keypoints ) ); ?>
					</section>
				<?php endif; ?>

				<?php if ( $can_view ) : ?>
					<div class="ct-article-prose"><?php the_content(); ?></div>
				<?php else : ?>
					<div class="ct-article-prose ct-article-prose--teaser"><?php echo wp_kses_post( wpautop( wp_trim_words( wp_strip_all_tags( get_the_content() ), 120 ) ) ); ?></div>
					<section class="ct-paywall">
						<span class="ct-badge ct-badge--pro"><?php echo ct_icon( 'lock' ); ?> CREED PRO</span>
						<h2>Continue with Creed Pro</h2>
						<p>Unlock this premium story and other member-only analysis.</p>
						<a href="<?php echo esc_url( home_url( '/creed-pro/' ) ); ?>">Explore Creed Pro →</a>
					</section>
				<?php endif; ?>

				<?php if ( $sources ) : ?>
					<details class="ct-sources-box" open>
						<summary>Sources & References</summary>
						<div><?php echo wp_kses_post( wpautop( $sources ) ); ?></div>
					</details>
				<?php endif; ?>

				<?php if ( get_the_tags() ) : ?>
					<div class="ct-article-tags">
						<?php foreach ( get_the_tags() as $tag ) : if ( ct_legacy_tag_excluded( $tag->term_id ) ) continue; ?>
							<a href="<?php echo esc_url( get_tag_link( $tag ) ); ?>">#<?php echo esc_html( $tag->name ); ?></a>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

				<?php if ( shortcode_exists( 'ct_private_notes' ) ) : ?>
					<section class="ct-private-notes-section">
						<?php echo do_shortcode( '[ct_private_notes]' ); ?>
					</section>
				<?php endif; ?>
			</div>

			<aside class="ct-article-side">
				<div class="ct-side-panel">
					<div class="ct-panel-title"><div><h2>Latest</h2></div></div>
					<?php
					$side_q = new WP_Query( array( 'post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => 5, 'post__not_in' => array( $post_id ), 'ignore_sticky_posts' => true ) );
					while ( $side_q->have_posts() ) : $side_q->the_post();
					?>
						<a class="ct-side-story" href="<?php the_permalink(); ?>"><span><?php echo esc_html( get_the_date( 'd M' ) ); ?></span><strong><?php the_title(); ?></strong></a>
					<?php endwhile; wp_reset_postdata(); ?>
				</div>
			</aside>
		</div>

		<section class="ct-container ct-author-box">
			<?php echo get_avatar( $author_id, 96, '', esc_attr( get_the_author() ) ); ?>
			<div><span class="ct-overline">WRITTEN BY</span><h2><a href="<?php echo esc_url( get_author_posts_url( $author_id ) ); ?>"><?php the_author(); ?></a></h2><p><?php echo esc_html( get_the_author_meta( 'description' ) ?: ct_author_designation( $author_id ) ); ?></p><a href="<?php echo esc_url( get_author_posts_url( $author_id ) ); ?>">View all stories →</a></div>
		</section>

		<section class="ct-related ct-section">
			<div class="ct-container">
				<div class="ct-section-heading ct-section-heading--line"><div><span class="ct-section-heading__bar"></span><div><h2>Related Stories</h2></div></div></div>
				<div class="ct-story-grid">
					<?php
					$related_q = new WP_Query( array(
						'post_type'           => 'post',
						'post_status'         => 'publish',
						'posts_per_page'      => 3,
						'post__not_in'        => array( $post_id ),
						'category__in'        => wp_get_post_categories( $post_id ),
						'ignore_sticky_posts' => true,
					) );
					while ( $related_q->have_posts() ) : $related_q->the_post(); get_template_part( 'template-parts/card', 'story' ); endwhile; wp_reset_postdata();
					?>
				</div>
			</div>
		</section>

		<?php if ( comments_open() || get_comments_number() ) : ?><section class="ct-comments-section"><div class="ct-container ct-comments-wrap"><?php comments_template(); ?></div></section><?php endif; ?>
	</article>
</main>
<?php endwhile; wp_reset_postdata(); get_footer(); ?>
