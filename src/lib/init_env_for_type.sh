#########################################
## Initialize the alternatives environment for a specific binary type
##
## Arguments:
##   $1: Binary type (one of php, phpize, php-config, or phar)
##
## Outputs:
##   Echoes status messages and prompts to STDOUT
##
## Status Codes:
##   0 on success
##   1 on failure
#########################################
init_env_for_type() {
    local type=$1
    local alternativesDir="${HOME}/.local/var/lib/alternatives"
    local alternativesFile="${alternativesDir}/${type}"
    local prompt

    blue "Do you want to create the file ${alternativesFile} to handle your ${type} binary?"

    read -r prompt
    if [[ "${prompt}" =~ ^[y|Y] ]]; then
        if [[ ! -d "${alternativesDir}" ]]; then
            mkdir -p "${alternativesDir}"
        fi

        # shellcheck disable=SC2059
        case $type in
            php)
                printf "$(alternatives_template_php)" "${HOME}" > "${alternativesFile}"
                ;;
            php-config)
                printf "$(alternatives_template_php_config)" "${HOME}" > "${alternativesFile}"
                ;;
            phpize)
                printf "$(alternatives_template_phpize)" "${HOME}" > "${alternativesFile}"
                ;;
            phar)
                printf "$(alternatives_template_phar)" "${HOME}" > "${alternativesFile}"
                ;;
        esac

        green "Alternatives file created for ${type} binary"
        return 0
    else
        red "Aborting"
        return 1
    fi
}

