<?php

namespace BestUsenetReviews\Theme;

\add_action( 'init', function () {
	$labels = [
		'name'                  => __( 'Template Parts', 'bestusenetreviews' ),
		'singular_name'         => __( 'Template Part', 'bestusenetreviews' ),
		'menu_name'             => _x( 'Template Parts', 'Admin Menu text', 'bestusenetreviews' ),
		'add_new'               => _x( 'Add New', 'Template Part', 'bestusenetreviews' ),
		'add_new_item'          => __( 'Add New Template Part', 'bestusenetreviews' ),
		'new_item'              => __( 'New Template Part', 'bestusenetreviews' ),
		'edit_item'             => __( 'Edit Template Part', 'bestusenetreviews' ),
		'view_item'             => __( 'View Template Part', 'bestusenetreviews' ),
		'all_items'             => __( 'Template Parts', 'bestusenetreviews' ),
		'search_items'          => __( 'Search Template Parts', 'bestusenetreviews' ),
		'parent_item_colon'     => __( 'Parent Template Part:', 'bestusenetreviews' ),
		'not_found'             => __( 'No template parts found.', 'bestusenetreviews' ),
		'not_found_in_trash'    => __( 'No template parts found in Trash.', 'bestusenetreviews' ),
		'archives'              => __( 'Template part archives', 'bestusenetreviews' ),
		'insert_into_item'      => __( 'Insert into template part', 'bestusenetreviews' ),
		'uploaded_to_this_item' => __( 'Uploaded to this template part', 'bestusenetreviews' ),
		'filter_items_list'     => __( 'Filter template parts list', 'bestusenetreviews' ),
		'items_list_navigation' => __( 'Template parts list navigation', 'bestusenetreviews' ),
		'items_list'            => __( 'Template parts list', 'bestusenetreviews' ),
	];

	$args = [
		'labels'                => $labels,
		'description'           => __( 'Template parts to include in your templates.', 'bestusenetreviews' ),
		'public'                => false,
		'has_archive'           => false,
		'rewrite'               => false,
		'show_ui'               => true,
		'show_in_menu'          => 'themes.php',
		'show_in_nav_menus'     => false,
		'show_in_admin_bar'     => false,
		'show_in_rest'          => true,
		'rest_controller_class' => false,
		'can_export'            => true,
		'supports'              => [
			'title',
			'slug',
			'editor',
			'revisions',
			'custom-fields',
			'page-attributes',
		],
	];

	\register_post_type( 'template_part', $args );
} );

function get_template_parts() {
	static $template_parts = [];

	if ( empty( $template_parts ) ) {
		$query = \get_posts( [
			'numberposts'      => -1,
			'post_type'        => 'template_part',
			'post_status'      => 'publish',
			'suppress_filters' => 1,
		] );

		foreach ( $query as $template_part ) {
			if ( $template_part->ID === get_en_id( $template_part->ID ) ) {
				$template_parts[] = $template_part;
			}
		}

		$template_parts = \apply_filters( 'template_parts', $template_parts );
	}

	return $template_parts;
}

function get_template_part_names() {
	static $template_parts = null;

	if ( \is_null( $template_parts ) ) {
		$all_template_parts = get_template_parts();
		$template_parts     = \array_unique( \apply_filters(
			'template_part_names',
			\wp_list_pluck( $all_template_parts, 'post_name' )
		) );
	}

	return $template_parts;
}

function get_template_parts_order() {
	static $order = null;

	if ( \is_null( $order ) ) {
		$order = \apply_filters(
			'template_part_order',
			[
				'header'  => 'header',
				'inner'   => 'main',
				'sidebar' => 'aside',
				'footer'  => 'footer',
				'default' => 'div',
			]
		);
	}

	return $order;
}


/**
 * Description of expected behavior.
 *
 * @since 1.0.0
 *
 * @param string $slug Template part path.
 *
 * @return string
 */
function get_template_part( $slug ) {
	$id            = \get_page_by_path( $slug, OBJECT, 'template_part' )->ID;
	$id            = \wpml_object_id_filter( $id, 'template_part', true );
	$object        = \get_post( $id );
	$template_part = '';

	if ( $object ) {
		$template_part .= get_action( "before_{$slug}_template_part" );
		$template_part .= \apply_filters( 'the_content', $object->post_content );

		$template_part .= get_action( "after_{$slug}_template_part" );
	}

	return \apply_filters( "template_part_$slug", $template_part );
}

/**
 * Description of expected behavior.
 *
 * @since 1.0.0
 *
 * @param      $slug
 *
 * @return void
 */
function do_template_part( $slug ) {
	echo get_template_part( $slug );
}
