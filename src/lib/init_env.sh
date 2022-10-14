#########################################
## Initialize the alternatives environment
##
## Outputs:
##   Echoes status messages and prompts to STDOUT
##
## Status Codes:
##   0 on success
##   1 on failure
#########################################
init_env() {
    local alternativesDir="${HOME}/.local/var/lib/alternatives"
    local alternativesFile="${alternativesDir}/php"
    local prompt

    blue "Do you want to create the file ${alternativesFile} to handle your PHP versions?"
    read -r prompt
    if [[ "${prompt}" =~ ^[y|Y] ]]; then
        if [[ ! -d "${alternativesDir}" ]]; then
            mkdir -p "${alternativesDir}"
        fi

        # shellcheck disable=SC2059
        printf "$(alternatives_template)" "${HOME}" > "${alternativesFile}"

        green "Alternatives file created"
        return 0
    else
        red "Aborting"
        return 1
    fi
}
