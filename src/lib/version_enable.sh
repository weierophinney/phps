#########################################
## Enable a PHP version in the alternatives
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
    local priority="${version//./0}"
    local alternativesFile
    local binary
    local template

    green "Enabling PHP version ${version}"

    if ! is_env_initialized; then
        if ! init_env; then
            return 1
        fi
    fi

    for type in php php-config phpize phar; do
        alternativesFile="${HOME}/.local/var/lib/alternatives/${type}"
        binary="/usr/bin/${type}${version}"

        # shellcheck disable=SC2059
        case $type in
            php)
                template="$(printf "$(alternatives_template_php)" "${HOME}")"
                ;;
            php-config)
                template="$(printf "$(alternatives_template_php_config)" "${HOME}")"
                ;;
            phpize)
                template="$(printf "$(alternatives_template_phpize)" "${HOME}")"
                ;;
            phar)
                template="$(printf "$(alternatives_template_phar)" "${HOME}")"
                ;;
        esac

        green "Enabling ${type} binary for PHP version ${version}"

        if [[ ! -f "${binary}" ]]; then
            red "Unable to find ${type} binary for version ${version}; executable '${binary}' not found"
            return 1
        fi

        if grep -q "${binary}" "${alternativesFile}"; then
            green "Binary already registered!"
        else
            file_trim_trailing_lines "${alternativesFile}"
            if [[ "$(cat "${alternativesFile}")" == "${template}" ]]; then
                printf "\n\n%s\n%s\n\n" "${binary}" "${priority}" >> "${alternativesFile}"
            else
                printf "%s\n%s\n\n" "${binary}" "${priority}" >> "${alternativesFile}"
            fi
            green "Done!"
        fi
    done

    return 0
}
