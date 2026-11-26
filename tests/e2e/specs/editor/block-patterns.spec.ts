/**
 * Editor: the Media Kit patterns are offered in their own inserter category.
 */

/**
 * WordPress dependencies
 */
import { test, expect } from '@wordpress/e2e-test-utils-playwright';

test.describe( 'Media Kit block patterns', () => {
	test.afterEach( async ( { requestUtils } ) => {
		await requestUtils.deleteAllPosts();
	} );

	test( 'offers the cover pattern in the Publisher Media Kit category', async ( {
		admin,
		editor,
		page,
	} ) => {
		// What admin.createNewPost() does, minus its wait for the iframed
		// canvas: the editor isn't iframed on every supported WordPress version.
		await admin.visitAdminPage( 'post-new.php', 'post_title=Test+Block+Pattern' );
		await editor.setPreferences( 'core/edit-post', {
			welcomeGuide: false,
			fullscreenMode: false,
		} );

		// WordPress 6.6 labels the toggle "Toggle block inserter"; later
		// releases label it "Block Inserter".
		await page
			.getByRole( 'region', { name: 'Editor top bar' } )
			.getByRole( 'button', { name: /^(Toggle block inserter|Block Inserter)$/ } )
			.click();

		const library = page.getByRole( 'region', { name: 'Block Library' } );
		await library.getByRole( 'tab', { name: 'Patterns' } ).click();
		await library.getByRole( 'tab', { name: 'Publisher Media Kit' } ).click();

		await expect(
			page.getByRole( 'option', { name: 'Publisher Media Kit - Cover' } )
		).toBeVisible();
	} );
} );
