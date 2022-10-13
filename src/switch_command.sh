# shellcheck disable=SC2215,SC2168,SC2154
name: switch
help: |-
  Choose which PHP version to use by default.

  Switches to the provided PHP version, in major.minor format, for use as the
  default version across all shells.
args:
- import: src/flags/version_arg.yml
---
local version="${args[version]}"
local supported_versions
local command

if [[ "${version}" == "auto" ]]; then
    version="$(alternatives_get_best_version)"
fi

supported_versions="$(alternatives_get_supported_versions)"
if [[ "${supported_versions}" != *"${version}"* ]]; then
    red "Unsupported version; must be one of:"
    red "${supported_versions}"
    exit 1
else
    command="$(alternatives_command)"
    if $command --set php "/usr/bin/php${version}"; then
        green "Now using PHP version ${version}"
        green "If you have previously aliased to a specific version, run:"
        echo "    unalias php"
    else
        red "Unable to switch PHP version"
        exit 1
    fi
fi
