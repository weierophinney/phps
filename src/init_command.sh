# shellcheck disable=SC2215
name: init
help: |-
  Prepare your environment to manage different PHP versions.

  It performs the following tasks:

  - Initializes the packages.sury.org repository, if it is not already.
  - Creates files that allow the user update-alternatives settings to know which PHP binaries it should manage.
---
echo "# this file is located in 'src/init_command.sh'"
echo "# code for 'phps init' goes here"
echo "# you can edit it freely and regenerate (it will not be overwritten)"
inspect_args
