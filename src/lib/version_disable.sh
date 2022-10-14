#########################################
## Disable a PHP version in the alternatives
##
## Arguments:
##   $1 PHP version to disable
##
## Outputs:
##   Echoes status messages and prompts to STDOUT
##
## Status Codes:
##   0 on success
##   1 on failure
#########################################
version_disable() {
    local version=$1
    local alternativesFile="${HOME}/.local/var/lib/alternatives/php"
    local binary="/usr/bin/php${version}"
    local command
    local tmpfile

    green "Disabling PHP ${version}"

    if ! is_env_initialized; then
        red "You do not appear to have initialized your environment yet."
        echo "Please run 'init', followed by at least one invocation of 'version enable'"
        echo "before attempting to disable a version."
        return 1
    fi

    binary="/usr/bin/php${version}"
    if ! grep -q "${binary}" "${alternativesFile}"; then
        green "Version does not appear to be registered; nothing to do"
        return 0
    fi

    tmpfile="$(mktemp)"
    command="$(printf '{gsub("\\\\n%s\\\\n[0-9]+",""); print}' "${binary}")"
    if ! awk -v RS="\0" -v ORS="" "${command}" "${alternativesFile}" > "${tmpfile}"; then
        red "The attempt to disable the version failed"
        echo "You will need to manually edit the file ${alternativesFile} to remove the entry for ${binary}"
        return 1
    fi

    mv "${tmpfile}" "${alternativesFile}"
    green "Done!"
    return 0
}
