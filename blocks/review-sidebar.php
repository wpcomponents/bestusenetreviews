<?php

namespace BestUsenetReviews\Theme;

\add_action( 'acf/init', function () {
	\acf_register_block_type( [
		'name'            => 'review-sidebar',
		'title'           => __( 'Review Sidebar', 'bestusenetreviews' ),
		'description'     => __( 'Displays a reviews in sidebar.', 'bestusenetreviews' ),
		'render_callback' => __NAMESPACE__ . '\\render_review_sidebar_block',
		'icon'            => 'excerpt-view',
	] );
} );

/**
 * Displays a sidebar of reviews.
 *
 * @param array  $block      The block settings and attributes.
 * @param string $content    The block inner HTML (empty).
 * @param bool   $is_preview True during AJAX preview.
 * @param int    $post_id    The post ID this block is saved to.
 *
 * @return void
 */
function render_review_sidebar_block( $block, $content = '', $is_preview = false, $post_id = 0 ) {
	$reviews = new \WP_Query( [
		'post_type'      => 'review',
		'post_status'    => 'publish',
		'order'          => 'ASC',
		'orderby'        => 'menu_order',
		'posts_per_page' => 3,
	] );

	if ( ! $reviews->have_posts() ) {
		return;
	}

	$count = 1;
	?>
	<ul class="reviews-sidebar">
		<?php while ( $reviews->have_posts() ) :
			$reviews->the_post();
			global $post;
			$en_id = \wpml_object_id_filter( $post->ID, 'review', true, 'en' );
			?>
			<li class="review-sidebar">
				<?php \the_post_thumbnail( '', [
					'class' => 'review-sidebar-logo',
					'width' => 80,
				] ); ?>
				<div class="review-sidebar-wrap">
					<strong><?php echo $count++ . '. ' . $post->post_title; ?></strong>
					<?php \printf(
						'<a href="%s" target="_blank">%s%s</a>',
						\get_field( 'link', $en_id ),
						__( 'Visit ', 'bestusenetreviews' ),
						$post->post_title
					); ?>
					<small><?php echo \get_field( 'discount', $en_id ) . __( '% Discount Now', 'bestusenetreviews' ); ?></small>
				</div>
			</li>
		<?php endwhile; ?>
	</ul>
	<?php

	\wp_reset_postdata();
}

