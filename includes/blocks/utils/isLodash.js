/* global _ */
/**
 * Determines if _ is lodash or not
 *
 * @return {boolean} True if _ is lodash, and false otherwise.
 */
export default function isLodash() {
	let isLodashUsed = false;

	// If _ is defined and the function _.forEach exists then we know underscore OR lodash are in place
	if (typeof _ !== 'undefined' && typeof _.forEach === 'function') {
		// A small sample of some of the functions that exist in lodash but not underscore
		const funcs = ['get', 'set', 'at', 'cloneDeep'];

		// Simplest if assume exists to start
		isLodashUsed = true;

		// eslint-disable-next-line func-names
		funcs.forEach(function (func) {
			// If just one of the functions do not exist, then not lodash
			isLodashUsed = typeof _[func] !== 'function' ? false : isLodashUsed;
		});
	}

	if (isLodashUsed) {
		// We know that lodash is loaded in the _ variable
		return true;
	}
	// We know that lodash is NOT loaded
	return false;
}
