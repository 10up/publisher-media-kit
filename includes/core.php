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
	$n = function ( $function ) {
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

	// Admin notice with media-kit page link.
	add_action( 'admin_notices', $n( 'pmk_admin_notice_notice' ) );
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

	if ( ! current_user_can( 'activate_plugins' ) ) {
		return;
	}

	$n = function ( $function ) {
		return __NAMESPACE__ . "\\$function";
	};

	// Create a media kit page.
	add_action( 'publisher_media_kit_init', $n( 'create_media_kit_page' ) );

	// First load the init scripts in case any rewrite functionality is being loaded
	init();
	flush_rewrite_rules();
}

/**
 * Admin Notice with medi-kit page link on activation.
 */
function pmk_admin_notice_notice() {

	/* Check transient, if available display notice */
	if ( get_transient( 'pmk-admin-notice' ) ) {
		$media_kit_id   = get_option( 'pmk-page' );
		$media_kit_link = $media_kit_id ? get_edit_post_link( $media_kit_id ) : admin_url( 'edit.php?post_type=page' );
		?>
		<div class="updated notice is-dismissible">
			<p><?php echo wp_kses_post( sprintf( __( 'A "Media Kit" page has been created! Please <a href="%s">click here</a> to edit and publish the page.', 'publisher-media-kit' ), esc_url( $media_kit_link ) ) ); ?></p>
		</div>
		<?php
		/* Delete transient, only display this notice once. */
		delete_transient( 'pmk-admin-notice' );
	}
}

/**
 * Deactivate the plugin
 *
 * Uninstall routines should be in uninstall.php
 *
 * @return void
 */
function deactivate() {
	// on deactivation, remove unnecessary data from database.
	delete_option( 'pmk-page' );
}

/**
 * A function to create a Publisher Media Kit page automatically.
 * A page will be created with 'pmk-page' meta key.
 * It also checks if a page with 'pmk-page' exists already or not.
 *
 * @throws \Exception Throws exception on Media Kit page creation fail.
 */
function create_media_kit_page() {
	// WP_Query arguments
	$args = array(
		'post_type'      => array( 'page' ),
		'post_status'    => array( 'publish', 'pending', 'draft', 'auto-draft', 'future', 'private' ),
		'posts_per_page' => 1,
		'fields'         => 'ids',
		'meta_query'     => array(
			array(
				'key' => 'pmk-page',
			),
		),
	);

	// The query to get the Media Kit page ID.
	$query   = new \WP_Query( $args );
	$post_ID = $query->posts[0] ?? '';

	// Restore original Post Data
	wp_reset_postdata();

	if ( empty( $post_ID ) ) {

		global $wp_version;

		$current_user      = wp_get_current_user();

		// Get block patterns to insert in a page.
		ob_start();

		include_once PUBLISHER_MEDIA_KIT_BLOCK_PATTERS . 'cover-esperanza.php';
		include_once PUBLISHER_MEDIA_KIT_BLOCK_PATTERS . 'audience-profiles.php';
		include_once PUBLISHER_MEDIA_KIT_BLOCK_PATTERS . 'stats.php';
		include_once PUBLISHER_MEDIA_KIT_BLOCK_PATTERS . 'why-digital.php';
		include_once PUBLISHER_MEDIA_KIT_BLOCK_PATTERS . 'digital-ad-specs.php';
		include_once PUBLISHER_MEDIA_KIT_BLOCK_PATTERS . 'our-packages.php';
		include_once PUBLISHER_MEDIA_KIT_BLOCK_PATTERS . 'our-rates.php';
		include_once PUBLISHER_MEDIA_KIT_BLOCK_PATTERS . 'question-block.php';

		$pmk_page_content = ob_get_clean();

		// create post object
		$page = array(
			'post_title'   => __( 'Media Kit', 'publisher-media-kit' ),
			'post_status'  => 'draft',
			'post_author'  => $current_user->ID,
			'post_type'    => 'page',
			'post_name'    => 'media-kit',
			'post_content' => wp_kses_post( $pmk_page_content ),
		);

		// insert the post into the database
		$post_ID = wp_insert_post( $page );

		if ( is_wp_error( $post_ID ) || 0 === $post_ID ) {
			throw new \Exception( $post_ID->get_error_message() );
		}

		// insert post meta for identity.
		add_post_meta( $post_ID, 'pmk-page', 1 );

		// add media-kit page id in the database for reference.
		add_option( 'pmk-page', $post_ID );

		// Add transient to display admin notice on activation.
		set_transient( 'pmk-admin-notice', true, 5 );
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
 *
 * @return string
 */
function mce_css( $stylesheets ) {
	if ( ! empty( $stylesheets ) ) {
		$stylesheets .= ',';
	}

	return $stylesheets . PUBLISHER_MEDIA_KIT_URL . ( ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ?
			'assets/css/frontend/editor-style.css' :
			'dist/css/editor-style.css' );
}

/**
 * Add async/defer attributes to enqueued scripts that have the specified script_execution flag.
 *
 * @link https://core.trac.wordpress.org/ticket/12009
 *
 * @param string $tag The script tag.
 * @param string $handle The script handle.
 *
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
