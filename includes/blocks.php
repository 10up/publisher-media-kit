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

	// Register block pattern category for Publisher Media Kit.
	register_block_pattern_category(
		'publisher-media-kit',
		array( 'label' => __( 'Publisher Media Kit', 'publisher-media-kit' ) )
	);

	// Register block pattern for cover image.
	register_block_pattern(
		'publisher-media-kit/cover-pattern',
		array(
			'title'       => __( 'Publisher Media Kit - Cover', 'publisher-media-kit' ),
			'description' => __( 'The main cover image for the Publisher Media Kit page.', 'publisher-media-kit' ),
			'categories'  => [ 'publisher-media-kit' ],
			'content'     => '<!-- wp:cover {"url":"/wp-content/plugins/publisher-media-kit/assets/images/cover-image.png","id":17,"align":"full","className":"pmk-cover"} --><div class="wp-block-cover alignfull has-background-dim pmk-cover"><img class="wp-block-cover__image-background wp-image-17" alt="" src="/wp-content/plugins/publisher-media-kit/assets/images/cover-image.png" data-object-fit="cover"/><div class="wp-block-cover__inner-container"><!-- wp:heading {"textAlign":"center"} --><h2 class="has-text-align-center">Media Kit</h2><!-- /wp:heading --><!-- wp:paragraph {"align":"center"} --><p class="has-text-align-center">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer dui<br>tortor, porttitor ut enim non, iaculis sagittis dolor.</p><!-- /wp:paragraph --><!-- wp:buttons {"contentJustification":"center"} --><div class="wp-block-buttons is-content-justification-center"><!-- wp:button {"className":"is-style-fill pmk-button"} --><div class="wp-block-button is-style-fill"><a class="wp-block-button__link">CONTACT US</a></div><!-- /wp:button --></div><!-- /wp:buttons --></div></div><!-- /wp:cover -->',
		)
	);

	// Register block pattern for audience profiles.
	register_block_pattern(
		'publisher-media-kit/audience-profiles',
		array(
			'title'       => __( 'Publisher Media Kit - Audience Profiles', 'publisher-media-kit' ),
			'description' => __( 'The 2 column layout showing the audience profiles.', 'publisher-media-kit' ),
			'categories'  => [ 'publisher-media-kit' ],
			'content'     => '<!-- wp:group {"className":"pmk-audience-profiles"} --><div class="wp-block-group pmk-audience-profiles"><!-- wp:heading {"textAlign":"center","level":2} --><h2 class="has-text-align-center">Audience Profiles</h2><!-- /wp:heading --><!-- wp:columns --><div class="wp-block-columns"><!-- wp:column --><div class="wp-block-column"><!-- wp:image {"align":"center","width":60,"height":137} --><div id="block-ad47cc21-f741-4805-a4d7-85e15762d9d9" class="wp-block-image"><figure class="aligncenter is-resized"><img src="/wp-content/plugins/publisher-media-kit/assets/images/icon-woman.png" alt="Female visitors" width="60" height="137"/></figure></div><!-- /wp:image --><!-- wp:paragraph {"align":"center"} --><p class="has-text-align-center" id="block-80c8c75b-6f07-4ca5-a041-fe48703c3e56">The average female visitor is<br><strong>38 years old</strong></p><!-- /wp:paragraph --></div><!-- /wp:column --><!-- wp:column --><div class="wp-block-column"><!-- wp:image {"align":"center","width":60,"height":137} --><div id="block-7db2072f-dbec-47c8-afdb-8e3c78404377" class="wp-block-image"><figure class="aligncenter is-resized"><img src="/wp-content/plugins/publisher-media-kit/assets/images/icon-man.png" alt="Male visitors" width="60" height="137"/></figure></div><!-- /wp:image --><!-- wp:paragraph {"align":"center"} --><p class="has-text-align-center" id="block-5faaca4c-38a7-4e2f-aee3-a7312df7cd8f">The average male visitor is<br><strong>52 years old</strong></p><!-- /wp:paragraph --></div><!-- /wp:column --></div><!-- /wp:columns --></div><!-- /wp:group -->',
		)
	);

	// Register block pattern for 5 column statics block.
	register_block_pattern(
		'publisher-media-kit/five-column-statics',
		array(
			'title'       => __( 'Publisher Media Kit - 5 Column Statics', 'publisher-media-kit' ),
			'description' => __( 'The 5 column layout showing the statics.', 'publisher-media-kit' ),
			'categories'  => [ 'publisher-media-kit' ],
			'content'     => '<!-- wp:group {"align":"full","className":"pmk-stats"} --><div class="wp-block-group alignfull pmk-stats"><!-- wp:columns --><div class="wp-block-columns"><!-- wp:column {"className":"pmk-stats-column"} --><div class="wp-block-column pmk-stats-column"><!-- wp:heading {"textAlign":"center","level":3} --><h3 class="has-text-align-center">2.5M</h3><!-- /wp:heading --><!-- wp:paragraph {"align":"center"} --><p class="has-text-align-center">Monthly Unique Visitors on WSVN.com</p><!-- /wp:paragraph --></div><!-- /wp:column --><!-- wp:column {"className":"pmk-stats-column"} --><div class="wp-block-column pmk-stats-column"><!-- wp:heading {"textAlign":"center","level":3} --><h3 class="has-text-align-center">7M</h3><!-- /wp:heading --><!-- wp:paragraph {"align":"center"} --><p class="has-text-align-center">Monthly Page Views on WSVN.com</p><!-- /wp:paragraph --></div><!-- /wp:column --><!-- wp:column {"className":"pmk-stats-column"} --><div class="wp-block-column pmk-stats-column"><!-- wp:heading {"textAlign":"center","level":3} --><h3 class="has-text-align-center">646K</h3><!-- /wp:heading --><!-- wp:paragraph {"align":"center"} --><p class="has-text-align-center">Facebook Followers</p><!-- /wp:paragraph --></div><!-- /wp:column --><!-- wp:column {"className":"pmk-stats-column"} --><div class="wp-block-column pmk-stats-column"><!-- wp:heading {"textAlign":"center","level":3} --><h3 class="has-text-align-center">389K</h3><!-- /wp:heading --><!-- wp:paragraph {"align":"center"} --><p class="has-text-align-center">Twitter Followers</p><!-- /wp:paragraph --></div><!-- /wp:column --><!-- wp:column {"className":"pmk-stats-column"} --><div class="wp-block-column pmk-stats-column"><!-- wp:heading {"textAlign":"center","level":3} --><h3 class="has-text-align-center">72K</h3><!-- /wp:heading --><!-- wp:paragraph {"align":"center"} --><p class="has-text-align-center">Instagram Followers</p><!-- /wp:paragraph --></div><!-- /wp:column --></div><!-- /wp:columns --></div><!-- /wp:group -->',
		)
	);

	// Register block pattern for 3 column why choose digital.
	register_block_pattern(
		'publisher-media-kit/why-choose-digital',
		array(
			'title'       => __( 'Publisher Media Kit - Why Choose Digital', 'publisher-media-kit' ),
			'description' => __( 'The 3 column layout for why choose digital block.', 'publisher-media-kit' ),
			'categories'  => [ 'publisher-media-kit' ],
			'content'     => '<!-- wp:group {"className":"pmk-why-digital"} --><div class="wp-block-group pmk-why-digital"><!-- wp:heading {"level":2} --><h2 class="has-text-align-center">Why you should choose digital</h2><!-- /wp:heading --><!-- wp:columns --><div class="wp-block-columns"><!-- wp:column {"className":"pmk-digital-column pmk-digital-column-source"} --><div class="wp-block-column pmk-digital-column pmk-digital-column-source"><!-- wp:heading {"level":3} --><h3>Source</h3><!-- /wp:heading --><!-- wp:paragraph --><p>WSVN is the leading news source in Miami. Making it South Florida’s GO-TO choice to stay informed on anything news, weather, sports &amp; entertainment.</p><!-- /wp:paragraph --></div><!-- /wp:column --><!-- wp:column {"className":"pmk-digital-column pmk-digital-column-visibility"} --><div class="wp-block-column pmk-digital-column pmk-digital-column-visibility"><!-- wp:heading {"level":3} --><h3>Visibility</h3><!-- /wp:heading --><!-- wp:paragraph --><p>All ad units across the site have a viewability percentage of 70% of higher, making “above-the-fold” a thing of the past!</p><!-- /wp:paragraph --></div><!-- /wp:column --><!-- wp:column {"className":"pmk-digital-column pmk-digital-column-measure"} --><div class="wp-block-column pmk-digital-column pmk-digital-column-measure"><!-- wp:heading {"level":3} --><h3>Measure</h3><!-- /wp:heading --><!-- wp:paragraph --><p>WSVN has Measurable ROI! Ads on WSVN.com have high click-through percentages against industry benchmark rates.</p><!-- /wp:paragraph --></div><!-- /wp:column --></div><!-- /wp:columns --><!-- wp:buttons {"contentJustification":"center"} --><div class="wp-block-buttons is-content-justification-center"><!-- wp:button {"className":"is-style-fill pmk-button"} --><div class="wp-block-button is-style-fill pmk-button"><a class="wp-block-button__link">CONTACT US</a></div><!-- /wp:button --></div><!-- /wp:buttons --></div><!-- /wp:group -->',
		)
	);

	// Register block pattern for tabs with table structure for Ad specs.
	register_block_pattern(
		'publisher-media-kit/tabs-table-ad-specs',
		array(
			'title'       => __( 'Publisher Media Kit - Ad Specs', 'publisher-media-kit' ),
			'description' => __( 'Ad Specs tabular structure with tabs management.', 'publisher-media-kit' ),
			'categories'  => [ 'publisher-media-kit' ],
			'content'     => '<!-- wp:group {"className":"pmk-tabs-table"} --><div class="wp-block-group pmk-tabs-table"><!-- wp:tenup/tabs {"tabsTitle":"Digital Ad Specs"} --><!-- wp:tenup/tabs-item {"header":"Standard Display"} --><!-- wp:table --><figure class="wp-block-table"><table><thead><tr><th><em>Ad Type</em></th><th><em>Dimensions</em></th><th><em>File/Creative Type</em></th><th><em>Device</em></th></tr></thead><tbody><tr><td>Billboard</td><td>970x250</td><td>GIF/JPEG - 250KB | HTML 5 - 500KB</td><td>Desktop</td></tr><tr><td>Super Leaderboard</td><td>970x90</td><td>GIF/JPEG - 250KB | HTML 5 - 500KB</td><td><meta charset="utf-8">Desktop</td></tr><tr><td>Leaderboard</td><td>728x90</td><td><meta charset="utf-8">GIF/JPEG - 250KB | HTML 5 - 500KB</td><td><meta charset="utf-8">Desktop, Tablet</td></tr></tbody></table></figure><!-- /wp:table --><!-- /wp:tenup/tabs-item --><!-- wp:tenup/tabs-item {"header":"Video"} --><!-- wp:table --><figure class="wp-block-table"><table><thead><tr><th><em>Ad Type</em></th><th><em>Dimensions</em></th><th><em>File/Creative Type</em></th><th><em>Device</em></th></tr></thead><tbody><tr><td>Medium Rectangle</td><td>300x250</td><td>GIF/JPEG - 250KB | HTML 5 - 500KB</td><td>Desktop, Tablet, Mobile</td></tr><tr><td>Half Page</td><td>300x600</td><td>GIF/JPEG - 250KB | HTML 5 - 500KB</td><td>Desktop</td></tr></tbody></table></figure><!-- /wp:table --><!-- /wp:tenup/tabs-item --><!-- wp:tenup/tabs-item {"header":"Native"} --><!-- wp:table --><figure class="wp-block-table"><table><thead><tr><th><em>Ad Type</em></th><th><em>Dimensions</em></th><th><em>File/Creative Type</em></th><th><em>Device</em></th></tr></thead><tbody><tr><td>Small Mobile Banner</td><td>320x50</td><td>GIF/JPEG - 250KB | HTML 5 - 500KB</td><td>Mobile</td></tr><tr><td>Large Mobile Banner</td><td>320x100</td><td>GIF/JPEG - 250KB | HTML 5 - 500KB</td><td>Mobile</td></tr></tbody></table></figure><!-- /wp:table --><!-- /wp:tenup/tabs-item --><!-- /wp:tenup/tabs --></div><!-- /wp:group -->',
		)
	);

	// Register block pattern for our packages section.
	register_block_pattern(
		'publisher-media-kit/our-packages',
		array(
			'title'       => __( 'Publisher Media Kit - Our Packages', 'publisher-media-kit' ),
			'description' => __( 'The our packages layout with a short note and 3 column blocks.', 'publisher-media-kit' ),
			'categories'  => [ 'publisher-media-kit' ],
			'content'     => '<!-- wp:group {"className":"pmk-packages"} --><div class="wp-block-group pmk-packages"><!-- wp:heading {"textAlign":"center","level":3} --><h3 class="has-text-align-center">Our Packages</h3><!-- /wp:heading --><!-- wp:paragraph {"align":"center"} --><p class="has-text-align-center">This is body copy that explains what packages are and why you would choose one over a regular single ad placement. This should span no more than two to three lines.</p><!-- /wp:paragraph --><!-- wp:columns {"align":"full","className":"pmk-packages-columns"} --><div class="wp-block-columns alignfull pmk-packages-columns"><!-- wp:column {"className":"pmk-packages-column"} --><div class="wp-block-column pmk-packages-column"><!-- wp:heading {"textAlign":"center","level":4} --><h4 class="has-text-align-center">Package 1</h4><!-- /wp:heading --><!-- wp:paragraph {"align":"center"} --><p class="has-text-align-center">You will air on WSVN Digital Elements including: live streaming, pre roll on site &amp; banners on site.</p><!-- /wp:paragraph --><!-- wp:list --><ul><li><strong>60,0000</strong> Guaranteed impressions</li><li><strong>50x</strong> Guaranteed live streaming</li></ul><!-- /wp:list --><!-- wp:separator {"align":"wide"} --><hr class="wp-block-separator alignwide"/><!-- /wp:separator --><!-- wp:paragraph {"align":"center","className":"pmk-big-font"} --><p class="has-text-align-center pmk-big-font">Total Cost: $750K</p><!-- /wp:paragraph --></div><!-- /wp:column --><!-- wp:column {"className":"pmk-packages-column"} --><div class="wp-block-column pmk-packages-column"><!-- wp:heading {"textAlign":"center","level":4} --><h4 class="has-text-align-center" id="block-6f23f229-474a-4422-96fe-ef60fae3c350">Package 1</h4><!-- /wp:heading --><!-- wp:paragraph {"align":"center"} --><p class="has-text-align-center" id="block-50be0f32-d950-4b74-8a77-d99dbea0a1e3">You will air on WSVN Digital Elements including: live streaming, pre roll on site &amp; banners on site.</p><!-- /wp:paragraph --><!-- wp:list --><ul id="block-e9083141-ff73-44ae-b807-8443fe34758b"><li><strong>130,0000</strong> Guaranteed impressions</li><li><strong>150x</strong> Guaranteed live streaming</li></ul><!-- /wp:list --><!-- wp:separator {"align":"wide"} --><hr class="wp-block-separator alignwide" id="block-ece0e9ef-f132-45f9-80db-88609c39244e"/><!-- /wp:separator --><!-- wp:paragraph {"align":"center","className":"pmk-big-font"} --><p class="has-text-align-center pmk-big-font" id="block-3689e47a-af48-4a67-b39d-1ad0e7ea9542">Total Cost: $750K</p><!-- /wp:paragraph --></div><!-- /wp:column --><!-- wp:column {"className":"pmk-packages-column"} --><div class="wp-block-column pmk-packages-column"><!-- wp:heading {"textAlign":"center","level":4} --><h4 class="has-text-align-center" id="block-6f23f229-474a-4422-96fe-ef60fae3c350">Package 1</h4><!-- /wp:heading --><!-- wp:paragraph --><p id="block-50be0f32-d950-4b74-8a77-d99dbea0a1e3">You will air on WSVN Digital Elements including: live streaming, pre roll on site &amp; banners on site.</p><!-- /wp:paragraph --><!-- wp:list --><ul id="block-e9083141-ff73-44ae-b807-8443fe34758b"><li><strong>220,0000</strong> Guaranteed impressions</li><li>2<strong>50x</strong> Guaranteed live streaming</li></ul><!-- /wp:list --><!-- wp:separator {"align":"wide"} --><hr class="wp-block-separator alignwide" id="block-ece0e9ef-f132-45f9-80db-88609c39244e"/><!-- /wp:separator --><!-- wp:paragraph {"align":"center","className":"pmk-big-font"} --><p class="has-text-align-center pmk-big-font" id="block-3689e47a-af48-4a67-b39d-1ad0e7ea9542">Total Cost: $750K</p><!-- /wp:paragraph --></div><!-- /wp:column --></div><!-- /wp:columns --></div><!-- /wp:group -->',
		)
	);

	// Register block pattern for tabs with table structure for Our rates.
	register_block_pattern(
		'publisher-media-kit/tabs-table-our-rates',
		array(
			'title'       => __( 'Publisher Media Kit - Our Rates', 'publisher-media-kit' ),
			'description' => __( 'Our Rates tabular structure with tabs management.', 'publisher-media-kit' ),
			'categories'  => [ 'publisher-media-kit' ],
			'content'     => '<!-- wp:group {"align":"full","className":"pmk-tabs-table pmk-our-rates"} --><div class="wp-block-group alignfull pmk-tabs-table pmk-our-rates"><!-- wp:tenup/tabs {"tabsTitle":"Our Rates"} --><!-- wp:tenup/tabs-item {"header":"Standard"} --><!-- wp:table --><figure class="wp-block-table"><table><thead><tr><th><em>Ad Type</em></th><th><em>Dimensions</em></th><th><em>Cost</em></th></tr></thead><tbody><tr><td>Standard Display</td><td>25,000</td><td>$10 CPM</td></tr><tr><td>Pre-roll</td><td>20,000</td><td>$25 CPM</td></tr><tr><td>Live Streaming</td><td>N/A</td><td><meta charset="utf-8">$10/a spot</td></tr></tbody></table></figure><!-- /wp:table --><!-- /wp:tenup/tabs-item --><!-- wp:tenup/tabs-item {"header":"Sponshorship"} --><!-- wp:table --><figure class="wp-block-table"><table><thead><tr><th><em>Ad Type</em></th><th><em>Dimensions</em></th><th><em>Cost</em></th></tr></thead><tbody><tr><td>Sponsored Content</td><td>N/A</td><td>$1,000</td></tr><tr><td>Sponsored Content Series (3 posts)</td><td>N/A</td><td>$25,000</td></tr></tbody></table></figure><!-- /wp:table --><!-- /wp:tenup/tabs-item --><!-- wp:tenup/tabs-item {"header":"App Mid-roll"} --><!-- wp:table --><figure class="wp-block-table"><table><thead><tr><th><em>Ad Type</em></th><th><em>Dimensions</em></th><th><em>Cost</em></th></tr></thead><tbody><tr><td>Countdown Cloc</td><td>N/A</td><td>$1,500 per week</td></tr><tr><td>Online Contest</td><td>N/A</td><td>$25000 per week</td></tr></tbody></table></figure><!-- /wp:table --><!-- /wp:tenup/tabs-item --><!-- /wp:tenup/tabs --></div><!-- /wp:group -->',
		)
	);

	// Register block pattern for still questions contact us block.
	register_block_pattern(
		'publisher-media-kit/still-questions',
		array(
			'title'       => __( 'Publisher Media Kit - Still Questions', 'publisher-media-kit' ),
			'description' => __( 'The block having a contact button if user has still questions.', 'publisher-media-kit' ),
			'categories'  => [ 'publisher-media-kit' ],
			'content'     => '<!-- wp:group {"align":"full","className":"pmk-question-block"} --><div class="wp-block-group alignfull pmk-question-block"><!-- wp:columns --><div class="wp-block-columns"><!-- wp:column {"width":"66.66%","className":"pmk-question-left"} --><div class="wp-block-column pmk-question-left" style="flex-basis:66.66%"><!-- wp:heading {"level":3} --><h3><meta charset="utf-8">Still have questions? We can help.</h3><!-- /wp:heading --></div><!-- /wp:column --><!-- wp:column {"width":"33.33%","className":"pmk-question-right"} --><div class="wp-block-column pmk-question-right" style="flex-basis:33.33%"><!-- wp:buttons {"contentJustification":"center"} --><div class="wp-block-buttons is-content-justification-center"><!-- wp:button {"className":"is-style-fill pmk-button"} --><div class="wp-block-button is-style-fill"><a class="wp-block-button__link">CONTACT US</a></div><!-- /wp:button --></div><!-- /wp:buttons --></div><!-- /wp:column --></div><!-- /wp:columns --></div><!-- /wp:group -->',
		)
	);

	// phpcs:enable
}
