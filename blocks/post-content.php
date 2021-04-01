<?php

namespace BestUsenetReviews\Theme;

\add_action( 'acf/init', function () {

	\acf_register_block_type( [
		'name'            => 'post-content',
		'title'           => __( 'Post Content', 'bestusenetreviews' ),
		'description'     => __( 'Displays the post content.', 'bestusenetreviews' ),
		'render_callback' => __NAMESPACE__ . '\\render_post_content_block',
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
function render_post_content_block( $block, $content = '', $is_preview = false, $post_id = 0 ) {
	$admin = ! \did_action( 'wp_body_open' );
	$post  = \get_post( $post_id );
	$en_id = get_en_id( $post_id );

	if ( $admin ) : ?>
		<div class="wp-block-group__inner-container">
			<h3><?php echo __( 'Post Content', 'bestusenetreviews' ); ?></h3>
			<?php echo get_lorem_ipsum( 5 ); ?>
		</div>
	<?php else :
		if ( isset( $post->post_content ) ) {
			echo \apply_filters( 'the_content', $post->post_content );
		}
	endif;
}
