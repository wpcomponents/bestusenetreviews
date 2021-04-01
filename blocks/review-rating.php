<?php

namespace BestUsenetReviews\Theme;

\add_action( 'acf/init', function () {
	\acf_register_block_type( [
		'name'            => 'review-rating',
		'title'           => __( 'Review Rating', 'bestusenetreviews' ),
		'description'     => __( 'Displays a review rating.', 'bestusenetreviews' ),
		'render_callback' => __NAMESPACE__ . '\\render_review_rating_block',
		'icon'            => 'marker',
	] );
} );

/**
 * Renders the review rating.
 *
 * @param array  $block      The block settings and attributes.
 * @param string $content    The block inner HTML (empty).
 * @param bool   $is_preview True during AJAX preview.
 * @param int    $post_id    The post ID this block is saved to.
 */
function render_review_rating_block( $block, $content = '', $is_preview = false, $post_id = 0 ) {
	$en_id      = get_en_id( $post_id );
	$rating     = isset( $block['rating'] ) ? $block['rating'] : (float) \get_field( 'rating', $en_id );
	$color      = isset( $block['color'] ) ? $block['color'] : 'var(--wp--preset--color--link)';
	$size       = 130;
	$stroke     = $size / 20;
	$radius     = ( $size / 2 ) - ( $stroke / 2 );
	$percentage = $rating * 10;
	$total      = \round( $radius * ( \pi() * 2 ) );
	$offset     = \round( $total - ( $percentage / 100 ) * $total );
	$rotate     = \round( $percentage * 3.6 );

	?>
	<div class="review-rating" style="--rotate:rotate(<?php echo $rotate; ?>deg);--total:<?php echo $total; ?>;--offset:<?php echo $offset; ?>;--stroke-width:<?php echo $stroke; ?>px;--stroke-color:<?php echo $color; ?>;width:<?php echo $size; ?>px;height:<?php echo $size; ?>px;">
		<svg width="<?php echo $size; ?>" height="<?php echo $size; ?>" class="review-circle">
			<circle cx="<?php echo $size / 2; ?>" cy="<?php echo $size / 2; ?>" r="<?php echo $radius; ?>" stroke="<?php echo $color; ?>" stroke-width="<?php echo $stroke; ?>" fill="transparent"></circle>
		</svg>
		<div class="review-dot-container" style="width:<?php echo $size; ?>px;height:<?php echo $size; ?>px;">
			<span class="review-dot"></span>
		</div>

		<p class="review-score"><?php echo $rating; ?>/10</p>
		<div class="review-stars">
			<div class="review-stars-empty">&#x2605;&#x2605;&#x2605;&#x2605;&#x2605;</div>
			<div class="review-stars-color" style="width:<?php echo $percentage; ?>%">&#x2605;&#x2605;&#x2605;&#x2605;&#x2605;</div>
		</div>
	</div>
	<?php
}
