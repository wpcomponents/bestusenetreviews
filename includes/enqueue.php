<?php

namespace BestUsenetReviews\Theme;

\add_action( 'after_setup_theme', function () {
	\add_editor_style( 'fonts.css' );
	\add_editor_style( 'style.css' );
	\add_editor_style( 'styles/utility.css' );
} );

\add_action( 'wp_enqueue_scripts', function () {
	enqueue_asset( 'normalize.css' );
	enqueue_asset( 'layout.css' );
	enqueue_asset( 'dropdown.css' );
	enqueue_asset( 'utility.css' );
	enqueue_asset( 'fonts.css' );
	enqueue_asset( '../style.css' );

	\wp_deregister_style( 'wpml-legacy-dropdown-click-0' );
} );

\add_action( 'admin_enqueue_scripts', function () {
	enqueue_asset( 'admin.css' );
} );

\add_action( 'enqueue_block_editor_assets', function () {
	enqueue_asset( 'editor.css' );
	enqueue_asset( 'dropdown.css' );
	enqueue_asset( 'editor.js' );
} );

\add_filter( 'block_editor_settings', function ( $settings ) {
	unset( $settings['styles'][1] );

	return $settings;
} );

/**
 * Description of expected behavior.
 *
 * @since 1.0.0
 *
 * @param       $asset
 * @param array $args
 *
 * @return void
 */
function enqueue_asset( $asset, $args = [] ) {
	$type = has_string( '.css', $asset ) ? 'styles' : 'scripts';

	if ( has_string( '../', $asset ) ) {
		$path   = \explode( '../', $asset );
		$src    = get_url() . $path[1];
		$ver    = \filemtime( get_dir() . $path[1] );
		$suffix = 'styles' === $type ? '.css' : '.js';
		$handle = apply_prefix( \basename( $path[1], $suffix ) );
	} else {
		$src    = get_url() . $type . DIRECTORY_SEPARATOR . $asset;
		$ver    = \filemtime( get_dir() . $type . DIRECTORY_SEPARATOR . $asset );
		$handle = apply_prefix( \strstr( $asset, '.', true ) );
	}

	$args = \wp_parse_args(
		$args,
		[
			'handle'    => $handle,
			'src'       => $src,
			'deps'      => [],
			'ver'       => $ver,
			'media'     => null,
			'in_footer' => true,
		]
	);

	if ( 'styles' === $type ) {
		unset( $args['in_footer'] );
		\wp_enqueue_style( ...\array_values( $args ) );
	} else {
		unset( $args['media'] );
		\wp_enqueue_script( ...\array_values( $args ) );
	}
}
