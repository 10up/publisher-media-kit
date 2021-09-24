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

	add_action('init', $n('register_blocks'));

	add_action('init', $n('block_patterns_and_categories'));

}

/**
 * Add in blocks that are registered in this plugin
 *
 * @return void
 */
function register_blocks()
{
	// Require custom blocks.
	require_once PUBLISHER_MEDIA_KIT_BLOCKS_PATH . '/tabs/register.php';

	// Call block register functions for each block.
	Tabs\register();

	// Require custom blocks.
	require_once PUBLISHER_MEDIA_KIT_BLOCKS_PATH . '/tabs-item/register.php';

	// Call block register functions for each block.
	TabsItem\register();

	// Remove the filter after we register the blocks
	remove_filter('plugins_url', __NAMESPACE__ . '\filter_plugins_url', 10, 2);
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
 * Manage block patterns and block pattern categories
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-patterns/
 *
 * @return void
 */
function block_patterns_and_categories()
{
	// phpcs:disable

	// Register block pattern for cover image.
	register_block_pattern(
		'publisher-media-kit/cover-pattern',
		array(
			'title'       => __( 'Publisher Media Kit Cover', 'publisher-media-kit' ),
			'description' => __( 'The main cover image for the Publisher Media Kit page', 'publisher-media-kit' ),
			'content'     => '<!-- wp:cover {"url":"/wp-content/plugins/publisher-media-kit/assets/images/cover-image.png","id":17,"align":"full","className":"pmk-cover"} --><div class="wp-block-cover alignfull has-background-dim pmk-cover"><img class="wp-block-cover__image-background wp-image-17" alt="" src="/wp-content/plugins/publisher-media-kit/assets/images/cover-image.png" data-object-fit="cover"/><div class="wp-block-cover__inner-container"><!-- wp:heading {"textAlign":"center"} --><h2 class="has-text-align-center">Media Kit</h2><!-- /wp:heading --><!-- wp:paragraph {"align":"center"} --><p class="has-text-align-center">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer dui<br>tortor, porttitor ut enim non, iaculis sagittis dolor.</p><!-- /wp:paragraph --><!-- wp:buttons {"contentJustification":"center"} --><div class="wp-block-buttons is-content-justification-center"><!-- wp:button {"className":"is-style-fill"} --><div class="wp-block-button is-style-fill"><a class="wp-block-button__link">CONTACT US</a></div><!-- /wp:button --></div><!-- /wp:buttons --></div></div><!-- /wp:cover -->',
		)
	);

	// phpcs:enable
}
