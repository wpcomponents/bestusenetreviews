<?php

namespace BestUsenetReviews\Theme;

\add_action( 'init', function () {
	$labels = [
		'name'                  => _x( 'Popups', 'Popup General Name', 'bestusenetreviews' ),
		'singular_name'         => _x( 'Popup', 'Popup Singular Name', 'bestusenetreviews' ),
		'menu_name'             => __( 'Popups', 'bestusenetreviews' ),
		'name_admin_bar'        => __( 'Popup', 'bestusenetreviews' ),
		'archives'              => __( 'Popup Archives', 'bestusenetreviews' ),
		'attributes'            => __( 'Popup Attributes', 'bestusenetreviews' ),
		'parent_item_colon'     => __( 'Parent Popup:', 'bestusenetreviews' ),
		'all_items'             => __( 'All Popups', 'bestusenetreviews' ),
		'add_new_item'          => __( 'Add New Popup', 'bestusenetreviews' ),
		'add_new'               => __( 'Add New', 'bestusenetreviews' ),
		'new_item'              => __( 'New Popup', 'bestusenetreviews' ),
		'edit_item'             => __( 'Edit Popup', 'bestusenetreviews' ),
		'update_item'           => __( 'Update Popup', 'bestusenetreviews' ),
		'view_item'             => __( 'View Popup', 'bestusenetreviews' ),
		'view_items'            => __( 'View Popups', 'bestusenetreviews' ),
		'search_items'          => __( 'Search Popup', 'bestusenetreviews' ),
		'not_found'             => __( 'Not found', 'bestusenetreviews' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'bestusenetreviews' ),
		'featured_image'        => __( 'Featured Image', 'bestusenetreviews' ),
		'set_featured_image'    => __( 'Set featured image', 'bestusenetreviews' ),
		'remove_featured_image' => __( 'Remove featured image', 'bestusenetreviews' ),
		'use_featured_image'    => __( 'Use as featured image', 'bestusenetreviews' ),
		'insert_into_item'      => __( 'Insert into item', 'bestusenetreviews' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'bestusenetreviews' ),
		'items_list'            => __( 'Popups list', 'bestusenetreviews' ),
		'items_list_navigation' => __( 'Popups list navigation', 'bestusenetreviews' ),
		'filter_items_list'     => __( 'Filter items list', 'bestusenetreviews' ),
	];

	$args = [
		'label'               => __( 'Popup', 'bestusenetreviews' ),
		'description'         => __( 'Popup Description', 'bestusenetreviews' ),
		'labels'              => $labels,
		'supports'            => [ 'title' ],
		'taxonomies'          => [],
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 25,
		'menu_icon'           => 'dashicons-align-full-width',
		'show_in_admin_bar'   => true,
		'show_in_nav_menus'   => true,
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capability_type'     => 'page',
	];

	\register_post_type( 'popup', $args );
} );

\add_action( 'wp_footer', function () {

	$lang = get_language();
	$id   = 459;

	// Top Bar.
	if ( \have_rows( 'top_bar', $id ) ) :
		while ( \have_rows( 'top_bar', $id ) ) :
			\the_row();
			$top_bar_icon = (int) \get_sub_field( 'top_bar_icon' );
			$top_bar_text = \get_sub_field( 'top_bar_text_' . $lang );
		endwhile;
	endif;

	// Left Column.
	if ( \have_rows( 'left_column', $id ) ) :
		while ( \have_rows( 'left_column', $id ) ) :
			\the_row();
			$main_image  = (int) \get_sub_field( 'main_image' );
			$rating_text = \get_sub_field( 'rating_text_' . $lang );
			$rating_logo = (int) \get_sub_field( 'rating_logo' );
		endwhile;
	endif;

	// Right Column.
	if ( \have_rows( 'right_column', $id ) ) :
		while ( \have_rows( 'right_column', $id ) ) :
			\the_row();
			$title       = \get_sub_field( 'title_' . $lang );
			$description = \get_sub_field( 'description_' . $lang );
			$button_link = \get_sub_field( 'button_link' );
			$button_text = \get_sub_field( 'button_text_' . $lang );
			$trust_logo  = (int) \get_sub_field( 'trust_logo' );
		endwhile;
	endif;

	?>
	<div class="popup-container">
		<div class="popup-overlay"></div>
		<div class="popup">
			<div class="popup-topbar">
				<?php echo \wp_get_attachment_image(
					$top_bar_icon,
					'full',
					true,
					[
						'class'  => 'top-bar-icon',
						'alt'   => \get_the_title( $top_bar_icon ),
						'height' => 20,
						'width'  => 20,
					] );
				?>
				<p><?php echo \esc_html( $top_bar_text ); ?></p>
			</div>
			<div class="popup-columns">
				<div class="popup-column">
					<?php echo \wp_get_attachment_image(
						$main_image,
						'medium',
						true,
						[
							'class' => 'main-image',
							'alt'   => \get_the_title( $main_image ),
						]
					); ?>
					<div class="rated-by">
						<span><?php echo \esc_html( $rating_text ); ?></span>
						<?php echo \wp_get_attachment_image(
							$rating_logo,
							'full',
							true,
							[
								'class' => 'rating-logo',
								'width' => 90,
								'alt'   => \get_the_title( $rating_logo ),
							]
						); ?>
					</div>
				</div>
				<div class="popup-column">
					<h2><?php echo \strip_tags( $title, '<strong>' ); ?></h2>
					<p><?php echo \esc_html( $description ); ?></p>
					<a href="<?php echo \esc_attr( $button_link ); ?>" target="_blank" class="button popup-button">
						<?php echo \esc_html( $button_text ) ?>
					</a>
					<br>
					<?php echo \wp_get_attachment_image(
						$trust_logo,
						'full',
						true,
						[
							'class' => 'trust-logo',
							'width' => 120,
							'alt'   => \get_the_title( $trust_logo ),
						] ); ?>
				</div>
			</div>
		</div>
	</div>

	<?php
} );

\add_action( 'wp_enqueue_scripts', function () {
	enqueue_asset( 'popup.css' );
	enqueue_asset( 'popup.js' );
} );
