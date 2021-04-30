<?php

namespace BestUsenetReviews\Theme;

\add_action( 'acf/init', function () {
	\acf_register_block_type( [
		'name'            => 'review-button',
		'title'           => __( 'Review Button', 'bestusenetreviews' ),
		'description'     => __( 'Displays a large review CTA button.', 'bestusenetreviews' ),
		'render_callback' => __NAMESPACE__ . '\\render_review_button_block',
		'icon'            => 'text',
	] );
} );

/**
 * Renders the review large.
 *
 * @param array  $block      The block settings and attributes.
 * @param string $content    The block inner HTML (empty).
 * @param bool   $is_preview True during AJAX preview.
 * @param int    $post_id    The post ID this block is saved to.
 *
 * @return void
 */
function render_review_button_block( $block, $content = '', $is_preview = false, $post_id = 0 ) {
	$admin = ! \did_action( 'wp_body_open' );
	$post  = \get_post( $post_id );
	$en_id = get_en_id( $post_id );

	if ( $admin ) : ?>
		<div class="review-cta-single wp-block-button is-style-large">
			<a href="javascript:void(0)" class="wp-block-button__link">
				<?php echo __( 'Visit Website', 'bestusenetreviews' ); ?>
			</a>
		</div>
	<?php elseif ( 'review' === $post->post_type ) : ?>
		<div class="review-cta-single">
			<?php echo get_cta_button( $post->ID, 'button-large' ); ?>
		</div>
	<?php endif;
}
