<?php

namespace BestUsenetReviews\Theme;

\add_action( 'init', function () {
	$template_parts = \array_unique( \wp_list_pluck( \get_posts( [
		'numberposts' => -1,
		'post_type'   => 'template_part',
	] ), 'post_name' ) );

	$tags = \apply_filters( 'template_part_tags', [
		'header'  => 'header',
		'inner'   => 'main',
		'sidebar' => 'aside',
		'footer'  => 'footer',
		'default' => 'div',
	] );

	\add_action( 'wp_body_open', function () use ( $template_parts, $tags ) {
		$skip_links = '<ul class="skip-links">';

		foreach ( $tags as $tag => $value ) {
			if ( ! \in_array( $tag, $template_parts ) ) {
				continue;
			}

			$skip_links .= \sprintf(
				'<li class="skip-link"><a href="#%s" class="screen-reader-text">%s</a></li>',
				$tag,
				__( 'Skip to ', 'starter' ) . $tag
			);
		}

		echo $skip_links . '</ul>';
	} );

	foreach ( $template_parts as $index => $name ) {
		$tag = isset( $tags[ $name ] ) ? $tags[ $name ] : $tags['default'];

		\add_action( "before_{$name}_template_part", function () use ( $name, $tag ) {
			\printf(
				'<%1$s class="wp-site-%2$s" id="%2$s">',
				$tag,
				$name
			);
		} );

		\add_action( "after_{$name}_template_part", function () use ( $tag ) {
			\printf(
				'</%1$s>',
				$tag
			);
		} );
	}
} );

\add_action( 'wp_head', function () {
	echo '<meta name="viewport" content="width=device-width, initial-scale=1">';
} );

\add_filter( 'body_class', function ( $classes ) {
	$classes = \array_flip( $classes );

	unset( $classes['blog'] );

	return \array_flip( $classes );
} );

\add_action( 'wp_body_open', function () {
	\printf( '<div class="wp-site-blocks">%s</div>', get_template() );
} );

\add_filter( 'template_slug', function ( $slug ) {
	global $post;

	if ( \is_singular() && template_exists( "single-{$post->post_type}" ) ) {
		$slug = "single-{$post->post_type}";
	}

	return $slug;
} );
