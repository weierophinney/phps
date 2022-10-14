# shellcheck disable=SC2215,SC2168,SC2154
name: disable
help: "Disable a previously enabled extension for the current PHP version."
args:
- import: src/flags/extension_arg.yml
---
local extension="${args[extension]}"
local version

version="$(get_env_php_version)"

green "Disabling extension ${extension} for PHP version ${version}"

if ! extension_disable "${version}" "${extension}"; then
    red "An error occurred disabling the extension."
    red "Please review the output for details."
    exit 1
fi
