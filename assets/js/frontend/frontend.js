// import foo from './components/bar';
import Tabs from '@10up/component-tabs';

const orientation = document.querySelector('.tabs-vertical');

// eslint-disable-next-line no-new
new Tabs('.tabs', {
	orientation: orientation ? 'vertical' : 'horizontal',
});
