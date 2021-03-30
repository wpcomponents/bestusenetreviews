<?php

namespace BestUsenetReviews\Theme;


\add_filter( 'render_block', function ( $block_content, $block ) {
	if ( 'core/separator' === $block['blockName'] ) {
		if ( isset( $block['attrs']['color'] ) ) {
			$block_content = \str_replace(
				'>',
				' style="border-color: var(--wp--preset--color--' . $block['attrs']['color'] . ')">',
				$block_content
			);
		} else {
			$block_content = \preg_replace(
				'/style="[\s\S]+?"/',
				'style="border-color:' . $block['attrs']['customColor'] . '"',
				$block_content
			);
		}
	}

	return $block_content;
}, 100, 2 );
