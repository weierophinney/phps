#########################################
## Switch the PHP version alternative
##
## Arguments:
##   $1: PHP version
##
## Status Code:
##   0 if successful
##   1 if unsuccessful
#########################################
switch_php() {
    local command
    local version=$1
    command="$(alternatives_command)"

    green "Switching PHP binary to version ${version}"
    if ! $command --set php "/usr/bin/php${version}"; then
        red "Error switching PHP binary version to ${version}!"
        return 1
    fi

    return 0
}
