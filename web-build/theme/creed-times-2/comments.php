<?php
if ( post_password_required() ) { return; }
?>
<div id="comments" class="ct-comments">
	<?php if ( have_comments() ) : ?>
		<h2><?php printf( esc_html( _nx( '%1$s Comment', '%1$s Comments', get_comments_number(), 'comments title', 'creed-times' ) ), number_format_i18n( get_comments_number() ) ); ?></h2>
		<ol class="ct-comment-list"><?php wp_list_comments( array( 'style' => 'ol', 'short_ping' => true, 'avatar_size' => 48 ) ); ?></ol>
		<?php the_comments_navigation(); ?>
	<?php endif; ?>
	<?php comment_form( array( 'class_submit' => 'ct-primary-btn', 'title_reply' => 'Join the discussion' ) ); ?>
</div>
