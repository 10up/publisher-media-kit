/**
 * Entry point file to register this block.
 */

/**
 * Internal dependencies
 */
import { registerBlock } from '../../utils/register-block';
import tabs from './index';

// Register the block
// wp.domReady is required for core filters to work with this custom block. See - https://github.com/WordPress/gutenberg/issues/9757
wp.domReady(function () {
	registerBlock(tabs);
});
