# shellcheck disable=SC2215,SC2168,SC2154
name: use
help: |-
  Choose which PHP version to use in the current session.

  Details how to use the specified PHP version, in major.minor format, within
  the current shell session.
args:
- import: src/flags/version_arg.yml
---
local version="${args[version]}"
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
    green "Cut and paste the following line to temporarily switch to the selected PHP version:"
    echo "    alias php=/usr/bin/php${version}"
fi
