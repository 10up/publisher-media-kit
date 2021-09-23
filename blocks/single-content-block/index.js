/**
 * WordPress dependencies
 */

import { registerBlockType, createBlock } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';
import { Button, PanelBody, ToggleControl, NavigableMenu } from '@wordpress/components';
import { compose, ifCondition } from '@wordpress/compose';
import { useState, useEffect, Fragment } from '@wordpress/element';
import { withSelect, withDispatch } from '@wordpress/data';
import { InnerBlocks, InspectorControls } from '@wordpress/block-editor';
import { applyFilters } from '@wordpress/hooks';


import block from './block.json';

registerBlockType(
	"publisher-media-kit/single-content-block", {
		title: "Single Content Block",
		category: 'common',
		icon: 'smiley',
		edit: (props) => {

			const {
				className,
				insertBlock,
				clientId,
				selectBlock,
			} = props;

			const [editTab, setEditTab] = useState('');

			const onSelect = (tabName) => {
				// Set selected tab
				setEditTab(tabName);
				selectBlock(tabName);
			};

			withDispatch((dispatch) => {
				const { selectBlock, insertBlock, removeBlock } = dispatch('core/block-editor');
				return {
					selectBlock: (id) => selectBlock(id),
					insertBlock,
					removeBlock,
				};
			});

			const resetEditing = () => {
				const isEditing = document.querySelectorAll(
					`#block-${clientId} > .wp-block-tenup-tabs .wp-block[data-is-tab-header-editing]`,
				);
				if (isEditing) {
					isEditing.forEach((block) =>
						// eslint-disable-next-line prettier/prettier, react/prop-types
						block.removeAttribute('data-is-tab-header-editing')
					);
				}
			};

		return (
			<Fragment>
				<NavigableMenu>
					<Button
						className="add-tab-button"
						icon="plus"
						label={__('Add New Tab', '10up-block-library')}
						onClick={() => {
							// eslint-disable-next-line react/prop-types
							const created = createBlock(
								'core/paragraph',
								{
									header: '',
								},
								// eslint-disable-next-line prettier/prettier
								[
									createBlock(
										'core/paragraph',
									),
								]
							);
							insertBlock(created, undefined, clientId);
							resetEditing();
							onSelect(created.clientId);
						}}
					/>
				</NavigableMenu>
				<div className={className}>
					<h1>Hello Editor!</h1>
				</div>
			</Fragment>
			);
		},
		save: () => {
			return (
				<div>
					<h1>Hello Editor2!</h1>
				</div>
			);
		}
	}
);
