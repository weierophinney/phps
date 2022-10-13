# shellcheck disable=SC2215
name: enable
help: |-
  Enable a newly installed PHP version.

  Enables a previously installed PHP version, exposing it to allow switching to
  and from the new version.

  When providing a version, provide it in major.minor format.
args:
- import: src/flags/version_arg.yml
---
echo "# this file is located in 'src/version_enable_command.sh'"
echo "# code for 'phps version enable' goes here"
echo "# you can edit it freely and regenerate (it will not be overwritten)"
inspect_args
