<?php

namespace BestUsenetReviews\Theme;

\add_action( 'init', function () {
	$labels = [
		'name'                  => _x( 'Reviews', 'Review General Name', 'bestusenetreviews' ),
		'singular_name'         => _x( 'Review', 'Review Singular Name', 'bestusenetreviews' ),
		'menu_name'             => __( 'Reviews', 'bestusenetreviews' ),
		'name_admin_bar'        => __( 'Review', 'bestusenetreviews' ),
		'archives'              => __( 'Review Archives', 'bestusenetreviews' ),
		'attributes'            => __( 'Review Attributes', 'bestusenetreviews' ),
		'parent_item_colon'     => __( 'Parent Review:', 'bestusenetreviews' ),
		'all_items'             => __( 'All Reviews', 'bestusenetreviews' ),
		'add_new_item'          => __( 'Add New Review', 'bestusenetreviews' ),
		'add_new'               => __( 'Add New', 'bestusenetreviews' ),
		'new_item'              => __( 'New Review', 'bestusenetreviews' ),
		'edit_item'             => __( 'Edit Review', 'bestusenetreviews' ),
		'update_item'           => __( 'Update Review', 'bestusenetreviews' ),
		'view_item'             => __( 'View Review', 'bestusenetreviews' ),
		'view_items'            => __( 'View Reviews', 'bestusenetreviews' ),
		'search_items'          => __( 'Search Review', 'bestusenetreviews' ),
		'not_found'             => __( 'Not found', 'bestusenetreviews' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'bestusenetreviews' ),
		'featured_image'        => __( 'Featured Image', 'bestusenetreviews' ),
		'set_featured_image'    => __( 'Set featured image', 'bestusenetreviews' ),
		'remove_featured_image' => __( 'Remove featured image', 'bestusenetreviews' ),
		'use_featured_image'    => __( 'Use as featured image', 'bestusenetreviews' ),
		'insert_into_item'      => __( 'Insert into review', 'bestusenetreviews' ),
		'uploaded_to_this_item' => __( 'Uploaded to this review', 'bestusenetreviews' ),
		'items_list'            => __( 'Reviews list', 'bestusenetreviews' ),
		'items_list_navigation' => __( 'Reviews list navigation', 'bestusenetreviews' ),
		'filter_items_list'     => __( 'Filter reviews list', 'bestusenetreviews' ),
	];

	$args = [
		'label'               => __( 'Review', 'bestusenetreviews' ),
		'description'         => __( 'Review Description', 'bestusenetreviews' ),
		'labels'              => $labels,
		'supports'            => [ 'title', 'excerpt', 'editor', 'thumbnail' ],
		'taxonomies'          => [],
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 20,
		'menu_icon'           => 'dashicons-star-half',
		'show_in_admin_bar'   => true,
		'show_in_nav_menus'   => true,
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capability_type'     => 'page',
		'show_in_rest'        => true,
	];

	\register_post_type( 'review', $args );
} );

\add_action( 'before_inner_template_part', function () {
	if ( ! \is_singular( 'review' ) ) {
		return;
	}

	global $post;
	?>
	<div class="review-intro-single">
		<div class="wp-block-columns are-vertically-aligned-center no-margin">
			<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:25%">
				<?php render_review_rating_block(
					[
						'color' => 'var(--wp--preset--color--link)',
					],
					'',
					false,
					$post->ID
				); ?>
			</div>

			<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:75%">
				<strong><?php echo $post->post_excerpt; ?></strong>
			</div>
		</div>

		<?php \printf(
			'<a href="%s" class="button" target="_blank">%s</a>',
			\get_field( 'link', $post->ID ),
			get_review_link_text()
		); ?>
	</div>
	<?php
}, 15 );

\add_action( 'after_inner_template_part', function () {
	if ( ! \is_singular( 'review' ) ) {
		return;
	}

	global $post;

	?>
	<div class="review-cta-single">
		<?php \printf(
			'<a href="%s" class="button button-large" target="_blank">%s</a>',
			\get_field( 'link', $post->ID ),
			get_review_link_text()
		); ?>
	</div>
	<?php

} );

/**
 * Description of expected behavior.
 *
 * @since 1.0.0
 *
 * @return string
 */
function get_review_link_text() {
	return __( 'Visit ', 'bestusenetreviews' ) . \get_the_title() . __( ' Now', 'bestusenetreviews' );
}

\add_filter( 'post_type_link', function ( $post_link, $post ) {
	if ( 'review' === $post->post_type && 'publish' === $post->post_status ) {
		$post_link = \str_replace(
			'/' . $post->post_type . '/',
			'/',
			$post_link
		);
	}

	return $post_link;
}, 10, 2 );

\add_action( 'pre_get_posts', function ( $query ) {
	/**
	 * @var $query \WP_Query
	 */
	if ( $query->is_main_query() && 2 === \count( $query->query ) && isset( $query->query['page'] ) && isset( $query->query['name'] ) ) {
		$query->set( 'post_type', [ 'post', 'review', 'page' ] );
	}
}, 10, 1 );
