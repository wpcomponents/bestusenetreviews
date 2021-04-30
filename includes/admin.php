<?php

namespace BestUsenetReviews\Theme;

\remove_action( 'welcome_panel', 'wp_welcome_panel' );

\add_action( 'wp_dashboard_setup', function () {
	\remove_meta_box( 'dashboard_site_health', 'dashboard', 'normal' );
	\remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
	\remove_meta_box( 'dashboard_activity', 'dashboard', 'normal' );
	\remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
	\remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'side' );
	\remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
	\remove_meta_box( 'dashboard_secondary', 'dashboard', 'side' );
	\remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' );
	\remove_meta_box( 'dashboard_plugins', 'dashboard', 'normal' );
} );

\add_filter( 'upload_mimes', function ( $mimes ) {
	if ( \current_user_can( 'manage_options' ) ) {
		$mimes['svg'] = 'image/svg+xml';
	}

	return $mimes;
}, 10, 1 );

\add_filter( 'admin_body_class', function ( $classes ) {
	if ( ! isset( $_GET['lang'] ) || ( isset( $_GET['lang'] ) && 'en' === $_GET['lang'] ) ) {
		$classes .= ' lang-en';
	}

	return $classes;
} );

\add_filter( 'manage_review_posts_columns', function ( $post_columns ) {
	$post_columns['menu_order'] = __( 'Order', 'bestusenetreviews' );

	return $post_columns;
}, 10, 1 );

\add_action( 'manage_review_posts_custom_column', function ( $column_name ) {
	if ( 'menu_order' === $column_name ) {
		global $post;
		$views = $post->menu_order;
		echo $views;
	}
}, 10, 1 );

\add_filter( 'manage_edit-review_sortable_columns', function ( $columns ) {
	$columns['menu_order'] = 'menu_order';

	return $columns;
}, 10, 1 );
