#########################################
## Switch the phpize alternative
##
## Arguments:
##   $1: PHP version
##
## Status Code:
##   0 if successful
##   1 if unsuccessful
#########################################
switch_phpize() {
    local command
    local version=$1
    command="$(alternatives_command)"

    green "Switching phpize binary to version ${version}"
    if ! $command --set phpize "/usr/bin/phpize${version}"; then
        red "Error switching phpize binary version to ${version}!"
        return 1
    fi

    return 0
}
