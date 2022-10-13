# shellcheck disable=SC2215
name: uninstall
help: |-
  Uninstall a PHP version.

  Uninstalls and disables a PHP version, along with all packages installed for
  that PHP version.

  When providing a version, provided it in major.minor format.
args:
- import: src/flags/version_arg.yml
---
echo "# this file is located in 'src/version_uninstall_command.sh'"
echo "# code for 'phps version uninstall' goes here"
echo "# you can edit it freely and regenerate (it will not be overwritten)"
inspect_args
