# shellcheck disable=SC2215
name: install
help: |-
  Install a new PHP version.

  Installs and enables a new PHP version, with any additional packages as
  provided in the 'extension' arguments. The 'common', 'cli', and 'dev'
  packages for the given version are always installed.

  When providing a version, provide in in major.minor format.
args:
- import: src/flags/version_arg.yml
- name: extension
  help: "Which additional extensions do you wish to install? (Provide package names minus 'php{version}-' prefix)"
  required: false
  repeatable: true
---
echo "# this file is located in 'src/version_install_command.sh'"
echo "# code for 'phps version install' goes here"
echo "# you can edit it freely and regenerate (it will not be overwritten)"
inspect_args
