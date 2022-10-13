# shellcheck disable=SC2215
name: switch
help: |-
  Choose which PHP version to use by default.

  Switches to the provided PHP version, in major.minor format, for use as the
  default version across all shells.
args:
- import: src/flags/version_arg.yml
---
echo "# this file is located in 'src/switch_command.sh'"
echo "# code for 'phps switch' goes here"
echo "# you can edit it freely and regenerate (it will not be overwritten)"
inspect_args
