/* global */
const { registerBlockType } = wp.blocks;

export const registerBlock = (block) => {
	if (!block) {
		return;
	}

	const { name, settings } = block;
	registerBlockType(name, settings);
};
