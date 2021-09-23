/**
 * WordPress dependencies
 */
import { PanelBody } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import { panelPropShape } from './props-shape';
import PostSelector from '../../components/post-selector';
import PostsPreview from '../../components/posts-preview';
import TermSelector from '../../components/term-selector';
import TermPreview from '../../components/term-preview';

/**
 * Content options.
 *
 * @param {*} options Function arguments.
 * @param {*} options.setAttributes State change function.
 * @param {*} options.attributes Attributes.
 * @param {*} options.attributes.curationMode Content mode attributes.
 * @param {*} options.attributes.contentID Content ID attributes.
 * @param {*} options.attributes.contentTag Content tags attributes.
 *
 * @return {*} JSX element.
 */
export const ContentOptions = ({
	setAttributes,
	attributes: { curationMode, contentID, contentTag },
}) => {
	const manual = 'manual';
	const automatic = 'automatic';
	const postTypes = ['post'];

	return (
		<PanelBody title={__('Content settings', 'publisher-media-kit')} className="dmg-sc-block-panel">
			<div>
				<input
					name="dmg-sc-mode"
					id="dmg-sc-mode-manual"
					type="radio"
					value={manual}
					checked={curationMode === manual}
					onChange={(e) => setAttributes({ curationMode: e.target.value })}
				/>
				<label htmlFor="dmg-sc-mode-manual">
					{__('Manually select content', 'publisher-media-kit')}
				</label>
				{curationMode === manual && (
					<div className="dmg-sc-manual-option">
						<PostsPreview
							posts={[contentID]}
							onUpdate={(items) => {
								if (items && Array.isArray(items) && items.length) {
									setAttributes({ contentID: items[0] });
								} else {
									setAttributes({ contentID: 0 });
								}
							}}
							postTypes={postTypes}
						/>
						<PostSelector
							placeholder={__('Search for content…', 'publisher-media-kit')}
							onSelect={(v, i) => {
								setAttributes({ contentID: i.id });
							}}
							postTypes={postTypes}
						/>
					</div>
				)}
				<div className="dmg-sc-mode-wrap">
					<input
						name="dmg-sc-mode"
						id="dmg-sc-mode-auto"
						type="radio"
						value={automatic}
						checked={curationMode === automatic}
						onChange={(e) => setAttributes({ curationMode: e.target.value })}
					/>
					<label htmlFor="dmg-sc-mode-auto">
						{__('Automatically populate', 'publisher-media-kit')}
					</label>
					{curationMode === automatic && (
						<div className="dmg-sc-automatic-option">
							<TermSelector
								onSelect={(v, i) => {
									setAttributes({ contentTag: i.id });
								}}
								placeholder={__('Search terms', 'publisher-media-kit')}
							/>
							<TermPreview
								label={__('Term selected', 'publisher-media-kit')}
								terms={contentTag ? [contentTag] : []}
								onUpdate={(items) => {
									if (items && Array.isArray(items) && items.length) {
										setAttributes({ contentTag: items[0] });
									} else {
										setAttributes({ contentTag: 0 });
									}
								}}
							/>
						</div>
					)}
				</div>
			</div>
		</PanelBody>
	);
};
ContentOptions.propTypes = {
	...panelPropShape,
};
