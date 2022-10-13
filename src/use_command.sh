# shellcheck disable=SC2215
name: use
help: |-
  Choose which PHP version to use in the current session.

  Details how to use the specified PHP version, in major.minor format, within
  the current shell session.
args:
- import: src/flags/version_arg.yml
---
echo "# this file is located in 'src/use_command.sh'"
echo "# code for 'phps use' goes here"
echo "# you can edit it freely and regenerate (it will not be overwritten)"
inspect_args
