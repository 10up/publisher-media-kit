/**
 * WordPress dependencies
 */
import { applyFilters } from '@wordpress/hooks';

/**
 * Ensures that children is always an array so we can spread.
 *
 * @param {(Function|Array)} children The child component(s)
 *
 * @return {Array} The list of children
 */
function prepareChildren(children) {
	return !Array.isArray(children) ? [children] : children;
}

/**
 * Create a filtered area component
 *
 * @param {string} filterName The name of the filter to create
 * @return {Function} The component
 */
export default function createFilterableComponent(filterName) {
	return ({ children, blockProps }) => {
		return applyFilters(filterName, prepareChildren(children), blockProps);
	};
}
