import PropTypes from 'prop-types';

export const propsShape = {
	attributes: PropTypes.shape({
		contentID: PropTypes.number,
		curationMode: PropTypes.string,
		contentTag: PropTypes.number,
	}).isRequired,
};

export const editPropsShape = {
	...propsShape,
	setAttributes: PropTypes.func.isRequired,
};

export const panelPropShape = {
	...propsShape,
	setAttributes: PropTypes.func.isRequired,
};
