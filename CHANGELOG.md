# Changelog

All notable changes to this project will be documented in this file, in reverse chronological order by release.

## 2.1.0 - 2022-10-19

### Changed

The utility now manages the alternatives for each of the php-config, phpize, and phar binaries.
Any PHP installation, enable operation, disable operation, or switch operation will now update the alternatives for these binaries in addition to the PHP binary.

## 2.0.0 - 2022-10-14

### Changed

The utility was rewritten using [Bashly](https://bashly.dannyb.co/), to eliminate the need to have a PHP version installed in order to use it.

Additionally, instead of using `:` to separate subcommands, it now uses a space:

- `phps ext:disable` becomes `phps ext disable`
- `phps ext:enable` becomes `phps ext enable`
- `phps ext:install` becomes `phps ext install`
- `phps repo:add` becomes `phps repo add`
- `phps php:disable` becomes `phps php disable`
- `phps php:enable` becomes `phps php enable`
- `phps php:install` becomes `phps php install`
- `phps php:list` becomes `phps php list`
- `phps php:uninstall` becomes `phps php uninstall`

Finally, installation now requires only copying or symlinking the `phps` binary in the repository root into your `$PATH`.

## 1.0.0 - 2019-12-03

### Added

- All features

### Changed

- Nothing.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- Nothing.

