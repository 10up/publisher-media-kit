<?php
/**
 * Gutenberg Blocks setup
 *
 * @package PublisherMediaKit/Blocks
 */

namespace PublisherMediaKit\Blocks;

/**
 * Set up blocks
 *
 * @return void
 */
function setup()
{
	$n = function ($function) {
		return __NAMESPACE__ . "\\$function";
	};

	add_filter('block_categories', $n('blocks_categories'), 10, 2);

	add_action('init', $n('register_blocks'));

	add_action('init', $n('block_patterns_and_categories'));

	//add_filter('allowed_block_types', $n('allowed_block_types'), 5, 2);
}

/**
 * Add in blocks that are registered in this plugin
 *
 * @return void
 */
function register_blocks()
{
	// Require custom blocks.
	require_once PUBLISHER_MEDIA_KIT_BLOCKS_PATH . '/single-content-block/register.php';

	// Call block register functions for each block.
	SingleContent\register();

	// Remove the filter after we register the blocks
	//remove_filter('plugins_url', __NAMESPACE__ . '\filter_plugins_url', 10, 2);
}

/**
 * Filter the plugins_url to allow us to use assets from plugin.
 *
 * @param string $url The plugins url
 * @param string $path The path to the asset.
 *
 * @return string The overridden url to the block asset.
 */
function filter_plugins_url($url, $path)
{
	$file = preg_replace('/\.\.\//', '', $path);
	return trailingslashit(get_stylesheet_directory_uri()) . $file;
}

/**
 * Filters the registered block categories.
 *
 * @param array $categories Registered categories.
 * @param object $post The post object.
 *
 * @return array Filtered categories.
 */
function blocks_categories($categories, $post)
{
	if (!in_array($post->post_type, array('post', 'page'), true)) {
		return $categories;
	}

	return array_merge(
		$categories,
		array(
			array(
				'slug' => 'tenup-scaffold-blocks',
				'title' => __('Custom Blocks', 'publisher-media-kit'),
			),
		)
	);
}

/**
 * Manage block patterns and block pattern categories
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-patterns/
 *
 * @return void
 */
function block_patterns_and_categories()
{
	// phpcs:disable
	## Examples

	// Register block pattern
	register_block_pattern(
		'tenup/block-pattern',
		array(
			'title'       => __( 'Two buttons', 'tenup' ),
			'description' => _x( 'Two horizontal buttons, the left button is filled in, and the right button is outlined.', 'Block pattern description', 'wpdocs-my-plugin' ),
			'content'     => "<!-- wp:buttons {\"align\":\"center\"} -->\n<div class=\"wp-block-buttons aligncenter\"><!-- wp:button {\"backgroundColor\":\"very-dark-gray\",\"borderRadius\":0} -->\n<div class=\"wp-block-button\"><a class=\"wp-block-button__link has-background has-very-dark-gray-background-color no-border-radius\">" . esc_html__( 'Button One', 'wpdocs-my-plugin' ) . "</a></div>\n<!-- /wp:button -->\n\n<!-- wp:button {\"textColor\":\"very-dark-gray\",\"borderRadius\":0,\"className\":\"is-style-outline\"} -->\n<div class=\"wp-block-button is-style-outline\"><a class=\"wp-block-button__link has-text-color has-very-dark-gray-color no-border-radius\">" . esc_html__( 'Button Two', 'wpdocs-my-plugin' ) . "</a></div>\n<!-- /wp:button --></div>\n<!-- /wp:buttons -->",
		)
	);

	// Unregister a block pattern
	unregister_block_pattern( 'tenup/block-pattern' );

	// Register a block pattern category
	register_block_pattern_category(
		'client-name',
			array( 'label' => __( 'Client Name', 'tenup' ) )
	);

	// Unregister a block pattern category
	unregister_block_pattern('client-name');

	// phpcs:enable
}

/**
 * Allowed block types
 *
 * @param array $allowed_blocks Allowed block
 * @param \WP_Post $post Post object
 *
 * @return array
 */
function allowed_block_types($allowed_blocks, \WP_Post $post)
{
	$allowed_blocks = [
		// Core Blocks.
		'core/block',
		'core/audio',
		'core/buttons',
		'core/button',
		'core/columns',
		'core/column',
		'core/embed',
		'core/file',
		'core/freeform',
		'core/gallery',
		'core/group',
		'core/heading',
		'core/html',
		'core/image',
		'core/list',
		'core/media-text',
		'core/paragraph',
		'core/pullquote',
		'core/quote',
		'core/separator',
		'core/shortcode',
		'core/social-link',
		'core/social-links',
		'core/spacer',
		'core/table',
		'core/template',
		'core/video',
	];

	if ('page' === $post->post_type) {
		$allowed_blocks[] = SingleContent\BLOCK_NAME;
		$allowed_blocks[] = ContentListing\BLOCK_NAME;
	}

	return $allowed_blocks;
}
