<?php

namespace BestUsenetReviews\Theme;


\add_filter( 'render_block', function ( $block_content, $block ) {
	if ( 'core/navigation' === $block['blockName'] ) {
		$block_content = \sprintf(
			'<button class="%s" aria-expanded="false" aria-pressed="false">%s<span class="screen-reader-text">%s</span></button>%s',
			'menu-toggle',
			'☰',
			'Menu',
			$block_content
		);

		if ( \wp_is_mobile() ) {
			$block_content = \str_replace(
				'wp-block-navigation"',
				'wp-block-navigation hide"',
				$block_content
			);
		} else {
			$block_content = \str_replace(
				'menu-toggle',
				'menu-toggle hide',
				$block_content
			);
		}
	}

	return $block_content;
}, 10, 2 );
