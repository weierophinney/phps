# shellcheck disable=SC2215,SC2168,SC2154
name: install
help: "Install an extension for the current PHP version"
args:
- import: src/flags/extension_arg.yml
---
local extension="${args[extension]}"
local version
local config

version="$(get_env_php_version)"

green "Installing extension ${extension} for PHP version ${version}"

if ! sudo pecl -d "php_suffix=${version}" install "${extension}" && sudo pecl uninstall -r "${extension}"; then
    red "An error occurred; please review the output for details"
    exit 1
fi

config="$(extension_config_template)"
# shellcheck disable=SC2059
if ! printf "${config}" "${extension}" "${extension}" | sudo tee "/etc/php/${version}/mods-available/${extension}.ini"; then
    red "An error occurred creating the extension configuration file."
    red "Please review the output for details."
    exit 1
fi

if ! extension_enable "${version}" "${extension}"; then
    red "An error occurred enabling the extension."
    red "Please review the output for details."
    exit 1
fi
