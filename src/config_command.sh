# shellcheck disable=SC2215,SC2168
name: config
help: |-
  Configure the current PHP version.

  Opens the php.ini associated with the current PHP version in \$EDITOR as root.
---
local version
local config_file
local editor

version=$(get_env_php_version)
config_file=$(get_config_file "${version}")
create_config_file "${version}" "${config_file}"
editor="${EDITOR:-"vim"}"

sudo "${editor}" "${config_file}"
