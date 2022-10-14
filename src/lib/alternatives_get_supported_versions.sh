#########################################
## Get the the list of supported PHP versions
##
## Outputs:
##   Writes the versions to STDOUT, one per line
#########################################
alternatives_get_supported_versions() {
    local command
    local alternatives
    local version

    command="$(alternatives_command)"
    mapfile -t alternatives < <($command --list php)
    for version in "${alternatives[@]}"; do
        version="$(basename "${version}")"
        echo "${version//php/}"
    done
}
