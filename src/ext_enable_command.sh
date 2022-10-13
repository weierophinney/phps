# shellcheck disable=SC2215
name: enable
help: "Enable an extension for the current PHP version."
args:
- import: src/flags/extension_arg.yml
---
echo "# this file is located in 'src/ext_enable_command.sh'"
echo "# code for 'phps ext enable' goes here"
echo "# you can edit it freely and regenerate (it will not be overwritten)"
inspect_args
