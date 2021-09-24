/**
 * Tabs
 */

/**
 * WordPress dependencies
 */

/* eslint-disable import/no-extraneous-dependencies */
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import edit from './edit';
import save from './save';
import metadata from './block.json';
import transforms from './transforms';

const { name } = metadata;

const labels = {
	title: __('Tabs', '10up-block-library'),
	description: __('Add tabbed content.', '10up-block-library'),
};

export default {
	name,
	settings: {
		...metadata,
		...labels,
		icon: (
			<svg
				width="125"
				height="118"
				viewBox="0 0 125 118"
				fill="none"
				xmlns="http://www.w3.org/2000/svg"
			>
				<path
					fillRule="evenodd"
					clipRule="evenodd"
					d="M33.0127 6H6V24.9508H33.0127V6ZM0 0V30.9508H39.0127V0H0Z"
					fill="#404040"
				/>
				<path
					fillRule="evenodd"
					clipRule="evenodd"
					d="M76.0064 6H48.9937V24.9508H76.0064V6ZM42.9937 0V30.9508H82.0064V0H42.9937Z"
					fill="#404040"
				/>
				<path
					fillRule="evenodd"
					clipRule="evenodd"
					d="M119 6H91.9872V24.9508H119V6ZM85.9872 0V30.9508H125V0H85.9872Z"
					fill="#404040"
				/>
				<path
					fillRule="evenodd"
					clipRule="evenodd"
					d="M119 40.0992H6V111.099H119V40.0992ZM0 34.0992V117.099H125V34.0992H0Z"
					fill="#404040"
				/>
			</svg>
		),
		edit,
		save,
		transforms,
	},
};
