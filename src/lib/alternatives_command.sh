#########################################
## Get the update-alternatives command string
##
## Outputs:
##   Writes the command string to STDOUT
#########################################
alternatives_command() {
    echo "update-alternatives --altdir ${HOME}/.local/etc/alternatives --admindir ${HOME}/.local/var/lib/alternatives"
}
