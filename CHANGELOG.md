# Changelog

All notable changes to this project will be documented in this file, per [the Keep a Changelog standard](http://keepachangelog.com/).  Moving forward, this project will (more strictly) adhere to [Semantic Versioning](http://semver.org/).

## [Unreleased] - TBD

## [1.3.0] - 2022-09-13

### Changed
- Bump min WordPress to 5.7 and min PHP to 7.4 (props [@vikrampm1](https://github.com/vikrampm1), [@faisal-alvi](https://github.com/faisal-alvi), [@Sidsector9](https://github.com/Sidsector9) via [#96](https://github.com/10up/publisher-media-kit/pull/96))

### Security
- Bump terser from 5.14.0 to 5.14.2 (props [@dependabot](https://github.com/apps/dependabot), [@faisal-alvi](https://github.com/faisal-alvi) via [#95](https://github.com/10up/publisher-media-kit/pull/95))

## [1.2.1] - 2022-07-19

### Changed
- Updated the `CONTRIBUTING.md` file (props [@faisal-alvi](https://github.com/faisal-alvi), [@jeffpaul](https://github.com/jeffpaul) via [#89](https://github.com/10up/publisher-media-kit/pull/89))

### Fixed
- Bring back the `/assets/images` directory to avoid broken images (props [@dkotter](https://github.com/dkotter), [@kmwilkerson](https://github.com/kmwilkerson), [@faisal-alvi](https://github.com/faisal-alvi) via [#91](https://github.com/10up/publisher-media-kit/pull/91))

## [1.2.0] - 2022-06-27
### Added
- Dependency security scanning. (props [@jeffpaul](https://github.com/jeffpaul), [@faisal-alvi](https://github.com/faisal-alvi) via [#84](https://github.com/10up/publisher-media-kit/pull/84))

### Changed
- Bump WordPress "tested up to" version 6.0. (props [@ajmaurya99](https://github.com/ajmaurya99), [@faisal-alvi](https://github.com/faisal-alvi), [@vikrampm1](https://github.com/vikrampm1) via [#83](https://github.com/10up/publisher-media-kit/pull/83))

### Security
- Upgrade 10up-toolkit to v4 (props [@jeffpaul](https://github.com/jeffpaul), [@faisal-alvi](https://github.com/faisal-alvi), [@Sidsector9](https://github.com/Sidsector9) via [#85](https://github.com/10up/publisher-media-kit/pull/85))
- Bump eventsource from 1.1.0 to 1.1.1 (props [@dependabot[bot]](https://github.com/apps/dependabot), [@jeffpaul](https://github.com/jeffpaul) via [#86](https://github.com/10up/publisher-media-kit/pull/86))

## [1.1.0] - 2022-04-20
### Added
- Added mobile styling and fixed linting errors (props [@cldhmmr](https://github.com/cldhmmr), [@treykane](https://github.com/treykane), [@faisal-alvi](https://github.com/faisal-alvi), [@sudip-10up](https://github.com/sudip-10up), [@jeffpaul](https://github.com/jeffpaul) via [#72](https://github.com/10up/publisher-media-kit/pull/72))

### Changed
- WP Version bump to 5.9 (props [@faisal-alvi](https://github.com/faisal-alvi), [@jeffpaul](https://github.com/jeffpaul), [@sudip-10up](https://github.com/sudip-10up), [@ankitguptaindia](https://github.com/ankitguptaindia) via [#64](https://github.com/10up/publisher-media-kit/pull/64))
- Compatible tabs with the upstream version 1.0.2 (props [@faisal-alvi](https://github.com/faisal-alvi), [@jeffpaul](https://github.com/jeffpaul) via [#81](https://github.com/10up/publisher-media-kit/pull/81))

### Fixed
- Fix responsive issues of media kit page (props [@mehidi258](https://github.com/mehidi258), [@sudip-10up](https://github.com/sudip-10up) [@faisal-alvi](https://github.com/faisal-alvi), [@jeffpaul](https://github.com/jeffpaul) via [#52](https://github.com/10up/publisher-media-kit/pull/52))
- Fix eslint errors (props [@mehidi258](https://github.com/mehidi258), [@faisal-alvi](https://github.com/faisal-alvi), [@jeffpaul](https://github.com/jeffpaul) via [#61](https://github.com/10up/publisher-media-kit/pull/61))
- Fix editor styles to match frontend design (props [@mehidi258](https://github.com/mehidi258), [@ankitguptaindia](https://github.com/ankitguptaindia), [@faisal-alvi](https://github.com/faisal-alvi), [@jeffpaul](https://github.com/jeffpaul) via [#58](https://github.com/10up/publisher-media-kit/pull/58))
- Fix audience profiles border width (props [@mehidi258](https://github.com/mehidi258), [@Sidsector9](https://github.com/Sidsector9), [@ankitguptaindia](https://github.com/ankitguptaindia), [@faisal-alvi](https://github.com/faisal-alvi), [@jeffpaul](https://github.com/jeffpaul) via [#59](https://github.com/10up/publisher-media-kit/pull/59))
- Tab Title is editable from below the tab (props [@faisal-alvi](https://github.com/faisal-alvi), [@peterwilsoncc](https://github.com/peterwilsoncc), [@kdo](https://github.com/kdo), [@jeffpaul](https://github.com/jeffpaul) via [#63](https://github.com/10up/publisher-media-kit/pull/63))

### Removed
- The Orientation Options are removed (props [@faisal-alvi](https://github.com/faisal-alvi), [@peterwilsoncc](https://github.com/peterwilsoncc) via [#65](https://github.com/10up/publisher-media-kit/pull/65))

### Security
- Bump `nanoid` from 3.1.25 to 3.2.0 (props [@dependabot](https://github.com/apps/dependabot) via [#67](https://github.com/10up/publisher-media-kit/pull/67))
- Bump `follow-redirects` from 1.14.4 to 1.14.8 (props [@dependabot](https://github.com/apps/dependabot) via [#68](https://github.com/10up/publisher-media-kit/pull/68) & [#69](https://github.com/10up/publisher-media-kit/pull/69))
- Bump `url-parse` from 1.5.3 to 1.5.10 (props [@dependabot](https://github.com/apps/dependabot) via [#70](https://github.com/10up/publisher-media-kit/pull/70) & [#71](https://github.com/10up/publisher-media-kit/pull/71))

## [1.0.0] - 2021-12-10
- Initial public plugin release 🎉

## [0.9.0] - 2021-12-01
- Initial private plugin release 🎉

[Unreleased]: https://github.com/10up/publisher-media-kit/compare/trunk...develop
[1.2.1]: https://github.com/10up/publisher-media-kit/compare/1.2.0...1.2.1
[1.2.0]: https://github.com/10up/publisher-media-kit/compare/1.1.0...1.2.0
[1.1.0]: https://github.com/10up/publisher-media-kit/compare/1.0.0...1.1.0
[1.0.0]: https://github.com/10up/publisher-media-kit/compare/0.9.0...1.0.0
[0.9.0]: https://github.com/10up/publisher-media-kit/tree/0.9.0
