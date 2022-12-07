<?php
/**
 * Tabs Block Context
 *
 * @package PublisherMediaKit\Blocks
 */

/*
 * Please not the lowercase B in the blocks portion of the namespace.
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
namespace PublisherMediaKit\blocks\BlockContext;

/**
 * Block registry
 */
class Tabs {

	/**
	 * Singleton instance
	 *
	 * @var $instance Plugin Singleton plugin instance
	 */
	public static $instance = null;

	/**
	 * Lazy initialize the plugin
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new Tabs();
		}

		return self::$instance;
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function setup() {
		add_filter( 'render_block', [ $this, 'render_tab_navigations' ], 10, 2 );
	}

	/**
	 * Add Tab Controls
	 *
	 * @param  string $block_content The block content.
	 * @param  array  $block The block data.
	 * @return string String of rendered HTML.
	 */
	public function render_tab_navigations( $block_content, $block ) {

		// Bail early if not Tabs block
		if ( 'tenup/tabs' !== $block['blockName'] ) {
			return $block_content;
		}

		if ( $block['innerBlocks'] ) {

			$tabs_title = $block['attrs']['tabsTitle'] ?? '';
			// Add tab navigation controls
			$tabs = '<div class="tab-control"><div class="tabs-header">
				<h2 class="tab-title">' . esc_html( $tabs_title ) . '</h2>
				<ul class="tab-list" role="tablist">';

			if ( is_array( $block['innerBlocks'] ) ) {
				foreach ( $block['innerBlocks'] as $inner_block ) {

					$header = $inner_block['attrs']['header'];
					$id     = 'tab-item-' . sanitize_title_with_dashes( $header );

					$tabs .= '<li class="tab-item">
						<a href="#' . esc_attr( $id ) . '" role="tab" aria-controls="' . esc_attr( $id ) . '">' . esc_html( $header ) . '</a>
					</li>';
				}
				$tabs .= '</ul></div></div>';
			}

			$block_content = str_replace( '<!-- Tabs Placeholder -->', $tabs, $block_content );

		}

		return $block_content;
	}
}
