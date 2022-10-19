# shellcheck disable=SC2215,SC2168
name: init
help: |-
  Prepare your environment to manage different PHP versions.

  It performs the following tasks:

  - Initializes the packages.sury.org repository, if it is not already.
  - Creates files that allow the user update-alternatives settings to know which PHP binaries it should manage.
---
local alternativesDir="${HOME}/.local/var/lib/alternatives"
local status=0
local alternativesFile
local type

for type in php php-config phpize phar; do
    alternativesFile="${alternativesDir}/${type}"
    if [[ -f "${alternativesFile}" ]]; then
        green "Alternative for ${type} binary is already prepared!"
    elif ! init_env_for_type "${type}"; then
        status=1
    fi
done

if [[ $status == 1 ]]; then
    red "One or more errors when initializing the environment; please refer to the logs"
    exit 1
fi
