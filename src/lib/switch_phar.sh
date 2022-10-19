#########################################
## Switch the phar alternative
##
## Arguments:
##   $1: PHP version
##
## Status Code:
##   0 if successful
##   1 if unsuccessful
#########################################
switch_phar() {
    local command
    local version=$1
    command="$(alternatives_command)"

    green "Switching phar binary to version ${version}"
    if ! $command --set phar "/usr/bin/phar${version}"; then
        red "Error switching phar binary version to ${version}!"
        return 1
    fi

    return 0
}
