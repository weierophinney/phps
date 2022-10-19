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
local status=0
local supported_versions

if [[ "${version}" == "auto" ]]; then
    version="$(alternatives_get_best_version)"
fi

supported_versions="$(alternatives_get_supported_versions)"
if [[ "${supported_versions}" != *"${version}"* ]]; then
    red "Unsupported version; must be one of:"
    red "${supported_versions}"
    exit 1
else
    if ! switch_php "${version}"; then
        status=1
    fi

    if ! switch_phar "${version}"; then
        status=1
    fi

    if ! switch_php_config "${version}"; then
        status=1
    fi

    if ! switch_phpize "${version}"; then
        status=1
    fi

    if [[ $status != 0 ]]; then
        red "There were one or more errors switching to version ${version}!"
        echo "Please review the logs for details"
        exit $status
    fi
fi
