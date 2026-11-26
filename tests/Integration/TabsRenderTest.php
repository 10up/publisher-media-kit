<?php
/**
 * Tests the front-end markup of the Tabs blocks.
 *
 * @package PublisherMediaKit
 */

namespace PublisherMediaKit\Tests\Integration;

use WP_UnitTestCase;

/**
 * Tests how a Tabs block and its Tabs Item blocks render: the tab navigation,
 * the panels it points to, and theme template overrides.
 */
class TabsRenderTest extends WP_UnitTestCase {

	/**
	 * Clears the cached theme paths so locate_template() reads them again
	 * without the fixture theme filter.
	 */
	public function tear_down(): void {
		$this->reset_theme_path_globals();

		parent::tear_down();
	}

	/**
	 * Each tab in the navigation points (by href and aria-controls) at the
	 * panel holding that tab's content, in the same order, under the title.
	 */
	public function test_tab_navigation_links_point_to_matching_panels(): void {
		$html = do_blocks(
			$this->tabs_markup(
				array( 'tabsTitle' => 'Ad Specs' ),
				array(
					'Print Ads'   => 'Print content',
					'Digital Ads' => 'Digital content',
				)
			)
		);

		$this->assertStringContainsString( '<h2 class="tab-title">Ad Specs</h2>', $html );

		preg_match_all( '/<a href="#([^"]+)" role="tab" aria-controls="([^"]+)">([^<]*)<\/a>/', $html, $links, PREG_SET_ORDER );
		preg_match_all( '/<div class="tab-content[^"]*" id="([^"]+)" role="tabpanel">\s*(.*?)\s*<\/div>/s', $html, $panels, PREG_SET_ORDER );

		$this->assertCount( 2, $links, 'There is one navigation link per tab.' );
		$this->assertCount( 2, $panels, 'There is one panel per tab.' );

		$expected = array(
			array( 'Print Ads', 'Print content' ),
			array( 'Digital Ads', 'Digital content' ),
		);
		foreach ( $expected as $index => list( $header, $content ) ) {
			list( , $href, $controls, $label )  = $links[ $index ];
			list( , $panel_id, $panel_content ) = $panels[ $index ];

			$this->assertSame( $header, $label );
			$this->assertSame( $panel_id, $href, "The \"{$header}\" link must point at its panel." );
			$this->assertSame( $panel_id, $controls, "The \"{$header}\" link must control its panel." );
			$this->assertStringContainsString( $content, $panel_content );
		}
	}

	/**
	 * Tab headers and the tabs title are rendered as text, and the panel id
	 * is a slug of the header.
	 */
	public function test_tab_header_and_title_are_rendered_as_text(): void {
		$html = do_blocks(
			$this->tabs_markup(
				array( 'tabsTitle' => '<img src=x onerror=y>' ),
				array( 'A <b onclick=x>B</b> "q" & C' => 'Content' )
			)
		);

		$this->assertStringContainsString( '<h2 class="tab-title">&lt;img src=x onerror=y&gt;</h2>', $html );
		$this->assertStringContainsString(
			'<a href="#tab-item-a-b-q-c" role="tab" aria-controls="tab-item-a-b-q-c">A &lt;b onclick=x&gt;B&lt;/b&gt; &quot;q&quot; &amp; C</a>',
			$html
		);
		$this->assertStringContainsString( 'id="tab-item-a-b-q-c" role="tabpanel"', $html );
		$this->assertStringNotContainsString( '<img', $html );
		$this->assertStringNotContainsString( '<b onclick', $html );
	}

	/**
	 * A theme's `partials/blocks/tabs-item/markup.php` replaces the plugin's
	 * Tabs Item template and receives the block's attributes and content.
	 */
	public function test_theme_template_overrides_tabs_item_markup(): void {
		$theme_dir = __DIR__ . '/fixtures/theme';
		add_filter(
			'stylesheet_directory',
			static function () use ( $theme_dir ) {
				return $theme_dir;
			}
		);
		$this->reset_theme_path_globals();

		$html = do_blocks( $this->tabs_markup( array(), array( 'Packages' => 'Package content' ) ) );

		$this->assertMatchesRegularExpression(
			'#<section class="theme-tab" data-header="Packages">.*Package content.*</section>#s',
			$html
		);
		$this->assertStringNotContainsString( 'role="tabpanel"', $html, 'The plugin template must not render as well.' );
	}

	/**
	 * Builds serialized block markup for a Tabs block.
	 *
	 * @param array    $attributes Tabs block attributes.
	 * @param string[] $tabs       Paragraph content keyed by tab header.
	 * @return string Block markup.
	 */
	private function tabs_markup( array $attributes, array $tabs ): string {
		$items = array();
		foreach ( $tabs as $header => $content ) {
			$paragraph = '<p>' . esc_html( $content ) . '</p>';
			$items[]   = array(
				'blockName'    => 'tenup/tabs-item',
				'attrs'        => array( 'header' => $header ),
				'innerBlocks'  => array(
					array(
						'blockName'    => 'core/paragraph',
						'attrs'        => array(),
						'innerBlocks'  => array(),
						'innerHTML'    => $paragraph,
						'innerContent' => array( $paragraph ),
					),
				),
				'innerHTML'    => '',
				'innerContent' => array( null ),
			);
		}

		return serialize_block(
			array(
				'blockName'    => 'tenup/tabs',
				'attrs'        => $attributes,
				'innerBlocks'  => $items,
				'innerHTML'    => '',
				'innerContent' => array_fill( 0, count( $items ), null ),
			)
		);
	}

	/**
	 * Clears the theme paths locate_template() caches in globals.
	 */
	private function reset_theme_path_globals(): void {
		unset( $GLOBALS['wp_stylesheet_path'], $GLOBALS['wp_template_path'] );
	}
}
