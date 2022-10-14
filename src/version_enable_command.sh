# shellcheck disable=SC2215,SC2154,SC2168
name: enable
help: |-
  Enable a newly installed PHP version.

  Enables a previously installed PHP version, exposing it to allow switching to
  and from the new version.

  When providing a version, provide it in major.minor format.
args:
- import: src/flags/version_arg.yml
---
local version="${args[version]}"
local alternativesFile="${HOME}/.local/var/lib/alternatives/php"
local binary="/usr/bin/php${version}"
local priority="${version//./0}"

green "Enabling PHP version ${version}"

if [[ ! -f "${binary}" ]]; then
    red "Unable to find binary for version ${version}; executable '${binary}' not found"
    exit 1
fi

if ! is_env_initialized; then
   init_command
fi

if grep -q "${binary}" "${alternativesFile}"; then
    green "Binary already registered!"
else
    file_trim_trailing_lines "${alternativesFile}"
    printf "%s\n%s\n\n" "${binary}" "${priority}" >> "${alternativesFile}"
    green "Done!"
fi
