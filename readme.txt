=== Publisher Media Kit ===
Contributors:      10up, jeffpaul, faisal03
Tags:              media kit, audience profiles, digital ad specs, ad rates
Requires at least: 5.5
Tested up to:      5.9
Stable tag:        1.1.0
Requires PHP:      7.2
License:           GPLv2 or later
License URI:       https://www.gnu.org/licenses/gpl-2.0.html

Pre-configured Media Kit Page using Gutenberg Block Patterns.

== Description ==

Publisher Media Kit provides a quick and easy option for small to medium sized publishers to digitize their media kit.  If you are a publisher hoping for a page like the [NY Times' Advertising Standard Units](https://nytmediakit.com/standard-units), but do not have a designer or developer on staff?  Then this plugin is for you.  If you are still using a print or PDF version of your media kit to sell space on your website, but want show advertisers looking to buy digital real estate that you are a digital-forward partner?  Then this plugin is for you.

The plugin adds a new "Media Kit" page, block patterns, and placeholder content that can then be customized to fit your need (e.g., text, links, colors, images, adding page link to site footer).

== Theme Compatibility ==

We have tested the plugin with the following WordPress themes and have validated that the resulting `Media Kit` page renders as expected on the front-end and within the block editor.  Please [open an issue](https://github.com/10up/publisher-media-kit/issues/new/choose) if you find an issue with any of these themes or have an alternate, public theme with a conflict that we can help resolve.

1. [Twenty Twenty-One](https://wordpress.org/themes/twentytwentyone/)
1. [Newspack base theme](https://github.com/Automattic/newspack-theme)
1. [Newspack: Joseph](https://github.com/Automattic/newspack-theme)
1. [Newspack: Katharine](https://github.com/Automattic/newspack-theme)
1. [Newspack: Nelson](https://github.com/Automattic/newspack-theme)
1. [Newspack: Sacha](https://github.com/Automattic/newspack-theme)
1. [Newspack: Scott](https://github.com/Automattic/newspack-theme)

## Installation

1. Install the plugin via the plugin installer, either by searching for it or uploading a .ZIP file.
1. Activate the plugin.
1. Open the "Media Kit" page, edit the content to your needs, and hit publish!

== Frequently Asked Questions ==

= I accidentally deleted a block from my `Media Kit` page, how do I add it back? =

Click the block inserter (`+` button) in the top left of the block editor, click the `Patterns` tab, select `Publisher Media Kit` in the dropdown, and select the specific Block Pattern that you want to add back to your page.

= I want to add block from my `Media Kit` page on a different post/page, how do I add it outside the `Media Kit` page? =

Click the block inserter (`+` button) in the top left of the block editor, click the `Patterns` tab, select `Publisher Media Kit` in the dropdown, and select the specific Block Pattern that you want to add to your post/page.

= Why do you require WordPress 5.5 and above?  Why not 5.0 and above? =

We require WordPress 5.5 and above as this is when `register_block_pattern_category` function introduced to register Block Patterns Categories in WordPress core and it is used in the Publisher Media Kit plugin.  In fact, we recommend that you utilize the latest major version of WordPress and commit to testing and ensuring that Publisher Media Kit works on the latest WordPress version.

== Screenshots ==

1. View of block patterns and placeholder content within the block editor running the Twenty Twenty One default theme.
2. Media Kit page on frontend of site running the Twenty Twenty One default theme.
3. View of block patterns and placeholder content within the block editor running the Newspack base theme.
4. Media Kit page on frontend of site running the Newspack base theme.

== Changelog ==

= 1.0.0 =
* Initial public plugin release 🎉

= 0.9.0 =
* Initial private plugin release 🎉
