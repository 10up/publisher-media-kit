<?php
/**
 * Core plugin functionality.
 *
 * @package PublisherMediaKit
 */

namespace PublisherMediaKit\Core;

use \WP_Error;

/**
 * Default setup routine
 *
 * @return void
 */
function setup() {
	$n = function( $function ) {
		return __NAMESPACE__ . "\\$function";
	};

	add_action( 'init', $n( 'i18n' ) );
	add_action( 'init', $n( 'init' ) );
	add_action( 'wp_enqueue_scripts', $n( 'scripts' ) );
	add_action( 'wp_enqueue_scripts', $n( 'styles' ) );
	add_action( 'admin_enqueue_scripts', $n( 'admin_scripts' ) );
	add_action( 'admin_enqueue_scripts', $n( 'admin_styles' ) );

	// Editor styles. add_editor_style() doesn't work outside of a theme.
	add_filter( 'mce_css', $n( 'mce_css' ) );
	// Hook to allow async or defer on asset loading.
	add_filter( 'script_loader_tag', $n( 'script_loader_tag' ), 10, 2 );

	do_action( 'publisher_media_kit_loaded' );
}

/**
 * Registers the default textdomain.
 *
 * @return void
 */
function i18n() {
	$locale = apply_filters( 'plugin_locale', get_locale(), 'publisher-media-kit' );
	load_textdomain( 'publisher-media-kit', WP_LANG_DIR . '/publisher-media-kit/publisher-media-kit-' . $locale . '.mo' );
	load_plugin_textdomain( 'publisher-media-kit', false, plugin_basename( PUBLISHER_MEDIA_KIT_PATH ) . '/languages/' );
}

/**
 * Initializes the plugin and fires an action other plugins can hook into.
 *
 * @return void
 */
function init() {
	do_action( 'publisher_media_kit_init' );
}

/**
 * Activate the plugin
 *
 * @return void
 */
function activate() {

	if ( ! current_user_can( 'activate_plugins' ) ) return;

	$n = function( $function ) {
		return __NAMESPACE__ . "\\$function";
	};

	// Create a media kit page.
	add_action('publisher_media_kit_init', $n('create_media_kit_page'));

	// First load the init scripts in case any rewrite functionality is being loaded
	init();
	flush_rewrite_rules();
}

/**
 * Deactivate the plugin
 *
 * Uninstall routines should be in uninstall.php
 *
 * @return void
 */
function deactivate() {

}

/**
 * A function to create a Publisher Media Kit page automatically.
 * A page will be created with 'pmk-page' meta key.
 * It also checks if a page with 'pmk-page' exists already or not.
 */
function create_media_kit_page()
{
	// Check if the Media Kit page already created in past.
	global $wpdb;

	$pmk_page_exists = $wpdb->get_row(
		"SELECT * FROM {$wpdb->prefix}postmeta as pm
					LEFT JOIN {$wpdb->prefix}posts as p
					ON p.ID = pm.post_id
					WHERE pm.meta_key = 'pmk-page'
					AND p.post_status != 'trash'
					AND p.post_type = 'page'
					", 'ARRAY_A');

	if (null === $pmk_page_exists) {

		$current_user = wp_get_current_user();

		$pmk_page_content = '<!-- wp:group {"align":"full","className":"pmk-media-kit"} --><div class="wp-block-group alignfull pmk-media-kit"><!-- wp:cover {"url":"/wp-content/plugins/publisher-media-kit/assets/images/cover-image.png","id":17,"align":"full","className":"pmk-cover"} --><div class="wp-block-cover alignfull has-background-dim pmk-cover"><img class="wp-block-cover__image-background wp-image-17" alt="" src="/wp-content/plugins/publisher-media-kit/assets/images/cover-image.png" data-object-fit="cover"/><div class="wp-block-cover__inner-container"><!-- wp:heading {"level": 1,"textAlign":"center"} --><h1 class="has-text-align-center">Media Kit</h1><!-- /wp:heading --><!-- wp:paragraph {"align":"center"} --><p class="has-text-align-center">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer dui<br>tortor, porttitor ut enim non, iaculis sagittis dolor.</p><!-- /wp:paragraph --><!-- wp:buttons {"contentJustification":"center"} --><div class="wp-block-buttons is-content-justification-center"><!-- wp:button {"className":"is-style-fill pmk-button"} --><div class="wp-block-button pmk-button is-style-fill"><a class="wp-block-button__link">CONTACT US</a></div><!-- /wp:button --></div><!-- /wp:buttons --></div></div><!-- /wp:cover --><!-- wp:group {"align":"wide","className":"pmk-audience-profiles"} --><div class="wp-block-group alignwide pmk-audience-profiles"><!-- wp:heading {"textAlign":"center","level":2} --><h2 class="has-text-align-center">Audience Profiles</h2><!-- /wp:heading --><!-- wp:columns {"align":"wide"} --><div class="wp-block-columns alignwide"><!-- wp:column --><div class="wp-block-column"><!-- wp:image {"align":"center","width":60,"height":137} --><div id="block-ad47cc21-f741-4805-a4d7-85e15762d9d9" class="wp-block-image"><figure class="aligncenter is-resized"><img src="/wp-content/plugins/publisher-media-kit/assets/images/icon-woman.png" alt="Female visitors" width="60" height="137"/></figure></div><!-- /wp:image --><!-- wp:paragraph {"align":"center"} --><p class="has-text-align-center" id="block-80c8c75b-6f07-4ca5-a041-fe48703c3e56">The average female visitor is<br><strong>38 years old</strong></p><!-- /wp:paragraph --></div><!-- /wp:column --><!-- wp:column --><div class="wp-block-column"><!-- wp:image {"align":"center","width":60,"height":137} --><div id="block-7db2072f-dbec-47c8-afdb-8e3c78404377" class="wp-block-image"><figure class="aligncenter is-resized"><img src="/wp-content/plugins/publisher-media-kit/assets/images/icon-man.png" alt="Male visitors" width="60" height="137"/></figure></div><!-- /wp:image --><!-- wp:paragraph {"align":"center"} --><p class="has-text-align-center" id="block-5faaca4c-38a7-4e2f-aee3-a7312df7cd8f">The average male visitor is<br><strong>52 years old</strong></p><!-- /wp:paragraph --></div><!-- /wp:column --></div><!-- /wp:columns --></div><!-- /wp:group --><!-- wp:group {"align":"wide","className":"pmk-stats"} --><div class="wp-block-group alignwide pmk-stats"><!-- wp:columns {"align":"wide"} -->
			<div class="wp-block-columns alignwide"><!-- wp:column {"className":"pmk-stats-column"} --><div class="wp-block-column pmk-stats-column"><!-- wp:heading {"textAlign":"center","level":3} --><h3 class="has-text-align-center">2.5M</h3><!-- /wp:heading --><!-- wp:paragraph {"align":"center"} --><p class="has-text-align-center">Monthly Unique Visitors on WSVN.com</p><!-- /wp:paragraph --></div><!-- /wp:column --><!-- wp:column {"className":"pmk-stats-column"} --><div class="wp-block-column pmk-stats-column"><!-- wp:heading {"textAlign":"center","level":3} --><h3 class="has-text-align-center">7M</h3><!-- /wp:heading --><!-- wp:paragraph {"align":"center"} --><p class="has-text-align-center">Monthly Page Views on WSVN.com</p><!-- /wp:paragraph --></div><!-- /wp:column --><!-- wp:column {"className":"pmk-stats-column"} --><div class="wp-block-column pmk-stats-column"><!-- wp:heading {"textAlign":"center","level":3} --><h3 class="has-text-align-center">646K</h3><!-- /wp:heading --><!-- wp:paragraph {"align":"center"} --><p class="has-text-align-center">Facebook Followers</p><!-- /wp:paragraph --></div><!-- /wp:column --><!-- wp:column {"className":"pmk-stats-column"} --><div class="wp-block-column pmk-stats-column"><!-- wp:heading {"textAlign":"center","level":3} --><h3 class="has-text-align-center">389K</h3><!-- /wp:heading --><!-- wp:paragraph {"align":"center"} --><p class="has-text-align-center">Twitter Followers</p><!-- /wp:paragraph --></div><!-- /wp:column --><!-- wp:column {"className":"pmk-stats-column"} --><div class="wp-block-column pmk-stats-column"><!-- wp:heading {"textAlign":"center","level":3} --><h3 class="has-text-align-center">72K</h3><!-- /wp:heading --><!-- wp:paragraph {"align":"center"} --><p class="has-text-align-center">Instagram Followers</p><!-- /wp:paragraph --></div><!-- /wp:column --></div><!-- /wp:columns --></div><!-- /wp:group --><!-- wp:group {"align":"wide","className":"pmk-why-digital"} --><div class="wp-block-group alignwide pmk-why-digital"><!-- wp:heading {"level":2} --><h2 class="has-text-align-center">Why you should choose digital</h2><!-- /wp:heading --><!-- wp:columns {"align":"wide"} --><div class="wp-block-columns alignwide"><!-- wp:column {"className":"pmk-digital-column pmk-digital-column-source"} --><div class="wp-block-column pmk-digital-column pmk-digital-column-source"><!-- wp:heading {"level":3} --><h3>Source</h3><!-- /wp:heading --><!-- wp:paragraph --><p>WSVN is the leading news source in Miami. Making it South Florida’s GO-TO choice to stay informed on anything news, weather, sports &amp; entertainment.</p><!-- /wp:paragraph --></div><!-- /wp:column --><!-- wp:column {"className":"pmk-digital-column pmk-digital-column-visibility"} --><div class="wp-block-column pmk-digital-column pmk-digital-column-visibility"><!-- wp:heading {"level":3} --><h3>Visibility</h3><!-- /wp:heading --><!-- wp:paragraph --><p>All ad units across the site have a viewability percentage of 70% of higher, making “above-the-fold” a thing of the past!</p><!-- /wp:paragraph --></div><!-- /wp:column --><!-- wp:column {"className":"pmk-digital-column pmk-digital-column-measure"} --><div class="wp-block-column pmk-digital-column pmk-digital-column-measure"><!-- wp:heading {"level":3} --><h3>Measure</h3><!-- /wp:heading --><!-- wp:paragraph --><p>WSVN has Measurable ROI! Ads on WSVN.com have high click-through percentages against industry benchmark rates.</p><!-- /wp:paragraph --></div><!-- /wp:column --></div><!-- /wp:columns --><!-- wp:buttons {"contentJustification":"center"} --><div class="wp-block-buttons is-content-justification-center"><!-- wp:button {"className":"is-style-fill pmk-button"} --><div class="wp-block-button is-style-fill pmk-button"><a class="wp-block-button__link">CONTACT US</a></div><!-- /wp:button --></div><!-- /wp:buttons --></div><!-- /wp:group --><!-- wp:group {"align": "wide", "className":"pmk-tabs-table"} --><div class="wp-block-group alignwide pmk-tabs-table"><!-- wp:tenup/tabs {"tabsTitle":"Digital Ad Specs"} --><!-- wp:tenup/tabs-item {"header":"Standard Display"} --><!-- wp:table --><figure class="wp-block-table"><table><thead><tr><th><em>Ad Type</em></th><th><em>Dimensions</em></th><th><em>File/Creative Type</em></th><th><em>Device</em></th></tr></thead><tbody><tr><td>Billboard</td><td>970x250</td><td>GIF/JPEG - 250KB | HTML 5 - 500KB</td><td>Desktop</td></tr><tr><td>Super Leaderboard</td><td>970x90</td><td>GIF/JPEG - 250KB | HTML 5 - 500KB</td><td><meta charset="utf-8">Desktop</td></tr><tr><td>Leaderboard</td><td>728x90</td><td><meta charset="utf-8">GIF/JPEG - 250KB | HTML 5 - 500KB</td><td><meta charset="utf-8">Desktop, Tablet</td></tr></tbody></table></figure><!-- /wp:table --><!-- /wp:tenup/tabs-item --><!-- wp:tenup/tabs-item {"header":"Video"} --><!-- wp:table --><figure class="wp-block-table"><table><thead><tr><th><em>Ad Type</em></th><th><em>Dimensions</em></th><th><em>File/Creative Type</em></th><th><em>Device</em></th></tr></thead><tbody><tr><td>Medium Rectangle</td><td>300x250</td><td>GIF/JPEG - 250KB | HTML 5 - 500KB</td><td>Desktop, Tablet, Mobile</td></tr><tr><td>Half Page</td><td>300x600</td><td>GIF/JPEG - 250KB | HTML 5 - 500KB</td><td>Desktop</td></tr></tbody></table></figure><!-- /wp:table --><!-- /wp:tenup/tabs-item --><!-- wp:tenup/tabs-item {"header":"Native"} --><!-- wp:table --><figure class="wp-block-table"><table><thead><tr><th><em>Ad Type</em></th><th><em>Dimensions</em></th><th><em>File/Creative Type</em></th><th><em>Device</em></th></tr></thead><tbody><tr><td>Small Mobile Banner</td><td>320x50</td><td>GIF/JPEG - 250KB | HTML 5 - 500KB</td><td>Mobile</td></tr><tr><td>Large Mobile Banner</td><td>320x100</td><td>GIF/JPEG - 250KB | HTML 5 - 500KB</td><td>Mobile</td></tr></tbody></table></figure><!-- /wp:table --><!-- /wp:tenup/tabs-item --><!-- /wp:tenup/tabs --></div><!-- /wp:group --><!-- wp:group {"align": "wide","className":"pmk-packages"} --><div class="wp-block-group alignwide pmk-packages"><!-- wp:heading {"textAlign":"center","level":2} --><h2 class="has-text-align-center">Our Packages</h2><!-- /wp:heading --><!-- wp:paragraph {"align":"center"} --><p class="has-text-align-center">This is body copy that explains what packages are and why you would choose one over a regular single ad placement. This should span no more than two to three lines.</p><!-- /wp:paragraph --><!-- wp:columns {"align":"wide","className":"pmk-packages-columns"} --><div class="wp-block-columns alignwide pmk-packages-columns"><!-- wp:column {"className":"pmk-packages-column"} --><div class="wp-block-column pmk-packages-column"><!-- wp:heading {"textAlign":"center","level":3} --><h3 class="has-text-align-center">Package 1</h3><!-- /wp:heading --><!-- wp:paragraph {"align":"center"} --><p class="has-text-align-center">You will air on WSVN Digital Elements including: live streaming, pre roll on site &amp; banners on site.</p><!-- /wp:paragraph --><!-- wp:list --><ul><li><strong>60,0000</strong> Guaranteed impressions</li><li><strong>50x</strong> Guaranteed live streaming</li></ul><!-- /wp:list --><!-- wp:paragraph {"align":"center","className":"pmk-big-font"} --><p class="has-text-align-center pmk-big-font">Total Cost: $750K</p><!-- /wp:paragraph --></div><!-- /wp:column --><!-- wp:column {"className":"pmk-packages-column"} --><div class="wp-block-column pmk-packages-column"><!-- wp:heading {"textAlign":"center","level":3} --><h3 class="has-text-align-center">Package 1</h3><!-- /wp:heading --><!-- wp:paragraph {"align":"center"} --><p class="has-text-align-center">You will air on WSVN Digital Elements including: live streaming, pre roll on site &amp; banners on site.</p><!-- /wp:paragraph --><!-- wp:list --><ul><li><strong>60,0000</strong> Guaranteed impressions</li><li><strong>50x</strong> Guaranteed live streaming</li></ul><!-- /wp:list --><!-- wp:paragraph {"align":"center","className":"pmk-big-font"} --><p class="has-text-align-center pmk-big-font">Total Cost: $750K</p><!-- /wp:paragraph --></div><!-- /wp:column --><!-- wp:column {"className":"pmk-packages-column"} --><div class="wp-block-column pmk-packages-column"><!-- wp:heading {"textAlign":"center","level":3} --><h3 class="has-text-align-center">Package 1</h3><!-- /wp:heading --><!-- wp:paragraph {"align":"center"} --><p class="has-text-align-center">You will air on WSVN Digital Elements including: live streaming, pre roll on site &amp; banners on site.</p><!-- /wp:paragraph --><!-- wp:list --><ul><li><strong>60,0000</strong> Guaranteed impressions</li><li><strong>50x</strong> Guaranteed live streaming</li></ul><!-- /wp:list --><!-- wp:paragraph {"align":"center","className":"pmk-big-font"} --><p class="has-text-align-center pmk-big-font">Total Cost: $750K</p><!-- /wp:paragraph --></div><!-- /wp:column --></div><!-- /wp:columns --></div><!-- /wp:group --><!-- wp:group {"align":"wide","className":"pmk-tabs-table pmk-our-rates"} --><div class="wp-block-group alignwide pmk-tabs-table pmk-our-rates"><!-- wp:tenup/tabs {"tabsTitle":"Our Rates"} --><!-- wp:tenup/tabs-item {"header":"Standard"} --><!-- wp:table --><figure class="wp-block-table"><table><thead><tr><th><em>Ad Type</em></th><th><em>Dimensions</em></th><th><em>Cost</em></th></tr></thead><tbody><tr><td>Standard Display</td><td>25,000</td><td>$10 CPM</td></tr><tr><td>Pre-roll</td><td>20,000</td><td>$25 CPM</td></tr><tr><td>Live Streaming</td><td>N/A</td><td><meta charset="utf-8">$10/a spot</td></tr></tbody></table></figure><!-- /wp:table --><!-- /wp:tenup/tabs-item --><!-- wp:tenup/tabs-item {"header":"Sponshorship"} --><!-- wp:table --><figure class="wp-block-table"><table><thead><tr><th><em>Ad Type</em></th><th><em>Dimensions</em></th><th><em>Cost</em></th></tr></thead><tbody><tr><td>Sponsored Content</td><td>N/A</td><td>$1,000</td></tr><tr><td>Sponsored Content Series (3 posts)</td><td>N/A</td><td>$25,000</td></tr></tbody></table></figure><!-- /wp:table --><!-- /wp:tenup/tabs-item --><!-- wp:tenup/tabs-item {"header":"App Mid-roll"} --><!-- wp:table --><figure class="wp-block-table"><table><thead><tr><th><em>Ad Type</em></th><th><em>Dimensions</em></th><th><em>Cost</em></th></tr></thead><tbody><tr><td>Countdown Cloc</td><td>N/A</td><td>$1,500 per week</td></tr><tr><td>Online Contest</td><td>N/A</td><td>$25000 per week</td></tr></tbody></table></figure><!-- /wp:table --><!-- /wp:tenup/tabs-item --><!-- /wp:tenup/tabs --></div><!-- /wp:group --><!-- wp:group {"align":"wide","className":"pmk-question-block"} --><div class="wp-block-group alignwide pmk-question-block"><!-- wp:columns {"align":"wide"} --><div class="wp-block-columns alignwide"><!-- wp:column {"className":"pmk-question-left"} --><div class="wp-block-column pmk-question-left"><!-- wp:heading {"level":2} --><h2><meta charset="utf-8">Still have questions? We can help.</h2><!-- /wp:heading --></div><!-- /wp:column --><!-- wp:column {"className":"pmk-question-right"} --><div class="wp-block-column pmk-question-right"><!-- wp:buttons --><div class="wp-block-buttons"><!-- wp:button {"className":"is-style-fill pmk-button"} --><div class="wp-block-button is-style-fill pmk-button"><a class="wp-block-button__link">CONTACT US</a></div><!-- /wp:button --></div><!-- /wp:buttons --></div><!-- /wp:column --></div><!-- /wp:columns --></div><!-- /wp:group --></div><!-- /wp:group -->';

		// create post object
		$page = array(
			'post_title' => __('Publisher Media Kit'),
			'post_status' => 'publish',
			'post_author' => $current_user->ID,
			'post_type' => 'page',
			'post_name' => 'media-kit',
			'post_content' => $pmk_page_content,
		);

		// insert the post into the database
		$post_id = wp_insert_post($page);

		// insert post meta for identity.
		add_post_meta($post_id, 'pmk-page', 1);
	}
}

/**
 * The list of knows contexts for enqueuing scripts/styles.
 *
 * @return array
 */
function get_enqueue_contexts() {
	return [ 'admin', 'frontend', 'shared' ];
}

/**
 * Generate an URL to a script, taking into account whether SCRIPT_DEBUG is enabled.
 *
 * @param string $script Script file name (no .js extension)
 * @param string $context Context for the script ('admin', 'frontend', or 'shared')
 *
 * @return string|WP_Error URL
 */
function script_url( $script, $context ) {

	if ( ! in_array( $context, get_enqueue_contexts(), true ) ) {
		return new WP_Error( 'invalid_enqueue_context', 'Invalid $context specified in PublisherMediaKit script loader.' );
	}

	return PUBLISHER_MEDIA_KIT_URL . "dist/js/${script}.js";

}

/**
 * Generate an URL to a stylesheet, taking into account whether SCRIPT_DEBUG is enabled.
 *
 * @param string $stylesheet Stylesheet file name (no .css extension)
 * @param string $context Context for the script ('admin', 'frontend', or 'shared')
 *
 * @return string URL
 */
function style_url( $stylesheet, $context ) {

	if ( ! in_array( $context, get_enqueue_contexts(), true ) ) {
		return new WP_Error( 'invalid_enqueue_context', 'Invalid $context specified in PublisherMediaKit stylesheet loader.' );
	}

	return PUBLISHER_MEDIA_KIT_URL . "dist/css/${stylesheet}.css";

}

/**
 * Enqueue scripts for front-end.
 *
 * @return void
 */
function scripts() {

	wp_enqueue_script(
		'publisher_media_kit_shared',
		script_url( 'shared', 'shared' ),
		[],
		PUBLISHER_MEDIA_KIT_VERSION,
		true
	);

	wp_enqueue_script(
		'publisher_media_kit_frontend',
		script_url( 'frontend', 'frontend' ),
		[],
		PUBLISHER_MEDIA_KIT_VERSION,
		true
	);

	/*$tabs_dep = require_once PUBLISHER_MEDIA_KIT_PATH . 'dist/blocks/tabs-block/editor.asset.php';
	wp_enqueue_script(
		'publisher_media_kit_tabs_frontend',
		PUBLISHER_MEDIA_KIT_URL . 'dist/blocks/tabs-block/editor.js',
		[ 'wp-blocks'],// $tabs_dep['dependencies'],
		$tabs_dep['version'],
		true
	);*/

	$tabs_item_dep = require_once PUBLISHER_MEDIA_KIT_PATH . 'dist/blocks/tabs-item-block/editor.asset.php';
	wp_enqueue_script(
		'publisher_media_kit_tabs_item_frontend',
		PUBLISHER_MEDIA_KIT_URL . 'dist/blocks/tabs-item-block/editor.js',
		$tabs_item_dep['dependencies'],
		$tabs_item_dep['version'],
		true
	);

}

/**
 * Enqueue scripts for admin.
 *
 * @return void
 */
function admin_scripts() {

	wp_enqueue_script(
		'publisher_media_kit_shared',
		script_url( 'shared', 'shared' ),
		[],
		PUBLISHER_MEDIA_KIT_VERSION,
		true
	);

	wp_enqueue_script(
		'publisher_media_kit_admin',
		script_url( 'admin', 'admin' ),
		[],
		PUBLISHER_MEDIA_KIT_VERSION,
		true
	);

}

/**
 * Enqueue styles for front-end.
 *
 * @return void
 */
function styles() {

	wp_enqueue_style(
		'publisher_media_kit_shared',
		style_url( 'shared-style', 'shared' ),
		[],
		PUBLISHER_MEDIA_KIT_VERSION
	);

	if ( is_admin() ) {
		wp_enqueue_style(
			'publisher_media_kit_admin',
			style_url( 'admin-style', 'admin' ),
			[],
			PUBLISHER_MEDIA_KIT_VERSION
		);
	} else {
		wp_enqueue_style(
			'publisher_media_kit_frontend',
			style_url( 'style', 'frontend' ),
			[],
			PUBLISHER_MEDIA_KIT_VERSION
		);
	}

	/*wp_enqueue_style(
		'publisher_media_kit_tabs_frontend',
		PUBLISHER_MEDIA_KIT_URL . 'dist/blocks/tabs-block/editor.css',
		[],
		PUBLISHER_MEDIA_KIT_VERSION
	);*/
}

/**
 * Enqueue styles for admin.
 *
 * @return void
 */
function admin_styles() {

	wp_enqueue_style(
		'publisher_media_kit_shared',
		style_url( 'shared-style', 'shared' ),
		[],
		PUBLISHER_MEDIA_KIT_VERSION
	);

	wp_enqueue_style(
		'publisher_media_kit_admin',
		style_url( 'admin-style', 'admin' ),
		[],
		PUBLISHER_MEDIA_KIT_VERSION
	);

}

/**
 * Enqueue editor styles. Filters the comma-delimited list of stylesheets to load in TinyMCE.
 *
 * @param string $stylesheets Comma-delimited list of stylesheets.
 * @return string
 */
function mce_css( $stylesheets ) {
	if ( ! empty( $stylesheets ) ) {
		$stylesheets .= ',';
	}

	return $stylesheets . PUBLISHER_MEDIA_KIT_URL . ( ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ?
			'assets/css/frontend/editor-style.css' :
			'dist/css/editor-style.min.css' );
}

/**
 * Add async/defer attributes to enqueued scripts that have the specified script_execution flag.
 *
 * @link https://core.trac.wordpress.org/ticket/12009
 * @param string $tag    The script tag.
 * @param string $handle The script handle.
 * @return string
 */
function script_loader_tag( $tag, $handle ) {
	$script_execution = wp_scripts()->get_data( $handle, 'script_execution' );

	if ( ! $script_execution ) {
		return $tag;
	}

	if ( 'async' !== $script_execution && 'defer' !== $script_execution ) {
		return $tag; // _doing_it_wrong()?
	}

	// Abort adding async/defer for scripts that have this script as a dependency. _doing_it_wrong()?
	foreach ( wp_scripts()->registered as $script ) {
		if ( in_array( $handle, $script->deps, true ) ) {
			return $tag;
		}
	}

	// Add the attribute if it hasn't already been added.
	if ( ! preg_match( ":\s$script_execution(=|>|\s):", $tag ) ) {
		$tag = preg_replace( ':(?=></script>):', " $script_execution", $tag, 1 );
	}

	return $tag;
}
