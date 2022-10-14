# shellcheck disable=SC2215,SC2168,SC2154
name: enable
help: "Enable an extension for the current PHP version."
args:
- import: src/flags/extension_arg.yml
---
local extension="${args[extension]}"
local version

version="$(get_env_php_version)"

green "Enabling extension ${extension} for PHP version ${version}"

if ! extension_enable "${version}" "${extension}"; then
    red "An error occurred enabling the extension."
    red "Please review the output for details."
    exit 1
fi
