/* global */
const { registerBlockType } = wp.blocks;
const { createHooks } = wp.hooks;

const hooks = createHooks();

export const registerBlock = (block) => {
	if (!block) {
		return;
	}

	const { name, settings } = block;
	registerBlockType(name, settings);
};

/**
 * Register each block
 *
 * @param {Array} blocks Array of block component to register.
 */
export const registerTenupBlocks = (blocks) => {
	if (!blocks || !Array.isArray(blocks)) {
		return;
	}

	/**
	 * Apply a filter to the blocks being registered.
	 */
	const filteredBlocks = hooks.applyFilters('tenup_register_blocks', blocks);

	filteredBlocks.forEach(registerBlock);
};

// Sample to use
// hooks.addFilter( 'tenup_register_blocks', 'myApp', ( blocks ) => {
// 	return blocks.filter( ( { name } ) => {
// 		return 'tenup/panel' !== name;
// 	});
// } );
