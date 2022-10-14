# shellcheck disable=SC2215,SC2168
name: init
help: |-
  Prepare your environment to manage different PHP versions.

  It performs the following tasks:

  - Initializes the packages.sury.org repository, if it is not already.
  - Creates files that allow the user update-alternatives settings to know which PHP binaries it should manage.
---
local alternativesDir="${HOME}/.local/var/lib/alternatives"
local alternativesFile="${alternativesDir}/php"

if [[ -f "${alternativesFile}" ]]; then
    green "Your environment is already prepared!"
else
    if ! init_env; then
        exit 1
    fi
fi
