#########################################
## Initialize the alternatives environment
##
## Arguments:
##   $1 PHP version to enable
##
## Outputs:
##   Echoes status messages and prompts to STDOUT
##
## Status Codes:
##   0 on success
##   1 on failure
#########################################
version_enable() {
    local version=$1
    local alternativesFile="${HOME}/.local/var/lib/alternatives/php"
    local binary="/usr/bin/php${version}"
    local priority="${version//./0}"

    green "Enabling PHP version ${version}"

    if [[ ! -f "${binary}" ]]; then
        red "Unable to find binary for version ${version}; executable '${binary}' not found"
        return 1
    fi

    if ! is_env_initialized; then
        if ! init_env; then
            return 1
        fi
    fi

    if grep -q "${binary}" "${alternativesFile}"; then
        green "Binary already registered!"
    else
        file_trim_trailing_lines "${alternativesFile}"
        printf "%s\n%s\n\n" "${binary}" "${priority}" >> "${alternativesFile}"
        green "Done!"
    fi

    return 0
}
