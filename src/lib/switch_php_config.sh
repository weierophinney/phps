#########################################
## Switch the php-config alternative
##
## Arguments:
##   $1: PHP version
##
## Status Code:
##   0 if successful
##   1 if unsuccessful
#########################################
switch_php_config() {
    local command
    local version=$1
    command="$(alternatives_command)"

    green "Switching php-config binary to version ${version}"
    if ! $command --set php-config "/usr/bin/php-config${version}"; then
        red "Error switching php-config binary version to ${version}!"
        return 1
    fi

    return 0
}
