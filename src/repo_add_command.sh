# shellcheck disable=SC2215,SC2288,SC1010
name: add
help: |-
  Add the packages.sury.org repository to your apt sources list.

  It first checks to see if a list entry already exists. If so, it aborts early
  with a success status.

  Otherwise, it will add the packages.sury.org repo via a new apt sources.list.d
  file, and then run apt-get update.
---
echo "# this file is located in 'src/repo_add_command.sh'"
echo "# code for 'phps repo add' goes here"
echo "# you can edit it freely and regenerate (it will not be overwritten)"
inspect_args
