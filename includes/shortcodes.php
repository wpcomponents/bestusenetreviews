<?php

namespace BestUsenetReviews\Theme;

\add_shortcode( 'year', function () {
	return \date( 'Y' );
} );

\add_shortcode( 'discount', function () {
	return \get_field( 'discount' );
} );

\add_shortcode( 'link_url', function () {
	return \get_field( 'link' );
} );

\add_shortcode( 'link_text', function () {
	return get_review_link_text();
} );

