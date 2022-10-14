#########################################
## Get the path to the PHP configuration file.
##
## Creates the file if it does not yet exist.
##
## Arguments:
##   $1: PHP version to locate configuration for
##
## Outputs:
##   Writes the config file path to STDOUT
#########################################
get_config_file() {
    local version=$1
    echo "/etc/php/${version}/mods-available/phps.ini"
}
