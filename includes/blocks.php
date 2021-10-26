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
			'content'     => '<!-- wp:cover {"url":"/wp-content/plugins/publisher-media-kit/assets/images/cover-image.png","id":17,"align":"full","className":"pmk-cover"} --><div class="wp-block-cover alignfull has-background-dim pmk-cover"><img class="wp-block-cover__image-background wp-image-17" alt="" src="/wp-content/plugins/publisher-media-kit/assets/images/cover-image.png" data-object-fit="cover"/><div class="wp-block-cover__inner-container"><!-- wp:heading {"level": 1,"textAlign":"center"} --><h1 class="has-text-align-center">Media Kit</h1><!-- /wp:heading --><!-- wp:paragraph {"align":"center"} --><p class="has-text-align-center">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer dui<br>tortor, porttitor ut enim non, iaculis sagittis dolor.</p><!-- /wp:paragraph --><!-- wp:buttons {"contentJustification":"center"} --><div class="wp-block-buttons is-content-justification-center"><!-- wp:button {"className":"is-style-fill pmk-button"} --><div class="wp-block-button pmk-button is-style-fill"><a class="wp-block-button__link">CONTACT US</a></div><!-- /wp:button --></div><!-- /wp:buttons --></div></div><!-- /wp:cover -->',
		)
	);

	// Register block pattern for audience profiles.
	register_block_pattern(
		'publisher-media-kit/audience-profiles',
		array(
			'title'       => __( 'Publisher Media Kit - Audience Profiles', 'publisher-media-kit' ),
			'description' => __( 'The 2 column layout showing the audience profiles.', 'publisher-media-kit' ),
			'categories'  => [ 'publisher-media-kit' ],
			'content'     => '<!-- wp:group {"align":"wide","className":"pmk-audience-profiles"} --><div class="wp-block-group alignwide pmk-audience-profiles"><!-- wp:heading {"textAlign":"center","level":2} --><h2 class="has-text-align-center">Audience Profiles</h2><!-- /wp:heading --><!-- wp:columns {"align":"wide"} --><div class="wp-block-columns alignwide"><!-- wp:column --><div class="wp-block-column"><!-- wp:image {"align":"center","width":60,"height":137} --><div id="block-ad47cc21-f741-4805-a4d7-85e15762d9d9" class="wp-block-image"><figure class="aligncenter is-resized"><img src="/wp-content/plugins/publisher-media-kit/assets/images/icon-woman.png" alt="Female visitors" width="60" height="137"/></figure></div><!-- /wp:image --><!-- wp:paragraph {"align":"center"} --><p class="has-text-align-center" id="block-80c8c75b-6f07-4ca5-a041-fe48703c3e56">The average female visitor is<br><strong>38 years old</strong></p><!-- /wp:paragraph --></div><!-- /wp:column --><!-- wp:column --><div class="wp-block-column"><!-- wp:image {"align":"center","width":60,"height":137} --><div id="block-7db2072f-dbec-47c8-afdb-8e3c78404377" class="wp-block-image"><figure class="aligncenter is-resized"><img src="/wp-content/plugins/publisher-media-kit/assets/images/icon-man.png" alt="Male visitors" width="60" height="137"/></figure></div><!-- /wp:image --><!-- wp:paragraph {"align":"center"} --><p class="has-text-align-center" id="block-5faaca4c-38a7-4e2f-aee3-a7312df7cd8f">The average male visitor is<br><strong>52 years old</strong></p><!-- /wp:paragraph --></div><!-- /wp:column --></div><!-- /wp:columns --></div><!-- /wp:group -->',
		)
	);

	// Register block pattern for 5 column statics block.
	register_block_pattern(
		'publisher-media-kit/five-column-statics',
		array(
			'title'       => __( 'Publisher Media Kit - 5 Column Statics', 'publisher-media-kit' ),
			'description' => __( 'The 5 column layout showing the statics.', 'publisher-media-kit' ),
			'categories'  => [ 'publisher-media-kit' ],
			'content'     => '<!-- wp:group {"align":"wide","className":"pmk-stats"} --><div class="wp-block-group alignwide pmk-stats"><!-- wp:columns {"align":"wide"} -->
			<div class="wp-block-columns alignwide"><!-- wp:column {"className":"pmk-stats-column"} --><div class="wp-block-column pmk-stats-column"><!-- wp:heading {"textAlign":"center","level":3} --><h3 class="has-text-align-center">2.5M</h3><!-- /wp:heading --><!-- wp:paragraph {"align":"center"} --><p class="has-text-align-center">Monthly Unique Visitors on 10up.com</p><!-- /wp:paragraph --></div><!-- /wp:column --><!-- wp:column {"className":"pmk-stats-column"} --><div class="wp-block-column pmk-stats-column"><!-- wp:heading {"textAlign":"center","level":3} --><h3 class="has-text-align-center">7M</h3><!-- /wp:heading --><!-- wp:paragraph {"align":"center"} --><p class="has-text-align-center">Monthly Page Views on 10up.com</p><!-- /wp:paragraph --></div><!-- /wp:column --><!-- wp:column {"className":"pmk-stats-column"} --><div class="wp-block-column pmk-stats-column"><!-- wp:heading {"textAlign":"center","level":3} --><h3 class="has-text-align-center">646K</h3><!-- /wp:heading --><!-- wp:paragraph {"align":"center"} --><p class="has-text-align-center">Facebook Followers</p><!-- /wp:paragraph --></div><!-- /wp:column --><!-- wp:column {"className":"pmk-stats-column"} --><div class="wp-block-column pmk-stats-column"><!-- wp:heading {"textAlign":"center","level":3} --><h3 class="has-text-align-center">389K</h3><!-- /wp:heading --><!-- wp:paragraph {"align":"center"} --><p class="has-text-align-center">Twitter Followers</p><!-- /wp:paragraph --></div><!-- /wp:column --><!-- wp:column {"className":"pmk-stats-column"} --><div class="wp-block-column pmk-stats-column"><!-- wp:heading {"textAlign":"center","level":3} --><h3 class="has-text-align-center">72K</h3><!-- /wp:heading --><!-- wp:paragraph {"align":"center"} --><p class="has-text-align-center">Instagram Followers</p><!-- /wp:paragraph --></div><!-- /wp:column --></div><!-- /wp:columns --></div><!-- /wp:group -->',
		)
	);

	// Register block pattern for 3 column why choose digital.
	register_block_pattern(
		'publisher-media-kit/why-choose-digital',
		array(
			'title'       => __( 'Publisher Media Kit - Why Choose Digital', 'publisher-media-kit' ),
			'description' => __( 'The 3 column layout for why choose digital block.', 'publisher-media-kit' ),
			'categories'  => [ 'publisher-media-kit' ],
			'content'     => '<!-- wp:group {"align":"wide","className":"pmk-why-digital"} --><div class="wp-block-group alignwide pmk-why-digital"><!-- wp:heading {"level":2} --><h2 class="has-text-align-center">Why you should choose digital</h2><!-- /wp:heading --><!-- wp:columns {"align":"wide"} --><div class="wp-block-columns alignwide"><!-- wp:column {"className":"pmk-digital-column pmk-digital-column-source"} --><div class="wp-block-column pmk-digital-column pmk-digital-column-source"><!-- wp:image {"align":"left","width":35,"height":35} --><figure class="wp-block-image alignleft is-resized"><img src="/wp-content/plugins/publisher-media-kit/assets/images/icon-digital-1.png" alt="Female visitors" width="35" height="35"/></figure><!-- /wp:image --><!-- wp:heading {"level":3} --><h3>Source</h3><!-- /wp:heading --><!-- wp:paragraph --><p>10up is the leading news source in Sacramento. Making it Northern California’s GO-TO choice to stay informed on anything news, weather, sports &amp; entertainment.</p><!-- /wp:paragraph --></div><!-- /wp:column --><!-- wp:column {"className":"pmk-digital-column pmk-digital-column-visibility"} --><div class="wp-block-column pmk-digital-column pmk-digital-column-visibility"><!-- wp:image {"align":"left","width":35,"height":35} --><figure class="wp-block-image alignleft is-resized"><img src="/wp-content/plugins/publisher-media-kit/assets/images/icon-digital-2.png" alt="Female visitors" width="35" height="35"/></figure><!-- /wp:image --><!-- wp:heading {"level":3} --><h3>Visibility</h3><!-- /wp:heading --><!-- wp:paragraph --><p>All ad units across the site have a viewability percentage of 70% of higher, making “above-the-fold” a thing of the past!</p><!-- /wp:paragraph --></div><!-- /wp:column --><!-- wp:column {"className":"pmk-digital-column pmk-digital-column-measure"} --><div class="wp-block-column pmk-digital-column pmk-digital-column-measure"><!-- wp:image {"align":"left","width":35,"height":35} --><figure class="wp-block-image alignleft is-resized"><img src="/wp-content/plugins/publisher-media-kit/assets/images/icon-digital-3.png" alt="Female visitors" width="35" height="35"/></figure><!-- /wp:image --><!-- wp:heading {"level":3} --><h3>Measure</h3><!-- /wp:heading --><!-- wp:paragraph --><p>10up has Measurable ROI! Ads on 10up.com have high click-through percentages against industry benchmark rates.</p><!-- /wp:paragraph --></div><!-- /wp:column --></div><!-- /wp:columns --><!-- wp:buttons {"contentJustification":"center"} --><div class="wp-block-buttons is-content-justification-center"><!-- wp:button {"className":"is-style-fill pmk-button"} --><div class="wp-block-button is-style-fill pmk-button"><a class="wp-block-button__link">CONTACT US</a></div><!-- /wp:button --></div><!-- /wp:buttons --></div><!-- /wp:group -->',
		)
	);

	// Register block pattern for tabs with table structure for Ad specs.
	register_block_pattern(
		'publisher-media-kit/tabs-table-ad-specs',
		array(
			'title'       => __( 'Publisher Media Kit - Ad Specs', 'publisher-media-kit' ),
			'description' => __( 'Ad Specs tabular structure with tabs management.', 'publisher-media-kit' ),
			'categories'  => [ 'publisher-media-kit' ],
			'content'     => '<!-- wp:group {"align":"wide","className":"pmk-tabs-table"} --><div class="wp-block-group alignwide pmk-tabs-table"><!-- wp:tenup/tabs {"tabsTitle":"Digital Ad Specs"} --><!-- wp:tenup/tabs-item {"header":"Standard Display"} --><!-- wp:table --><figure class="wp-block-table"><table><thead><tr><th><em>Ad Type</em></th><th><em>Dimensions</em></th><th><em>File/Creative Type</em></th><th><em>Device</em></th></tr></thead><tbody><tr><td>Billboard</td><td>970x250</td><td>GIF/JPEG - 250KB | HTML 5 - 500KB</td><td>Desktop</td></tr><tr><td>Super Leaderboard</td><td>970x90</td><td>GIF/JPEG - 250KB | HTML 5 - 500KB</td><td><meta charset="utf-8">Desktop</td></tr><tr><td>Leaderboard</td><td>728x90</td><td><meta charset="utf-8">GIF/JPEG - 250KB | HTML 5 - 500KB</td><td><meta charset="utf-8">Desktop, Tablet</td></tr></tbody></table></figure><!-- /wp:table --><!-- /wp:tenup/tabs-item --><!-- wp:tenup/tabs-item {"header":"Video"} --><!-- wp:table --><figure class="wp-block-table"><table><thead><tr><th><em>Ad Type</em></th><th><em>Dimensions</em></th><th><em>File/Creative Type</em></th><th><em><strong>Duration</strong></em></th></tr></thead><tbody><tr><td>Pre-roll</td><td>1280x720 (min) | 16x9 aspect ratio</td><td>MPEG4, 3GPP, MOV, VAST, VPAID - Max 30MB</td><td>15 - 30 seconds</td></tr><tr><td>Mid-roll</td><td>1280x720 (min) | 16x9 aspect ratio</td><td>MPEG4, 3GPP, MOV, VAST, VPAID - Max 30MB</td><td>Up to 15 seconds</td></tr></tbody></table></figure><!-- /wp:table --><!-- /wp:tenup/tabs-item --><!-- wp:tenup/tabs-item {"header":"Native"} --><!-- wp:table --><figure class="wp-block-table"><table><thead><tr><th><em>Type</em></th><th><em><strong>Word Count</strong></em></th><th><em><strong>Placements</strong></em></th><th><em><strong>Accompanying Banner Sizes</strong></em></th></tr></thead><tbody><tr><td>Sponsored Content</td><td>750 - 1000</td><td>7-day dedicated homepage slot, 3 social media posts</td><td>970x250, 728x90, 300x250, 320x50</td></tr><tr><td>Sponsored Email Blast</td><td>250 - 500</td><td>Sent to primary subscriber list of 10,000</td><td>300x250</td></tr></tbody></table></figure><!-- /wp:table --><!-- /wp:tenup/tabs-item --><!-- /wp:tenup/tabs --></div><!-- /wp:group -->',
		)
	);

	// Register block pattern for our packages section.
	register_block_pattern(
		'publisher-media-kit/our-packages',
		array(
			'title'       => __( 'Publisher Media Kit - Our Packages', 'publisher-media-kit' ),
			'description' => __( 'The our packages layout with a short note and 3 column blocks.', 'publisher-media-kit' ),
			'categories'  => [ 'publisher-media-kit' ],
			'content'     => '<!-- wp:group {"align": "wide","className":"pmk-packages"} --><div class="wp-block-group alignwide pmk-packages"><!-- wp:heading {"textAlign":"center","level":2} --><h2 class="has-text-align-center">Our Packages</h2><!-- /wp:heading --><!-- wp:paragraph {"align":"center"} --><p class="has-text-align-center">This is body copy that explains what packages are and why you would choose one over a regular single ad placement. This should span no more than two to three lines.</p><!-- /wp:paragraph --><!-- wp:columns {"align":"wide","className":"pmk-packages-columns"} --><div class="wp-block-columns alignwide pmk-packages-columns"><!-- wp:column {"className":"pmk-packages-column"} --><div class="wp-block-column pmk-packages-column"><!-- wp:heading {"textAlign":"center","level":3} --><h3 class="has-text-align-center">Package 1</h3><!-- /wp:heading --><!-- wp:paragraph {"align":"center"} --><p class="has-text-align-center">You will air on 10up Digital Elements including: live streaming, pre roll on site &amp; banners on site.</p><!-- /wp:paragraph --><!-- wp:list --><ul><li><strong>60,0000</strong> Guaranteed impressions</li><li><strong>50x</strong> Guaranteed live streaming</li></ul><!-- /wp:list --><!-- wp:paragraph {"align":"center","className":"pmk-big-font"} --><p class="has-text-align-center pmk-big-font">Total Cost: $750K</p><!-- /wp:paragraph --></div><!-- /wp:column --><!-- wp:column {"className":"pmk-packages-column"} --><div class="wp-block-column pmk-packages-column"><!-- wp:heading {"textAlign":"center","level":3} --><h3 class="has-text-align-center">Package 2</h3><!-- /wp:heading --><!-- wp:paragraph {"align":"center"} --><p class="has-text-align-center">You will air on 10up Digital Elements including: live streaming, pre roll on site &amp; banners on site.</p><!-- /wp:paragraph --><!-- wp:list --><ul><li><strong>130,000</strong> Guaranteed impressions</li><li><strong>150x</strong> Guaranteed live streaming</li></ul><!-- /wp:list --><!-- wp:paragraph {"align":"center","className":"pmk-big-font"} --><p class="has-text-align-center pmk-big-font">Total Cost: $1,500K</p><!-- /wp:paragraph --></div><!-- /wp:column --><!-- wp:column {"className":"pmk-packages-column"} --><div class="wp-block-column pmk-packages-column"><!-- wp:heading {"textAlign":"center","level":3} --><h3 class="has-text-align-center">Package 3</h3><!-- /wp:heading --><!-- wp:paragraph {"align":"center"} --><p class="has-text-align-center">You will air on 10up Digital Elements including: live streaming, pre roll on site &amp; banners on site.</p><!-- /wp:paragraph --><!-- wp:list --><ul><li><strong>220,000</strong> Guaranteed impressions</li><li><strong>250x</strong> Guaranteed live streaming</li></ul><!-- /wp:list --><!-- wp:paragraph {"align":"center","className":"pmk-big-font"} --><p class="has-text-align-center pmk-big-font">Total Cost: $2,500K</p><!-- /wp:paragraph --></div><!-- /wp:column --></div><!-- /wp:columns --></div><!-- /wp:group -->',
		)
	);

	// Register block pattern for tabs with table structure for Our rates.
	register_block_pattern(
		'publisher-media-kit/tabs-table-our-rates',
		array(
			'title'       => __( 'Publisher Media Kit - Our Rates', 'publisher-media-kit' ),
			'description' => __( 'Our Rates tabular structure with tabs management.', 'publisher-media-kit' ),
			'categories'  => [ 'publisher-media-kit' ],
			'content'     => '<!-- wp:group {"align":"wide","className":"pmk-tabs-table pmk-our-rates"} --><div class="wp-block-group alignwide pmk-tabs-table pmk-our-rates"><!-- wp:tenup/tabs {"tabsTitle":"Our Rates"} --><!-- wp:tenup/tabs-item {"header":"Standard"} --><!-- wp:table --><figure class="wp-block-table"><table><thead><tr><th><em>Type</em></th><th><em><strong>Impression Minimum</strong></em></th><th><em>Cost</em></th></tr></thead><tbody><tr><td>Standard Display</td><td>25,000</td><td>$10 CPM</td></tr><tr><td>Pre-roll / Mid-roll</td><td>20,000</td><td>$25 CPM</td></tr><tr><td>Sponsored Content</td><td>N/A</td><td>$1,000</td></tr><tr><td>Sponsored Content Series (3 posts)</td><td><meta charset="utf-8">N/A</td><td>$2,500</td></tr></tbody></table></figure><!-- /wp:table --><!-- /wp:tenup/tabs-item --><!-- wp:tenup/tabs-item {"header":"Sponsorship"} --><!-- wp:table --><figure class="wp-block-table"><table><thead><tr><th><em>Type</em></th><th><em><strong>Minimum Required Assets</strong></em></th><th><em>Cost</em></th></tr></thead><tbody><tr><td>Homepage Takeover</td><td>728x90, 300x250</td><td>$1,000 CPD</td></tr><tr><td>Category/Tag Takeover</td><td>728x90, 300x250</td><td>$1,500 CPD</td></tr><tr><td>Single Post Takeover</td><td>728x90, 300x250</td><td>$500 CPD</td></tr></tbody></table></figure><!-- /wp:table --><!-- /wp:tenup/tabs-item --><!-- /wp:tenup/tabs --></div><!-- /wp:group -->',
		)
	);

	// Register block pattern for still questions contact us block.
	register_block_pattern(
		'publisher-media-kit/still-questions',
		array(
			'title'       => __( 'Publisher Media Kit - Still Questions', 'publisher-media-kit' ),
			'description' => __( 'The block having a contact button if user has still questions.', 'publisher-media-kit' ),
			'categories'  => [ 'publisher-media-kit' ],
			'content'     => '<!-- wp:group {"align":"wide","className":"pmk-question-block"} --><div class="wp-block-group alignwide pmk-question-block"><!-- wp:columns {"align":"wide"} --><div class="wp-block-columns alignwide"><!-- wp:column {"className":"pmk-question-left"} --><div class="wp-block-column pmk-question-left"><!-- wp:heading {"level":2} --><h2><meta charset="utf-8">Still have questions? We can help.</h2><!-- /wp:heading --></div><!-- /wp:column --><!-- wp:column {"className":"pmk-question-right"} --><div class="wp-block-column pmk-question-right"><!-- wp:buttons --><div class="wp-block-buttons"><!-- wp:button {"className":"is-style-fill pmk-button"} --><div class="wp-block-button is-style-fill pmk-button"><a class="wp-block-button__link">CONTACT US</a></div><!-- /wp:button --></div><!-- /wp:buttons --></div><!-- /wp:column --></div><!-- /wp:columns --></div><!-- /wp:group -->',
		)
	);

	// phpcs:enable
}
