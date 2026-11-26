/**
 * Admin: activating the plugin creates the Media Kit page, links to it from
 * the activation notice, and the page's pattern images load.
 */

/**
 * WordPress dependencies
 */
import { test, expect } from '@wordpress/e2e-test-utils-playwright';
import type { RequestUtils } from '@wordpress/e2e-test-utils-playwright';

type Page = { id: number };

type Block = {
	name: string;
	attributes: Record< string, unknown >;
	innerBlocks: Block[];
};

/**
 * Finds the first image URL on this site in a block tree, in document order.
 *
 * @param blocks  Blocks to search.
 * @param siteUrl Site URL the image must start with.
 * @return The image URL, if any.
 */
function findFirstImageUrl(
	blocks: Block[],
	siteUrl: string
): string | undefined {
	for ( const block of blocks ) {
		const { url } = block.attributes;
		if ( typeof url === 'string' && url.startsWith( siteUrl ) ) {
			return url;
		}

		const nested = findFirstImageUrl( block.innerBlocks, siteUrl );
		if ( nested ) {
			return nested;
		}
	}

	return undefined;
}

/**
 * Finds pages titled "Media Kit" in any status except trash.
 *
 * @param requestUtils Request utilities.
 * @return Matching pages.
 */
function findMediaKitPages( requestUtils: RequestUtils ): Promise< Page[] > {
	return requestUtils.rest( {
		path: '/wp/v2/pages',
		params: {
			search: 'Media Kit',
			status: 'publish,future,draft,pending,private',
			per_page: 100,
		},
	} );
}

test.describe( 'Media Kit page created on activation', () => {
	test.describe.configure( { mode: 'serial' } );

	test.beforeAll( async ( { requestUtils } ) => {
		// Trash (not delete) any existing page so activation must create one.
		for ( const { id } of await findMediaKitPages( requestUtils ) ) {
			await requestUtils.rest( {
				method: 'DELETE',
				path: `/wp/v2/pages/${ id }`,
			} );
		}
	} );

	test( 'activation notice links to the new Media Kit page', async ( {
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

		const href = await page
			.locator( '.updated.notice' )
			.getByRole( 'link', { name: 'click here' } )
			.getAttribute( 'href' );
		expect( href ).toBeTruthy();

		const response = await page.request.get( href as string );
		expect( response.status() ).toBe( 200 );
	} );

	test( 'Media Kit page images load', async ( {
		admin,
		editor,
		page,
		requestUtils,
		baseURL,
	} ) => {
		const [ mediaKitPage ] = await findMediaKitPages( requestUtils );
		expect( mediaKitPage ).toBeDefined();

		await admin.editPost( mediaKitPage.id );

		// Read the URL from the block attributes rather than the canvas: the
		// editor isn't iframed on every supported WordPress version.
		await expect.poll( () => editor.getBlocks() ).not.toHaveLength( 0 );
		const src = findFirstImageUrl( await editor.getBlocks(), baseURL as string );
		expect( src ).toBeDefined();

		const response = await page.request.get( src as string );
		expect( response.status() ).toBe( 200 );
	} );
} );
