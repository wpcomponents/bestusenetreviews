<?php

namespace BestUsenetReviews\Theme;

\add_action( 'acf/init', function () {
	\acf_register_block_type( [
		'name'            => 'review-intro',
		'title'           => __( 'Review Intro', 'bestusenetreviews' ),
		'description'     => __( 'Displays a review intro.', 'bestusenetreviews' ),
		'render_callback' => __NAMESPACE__ . '\\render_review_intro_block',
		'icon'            => 'text',
	] );
} );

/**
 * Renders the review intro.
 *
 * @param array  $block      The block settings and attributes.
 * @param string $content    The block inner HTML (empty).
 * @param bool   $is_preview True during AJAX preview.
 * @param int    $post_id    The post ID this block is saved to.
 *
 * @return void
 */
function render_review_intro_block( $block, $content = '', $is_preview = false, $post_id = 0 ) {
	$admin = ! \did_action( 'wp_body_open' );
	$post  = \get_post( $post_id );
	$en_id = get_en_id( $post_id );

	if ( $admin ) : ?>
		<div class="review-intro-single">
			<div class="wp-block-columns are-vertically-aligned-center no-margin">
				<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:25%">
					<?php render_review_rating_block(
						[
							'rating' => 9.5,
							'color'  => 'var(--wp--preset--color--link)',
						],
						'',
						false,
						$en_id
					); ?>
				</div>

				<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:75%">
					<strong><?php echo get_lorem_ipsum(); ?></strong>
				</div>
			</div>

			<a href="javascript:void(0)" class="wp-block-button__link">
				<?php echo __( 'Visit Website', 'bestusenetreviews' ); ?>
			</a>
		</div>
	<?php elseif ( 'review' === $post->post_type ) : ?>
		<div class="review-intro-single">
			<div class="wp-block-columns are-vertically-aligned-center no-margin">
				<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:25%">
					<?php render_review_rating_block(
						[
							'color' => 'var(--wp--preset--color--link)',
						],
						'',
						false,
						$en_id
					); ?>
				</div>

				<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:75%">
					<strong><?php echo \get_the_excerpt( $post_id ); ?></strong>
				</div>
			</div>

			<?php \printf(
				'<a href="%s" class="button" target="_blank">%s</a>',
				\get_field( 'link', $en_id ),
				get_review_link_text()
			); ?>
		</div>
	<?php endif;
}
