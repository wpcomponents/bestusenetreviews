<?php

namespace BestUsenetReviews\Theme;

\add_action( 'acf/init', function () {
	\acf_register_block_type( [
		'name'            => 'review-list',
		'title'           => __( 'Review List', 'bestusenetreviews' ),
		'description'     => __( 'Displays a list of reviews.', 'bestusenetreviews' ),
		'render_callback' => __NAMESPACE__ . '\\render_review_list_block',
		'icon'            => 'excerpt-view',
	] );
} );

/**
 * Displays a list of reviews.
 *
 * @param array  $block      The block settings and attributes.
 * @param string $content    The block inner HTML (empty).
 * @param bool   $is_preview True during AJAX preview.
 * @param int    $post_id    The post ID this block is saved to.
 *
 * @return void
 */
function render_review_list_block( $block, $content = '', $is_preview = false, $post_id = 0 ) {
	$layout = \get_field( 'layout' ) ? \get_field( 'layout' ) : 'default';
	$number = \get_field( 'number' ) ? \get_field( 'number' ) : 0;

	$reviews = new \WP_Query( [
		'post_type'      => 'review',
		'post_status'    => 'publish',
		'order'          => 'ASC',
		'orderby'        => 'menu_order',
		'posts_per_page' => $number,
	] );

	if ( ! $reviews->have_posts() ) {
		return;
	}

	$count = 1;
	?>
	<ul class="reviews reviews-<?php echo $layout; ?>">
		<?php while ( $reviews->have_posts() ) :
			$reviews->the_post();
			global $post;
			$en_id          = get_en_id( $post->ID );
			$featured       = 1 === $count++;
			$features       = [];
			$feature_fields = [
				'servers',
				'connections',
				'speed',
				'retention',
				'trial',
				'plans',
				'vpn',
			];

			foreach ( $feature_fields as $feature_field ) {
				$feature_value = \get_field( $feature_field, $post->ID );

				if ( $feature_value ) {
					$features[ $feature_field ] = $feature_value;
				}
			}

			?>
			<li class="review review-<?php echo $layout . ( $featured ? ' featured' : '' ); ?>">
				<?php
				if ( 'category' === $layout ) {
					render_category_review_layout( $post, $featured, $en_id );
				} elseif ( 'comparison' === $layout ) {
					render_comparison_review_layout( $post, $featured, $en_id );
				} else {
					render_default_review_layout( $post, $featured, $en_id );
				}
				?>
			</li>
		<?php endwhile; ?>
	</ul>
	<?php

	\wp_reset_postdata();
}

/**
 * Description of expected behavior.
 *
 * @since 1.0.0
 *
 * @param $post
 * @param $featured
 *
 * @return void
 */
function render_default_review_layout( $post, $featured, $en_id ) {
	$badge   = \get_field( 'badge', $en_id );
	$reviews = \get_field( 'reviews', $en_id );
	$blocks  = \parse_blocks( $post->post_content );
	$list    = '';

	foreach ( $blocks as $block ) {
		if ( ! isset( $block['blockName'] ) || 'core/list' !== $block['blockName'] ) {
			continue;
		}

		if ( ! isset( $block['innerHTML'] ) || ! $block['innerHTML'] || $list ) {
			continue;
		}

		$list = $block['innerHTML'];
	}
	if ( $badge ) : ?>
		<div class="review-badge">
			<?php
			echo \wp_get_attachment_image(
				$badge,
				'full',
				false,
				[
					'width' => 120,
				]
			);
			?>
		</div>
	<?php endif; ?>
	<div class="review-order">#<?php echo $post->menu_order; ?></div>
	<div class="review-image">
		<?php \the_post_thumbnail( '', [
			'class' => 'review-logo',
			'width' => 160,
		] ); ?>
		<small><?php echo $reviews . ' ' . __( 'Reviews', 'bestusenetreviews' ); ?></small>
	</div>
	<div class="review-content">
		<?php \the_excerpt(); ?>
		<?php if ( $list ) : ?>
			<?php echo $list; ?>
		<?php endif; ?>

		<div class="review-cta">
			<?php render_review_rating_block(
				[
					'color' => $featured ? 'var(--wp--preset--color--link)' : 'var(--wp--preset--color--gray)',
				],
				'',
				false,
				$post->ID
			);
			?>
			<?php
			\printf(
				'<a href="%s" class="button%s" target="_blank">%s</a>',
				\get_field( 'link', $en_id ),
				$featured ? '' : ' button-secondary',
				get_review_link_text()
			);
			\printf(
				'<a href="%s" class="review-more-link">%s %s</a>',
				\get_permalink(),
				__( 'Learn More About', 'bestusenetreviews' ),
				\get_the_title()
			);
			?>
		</div>
	</div>
	<?php
}

/**
 * Description of expected behavior.
 *
 * @since 1.0.0
 *
 * @param $post
 * @param $featured
 *
 * @return void
 */
function render_category_review_layout( $post, $featured, $en_id ) {
	if ( $featured ) {
		$features = [
			'trial' => __( 'Free Trial', 'bestusenetreviews' ),
			'vpn'   => __( 'VPN Included', 'bestusenetreviews' ),
			'speed' => __( 'Unlimited Speeds', 'bestusenetreviews' ) . ' ' . __( 'Up to', 'bestusenetreviews' ) . ' 1 ' . __( 'Gbps', 'bestusenetreviews' ),
		];
	} else {
		$features = [
			'retention'   => __( 'Days of Retention', 'bestusenetreviews' ),
			'connections' => __( 'Connections', 'bestusenetreviews' ),
			'security'    => __( '256-Bit SSL Security', 'bestusenetreviews' ),
		];
	}

	?>
	<?php
	echo \wp_get_attachment_image(
		\get_field( 'logo_alt', $en_id ),
		'full',
		false,
		[
			'width' => 100,
		]
	);
	?>
	<hr>
	<ul class="review-features">
		<?php foreach ( $features as $name => $label ) : ?>
			<?php $value = \get_field( $name, $en_id ); ?>
			<li class="review-feature">
				<span class="icon-feature icon-<?php echo $name; ?>"><?php echo \file_get_contents( get_dir() . "svg/feature-{$name}.svg" ) ?></span>
				<p>
					<?php
					if ( \in_array( $name, [ 'trial', 'vpn', 'speed', 'security' ] ) ) {
						echo $label;
					} else {
						echo "$value $label";
					}
					?>
				</p>
			</li>
		<?php endforeach; ?>
	</ul>
	<?php \printf(
		'<a href="%s" class="button%s" target="_blank">%s</a>',
		\get_field( 'link', $en_id ),
		$featured ? '' : ' button-secondary',
		__( 'Visit Website', 'bestusenetreviews' )
	); ?>
	<?php
}

/**
 * Description of expected behavior.
 *
 * @since 1.0.0
 *
 * @param \WP_Post $post
 * @param bool     $featured
 * @param int      $en_id
 *
 * @return void
 */
function render_comparison_review_layout( $post, $featured, $en_id ) {
	$features = [
		'trial'       => __( 'Free Trial', 'bestusenetreviews' ),
		'security'    => __( '256-Bit SSL Security', 'bestusenetreviews' ),
		'connections' => __( 'Connections', 'bestusenetreviews' ),
		'speed'       => __( 'Unlimited Speeds', 'bestusenetreviews' ),
		'plans'       => __( 'Annual Plans', 'bestusenetreviews' ),
	];

	?>
	<div class="review-comparison-content">
		<?php
		echo \wp_get_attachment_image(
			\get_field( 'logo_alt', $en_id ),
			'full',
			false,
			[
				'class' => 'review-comparison-logo',
				'width' => 200,
			]
		);

		$testimonial = \get_field( 'testimonial', $post->ID ) ? \get_field( 'testimonial', $post->ID ) : $post->post_excerpt;
		?>
		<p><?php echo $testimonial; ?></p>
	</div>

	<div class="review-comparison-items">
		<?php foreach ( $features as $name => $label ) : ?>
			<div class="review-comparison-item">
				<?php
				$icon  = \file_get_contents( get_dir() . "svg/gray-{$name}.svg" );
				$field = \get_field( $name, $en_id );
				$value = \is_bool( $field ) && $field ? __( 'Yes', 'bestusenetreviews' ) : $field;
				?>
				<span class="icon-gray icon-<?php echo $name; ?>"><?php echo $icon; ?></span>
				<div class="review-comparison-wrap">
					<p><?php echo $label ?></p>
					<p><?php echo $value; ?></p>
				</div>
			</div>
		<?php endforeach; ?>
	</div>

	<table class="review-comparison-table">
		<tr>
			<?php foreach ( $features as $name => $label ) : ?>
				<th>
				<span class="icon-gray icon-<?php echo $name; ?>">
					<?php echo \file_get_contents( get_dir() . "svg/gray-{$name}.svg" ) ?>
				</span>
				</th>
			<?php endforeach; ?>
		</tr>
		<tr>
			<?php foreach ( $features as $name => $label ) : ?>
				<td>
					<p><?php echo $label ?></p>
				</td>
			<?php endforeach; ?>
		</tr>
		<tr>
			<?php foreach ( $features as $name => $label ) : ?>
				<td>
					<?php
					$field = \get_field( $name, $en_id );
					$value = \is_bool( $field ) && $field ? __( 'Yes', 'bestusenetreviews' ) : $field;
					$value = ( 'speed' === $name ) ? __( 'Up to', 'bestusenetreviews' ) . " $value " . __( 'Gbps', 'bestusenetreviews' ) : $value;
					?>
					<p><?php echo $value; ?></p>
				</td>
			<?php endforeach; ?>
		</tr>
	</table>
	<div class="review-comparison-cta">
		<?php \printf(
			'<a href="%s" class="button%s" target="_blank">%s</a>',
			\get_field( 'link', $en_id ),
			$featured ? '' : ' button-secondary',
			__( 'Visit Website', 'bestusenetreviews' )
		); ?>
	</div>
	<?php
}
