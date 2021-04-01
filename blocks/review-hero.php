<?php

namespace BestUsenetReviews\Theme;

\add_action( 'acf/init', function () {
	\acf_register_block_type( [
		'name'            => 'review-hero',
		'title'           => __( 'Review Hero', 'bestusenetreviews' ),
		'description'     => __( 'Displays a review hero.', 'bestusenetreviews' ),
		'render_callback' => __NAMESPACE__ . '\\render_review_hero_block',
		'icon'            => 'align-wide',
	] );
} );

/**
 * Renders the review hero.
 *
 * @param array  $block      The block settings and attributes.
 * @param string $content    The block inner HTML (empty).
 * @param bool   $is_preview True during AJAX preview.
 * @param int    $post_id    The post ID this block is saved to.
 *
 * @return void
 */
function render_review_hero_block( $block, $content = '', $is_preview = false, $post_id = 0 ) {
	$admin = ! \did_action( 'wp_body_open' );
	$post  = \get_post( $post_id );
	$en_id = get_en_id( $post_id );
	$badge = \get_field( 'badge', $en_id );
	$logo  = \get_field( 'logo_alt', $en_id );

	if ( $admin ) : ?>
		<section class="review-hero alignfull has-light-background-color">
			<div class="review-hero-container wp-block-group__inner-container">
				<img src="https://fakeimg.pl/300x60/?text=Review+Logo&font=bebas&font_size=20">
			</div>
		</section>

	<?php else : ?>
		<section class="review-hero alignfull has-light-background-color" role="banner">
			<div class="review-hero-container wp-block-group__inner-container">
				<?php
				\the_post_thumbnail( 'full', [
					'class' => 'review-background',
					'width' => 200,
				] );
				?>
				<div class="review-logo-wrap">
					<?php
					if ( $logo ) {
						echo \wp_get_attachment_image(
							$logo,
							'full',
							false,
							[
								'width' => 400,
								'alt'   => \get_the_title() . ' ' . __( 'Site Logo', 'bestusenetreviews' ),
							]
						);
					}

					if ( $badge ) {
						echo \wp_get_attachment_image(
							$badge,
							'full',
							false,
							[
								'class' => 'review-badge-single',
								'width' => 100,
							]
						);
					}
					?>
				</div>
			</div>
		</section>
	<?php endif;
}
