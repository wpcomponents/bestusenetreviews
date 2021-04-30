<?php

namespace BestUsenetReviews\Theme;

\add_action( 'acf/init', function () {
	\acf_register_block_type( [
		'name'            => 'review-cta',
		'title'           => __( 'Review CTA', 'bestusenetreviews' ),
		'description'     => __( 'Displays a review cta.', 'bestusenetreviews' ),
		'render_callback' => __NAMESPACE__ . '\\render_review_cta_block',
		'icon'            => 'align-center',
	] );
} );

/**
 * Renders the review cta.
 *
 * @param array  $block      The block settings and attributes.
 * @param string $content    The block inner HTML (empty).
 * @param bool   $is_preview True during AJAX preview.
 * @param int    $post_id    The post ID this block is saved to.
 */
function render_review_cta_block( $block, $content = '', $is_preview = false, $post_id = 0 ) {
	$en_id = get_en_id( $post_id );
	$title = __( 'Get Special', 'bestusenetreviews' ) . ' ' . \get_field( 'discount', $en_id ) . __( '% Discount Now', 'bestusenetreviews' );
	?>
	<div class="review-cta-block">
		<p>
		<?php echo \wp_get_attachment_image(
			\get_field( 'logo_alt', $en_id ),
			'full',
			false,
			[
				'width' => 300,
			]
		); ?>
		</p>
		<h2><strong><?php echo $title; ?></strong></h2>
		<?php echo get_cta_button( $post_id );	?>
	</div>
	<?php
}
