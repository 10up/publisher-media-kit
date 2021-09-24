/**
 * WordPress dependencies
 */
import { createBlock, createBlocksFromInnerBlocksTemplate } from '@wordpress/blocks';

const transforms = {
	from: [
		{
			type: 'block',
			blocks: ['tenup/accordion'],
			__experimentalConvert: (blocks) => {
				const innerBlocksTemplate = blocks.innerBlocks.map(
					({ attributes, innerBlocks }) => [
						'tenup/tabs-item',
						{ ...attributes },
						innerBlocks,
					],
				);
				return createBlock(
					'tenup/tabs',
					{},
					createBlocksFromInnerBlocksTemplate(innerBlocksTemplate),
				);
			},
		},
	],
	to: [
		{
			type: 'block',
			blocks: ['tenup/accordion'],
			__experimentalConvert: (blocks) => {
				const innerBlocksTemplate = blocks.innerBlocks.map(
					({ attributes, innerBlocks }) => [
						'tenup/accordion-item',
						{ ...attributes },
						innerBlocks,
					],
				);
				return createBlock(
					'tenup/accordion',
					{},
					createBlocksFromInnerBlocksTemplate(innerBlocksTemplate),
				);
			},
		},
	],
};

export default transforms;
