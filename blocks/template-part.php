<?php

namespace BestUsenetReviews\Theme;

\add_action( 'acf/init', function () {
	\acf_register_block_type( [
		'name'            => 'template-part',
		'title'           => __( 'Template Part', 'bestusenetreviews' ),
		'description'     => __( 'Displays the template part.', 'bestusenetreviews' ),
		'render_callback' => __NAMESPACE__ . '\\render_template_part_block',
		'icon'            => 'block-default',
		'align'           => 'full',
	] );
} );

/**
 * Renders the template part.
 *
 * @param array  $block      The block settings and attributes.
 * @param string $content    The block inner HTML (empty).
 * @param bool   $is_preview True during AJAX preview.
 * @param int    $post_id    The post ID this block is saved to.
 *
 * @return void
 */
function render_template_part_block( $block, $content = '', $is_preview = false, $post_id = 0 ) {
	/**
	 * @var $template_part \WP_Post;
	 */
	$template_part = \get_field( 'template_part' );

	if ( isset( $template_part->post_name ) ) {
		do_template_part( $template_part->post_name );
	} else {
		__( 'Please select a template part →', 'bestusenetreviews' );
	}
}

\add_filter( 'render_block', function ( $block_content, $block ) {
	if ( 'core/template-part' !== $block['blockName'] ) {
		return $block_content;
	}

	$tags = [
		'header'  => 'header',
		'footer'  => 'footer',
		'inner'   => 'main',
		'sidebar' => 'aside',
	];
	$slug = $block['attrs']['slug'];

	if ( ! isset( $tags[ $slug ] ) ) {
		return $block_content;
	}

	$dom   = get_dom_document( $block_content );
	$xpath = new \DOMXPath( $dom );
	$nodes = $xpath->query( '//div[contains(@class, "wp-block-template-part")]' );

	/**
	 * @var \DOMElement $node
	 */
	foreach ( $nodes as $node ) {

		/**
		 * @var $new_node \DOMElement
		 */
		$new_node = $node->ownerDocument->createElement( $tags[ $slug ] );

		foreach ( $node->childNodes as $child ) {
			$new_node->appendChild( $node->ownerDocument->importNode( $child, true ) );
		}

		foreach ( $node->attributes as $name => $attr ) {
			$new_node->setAttribute( $attr->nodeName, $attr->nodeValue );
		}

		$new_node->setAttribute( 'id', $slug );
		$new_node->setAttribute( 'class', "wp-site-{$slug}" );

		$hook_open  = get_action( "{$slug}_template_part_open" );
		$hook_close = get_action( "{$slug}_template_part_close" );

		if ( $hook_open ) {
			$hook_dom  = get_dom_document( $hook_open );
			$hook_node = $dom->importNode( $hook_dom->documentElement, true );
			$new_node->insertBefore( $hook_node, $new_node->firstChild );
		}

		if ( $hook_close ) {
			$hook_dom  = get_dom_document( $hook_open );
			$hook_node = $dom->importNode( $hook_dom->documentElement, true );
			$new_node->lastChild->appendChild( $hook_node );
		}

		$node->parentNode->replaceChild( $new_node, $node );
	}

	$block_content = \str_replace(
		'alignfull wp-block-template-part"',
		"wp-site-$slug\" id=\"$slug\"",
		$dom->saveHTML()
	);

	$before = get_action( "before_{$slug}_template_part" );
	$after  = get_action( "after_{$slug}_template_part" );

	if ( $before ) {
		$block_content = $before . $block_content;
	}

	if ( $after ) {
		$block_content .= $after;
	}

	return $block_content;
}, 100, 2 );

\add_filter( 'render_block', function ( $block_content, $block ) {
	if ( isset( $block['attrs']['slug'] ) && 'header' === $block['attrs']['slug'] ) {
		$block_content = unautop( $block_content );
	}

	return $block_content;
}, 10, 2 );
