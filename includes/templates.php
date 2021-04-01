<?php

namespace BestUsenetReviews\Theme;

\add_action( 'init', function () {
	$labels = [
		'name'                  => __( 'Templates', 'bestusenetreviews' ),
		'singular_name'         => __( 'Template', 'bestusenetreviews' ),
		'menu_name'             => _x( 'Templates', 'Admin Menu text', 'bestusenetreviews' ),
		'add_new'               => _x( 'Add New', 'Template', 'bestusenetreviews' ),
		'add_new_item'          => __( 'Add New Template', 'bestusenetreviews' ),
		'new_item'              => __( 'New Template', 'bestusenetreviews' ),
		'edit_item'             => __( 'Edit Template', 'bestusenetreviews' ),
		'view_item'             => __( 'View Template', 'bestusenetreviews' ),
		'all_items'             => __( 'Templates', 'bestusenetreviews' ),
		'search_items'          => __( 'Search Templates', 'bestusenetreviews' ),
		'parent_item_colon'     => __( 'Parent Template:', 'bestusenetreviews' ),
		'not_found'             => __( 'No templates found.', 'bestusenetreviews' ),
		'not_found_in_trash'    => __( 'No templates found in Trash.', 'bestusenetreviews' ),
		'archives'              => __( 'Template archives', 'bestusenetreviews' ),
		'insert_into_item'      => __( 'Insert into template', 'bestusenetreviews' ),
		'uploaded_to_this_item' => __( 'Uploaded to this template', 'bestusenetreviews' ),
		'filter_items_list'     => __( 'Filter templates list', 'bestusenetreviews' ),
		'items_list_navigation' => __( 'Templates list navigation', 'bestusenetreviews' ),
		'items_list'            => __( 'Templates list', 'bestusenetreviews' ),
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

	\register_post_type( 'template', $args );
} );


function get_all_templates() {
	static $templates = null;

	if ( \is_null( $templates ) ) {
		$templates = \wp_list_pluck( \get_posts( [
			'numberposts'      => -1,
			'post_type'        => 'template',
			'suppress_filters' => true,
		] ), 'post_name' );
	}

	return $templates;
}

/**
 * Description of expected behavior.
 *
 * @since 1.0.0
 *
 * @param string $slug
 *
 * @return bool
 */
function template_exists( $slug ) {
	$templates = get_all_templates();

	return \in_array( $slug, $templates );
}

/**
 * Description of expected behavior.
 *
 * @since 1.0.0
 *
 * @param string $slug
 *
 * @return string
 */
function get_template( $slug = 'index' ) {
	$slug = \apply_filters( 'template_slug', $slug );

	/**
	 * @var $template \WP_Post;
	 */
	$template = \get_page_by_path( $slug, OBJECT, 'template' );

	return $template ? \do_blocks( $template->post_content ) : '';
}

/**
 * Description of expected behavior.
 *
 * @since 1.0.0
 *
 * @return void
 */
function render_template() {
	?>
	<!doctype html>
	<html <?php \language_attributes(); ?> translate="no">
		<head>
			<meta charset="<?php \bloginfo( 'charset' ); ?>">
			<meta name="google" content="notranslate">
			<?php \wp_head(); ?>
		</head>
		<body <?php echo body_class(); ?>>
			<?php
			\wp_body_open();
			\wp_footer();
			?>
		</body>
	</html>
	<?php
}
