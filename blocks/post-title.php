<?php

namespace BestUsenetReviews\Theme;

\add_action( 'acf/init', function () {
	\acf_register_block_type( [
		'name'            => 'post-title',
		'title'           => __( 'Post Title', 'bestusenetreviews' ),
		'description'     => __( 'Displays the post title.', 'bestusenetreviews' ),
		'render_callback' => __NAMESPACE__ . '\\render_post_title_block',
		'icon'            => 'editor-textcolor',
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
function render_post_title_block( $block, $content = '', $is_preview = false, $post_id = 0 ) {
	?>
	<h1 class="no-margin">
		<strong>
			<?php if ( \did_action( 'wp_body_open' ) ) : ?>
				<?php \the_title(); ?>
			<?php else : ?>
				<?php echo __( 'Post Title', 'bestusenetreviews' ); ?>
			<?php endif; ?>
		</strong>
	</h1>
	<?php
}
