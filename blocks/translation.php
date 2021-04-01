<?php

namespace BestUsenetReviews\Theme;

\add_action( 'acf/init', function () {
	\acf_register_block_type( [
		'name'            => 'translations',
		'title'           => __( 'Translations', 'bestusenetreviews' ),
		'description'     => __( 'Contains translations of blocks.', 'bestusenetreviews' ),
		'render_callback' => __NAMESPACE__ . '\\render_translations_block',
		'icon'            => 'translation',
		'supports'        => [
			'align'           => false,
			'anchor'          => false,
			'customClassName' => false,
			'jsx'             => true,
		],
	] );

	\acf_register_block_type( [
		'name'            => 'translation',
		'title'           => __( 'Translation', 'bestusenetreviews' ),
		'description'     => __( 'Contains single translation of blocks.', 'bestusenetreviews' ),
		'render_callback' => __NAMESPACE__ . '\\render_translation_block',
		'icon'            => 'translation',
		'supports'        => [
			'align'           => false,
			'anchor'          => false,
			'customClassName' => false,
			'jsx'             => true,
		],
	] );
} );

/**
 * Renders the translation block.
 *
 * @param array  $block      The block settings and attributes.
 * @param string $content    The block inner HTML (empty).
 * @param bool   $is_preview True during AJAX preview.
 * @param int    $post_id    The post ID this block is saved to.
 *
 * @return void
 */
function render_translations_block( $block, $content = '', $is_preview = false, $post_id = 0 ) {
	$admin          = ! \did_action( 'wp_body_open' );
	$allowed_blocks = [ 'acf/translation' ];
	$field          = \get_field_object( 'show' );
	$languages      = $field['choices'];
	$show           = \get_field( 'show' );
	$style          = '<style>';

	foreach ( $languages as $code => $name ) {
		$style .= ".$code {display:none}";
		$style .= ".show-$code .$code {display:block}";
	}

	$style .= '</style>';

	if ( $admin ) :
		echo $style;
		echo '<div class="translations-block show-' . $show . '">';
		echo '<InnerBlocks allowedBlocks="' . esc_attr( wp_json_encode( $allowed_blocks ) ) . '" />';
		echo '</div>';
	else :
		echo '<InnerBlocks allowedBlocks="' . esc_attr( wp_json_encode( $allowed_blocks ) ) . '" />';
	endif;
}

/**
 * Renders the translation block.
 *
 * @param array  $block      The block settings and attributes.
 * @param string $content    The block inner HTML (empty).
 * @param bool   $is_preview True during AJAX preview.
 * @param int    $post_id    The post ID this block is saved to.
 *
 * @return void
 */
function render_translation_block( $block, $content = '', $is_preview = false, $post_id = 0 ) {
	$admin = ! \did_action( 'wp_body_open' );
	$lang  = \get_field( 'language' );

	if ( $admin ) : ?>
		<div class="translation-block <?php echo $lang; ?>">
			<InnerBlocks/>
		</div>
	<?php else : ?>
		<InnerBlocks/>
	<?php endif;
}
