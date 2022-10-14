# shellcheck disable=SC2215,SC2288,SC2168,SC2154
name: disable
help: |-
  Remove or disable a PHP version.

  Removes a PHP version from the list of alternatives, which will prevent it
  from being displayed by 'version list', as well as prevent the ability to
  use/switch to the version, or enable/disable extensions for it.

  Typically, only use this after uninstalling the version from your computer.

  When providing a version, provide it in major.minor format.
args:
- import: src/flags/version_arg.yml
---
local version="${args[version]}"
local alternativesFile="${HOME}/.local/var/lib/alternatives/php"
local binary
local command
local tmpfile

if ! is_env_initialized; then
    red "You do not appear to have initialized your environment yet."
    echo "Please run 'init', followed by at least one invocation of 'version enable'"
    echo "before attempting to disable a version."
    exit 1
fi

binary="/usr/bin/php${version}"
if ! grep -q "${binary}" "${alternativesFile}"; then
    green "Version does not appear to be registered"
else
    tmpfile="$(mktemp)"
    command="$(printf '{gsub("\\\\n%s\\\\n[0-9]+",""); print}' "${binary}")"
    if ! awk -v RS="\0" -v ORS="" "${command}" "${alternativesFile}" > "${tmpfile}"; then
        red "The attempt to remove the version failed"
        echo "You will need to manually edit the file ${alternativesFile} to remove the entry for ${binary}"
        exit 1
    fi

    mv "${tmpfile}" "${alternativesFile}"
    green "Done!"
fi
