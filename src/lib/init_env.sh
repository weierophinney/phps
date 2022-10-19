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
    local status=0
    local type

    for type in php phpize php-config phar;do
        if ! init_env_for_type "${type}"; then
            status=1
        fi
    done

    return $status
}
