# shellcheck disable=SC2215
name: install
help: "Install an extension for the current PHP version"
args:
- import: src/flags/extension_arg.yml
---
echo "# this file is located in 'src/ext_install_command.sh'"
echo "# code for 'phps ext install' goes here"
echo "# you can edit it freely and regenerate (it will not be overwritten)"
inspect_args
