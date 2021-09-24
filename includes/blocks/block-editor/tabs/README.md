# (Has Known Issues) Tabs

**This block has known bugs that are still being solved.**

Add tabbed content to the block editor. By default the tabs block accepts any block type as content. You can filter `allowedBlocks` to accept custom block types using `tenup.tabs.allowedBlocks` filter.

## Filters

* **tenup.tabs.allowedBlocks** - By default set to `[]` to allow all block types.
* **tenup.tabs.header** - Provides access to the area **above** the `InnerBlocks `instance of the Tabs block.
* **tenup.tabs.footer** - Provides access to the area **below** the `InnerBlocks `instance of the Tabs block.
* **tenup.tabsItem.header** - Provides access to the area **above** the `InnerBlocks `instance of the TabsItem block.
* **tenup.tabsItem.footer** - Provides access to the area **below** the `InnerBlocks `instance of the TabsItem block.
* **tenup.tabs.showOrientationOption** - By default is true. Set to false to remove orientation option.

## Known Issues

__Bug 1 (Safari only)__: There is a small delay when moving tabs in Safari. WordPress applies an animation when an inner block is moved. Right now there is no way to disable that animation. https://github.com/WordPress/gutenberg/issues/30299

__Bug 2 (Safari only)__: Switching tabs when an inner block is selected results in a flash of two tabs being shown. This is a bug in Gutenburg core. Block re-rendering is not triggered correctly in Safari when the user selects different blocks. https://github.com/WordPress/gutenberg/issues/30249
