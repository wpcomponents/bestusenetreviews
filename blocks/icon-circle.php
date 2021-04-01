<?php

namespace BestUsenetReviews\Theme;

\add_action( 'acf/init', function () {
	\acf_register_block_type( [
		'name'            => 'icon-circle',
		'title'           => __( 'Icon Circle', 'bestusenetreviews' ),
		'description'     => __( 'Displays an icon circle.', 'bestusenetreviews' ),
		'render_callback' => __NAMESPACE__ . '\\render_icon_circle_block',
		'icon'            => 'star-empty',
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
function render_icon_circle_block( $block, $content = '', $is_preview = false, $post_id = 0 ) {
	$icon  = \get_field( 'icon' );
	$label = \get_field( 'label' );

	?>
	<div class="icon-circle">

		<p><?php echo \strtoupper( $label ); ?></p>
	</div>

	<?php
}
