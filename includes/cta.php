<?php

namespace BestUsenetReviews\Theme;

/**
 * Description of expected behavior.
 *
 * @since 1.0.0
 *
 * @param int    $post  Post ID.
 * @param string $class Additional classes.
 * @param string $text  Custom text.
 *
 * @return string
 */
function get_cta_button( $post, $class = '', $text = '' ) {
	$en_id = get_en_id( $post );
	$class = $class ? 'button ' . $class : 'button';
	$link  = \get_field( 'link', $en_id );
	$text  = $text ? $text : \get_field( 'link_text', $post );

	if ( ! $text ) {
		$text = \get_field( 'link_text', $en_id );
	}

	if ( ! $text ) {
		$text = __( 'Visit ', 'bestusenetreviews' ) . \get_the_title( $post ) . __( ' Now', 'bestusenetreviews' );
	}

	return \sprintf(
		'<a href="%s" class="%s" target="_blank">%s</a>',
		\esc_url( $link ),
		\esc_attr( $class ),
		\esc_html( $text )
	);
}
