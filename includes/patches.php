<?php

namespace BestUsenetReviews\Theme;

add_filter( 'block_type_metadata_settings', function ( $settings, $metadata ) {
	if ( isset( $settings['render_callback'] ) && 'gutenberg_render_block_core_navigation_link' === $settings['render_callback'] ) {
		$settings['render_callback'] = __NAMESPACE__ . '\\gutenberg_render_block_core_navigation_link';
	}

	if ( isset( $settings['render_callback'] ) && 'gutenberg_render_block_core_site_logo' === $settings['render_callback'] ) {
		$settings['render_callback'] = __NAMESPACE__ . '\\gutenberg_render_block_core_site_logo';
	}

	return $settings;
}, 10, 2 );

/**
 * Workaround: Renders the `core/navigation-link` block.
 *
 * @TODO: Remove when Gutenberg fixes issue. Poorly coded.
 *
 * @param array     $attributes The block attributes.
 * @param array     $content    The saved content.
 * @param \WP_Block $block      The parsed block.
 *
 * @return string Returns the post content with the legacy widget added.
 */
function gutenberg_render_block_core_navigation_link( $attributes, $content, $block ) {
	$navigation_link_has_id = isset( $attributes['id'] ) && is_numeric( $attributes['id'] );
	$is_post_type           = isset( $attributes['kind'] ) && 'post-type' === $attributes['kind'];
	$is_post_type           = $is_post_type || isset( $attributes['type'] ) && ( 'post' === $attributes['type'] || 'page' === $attributes['type'] );

	if ( $is_post_type && $navigation_link_has_id ) {
		$post = get_post( $attributes['id'] );
		if ( $post && 'publish' !== $post->post_status ) {
			return '';
		}
	}

	if ( empty( $attributes['label'] ) ) {
		return '';
	}

	$colors          = \gutenberg_block_core_navigation_link_build_css_colors( $block->context );
	$font_sizes      = \gutenberg_block_core_navigation_link_build_css_font_sizes( $block->context );
	$classes         = \array_merge(
		$colors['css_classes'],
		$font_sizes['css_classes']
	);
	$style_attribute = ( $colors['inline_styles'] . $font_sizes['inline_styles'] );

	$css_classes = \trim( \implode( ' ', $classes ) );
	$has_submenu = \count( $block->inner_blocks ) > 0;
	$is_active   = ! empty( $attributes['id'] ) && ( \get_the_ID() === $attributes['id'] );

	$class_name = ! empty( $attributes['className'] ) ? \implode( ' ', (array) $attributes['className'] ) : false;

	if ( false !== $class_name ) {
		$css_classes .= ' ' . $class_name;
	};

	$wrapper_attributes = \get_block_wrapper_attributes(
		[
			'class' => $css_classes . ( $has_submenu ? ' has-child' : '' ) .
			           ( $is_active ? ' current-menu-item' : '' ),
			'style' => $style_attribute,
		]
	);

	$html = '<li ' . $wrapper_attributes . '><a class="wp-block-navigation-link__content" ';

	if ( isset( $attributes['url'] ) ) {
		$html .= ' href="' . esc_url( $attributes['url'] ) . '"';
	}

	if ( isset( $attributes['opensInNewTab'] ) && true === $attributes['opensInNewTab'] ) {
		$html .= ' target="_blank"  ';
	}

	if ( isset( $attributes['rel'] ) ) {
		$html .= ' rel="' . \esc_attr( $attributes['rel'] ) . '"';
	} elseif ( isset( $attributes['nofollow'] ) && $attributes['nofollow'] ) {
		$html .= ' rel="nofollow"';
	}

	if ( isset( $attributes['title'] ) ) {
		$html .= ' title="' . \esc_attr( $attributes['title'] ) . '"';
	}

	$html .= '><span class="wp-block-navigation-link__label">';

	if ( isset( $attributes['label'] ) ) {
		$html .= \wp_kses(
			$attributes['label'],
			[
				'code'   => [],
				'em'     => [],
				'img'    => [
					'scale' => [],
					'class' => [],
					'style' => [],
					'src'   => [],
					'alt'   => [],
				],
				's'      => [],
				'span'   => [
					'style' => [],
				],
				'strong' => [],
			]
		);
	}

	$html .= '</span></a>';

	if ( $block->context['showSubmenuIcon'] && $has_submenu ) {
		$html .= '<span class="wp-block-navigation-link__submenu-icon">' . \gutenberg_block_core_navigation_link_render_submenu_icon() . '</span>';
	}

	if ( $has_submenu ) {
		$inner_blocks_html = '';
		foreach ( $block->inner_blocks as $inner_block ) {
			$inner_blocks_html .= $inner_block->render();
		}

		$html .= \sprintf(
			'<ul class="wp-block-navigation-link__container">%s</ul>',
			$inner_blocks_html
		);
	}

	$html .= '</li>';

	return $html;
}

/**
 * Renders the `core/site-logo` block on the server.
 *
 * @param array $attributes The block attributes.
 *
 * @return string
 */
function gutenberg_render_block_core_site_logo( $attributes ) {
	$adjust_width_height_filter = function ( $image ) use ( $attributes ) {
		$image[1] = $attributes['width'];

		return $image;
	};

	\add_filter( 'wp_get_attachment_image_src', $adjust_width_height_filter );

	$custom_logo = \get_custom_logo();
	$classnames  = [];

	if ( ! empty( $attributes['className'] ) ) {
		$classnames[] = $attributes['className'];
	}

	if ( ! empty( $attributes['align'] ) && \in_array( $attributes['align'], [ 'center', 'left', 'right' ], true ) ) {
		$classnames[] = "align{$attributes['align']}";
	}

	$wrapper_attributes = \get_block_wrapper_attributes( [ 'class' => implode( ' ', $classnames ) ] );
	$html               = \sprintf( '<div %s>%s</div>', $wrapper_attributes, $custom_logo );

	\remove_filter( 'wp_get_attachment_image_src', $adjust_width_height_filter );

	return $html;
}
