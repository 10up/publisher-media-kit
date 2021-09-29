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

		$pmk_page_content = '<!-- wp:group {"align":"full","className":"pnk-parent"} --><div class="wp-block-group alignfull pnk-parent"><!-- wp:cover {"url":"/wp-content/plugins/publisher-media-kit/assets/images/cover-image.png","id":17,"align":"full","className":"pmk-cover"} --><div class="wp-block-cover alignfull has-background-dim pmk-cover"><img class="wp-block-cover__image-background wp-image-17" alt="" src="/wp-content/plugins/publisher-media-kit/assets/images/cover-image.png" data-object-fit="cover"/><div class="wp-block-cover__inner-container"><!-- wp:heading {"textAlign":"center"} --><h2 class="has-text-align-center">Media Kit</h2><!-- /wp:heading --><!-- wp:paragraph {"align":"center"} --><p class="has-text-align-center">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer dui<br>tortor, porttitor ut enim non, iaculis sagittis dolor.</p><!-- /wp:paragraph --><!-- wp:buttons {"contentJustification":"center"} --><div class="wp-block-buttons is-content-justification-center"><!-- wp:button {"className":"is-style-fill"} --><div class="wp-block-button is-style-fill"><a class="wp-block-button__link">CONTACT US</a></div><!-- /wp:button --></div><!-- /wp:buttons --></div></div><!-- /wp:cover --></div><!-- /wp:group -->';

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
