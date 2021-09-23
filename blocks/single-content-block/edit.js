/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { InspectorControls, BlockControls, AlignmentToolbar } from '@wordpress/block-editor';
import { select } from '@wordpress/data';

/**
 * Internal dependencies
 */
import { editPropsShape } from './props-shape';
import { ContentOptions } from './inspector';

//import { getContentBlocks } from '../../../assets/js/admin/helpers/helpers';

const contentListingBlocks = document.querySelectorAll(
	'.wp-block-dmg-eliza-content-listing-block',
);
const singleContentBlocks = document.querySelectorAll(
	'.wp-block-dmg-eliza-single-content-block',
);
const getContentBlocks = [];

// Content listing blocks
for (const cLBlock of contentListingBlocks) {
	getContentBlocks.push(cLBlock.parentElement.dataset.block);
}
// Single content blocks
for (const scBlock of singleContentBlocks) {
	getContentBlocks.push(scBlock.parentElement.dataset.block);
}

//import DelayedServerSideRender from '../../components/delayed-serverside-render';

const DelayedServerSideRender = [];

// Content listing blocks
for (const cLBlock of contentListingBlocks) {
	DelayedServerSideRender.push(cLBlock.parentElement.dataset.block);
}
// Single content blocks
for (const scBlock of singleContentBlocks) {
	DelayedServerSideRender.push(scBlock.parentElement.dataset.block);
}


/**
 * Edit component.
 * See https://wordpress.org/gutenberg/handbook/designers-developers/developers/block-api/block-edit-save/#edit
 *
 * @param {Object}   props                        The block props.
 * @param {Object}   props.attributes             Block attributes.
 * @param {string}   props.clientId               ID of the block.
 * @param {Function} props.setAttributes          Sets the value for block attributes.
 * @param {string}   props.className              Class name for the block.
 * @return {Function} Render the edit screen
 */
const SingleContentBlockEdit = ({ attributes, setAttributes, className, clientId }) => {
	const { curationMode, contentID, contentTag, alignment } = attributes;
	const blocks = getContentBlocks();

	let render = true;
	if (
		(curationMode === 'manual' && !contentID) ||
		(curationMode === 'automatic' && !contentTag)
	) {
		render = false;
	}

	// Counter used to increment the delay time depending upon number of calls being made for initial render.
	let { contentBlockCounter } = window.publisherMediaKitAdmin.blockEditor;
	// Need to execute this workflow only for the initial load, where number of rendered existing content/content-listing blocks will be ZERO.
	if (blocks.length === 0) {
		contentBlockCounter = window.publisherMediaKitAdmin.blockEditor.contentBlockCounter++;
	} else {
		contentBlockCounter = 0;
	}

	let isFirstBlock = 0;
	const firstBlock = select('core/block-editor').getBlocks()[0];
	if (firstBlock && firstBlock.clientId === clientId) {
		isFirstBlock = 1;
	}

	return (
		<div className={className}>
			<InspectorControls>
				<ContentOptions attributes={attributes} setAttributes={setAttributes} />
			</InspectorControls>
			<BlockControls>
				<AlignmentToolbar
					alignmentControls={[
						{
							title: __('Align left', 'publisher-media-kit'),
							align: 'left',
							icon: 'editor-alignleft',
						},
						{
							title: __('Align right', 'publisher-media-kit'),
							align: 'right',
							icon: 'editor-alignright',
						},
					]}
					value={alignment}
					onChange={(alignment) => setAttributes({ alignment })}
					describedBy={__('Layout alignment', 'publisher-media-kit')}
				/>
			</BlockControls>
			{render ? (
				<DelayedServerSideRender
					block="publisher-media-kit/single-content-block"
					attributes={attributes}
					urlQueryArgs={{
						edit: 1,
						currentBlockId: clientId,
						blocksIds: JSON.stringify(blocks),
						isFirstBlock,
					}}
					delay={contentBlockCounter * 50}
				/>
			) : (
				<div className="components-placeholder is-large">
					<div className="components-placeholder__fieldset">
						{__('Please configure content settings to render the block.', 'publisher-media-kit')}
					</div>
				</div>
			)}
		</div>
	);
};

SingleContentBlockEdit.propTypes = {
	...editPropsShape,
};

export default SingleContentBlockEdit;
