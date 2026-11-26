/**
 * Front end: a Tabs block switches the visible panel when a visitor picks a
 * tab.
 */

/**
 * WordPress dependencies
 */
import { test, expect } from '@wordpress/e2e-test-utils-playwright';

const TABS_CONTENT = [
	'<!-- wp:tenup/tabs {"tabsTitle":"Rates"} -->',
	'<!-- wp:tenup/tabs-item {"header":"Print Ads"} -->',
	'<!-- wp:paragraph --><p>Print rates</p><!-- /wp:paragraph -->',
	'<!-- /wp:tenup/tabs-item -->',
	'<!-- wp:tenup/tabs-item {"header":"Digital Ads"} -->',
	'<!-- wp:paragraph --><p>Digital rates</p><!-- /wp:paragraph -->',
	'<!-- /wp:tenup/tabs-item -->',
	'<!-- /wp:tenup/tabs -->',
].join( '' );

test.describe( 'Tabs block on the front end', () => {
	test.use( { storageState: { cookies: [], origins: [] } } );

	let link: string;

	test.beforeAll( async ( { requestUtils } ) => {
		( { link } = await requestUtils.createPage( {
			title: 'Tabs',
			content: TABS_CONTENT,
			status: 'publish',
		} ) );
	} );

	test.afterAll( async ( { requestUtils } ) => {
		await requestUtils.deleteAllPages();
	} );

	test( 'choosing a tab shows its panel and hides the others', async ( {
		page,
	} ) => {
		await page.goto( link );

		const printTab = page.getByRole( 'tab', { name: 'Print Ads' } );
		const digitalTab = page.getByRole( 'tab', { name: 'Digital Ads' } );
		const printPanel = page.locator( '#tab-item-print-ads' );
		const digitalPanel = page.locator( '#tab-item-digital-ads' );

		await expect( printTab ).toHaveAttribute( 'aria-selected', 'true' );
		await expect( printPanel ).toBeVisible();
		await expect( digitalPanel ).toBeHidden();

		await digitalTab.click();

		await expect( digitalTab ).toHaveAttribute( 'aria-selected', 'true' );
		await expect( printTab ).toHaveAttribute( 'aria-selected', 'false' );
		await expect( digitalPanel ).toBeVisible();
		await expect( digitalPanel ).toContainText( 'Digital rates' );
		await expect( printPanel ).toBeHidden();
	} );
} );
