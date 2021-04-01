<?php

namespace BestUsenetReviews\Theme;

\add_action( 'init', function () {
	\register_block_style(
		'core/button',
		[
			'name'  => 'large',
			'label' => __( 'Large', 'bestusenetreviews' ),
		]
	);
} );
