<?php

namespace BestUsenetReviews\Theme;

\add_action( 'after_switch_theme', function () {
	foreach ( [ 'home', 'blog', 'shop', 'about', 'contact' ] as $page ) {
		if ( ! \get_page_by_path( $page ) ) {
			\wp_insert_post( [
				'post_title'  => \ucwords( $page ),
				'post_status' => 'publish',
				'post_type'   => 'page',
			] );
		}
	}

	$home           = \get_page_by_path( 'home' );
	$blog           = \get_page_by_path( 'blog' );
	$shop           = \get_page_by_path( 'shop' );
	$hello_world    = \get_page_by_path( 'hello-world', OBJECT, 'post' );
	$sample_page    = \get_page_by_path( 'sample-page' );
	$privacy_policy = \get_page_by_path( 'privacy-policy' );

	\update_option( 'page_on_front', $home->ID );
	\update_option( 'page_for_posts', $blog->ID );
	\update_option( 'woocommerce_shop_page_id', $shop->ID );
	\update_option( 'show_on_front', 'page' );

	$hello_world ? \wp_delete_post( $hello_world->ID, true ) : null;
	$sample_page ? \wp_delete_post( $sample_page->ID, true ) : null;
	$privacy_policy ? \wp_update_post( [ 'ID' => $privacy_policy->ID, 'post_status' => 'publish' ] ) : null;

	/**
	 * @var $wp_rewrite \WP_Rewrite WP_Rewrite object.
	 */
	global $wp_rewrite;
	$wp_rewrite->set_permalink_structure( '/%postname%/' );
	$wp_rewrite->flush_rules( true );
	\update_option( 'rewrite_rules', false );
} );
