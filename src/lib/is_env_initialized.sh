#########################################
## Determine if the alternatives file has been initialized
##
## Status Code:
##   0 if successful
##   1 if unsuccessful
#########################################
is_env_initialized() {
    if [[ ! -f "${HOME}/.local/var/lib/alternatives/php" ]]; then
        return 1;
    fi

    return 0;
}
