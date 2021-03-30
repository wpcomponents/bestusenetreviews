<?php

namespace BestUsenetReviews\Theme;

\add_action( 'init', function () {
	\remove_action( 'wp_head', 'feed_links_extra', 3 );
	\remove_action( 'wp_head', 'feed_links', 2 );
	\remove_action( 'wp_head', 'rsd_link' );
	\remove_action( 'wp_head', 'wlwmanifest_link' );
	\remove_action( 'wp_head', 'index_rel_link' );
	\remove_action( 'wp_head', 'parent_post_rel_link' );
	\remove_action( 'wp_head', 'start_post_rel_link' );
	\remove_action( 'wp_head', 'adjacent_posts_rel_link' );
	\remove_action( 'wp_head', 'wp_generator' );
	\remove_action( 'wp_head', 'rest_output_link_wp_head' );
	\remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
	\remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	\remove_action( 'wp_head', 'wp_resource_hints', 2 );
	\remove_action( 'wp_head', 'wp_shortlink_wp_head' );
	\remove_action( 'template_redirect', 'rest_output_link_header', 11 );
	\remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	\remove_action( 'wp_print_styles', 'print_emoji_styles' );
	\remove_action( 'admin_print_styles', 'print_emoji_styles' );

	\add_action( 'wp_footer', function () {
		\wp_dequeue_script( 'wp-embed' );
	} );

	\add_action( 'widgets_init', function () {
		global $wp_widget_factory;

		\remove_action( 'wp_head', [
			$wp_widget_factory->widgets['WP_Widget_Recent_Comments'],
			'recent_comments_style',
		] );
	} );
} );
