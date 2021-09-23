<?php
/**
 * Gutenberg Blocks setup
 *
 * @package PublisherMediaKit\Blocks\SingleContent
 */

namespace PublisherMediaKit\Blocks\SingleContent;

const BLOCK_NAME = 'publisher-media-kit/single-content-block';

/**
 * Register the block
 */
function register() {
	$n = function( $function ) {
		return __NAMESPACE__ . "\\$function";
	};
	// Register the block.
	register_block_type_from_metadata(
		PUBLISHER_MEDIA_KIT_BLOCKS_PATH . '/single-content-block', // this is the directory where the block.json is found.
		[
			'render_callback' => $n( 'render_block_callback' ),
		]
	);
}

/**
 * Render callback method for the block
 *
 * @param array  $attributes The blocks attributes
 * @param string $content    Data returned from InnerBlocks.Content
 * @param array  $block      Block information such as context.
 *
 * @return string The rendered block markup.
 */
function render_block_callback( $attributes, $content, $block ) {




	return "testing 003";







	ob_start();
	get_template_part(
		'includes/blocks/single-content-block/markup',
		null,
		[
			'attributes' => $attributes,
			'content'    => $content,
			'block'      => $block,
		]
	);

	return ob_get_clean();
}
