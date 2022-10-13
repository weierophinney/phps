#########################################
## Get the currently selected PHP version
##
## Outputs:
##   Writes the discovered version to STDOUT
#########################################
get_env_php_version() {
    /usr/bin/env php -v | head -n1 | sed -E 's/^php ([1-9]+\.[0-9]+).*$/\1/i'
}
