#########################################
## Create the php.ini for a given PHP version if it does not yet exist
##
## Arguments:
##   $1: PHP version
##   $2: Path to version-specific php.ini file
##
## Outputs:
##   Echoes status messages and prompts to STDOUT
#########################################
create_config_file() {
    local version=$1
    local path=$2

    if ! [[ -f "${path}" ]];then
        blue "Creating ${path}"
        echo "(you may have to provide your password)"
        config_template | sudo tee "${path}"

        blue "Registering phps for PHP ${version}..."
        echo "(you may have to provide your password)"
        sudo phpenmod -v "${version}" -s cli phps
    fi
}

