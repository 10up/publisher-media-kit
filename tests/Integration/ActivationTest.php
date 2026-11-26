<?php
/**
 * Tests the Media Kit page created on activation.
 *
 * @package PublisherMediaKit
 */

namespace PublisherMediaKit\Tests\Integration;

use WP_UnitTestCase;

/**
 * Tests who can create the Media Kit page on activation, and that it is
 * created only once.
 */
class ActivationTest extends WP_UnitTestCase {

	/**
	 * An administrator activating the plugin gets one draft "Media Kit" page,
	 * flagged with the `pmk-page` meta and recorded in the `pmk-page` option.
	 * Activating again reuses that page instead of creating another.
	 */
	public function test_administrator_activation_creates_one_draft_media_kit_page(): void {
		wp_set_current_user( $this->create_plugin_administrator() );

		\PublisherMediaKit\Core\activate();

		$page_ids = $this->get_media_kit_page_ids();
		$this->assertCount( 1, $page_ids );

		$page = get_post( $page_ids[0] );
		$this->assertSame( 'page', $page->post_type );
		$this->assertSame( 'draft', $page->post_status );
		$this->assertSame( 'Media Kit', $page->post_title );
		$this->assertSame( $page->ID, (int) get_option( 'pmk-page' ) );

		\PublisherMediaKit\Core\activate();

		$this->assertSame( $page_ids, $this->get_media_kit_page_ids() );
	}

	/**
	 * A user who cannot activate plugins does not create the page.
	 */
	public function test_activation_by_user_without_activate_plugins_creates_no_page(): void {
		wp_set_current_user( self::factory()->user->create( array( 'role' => 'editor' ) ) );

		\PublisherMediaKit\Core\activate();

		$this->assertSame( array(), $this->get_media_kit_page_ids() );
		$this->assertFalse( get_option( 'pmk-page' ) );
	}

	/**
	 * Creates a user who can activate plugins on this install.
	 *
	 * On multisite, `activate_plugins` also needs `manage_network_plugins`
	 * unless the network enables the Plugins menu for site admins, so the
	 * administrator is made a super admin there.
	 *
	 * @return int User ID.
	 */
	private function create_plugin_administrator(): int {
		$user_id = self::factory()->user->create( array( 'role' => 'administrator' ) );

		if ( is_multisite() ) {
			grant_super_admin( $user_id );
		}

		return $user_id;
	}

	/**
	 * Gets the IDs of all pages flagged as the Media Kit page, in any status.
	 *
	 * @return int[] Page IDs.
	 */
	private function get_media_kit_page_ids(): array {
		return get_posts(
			array(
				'post_type'      => 'page',
				'post_status'    => 'any',
				'posts_per_page' => 10,
				'fields'         => 'ids',
				'meta_key'       => 'pmk-page', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
			)
		);
	}
}
