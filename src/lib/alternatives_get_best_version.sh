#########################################
## Get the "best" version of PHP (the default)
##
## Outputs:
##   Writes the version to STDOUT
#########################################
alternatives_get_best_version() {
    local command
    local alternatives
    local binary

    command="$(alternatives_command)"
    alternatives="$($command --query php)"

    if [[ "${alternatives}" =~ ^Best:[[:space:]]+([![:space:]]+)$ ]]; then
        binary="${BASH_REMATCH[1]}"
        binary="$(basename "${binary}")"
        echo "${binary//php/}"
    else
        # get supported versions
        alternatives="$(alternatives_get_supported_versions)"
        if [[ "${alternatives}" == "" ]]; then
            # return null if empty
            echo ""
        else
            # sort versions
            alternatives="$(echo "${alternatives}" | sort)"
        fi
        # return last
        echo "${alternatives}" | tail -n1
    fi
}
