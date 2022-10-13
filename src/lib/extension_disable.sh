#########################################
## Disable an extension for a given PHP vresion
##
## Arguments:
##   $1: PHP version
##   $2: Extension to disable
##
## Status Code:
##   0 if successful
##   1 if unsuccessful
#########################################
extension_disable() {
    local version=$1
    local extension=$2
    if sudo phpdismod -v "${version}" -s cli "${extension}"; then
        return 0
    else
        return 1
    fi
}
