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
	 * An existing Media Kit page that isn't in the trash is reused, whatever
	 * its status, so activating never creates a second page.
	 *
	 * @dataProvider data_live_page_statuses
	 *
	 * @param string $status Status of the existing Media Kit page.
	 */
	public function test_activation_reuses_existing_media_kit_page( string $status ): void {
		$existing_id = $this->create_media_kit_page( $status );
		wp_set_current_user( $this->create_plugin_administrator() );

		\PublisherMediaKit\Core\activate();

		$this->assertSame( array( $existing_id ), $this->get_media_kit_page_ids() );
		$this->assertSame( $status, get_post_status( $existing_id ) );
	}

	/**
	 * Page statuses the existing Media Kit page can have.
	 *
	 * @return array[] Test cases: status.
	 */
	public static function data_live_page_statuses(): array {
		return array(
			'published' => array( 'publish' ),
			'private'   => array( 'private' ),
			'pending'   => array( 'pending' ),
			'draft'     => array( 'draft' ),
			'scheduled' => array( 'future' ),
		);
	}

	/**
	 * A Media Kit page in the trash doesn't count: activating creates a new
	 * draft page and leaves the trashed one where it is.
	 */
	public function test_activation_ignores_trashed_media_kit_page(): void {
		$trashed_id = $this->create_media_kit_page( 'trash' );
		wp_set_current_user( $this->create_plugin_administrator() );

		\PublisherMediaKit\Core\activate();

		$page_ids = $this->get_media_kit_page_ids();
		$this->assertCount( 1, $page_ids );
		$this->assertNotSame( $trashed_id, $page_ids[0] );
		$this->assertSame( 'draft', get_post_status( $page_ids[0] ) );
		$this->assertSame( 'trash', get_post_status( $trashed_id ) );
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
	 * Creates a page flagged as the Media Kit page.
	 *
	 * @param string $status Page status.
	 * @return int Page ID.
	 */
	private function create_media_kit_page( string $status ): int {
		$page_id = self::factory()->post->create(
			array(
				'post_type'   => 'page',
				'post_title'  => 'Media Kit',
				'post_status' => $status,
				'post_date'   => 'future' === $status ? gmdate( 'Y-m-d H:i:s', time() + DAY_IN_SECONDS ) : '',
			)
		);
		add_post_meta( $page_id, 'pmk-page', 1 );

		return $page_id;
	}

	/**
	 * Gets the IDs of all non-trashed pages flagged as the Media Kit page.
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
