# shellcheck disable=SC2215,SC2288
name: disable
help: |-
  Remove or disable a PHP version.

  Removes a PHP version from the list of alternatives, which will prevent it
  from being displayed by 'version list', as well as prevent the ability to
  use/switch to the version, or enable/disable extensions for it.

  Typically, only use this after uninstalling the version from your computer.

  When providing a version, provide it in major.minor format.
args:
- import: src/flags/version_arg.yml
---
echo "# this file is located in 'src/version_disable_command.sh'"
echo "# code for 'phps version disable' goes here"
echo "# you can edit it freely and regenerate (it will not be overwritten)"
inspect_args
