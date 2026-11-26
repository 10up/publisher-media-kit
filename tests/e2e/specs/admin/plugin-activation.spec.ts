/**
 * Admin: the plugin can be deactivated and activated again from the Plugins
 * screen.
 */

/**
 * WordPress dependencies
 */
import { test, expect } from '@wordpress/e2e-test-utils-playwright';

test.describe( 'Plugin activation', () => {
	test( 'can activate the plugin after deactivating it', async ( {
		admin,
		page,
	} ) => {
		await admin.visitAdminPage( 'plugins.php' );

		await page
			.getByRole( 'link', { name: 'Deactivate Publisher Media Kit' } )
			.click();
		await page
			.getByRole( 'link', { name: 'Activate Publisher Media Kit' } )
			.click();

		await expect(
			page.getByRole( 'link', { name: 'Deactivate Publisher Media Kit' } )
		).toBeVisible();
	} );
} );
