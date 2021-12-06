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
function setup() {
	$n = function ( $function ) {
		return __NAMESPACE__ . "\\$function";
	};

	add_action( 'init', $n( 'register_blocks' ) );

	add_action( 'init', $n( 'block_patterns_and_categories' ) );

}

/**
 * Add in blocks that are registered in this plugin
 *
 * @return void
 */
function register_blocks() {
	// Require custom blocks.
	require_once PUBLISHER_MEDIA_KIT_BLOCKS_PATH . '/tabs/register.php';

	// Call block register functions for each block.
	Tabs\register();

	// Require custom blocks.
	require_once PUBLISHER_MEDIA_KIT_BLOCKS_PATH . '/tabs-item/register.php';

	// Call block register functions for each block.
	TabsItem\register();
}

/**
 * Manage block patterns and block pattern categories
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-patterns/
 *
 * @return void
 */
function block_patterns_and_categories() {
	// phpcs:disable

	// Register block pattern category for Publisher Media Kit.
	register_block_pattern_category(
		'publisher-media-kit',
		array( 'label' => __( 'Publisher Media Kit', 'publisher-media-kit' ) )
	);

	global $wp_version;
	$cover_patter_file = version_compare( $wp_version, '5.7', '<' ) ? 'cover' : 'cover-esperanza';

	// Register block pattern for cover image.
	ob_start();
	include_once PUBLISHER_MEDIA_KIT_BLOCK_PATTERS . $cover_patter_file . '.php';
	$cover = ob_get_clean();
	register_block_pattern(
		'publisher-media-kit/cover-pattern',
		array(
			'title'       => __( 'Publisher Media Kit - Cover', 'publisher-media-kit' ),
			'description' => __( 'The main cover image for the Publisher Media Kit page.', 'publisher-media-kit' ),
			'categories'  => [ 'publisher-media-kit' ],
			'content'     => wp_kses_post( $cover ),
		)
	);

	// Register block pattern for audience profiles.
	ob_start();
	include_once PUBLISHER_MEDIA_KIT_BLOCK_PATTERS . 'audience-profiles.php';
	$audience_profiles = ob_get_clean();
	register_block_pattern(
		'publisher-media-kit/audience-profiles',
		array(
			'title'       => __( 'Publisher Media Kit - Audience Profiles', 'publisher-media-kit' ),
			'description' => __( 'The 2 column layout showing the audience profiles.', 'publisher-media-kit' ),
			'categories'  => [ 'publisher-media-kit' ],
			'content'     => wp_kses_post( $audience_profiles ),
		)
	);

	// Register block pattern for 5 column statics block.
	ob_start();
	include_once PUBLISHER_MEDIA_KIT_BLOCK_PATTERS . 'stats.php';
	$stats = ob_get_clean();
	register_block_pattern(
		'publisher-media-kit/five-column-statics',
		array(
			'title'       => __( 'Publisher Media Kit - 5 Column Statics', 'publisher-media-kit' ),
			'description' => __( 'The 5 column layout showing the statics.', 'publisher-media-kit' ),
			'categories'  => [ 'publisher-media-kit' ],
			'content'     => wp_kses_post( $stats ),
		)
	);

	// Register block pattern for 3 column why choose digital.
	ob_start();
	include_once PUBLISHER_MEDIA_KIT_BLOCK_PATTERS . 'why-digital.php';
	$why_digital = ob_get_clean();
	register_block_pattern(
		'publisher-media-kit/why-choose-digital',
		array(
			'title'       => __( 'Publisher Media Kit - Why Choose Digital', 'publisher-media-kit' ),
			'description' => __( 'The 3 column layout for why choose digital block.', 'publisher-media-kit' ),
			'categories'  => [ 'publisher-media-kit' ],
			'content'     => wp_kses_post( $why_digital ),
		)
	);

	// Register block pattern for tabs with table structure for Ad specs.
	ob_start();
	include_once PUBLISHER_MEDIA_KIT_BLOCK_PATTERS . 'digital-ad-specs.php';
	$digital_ad_specs = ob_get_clean();
	register_block_pattern(
		'publisher-media-kit/tabs-table-ad-specs',
		array(
			'title'       => __( 'Publisher Media Kit - Ad Specs', 'publisher-media-kit' ),
			'description' => __( 'Ad Specs tabular structure with tabs management.', 'publisher-media-kit' ),
			'categories'  => [ 'publisher-media-kit' ],
			'content'     => wp_kses_post( $digital_ad_specs ),
		)
	);

	// Register block pattern for our packages section.
	ob_start();
	include_once PUBLISHER_MEDIA_KIT_BLOCK_PATTERS . 'our-packages.php';
	$our_packages = ob_get_clean();
	register_block_pattern(
		'publisher-media-kit/our-packages',
		array(
			'title'       => __( 'Publisher Media Kit - Our Packages', 'publisher-media-kit' ),
			'description' => __( 'The our packages layout with a short note and 3 column blocks.', 'publisher-media-kit' ),
			'categories'  => [ 'publisher-media-kit' ],
			'content'     => wp_kses_post( $our_packages ),
		)
	);

	// Register block pattern for tabs with table structure for Our rates.
	ob_start();
	include_once PUBLISHER_MEDIA_KIT_BLOCK_PATTERS . 'our-rates.php';
	$our_rates = ob_get_clean();
	register_block_pattern(
		'publisher-media-kit/tabs-table-our-rates',
		array(
			'title'       => __( 'Publisher Media Kit - Our Rates', 'publisher-media-kit' ),
			'description' => __( 'Our Rates tabular structure with tabs management.', 'publisher-media-kit' ),
			'categories'  => [ 'publisher-media-kit' ],
			'content'     => wp_kses_post( $our_rates ),
		)
	);

	// Register block pattern for still questions contact us block.
	ob_start();
	include_once PUBLISHER_MEDIA_KIT_BLOCK_PATTERS . 'question-block.php';
	$question_block = ob_get_clean();
	register_block_pattern(
		'publisher-media-kit/still-questions',
		array(
			'title'       => __( 'Publisher Media Kit - Still Questions', 'publisher-media-kit' ),
			'description' => __( 'The block having a contact button if user has still questions.', 'publisher-media-kit' ),
			'categories'  => [ 'publisher-media-kit' ],
			'content'     => wp_kses_post( $question_block ),
		)
	);

	// phpcs:enable
}
