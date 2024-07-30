<?php
/**
 * Plugin Name:       Publisher Media Kit
 * Plugin URI:        https://github.com/10up/publisher-media-kit
 * Description:       Pre-configured Media Kit Page using Gutenberg Block Patterns.
 * Version:           1.3.5
 * Requires at least: 6.4
 * Requires PHP:      7.4
 * Author:            10up
 * Author URI:        https://10up.com
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       publisher-media-kit
 * Domain Path:       /languages
 *
 * @package           PublisherMediaKit
 */

// Useful global constants.
define( 'PUBLISHER_MEDIA_KIT_VERSION', '1.3.5' );
define( 'PUBLISHER_MEDIA_KIT_URL', plugin_dir_url( __FILE__ ) );
define( 'PUBLISHER_MEDIA_KIT_PATH', plugin_dir_path( __FILE__ ) );
define( 'PUBLISHER_MEDIA_KIT_BLOCKS_PATH', plugin_dir_path( __FILE__ ) . 'includes/blocks/block-editor/' );
define( 'PUBLISHER_MEDIA_KIT_INC', PUBLISHER_MEDIA_KIT_PATH . 'includes/' );
define( 'PUBLISHER_MEDIA_KIT_BLOCK_PATTERS', PUBLISHER_MEDIA_KIT_PATH . 'includes/block-patterns/' );

if ( ! is_readable( __DIR__ . '/10up-lib/wp-compat-validation-tool/src/Validator.php' ) ) {
	return;
}

require_once '10up-lib/wp-compat-validation-tool/src/Validator.php';

$compat_checker = new \PublisherMediaKitValidator\Validator();
$compat_checker
	->set_plugin_name( 'Publisher Media Kit' )
	->set_php_min_required_version( '7.4' );

if ( ! $compat_checker->is_plugin_compatible() ) {
	return;
}

// Require Composer autoloader if it exists.
if ( file_exists( PUBLISHER_MEDIA_KIT_PATH . 'vendor/autoload.php' ) ) {
	require_once PUBLISHER_MEDIA_KIT_PATH . 'vendor/autoload.php';
}

// Include files.
require_once PUBLISHER_MEDIA_KIT_INC . '/core.php';
// Block Editor
require_once PUBLISHER_MEDIA_KIT_INC . '/blocks.php';

// Activation/Deactivation.
register_activation_hook( __FILE__, '\PublisherMediaKit\Core\activate' );
register_deactivation_hook( __FILE__, '\PublisherMediaKit\Core\deactivate' );

// Bootstrap.
PublisherMediaKit\Core\setup();
// Blocks
PublisherMediaKit\Blocks\setup();

/*
 * Please note the lowercase B in the blocks portion of the namespace.
 *
 * Due to an earlier typo in the blocks folder name (it uses a lower case b),
 * that part of the namespace is lowercase. This is to avoid breaking existing
 * code that may be referencing this file directly.
 *
 * Namespaces are case insensitive whereas file systems can be case sensitive so
 * the namespace case was modified to match the folder name.
 *
 * @see https://github.com/10up/publisher-media-kit/issues/118
 */
PublisherMediaKit\blocks\BlockContext\Tabs::get_instance()->setup();
