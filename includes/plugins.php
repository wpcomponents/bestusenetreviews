<?php

namespace BestUsenetReviews\Theme;

\add_action( 'tgmpa_register', function () {
	\tgmpa(
		[],
		[
			'id'           => 'tgmpa',
			'default_path' => '',
			'menu'         => 'tgmpa-install-plugins',
			'parent_slug'  => 'themes.php',
			'capability'   => 'edit_theme_options',
			'has_notices'  => true,
			'dismissable'  => true,
			'dismiss_msg'  => '',
			'is_automatic' => false,
			'message'      => '',
		]
	);
} );
