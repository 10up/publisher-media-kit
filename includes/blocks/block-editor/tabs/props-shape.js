/**
 * External dependencies
 */
import PropTypes from 'prop-types';

export const propsShape = {
	attributes: PropTypes.shape({
		tabVertical: PropTypes.boolean,
	}).isRequired,
	clientId: PropTypes.string.isRequired,
};

export const editPropsShape = {
	...propsShape,
	isSelected: PropTypes.bool.isRequired,
	setAttributes: PropTypes.func.isRequired,
};

export const panelPropShape = {
	...propsShape,
	setAttributes: PropTypes.func.isRequired,
};
