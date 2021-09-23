<?php
/**
 * Single Content Block
 *
 * @package PublisherMediaKit\Blocks\SingleContent
 *
 * @var array $args {
 *     $args is provided by get_template_call.
 *
 *     @type array $attributes Block attributes.
 *     @type array $content    Block content.
 *     @type array $block      Block instance.
 * }
 */

use PublisherMediaKit\BlockHelper;
use PublisherMediaKit\PostHelper;

// Set defaults.
$args = wp_parse_args(
	$args, // phpcs:ignore
	[
		'attributes' => [
			'curationMode' => 'manual',
			'alignment'    => 'left',
			'is_first_on_page' => false,
		],
		'class_name'       => 'wp-block-single-content',
	]
);

$block_posts = BlockHelper::get_block_posts( $args['attributes'] );

$is_edit_first_block = false;
// Check if in editor
if ( defined( 'REST_REQUEST' ) && REST_REQUEST && 1 === (int) filter_input( INPUT_GET, 'edit', FILTER_SANITIZE_NUMBER_INT ) ) {
	// Check if current page is front page
	$edited_post = (int) filter_input( INPUT_GET, 'post_id', FILTER_SANITIZE_NUMBER_INT );
	if ( $edited_post && PostHelper::is_page_front_page( $edited_post ) ) {
		$is_edit_first_block = 1 === (int) filter_input( INPUT_GET, 'isFirstBlock', FILTER_SANITIZE_NUMBER_INT );
	}
}

global $global_content_listing_items_ids_frontend;
$global_content_listing_items_ids_frontend = ! empty( $global_content_listing_items_ids_frontend ) ? $global_content_listing_items_ids_frontend : [];

if ( ! empty( $block_posts ) && is_array( $block_posts ) ) : ?>

	<section class="section section--single-block alignfull">
		<div class="container">

			<?php
			$block_post = $block_posts[0];
			$global_content_listing_items_ids_frontend[] = $block_post->ID;

			// Build partial args
			$partial_args            = PostHelper::get_args_for_articl_card_partial( $block_post->ID );
			$partial_args['style']   = 'featured';
			$partial_args['reverse'] = 'left' !== $args['attributes']['alignment'];

			global $dmg_block_counter;

			if ( is_front_page() && 1 === $dmg_block_counter ) {
				$partial_args['style'] = 'featured-home';
			}

			if ( true === $args['attributes']['is_first_on_page'] ) {
				$partial_args['style']   = 'featured-home';
				$partial_args['reverse'] = false;
			}

			if ( 'automatic' === $args['attributes']['curationMode'] ) {
				// Do not render header if first block in front page otherwise always render header
				if ( ! ( is_front_page() && 1 === $dmg_block_counter ) && ! $is_edit_first_block ) {
					$term_id = $args['attributes']['contentTag'] ?? 0;
					if ( $term_id ) {
						$block_term = get_term( $term_id );

						if ( $block_term instanceof \WP_Term ) {
							$header_title    = $block_term->name;
							$header_link_url = get_term_link( $block_term );

							$header_link_title = sprintf(
								/* translators: the category name */
								esc_html_x( 'More in %s', 'link to category', 'publisher-media-kit' ),
								$header_title
							);

							get_template_part(
								'partials/section-header',
								null,
								[
									'title'      => $header_title,
									'link_url'   => $header_link_url,
									'link_title' => $header_link_title,
								]
							);
						}
					}
				}
			}

			get_template_part( 'partials/article/article-card', '', $partial_args );
			?>

		</div>
	</section>

<?php endif ?>
