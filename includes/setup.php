<?php

namespace BestUsenetReviews\Theme;

\add_action( 'after_setup_theme', function () {
	\load_theme_textdomain(
		get_text_domain(),
		get_dir() . get_domain_path()
	);

	\add_theme_support( 'automatic-feed-links' );
	\add_theme_support( 'align-wide' );
	\add_theme_support( 'responsive-embeds' );
	\add_theme_support( 'custom-units' );
	\add_theme_support( 'experimental-link-color' );
	\add_theme_support( 'experimental-custom-spacing' );
	\add_theme_support( 'widgets-block-editor' );
	\add_theme_support( 'block-nav-menus' );
	\add_theme_support( 'editor-styles' );
	\add_theme_support( 'title-tag' );
	\add_theme_support( 'post-thumbnails' );
	\add_theme_support( 'editor-color-palette', [
		[
			'name'  => 'Black',
			'slug'  => 'black',
			'color' => '#000000',
		],
		[
			'name'  => 'Dark',
			'slug'  => 'dark',
			'color' => '#302e2c',
		],
		[
			'name'  => 'Heading',
			'slug'  => 'heading',
			'color' => '#3c444f',
		],
		[
			'name'  => 'Body',
			'slug'  => 'body',
			'color' => '#565656',
		],
		[
			'name'  => 'Medium',
			'slug'  => 'medium',
			'color' => '#737373',
		],
		[
			'name'  => 'Gray',
			'slug'  => 'gray',
			'color' => '#a3a3a3',
		],
		[
			'name'  => 'Shadow',
			'slug'  => 'shadow',
			'color' => 'rgba(159,159,159,0.3)',
		],
		[
			'name'  => 'Light',
			'slug'  => 'light',
			'color' => '#f1f2f2',
		],
		[
			'name'  => 'White',
			'slug'  => 'white',
			'color' => '#ffffff',
		],
		[
			'name'  => 'Link',
			'slug'  => 'link',
			'color' => '#da9857',
		],
		[
			'name'  => 'Secondary',
			'slug'  => 'secondary',
			'color' => '#b27742',
		],
		[
			'name'  => 'Overlay',
			'slug'  => 'overlay',
			'color' => '#ffefe0',
		],
		[
			'name'  => 'Opaque',
			'slug'  => 'opaque',
			'color' => '#fff8f1',
		],
		[
			'name'  => 'Success',
			'slug'  => 'success',
			'color' => '#57bb81',
		],
	] );
	\add_theme_support(
		'editor-gradient-presets',
		[
			[
				'name'     => 'Primary',
				'gradient' => 'linear-gradient(180deg,#da9857 0%,#a87039 100%)',
				'slug'     => 'primary',
			],
			[
				'name'     => 'Secondary',
				'gradient' => 'linear-gradient(180deg,#c7c7c7 0%,#aeaeae 100%)',
				'slug'     => 'secondary',
			],
		]
	);
} );

\add_action( 'after_setup_theme', function () {
	if ( ! \function_exists( 'deactivate_plugins' ) ) {
		require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	}

	$acf_pro    = '/advanced-custom-fields-pro/acf.php';

	\add_filter( 'acf/settings/url', function () use ( $acf_pro ) {
		return get_url() . 'vendor/advanced-custom-fields/advanced-custom-fields-pro/';
	}, 10, 1 );

	require_once get_dir() . "vendor/advanced-custom-fields{$acf_pro}";
} );
