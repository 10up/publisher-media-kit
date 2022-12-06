<?php
/**
 * Plugin Name:       Publisher Media Kit
 * Plugin URI:        https://github.com/10up/publisher-media-kit
 * Description:       Pre-configured Media Kit Page using Gutenberg Block Patterns.
 * Version:           1.3.0
 * Requires at least: 5.8
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
define( 'PUBLISHER_MEDIA_KIT_VERSION', '1.3.0' );
define( 'PUBLISHER_MEDIA_KIT_URL', plugin_dir_url( __FILE__ ) );
define( 'PUBLISHER_MEDIA_KIT_PATH', plugin_dir_path( __FILE__ ) );
define( 'PUBLISHER_MEDIA_KIT_BLOCKS_PATH', plugin_dir_path( __FILE__ ) . 'includes/blocks/block-editor/' );
define( 'PUBLISHER_MEDIA_KIT_INC', PUBLISHER_MEDIA_KIT_PATH . 'includes/' );
define( 'PUBLISHER_MEDIA_KIT_BLOCK_PATTERS', PUBLISHER_MEDIA_KIT_PATH . 'includes/block-patterns/' );

// Require Composer autoloader if it exists.
if ( file_exists( PUBLISHER_MEDIA_KIT_PATH . 'vendor/autoload.php' ) ) {
	require_once PUBLISHER_MEDIA_KIT_PATH . 'vendor/autoload.php';
}

// Include files.
require_once PUBLISHER_MEDIA_KIT_INC . '/core.php';
// Block Editor
require_once PUBLISHER_MEDIA_KIT_INC . '/blocks.php';
// Block Context
require_once PUBLISHER_MEDIA_KIT_INC . '/classes/blocks/BlockContext/Tabs.php';

// Activation/Deactivation.
register_activation_hook( __FILE__, '\PublisherMediaKit\Core\activate' );
register_deactivation_hook( __FILE__, '\PublisherMediaKit\Core\deactivate' );

// Bootstrap.
PublisherMediaKit\Core\setup();
// Blocks
PublisherMediaKit\Blocks\setup();
// Block Context
PublisherMediaKit\Blocks\BlockContext\Tabs::get_instance()->setup();
