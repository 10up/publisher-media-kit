/**
 * Admin: activating the plugin creates the Media Kit page, links to it from
 * the activation notice, and the page's blocks and images work.
 */

/**
 * WordPress dependencies
 */
import { test, expect } from '@wordpress/e2e-test-utils-playwright';
import type { RequestUtils } from '@wordpress/e2e-test-utils-playwright';

type Page = { id: number };

type Block = {
	name: string;
	isValid?: boolean;
	attributes: Record< string, unknown >;
	innerBlocks: Block[];
};

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

/**
 * Collects the image URLs on this site from a block tree, in document order.
 *
 * @param blocks  Blocks to search.
 * @param siteUrl Site URL an image must start with.
 * @return Image URLs.
 */
function collectImageUrls( blocks: Block[], siteUrl: string ): string[] {
	return blocks.flatMap( ( block ) => {
		const { url } = block.attributes;
		const own =
			typeof url === 'string' && url.startsWith( siteUrl ) ? [ url ] : [];

		return [ ...own, ...collectImageUrls( block.innerBlocks, siteUrl ) ];
	} );
}

/**
 * Collects the names of invalid blocks in a block tree.
 *
 * @param blocks Blocks to check.
 * @return Names of blocks whose saved markup doesn't validate.
 */
function collectInvalidBlocks( blocks: Block[] ): string[] {
	return blocks.flatMap( ( block ) => [
		...( block.isValid ? [] : [ block.name ] ),
		...collectInvalidBlocks( block.innerBlocks ),
	] );
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
		requestUtils,
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

		const pages = await findMediaKitPages( requestUtils );
		expect( pages ).toHaveLength( 1 );

		const link = new URL( href as string );
		expect( link.pathname ).toMatch( /\/wp-admin\/post\.php$/ );
		expect( link.searchParams.get( 'post' ) ).toBe( String( pages[ 0 ].id ) );
		expect( link.searchParams.get( 'action' ) ).toBe( 'edit' );

		const response = await page.request.get( href as string );
		expect( response.status() ).toBe( 200 );
	} );

	test( 'Media Kit page sections are valid blocks', async ( {
		admin,
		editor,
		requestUtils,
	} ) => {
		const [ mediaKitPage ] = await findMediaKitPages( requestUtils );
		expect( mediaKitPage ).toBeDefined();

		await admin.editPost( mediaKitPage.id );

		// Read the store rather than the canvas: the editor isn't iframed on
		// every supported WordPress version.
		await expect.poll( () => editor.getBlocks() ).not.toHaveLength( 0 );
		const blocks = ( await editor.getBlocks( { full: true } ) ) as Block[];

		// One top-level section per bundled pattern.
		expect( blocks ).toHaveLength( 8 );
		expect( collectInvalidBlocks( blocks ) ).toEqual( [] );
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

		await expect.poll( () => editor.getBlocks() ).not.toHaveLength( 0 );
		const urls = collectImageUrls(
			( await editor.getBlocks() ) as Block[],
			baseURL as string
		);
		expect( urls.length ).toBeGreaterThan( 0 );

		for ( const url of urls ) {
			const response = await page.request.get( url );
			expect( response.status(), url ).toBe( 200 );
		}
	} );
} );
