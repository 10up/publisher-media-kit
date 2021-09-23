<?php
/**
 * Plugin Name:       Publisher Media Kit
 * Description:       Serves a Landing Page for a Publisher Media Kit with Gutenberg Block Patterns.
 * Version:           0.1.0
 * Requires at least: 4.9
 * Requires PHP:      7.2
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
define( 'PUBLISHER_MEDIA_KIT_VERSION', '0.1.0' );
define( 'PUBLISHER_MEDIA_KIT_URL', plugin_dir_url( __FILE__ ) );
define( 'PUBLISHER_MEDIA_KIT_PATH', plugin_dir_path( __FILE__ ) );
define( 'PUBLISHER_MEDIA_KIT_BLOCKS_PATH', plugin_dir_path( __FILE__ ) . 'blocks/' );
define( 'PUBLISHER_MEDIA_KIT_INC', PUBLISHER_MEDIA_KIT_PATH . 'includes/' );

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
