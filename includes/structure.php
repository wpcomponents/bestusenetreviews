<?php

namespace BestUsenetReviews\Theme;

\add_action( 'init', function () {
	$template_parts = get_template_parts();

	/**
	 * @var $template_part \WP_Post
	 */
	foreach ( $template_parts as $template_part ) {
		$template_part->attr  = '';
		$template_part->class = \strtok( $template_part->post_name, '-' );
		$template_part->tag   = \get_field( 'tag', $template_part->ID );
		$attributes           = \get_field( 'attr', $template_part->ID );

		if ( \is_array( $attributes ) ) {
			foreach ( $attributes as $attribute ) {
				$template_part->attr .= "{$attribute['name']}='{$attribute['value']}'";
			}
		}

		\add_action( "before_{$template_part->post_name}_template_part", function () use ( $template_part ) {
			\printf(
				'<%s class="wp-site-%s" id="%s" %s>',
				$template_part->tag,
				$template_part->class,
				$template_part->class,
				$template_part->attr
			);
		} );

		\add_action( "after_{$template_part->post_name}_template_part", function () use ( $template_part ) {
			\printf(
				'</%1$s>',
				$template_part->tag
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

	$classes = \array_flip( $classes );

	$template_parts = get_template_part_names();

	if ( \in_array( 'sidebar', $template_parts ) ) {
		$classes[] = 'has-sidebar';
	}

	return $classes;
} );

\add_action( 'wp_body_open', function () {
	$template_part_names = get_template_part_names();
	$tags                = get_template_parts_order();
	$skip_links          = '<ul class="skip-links">';

	foreach ( $tags as $tag => $value ) {
		if ( ! \in_array( $tag, $template_part_names ) ) {
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

\add_action( 'wp_body_open', function () {
	?>
	<div class="wp-site-blocks">
		<?php echo get_template(); ?>
	</div>
	<?php
} );

\add_filter( 'template_slug', function ( $slug ) {
	global $post;

	if ( \is_front_page() && template_exists( 'front-page' ) ) {
		return 'front-page';
	}

	if ( \is_singular() && template_exists( "single-{$post->post_type}" ) ) {
		return "single-{$post->post_type}";
	}

	return 'index';
} );
