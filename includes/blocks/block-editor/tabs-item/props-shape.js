import PropTypes from 'prop-types';

export const propsShape = {
	attributes: PropTypes.shape({
		header: PropTypes.string.isRequired,
	}).isRequired,
};

export const editPropsShape = {
	...propsShape,
	clientId: PropTypes.string.isRequired,
	isSelected: PropTypes.bool.isRequired,
	setAttributes: PropTypes.func.isRequired,
	hasSelectedInnerBlock: PropTypes.func,
};
