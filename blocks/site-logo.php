<?php

namespace BestUsenetReviews\Theme;

\add_action( 'acf/init', function () {
	\acf_register_block_type( [
		'name'            => 'site-logo',
		'title'           => __( 'Site Logo', 'bestusenetreviews' ),
		'description'     => __( 'Displays the site logo.', 'bestusenetreviews' ),
		'render_callback' => __NAMESPACE__ . '\\render_site_logo_block',
		'icon'            => 'flag',
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
function render_site_logo_block( $block, $content = '', $is_preview = false, $post_id = 0 ) {
	$url   = \is_admin() ? 'javascript:void(0)' : \home_url();
	$image = \get_field( 'image' );
	$width = \get_field( 'width' );
	?>
	<div class="wp-block-site-logo">
		<a href="<?php echo $url; ?>" class="custom-logo-link" rel="home" aria-current="page">
			<?php
			echo \wp_get_attachment_image(
				$image,
				'full',
				false,
				[
					'class' => 'custom-logo',
					'width' => $width,
				]
			);
			?>
		</a>
	</div>
	<?php
}
