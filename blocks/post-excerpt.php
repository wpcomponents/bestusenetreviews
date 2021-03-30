<?php

namespace BestUsenetReviews\Theme;

\add_action( 'acf/init', function () {
	\acf_register_block_type( [
		'name'            => 'post-excerpt',
		'title'           => __( 'Post Excerpt', 'bestusenetreviews' ),
		'description'     => __( 'Displays the post excerpt.', 'bestusenetreviews' ),
		'render_callback' => __NAMESPACE__ . '\\render_post_excerpt_block',
		'icon'            => 'editor-justify',
	] );
} );

/**
 * Renders the review rating.
 *
 * @param array  $block      The block settings and attributes.
 * @param string $content    The block inner HTML (empty).
 * @param bool   $is_preview True during AJAX preview.
 * @param int    $post_id    The post ID this block is saved to.
 *
 * @return void
 */
function render_post_excerpt_block( $block, $content = '', $is_preview = false, $post_id = 0 ) {
	?>
	<div class="wp-block-post-excerpt">
		<div class="wp-block-post-excerpt__excerpt">
			<?php if ( \did_action( 'wp_body_open' ) ) : ?>
				<?php \the_excerpt(); ?>
			<?php else : ?>
				<?php echo get_lorem_ipsum( 200 ); ?>
			<?php endif; ?>
		</div>
	</div>
	<?php
}
